<?php
$sayfaBaslik = ['tr'=>'Sigorta Hizmetleri','en'=>'Insurance Services','de'=>'Versicherung'][$_GET['lang']??'tr'] ?? 'Sigorta';
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_sigorta') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_sigorta') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <h2 class="section-title">
          <?= $DIL==='tr'?'Size Uygun Sağlık Sigortası':($DIL==='de'?'Passende Krankenversicherung':'Health Insurance That Fits') ?>
        </h2>
        <?php if ($DIL==='tr'): ?>
          <p class="text-muted">İhtiyaçlarınıza en uygun sigorta sağlayıcılarından birinden <strong>sağlık sigortası</strong> almanıza yardımcı oluyoruz.</p>
        <?php elseif ($DIL==='de'): ?>
          <p class="text-muted">Wir helfen Ihnen, eine <strong>Krankenversicherung</strong> bei einem der für Ihre Bedürfnisse am besten geeigneten Versicherungsanbieter abzuschließen.</p>
        <?php else: ?>
          <p class="text-muted">We help you obtain <strong>health insurance</strong> from one of the most suitable insurance providers for your needs.</p>
        <?php endif; ?>
        <ul class="mt-3">
          <li><?= $DIL==='tr'?'Tedavi sonrası komplikasyon teminatı':($DIL==='de'?'Komplikationsschutz nach der Behandlung':'Post-treatment complication coverage') ?></li>
          <li><?= $DIL==='tr'?'Acil sağlık sigortası':($DIL==='de'?'Notfall-Krankenversicherung':'Emergency health insurance') ?></li>
          <li><?= $DIL==='tr'?'Seyahat sigortası':($DIL==='de'?'Reiseversicherung':'Travel insurance') ?></li>
        </ul>
      </div>
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1200" class="rounded-4 shadow" alt="">
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
