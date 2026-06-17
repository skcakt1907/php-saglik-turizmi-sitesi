<?php
$baslik = 'Galeri (Before/After)';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM galeri WHERE id=?")->execute([$id]);
    header('Location: galeri?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $veri = [
        'hizmet_id'   => (int)($_POST['hizmet_id'] ?: 0) ?: null,
        'sira'        => (int)$_POST['sira'],
        'durum'       => isset($_POST['durum'])?1:0,
        'tr_aciklama' => trim($_POST['tr_aciklama']),
        'en_aciklama' => trim($_POST['en_aciklama']),
        'de_aciklama' => trim($_POST['de_aciklama']),
    ];
    if (!empty($_FILES['oncesi']['name'])) {
        $y = dosyaKaydet($_FILES['oncesi'],'galeri'); if ($y) $veri['oncesi']=$y;
    } elseif (!empty($_POST['oncesi_url'])) $veri['oncesi'] = $_POST['oncesi_url'];
    if (!empty($_FILES['sonrasi']['name'])) {
        $y = dosyaKaydet($_FILES['sonrasi'],'galeri'); if ($y) $veri['sonrasi']=$y;
    } elseif (!empty($_POST['sonrasi_url'])) $veri['sonrasi'] = $_POST['sonrasi_url'];

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $veri['id'] = $id;
        $db->prepare("UPDATE galeri SET $set WHERE id=:id")->execute($veri);
    } else {
        $k = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $p = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $db->prepare("INSERT INTO galeri ($k) VALUES ($p)")->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: galeri?action=duzenle&id='.$id.'&ok=1'); exit;
}

$hizmetler = $db->query("SELECT id, tr_baslik FROM hizmetler")->fetchAll();

if ($action==='ekle' || $action==='duzenle') {
    $r = $id ? getOne('galeri','id=?',[$id]) : ['sira'=>0,'durum'=>1,'oncesi'=>'','sonrasi'=>'','hizmet_id'=>null];
?>
  <a href="galeri" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">İlgili Hizmet</label>
        <select class="form-select" name="hizmet_id">
          <option value="">— Yok —</option>
          <?php foreach($hizmetler as $h): ?><option value="<?= (int)$h['id'] ?>" <?= ($r['hizmet_id']==$h['id'])?'selected':'' ?>><?= e($h['tr_baslik']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-2"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum']?'checked':'' ?>></div></div>
      <div class="col-md-6"><label class="form-label">Öncesi (görsel)</label><input type="file" class="form-control form-control-sm" name="oncesi" accept="image/*"><input class="form-control form-control-sm mt-1" name="oncesi_url" value="<?= e($r['oncesi']) ?>" placeholder="veya URL"></div>
      <div class="col-md-6"><label class="form-label">Sonrası (görsel)</label><input type="file" class="form-control form-control-sm" name="sonrasi" accept="image/*"><input class="form-control form-control-sm mt-1" name="sonrasi_url" value="<?= e($r['sonrasi']) ?>" placeholder="veya URL"></div>
    </div>
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span></a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach(['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <input class="form-control" name="<?= $k ?>_aciklama" value="<?= e($r[$k.'_aciklama']??'') ?>" placeholder="Açıklama">
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT g.*, h.tr_baslik AS hizmet_ad FROM galeri g LEFT JOIN hizmetler h ON h.id=g.hizmet_id ORDER BY g.sira ASC, g.id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> görsel çifti</h5>
    <a href="galeri?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="row g-3">
    <?php foreach($rows as $r): ?>
      <div class="col-md-6 col-lg-4">
        <div class="table-card">
          <div class="d-flex gap-2 mb-2">
            <img src="<?= e(uploadURL($r['oncesi'])) ?>" style="width:50%;height:120px;object-fit:cover;border-radius:6px;">
            <img src="<?= e(uploadURL($r['sonrasi'])) ?>" style="width:50%;height:120px;object-fit:cover;border-radius:6px;">
          </div>
          <small class="text-muted"><?= e($r['hizmet_ad']??'-') ?> · sıra <?= (int)$r['sira'] ?></small>
          <p class="small mt-1 mb-2"><?= e($r['tr_aciklama']) ?></p>
          <div class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="galeri?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
            <a class="btn btn-sm btn-outline-danger" href="galeri?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
