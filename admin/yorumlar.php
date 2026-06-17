<?php
$baslik = 'Hasta Yorumları';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM yorumlar WHERE id=?")->execute([$id]);
    header('Location: yorumlar?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $veri = [
        'hasta_adi' => trim($_POST['hasta_adi']),
        'ulke'      => trim($_POST['ulke']),
        'video_url' => trim($_POST['video_url']),
        'puan'      => max(1, min(5, (int)$_POST['puan'])),
        'sira'      => (int)$_POST['sira'],
        'durum'     => isset($_POST['durum'])?1:0,
        'tr_yorum'  => trim($_POST['tr_yorum']),
        'en_yorum'  => trim($_POST['en_yorum']),
        'de_yorum'  => trim($_POST['de_yorum']),
    ];
    if (!empty($_FILES['foto']['name'])) {
        $y = dosyaKaydet($_FILES['foto'],'yorumlar'); if ($y) $veri['foto']=$y;
    } elseif (!empty($_POST['foto_url'])) $veri['foto'] = $_POST['foto_url'];

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $veri['id'] = $id;
        $db->prepare("UPDATE yorumlar SET $set WHERE id=:id")->execute($veri);
    } else {
        $k = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $p = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $db->prepare("INSERT INTO yorumlar ($k) VALUES ($p)")->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: yorumlar?action=duzenle&id='.$id.'&ok=1'); exit;
}

if ($action==='ekle' || $action==='duzenle') {
    $r = $id ? getOne('yorumlar','id=?',[$id]) : ['sira'=>0,'durum'=>1,'puan'=>5,'foto'=>''];
?>
  <a href="yorumlar" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Hasta Adı</label><input class="form-control" name="hasta_adi" value="<?= e($r['hasta_adi']??'') ?>" required></div>
      <div class="col-md-2"><label class="form-label">Ülke</label><input class="form-control" name="ulke" value="<?= e($r['ulke']??'') ?>"></div>
      <div class="col-md-2"><label class="form-label">Puan</label><input type="number" class="form-control" name="puan" min="1" max="5" value="<?= (int)$r['puan'] ?>"></div>
      <div class="col-md-2"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-2"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum']?'checked':'' ?>></div></div>
      <div class="col-md-6"><label class="form-label">Foto</label><input type="file" class="form-control form-control-sm" name="foto" accept="image/*"></div>
      <div class="col-md-6"><label class="form-label">veya URL</label><input class="form-control form-control-sm" name="foto_url" value="<?= e($r['foto']) ?>"></div>
      <div class="col-12"><label class="form-label">Video URL (opsiyonel — YouTube)</label><input class="form-control" name="video_url" value="<?= e($r['video_url']??'') ?>"></div>
    </div>
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span></a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach(['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <textarea class="form-control" name="<?= $k ?>_yorum" rows="4"><?= e($r[$k.'_yorum']??'') ?></textarea>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT * FROM yorumlar ORDER BY sira ASC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> yorum</h5>
    <a href="yorumlar?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>#</th><th>Foto</th><th>Hasta</th><th>Ülke</th><th>★</th><th>TR Yorum</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?php if($r['foto']): ?><img src="<?= e(uploadURL($r['foto'])) ?>" style="width:36px;height:36px;border-radius:50%;"><?php endif; ?></td>
        <td><?= e($r['hasta_adi']) ?></td>
        <td><?= e($r['ulke']) ?></td>
        <td><?= str_repeat('★',(int)$r['puan']) ?></td>
        <td><small><?= e(mb_strimwidth($r['tr_yorum'],0,60,'…')) ?></small></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="yorumlar?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
          <a class="btn btn-sm btn-outline-danger" href="yorumlar?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
