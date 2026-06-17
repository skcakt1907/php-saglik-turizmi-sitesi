<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/dil.php';
require_once __DIR__ . '/inc/helpers.php';

$slug = $_GET['slug'] ?? '';

if ($slug !== '') {
    $b = getOne('blog','slug=? AND durum=1',[$slug]);
    if (!$b) { header('HTTP/1.1 404 Not Found'); include __DIR__ . '/404.php'; exit; }
    $sayfaBaslik = dilliAlan($b,'baslik');
    $sayfaAciklama = mb_strimwidth(strip_tags(dilliAlan($b,'ozet')), 0, 160, '…');
    require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= e(dilliAlan($b,'baslik')) ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item"><a href="<?= e(url('blog')) ?>"><?= t('menu_blog') ?></a></li>
      <li class="breadcrumb-item active"><?= e(mb_strimwidth(dilliAlan($b,'baslik'),0,40,'…')) ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <p class="text-muted small mb-3"><i class="bi bi-calendar3 me-1"></i><?= e(dilliTarih($b['tarih'])) ?> · <i class="bi bi-person ms-2 me-1"></i><?= e($b['yazar']) ?></p>
        <?php if ($b['kapak']): ?><img src="<?= e(uploadURL($b['kapak'])) ?>" class="img-fluid rounded-4 shadow-sm mb-4" alt=""><?php endif; ?>
        <div class="content-block">
          <p class="lead"><?= e(dilliAlan($b,'ozet')) ?></p>
          <?= dilliAlan($b,'icerik') ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
} else {
    $sayfaBaslik = t('menu_blog');
    require_once __DIR__ . '/inc/header.php';
    $blog = getList('blog','durum=1',[],'sira ASC, tarih DESC');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_blog') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_blog') ?></li>
    </ol></nav>
  </div>
</section>
<section>
  <div class="container">
    <div class="row g-4">
      <?php foreach ($blog as $b): ?>
        <div class="col-md-6 col-lg-4">
          <div class="blog-card">
            <a href="<?= e(url('blog',$b['slug'])) ?>" class="img-wrap"><img src="<?= e(uploadURL($b['kapak'])) ?>" alt=""></a>
            <div class="body">
              <div class="meta"><i class="bi bi-calendar3 me-1"></i><?= e(dilliTarih($b['tarih'])) ?></div>
              <h5 class="mt-2"><a href="<?= e(url('blog',$b['slug'])) ?>"><?= e(dilliAlan($b,'baslik')) ?></a></h5>
              <p class="text-muted small"><?= e(mb_strimwidth(dilliAlan($b,'ozet'),0,110,'…')) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php } require_once __DIR__ . '/inc/footer.php'; ?>
