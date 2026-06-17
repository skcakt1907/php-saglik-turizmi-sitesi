<?php
$sayfaBaslik = ['tr'=>'Konaklama Hizmetleri','en'=>'Accommodation Services','de'=>'Unterkunftsservice'][$_GET['lang']??'tr'] ?? 'Konaklama';
require_once __DIR__ . '/inc/header.php';
$tesisler = getList('konaklama','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_konaklama') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_konaklama') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5 align-items-center mb-5">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200" class="rounded-4 shadow" alt="">
      </div>
      <div class="col-lg-6">
        <h2 class="section-title">
          <?= $DIL==='tr'?'Kendinizi Evinizde Hissedeceğiniz Oteller':($DIL==='de'?'Hotels, in denen Sie sich wie zu Hause fühlen':'Hotels Where You Feel at Home') ?>
        </h2>
        <?php if ($DIL==='tr'): ?>
          <p class="text-muted">Kendinizi evinizdeymiş gibi hissedeceğiniz <strong>anlaşmalı otellerimizde</strong> konaklama sağlıyoruz. Konforlu ve şık seçeneklerden lüks alternatiflere kadar; <strong>hastanenize veya şehir merkezine yakın</strong> oteller organize ediyoruz.</p>
        <?php elseif ($DIL==='de'): ?>
          <p class="text-muted">Wir bieten Unterkunft in unseren <strong>Partnerhotels</strong>, in denen Sie sich wie zu Hause fühlen können. Von komfortablen und stilvollen Optionen bis hin zu Luxusalternativen — wir organisieren Hotels in der Nähe Ihres Krankenhauses oder im Stadtzentrum.</p>
        <?php else: ?>
          <p class="text-muted">We provide accommodation through our <strong>partner hotels</strong> where you can feel at home. From comfortable and stylish to luxury options, we arrange hotels <strong>near your hospital or in the city center</strong>.</p>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($tesisler): ?>
    <div class="row g-4">
      <?php foreach ($tesisler as $t): ?>
        <div class="col-md-6">
          <div class="package-card h-100">
            <?php if ($t['gorsel']): ?><div class="img-wrap"><img src="<?= e(uploadURL($t['gorsel'])) ?>" alt=""></div><?php endif; ?>
            <div class="body">
              <div class="d-flex justify-content-between">
                <h5><?= e(dilliAlan($t,'ad')) ?></h5>
                <span class="text-warning"><?= str_repeat('★', max(1,(int)$t['yildiz'])) ?></span>
              </div>
              <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?= e($t['konum']) ?></small>
              <p class="mt-2 mb-0"><?= e(dilliAlan($t,'aciklama')) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
