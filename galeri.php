<?php
$sayfaBaslik = t('menu_galeri');
require_once __DIR__ . '/inc/header.php';
$galeri = getList('galeri','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_galeri') ?> — <?= t('oncesi_sonrasi') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_galeri') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-4">
      <?php foreach ($galeri as $g): ?>
        <div class="col-md-6">
          <div class="ba-card">
            <div class="ba-images">
              <div data-label="<?= $DIL==='tr'?'Öncesi':($DIL==='de'?'Vorher':'Before') ?>"><img src="<?= e(uploadURL($g['oncesi'])) ?>" alt=""></div>
              <div data-label="<?= $DIL==='tr'?'Sonrası':($DIL==='de'?'Nachher':'After') ?>"><img src="<?= e(uploadURL($g['sonrasi'])) ?>" alt=""></div>
            </div>
            <div class="body"><?= e(dilliAlan($g,'aciklama')) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
