<?php
require_once __DIR__ . '/inc/header.php';
$hizmetler  = getList('hizmetler', 'durum=1', [], 'sira ASC', 6);
$partnerler = getList('partnerler','durum=1', [], 'sira ASC');
?>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <span class="section-eyebrow text-warning"><?= e(ayar('site_adi')) ?></span>
        <h1 class="mt-2"><?= e(ayarDilli('hero_baslik')) ?></h1>
        <p class="lead mt-3"><?= e(ayarDilli('hero_alt')) ?></p>
        <div class="mt-4">
          <a href="<?= e(url('iletisim')) ?>" class="btn btn-cta me-2"><i class="bi bi-chat-square-heart me-1"></i><?= t('iletisime_gec') ?></a>
          <a href="<?= e(url('saglik-hizmetleri')) ?>" class="btn btn-outline-light"><?= t('menu_hizmetler') ?></a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat"><strong>25+</strong><span><?= $DIL==='tr'?'Yıllık Deneyim':($DIL==='de'?'Jahre Erfahrung':'Years Experience') ?></span></div>
          <div class="hero-stat"><strong>3</strong><span><?= $DIL==='tr'?'Partner Sağlık Kuruluşu':($DIL==='de'?'Partner-Einrichtungen':'Partner Facilities') ?></span></div>
          <div class="hero-stat"><strong>3</strong><span><?= $DIL==='tr'?'Dilde Hizmet':($DIL==='de'?'Sprachen':'Languages') ?></span></div>
          <div class="hero-stat"><strong>A-Z</strong><span><?= $DIL==='tr'?'Uçtan Uca Hizmet':($DIL==='de'?'End-to-End-Service':'End-to-End Service') ?></span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ANA HİZMETLER (5 kategori — referans siteye paralel) -->
<section>
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow"><?= $DIL==='tr'?'Hizmetlerimiz':($DIL==='de'?'Unsere Leistungen':'Our Services') ?></span>
      <h2 class="section-title"><?= $DIL==='tr'?'Tek Çatı Altında Sağlık ve Seyahat':($DIL==='de'?'Gesundheit und Reisen unter einem Dach':'Health and Travel Under One Roof') ?></h2>
      <p class="section-subtitle"><?= ayarDilli('hero_alt') ?></p>
    </div>
    <div class="row g-4">
      <?php
      $ust_hizmetler = [
        ['saglik-hizmetleri','heart-pulse',  $DIL==='tr'?'Sağlık Hizmetleri':($DIL==='de'?'Gesundheitsleistungen':'Health Services'),       $DIL==='tr'?'Anlaşmalı doktor ve sağlık kuruluşlarımızla en uygun tedavi planı.':($DIL==='de'?'Optimaler Behandlungsplan mit unseren Partnerärzten.':'The most suitable treatment plan with our partner doctors.') ],
        ['konaklama','house',                $DIL==='tr'?'Konaklama':($DIL==='de'?'Unterkunft':'Accommodation'),                              $DIL==='tr'?'Hastane veya şehir merkezine yakın anlaşmalı oteller.':($DIL==='de'?'Partnerhotels in der Nähe von Krankenhaus oder Zentrum.':'Partner hotels near your hospital or city center.') ],
        ['transfer','car-front',             $DIL==='tr'?'Transfer':($DIL==='de'?'Transfer':'Transfer'),                                       $DIL==='tr'?'25 yıllık deneyimle havalimanı ve klinik transferleri.':($DIL==='de'?'25 Jahre Erfahrung mit Flughafen- und Kliniktransfers.':'25 years of experience with airport and clinic transfers.') ],
        ['bilet-seyahat','airplane',         $DIL==='tr'?'Bilet & Seyahat':($DIL==='de'?'Tickets & Reise':'Tickets & Travel'),                $DIL==='tr'?'Uçak bileti, vize başvurusu ve tur organizasyonu.':($DIL==='de'?'Flugticket, Visumantrag und Tourorganisation.':'Flight tickets, visa applications and tour organization.') ],
        ['sigorta','umbrella',               $DIL==='tr'?'Sigorta':($DIL==='de'?'Versicherung':'Insurance'),                                  $DIL==='tr'?'İhtiyacınıza en uygun sağlık sigortası.':($DIL==='de'?'Die passende Krankenversicherung für Sie.':'Health insurance that fits your needs.') ],
        ['partner-tesisler','buildings',     $DIL==='tr'?'Partner Tesisler':($DIL==='de'?'Partner-Einrichtungen':'Partner Facilities'),       $DIL==='tr'?'Yücelen Hastanesi, Your Beauty Clinic, Zirve Dental.':($DIL==='de'?'Yücelen Krankenhaus, Your Beauty Clinic, Zirve Dental.':'Yücelen Hospital, Your Beauty Clinic, Zirve Dental.') ],
      ];
      foreach ($ust_hizmetler as [$slug,$ico,$baslik,$ozet]): ?>
        <div class="col-md-6 col-lg-4">
          <div class="service-card">
            <div class="service-icon"><i class="bi bi-<?= e($ico) ?>"></i></div>
            <h5><?= e($baslik) ?></h5>
            <p><?= e($ozet) ?></p>
            <a class="more-link" href="<?= e(url($slug)) ?>"><?= t('detay') ?> <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SAĞLIK BRANŞLARI (DB'den) -->
<?php if ($hizmetler): ?>
<section style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow"><?= $DIL==='tr'?'Sağlık Branşları':($DIL==='de'?'Medizinische Fachbereiche':'Medical Specialties') ?></span>
      <h2 class="section-title"><?= t('hizmetlerimiz') ?></h2>
      <p class="section-subtitle"><?= t('hizmetlerimiz_alt') ?></p>
    </div>
    <div class="row g-4">
      <?php foreach ($hizmetler as $h): ?>
        <div class="col-md-6 col-lg-4">
          <div class="service-card">
            <div class="service-icon"><i class="bi <?= e($h['ikon']) ?>"></i></div>
            <h5><?= e(dilliAlan($h,'baslik')) ?></h5>
            <p><?= e(dilliAlan($h,'ozet')) ?></p>
            <a class="more-link" href="<?= e(url('saglik-hizmetleri', $h['slug'])) ?>"><?= t('detay') ?> <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a class="btn btn-outline-primary" href="<?= e(url('saglik-hizmetleri')) ?>"><?= t('tumunu_gor') ?></a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- NEDEN BİZ -->
<section>
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow"><?= $DIL==='tr'?'Avantajlarımız':($DIL==='de'?'Unsere Vorteile':'Our Advantages') ?></span>
      <h2 class="section-title"><?= t('neden_biz') ?></h2>
    </div>
    <div class="row g-4">
      <?php
      $why = [
        ['shield-check', $DIL==='tr'?'Anlaşmalı Tesisler':($DIL==='de'?'Partner-Einrichtungen':'Partner Facilities'),
          $DIL==='tr'?'Yücelen Hastanesi, Your Beauty ve Zirve Dental ortaklıkları.':($DIL==='de'?'Partnerschaften mit Yücelen, Your Beauty und Zirve Dental.':'Partnerships with Yücelen, Your Beauty and Zirve Dental.')],
        ['translate', $DIL==='tr'?'Çok Dilli Refakat':($DIL==='de'?'Mehrsprachige Begleitung':'Multilingual Assistance'),
          $DIL==='tr'?'TR / EN / DE konuşan ekiple her aşamada yanınızda.':($DIL==='de'?'TR/EN/DE-sprechendes Team an Ihrer Seite.':'TR/EN/DE-speaking team by your side.')],
        ['airplane', $DIL==='tr'?'Havalimanı Transferi':($DIL==='de'?'Flughafentransfer':'Airport Transfer'),
          $DIL==='tr'?'Dalaman / Bodrum karşılama, hastane ve otel transferi.':($DIL==='de'?'Empfang Dalaman/Bodrum, Krankenhaus- und Hoteltransfer.':'Dalaman/Bodrum pickup, hospital and hotel transfer.')],
        ['cash-coin', $DIL==='tr'?'Şeffaf Süreç':($DIL==='de'?'Transparenter Prozess':'Transparent Process'),
          $DIL==='tr'?'Tedavi öncesi tüm detaylar yazılı olarak.':($DIL==='de'?'Alle Details vor der Behandlung schriftlich.':'All details in writing before treatment.')],
        ['heart-pulse', $DIL==='tr'?'25 Yıllık Deneyim':($DIL==='de'?'25 Jahre Erfahrung':'25 Years of Experience'),
          $DIL==='tr'?'ornekseyahat adıyla 25 yıldır faaliyetteyiz.':($DIL==='de'?'Seit 25 Jahren als ornekseyahat tätig.':'Operating as ornekseyahat for 25 years.')],
        ['umbrella', $DIL==='tr'?'Sigorta Desteği':($DIL==='de'?'Versicherungsschutz':'Insurance Support'),
          $DIL==='tr'?'Tedavi sigortası seçenekleri ile güvende olun.':($DIL==='de'?'Mit Versicherungsoptionen geschützt.':'Stay protected with insurance options.')],
      ];
      foreach ($why as [$ico,$baslik,$aciklama]): ?>
        <div class="col-md-6 col-lg-4">
          <div class="why-card">
            <div class="ico"><i class="bi bi-<?= e($ico) ?>"></i></div>
            <h6><?= e($baslik) ?></h6>
            <p><?= e($aciklama) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PARTNERLER -->
<?php if ($partnerler): ?>
<section style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow"><?= t('menu_partnerler') ?></span>
      <h2 class="section-title"><?= t('partnerlerimiz') ?></h2>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($partnerler as $p): ?>
        <div class="col-md-4">
          <div class="service-card text-center h-100">
            <div class="service-icon mx-auto"><i class="bi bi-building"></i></div>
            <h5><?= e($p['ad']) ?></h5>
            <p class="small text-muted"><?= e(mb_strimwidth(strip_tags(dilliAlan($p,'aciklama')),0,140,'…')) ?></p>
            <a class="more-link" href="<?= e(url('partner-tesisler')) ?>#partner-<?= (int)$p['id'] ?>"><?= t('detay') ?> <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section style="background:linear-gradient(135deg,var(--mhc-primary),var(--mhc-accent)); color:#fff; padding:60px 0;">
  <div class="container text-center">
    <h2 class="fw-bold mb-3"><?= t('iletisim_basligi') ?></h2>
    <p class="lead mb-4 opacity-90"><?= ayarDilli('hero_alt') ?></p>
    <a href="<?= e(url('iletisim')) ?>" class="btn btn-cta btn-lg"><i class="bi bi-chat-square-heart me-2"></i><?= t('iletisime_gec') ?></a>
    <a href="https://wa.me/<?= e(preg_replace('/\D/','', ayar('whatsapp'))) ?>" target="_blank" class="btn btn-light btn-lg ms-2"><i class="bi bi-whatsapp me-2"></i>WhatsApp</a>
  </div>
</section>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
