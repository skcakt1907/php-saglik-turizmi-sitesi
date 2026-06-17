<?php
$sayfaBaslik = ['tr'=>'Transfer Hizmetleri','en'=>'Transfer Services','de'=>'Transfer-Service'][$_GET['lang']??'tr'] ?? 'Transfer';
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_transfer') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_transfer') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1571987502227-9231b837d92a?w=1200" class="rounded-4 shadow" alt="">
      </div>
      <div class="col-lg-6">
        <h2 class="section-title">
          <?= $DIL==='tr'?'25 Yıllık Transfer Deneyimi':($DIL==='de'?'25 Jahre Transfer-Erfahrung':'25 Years of Transfer Experience') ?>
        </h2>
        <?php if ($DIL==='tr'): ?>
          <p class="text-muted">Marmaris\'te 25 yıldır <strong>ornekseyahat</strong> adı altında faaliyet göstermekteyiz. Şirketimiz aracılığıyla bölgedeki transfer ve turları organize ediyoruz. Misafirlerimizin çoğu, Marmaris\'e geldiklerinde transfer ve seyahat desteği için bize güveniyor.</p>
        <?php elseif ($DIL==='de'): ?>
          <p class="text-muted">Wir sind seit 25 Jahren in Marmaris unter dem Namen <strong>ornekseyahat</strong> tätig. Über unser Unternehmen organisieren wir Transfers und Touren in der Region. Viele unserer Gäste verlassen sich bei jedem Marmaris-Aufenthalt auf uns für Transfer- und Reiseunterstützung.</p>
        <?php else: ?>
          <p class="text-muted">We have been operating in Marmaris for 25 years under the name <strong>ornekseyahat</strong>. Through our company, we organize transfers and tours in nearby regions. Many of our guests rely on us for transfer and travel support whenever they come to Marmaris.</p>
        <?php endif; ?>
        <ul class="list-unstyled mt-3">
          <li><i class="bi bi-check-circle text-success me-2"></i><?= $DIL==='tr'?'7/24 havalimanı transferi (Dalaman, Bodrum)':($DIL==='de'?'24/7 Flughafentransfer (Dalaman, Bodrum)':'24/7 airport transfer (Dalaman, Bodrum)') ?></li>
          <li><i class="bi bi-check-circle text-success me-2"></i><?= $DIL==='tr'?'Hastane / klinik gidiş-dönüş':($DIL==='de'?'Hin- und Rückfahrt zu Krankenhaus / Klinik':'Hospital / clinic round-trip') ?></li>
          <li><i class="bi bi-check-circle text-success me-2"></i><?= $DIL==='tr'?'Çok dilli refakatçi':($DIL==='de'?'Mehrsprachige Begleitung':'Multilingual escort') ?></li>
          <li><i class="bi bi-check-circle text-success me-2"></i><?= $DIL==='tr'?'Klimalı, lüks araçlar':($DIL==='de'?'Klimatisierte Luxusfahrzeuge':'Air-conditioned, luxury vehicles') ?></li>
          <li><i class="bi bi-check-circle text-success me-2"></i><?= $DIL==='tr'?'Çevre bölgelere tur organizasyonu':($DIL==='de'?'Touren in die nähere Umgebung':'Tours to nearby regions') ?></li>
        </ul>
        <a class="btn btn-cta mt-2" href="<?= e(url('teklif-al')) ?>"><?= t('menu_teklif') ?></a>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
