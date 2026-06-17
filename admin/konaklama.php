<?php
$baslik = 'Konaklama';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM konaklama WHERE id=?")->execute([$id]);
    header('Location: konaklama?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $veri = [
        'slug'        => slugify($_POST['slug'] ?: $_POST['tr_ad']),
        'yildiz'      => (int)$_POST['yildiz'],
        'konum'       => trim($_POST['konum']),
        'sira'        => (int)$_POST['sira'],
        'durum'       => isset($_POST['durum']) ? 1 : 0,
        'tr_ad'=>trim($_POST['tr_ad']), 'tr_aciklama'=>$_POST['tr_aciklama'],
        'en_ad'=>trim($_POST['en_ad']), 'en_aciklama'=>$_POST['en_aciklama'],
        'de_ad'=>trim($_POST['de_ad']), 'de_aciklama'=>$_POST['de_aciklama'],
    ];
    if (!empty($_FILES['gorsel']['name'])) {
        $yol = dosyaKaydet($_FILES['gorsel'],'konaklama'); if ($yol) $veri['gorsel'] = $yol;
    } elseif (!empty($_POST['gorsel_url'])) $veri['gorsel'] = $_POST['gorsel_url'];

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $veri['id'] = $id;
        $db->prepare("UPDATE konaklama SET $set WHERE id=:id")->execute($veri);
    } else {
        $k = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $p = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $db->prepare("INSERT INTO konaklama ($k) VALUES ($p)")->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: konaklama?action=duzenle&id='.$id.'&ok=1'); exit;
}

if ($action==='ekle' || $action==='duzenle') {
    $r = $id ? getOne('konaklama','id=?',[$id]) : ['sira'=>0,'durum'=>1,'yildiz'=>4,'gorsel'=>''];
?>
  <a href="konaklama" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($r['slug']??'') ?>"></div>
      <div class="col-md-2"><label class="form-label">Yıldız</label><input type="number" min="1" max="5" class="form-control" name="yildiz" value="<?= (int)$r['yildiz'] ?>"></div>
      <div class="col-md-3"><label class="form-label">Konum</label><input class="form-control" name="konum" value="<?= e($r['konum']??'') ?>"></div>
      <div class="col-md-2"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-2"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum']?'checked':'' ?>></div></div>
      <div class="col-md-6"><label class="form-label">Görsel</label><input type="file" class="form-control form-control-sm" name="gorsel" accept="image/*"></div>
      <div class="col-md-6"><label class="form-label">veya URL</label><input class="form-control form-control-sm" name="gorsel_url" value="<?= e($r['gorsel']) ?>"></div>
    </div>
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span></a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach(['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <div class="mb-2"><label class="form-label">Ad</label><input class="form-control" name="<?= $k ?>_ad" value="<?= e($r[$k.'_ad']??'') ?>"></div>
          <div class="mb-2"><label class="form-label">Açıklama</label><textarea class="form-control" name="<?= $k ?>_aciklama" rows="4"><?= e($r[$k.'_aciklama']??'') ?></textarea></div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT * FROM konaklama ORDER BY sira ASC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> tesis</h5>
    <a href="konaklama?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>#</th><th>Görsel</th><th>Ad</th><th>★</th><th>Konum</th><th>Sıra</th><th></th></tr></thead><tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?php if($r['gorsel']): ?><img src="<?= e(uploadURL($r['gorsel'])) ?>" style="height:40px;border-radius:6px;"><?php endif; ?></td>
        <td><?= e($r['tr_ad']) ?></td>
        <td><?= str_repeat('★',(int)$r['yildiz']) ?></td>
        <td><small><?= e($r['konum']) ?></small></td>
        <td><?= (int)$r['sira'] ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="konaklama?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
          <a class="btn btn-sm btn-outline-danger" href="konaklama?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
