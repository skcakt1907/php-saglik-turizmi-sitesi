<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/dil.php';
require_once __DIR__ . '/inc/helpers.php';

$basarili = false;
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['_csrf'] ?? null)) {
        $hata = t('form_hata');
    } else {
        $ad      = trim($_POST['ad']      ?? '');
        $eposta  = trim($_POST['eposta']  ?? '');
        $telefon = trim($_POST['telefon'] ?? '');
        $konu    = trim($_POST['konu']    ?? '');
        $mesaj   = trim($_POST['mesaj']   ?? '');
        // Honeypot
        $hp      = trim($_POST['website'] ?? '');

        if ($hp !== '' || $ad === '' || $mesaj === '' || !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            $hata = t('form_hata');
        } else {
            $st = $db->prepare("INSERT INTO mesajlar (ad,eposta,telefon,konu,mesaj,ip,ua,dil) VALUES (?,?,?,?,?,?,?,?)");
            $st->execute([$ad, $eposta, $telefon, $konu, $mesaj, istemciIP(), substr($_SERVER['HTTP_USER_AGENT']??'',0,250), $DIL]);
            $basarili = true;
        }
    }
}

$sayfaBaslik = t('menu_iletisim');
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_iletisim') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_iletisim') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <h3 class="text-primary"><?= t('iletisim_basligi') ?></h3>
        <p class="text-muted"><?= ayarDilli('site_slogan') ?></p>
        <ul class="list-unstyled mt-4">
          <li class="mb-3"><i class="bi bi-telephone-fill text-accent me-2"></i><a href="tel:<?= e(ayar('telefon')) ?>"><?= e(ayar('telefon')) ?></a></li>
          <li class="mb-3"><i class="bi bi-whatsapp text-success me-2"></i><a href="https://wa.me/<?= e(preg_replace('/\D/','', ayar('whatsapp'))) ?>" target="_blank">WhatsApp</a></li>
          <li class="mb-3"><i class="bi bi-envelope-fill text-accent me-2"></i><a href="mailto:<?= e(ayar('eposta')) ?>"><?= e(ayar('eposta')) ?></a></li>
          <li class="mb-3"><i class="bi bi-geo-alt-fill text-accent me-2"></i><?= e(ayarDilli('adres')) ?></li>
        </ul>
        <?php if (ayar('harita_embed')): ?>
          <div class="mt-3 rounded-4 overflow-hidden shadow-sm"><?= ayar('harita_embed') ?></div>
        <?php endif; ?>
      </div>

      <div class="col-lg-7">
        <div class="content-block">
          <h4 class="text-primary"><?= t('eposta_gonder') ?></h4>
          <?php if ($basarili): ?>
            <div class="alert alert-success"><?= t('form_basarili') ?></div>
          <?php elseif ($hata): ?>
            <div class="alert alert-danger"><?= e($hata) ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label"><?= t('form_ad') ?> *</label>
                <input class="form-control" name="ad" required value="<?= e($_POST['ad']??'') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><?= t('form_eposta') ?> *</label>
                <input class="form-control" type="email" name="eposta" required value="<?= e($_POST['eposta']??'') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><?= t('form_telefon') ?></label>
                <input class="form-control" name="telefon" value="<?= e($_POST['telefon']??'') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label"><?= t('form_konu') ?></label>
                <input class="form-control" name="konu" value="<?= e($_POST['konu']??'') ?>">
              </div>
              <div class="col-12">
                <label class="form-label"><?= t('form_mesaj') ?> *</label>
                <textarea class="form-control" name="mesaj" rows="5" required><?= e($_POST['mesaj']??'') ?></textarea>
              </div>
              <div class="col-12">
                <button class="btn btn-cta"><i class="bi bi-send me-1"></i> <?= t('gonder') ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
