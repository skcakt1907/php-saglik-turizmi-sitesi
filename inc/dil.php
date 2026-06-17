<?php
// =====================================================================
// Dil sistemi: aktif dil tespiti + çeviri yardımcısı
// Aktif dil sırası: 1) URL ?lang=  2) cookie  3) tarayıcı 4) varsayılan
// =====================================================================

require_once __DIR__ . '/config.php';

function aktifDil(): string {
    $g = $_GET['lang'] ?? null;
    if ($g && in_array($g, DESTEKLENEN_DILLER, true)) {
        setcookie('mhc_dil', $g, time() + 60*60*24*180, '/', '', !empty($_SERVER['HTTPS']), true);
        return $g;
    }
    $c = $_COOKIE['mhc_dil'] ?? null;
    if ($c && in_array($c, DESTEKLENEN_DILLER, true)) return $c;

    // Tarayıcı dili dikkate ALINMAZ — ilk giriş daima varsayılan dil (İngilizce).
    // Ziyaretçi menüden TR/DE seçerse çerezle o dilde kalır.
    return VARSAYILAN_DIL;
}

$DIL = aktifDil();
$_GET['lang'] = $DIL; // alt sistemler için sabitle

// Sözlük yükle
$_LANG = [];
$dosya = __DIR__ . '/../lang/' . $DIL . '.php';
if (is_file($dosya)) {
    $_LANG = require $dosya;
}

/**
 * Çeviri al. Anahtar yoksa anahtarı döndürür (fallback).
 */
function t(string $anahtar, array $degiskenler = []): string {
    global $_LANG;
    $deger = $_LANG[$anahtar] ?? $anahtar;
    foreach ($degiskenler as $k => $v) {
        $deger = str_replace('{' . $k . '}', (string)$v, $deger);
    }
    return $deger;
}

/**
 * Aktif dile göre çok dilli sütundan veri çek.
 * dilliAlan($satir, 'baslik') → $satir['en_baslik'] (TR boşsa fallback TR)
 */
function dilliAlan(array $satir, string $alanAdi): string {
    global $DIL;
    $kolon = $DIL . '_' . $alanAdi;
    if (!empty($satir[$kolon])) return (string)$satir[$kolon];
    // Fallback: TR
    if (!empty($satir['tr_' . $alanAdi])) return (string)$satir['tr_' . $alanAdi];
    return '';
}

/**
 * Aktif dile uygun URL üretir.
 * url('hakkimizda')           → /marmaris-php/hakkimizda  (tr)
 *                              → /marmaris-php/en/hakkimizda  (en)
 * url('saglik-hizmetleri','dis-tedavisi') → kategori/slug
 */
function url(string $sayfa = '', string $slug = ''): string {
    global $DIL;
    $base = SITE_URL;
    $prefix = ($DIL === VARSAYILAN_DIL) ? '' : '/' . $DIL;
    $yol = $sayfa === '' ? '/' : ('/' . $sayfa . ($slug !== '' ? '/' . $slug : ''));
    if ($sayfa === '' && $prefix === '') return $base . '/';
    return $base . $prefix . $yol;
}

/** Aktif sayfanın diğer dillerdeki URL'sini üretir (dil değiştirici için). */
function dilDegisURL(string $hedefDil): string {
    $u = $_SERVER['REQUEST_URI'] ?? '/';
    // Query string'i ayır (varsa)
    if (($qpos = strpos($u, '?')) !== false) $u = substr($u, 0, $qpos);
    // Mevcut prefix temizle
    $u = preg_replace('#^/marmaris-php#', '', $u);
    $u = preg_replace('#^/(tr|de)(/|$)#', '/', $u);
    if ($u === '') $u = '/';
    // Yol oluştur
    if ($hedefDil === VARSAYILAN_DIL) {
        $path = ($u === '/') ? '/' : $u;
    } else {
        $path = '/' . $hedefDil . ($u === '/' ? '' : $u);
    }
    // ?lang= ZORUNLU: cookie'yi hedef dile günceller; aksi halde TR'ye (temiz URL)
    // dönüşte eski dil cookie'si seni o dilde tutuyordu.
    return SITE_URL . $path . '?lang=' . $hedefDil;
}
