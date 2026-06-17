<?php
$baslik = 'Partner Tesisler';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM partnerler WHERE id=?")->execute([$id]);
    header('Location: partnerler?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['_csrf'] ?? null)) {
    $veri = [
        'ad'           => trim($_POST['ad']),
        'site'         => trim($_POST['site']),
        'tip'          => $_POST['tip'] ?: 'klinik',
        'sira'         => (int)$_POST['sira'],
        'durum'        => isset($_POST['durum']) ? 1 : 0,
        'tr_aciklama'  => $_POST['tr_aciklama'],
        'en_aciklama'  => $_POST['en_aciklama'],
        'de_aciklama'  => $_POST['de_aciklama'],
    ];
    if (!empty($_FILES['logo']['name'])) {
        $yol = dosyaKaydet($_FILES['logo'], 'partnerler');
        if ($yol) $veri['logo'] = $yol;
    } elseif (!empty($_POST['logo_url'])) {
        $veri['logo'] = $_POST['logo_url'];
    }

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $st = $db->prepare("UPDATE partnerler SET $set WHERE id=:id");
        $veri['id'] = $id;
        $st->execute($veri);
    } else {
        $kolonlar = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $param    = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $st = $db->prepare("INSERT INTO partnerler ($kolonlar) VALUES ($param)");
        $st->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: partnerler?action=duzenle&id=' . $id . '&ok=1'); exit;
}

if ($action === 'ekle' || $action === 'duzenle') {
    $r = $id ? getOne('partnerler','id=?',[$id]) : ['sira'=>0,'durum'=>1,'tip'=>'klinik','logo'=>''];
?>
  <a href="partnerler" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Listeye Dön</a>
  <?php if (isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Ad</label><input class="form-control" name="ad" value="<?= e($r['ad'] ?? '') ?>" required></div>
      <div class="col-md-3">
        <label class="form-label">Tip</label>
        <select class="form-select" name="tip">
          <?php foreach (['hastane','klinik','laboratuvar','sigorta','konaklama','digerleri'] as $t): ?>
            <option <?= $r['tip']===$t?'selected':'' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Web Site</label><input class="form-control" name="site" value="<?= e($r['site'] ?? '') ?>"></div>
      <div class="col-md-1"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-1"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum'] ? 'checked' : '' ?>></div></div>
      <div class="col-md-6"><label class="form-label">Logo</label><input type="file" class="form-control form-control-sm" name="logo" accept="image/*"></div>
      <div class="col-md-6"><label class="form-label">veya URL</label><input class="form-control form-control-sm" name="logo_url" value="<?= e($r['logo']) ?>"></div>
    </div>
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span></a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach (['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <textarea class="form-control" name="<?= $k ?>_aciklama" rows="4"><?= e($r[$k.'_aciklama'] ?? '') ?></textarea>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT * FROM partnerler ORDER BY sira ASC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> partner</h5>
    <a href="partnerler?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="table-card">
    <table class="table align-middle">
      <thead><tr><th>#</th><th>Logo</th><th>Ad</th><th>Tip</th><th>Site</th><th>Sıra</th><th>Aktif</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['id'] ?></td>
          <td><?php if($r['logo']): ?><img src="<?= e(uploadURL($r['logo'])) ?>" style="height:40px;"><?php endif; ?></td>
          <td><?= e($r['ad']) ?></td>
          <td><span class="badge bg-light text-dark"><?= e($r['tip']) ?></span></td>
          <td><small><?= e($r['site']) ?></small></td>
          <td><?= (int)$r['sira'] ?></td>
          <td><?= $r['durum'] ? '✅' : '—' ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="partnerler?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
            <a class="btn btn-sm btn-outline-danger" href="partnerler?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
