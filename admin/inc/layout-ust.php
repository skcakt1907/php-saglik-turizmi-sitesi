<?php
require_once __DIR__ . '/auth.php';
$baslik = $baslik ?? 'Yönetim';
$adminURL = SITE_URL . '/' . ADMIN_SLUG;
$mevcutSayfa = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($baslik) ?> — MHC Yönetim</title>
<meta name="robots" content="noindex,nofollow">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { background:#f5f7fa; font-family:'Poppins',sans-serif; }
  .ad-sidebar { background:#0d3b66; min-height:100vh; padding:20px 0; color:#fff; position:sticky; top:0; }
  .ad-sidebar .brand { padding:0 20px 20px; border-bottom:1px solid rgba(255,255,255,.12); margin-bottom:14px; }
  .ad-sidebar .brand-mark { background:#14b8a6; color:#fff; padding:6px 12px; border-radius:8px; font-weight:800; letter-spacing:1px; }
  .ad-sidebar a { display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.78); padding:10px 20px; text-decoration:none; font-size:.92rem; font-weight:500; }
  .ad-sidebar a:hover, .ad-sidebar a.active { background:rgba(255,255,255,.08); color:#fff; border-left:3px solid #14b8a6; padding-left:17px; }
  .ad-sidebar i { width:20px; }
  .ad-top { background:#fff; padding:14px 24px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; }
  .ad-content { padding:24px; }
  .stat-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,.04); }
  .stat-card .ico { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
  .table-card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,.04); }
  .nav-tabs .nav-link { color:#0d3b66; }
  .nav-tabs .nav-link.active { color:#14b8a6; border-bottom-color:#14b8a6; }
  .lang-badge { display:inline-block; background:#e5e7eb; color:#374151; font-size:.7rem; padding:2px 8px; border-radius:50rem; font-weight:600; margin-left:6px; }
</style>
</head>
<body>
<div class="d-flex">
  <aside class="ad-sidebar" style="width:240px; flex:0 0 240px;">
    <div class="brand">
      <span class="brand-mark">MHC</span>
      <small class="d-block mt-2 text-white-50">Yönetim Paneli</small>
    </div>
    <?php
    $menu = [
      'dashboard'        => ['Ana Panel',          'speedometer2'],
      'hizmetler'        => ['Sağlık Hizmetleri',  'heart-pulse'],
      'paketler'         => ['Paketler',           'box-seam'],
      'doktorlar'        => ['Doktorlar',          'person-badge'],
      'partnerler'       => ['Partnerler',         'building'],
      'konaklama'        => ['Konaklama',          'house'],
      'galeri'           => ['Galeri (Before/After)','images'],
      'yorumlar'         => ['Yorumlar',           'chat-quote'],
      'blog'             => ['Blog',               'newspaper'],
      'mesajlar'         => ['Mesajlar',           'envelope'],
      'teklif-talepleri' => ['Teklif Talepleri',   'cash-coin'],
      'ayarlar'          => ['Ayarlar',            'gear'],
    ];
    foreach ($menu as $slug => [$ad, $ikon]):
      $active = $mevcutSayfa === $slug ? 'active' : '';
    ?>
      <a class="<?= $active ?>" href="<?= e($adminURL) ?>/<?= e($slug) ?>"><i class="bi bi-<?= e($ikon) ?>"></i><?= e($ad) ?></a>
    <?php endforeach; ?>
  </aside>

  <main class="flex-grow-1">
    <div class="ad-top">
      <h5 class="mb-0"><?= e($baslik) ?></h5>
      <div>
        <a href="<?= SITE_URL ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i> Siteyi Aç</a>
        <span class="ms-2 text-muted small"><i class="bi bi-person-circle"></i> <?= e($_SESSION['admin_kullanici'] ?? '') ?></span>
        <a href="<?= e($adminURL) ?>/cikis" class="btn btn-sm btn-outline-danger ms-2"><i class="bi bi-box-arrow-right"></i> Çıkış</a>
      </div>
    </div>
    <div class="ad-content">
