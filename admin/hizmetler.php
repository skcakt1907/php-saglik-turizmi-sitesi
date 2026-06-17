<?php
$baslik = 'Sağlık Hizmetleri';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);
$mesaj = '';

// --- Sil ---
if ($action === 'sil' && $id) {
    if (csrf_check($_GET['_t'] ?? null)) {
        $db->prepare("DELETE FROM hizmetler WHERE id=?")->execute([$id]);
        header('Location: hizmetler?ok=silindi'); exit;
    }
}

// --- Kaydet (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['_csrf'] ?? null)) {
    $veri = [
        'slug'      => slugify($_POST['slug'] ?: $_POST['tr_baslik']),
        'ikon'      => trim($_POST['ikon']) ?: 'bi-heart-pulse',
        'sira'      => (int)$_POST['sira'],
        'durum'     => isset($_POST['durum']) ? 1 : 0,
        'tr_baslik' => trim($_POST['tr_baslik']), 'tr_ozet' => trim($_POST['tr_ozet']), 'tr_icerik' => $_POST['tr_icerik'],
        'en_baslik' => trim($_POST['en_baslik']), 'en_ozet' => trim($_POST['en_ozet']), 'en_icerik' => $_POST['en_icerik'],
        'de_baslik' => trim($_POST['de_baslik']), 'de_ozet' => trim($_POST['de_ozet']), 'de_icerik' => $_POST['de_icerik'],
    ];

    // Görsel yükleme
    if (!empty($_FILES['gorsel']['name'])) {
        $yol = dosyaKaydet($_FILES['gorsel'], 'hizmetler');
        if ($yol) $veri['gorsel'] = $yol;
    } elseif (!empty($_POST['gorsel_url'])) {
        $veri['gorsel'] = $_POST['gorsel_url'];
    }

    if ($id) {
        $set = implode(',', array_map(fn($k) => "`$k`=:$k", array_keys($veri)));
        $st = $db->prepare("UPDATE hizmetler SET $set WHERE id=:id");
        $veri['id'] = $id;
        $st->execute($veri);
    } else {
        $kolonlar = implode(',', array_map(fn($k) => "`$k`", array_keys($veri)));
        $param    = implode(',', array_map(fn($k) => ":$k", array_keys($veri)));
        $st = $db->prepare("INSERT INTO hizmetler ($kolonlar) VALUES ($param)");
        $st->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: hizmetler?action=duzenle&id=' . $id . '&ok=kaydedildi');
    exit;
}

// --- Form (ekle/düzenle) ---
if ($action === 'ekle' || $action === 'duzenle') {
    $r = $id ? getOne('hizmetler', 'id=?', [$id]) : ['ikon'=>'bi-heart-pulse','sira'=>0,'durum'=>1,'gorsel'=>''];
    if ($action === 'duzenle' && !$r) { echo "<div class='alert alert-danger'>Bulunamadı</div>"; require_once __DIR__ . '/inc/layout-alt.php'; exit; }
?>
  <a href="hizmetler" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Listeye Dön</a>
  <?php if (isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Slug (URL)</label>
        <input class="form-control" name="slug" value="<?= e($r['slug'] ?? '') ?>" placeholder="otomatik">
      </div>
      <div class="col-md-3">
        <label class="form-label">İkon (bootstrap-icons)</label>
        <input class="form-control" name="ikon" value="<?= e($r['ikon']) ?>" placeholder="bi-heart-pulse">
      </div>
      <div class="col-md-2">
        <label class="form-label">Sıra</label>
        <input class="form-control" type="number" name="sira" value="<?= (int)$r['sira'] ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Durum</label>
        <div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum'] ? 'checked' : '' ?>> Yayında</div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Görsel</label>
        <input class="form-control form-control-sm" type="file" name="gorsel" accept="image/*">
      </div>
      <div class="col-12">
        <label class="form-label small">veya Görsel URL</label>
        <input class="form-control form-control-sm" name="gorsel_url" value="<?= e($r['gorsel'] ?? '') ?>" placeholder="https://...">
      </div>
    </div>

    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span> Türkçe</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span> English</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span> Deutsch</a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach (['tr'=>'Türkçe','en'=>'English','de'=>'Deutsch'] as $k=>$ad): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <div class="mb-2"><label class="form-label">Başlık (<?= $ad ?>)</label><input class="form-control" name="<?= $k ?>_baslik" value="<?= e($r[$k.'_baslik'] ?? '') ?>"></div>
          <div class="mb-2"><label class="form-label">Özet</label><textarea class="form-control" name="<?= $k ?>_ozet" rows="2"><?= e($r[$k.'_ozet'] ?? '') ?></textarea></div>
          <div class="mb-2"><label class="form-label">İçerik (HTML)</label><textarea class="form-control" name="<?= $k ?>_icerik" rows="8"><?= e($r[$k.'_icerik'] ?? '') ?></textarea></div>
        </div>
      <?php endforeach; ?>
    </div>

    <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Kaydet</button>
  </form>
<?php
} else {
    // --- LİSTE ---
    $rows = $db->query("SELECT * FROM hizmetler ORDER BY sira ASC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> hizmet</h5>
    <a href="hizmetler?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <?php if (isset($_GET['ok'])): ?><div class="alert alert-success">İşlem başarılı.</div><?php endif; ?>

  <div class="table-card">
    <table class="table">
      <thead><tr><th>#</th><th>Başlık (TR)</th><th>Slug</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><i class="bi <?= e($r['ikon']) ?> text-primary me-2"></i><?= e($r['tr_baslik']) ?></td>
          <td><small class="text-muted">/saglik-hizmetleri/<?= e($r['slug']) ?></small></td>
          <td><?= (int)$r['sira'] ?></td>
          <td><?= $r['durum'] ? '<span class="badge bg-success">Yayında</span>' : '<span class="badge bg-secondary">Pasif</span>' ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="hizmetler?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
            <a class="btn btn-sm btn-outline-danger" href="hizmetler?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Silinsin mi?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
