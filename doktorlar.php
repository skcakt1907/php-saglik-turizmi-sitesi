<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/dil.php';
require_once __DIR__ . '/inc/helpers.php';

$slug = $_GET['slug'] ?? '';

if ($slug !== '') {
    $d = getOne('doktorlar','slug=? AND durum=1',[$slug]);
    if (!$d) { header('HTTP/1.1 404 Not Found'); include __DIR__ . '/404.php'; exit; }
    $sayfaBaslik = dilliAlan($d,'ad');
    require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= e(dilliAlan($d,'ad')) ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item"><a href="<?= e(url('doktorlar')) ?>"><?= t('menu_doktorlar') ?></a></li>
      <li class="breadcrumb-item active"><?= e(dilliAlan($d,'ad')) ?></li>
    </ol></nav>
  </div>
</section>
<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-md-4">
        <img src="<?= e(uploadURL($d['foto'])) ?>" class="rounded-4 shadow-sm w-100" alt="">
      </div>
      <div class="col-md-8">
        <div class="content-block">
          <h2 class="text-primary"><?= e(dilliAlan($d,'ad')) ?></h2>
          <h5 class="text-muted"><?= e(dilliAlan($d,'unvan')) ?></h5>
          <?php if ($d['diller']): ?><p class="text-accent"><i class="bi bi-translate me-1"></i><?= e($d['diller']) ?></p><?php endif; ?>
          <hr>
          <?= dilliAlan($d,'bio') ?>
          <a class="btn btn-cta mt-3" href="<?= e(url('teklif-al')) ?>?doktor=<?= e($d['slug']) ?>"><?= t('iletisime_gec') ?></a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
} else {
    $sayfaBaslik = t('menu_doktorlar');
    require_once __DIR__ . '/inc/header.php';
    $doktorlar = getList('doktorlar','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_doktorlar') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_doktorlar') ?></li>
    </ol></nav>
  </div>
</section>
<section>
  <div class="container">
    <div class="row g-4">
      <?php foreach ($doktorlar as $d): ?>
        <div class="col-sm-6 col-lg-3">
          <a href="<?= e(url('doktorlar',$d['slug'])) ?>" class="text-decoration-none">
            <div class="doctor-card">
              <div class="photo"><img src="<?= e(uploadURL($d['foto'])) ?>" alt=""></div>
              <div class="body">
                <h6><?= e(dilliAlan($d,'ad')) ?></h6>
                <div class="role"><?= e(dilliAlan($d,'unvan')) ?></div>
                <?php if ($d['diller']): ?><div class="langs"><i class="bi bi-translate me-1"></i><?= e($d['diller']) ?></div><?php endif; ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php } require_once __DIR__ . '/inc/footer.php'; ?>
