<?php
// Çıktı tamponlama: layout-ust.php HTML bastıktan SONRA bile header() redirect
// çalışsın diye (admin CRUD sayfaları layout'u üstte include edip altta yönlendiriyor).
// Koşulsuz: php.ini output_buffering=4096 ise o tampon dolunca boşalmasın diye
// kendi sınırsız tamponumuzu en üste açıyoruz.
ob_start();

require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/helpers.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/' . ADMIN_SLUG . '/');
    exit;
}

// Sürekli yenilenen aktivite zamanı
$_SESSION['admin_last_act'] = time();
