<?php
$baslik = 'Ana Panel';
require_once __DIR__ . '/inc/layout-ust.php';

$sayilar = [
    'hizmetler'        => $db->query("SELECT COUNT(*) FROM hizmetler")->fetchColumn(),
    'doktorlar'        => $db->query("SELECT COUNT(*) FROM doktorlar")->fetchColumn(),
    'paketler'         => $db->query("SELECT COUNT(*) FROM paketler")->fetchColumn(),
    'partnerler'       => $db->query("SELECT COUNT(*) FROM partnerler")->fetchColumn(),
    'blog'             => $db->query("SELECT COUNT(*) FROM blog")->fetchColumn(),
    'mesajlar_yeni'    => $db->query("SELECT COUNT(*) FROM mesajlar WHERE durum='yeni'")->fetchColumn(),
    'teklif_yeni'      => $db->query("SELECT COUNT(*) FROM teklif_talepleri WHERE durum='yeni'")->fetchColumn(),
    'yorum'            => $db->query("SELECT COUNT(*) FROM yorumlar")->fetchColumn(),
];

$sonMesajlar = $db->query("SELECT * FROM mesajlar ORDER BY tarih DESC LIMIT 5")->fetchAll();
$sonTeklifler = $db->query("SELECT t.*, h.tr_baslik AS hizmet_ad FROM teklif_talepleri t LEFT JOIN hizmetler h ON h.id=t.hizmet_id ORDER BY t.olusturma DESC LIMIT 5")->fetchAll();
?>
<div class="row g-3 mb-4">
  <?php
  $kartlar = [
    ['Hizmet',           $sayilar['hizmetler'],     'heart-pulse',  '#0d3b66'],
    ['Paket',            $sayilar['paketler'],      'box-seam',     '#14b8a6'],
    ['Doktor',           $sayilar['doktorlar'],     'person-badge', '#f4a261'],
    ['Blog',             $sayilar['blog'],          'newspaper',    '#7c3aed'],
    ['Yeni Mesaj',       $sayilar['mesajlar_yeni'], 'envelope',     '#dc2626'],
    ['Yeni Teklif',      $sayilar['teklif_yeni'],   'cash-coin',    '#16a34a'],
    ['Partner',          $sayilar['partnerler'],    'building',     '#0891b2'],
    ['Yorum',            $sayilar['yorum'],         'chat-quote',   '#db2777'],
  ];
  foreach ($kartlar as [$ad, $sayi, $ikon, $renk]): ?>
    <div class="col-6 col-md-3">
      <div class="stat-card d-flex align-items-center gap-3">
        <div class="ico" style="background: <?= $renk ?>15; color: <?= $renk ?>;"><i class="bi bi-<?= $ikon ?>"></i></div>
        <div><div class="text-muted small"><?= e($ad) ?></div><h3 class="mb-0 fw-bold"><?= (int)$sayi ?></h3></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="table-card">
      <h6 class="fw-bold mb-3"><i class="bi bi-envelope text-danger"></i> Son Mesajlar</h6>
      <?php if ($sonMesajlar): ?>
        <table class="table table-sm">
          <thead><tr><th>Ad</th><th>Konu</th><th>Tarih</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($sonMesajlar as $m): ?>
            <tr>
              <td><?= e($m['ad']) ?><br><small class="text-muted"><?= e($m['eposta']) ?></small></td>
              <td><?= e(mb_strimwidth($m['konu'] ?: $m['mesaj'], 0, 40, '…')) ?></td>
              <td><small><?= e(trTarih($m['tarih'])) ?></small></td>
              <td><a href="mesajlar?id=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-primary">Aç</a></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted small mb-0">Henüz mesaj yok.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="table-card">
      <h6 class="fw-bold mb-3"><i class="bi bi-cash-coin text-success"></i> Son Teklif Talepleri</h6>
      <?php if ($sonTeklifler): ?>
        <table class="table table-sm">
          <thead><tr><th>Ad</th><th>Hizmet</th><th>Ülke</th><th>Tarih</th></tr></thead>
          <tbody>
          <?php foreach ($sonTeklifler as $t): ?>
            <tr>
              <td><?= e($t['ad']) ?><br><small class="text-muted"><?= e($t['eposta']) ?></small></td>
              <td><small><?= e($t['hizmet_ad'] ?? '-') ?></small></td>
              <td><small><?= e($t['ulke']) ?></small></td>
              <td><small><?= e(trTarih($t['olusturma'])) ?></small></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted small mb-0">Henüz teklif talebi yok.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/inc/layout-alt.php'; ?>
