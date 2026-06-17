<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';

// Zaten girişli ise dashboard'a gönder
if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/' . ADMIN_SLUG . '/dashboard');
    exit;
}

$hata = '';
$ip = istemciIP();

// --- Brute-force kontrol ---
$st = $db->prepare("SELECT COUNT(*) FROM giris_loglari WHERE ip=? AND basarili=0 AND tarih >= (NOW() - INTERVAL ? MINUTE)");
$st->execute([$ip, LOGIN_KILIT_DK]);
$basarisiz = (int)$st->fetchColumn();
$kilitli = $basarisiz >= LOGIN_DENEME_LIMIT;

if (!$kilitli && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['_csrf'] ?? null)) {
        $hata = 'Oturum hatası. Lütfen sayfayı yenileyin.';
    } else {
        $kullanici = trim($_POST['kullanici'] ?? '');
        $sifre     = $_POST['sifre'] ?? '';

        $u = getOne('admin', 'kullanici=? AND durum=1', [$kullanici]);
        if ($u && password_verify($sifre, $u['sifre_hash'])) {
            // Başarılı
            $db->prepare("INSERT INTO giris_loglari (ip,kullanici,basarili) VALUES (?,?,1)")->execute([$ip, $kullanici]);
            $db->prepare("UPDATE admin SET son_giris=NOW(), son_giris_ip=? WHERE id=?")->execute([$ip, $u['id']]);

            session_regenerate_id(true);
            $_SESSION['admin_id']        = (int)$u['id'];
            $_SESSION['admin_kullanici'] = $u['kullanici'];
            $_SESSION['admin_ad']        = $u['ad_soyad'];
            $_SESSION['admin_last_act']  = time();

            header('Location: ' . SITE_URL . '/' . ADMIN_SLUG . '/dashboard');
            exit;
        } else {
            // Başarısız
            $db->prepare("INSERT INTO giris_loglari (ip,kullanici,basarili) VALUES (?,?,0)")->execute([$ip, $kullanici]);
            $hata = 'Kullanıcı adı veya şifre hatalı.';
            // Yeniden say
            $st->execute([$ip, LOGIN_KILIT_DK]);
            $basarisiz = (int)$st->fetchColumn();
            if ($basarisiz >= LOGIN_DENEME_LIMIT) $kilitli = true;
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Yönetim Girişi</title>
<meta name="robots" content="noindex,nofollow">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family:'Poppins',sans-serif; background:linear-gradient(135deg,#0d3b66 0%,#14b8a6 100%); min-height:100vh; display:flex; align-items:center; }
  .login-card { background:#fff; padding:36px 32px; border-radius:18px; box-shadow:0 20px 40px rgba(0,0,0,.18); max-width:400px; width:100%; }
  .login-card .brand-mark { background:linear-gradient(135deg,#14b8a6,#0d3b66); color:#fff; padding:8px 14px; border-radius:8px; font-weight:800; letter-spacing:1px; font-size:1.1rem; }
  .form-control { border-radius:10px; padding:.7rem 1rem; }
  .btn-primary { background:#0d3b66; border-color:#0d3b66; border-radius:10px; padding:.7rem; font-weight:600; }
  .btn-primary:hover { background:#154f87; border-color:#154f87; }
</style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
      <div class="login-card">
        <div class="text-center mb-4">
          <span class="brand-mark">MHC</span>
          <h5 class="mt-3 fw-bold">Yönetim Paneli</h5>
          <small class="text-muted">Marmaris Health Center</small>
        </div>

        <?php if ($kilitli): ?>
          <div class="alert alert-danger small">
            <i class="bi bi-shield-exclamation"></i>
            Çok fazla başarısız giriş denemesi. Lütfen <strong><?= LOGIN_KILIT_DK ?> dakika</strong> sonra tekrar deneyin.
          </div>
        <?php else: ?>
          <?php if ($hata): ?><div class="alert alert-danger small"><?= e($hata) ?></div><?php endif; ?>
          <?php if ($basarisiz > 0): ?>
            <div class="alert alert-warning small">
              Başarısız deneme: <?= (int)$basarisiz ?>/<?= LOGIN_DENEME_LIMIT ?>
            </div>
          <?php endif; ?>

          <form method="post" autocomplete="off">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
              <label class="form-label">Kullanıcı Adı</label>
              <input class="form-control" name="kullanici" required autofocus>
            </div>
            <div class="mb-3">
              <label class="form-label">Şifre</label>
              <input class="form-control" name="sifre" type="password" required>
            </div>
            <button class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap</button>
          </form>
        <?php endif; ?>
      </div>
      <p class="text-center text-white-50 small mt-3">© <?= date('Y') ?> Marmaris Health Center</p>
    </div>
  </div>
</div>
</body>
</html>
