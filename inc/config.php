<?php
// =====================================================================
// Marmaris Health Center - Yapılandırma
// Canlıya yüklerken DB_HOST, DB_USER, DB_PASS, DB_NAME ve SITE_URL'i güncelle.
// =====================================================================

// --- Veritabanı (geliştirme: WAMP varsayılanları) ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'marmaris');
define('DB_CHARSET', 'utf8mb4');

// --- Site adresi ---
// Geliştirme:  http://localhost/marmaris-php
// Canlı:       https://www.marmarishealthcenter.com
define('SITE_URL', 'http://localhost/marmaris-php');

// --- Diller ---
define('DESTEKLENEN_DILLER', ['en','tr','de']);
define('VARSAYILAN_DIL', 'en');

// --- Admin URL slug ---
// .htaccess'teki slug ile aynı olmalı. Değişirse iki yerde de güncelle.
define('ADMIN_SLUG', 'admin');

// --- Güvenlik ---
define('CSRF_OMUR', 7200);              // 2 saat
define('LOGIN_DENEME_LIMIT', 5);        // 15 dk içinde 5 başarısız giriş → kilit
define('LOGIN_KILIT_DK', 15);

// --- Yükleme ---
define('UPLOAD_MAX_BYTES', 5 * 1024 * 1024); // 5 MB
define('UPLOAD_KOK', __DIR__ . '/../uploads');
define('UPLOAD_URL', SITE_URL . '/uploads');

// --- Genel ---
date_default_timezone_set('Europe/Istanbul');
mb_internal_encoding('UTF-8');

// Hata gösterimi (canlıda kapat!)
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Session güvenli ayarlar
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS'])) {
        ini_set('session.cookie_secure', '1');
    }
    session_name('MHC_SESS');
    session_start();
}
