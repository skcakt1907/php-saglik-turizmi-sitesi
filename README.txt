=====================================================================
  MARMARIS HEALTH CENTER — PHP + MySQL TEMASI
  Çoklu dil (TR / EN / DE) • Temiz URL • Gizli admin paneli
=====================================================================

İÇİNDEKİLER
  1. Geliştirme (WAMP / Localhost)
  2. Canlıya Yükleme (cPanel / FTP)
  3. Admin Paneli
  4. Klasör Yapısı
  5. URL Şeması
  6. Güvenlik Notları
  7. Sık Yapılacaklar


---------------------------------------------------------------------
1. GELİŞTİRME (LOCALHOST / WAMP)
---------------------------------------------------------------------

a) WAMP'ı çalıştır (yeşil ikon).
b) phpMyAdmin'i aç → http://localhost/phpmyadmin
   - Kullanıcı: root  /  Şifre: (boş)
   - "İçe Aktar" → kurulum.sql dosyasını seç → Yükle
   YA DA komut satırından:
     mysql -u root < kurulum.sql

c) inc/config.php içinde sadece SITE_URL kontrol et:
     define('SITE_URL', 'http://localhost/marmaris-php');

d) Test:
   Ana sayfa  → http://localhost/marmaris-php/
   İngilizce  → http://localhost/marmaris-php/en/
   Almanca    → http://localhost/marmaris-php/de/
   Admin      → http://localhost/marmaris-php/yonetim-mhc-7k9x4q/
                Kullanıcı: admin   Şifre: admin123


---------------------------------------------------------------------
2. CANLIYA YÜKLEME (cPanel / FTP)
---------------------------------------------------------------------

a) cPanel → MySQL Veritabanları:
   - Yeni DB oluştur: örn  hsthstm_marmaris
   - Yeni kullanıcı + güçlü şifre, DB'ye ALL PRIVILEGES ver.
   - kurulum.sql dosyasını phpMyAdmin'den içe aktar
     (NOT: ilk satırdaki "CREATE DATABASE marmaris;" ve "USE marmaris;"
      satırlarını kaldırabilirsin — DB'yi cPanel oluşturdu.)

b) Tüm dosyaları FTP ile public_html'e yükle.

c) inc/config.php'i CANLI değerlerle güncelle:
     DB_HOST = 'localhost'
     DB_USER = 'hsthstm_admin'    (cPanel kullanıcı adın)
     DB_PASS = 'GÜÇLÜ_ŞİFRE'
     DB_NAME = 'hsthstm_marmaris'
     SITE_URL = 'https://www.marmarishealthcenter.com'

d) ÖNEMLİ — .htaccess'i düzenle:
   "RewriteBase /marmaris-php/" satırını
   "RewriteBase /" olarak değiştir (kök dizine kuruluyor).
   Aynı dosyada "marmaris-php" geçen 301 yönlendirme satırını da
   "/" olarak güncelle.

e) hata gösterimini KAPAT:
   inc/config.php sonunda:
     ini_set('display_errors', '0');
     error_reporting(0);

f) HTTPS'i zorla (cPanel "Force HTTPS Redirect").

g) chmod (FTP / dosya yöneticisi):
   uploads/    755 (yazılabilir)
   inc/        755 (okunabilir, yazılamaz)
   *.php       644

h) Admin şifresini DEĞİŞTİR (aşağıya bak).

i) Admin URL slug'ını DEĞİŞTİR (aşağıya bak).


---------------------------------------------------------------------
3. ADMIN PANELİ
---------------------------------------------------------------------

GİRİŞ
  URL:        https://siten.com/yonetim-mhc-7k9x4q/
  Kullanıcı:  admin
  Şifre:      admin123

ŞİFRE DEĞİŞTİR
  En kolayı: phpMyAdmin'den admin tablosuna git, sifre_hash kolonuna
  yeni şifrenin password_hash() çıktısını yapıştır.

  Yeni hash üretmek için (terminal):
    php -r "echo password_hash('YeniSifre123!', PASSWORD_DEFAULT);"

ADMIN URL'İNİ DEĞİŞTİR (slug)
  İki yerde değişmeli:
  1) inc/config.php  →  define('ADMIN_SLUG', 'yeni-slug-buraya');
  2) .htaccess        →  3 satırda 'yonetim-mhc-7k9x4q' geçen yerleri
                         yeni-slug ile değiştir.
  Slug'ı tahmin edilemez yap (örn: kontrol-mhc-q7y2x4n9 gibi).

PANELDEKİ MODÜLLER
  - Ana Panel        : Özet sayılar + son mesajlar/teklifler
  - Sağlık Hizmetleri: Tedavi alanları (TR/EN/DE içerik)
  - Paketler         : Tedavi+konaklama+transfer paketleri (fiyatlı)
  - Doktorlar        : Doktor profilleri
  - Partnerler       : Hastane/klinik logoları
  - Konaklama        : Anlaşmalı oteller
  - Galeri           : Before/After görsel çiftleri
  - Yorumlar         : Hasta testimonialları
  - Blog             : SEO için yazılar
  - Mesajlar         : İletişim formundan gelenler
  - Teklif Talepleri : Çok adımlı formdan gelen lead'ler
  - Ayarlar          : Site adı, telefon, sosyal medya, hero metinleri,
                       SEO açıklamaları (TR/EN/DE)

GÜVENLİK
  - 15 dakika içinde 5 başarısız giriş → IP 15 dk kilitlenir
  - Tüm formlarda CSRF token
  - Tüm DB sorguları PDO prepared statements
  - uploads/ klasöründe PHP execution engelli (.htaccess)
  - inc/ ve lang/ klasörleri dış erişime kapalı


---------------------------------------------------------------------
4. KLASÖR YAPISI
---------------------------------------------------------------------

  marmaris-php/
  ├── .htaccess              ← URL yönlendirme + güvenlik
  ├── kurulum.sql            ← DB şeması + örnek veri
  ├── README.txt             ← bu dosya
  ├── *.php                  ← ön yüz sayfaları
  ├── 404.php
  ├── inc/                   ← çekirdek (dış erişim YASAK)
  │   ├── config.php
  │   ├── db.php
  │   ├── dil.php
  │   ├── helpers.php
  │   ├── header.php
  │   └── footer.php
  ├── admin/                 ← yönetim (dış URL: /yonetim-mhc-7k9x4q/)
  │   ├── index.php          ← login
  │   ├── dashboard.php
  │   ├── hizmetler.php  ...
  │   └── inc/
  │       ├── auth.php
  │       ├── layout-ust.php
  │       └── layout-alt.php
  ├── lang/                  ← çeviri sözlükleri
  │   ├── tr.php / en.php / de.php
  ├── css/style.css
  ├── js/main.js
  ├── img/
  └── uploads/               ← admin'den yüklenen görseller
      ├── hizmetler/  doktorlar/  partnerler/  galeri/  blog/  paketler/


---------------------------------------------------------------------
5. URL ŞEMASI
---------------------------------------------------------------------

ÖN YÜZ (Türkçe — varsayılan)
  /                          ana sayfa
  /hakkimizda
  /saglik-hizmetleri
  /saglik-hizmetleri/dis-tedavisi
  /konaklama
  /transfer
  /bilet-seyahat
  /sigorta
  /partner-tesisler
  /doktorlar
  /doktorlar/op-dr-mehmet-yilmaz
  /galeri
  /yorumlar
  /blog
  /blog/marmaris-saglik-turizmi-rehberi
  /iletisim
  /teklif-al

İNGİLİZCE / ALMANCA
  Tüm URL'ler /en/ veya /de/ ön ekiyle:
  /en/about-us  → tabii ki SLUG aynı kalır (saglik-hizmetleri)
  /en/saglik-hizmetleri/dis-tedavisi
  /de/saglik-hizmetleri/dis-tedavisi
  (Slug'ı dile göre çevirmek isterse ileride extension yapılır.)

ADMIN
  /yonetim-mhc-7k9x4q/             → login
  /yonetim-mhc-7k9x4q/dashboard
  /yonetim-mhc-7k9x4q/hizmetler
  /yonetim-mhc-7k9x4q/hizmetler?action=ekle
  /yonetim-mhc-7k9x4q/hizmetler?action=duzenle&id=1
  /yonetim-mhc-7k9x4q/cikis


---------------------------------------------------------------------
6. GÜVENLİK NOTLARI
---------------------------------------------------------------------

YAPILMIŞ KORUMALAR
  ✅ Tüm DB sorguları PDO prepared statement
  ✅ Tüm çıktılar e() ile XSS-escape
  ✅ CSRF token tüm POST formlarda
  ✅ password_hash + password_verify
  ✅ Brute-force kilit (15 dk / 5 deneme)
  ✅ Honeypot alanı (iletişim & teklif formu)
  ✅ Session güvenli (httponly, samesite)
  ✅ Login sonrası session_regenerate_id
  ✅ uploads/ klasöründe PHP/script execution engellendi
  ✅ inc/ ve lang/ klasörleri 403
  ✅ /admin/ doğrudan URL erişimi 404'lenir (sadece slug üzerinden)
  ✅ Görsel upload'larda MIME tipi doğrulaması + boyut limiti
  ✅ Güvenlik header'ları (X-Frame-Options, nosniff, vb.)

CANLIDA EKSTRA YAP
  - Admin şifresini değiştir (admin123 değil!)
  - Admin slug'ını değiştir (yonetim-mhc-7k9x4q değil!)
  - HTTPS zorla (Let's Encrypt + cPanel "Force HTTPS")
  - inc/config.php'de display_errors = 0
  - cPanel'den FTP şifresini güçlendir
  - PHP versiyonu en az 8.1 olsun


---------------------------------------------------------------------
7. SIK YAPILACAKLAR
---------------------------------------------------------------------

LOGO DEĞİŞTİRME
  Şu an metin tabanlı "MHC" logo kullanılıyor.
  Görsel logo için inc/header.php içinde .brand-mark ve .brand-text'i
  <img src="..."> ile değiştir.

RENK DEĞİŞTİRME
  css/style.css üst kısımdaki :root değişkenlerinden:
    --mhc-primary  : ana lacivert
    --mhc-accent   : turkuaz
    --mhc-gold     : altın CTA
    --mhc-gold       (CTA butonları)

WHATSAPP NUMARASI / TELEFON
  Admin → Ayarlar → Genel → telefon, whatsapp

HERO METNİ / SLOGAN
  Admin → Ayarlar → her dil sekmesinden hero_baslik / hero_alt

SEO META AÇIKLAMASI
  Admin → Ayarlar → her dil sekmesinden seo_aciklama

YENİ DİL EKLEME (örn: Rusça)
  1) inc/config.php → DESTEKLENEN_DILLER = ['tr','en','de','ru']
  2) lang/ru.php oluştur (en.php'yi kopyala, çevir)
  3) Tüm tablolarda ru_baslik, ru_ozet, ru_icerik vb. kolonları ekle
     (ALTER TABLE ... ADD COLUMN)
  4) Admin'deki sekmelere ru tab'ı ekle

E-POSTA BİLDİRİMİ EKLEME (yeni mesaj/teklif gelince)
  iletisim.php ve teklif-al.php içindeki INSERT'ten sonra
  mail() veya PHPMailer ile bildirim gönder.

GOOGLE ANALYTICS / META PIXEL
  inc/header.php sonuna veya footer.php'ye </body> önüne yapıştır.


=====================================================================
İYİ ŞANSLAR! Sorunlar için: bu README'yi okudun, çözemiyorsan ara :)
=====================================================================
