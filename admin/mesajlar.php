<?php
$baslik = 'İletişim Mesajları';
require_once __DIR__ . '/inc/layout-ust.php';

$id = (int)($_GET['id'] ?? 0);

// Aksiyonlar
if (isset($_GET['islem']) && $id && csrf_check($_GET['_t'] ?? null)) {
    if ($_GET['islem'] === 'okundu') {
        $db->prepare("UPDATE mesajlar SET durum='okundu' WHERE id=?")->execute([$id]);
    } elseif ($_GET['islem'] === 'sil') {
        $db->prepare("DELETE FROM mesajlar WHERE id=?")->execute([$id]);
        header('Location: mesajlar'); exit;
    }
    header('Location: mesajlar?id=' . $id); exit;
}

// Detay
if ($id) {
    $m = getOne('mesajlar','id=?',[$id]);
    if (!$m) { echo "<div class='alert alert-warning'>Bulunamadı</div>"; require_once __DIR__ . '/inc/layout-alt.php'; exit; }
    if ($m['durum'] === 'yeni') {
        $db->prepare("UPDATE mesajlar SET durum='okundu' WHERE id=?")->execute([$id]);
        $m['durum'] = 'okundu';
    }
?>
  <a href="mesajlar" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Liste</a>
  <div class="table-card">
    <div class="d-flex justify-content-between mb-3">
      <h5 class="fw-bold mb-0"><?= e($m['konu'] ?: '(konu yok)') ?></h5>
      <span class="badge bg-<?= $m['durum']==='yeni'?'danger':'success' ?>"><?= e($m['durum']) ?></span>
    </div>
    <p class="text-muted small">
      <strong><?= e($m['ad']) ?></strong>
      &lt;<a href="mailto:<?= e($m['eposta']) ?>"><?= e($m['eposta']) ?></a>&gt;
      <?php if($m['telefon']): ?> · <i class="bi bi-telephone"></i> <?= e($m['telefon']) ?><?php endif; ?>
      · <i class="bi bi-clock"></i> <?= e(trTarih($m['tarih'])) ?>
      · <span class="badge bg-light text-dark"><?= strtoupper(e($m['dil'])) ?></span>
      · <small class="text-muted">IP: <?= e($m['ip']) ?></small>
    </p>
    <hr>
    <div style="white-space:pre-wrap;"><?= e($m['mesaj']) ?></div>
    <hr>
    <a class="btn btn-sm btn-primary" href="mailto:<?= e($m['eposta']) ?>?subject=Re: <?= e($m['konu']) ?>"><i class="bi bi-reply"></i> Yanıtla</a>
    <a class="btn btn-sm btn-outline-danger" href="mesajlar?id=<?= $id ?>&islem=sil&_t=<?= e(csrf_token()) ?>" onclick="return confirm('Silinsin mi?')"><i class="bi bi-trash"></i> Sil</a>
  </div>
<?php } else {
    $filtre = $_GET['filtre'] ?? 'tumu';
    $where = $filtre === 'yeni' ? "WHERE durum='yeni'" : "";
    $rows = $db->query("SELECT * FROM mesajlar $where ORDER BY tarih DESC")->fetchAll();
?>
  <div class="d-flex justify-content-between mb-3">
    <div class="btn-group">
      <a class="btn btn-sm btn-outline-primary <?= $filtre==='tumu'?'active':'' ?>" href="mesajlar">Tümü</a>
      <a class="btn btn-sm btn-outline-danger <?= $filtre==='yeni'?'active':'' ?>" href="mesajlar?filtre=yeni">Yeni</a>
    </div>
    <span class="text-muted small"><?= count($rows) ?> mesaj</span>
  </div>
  <div class="table-card">
    <table class="table"><thead><tr><th>Durum</th><th>Ad</th><th>Konu / Mesaj</th><th>Dil</th><th>Tarih</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $m): ?>
      <tr>
        <td><span class="badge bg-<?= $m['durum']==='yeni'?'danger':'secondary' ?>"><?= e($m['durum']) ?></span></td>
        <td><?= e($m['ad']) ?><br><small class="text-muted"><?= e($m['eposta']) ?></small></td>
        <td><?= e(mb_strimwidth($m['konu'] ?: $m['mesaj'], 0, 80, '…')) ?></td>
        <td><span class="badge bg-light text-dark"><?= strtoupper(e($m['dil'])) ?></span></td>
        <td><small><?= e(trTarih($m['tarih'])) ?></small></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="mesajlar?id=<?= (int)$m['id'] ?>"><i class="bi bi-eye"></i></a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="6" class="text-center text-muted py-4">Mesaj yok.</td></tr><?php endif; ?>
    </tbody></table>
  </div>
<?php } require_once __DIR__ . '/inc/layout-alt.php'; ?>
