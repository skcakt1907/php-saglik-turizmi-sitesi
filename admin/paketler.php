<?php
$baslik = 'Tedavi Paketleri';
require_once __DIR__ . '/inc/layout-ust.php';

$action = $_GET['action'] ?? 'liste';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM paketler WHERE id=?")->execute([$id]);
    header('Location: paketler?ok=silindi'); exit;
}

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $veri = [
        'slug'        => slugify($_POST['slug'] ?: $_POST['tr_baslik']),
        'hizmet_id'   => (int)($_POST['hizmet_id'] ?: 0) ?: null,
        'fiyat'       => (float)str_replace(',', '.', $_POST['fiyat']),
        'para_birimi' => strtoupper(substr(trim($_POST['para_birimi']),0,3)) ?: 'EUR',
        'sure_gun'    => (int)$_POST['sure_gun'],
        'sira'        => (int)$_POST['sira'],
        'durum'       => isset($_POST['durum']) ? 1 : 0,
        'tr_baslik'=>trim($_POST['tr_baslik']),'tr_icerik'=>$_POST['tr_icerik'],
        'en_baslik'=>trim($_POST['en_baslik']),'en_icerik'=>$_POST['en_icerik'],
        'de_baslik'=>trim($_POST['de_baslik']),'de_icerik'=>$_POST['de_icerik'],
    ];
    if (!empty($_FILES['gorsel']['name'])) {
        $yol = dosyaKaydet($_FILES['gorsel'],'paketler'); if ($yol) $veri['gorsel'] = $yol;
    } elseif (!empty($_POST['gorsel_url'])) $veri['gorsel'] = $_POST['gorsel_url'];

    if ($id) {
        $set = implode(',', array_map(fn($k)=>"`$k`=:$k", array_keys($veri)));
        $veri['id'] = $id;
        $db->prepare("UPDATE paketler SET $set WHERE id=:id")->execute($veri);
    } else {
        $k = implode(',', array_map(fn($k)=>"`$k`", array_keys($veri)));
        $p = implode(',', array_map(fn($k)=>":$k", array_keys($veri)));
        $db->prepare("INSERT INTO paketler ($k) VALUES ($p)")->execute($veri);
        $id = (int)$db->lastInsertId();
    }
    header('Location: paketler?action=duzenle&id='.$id.'&ok=1'); exit;
}

$hizmetler = $db->query("SELECT id, tr_baslik FROM hizmetler ORDER BY sira ASC")->fetchAll();

if ($action==='ekle' || $action==='duzenle') {
    $r = $id ? getOne('paketler','id=?',[$id]) : ['sira'=>0,'durum'=>1,'fiyat'=>0,'para_birimi'=>'EUR','sure_gun'=>5,'gorsel'=>'','hizmet_id'=>null];
?>
  <a href="paketler" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Kaydedildi.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="table-card">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <div class="row g-3">
      <div class="col-md-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($r['slug']??'') ?>"></div>
      <div class="col-md-3"><label class="form-label">İlgili Hizmet</label>
        <select class="form-select" name="hizmet_id">
          <option value="">— Seç —</option>
          <?php foreach($hizmetler as $h): ?><option value="<?= (int)$h['id'] ?>" <?= ($r['hizmet_id']==$h['id'])?'selected':'' ?>><?= e($h['tr_baslik']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Fiyat</label><input class="form-control" name="fiyat" value="<?= e($r['fiyat']) ?>"></div>
      <div class="col-md-1"><label class="form-label">Para</label><input class="form-control" name="para_birimi" value="<?= e($r['para_birimi']) ?>" maxlength="3"></div>
      <div class="col-md-1"><label class="form-label">Gün</label><input type="number" class="form-control" name="sure_gun" value="<?= (int)$r['sure_gun'] ?>"></div>
      <div class="col-md-1"><label class="form-label">Sıra</label><input type="number" class="form-control" name="sira" value="<?= (int)$r['sira'] ?>"></div>
      <div class="col-md-1"><label class="form-label">Aktif</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="durum" <?= $r['durum']?'checked':'' ?>></div></div>
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
          <div class="mb-2"><label class="form-label">Başlık</label><input class="form-control" name="<?= $k ?>_baslik" value="<?= e($r[$k.'_baslik']??'') ?>"></div>
          <div class="mb-2"><label class="form-label">İçerik (HTML)</label><textarea class="form-control" name="<?= $k ?>_icerik" rows="6"><?= e($r[$k.'_icerik']??'') ?></textarea></div>
        </div>
      <?php endforeach; ?>
    </div>
    <button class="btn btn-primary"><i class="bi bi-save"></i> Kaydet</button>
  </form>
<?php } else {
    $rows = $db->query("SELECT p.*, h.tr_baslik AS hizmet_ad FROM paketler p LEFT JOIN hizmetler h ON h.id=p.hizmet_id ORDER BY p.sira ASC, p.id DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <h5 class="fw-bold"><?= count($rows) ?> paket</h5>
    <a href="paketler?action=ekle" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Yeni Ekle</a>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>#</th><th>Başlık</th><th>Hizmet</th><th>Fiyat</th><th>Süre</th><th>Sıra</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?= e($r['tr_baslik']) ?></td>
        <td><small><?= e($r['hizmet_ad']??'-') ?></small></td>
        <td><strong><?= e($r['para_birimi']) ?> <?= number_format((float)$r['fiyat'],0,',','.') ?></strong></td>
        <td><?= (int)$r['sure_gun'] ?> gün</td>
        <td><?= (int)$r['sira'] ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="paketler?action=duzenle&id=<?= (int)$r['id'] ?>"><i class="bi bi-pencil"></i></a>
          <a class="btn btn-sm btn-outline-danger" href="paketler?action=sil&id=<?= (int)$r['id'] ?>&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
