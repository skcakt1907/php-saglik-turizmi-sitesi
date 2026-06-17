<?php
$baslik = 'Doktorlar';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM doktorlar WHERE id=?")->execute([$id]);
    header('Location: doktorlar?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['_csrf'] ?? null)) {
    $veri = [
        'slug'    => slugify($_POST['slug'] ?: $_POST['tr_ad']),
        'diller'  => trim($_POST['diller']),
        'sira'    => (int)$_POST['sira'],
        'durum'   => isset($_POST['durum']) ? 1 : 0,
        'tr_ad'   => trim($_POST['tr_ad']), 'tr_unvan' => trim($_POST['tr_unvan']), 'tr_bio' => $_POST['tr_bio'],
        'en_ad'   => trim($_POST['en_ad']), 'en_unvan' => trim($_POST['en_unvan']), 'en_bio' => $_POST['en_bio'],
        'de_ad'   => trim($_POST['de_ad']), 'de_unvan' => trim($_POST['de_unvan']), 'de_bio' => $_POST['de_bio'],
    ];
    if (!empty($_FILES['foto']['name'])) {
        $yol = dosyaKaydet($_FILES['foto'], 'doktorlar');
        if ($yol) $veri['foto'] = $yol;
    } elseif (!empty($_POST['foto_url'])) {
        $veri['foto'] = $_POST['foto_url'];
    }

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $st = $db->prepare("UPDATE doktorlar SET $set WHERE id=:id");
        $veri['id'] = $id;
        $st->execute($veri);
    } else {
        $kolonlar = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $param    = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $st = $db->prepare("INSERT INTO doktorlar ($kolonlar) VALUES ($param)");
        $st->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: doktorlar?action=duzenle&id=' . $id . '&ok=1'); exit;
}

if ($action === 'ekle' || $action === 'duzenle') {
    $r = $id ? getOne('doktorlar','id=?',[$id]) : ['sira'=>0,'durum'=>1,'foto'=>'','diller'=>'TR, EN'];
?>
  <a href="doktorlar" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Listeye Dön</a>
  <?php if (isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($r['slug'] ?? '') ?>"></div>
      <div class="col-md-3"><label class="form-label">Konuştuğu Diller</label><input class="form-control" name="diller" value="<?= e($r['diller']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-2"><label class="form-label">Durum</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum'] ? 'checked' : '' ?>> Yayında</div></div>
      <div class="col-md-2"><label class="form-label">Foto</label><input type="file" class="form-control form-control-sm" name="foto" accept="image/*"></div>
      <div class="col-12"><label class="form-label small">veya Foto URL</label><input class="form-control form-control-sm" name="foto_url" value="<?= e($r['foto']) ?>"></div>
    </div>

    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span> Türkçe</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span> English</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span> Deutsch</a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach (['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <div class="mb-2"><label class="form-label">Ad Soyad</label><input class="form-control" name="<?= $k ?>_ad" value="<?= e($r[$k.'_ad'] ?? '') ?>"></div>
          <div class="mb-2"><label class="form-label">Unvan</label><input class="form-control" name="<?= $k ?>_unvan" value="<?= e($r[$k.'_unvan'] ?? '') ?>"></div>
          <div class="mb-2"><label class="form-label">Biyografi</label><textarea class="form-control" name="<?= $k ?>_bio" rows="6"><?= e($r[$k.'_bio'] ?? '') ?></textarea></div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT * FROM doktorlar ORDER BY sira ASC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> doktor</h5>
    <a href="doktorlar?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <?php if (isset($_GET['ok'])): ?><div class="alert alert-success">Tamam.</div><?php endif; ?>
  <div class="table-card">
    <table class="table align-middle">
      <thead><tr><th>#</th><th>Foto</th><th>Ad</th><th>Unvan</th><th>Diller</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?php if($r['foto']): ?><img src="<?= e(uploadURL($r['foto'])) ?>" style="width:42px;height:42px;border-radius:50%;object-fit:cover;"><?php endif; ?></td>
          <td><?= e($r['tr_ad']) ?></td>
          <td><small><?= e($r['tr_unvan']) ?></small></td>
          <td><small><?= e($r['diller']) ?></small></td>
          <td><?= (int)$r['sira'] ?></td>
          <td><?= $r['durum'] ? '<span class="badge bg-success">Yayında</span>' : '<span class="badge bg-secondary">Pasif</span>' ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="doktorlar?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
            <a class="btn btn-sm btn-outline-danger" href="doktorlar?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
