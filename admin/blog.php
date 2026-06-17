<?php
$baslik = 'Blog';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM blog WHERE id=?")->execute([$id]);
    header('Location: blog?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $veri = [
        'slug'    => slugify($_POST['slug'] ?: $_POST['tr_baslik']),
        'yazar'   => trim($_POST['yazar']) ?: 'Editör',
        'tarih'   => $_POST['tarih'] ?: date('Y-m-d'),
        'sira'    => (int)$_POST['sira'],
        'durum'   => isset($_POST['durum'])?1:0,
        'tr_baslik'=>trim($_POST['tr_baslik']),'tr_ozet'=>$_POST['tr_ozet'],'tr_icerik'=>$_POST['tr_icerik'],
        'en_baslik'=>trim($_POST['en_baslik']),'en_ozet'=>$_POST['en_ozet'],'en_icerik'=>$_POST['en_icerik'],
        'de_baslik'=>trim($_POST['de_baslik']),'de_ozet'=>$_POST['de_ozet'],'de_icerik'=>$_POST['de_icerik'],
    ];
    if (!empty($_FILES['kapak']['name'])) {
        $y = dosyaKaydet($_FILES['kapak'],'blog'); if ($y) $veri['kapak']=$y;
    } elseif (!empty($_POST['kapak_url'])) $veri['kapak'] = $_POST['kapak_url'];

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $veri['id'] = $id;
        $db->prepare("UPDATE blog SET $set WHERE id=:id")->execute($veri);
    } else {
        $k = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $p = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $db->prepare("INSERT INTO blog ($k) VALUES ($p)")->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: blog?action=duzenle&id='.$id.'&ok=1'); exit;
}

if ($action==='ekle' || $action==='duzenle') {
    $r = $id ? getOne('blog','id=?',[$id]) : ['sira'=>0,'durum'=>1,'kapak'=>'','yazar'=>'Editör','tarih'=>date('Y-m-d')];
?>
  <a href="blog" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($r['slug']??'') ?>"></div>
      <div class="col-md-3"><label class="form-label">Yazar</label><input class="form-control" name="yazar" value="<?= e($r['yazar']) ?>"></div>
      <div class="col-md-3"><label class="form-label">Tarih</label><input type="date" class="form-control" name="tarih" value="<?= e($r['tarih']) ?>"></div>
      <div class="col-md-1"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-1"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum']?'checked':'' ?>></div></div>
      <div class="col-md-6"><label class="form-label">Kapak</label><input type="file" class="form-control form-control-sm" name="kapak" accept="image/*"></div>
      <div class="col-md-6"><label class="form-label">veya URL</label><input class="form-control form-control-sm" name="kapak_url" value="<?= e($r['kapak']) ?>"></div>
    </div>
    <ul class="nav nav-tabs mt-4">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-tr"><span class="lang-badge">TR</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-en"><span class="lang-badge">EN</span></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-de"><span class="lang-badge">DE</span></a></li>
    </ul>
    <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom mb-3">
      <?php foreach(['tr','en','de'] as $k): ?>
        <div class="tab-pane fade <?= $k==='tr'?'show active':'' ?>" id="tab-<?= $k ?>">
          <div class="mb-2"><label class="form-label">Başlık</label><input class="form-control" name="<?= $k ?>_baslik" value="<?= e($r[$k.'_baslik']??'') ?>"></div>
          <div class="mb-2"><label class="form-label">Özet</label><textarea class="form-control" name="<?= $k ?>_ozet" rows="2"><?= e($r[$k.'_ozet']??'') ?></textarea></div>
          <div class="mb-2"><label class="form-label">İçerik (HTML)</label><textarea class="form-control" name="<?= $k ?>_icerik" rows="10"><?= e($r[$k.'_icerik']??'') ?></textarea></div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT * FROM blog ORDER BY tarih DESC, id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> yazı</h5>
    <a href="blog?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>#</th><th>Kapak</th><th>Başlık</th><th>Yazar</th><th>Tarih</th><th>Aktif</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?php if($r['kapak']): ?><img src="<?= e(uploadURL($r['kapak'])) ?>" style="height:42px;border-radius:6px;"><?php endif; ?></td>
        <td><?= e($r['tr_baslik']) ?><br><small class="text-muted">/blog/<?= e($r['slug']) ?></small></td>
        <td><small><?= e($r['yazar']) ?></small></td>
        <td><small><?= e($r['tarih']) ?></small></td>
        <td><?= $r['durum']?'✅':'—' ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="blog?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
          <a class="btn btn-sm btn-outline-danger" href="blog?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
