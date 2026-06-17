<?php
$sayfaBaslik = ['tr'=>'Partner Tesisler','en'=>'Partner Facilities','de'=>'Partner-Einrichtungen'][$_GET['lang']??'tr'] ?? 'Partner Tesisler';
require_once __DIR__ . '/inc/header.php';
$partnerler = getList('partnerler','durum=1');
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_partnerler') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_partnerler') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <p class="text-center text-muted mb-5">
      <?= $DIL==='tr'?'Anlaşmalı sağlık kuruluşlarımızla kaliteli ve güvenilir sağlık hizmetleri sunuyoruz.':($DIL==='de'?'Mit unseren Partner-Gesundheitseinrichtungen bieten wir qualitativ hochwertige und vertrauenswürdige Gesundheitsdienste.':'We provide quality and reliable healthcare through our partner facilities.') ?>
    </p>

    <?php foreach ($partnerler as $i => $p): ?>
      <div id="partner-<?= (int)$p['id'] ?>" class="content-block mb-4">
        <div class="row g-4 align-items-start">
          <div class="col-md-3 text-center">
            <div class="service-icon mx-auto mb-2" style="width:80px;height:80px;font-size:2.2rem;"><i class="bi bi-building"></i></div>
            <h4 class="text-primary mb-0"><?= e($p['ad']) ?></h4>
            <small class="text-muted text-uppercase"><?= e($p['tip']) ?></small>
          </div>
          <div class="col-md-9">
            <?= dilliAlan($p,'aciklama') ?>
            <a class="btn btn-cta mt-2" href="<?= e(url('iletisim')) ?>"><i class="bi bi-chat-square-heart me-1"></i> <?= t('iletisime_gec') ?></a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
