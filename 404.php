<?php
http_response_code(404);
$sayfaBaslik = '404';
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container text-center">
    <h1 style="font-size:5rem;">404</h1>
    <p class="lead"><?= t('sayfa_bulunamadi') ?></p>
    <a class="btn btn-cta mt-3" href="<?= e(url('')) ?>"><i class="bi bi-house me-1"></i> <?= t('anasayfaya_don') ?></a>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
