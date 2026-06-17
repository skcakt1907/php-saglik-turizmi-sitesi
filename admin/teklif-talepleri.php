<?php
$baslik = 'Teklif Talepleri';
require_once __DIR__ . '/inc/layout-ust.php';

$id = (int)($_GET['id'] ?? 0);

if (isset($_GET['durum']) && $id && csrf_check($_GET['_t'] ?? null)) {
    $yeniDurum = in_array($_GET['durum'], ['yeni','iletildi','kapandi'], true) ? $_GET['durum'] : 'yeni';
    $db->prepare("UPDATE teklif_talepleri SET durum=? WHERE id=?")->execute([$yeniDurum, $id]);
    header('Location: teklif-talepleri?id=' . $id); exit;
}
if (isset($_GET['islem']) && $_GET['islem']==='sil' && $id && csrf_check($_GET['_t'] ?? null)) {
    $db->prepare("DELETE FROM teklif_talepleri WHERE id=?")->execute([$id]);
    header('Location: teklif-talepleri'); exit;
}

if ($id) {
    $t = $db->prepare("SELECT t.*, h.tr_baslik AS hizmet_ad FROM teklif_talepleri t LEFT JOIN hizmetler h ON h.id=t.hizmet_id WHERE t.id=?");
    $t->execute([$id]); $r = $t->fetch();
    if (!$r) { echo "<div class='alert alert-warning'>Bulunamadı</div>"; require_once __DIR__ . '/inc/layout-alt.php'; exit; }
?>
  <a href="teklif-talepleri" class="btn btn-sm btn-outline-secondary mb-3">← Liste</a>
  <div class="table-card">
    <div class="d-flex justify-content-between mb-3">
      <h5 class="fw-bold mb-0">Teklif Talebi #<?= (int)$r['id'] ?></h5>
      <span class="badge bg-<?= ['yeni'=>'danger','iletildi'=>'warning','kapandi'=>'success'][$r['durum']] ?>"><?= e($r['durum']) ?></span>
    </div>
    <table class="table table-sm">
      <tr><th width="180">Ad Soyad</th><td><?= e($r['ad']) ?></td></tr>
      <tr><th>E-posta</th><td><a href="mailto:<?= e($r['eposta']) ?>"><?= e($r['eposta']) ?></a></td></tr>
      <tr><th>Telefon</th><td><?= e($r['telefon']) ?> <?php if($r['telefon']): ?><a class="btn btn-sm btn-success" href="https://wa.me/<?= e(preg_replace('/\D/','', $r['telefon'])) ?>" target="_blank"><i class="bi bi-whatsapp"></i></a><?php endif; ?></td></tr>
      <tr><th>Ülke</th><td><?= e($r['ulke']) ?></td></tr>
      <tr><th>İlgilendiği Hizmet</th><td><?= e($r['hizmet_ad'] ?? '-') ?></td></tr>
      <tr><th>Tarih Tercihi</th><td><?= e($r['tarih_tercih']) ?></td></tr>
      <tr><th>Mesaj</th><td><?= nl2br(e($r['mesaj'])) ?></td></tr>
      <tr><th>Form Dili</th><td><span class="badge bg-light text-dark"><?= strtoupper(e($r['dil'])) ?></span></td></tr>
      <tr><th>IP / Tarih</th><td><small class="text-muted"><?= e($r['ip']) ?> · <?= e(trTarih($r['olusturma'])) ?></small></td></tr>
    </table>
    <hr>
    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-outline-warning" href="teklif-talepleri?id=<?= $id ?>&durum=iletildi&_t=<?= e(csrf_token()) ?>">İletildi olarak işaretle</a>
      <a class="btn btn-sm btn-outline-success" href="teklif-talepleri?id=<?= $id ?>&durum=kapandi&_t=<?= e(csrf_token()) ?>">Kapandı</a>
      <a class="btn btn-sm btn-outline-danger ms-auto" href="teklif-talepleri?id=<?= $id ?>&islem=sil&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Sil?')"><i class="bi bi-trash"></i> Sil</a>
    </div>
  </div>
<?php } else {
    $filtre = $_GET['filtre'] ?? 'tumu';
    $where  = $filtre === 'yeni' ? "WHERE t.durum='yeni'" : '';
    $rows = $db->query("SELECT t.*, h.tr_baslik AS hizmet_ad FROM teklif_talepleri t LEFT JOIN hizmetler h ON h.id=t.hizmet_id $where ORDER BY t.olusturma DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <div class="btn-group">
      <a class="btn btn-sm btn-outline-primary <?= $filtre==='tumu'?'active':'' ?>" href="teklif-talepleri">Tümü</a>
      <a class="btn btn-sm btn-outline-danger <?= $filtre==='yeni'?'active':'' ?>" href="teklif-talepleri?filtre=yeni">Yeni</a>
    </div>
    <span class="text-muted small"><?= count($rows) ?> talep</span>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>Durum</th><th>Ad</th><th>Hizmet</th><th>Ülke</th><th>Tarih</th><th>Dil</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><span class="badge bg-<?= ['yeni'=>'danger','iletildi'=>'warning','kapandi'=>'success'][$r['durum']] ?>"><?= e($r['durum']) ?></span></td>
        <td><?= e($r['ad']) ?><br><small class="text-muted"><?= e($r['eposta']) ?></small></td>
        <td><small><?= e($r['hizmet_ad']??'-') ?></small></td>
        <td><small><?= e($r['ulke']) ?></small></td>
        <td><small><?= e(trTarih($r['olusturma'])) ?></small></td>
        <td><span class="badge bg-light text-dark"><?= strtoupper(e($r['dil'])) ?></span></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="teklif-talepleri?id=<?= (int)$r['id'] ?>"><i class="bi bi-eye"></i></a></td>
      </tr>
    <?php endforeach; ?>
    <?php if(!$rows): ?><tr><td colspan="7" class="text-center text-muted py-4">Talep yok.</td></tr><?php endif; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
