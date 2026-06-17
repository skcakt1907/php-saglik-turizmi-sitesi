<?php
$sayfaBaslik = ['tr'=>'Hakkımızda','en'=>'About Us','de'=>'Über uns'][$_GET['lang'] ?? 'tr'] ?? 'Hakkımızda';
require_once __DIR__ . '/inc/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('menu_hakkimizda') ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= t('menu_anasayfa') ?></a></li>
      <li class="breadcrumb-item active"><?= t('menu_hakkimizda') ?></li>
    </ol></nav>
  </div>
</section>

<section>
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=1200" class="rounded-4 shadow" alt="">
      </div>
      <div class="col-lg-6">
        <span class="section-eyebrow"><?= e(ayar('site_adi')) ?></span>
        <h2 class="section-title"><?= t('menu_hakkimizda') ?></h2>
        <?php if ($DIL==='tr'): ?>
          <p>Marmaris Health Center, Marmaris\'te <strong>ornekseyahat</strong> adıyla 25 yıldır faaliyet gösteren, sağlık turizmini bütünleşik bir paket olarak sunan danışmanlık merkezidir. Tedavi, konaklama, transfer, bilet ve sigorta süreçlerini tek elden yönetiyoruz.</p>
          <p>Danışman ekibimizin temel hedefi, anlaşmalı doktor ve sağlık kuruluşlarımız aracılığıyla misafirlerimize doğru tedavi seçeneklerini oluşturmaktır. Anlaşmalı sağlık profesyonellerimiz raporlarınızı inceler ve sizin için en uygun tedavi planını hazırlar.</p>
        <?php elseif ($DIL==='de'): ?>
          <p>Marmaris Health Center ist ein Beratungszentrum, das seit 25 Jahren unter dem Namen <strong>ornekseyahat</strong> in Marmaris tätig ist und Gesundheitstourismus als integriertes Paket anbietet. Wir verwalten Behandlung, Unterkunft, Transfer, Tickets und Versicherung aus einer Hand.</p>
          <p>Das Hauptziel unseres Beratungsteams ist es, unseren Gästen über unsere Partnerärzte und Gesundheitseinrichtungen die richtigen Behandlungsmöglichkeiten zu bieten. Unsere Partner-Mediziner prüfen Ihre Unterlagen und erstellen den am besten geeigneten Behandlungsplan.</p>
        <?php else: ?>
          <p>Marmaris Health Center is a consultancy center that has been operating in Marmaris for 25 years under the name <strong>ornekseyahat</strong>, offering health tourism as an integrated package. We manage treatment, accommodation, transfer, tickets and insurance from a single source.</p>
          <p>The main goal of our consultancy team is to help our guests create the right treatment options through our partner doctors and healthcare institutions. Our partner medical professionals review your reports and prepare the most suitable treatment plan for you.</p>
        <?php endif; ?>
        <a class="btn btn-primary mt-3" href="<?= e(url('iletisim')) ?>"><?= t('iletisime_gec') ?></a>
      </div>
    </div>
  </div>
</section>

<section style="background:#fff;">
  <div class="container">
    <h3 class="section-title text-center mb-5"><?= $DIL==='tr'?'Bizimle Çalışmanın Avantajları':($DIL==='de'?'Vorteile einer Zusammenarbeit mit uns':'The Advantages of Working with Us') ?></h3>
    <div class="row g-4 text-center">
      <div class="col-md-3"><div class="why-card"><div class="ico"><i class="bi bi-award"></i></div><h3 class="text-primary fw-bold">25+</h3><p><?= $DIL==='tr'?'Yıllık Deneyim':($DIL==='de'?'Jahre Erfahrung':'Years Experience') ?></p></div></div>
      <div class="col-md-3"><div class="why-card"><div class="ico"><i class="bi bi-buildings"></i></div><h3 class="text-primary fw-bold">3</h3><p><?= $DIL==='tr'?'Partner Sağlık Kuruluşu':($DIL==='de'?'Partner-Gesundheitseinrichtungen':'Partner Healthcare Facilities') ?></p></div></div>
      <div class="col-md-3"><div class="why-card"><div class="ico"><i class="bi bi-translate"></i></div><h3 class="text-primary fw-bold">3</h3><p><?= $DIL==='tr'?'Dilde Hizmet':($DIL==='de'?'Sprachen':'Languages') ?></p></div></div>
      <div class="col-md-3"><div class="why-card"><div class="ico"><i class="bi bi-clipboard2-check"></i></div><h3 class="text-primary fw-bold">A-Z</h3><p><?= $DIL==='tr'?'Uçtan Uca Hizmet':($DIL==='de'?'End-to-End-Service':'End-to-End Service') ?></p></div></div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
