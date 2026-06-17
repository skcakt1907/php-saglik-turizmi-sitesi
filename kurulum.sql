-- =====================================================================
-- Marmaris Health Center - Veritabanı Şeması + Örnek Veri
-- Karakter seti: utf8mb4 (emoji + tüm dil karakterleri)
-- Çoklu dil: aynı tabloda tr_*, en_*, de_* kolonları
-- =====================================================================

CREATE DATABASE IF NOT EXISTS marmaris CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marmaris;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- AYARLAR (key-value)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS ayarlar;
CREATE TABLE ayarlar (
  anahtar     VARCHAR(80)  PRIMARY KEY,
  deger       TEXT,
  guncelleme  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO ayarlar (anahtar, deger) VALUES
  ('site_adi',           'Marmaris Health Center'),
  ('site_slogan_tr',     'Sağlık ve tatili tek paketle yaşayın'),
  ('site_slogan_en',     'Health and holiday in one package'),
  ('site_slogan_de',     'Gesundheit und Urlaub in einem Paket'),
  ('telefon',            '+90 252 000 00 00'),
  ('whatsapp',           '+905000000000'),
  ('eposta',             'info@marmarishealthcenter.com'),
  ('adres_tr',           'Marmaris, Muğla, Türkiye'),
  ('adres_en',           'Marmaris, Mugla, Turkey'),
  ('adres_de',           'Marmaris, Mugla, Türkei'),
  ('instagram',          'https://instagram.com/'),
  ('facebook',           'https://facebook.com/'),
  ('youtube',            'https://youtube.com/'),
  ('harita_embed',       '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12774.000000!2d28.275!3d36.852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzbCsDUxJzA3LjIiTiAyOMKwMTYnMzAuMCJF!5e0!3m2!1str!2str!4v0000000000" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),
  ('hero_baslik_tr',     'Marmaris''te dünya standartlarında sağlık hizmeti'),
  ('hero_baslik_en',     'World-class healthcare in Marmaris'),
  ('hero_baslik_de',     'Gesundheitsversorgung auf Weltniveau in Marmaris'),
  ('hero_alt_tr',        'Tedavi, konaklama, transfer ve sigorta — hepsi tek pakette'),
  ('hero_alt_en',        'Treatment, accommodation, transfer and insurance — all in one package'),
  ('hero_alt_de',        'Behandlung, Unterkunft, Transfer und Versicherung — alles in einem Paket'),
  ('seo_aciklama_tr',    'Marmaris''te diş, estetik, saç ekimi ve tedavi paketleri. Konaklama ve transfer dahil.'),
  ('seo_aciklama_en',    'Dental, aesthetic, hair transplant and treatment packages in Marmaris. Accommodation and transfer included.'),
  ('seo_aciklama_de',    'Zahnmedizin, Ästhetik, Haartransplantation und Behandlungspakete in Marmaris. Unterkunft und Transfer inklusive.');

-- ---------------------------------------------------------------------
-- HİZMETLER (Sağlık alanları: diş, estetik, saç ekimi vs.)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS hizmetler;
CREATE TABLE hizmetler (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(120) UNIQUE NOT NULL,
  ikon         VARCHAR(60)  DEFAULT 'bi-heart-pulse',
  gorsel       VARCHAR(255),
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_baslik    VARCHAR(180),
  tr_ozet      VARCHAR(500),
  tr_icerik    MEDIUMTEXT,
  en_baslik    VARCHAR(180),
  en_ozet      VARCHAR(500),
  en_icerik    MEDIUMTEXT,
  de_baslik    VARCHAR(180),
  de_ozet      VARCHAR(500),
  de_icerik    MEDIUMTEXT,
  olusturma    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO hizmetler (slug, ikon, gorsel, sira, tr_baslik, tr_ozet, tr_icerik, en_baslik, en_ozet, en_icerik, de_baslik, de_ozet, de_icerik) VALUES
('dis-tedavisi','bi-emoji-smile','https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=1200',1,
 'Diş Tedavisi','İmplant, zirkonyum kron, lamine veneer ve tüm diş tedavileri Marmaris''in akredite kliniklerinde.',
 '<p>Marmaris Health Center, partner kliniklerinde modern diş hekimliğinin tüm hizmetlerini sunar: implant, zirkonyum kaplama, lamine veneer, smile design, kanal tedavisi ve beyazlatma.</p><ul><li>CAD/CAM dijital diş tasarımı</li><li>Steril ve akredite klinikler</li><li>Tüm tedaviler için garanti belgesi</li><li>Konaklama ve transfer dahil paketler</li></ul>',
 'Dental Treatment','Implants, zirconia crowns, veneers and full dental care at accredited Marmaris clinics.',
 '<p>Marmaris Health Center provides full modern dental care through partner clinics: implants, zirconia crowns, laminate veneers, smile design, root canal and whitening.</p><ul><li>CAD/CAM digital dentistry</li><li>Sterile, accredited clinics</li><li>Treatment guarantee certificate</li><li>Packages include accommodation and transfer</li></ul>',
 'Zahnbehandlung','Implantate, Zirkonkronen, Veneers und vollständige Zahnpflege in akkreditierten Kliniken in Marmaris.',
 '<p>Marmaris Health Center bietet über Partnerkliniken vollständige moderne Zahnpflege: Implantate, Zirkonkronen, Laminat-Veneers, Smile-Design, Wurzelbehandlung und Bleaching.</p><ul><li>Digitale CAD/CAM-Zahnmedizin</li><li>Sterile, akkreditierte Kliniken</li><li>Behandlungsgarantie</li><li>Pakete inkl. Unterkunft und Transfer</li></ul>'),

('estetik-cerrahi','bi-stars','https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=1200',2,
 'Estetik Cerrahi','Burun estetiği, meme, karın germe, liposuction — alanında uzman cerrahlarla.',
 '<p>Plastik ve estetik cerrahi alanında uzman ekiple rinoplasti (burun estetiği), meme estetiği, karın germe (abdominoplasti), liposuction ve yüz germe operasyonları gerçekleştiriyoruz.</p>',
 'Aesthetic Surgery','Rhinoplasty, breast, tummy tuck, liposuction — performed by board-certified surgeons.',
 '<p>Our plastic and aesthetic surgery team performs rhinoplasty, breast aesthetics, abdominoplasty, liposuction and facelift procedures.</p>',
 'Ästhetische Chirurgie','Nasenkorrektur, Brust, Bauchstraffung, Fettabsaugung — von zertifizierten Chirurgen.',
 '<p>Unser plastisch-ästhetisches Chirurgenteam führt Nasenkorrekturen, Brustästhetik, Bauchdeckenstraffung, Fettabsaugung und Facelifting durch.</p>'),

('sac-ekimi','bi-person-bounding-box','https://images.unsplash.com/photo-1559757175-5700dde675bc?w=1200',3,
 'Saç Ekimi','DHI ve FUE Safir teknikleriyle doğal görünümlü, kalıcı saç ekimi.',
 '<p>Saç ekimi alanında DHI (Direct Hair Implantation) ve FUE Safir teknikleriyle doğal ve kalıcı sonuçlar. Sakal ve kaş ekimi de mevcut.</p>',
 'Hair Transplant','Natural-looking, permanent hair transplantation with DHI and Sapphire FUE.',
 '<p>Natural and permanent results in hair transplantation using DHI (Direct Hair Implantation) and Sapphire FUE techniques. Beard and eyebrow transplants are also available.</p>',
 'Haartransplantation','Natürlich aussehende, dauerhafte Haartransplantation mit DHI und Saphir-FUE.',
 '<p>Natürliche und dauerhafte Ergebnisse bei Haartransplantationen mit DHI- und Saphir-FUE-Techniken. Bart- und Augenbrauentransplantationen ebenfalls verfügbar.</p>'),

('goz-saglik','bi-eye','https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=1200',4,
 'Göz Sağlığı','Lazer göz tedavisi (LASIK), katarakt ve akıllı mercek uygulamaları.',
 '<p>LASIK lazer göz ameliyatı, katarakt cerrahisi, akıllı mercek (multifokal) uygulamaları partner göz hastanelerimizde uygulanır.</p>',
 'Eye Care','Laser eye surgery (LASIK), cataract and smart lens procedures.',
 '<p>LASIK laser eye surgery, cataract surgery and smart (multifocal) lens implantation are performed at our partner eye hospitals.</p>',
 'Augenheilkunde','Laser-Augenoperation (LASIK), Katarakt und Smart-Linsen-Eingriffe.',
 '<p>LASIK-Laseroperation, Katarakt-Chirurgie und Smart- (multifokale) Linsenimplantation in unseren Partner-Augenkliniken.</p>'),

('check-up','bi-clipboard2-pulse','https://images.unsplash.com/photo-1530497610245-94d3c16cda28?w=1200',5,
 'Check-Up Paketleri','Kapsamlı sağlık taraması — kan, görüntüleme ve uzman muayene.',
 '<p>Erkek, kadın ve yönetici check-up paketleri. Kan tahlilleri, EKG, USG, MR görüntüleme ve uzman doktor muayenesi tek günde.</p>',
 'Check-Up Packages','Comprehensive health screening — labs, imaging and specialist consultation.',
 '<p>Male, female and executive check-up packages. Blood tests, ECG, USG, MRI imaging and specialist consultation in a single day.</p>',
 'Check-Up-Pakete','Umfassende Gesundheitsuntersuchung — Labor, Bildgebung und Facharztkonsultation.',
 '<p>Check-up-Pakete für Männer, Frauen und Führungskräfte. Bluttests, EKG, USG, MRT und fachärztliche Beratung an einem Tag.</p>'),

('ortopedi','bi-bandaid','https://images.unsplash.com/photo-1551601651-2a8555f1a136?w=1200',6,
 'Ortopedi','Diz ve kalça protezi, artroskopi ve spor yaralanmaları.',
 '<p>Total diz ve kalça protezi, artroskopik diz cerrahisi, omuz ve spor yaralanmaları tedavisi.</p>',
 'Orthopedics','Knee/hip replacement, arthroscopy and sports injuries.',
 '<p>Total knee and hip replacement, arthroscopic knee surgery, shoulder and sports injury treatment.</p>',
 'Orthopädie','Knie- und Hüftprothetik, Arthroskopie und Sportverletzungen.',
 '<p>Totale Knie- und Hüftendoprothetik, arthroskopische Kniechirurgie, Schulter- und Sportverletzungsbehandlung.</p>');

-- ---------------------------------------------------------------------
-- DOKTORLAR
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS doktorlar;
CREATE TABLE doktorlar (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(120) UNIQUE NOT NULL,
  foto        VARCHAR(255),
  diller      VARCHAR(120),
  sira        INT DEFAULT 0,
  durum       TINYINT(1) DEFAULT 1,
  tr_ad       VARCHAR(120),
  tr_unvan    VARCHAR(160),
  tr_bio      MEDIUMTEXT,
  en_ad       VARCHAR(120),
  en_unvan    VARCHAR(160),
  en_bio      MEDIUMTEXT,
  de_ad       VARCHAR(120),
  de_unvan    VARCHAR(160),
  de_bio      MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO doktorlar (slug, foto, diller, sira, tr_ad, tr_unvan, tr_bio, en_ad, en_unvan, en_bio, de_ad, de_unvan, de_bio) VALUES
('op-dr-mehmet-yilmaz','https://images.unsplash.com/photo-1612531386530-97286d97c2d2?w=600','TR, EN',1,
 'Op. Dr. Mehmet Yılmaz','Plastik ve Estetik Cerrahi Uzmanı','<p>İstanbul Üniversitesi Tıp Fakültesi mezunu. 18 yıllık plastik ve estetik cerrahi deneyimi.</p>',
 'Op. Dr. Mehmet Yilmaz','Plastic and Aesthetic Surgery Specialist','<p>Graduate of Istanbul University Faculty of Medicine. 18 years of plastic and aesthetic surgery experience.</p>',
 'Op. Dr. Mehmet Yilmaz','Facharzt für Plastische und Ästhetische Chirurgie','<p>Absolvent der medizinischen Fakultät der Universität Istanbul. 18 Jahre Erfahrung in der plastischen und ästhetischen Chirurgie.</p>'),

('dt-elif-kaya','https://images.unsplash.com/photo-1594824476967-48c8b964273f?w=600','TR, EN, DE',2,
 'Dt. Elif Kaya','Diş Hekimi — Estetik Diş Hekimliği','<p>Hacettepe Üniversitesi mezunu. Smile design ve dijital diş hekimliği uzmanı.</p>',
 'Dt. Elif Kaya','Dentist — Aesthetic Dentistry','<p>Graduate of Hacettepe University. Specialist in smile design and digital dentistry.</p>',
 'Dr. med. dent. Elif Kaya','Zahnärztin — Ästhetische Zahnheilkunde','<p>Absolventin der Hacettepe-Universität. Spezialistin für Smile-Design und digitale Zahnheilkunde.</p>'),

('dr-ahmet-demir','https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=600','TR, EN',3,
 'Dr. Ahmet Demir','Saç Ekimi Uzmanı','<p>10+ yıllık deneyim, 3000''den fazla başarılı operasyon. DHI ve FUE Safir teknikleri.</p>',
 'Dr. Ahmet Demir','Hair Transplant Specialist','<p>10+ years of experience, more than 3000 successful operations. DHI and Sapphire FUE techniques.</p>',
 'Dr. Ahmet Demir','Spezialist für Haartransplantation','<p>10+ Jahre Erfahrung, über 3000 erfolgreiche Eingriffe. DHI- und Saphir-FUE-Techniken.</p>'),

('op-dr-ayse-celik','https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600','TR, EN, DE',4,
 'Op. Dr. Ayşe Çelik','Göz Hastalıkları Uzmanı','<p>Ege Üniversitesi mezunu. LASIK ve katarakt cerrahisinde 15 yıl deneyim.</p>',
 'Op. Dr. Ayse Celik','Ophthalmology Specialist','<p>Graduate of Ege University. 15 years of experience in LASIK and cataract surgery.</p>',
 'Op. Dr. Ayse Celik','Fachärztin für Augenheilkunde','<p>Absolventin der Ege-Universität. 15 Jahre Erfahrung in LASIK- und Katarakt-Chirurgie.</p>');

-- ---------------------------------------------------------------------
-- PARTNERLER (Yücelen Hastanesi, Zirve Dental, Your Beauty Clinic vb.)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS partnerler;
CREATE TABLE partnerler (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  ad           VARCHAR(160),
  logo         VARCHAR(255),
  site         VARCHAR(255),
  tip          ENUM('hastane','klinik','laboratuvar','sigorta','konaklama','digerleri') DEFAULT 'klinik',
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_aciklama  TEXT,
  en_aciklama  TEXT,
  de_aciklama  TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO partnerler (ad, logo, site, tip, sira, tr_aciklama, en_aciklama, de_aciklama) VALUES
('Yücelen Hastanesi','https://via.placeholder.com/240x100?text=Yucelen','#','hastane',1,
 'Marmaris''in en köklü özel hastanesi. Tüm branşlarda 7/24 hizmet.',
 'Marmaris''s most established private hospital. 24/7 service in all branches.',
 'Das etablierteste Privatkrankenhaus in Marmaris. 24/7-Service in allen Fachbereichen.'),
('Zirve Dental Clinic','https://via.placeholder.com/240x100?text=Zirve+Dental','#','klinik',2,
 'Diş tedavisinde Marmaris''in lider kliniği.',
 'Marmaris''s leading clinic in dental treatment.',
 'Die führende Klinik für Zahnbehandlungen in Marmaris.'),
('Your Beauty Clinic','https://via.placeholder.com/240x100?text=Your+Beauty','#','klinik',3,
 'Estetik cerrahi ve güzellik uygulamalarında uzmanlaşmış klinik.',
 'Clinic specialized in aesthetic surgery and beauty procedures.',
 'Auf ästhetische Chirurgie und Schönheitsbehandlungen spezialisierte Klinik.');

-- ---------------------------------------------------------------------
-- KONAKLAMA TESİSLERİ
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS konaklama;
CREATE TABLE konaklama (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(120) UNIQUE NOT NULL,
  gorsel       VARCHAR(255),
  yildiz       TINYINT DEFAULT 4,
  konum        VARCHAR(160),
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_ad        VARCHAR(160),
  tr_aciklama  TEXT,
  en_ad        VARCHAR(160),
  en_aciklama  TEXT,
  de_ad        VARCHAR(160),
  de_aciklama  TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO konaklama (slug, gorsel, yildiz, konum, sira, tr_ad, tr_aciklama, en_ad, en_aciklama, de_ad, de_aciklama) VALUES
('grand-marmaris-hotel','https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',5,'Marmaris Merkez',1,
 'Grand Marmaris Otel','Denize sıfır 5 yıldızlı otel. Tedavi sonrası dinlenme için ideal.',
 'Grand Marmaris Hotel','5-star beachfront hotel. Ideal for post-treatment recovery.',
 'Grand Marmaris Hotel','5-Sterne-Hotel direkt am Strand. Ideal zur Erholung nach der Behandlung.'),
('icmeler-resort','https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1200',4,'İçmeler',2,
 'İçmeler Resort','Sakin ve doğal bir ortamda boutique resort.',
 'Icmeler Resort','Boutique resort in a quiet, natural setting.',
 'Icmeler Resort','Boutique-Resort in ruhiger, natürlicher Umgebung.');

-- ---------------------------------------------------------------------
-- PAKETLER (Tedavi + Konaklama + Transfer)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS paketler;
CREATE TABLE paketler (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(120) UNIQUE NOT NULL,
  hizmet_id    INT,
  gorsel       VARCHAR(255),
  fiyat        DECIMAL(10,2),
  para_birimi  CHAR(3) DEFAULT 'EUR',
  sure_gun     INT DEFAULT 7,
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_baslik    VARCHAR(180),
  tr_icerik    MEDIUMTEXT,
  en_baslik    VARCHAR(180),
  en_icerik    MEDIUMTEXT,
  de_baslik    VARCHAR(180),
  de_icerik    MEDIUMTEXT,
  FOREIGN KEY (hizmet_id) REFERENCES hizmetler(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO paketler (slug, hizmet_id, gorsel, fiyat, para_birimi, sure_gun, sira, tr_baslik, tr_icerik, en_baslik, en_icerik, de_baslik, de_icerik) VALUES
('hollywood-smile-paketi',1,'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?w=1200',1990,'EUR',5,1,
 'Hollywood Smile Paketi','<ul><li>20 adet zirkonyum/lamine kaplama</li><li>4 gece 5 yıldızlı otel konaklama</li><li>Havalimanı transferi</li><li>Türkçe/İngilizce refakat</li></ul>',
 'Hollywood Smile Package','<ul><li>20 zirconia/laminate veneers</li><li>4 nights 5-star hotel accommodation</li><li>Airport transfer</li><li>Turkish/English assistance</li></ul>',
 'Hollywood Smile Paket','<ul><li>20 Zirkon-/Laminat-Veneers</li><li>4 Nächte 5-Sterne-Hotel</li><li>Flughafentransfer</li><li>TR/EN-Begleitung</li></ul>'),
('sac-ekimi-tatil',3,'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=1200',1490,'EUR',4,2,
 'Saç Ekimi + Tatil','<ul><li>FUE Safir saç ekimi (4500 greft)</li><li>3 gece otel</li><li>Tüm transferler</li><li>1 yıl takip garantisi</li></ul>',
 'Hair Transplant + Holiday','<ul><li>Sapphire FUE hair transplant (4500 grafts)</li><li>3 nights hotel</li><li>All transfers</li><li>1-year follow-up guarantee</li></ul>',
 'Haartransplantation + Urlaub','<ul><li>Saphir-FUE-Haartransplantation (4500 Grafts)</li><li>3 Nächte Hotel</li><li>Alle Transfers</li><li>1 Jahr Nachsorge-Garantie</li></ul>');

-- ---------------------------------------------------------------------
-- GALERİ (Before/After ve klinik görselleri)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS galeri;
CREATE TABLE galeri (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  hizmet_id     INT,
  oncesi        VARCHAR(255),
  sonrasi       VARCHAR(255),
  sira          INT DEFAULT 0,
  durum         TINYINT(1) DEFAULT 1,
  tr_aciklama   VARCHAR(255),
  en_aciklama   VARCHAR(255),
  de_aciklama   VARCHAR(255),
  FOREIGN KEY (hizmet_id) REFERENCES hizmetler(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO galeri (hizmet_id, oncesi, sonrasi, sira, tr_aciklama, en_aciklama, de_aciklama) VALUES
(1,'https://images.unsplash.com/photo-1606265752439-1f18756aa8ed?w=800','https://images.unsplash.com/photo-1581585504054-3a1086c95cea?w=800',1,
 'Hollywood Smile — 20 zirkonyum kaplama','Hollywood Smile — 20 zirconia veneers','Hollywood Smile — 20 Zirkon-Veneers'),
(3,'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=800','https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800',2,
 'FUE Safir saç ekimi — 12 ay sonrası','Sapphire FUE hair transplant — 12 months after','Saphir-FUE-Haartransplantation — 12 Monate später');

-- ---------------------------------------------------------------------
-- HASTA YORUMLARI (Testimonials)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS yorumlar;
CREATE TABLE yorumlar (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  hasta_adi    VARCHAR(120),
  ulke         VARCHAR(80),
  foto         VARCHAR(255),
  video_url    VARCHAR(255),
  puan         TINYINT DEFAULT 5,
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_yorum     TEXT,
  en_yorum     TEXT,
  de_yorum     TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO yorumlar (hasta_adi, ulke, foto, puan, sira, tr_yorum, en_yorum, de_yorum) VALUES
('Sarah M.','Almanya','https://i.pravatar.cc/150?img=47',5,1,
 'Saç ekimi için Marmaris''e geldim. Hem tedavi hem tatil mükemmeldi. Tüm ekibe teşekkürler!',
 'I came to Marmaris for hair transplant. Both the treatment and the holiday were perfect. Thanks to the whole team!',
 'Ich kam für eine Haartransplantation nach Marmaris. Behandlung und Urlaub waren perfekt. Danke an das gesamte Team!'),
('John P.','İngiltere','https://i.pravatar.cc/150?img=60',5,2,
 'Diş tedavim için seçtiğim en doğru karardı. Profesyonel, dürüst ve samimi.',
 'Choosing them for my dental treatment was the best decision. Professional, honest and friendly.',
 'Die Wahl für meine Zahnbehandlung war die beste Entscheidung. Professionell, ehrlich und freundlich.'),
('Klaus S.','Almanya','https://i.pravatar.cc/150?img=12',5,3,
 'Burun estetiği yaptırdım. Sonuçtan çok memnunum, herkese tavsiye ederim.',
 'I had rhinoplasty. Very happy with the result, I recommend to everyone.',
 'Ich hatte eine Nasenkorrektur. Sehr zufrieden mit dem Ergebnis, empfehle es jedem.');

-- ---------------------------------------------------------------------
-- BLOG
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS blog;
CREATE TABLE blog (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  slug         VARCHAR(160) UNIQUE NOT NULL,
  kapak        VARCHAR(255),
  yazar        VARCHAR(120) DEFAULT 'Editör',
  tarih        DATE,
  sira         INT DEFAULT 0,
  durum        TINYINT(1) DEFAULT 1,
  tr_baslik    VARCHAR(220),
  tr_ozet      VARCHAR(500),
  tr_icerik    MEDIUMTEXT,
  en_baslik    VARCHAR(220),
  en_ozet      VARCHAR(500),
  en_icerik    MEDIUMTEXT,
  de_baslik    VARCHAR(220),
  de_ozet      VARCHAR(500),
  de_icerik    MEDIUMTEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO blog (slug, kapak, tarih, sira, tr_baslik, tr_ozet, tr_icerik, en_baslik, en_ozet, en_icerik, de_baslik, de_ozet, de_icerik) VALUES
('marmaris-saglik-turizmi-rehberi','https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=1200','2026-04-01',1,
 'Marmaris Sağlık Turizmi Rehberi','Neden Marmaris? Tedavi paketleri, mevsim seçimi, ulaşım ve daha fazlası.',
 '<p>Marmaris, Türkiye''nin sağlık turizmi haritasında hızla yükselen şehirlerinden biri. Bu rehberde Marmaris''i tercih etme nedenlerini, tedavi paketlerini ve mevsim seçimini ele alıyoruz.</p>',
 'Marmaris Health Tourism Guide','Why Marmaris? Treatment packages, season choice, travel and more.',
 '<p>Marmaris is one of the rapidly rising cities on Turkey''s health tourism map. In this guide we discuss reasons to choose Marmaris, treatment packages and season choice.</p>',
 'Marmaris Gesundheitstourismus-Leitfaden','Warum Marmaris? Behandlungspakete, Jahreszeit, Anreise und mehr.',
 '<p>Marmaris ist eine der schnell aufsteigenden Städte auf der Karte des Gesundheitstourismus in der Türkei. In diesem Leitfaden erläutern wir Gründe für Marmaris.</p>'),
('dis-implant-sureci','https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=1200','2026-03-20',2,
 'Diş İmplantı Süreci A''dan Z''ye','İmplant nedir, kimlere uygulanır, iyileşme ne kadar sürer?',
 '<p>Diş implantı, kaybedilen dişlerin yerine titanyum vida ile yapay kök yerleştirilmesidir...</p>',
 'Dental Implant Process A to Z','What is an implant, who is it for, how long does healing take?',
 '<p>A dental implant replaces a lost tooth by placing a titanium screw as an artificial root...</p>',
 'Zahnimplantat-Prozess von A bis Z','Was ist ein Implantat, für wen, wie lange dauert die Heilung?',
 '<p>Ein Zahnimplantat ersetzt einen verlorenen Zahn durch eine Titanschraube als künstliche Wurzel...</p>');

-- ---------------------------------------------------------------------
-- MESAJLAR (İletişim formundan)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS mesajlar;
CREATE TABLE mesajlar (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  ad          VARCHAR(120),
  eposta      VARCHAR(160),
  telefon     VARCHAR(40),
  konu        VARCHAR(180),
  mesaj       TEXT,
  ip          VARCHAR(45),
  ua          VARCHAR(255),
  dil         CHAR(2) DEFAULT 'tr',
  durum       ENUM('yeni','okundu','silindi') DEFAULT 'yeni',
  tarih       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- TEKLİF TALEPLERİ (multi-step form)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS teklif_talepleri;
CREATE TABLE teklif_talepleri (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  ad           VARCHAR(120),
  eposta       VARCHAR(160),
  telefon      VARCHAR(40),
  ulke         VARCHAR(80),
  hizmet_id    INT,
  tarih_tercih VARCHAR(80),
  mesaj        TEXT,
  ip           VARCHAR(45),
  ua           VARCHAR(255),
  dil          CHAR(2) DEFAULT 'tr',
  durum        ENUM('yeni','iletildi','kapandi') DEFAULT 'yeni',
  olusturma    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (hizmet_id) REFERENCES hizmetler(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- ADMIN
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  kullanici    VARCHAR(60)  UNIQUE NOT NULL,
  sifre_hash   VARCHAR(255) NOT NULL,
  ad_soyad     VARCHAR(120),
  eposta       VARCHAR(160),
  son_giris    DATETIME,
  son_giris_ip VARCHAR(45),
  durum        TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Varsayılan giriş: admin / admin123  (canlıya yüklerken DEĞİŞTİR!)
INSERT INTO admin (kullanici, sifre_hash, ad_soyad, eposta) VALUES
('admin','$2y$10$6gNrpOXRLof.Toy.Ugy.yu4DrKP23OcnJa7UBhKOH1iMxbtH8GK0.','Yönetici','info@marmarishealthcenter.com');

-- ---------------------------------------------------------------------
-- GİRİŞ LOGLARI (brute-force için)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS giris_loglari;
CREATE TABLE giris_loglari (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  ip          VARCHAR(45),
  kullanici   VARCHAR(60),
  basarili    TINYINT(1) DEFAULT 0,
  tarih       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ip_tarih (ip, tarih)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- BİTTİ. Toplam 13 tablo.
-- =====================================================================
