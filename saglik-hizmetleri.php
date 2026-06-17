<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/dil.php';
require_once __DIR__ . '/inc/helpers.php';

$slug = $_GET['slug'] ?? '';

if ($slug !== '') {
    // ----- DETAY -----
    $h = getOne('hizmetler', 'slug=? AND durum=1', [$slug]);
    if (!$h) { header('HTTP/1.1 404 Not Found'); include __DIR__ . '/404.php'; exit; }
    $sayfaBaslik = dilliAlan($h, 'baslik');
    $sayfaAciklama = mb_strimwidth(strip_tags(dilliAlan($h,'ozet')), 0, 160, '…');
    require_once __DIR__ . '/inc/header.php';
    $paketler = getList('paketler', 'durum=1 AND hizmet_id=?', [$h['id']], 'sira ASC');
    $galeriH  = getList('galeri',   'durum=1 AND hizmet_id=?', [$h['id']], 'sira ASC');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= e(dilliAlan($h,'baslik')) ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item"><a href="<?= e(url('saglik-hizmetleri')) ?>"><?= t('menu_hizmetler') ?></a></li>
      <li class="breadcrumb-item active"><?= e(dilliAlan($h,'baslik')) ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <?php if ($h['gorsel']): ?>
          <img src="<?= e(uploadURL($h['gorsel'])) ?>" class="img-fluid rounded-4 mb-4 shadow-sm" alt="">
        <?php endif; ?>
        <div class="content-block">
          <p class="lead"><?= e(dilliAlan($h,'ozet')) ?></p>
          <?= dilliAlan($h,'icerik') ?>
        </div>

        <?php if ($galeriH): ?>
          <h3 class="mt-5 mb-3 text-primary"><?= t('oncesi_sonrasi') ?></h3>
          <div class="row g-3">
            <?php foreach ($galeriH as $g): ?>
              <div class="col-md-6">
                <div class="ba-card">
                  <div class="ba-images">
                    <div data-label="<?= $DIL==='tr'?'Öncesi':($DIL==='de'?'Vorher':'Before') ?>"><img src="<?= e(uploadURL($g['oncesi'])) ?>" alt=""></div>
                    <div data-label="<?= $DIL==='tr'?'Sonrası':($DIL==='de'?'Nachher':'After') ?>"><img src="<?= e(uploadURL($g['sonrasi'])) ?>" alt=""></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-4">
        <div class="content-block">
          <h5 class="text-primary"><?= t('iletisime_gec') ?></h5>
          <p class="small text-muted"><?= t('teklif_alt') ?></p>
          <a class="btn btn-cta w-100 mb-2" href="<?= e(url('teklif-al')) ?>?hizmet=<?= e($h['slug']) ?>"><?= t('menu_teklif') ?></a>
          <a class="btn btn-accent w-100" href="https://wa.me/<?= e(preg_replace('/\D/','', ayar('whatsapp'))) ?>" target="_blank"><i class="bi bi-whatsapp me-1"></i> WhatsApp</a>
        </div>

        <?php if ($paketler): ?>
        <div class="content-block mt-4">
          <h5 class="text-primary"><?= t('paketlerimiz') ?></h5>
          <?php foreach ($paketler as $p): ?>
            <div class="border-bottom py-2">
              <a href="<?= e(url('teklif-al')) ?>?paket=<?= e($p['slug']) ?>" class="d-flex justify-content-between align-items-center">
                <span><?= e(dilliAlan($p,'baslik')) ?></span>
                <strong class="text-warning"><?= e($p['para_birimi']) ?> <?= number_format((float)$p['fiyat'],0,',','.') ?></strong>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php
} else {
    // ----- LİSTE -----
    $sayfaBaslik = t('menu_hizmetler');
    require_once __DIR__ . '/inc/header.php';
    $hizmetler = getList('hizmetler','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_hizmetler') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_hizmetler') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <p class="text-center text-muted mb-5"><?= t('hizmetlerimiz_alt') ?></p>
    <div class="row g-4">
      <?php foreach ($hizmetler as $h): ?>
        <div class="col-md-6 col-lg-4">
          <div class="service-card">
            <div class="service-icon"><i class="bi <?= e($h['ikon']) ?>"></i></div>
            <h5><?= e(dilliAlan($h,'baslik')) ?></h5>
            <p><?= e(dilliAlan($h,'ozet')) ?></p>
            <a class="more-link" href="<?= e(url('saglik-hizmetleri',$h['slug'])) ?>"><?= t('detay') ?> <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php } require_once __DIR__ . '/inc/footer.php'; ?>
