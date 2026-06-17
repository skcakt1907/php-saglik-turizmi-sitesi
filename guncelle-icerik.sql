-- =====================================================================
-- Marmaris Health Center - GERÇEK İÇERİK GÜNCELLEME
-- (referans: https://www.marmarishealthcenter.com.tr/)
-- =====================================================================
USE marmaris;
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- AYARLAR — gerçek iletişim & metinler
-- ---------------------------------------------------------------------
INSERT INTO ayarlar (anahtar, deger) VALUES
  ('site_adi',           'Marmaris Health Center'),
  ('sirket_adi',         'Marmaris Travel Center Turizm Taşımacılık Gıda İnşaat ve Ticaret Limited Şirketi'),
  ('vergi',              'Marmaris VD - 612 072 02 18'),
  ('site_slogan_tr',     'Sağlık ve seyahat için tek adres — Marmaris\'te'),
  ('site_slogan_en',     'Your single address for health and travel — in Marmaris'),
  ('site_slogan_de',     'Ihre einzige Adresse für Gesundheit und Reisen — in Marmaris'),
  ('telefon',            '+90 252 417 13 33'),
  ('mobil',              '+90 532 296 47 85'),
  ('whatsapp',           '+905322964785'),
  ('eposta',             'info@ornek-seyahat.com'),
  ('calisma_saati_tr',   '07:00 — 24:00'),
  ('calisma_saati_en',   '07:00 — 24:00'),
  ('calisma_saati_de',   '07:00 — 24:00'),
  ('adres_tr',           'Armutalan Mah., Şehit Ahmet Benler Cad., No: 26/Z3, Marmaris / Muğla'),
  ('adres_en',           'Armutalan, Sehit Ahmet Benler Cad., No: 26/Z3, Marmaris / Mugla, Türkiye'),
  ('adres_de',           'Armutalan, Sehit Ahmet Benler Cad., Nr. 26/Z3, Marmaris / Mugla, Türkei'),
  ('hero_baslik_tr',     'Marmaris\'te Dünya Standartlarında Sağlık ve Seyahat'),
  ('hero_baslik_en',     'World-Class Health and Travel in Marmaris'),
  ('hero_baslik_de',     'Weltklasse-Gesundheit und Reisen in Marmaris'),
  ('hero_alt_tr',        'Tedavi, konaklama, transfer, bilet ve sigorta — hepsini biz organize edelim.'),
  ('hero_alt_en',        'Treatment, accommodation, transfer, tickets and insurance — let us organize everything.'),
  ('hero_alt_de',        'Behandlung, Unterkunft, Transfer, Tickets und Versicherung — wir organisieren alles.'),
  ('seo_aciklama_tr',    'Marmaris\'in deneyimli sağlık turizmi danışmanı. Anlaşmalı hastane ve kliniklerle uçtan uca tedavi+konaklama+transfer paketleri.'),
  ('seo_aciklama_en',    'Marmaris\'s experienced health tourism consultancy. End-to-end treatment+accommodation+transfer packages with partner hospitals & clinics.'),
  ('seo_aciklama_de',    'Erfahrener Gesundheitstourismus-Berater in Marmaris. End-to-End-Behandlungs-, Unterkunfts- und Transferpakete.'),
  ('harita_embed',       '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3192.5388767088743!2d28.248734315683133!3d36.85351827993763!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14bfbc2b68c29025%3A0x226e3087144ad771!2sMikey%27s+Place+-+Marmaris+Travel+Center!5e0!3m2!1str!2str!4v0000000000" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'),
  ('instagram',          ''),
  ('facebook',           ''),
  ('youtube',            '')
ON DUPLICATE KEY UPDATE deger = VALUES(deger);

-- ---------------------------------------------------------------------
-- PARTNERLER — referans sitedeki 3 ortağı gerçek metinle güncelle
-- ---------------------------------------------------------------------
DELETE FROM partnerler;

INSERT INTO partnerler (ad, logo, site, tip, sira, durum, tr_aciklama, en_aciklama, de_aciklama) VALUES
('Yücelen Hastanesi','','#','hastane',1,1,
 '<p>Marmaris\'in köklü özel hastanesi. Uzman hekim kadromuzla aşağıdaki bölümlerde hizmet verilmektedir:</p><ul><li>Ağız ve Diş Sağlığı</li><li>Beslenme ve Diyetetik</li><li>Beyin ve Sinir Cerrahisi</li><li>Dermatoloji</li><li>Çocuk Hekimliği</li><li>İç Hastalıkları</li><li>Fizik Tedavi ve Rehabilitasyon</li><li>Genel Cerrahi</li><li>Göz Hekimliği</li><li>Göğüs Hastalıkları</li><li>Kadın Hastalıkları ve Doğum</li><li>Kardiyoloji</li><li>Kulak Burun Boğaz</li><li>Nöroloji</li><li>Ortodonti</li><li>Ortopedi ve Travmatoloji</li><li>Plastik Cerrahi</li><li>Psikiyatri</li><li>Psikoloji</li><li>Radyoloji</li><li>Üroloji</li></ul>',
 '<p>Marmaris\'s established private hospital. Our specialist doctors provide services in the following departments:</p><ul><li>Oral and Dental Health</li><li>Nutrition and Dietetics</li><li>Brain and Neurosurgery</li><li>Dermatology</li><li>Pediatrics</li><li>Internal Medicine</li><li>Physical Therapy and Rehabilitation</li><li>General Surgery</li><li>Ophthalmology</li><li>Chest Diseases</li><li>Gynecology and Obstetrics</li><li>Cardiology</li><li>Ear, Nose and Throat</li><li>Neurology</li><li>Orthodontics</li><li>Orthopedics and Traumatology</li><li>Plastic Surgery</li><li>Psychiatry</li><li>Psychology</li><li>Radiology</li><li>Urology</li></ul>',
 '<p>Etabliertes Privatkrankenhaus in Marmaris. Unsere Fachärzte bieten Leistungen in folgenden Abteilungen:</p><ul><li>Mund- und Zahngesundheit</li><li>Ernährung und Diätetik</li><li>Hirn- und Nervenchirurgie</li><li>Dermatologie</li><li>Kinderheilkunde</li><li>Innere Medizin</li><li>Physikalische Therapie und Rehabilitation</li><li>Allgemeinchirurgie</li><li>Augenheilkunde</li><li>Lungenheilkunde</li><li>Frauenheilkunde und Geburtshilfe</li><li>Kardiologie</li><li>HNO</li><li>Neurologie</li><li>Kieferorthopädie</li><li>Orthopädie und Traumatologie</li><li>Plastische Chirurgie</li><li>Psychiatrie</li><li>Psychologie</li><li>Radiologie</li><li>Urologie</li></ul>'),

('Your Beauty Clinic','','#','klinik',2,1,
 '<p>Polikliniğimizi ziyaret ederek gerçekleştirdiğimiz operasyonlar hakkında doğru bilgi edinin.</p><ul><li>15 yıllık deneyim</li><li>Hızlı etki</li><li>Doğal sonuç</li><li>Bireysel estetik çözümler</li><li>Estetiğin altın üçlüsü</li><li>En yeni ve güvenli yöntemler</li></ul>',
 '<p>Visit our policlinic and get the right perspective on the surgeries we perform.</p><ul><li>15 Years Experience</li><li>Quick Effect</li><li>Natural Result</li><li>Individual Aesthetic Solutions</li><li>Golden Triad of Aesthetics</li><li>Newest and Safe Methods</li></ul>',
 '<p>Besuchen Sie unsere Poliklinik und erhalten Sie die richtige Perspektive auf die Operationen, die wir durchführen.</p><ul><li>15 Jahre Erfahrung</li><li>Schnelle Wirkung</li><li>Natürliches Ergebnis</li><li>Individuelle ästhetische Lösungen</li><li>Goldenes Dreieck der Ästhetik</li><li>Neueste und sichere Methoden</li></ul>'),

('Zirve Dental Clinic','','#','klinik',3,1,
 '<p>Marmaris Zirve Dental Clinic, uzmanlığı en son teknolojiyle birleştirerek diş bakımı sunar. Yüksek kaliteli diş bakımı; ileri görüşlü yaklaşım ve modern teknolojiye bağlılıkla, sorunsuz ve konforlu bir deneyim için kişiselleştirilmiş diş tedavileri sunuyoruz. Sağlığınız ve gülümsemeniz önceliğimizdir.</p><ul><li>Kozmetik Tedaviler</li><li>All-on-4 ve All-on-6 implant çözümleri</li><li>Diş Beyazlatma</li><li>Veneer ve Gülüş Tasarımı</li></ul>',
 '<p>Marmaris Zirve Dental Clinic provides dental care by combining expertise with the latest technology. High-quality dental care. With a forward-looking approach and commitment to modern technology, we provide personalized dental treatments for a smooth and comfortable experience. Your health and smile are our priority.</p><ul><li>Cosmetic Treatments</li><li>All-on-4 and All-on-6 implant solutions</li><li>Teeth Whitening</li><li>Veneers and Smile Design</li></ul>',
 '<p>Marmaris Zirve Dental Clinic bietet Zahnpflege durch die Kombination von Expertise mit neuester Technologie. Hochwertige Zahnpflege. Mit einem zukunftsorientierten Ansatz und der Verpflichtung zu moderner Technologie bieten wir personalisierte Zahnbehandlungen für ein reibungsloses und komfortables Erlebnis. Ihre Gesundheit und Ihr Lächeln haben für uns Priorität.</p><ul><li>Kosmetische Behandlungen</li><li>All-on-4 und All-on-6 Implantatlösungen</li><li>Zahnaufhellung</li><li>Veneers und Smile Design</li></ul>');

-- ---------------------------------------------------------------------
-- HİZMETLER — referans site (Health Services tek paragraf) ile uyumlu
-- ana sayfa kartları için 6 ana hizmet (sağlık branşları)
-- ---------------------------------------------------------------------
DELETE FROM galeri;
DELETE FROM paketler;
DELETE FROM hizmetler;

INSERT INTO hizmetler (slug, ikon, gorsel, sira, durum, tr_baslik, tr_ozet, tr_icerik, en_baslik, en_ozet, en_icerik, de_baslik, de_ozet, de_icerik) VALUES
('saglik-danismanligi','bi-clipboard2-pulse','',1,1,
 'Sağlık Danışmanlığı','Anlaşmalı doktor ve sağlık kuruluşlarımızla size en uygun tedavi planını oluşturuyoruz.',
 '<p>Marmaris Health Center danışman ekibinin temel hedefi, anlaşmalı doktor ve sağlık kuruluşlarımız aracılığıyla misafirlerimize doğru tedavi seçeneklerini oluşturmaktır. Anlaşmalı sağlık profesyonellerimiz raporlarınızı inceler ve sizin için en uygun tedavi planını hazırlar.</p>',
 'Health Consultancy','We create the most suitable treatment plan with our partner doctors and healthcare institutions.',
 '<p>The main goal of the Marmaris Health Center consultancy team is to help our guests create the right treatment options through our partner doctors and healthcare institutions. Our partner medical professionals review your reports and prepare the most suitable treatment plan for you.</p>',
 'Gesundheitsberatung','Wir erstellen mit unseren Partnerärzten den passendsten Behandlungsplan für Sie.',
 '<p>Das Hauptziel des Beratungsteams von Marmaris Health Center ist es, unseren Gästen bei der Planung geeigneter Behandlungsmöglichkeiten zu helfen. Unsere Partnerärzte und Gesundheitseinrichtungen prüfen Ihre medizinischen Unterlagen sorgfältig und erstellen den besten Behandlungsplan.</p>'),

('dis-tedavisi','bi-emoji-smile','',2,1,
 'Diş Tedavisi','İmplant, zirkonyum, lamine veneer, gülüş tasarımı — Zirve Dental Clinic ile.',
 '<p>Anlaşmalı kliniğimiz Zirve Dental Clinic ile kozmetik tedaviler, All-on-4 / All-on-6 implant çözümleri, diş beyazlatma, veneer ve gülüş tasarımı uygulamaları sunulmaktadır.</p>',
 'Dental Treatment','Implants, zirconia, veneers, smile design — with Zirve Dental Clinic.',
 '<p>Through our partner Zirve Dental Clinic: cosmetic treatments, All-on-4 / All-on-6 implant solutions, teeth whitening, veneers and smile design.</p>',
 'Zahnbehandlung','Implantate, Zirkon, Veneers, Smile Design — mit Zirve Dental Clinic.',
 '<p>Über unsere Partnerklinik Zirve Dental Clinic: kosmetische Behandlungen, All-on-4 / All-on-6 Implantate, Bleaching, Veneers und Smile Design.</p>'),

('estetik-cerrahi','bi-stars','',3,1,
 'Estetik ve Plastik Cerrahi','15 yıllık deneyim, doğal sonuç ve bireysel estetik çözümler — Your Beauty Clinic ile.',
 '<p>Your Beauty Clinic ortağımızla 15 yıllık deneyim, hızlı etki, doğal sonuç ve estetiğin altın üçlüsü prensibiyle bireysel estetik çözümler sunulmaktadır. En yeni ve güvenli yöntemler kullanılmaktadır.</p>',
 'Aesthetic & Plastic Surgery','15 years experience, natural results and individual aesthetic solutions — with Your Beauty Clinic.',
 '<p>Through our partner Your Beauty Clinic: 15 years of experience, quick effect, natural result and individual aesthetic solutions following the golden triad principle, using the newest and safest methods.</p>',
 'Ästhetische & Plastische Chirurgie','15 Jahre Erfahrung, natürliche Ergebnisse — mit Your Beauty Clinic.',
 '<p>Mit unserem Partner Your Beauty Clinic: 15 Jahre Erfahrung, schnelle Wirkung, natürliches Ergebnis und individuelle ästhetische Lösungen nach dem Prinzip des goldenen Dreiecks.</p>'),

('kadin-dogum','bi-heart','',4,1,
 'Kadın Hastalıkları ve Doğum','Yücelen Hastanesi\'nde kadın doğum ve jinekoloji hizmetleri.',
 '<p>Anlaşmalı hastanemiz Yücelen Hastanesi\'nde kadın hastalıkları, doğum ve jinekoloji uzmanlarımızla hizmet sunulmaktadır.</p>',
 'Gynecology & Obstetrics','Comprehensive women\'s health and obstetrics services at Yücelen Hospital.',
 '<p>Comprehensive women\'s health, obstetrics and gynecology services at our partner Yücelen Hospital.</p>',
 'Frauenheilkunde & Geburtshilfe','Frauenheilkunde und Geburtshilfe im Yücelen Krankenhaus.',
 '<p>Umfassende Frauenheilkunde, Geburtshilfe und Gynäkologie in unserem Partnerkrankenhaus Yücelen.</p>'),

('goz-hekimligi','bi-eye','',5,1,
 'Göz Hekimliği','Tüm göz hastalıkları, katarakt ve refraktif cerrahi.',
 '<p>Yücelen Hastanesi göz hekimliği bölümünde tüm göz rahatsızlıkları, katarakt cerrahisi ve refraktif (LASIK) uygulamalar yapılmaktadır.</p>',
 'Ophthalmology','All eye diseases, cataract and refractive surgery.',
 '<p>At Yücelen Hospital ophthalmology department: all eye conditions, cataract surgery and refractive (LASIK) procedures.</p>',
 'Augenheilkunde','Alle Augenerkrankungen, Katarakt und refraktive Chirurgie.',
 '<p>In der Augenabteilung des Yücelen-Krankenhauses: alle Augenerkrankungen, Katarakt-Chirurgie und refraktive (LASIK) Verfahren.</p>'),

('ortopedi','bi-bandaid','',6,1,
 'Ortopedi ve Travmatoloji','Diz ve kalça protezi, artroskopi, spor yaralanmaları.',
 '<p>Yücelen Hastanesi ortopedi ve travmatoloji bölümünde diz/kalça protezi, artroskopik girişimler ve spor yaralanmaları tedavisi sunulmaktadır.</p>',
 'Orthopedics & Traumatology','Knee and hip replacement, arthroscopy, sports injuries.',
 '<p>At Yücelen Hospital orthopedics & traumatology department: knee/hip replacement, arthroscopic procedures and sports injury treatment.</p>',
 'Orthopädie & Traumatologie','Knie- und Hüftendoprothetik, Arthroskopie, Sportverletzungen.',
 '<p>In der orthopädischen und traumatologischen Abteilung des Yücelen-Krankenhauses: Knie-/Hüftendoprothetik, arthroskopische Eingriffe und Behandlung von Sportverletzungen.</p>');

-- ---------------------------------------------------------------------
-- KONAKLAMA — gerçek site metnine uyumla
-- ---------------------------------------------------------------------
DELETE FROM konaklama;

INSERT INTO konaklama (slug, gorsel, yildiz, konum, sira, durum, tr_ad, tr_aciklama, en_ad, en_aciklama, de_ad, de_aciklama) VALUES
('anlasmali-oteller','',5,'Marmaris',1,1,
 'Anlaşmalı Oteller',
 'Konforlu ve şık seçeneklerden lüks otellere kadar — kendinizi evinizdeymiş gibi hissedeceğiniz, hastaneye veya şehir merkezine yakın anlaşmalı otellerde konaklama imkânı sunuyoruz.',
 'Partner Hotels',
 'From comfortable and stylish options to luxury hotels — we provide accommodation at partner hotels near your hospital or in the city center, where you can feel at home.',
 'Partner-Hotels',
 'Von komfortablen und stilvollen Optionen bis hin zu Luxushotels — wir bieten Unterkünfte in Partnerhotels nahe Ihrem Krankenhaus oder im Stadtzentrum.');

-- ---------------------------------------------------------------------
-- BLOG — referans sitede yok, mevcut 2 yazı kalsın (silmiyoruz)
-- ---------------------------------------------------------------------

-- ---------------------------------------------------------------------
-- DOKTORLAR — referans sitede yok, mevcut 4 doktor placeholder kalsın
-- ---------------------------------------------------------------------

SELECT 'BİTTİ' AS durum,
       (SELECT COUNT(*) FROM ayarlar)    AS ayarlar,
       (SELECT COUNT(*) FROM hizmetler)  AS hizmet,
       (SELECT COUNT(*) FROM partnerler) AS partner,
       (SELECT COUNT(*) FROM konaklama)  AS konaklama;
