<?php
$sayfaBaslik = t('menu_yorumlar');
require_once __DIR__ . '/inc/header.php';
$yorumlar = getList('yorumlar','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_yorumlar') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_yorumlar') ?></li>
    </ol></nav>
  </div>
</section>
<section>
  <div class="container">
    <div class="row g-4">
      <?php foreach ($yorumlar as $y): ?>
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="stars"><?= str_repeat('<i class="bi bi-star-fill"></i>', max(1,(int)$y['puan'])) ?></div>
            <p>"<?= e(dilliAlan($y,'yorum')) ?>"</p>
            <div class="author">
              <?php if ($y['foto']): ?><img src="<?= e(uploadURL($y['foto'])) ?>" alt=""><?php endif; ?>
              <div><strong><?= e($y['hasta_adi']) ?></strong><span><?= e($y['ulke']) ?></span></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
