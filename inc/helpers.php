<?php
// =====================================================================
// Yardımcı fonksiyonlar
// =====================================================================

require_once __DIR__ . '/db.php';

/** HTML escape kısayolu. */
function e($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Ayar değeri çek (DB'den). */
function ayar(string $anahtar, string $varsayilan = ''): string {
    static $cache = null;
    global $db;
    if ($cache === null) {
        $cache = [];
        try {
            $rows = $db->query("SELECT anahtar, deger FROM ayarlar")->fetchAll();
            foreach ($rows as $r) $cache[$r['anahtar']] = $r['deger'];
        } catch (Throwable $e) {}
    }
    return $cache[$anahtar] ?? $varsayilan;
}

/**
 * Dil-duyarlı ayar. ayarDilli('hero_baslik') → ayar('hero_baslik_tr') aktif dile göre.
 */
function ayarDilli(string $anahtar, string $varsayilan = ''): string {
    global $DIL;
    $v = ayar($anahtar . '_' . $DIL);
    if ($v !== '') return $v;
    return ayar($anahtar . '_tr', $varsayilan);
}

/** Sıralı liste çek. */
function getList(string $tablo, string $where = '', array $params = [], string $orderBy = 'sira ASC, id DESC', int $limit = 0): array {
    global $db;
    $sql = "SELECT * FROM `$tablo`";
    if ($where !== '') $sql .= " WHERE $where";
    $sql .= " ORDER BY $orderBy";
    if ($limit > 0) $sql .= " LIMIT $limit";
    $st = $db->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

/** Tek satır. */
function getOne(string $tablo, string $where, array $params = []): ?array {
    global $db;
    $st = $db->prepare("SELECT * FROM `$tablo` WHERE $where LIMIT 1");
    $st->execute($params);
    $r = $st->fetch();
    return $r ?: null;
}

/** CSRF token üret. */
function csrf_token(): string {
    if (empty($_SESSION['_csrf']) || ($_SESSION['_csrf_t'] ?? 0) < time() - CSRF_OMUR) {
        $_SESSION['_csrf']   = bin2hex(random_bytes(32));
        $_SESSION['_csrf_t'] = time();
    }
    return $_SESSION['_csrf'];
}

/** CSRF doğrula. */
function csrf_check(?string $token): bool {
    return !empty($token) && !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}

/** Slug üret (Türkçe karakterleri çevirir). */
function slugify(string $s): string {
    $s = mb_strtolower(trim($s), 'UTF-8');
    $tr = ['ç','ğ','ı','ö','ş','ü','â','î','û'];
    $en = ['c','g','i','o','s','u','a','i','u'];
    $s = str_replace($tr, $en, $s);
    $s = preg_replace('/[^a-z0-9\-]+/', '-', $s);
    $s = preg_replace('/-+/', '-', $s);
    return trim($s, '-');
}

/** Türkçe ay adlı tarih formatı. */
function trTarih($t): string {
    $aylar = ['','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
    $ts = is_numeric($t) ? (int)$t : strtotime((string)$t);
    if (!$ts) return '';
    return date('j', $ts) . ' ' . $aylar[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/** Dile uygun tarih formatı. */
function dilliTarih($t): string {
    global $DIL;
    if ($DIL === 'tr') return trTarih($t);
    $ts = is_numeric($t) ? (int)$t : strtotime((string)$t);
    if (!$ts) return '';
    if ($DIL === 'de') {
        $aylar = ['','Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
        return date('j', $ts) . '. ' . $aylar[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
    return date('F j, Y', $ts);
}

/** Görseli güvenli kaydet (admin upload). */
function dosyaKaydet(array $file, string $altKlasor = ''): ?string {
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > UPLOAD_MAX_BYTES) return null;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif', 'image/svg+xml' => 'svg'];
    if (!isset($izin[$mime])) return null;

    $hedefKlasor = rtrim(UPLOAD_KOK, '/\\') . ($altKlasor ? '/' . trim($altKlasor, '/') : '');
    if (!is_dir($hedefKlasor)) @mkdir($hedefKlasor, 0775, true);

    $ad = bin2hex(random_bytes(8)) . '.' . $izin[$mime];
    $hedef = $hedefKlasor . '/' . $ad;
    if (!move_uploaded_file($file['tmp_name'], $hedef)) return null;

    return ($altKlasor ? trim($altKlasor, '/') . '/' : '') . $ad;
}

/** Yüklenmiş görselin tam URL'si. */
function uploadURL(?string $rel): string {
    if (!$rel) return '';
    if (preg_match('#^https?://#', $rel)) return $rel;
    return UPLOAD_URL . '/' . ltrim($rel, '/');
}

/** Yönlendirme. */
function git(string $u): void {
    header('Location: ' . $u);
    exit;
}

/** İstemci IP. */
function istemciIP(): string {
    return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}
