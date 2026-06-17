<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/dil.php';
require_once __DIR__ . '/helpers.php';

$sayfaBaslik = $sayfaBaslik ?? ayar('site_adi');
$sayfaAciklama = $sayfaAciklama ?? ayarDilli('seo_aciklama');
?>
<!doctype html>
<html lang="<?= e($DIL) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($sayfaBaslik) ?> | <?= e(ayar('site_adi')) ?></title>
<meta name="description" content="<?= e($sayfaAciklama) ?>">
<meta name="theme-color" content="#0d3b66">

<!-- Favicon -->
<link rel="icon" type="image/jpeg" href="<?= SITE_URL ?>/img/logo.jpg">
<link rel="apple-touch-icon" href="<?= SITE_URL ?>/img/logo.jpg">

<!-- Open Graph -->
<meta property="og:title" content="<?= e($sayfaBaslik) ?>">
<meta property="og:description" content="<?= e($sayfaAciklama) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="<?= e($DIL) ?>">
<meta property="og:image" content="<?= SITE_URL ?>/img/logo2.jpg">

<!-- hreflang (çoklu dil SEO) -->
<?php foreach (DESTEKLENEN_DILLER as $d): ?>
<link rel="alternate" hreflang="<?= e($d) ?>" href="<?= e(dilDegisURL($d)) ?>">
<?php endforeach; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= SITE_URL ?>/css/style.css?v=<?= @filemtime(__DIR__ . '/../css/style.css') ?>" rel="stylesheet">
</head>
<body>

<!-- Üst bar -->
<div class="top-bar d-none d-lg-block">
  <div class="container d-flex justify-content-between align-items-center py-1 small">
    <div>
      <a class="me-3 text-white" href="tel:<?= e(ayar('telefon')) ?>"><i class="bi bi-telephone-fill me-1"></i><?= e(ayar('telefon')) ?></a>
      <a class="text-white" href="mailto:<?= e(ayar('eposta')) ?>"><i class="bi bi-envelope-fill me-1"></i><?= e(ayar('eposta')) ?></a>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span class="dil-degis">
        <?php foreach (DESTEKLENEN_DILLER as $d): ?>
          <a class="<?= $d === $DIL ? 'aktif' : '' ?>" href="<?= e(dilDegisURL($d)) ?>"><?= strtoupper($d) ?></a>
        <?php endforeach; ?>
      </span>
      <?php if (ayar('instagram')): ?><a class="text-white" href="<?= e(ayar('instagram')) ?>" target="_blank"><i class="bi bi-instagram"></i></a><?php endif; ?>
      <?php if (ayar('facebook')):  ?><a class="text-white" href="<?= e(ayar('facebook'))  ?>" target="_blank"><i class="bi bi-facebook"></i></a><?php endif; ?>
      <?php if (ayar('youtube')):   ?><a class="text-white" href="<?= e(ayar('youtube'))   ?>" target="_blank"><i class="bi bi-youtube"></i></a><?php endif; ?>
    </div>
  </div>
</div>

<!-- Navbar -->
<?php
$mevcut = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '.php');
$mevcut = preg_replace('#^(en|de)/#', '', $mevcut);
function aktifMi(string $hedef, string $mevcut): string {
    if ($hedef === 'index' && ($mevcut === '' || $mevcut === 'index' || $mevcut === 'marmaris-php')) return 'active';
    return $hedef === $mevcut ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top main-nav">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= e(url('')) ?>">
      <img src="<?= SITE_URL ?>/img/logo2.jpg" alt="<?= e(ayar('site_adi')) ?>" class="brand-logo">
      <span class="brand-text"><?= e(ayar('site_adi')) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= aktifMi('saglik-hizmetleri',$mevcut) ?>" href="<?= e(url('saglik-hizmetleri')) ?>"><?= t('menu_hizmetler') ?></a></li>
        <li class="nav-item"><a class="nav-link <?= aktifMi('konaklama',$mevcut) ?>"        href="<?= e(url('konaklama')) ?>"><?= t('menu_konaklama') ?></a></li>
        <li class="nav-item"><a class="nav-link <?= aktifMi('transfer',$mevcut) ?>"         href="<?= e(url('transfer')) ?>"><?= t('menu_transfer') ?></a></li>
        <li class="nav-item"><a class="nav-link <?= aktifMi('bilet-seyahat',$mevcut) ?>"    href="<?= e(url('bilet-seyahat')) ?>"><?= t('menu_bilet') ?></a></li>
        <li class="nav-item"><a class="nav-link <?= aktifMi('sigorta',$mevcut) ?>"          href="<?= e(url('sigorta')) ?>"><?= t('menu_sigorta') ?></a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= aktifMi('partner-tesisler',$mevcut) ?>" href="<?= e(url('partner-tesisler')) ?>" data-bs-toggle="dropdown"><?= t('menu_partnerler') ?></a>
          <ul class="dropdown-menu shadow-sm">
            <?php foreach (getList('partnerler','durum=1') as $p): ?>
              <li><a class="dropdown-item" href="<?= e(url('partner-tesisler')) ?>#partner-<?= (int)$p['id'] ?>"><i class="bi bi-building me-1 text-primary"></i> <?= e($p['ad']) ?></a></li>
            <?php endforeach; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item fw-semibold" href="<?= e(url('partner-tesisler')) ?>"><?= t('tumunu_gor') ?> →</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link <?= aktifMi('iletisim',$mevcut) ?>"         href="<?= e(url('iletisim')) ?>"><?= t('menu_iletisim') ?></a></li>
      </ul>
    </div>
  </div>
</nav>
