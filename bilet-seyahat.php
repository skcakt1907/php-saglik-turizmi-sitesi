<?php
$sayfaBaslik = ['tr'=>'Bilet & Seyahat','en'=>'Tickets & Travel','de'=>'Tickets & Reise'][$_GET['lang']??'tr'] ?? 'Bilet & Seyahat';
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_bilet') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_bilet') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-lg-6">
        <h2 class="section-title">
          <?= $DIL==='tr'?'Deneyimli Turizm Ekibimizle Yanınızdayız':($DIL==='de'?'Mit unserem erfahrenen Reiseteam an Ihrer Seite':'Supported by Our Experienced Tourism Team') ?>
        </h2>
        <?php if ($DIL==='tr'): ?>
          <p class="text-muted">Deneyimli ve profesyonel turizm ekibimizle <strong>biletleme</strong> ve <strong>vize başvurularında</strong> destek oluyoruz. Güvenilir hava yolu seçenekleri ve uygun fiyatlı uçak biletleri sunuyoruz.</p>
        <?php elseif ($DIL==='de'): ?>
          <p class="text-muted">Mit unserem erfahrenen und professionellen Reiseteam unterstützen wir Sie bei <strong>Ticketing</strong> und <strong>Visumanträgen</strong>. Wir bieten zuverlässige Fluggesellschaftsoptionen und günstige Flugtickets.</p>
        <?php else: ?>
          <p class="text-muted">With our experienced and professional tourism team, we support you with <strong>ticketing</strong> and <strong>visa applications</strong>. We provide reliable airline options and affordable flight tickets.</p>
        <?php endif; ?>
      </div>
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200" class="rounded-4 shadow" alt="">
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-4"><div class="why-card"><div class="ico"><i class="bi bi-airplane"></i></div><h6><?= $DIL==='tr'?'Uçak Bileti':($DIL==='de'?'Flugticket':'Flight Tickets') ?></h6><p><?= $DIL==='tr'?'Anlaşmalı havayollarıyla avantajlı fiyatlarla bilet temini.':($DIL==='de'?'Tickets zu günstigen Preisen mit Partner-Fluggesellschaften.':'Tickets at favorable prices with partner airlines.') ?></p></div></div>
      <div class="col-md-4"><div class="why-card"><div class="ico"><i class="bi bi-passport"></i></div><h6><?= $DIL==='tr'?'Vize Başvurusu':($DIL==='de'?'Visumantrag':'Visa Application') ?></h6><p><?= $DIL==='tr'?'Vize başvuru süreçlerinde profesyonel destek.':($DIL==='de'?'Professionelle Unterstützung bei Visumanträgen.':'Professional support throughout the visa application process.') ?></p></div></div>
      <div class="col-md-4"><div class="why-card"><div class="ico"><i class="bi bi-water"></i></div><h6><?= $DIL==='tr'?'Tekne & Şehir Turları':($DIL==='de'?'Boots- & Stadttouren':'Boat & City Tours') ?></h6><p><?= $DIL==='tr'?'Marmaris koylarında tekne turları, çevre turlar.':($DIL==='de'?'Bootstouren in den Marmaris-Buchten, Touren in der Umgebung.':'Boat tours in Marmaris coves, tours to surrounding areas.') ?></p></div></div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
