<?php
$baslik = 'Site Ayarları';
require_once __DIR__ . '/inc/layout-ust.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && csrf_check($_POST['_csrf']??null)) {
    $st = $db->prepare("INSERT INTO ayarlar (anahtar,deger) VALUES (?,?) ON DUPLICATE KEY UPDATE deger=VALUES(deger)");
    foreach ($_POST['ayarlar'] ?? [] as $anahtar => $deger) {
        $st->execute([$anahtar, $deger]);
    }
    header('Location: ayarlar?ok=1'); exit;
}

$tum = $db->query("SELECT anahtar, deger FROM ayarlar ORDER BY anahtar")->fetchAll(PDO::FETCH_KEY_PAIR);

// Gruplara böl
$gruplar = [
    'Genel' => ['site_adi','telefon','whatsapp','eposta','instagram','facebook','youtube'],
    'Türkçe (TR)'  => ['site_slogan_tr','hero_baslik_tr','hero_alt_tr','adres_tr','seo_aciklama_tr'],
    'English (EN)' => ['site_slogan_en','hero_baslik_en','hero_alt_en','adres_en','seo_aciklama_en'],
    'Deutsch (DE)' => ['site_slogan_de','hero_baslik_de','hero_alt_de','adres_de','seo_aciklama_de'],
    'Harita / Diğer' => ['harita_embed'],
];
?>
<?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Ayarlar güncellendi.</div><?php endif; ?>
<form method="post">
  <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

  <ul class="nav nav-tabs mb-0">
    <?php $i=0; foreach (array_keys($gruplar) as $g): ?>
      <li class="nav-item"><a class="nav-link <?= $i++===0?'active':'' ?>" data-bs-toggle="tab" href="#g-<?= md5($g) ?>"><?= e($g) ?></a></li>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content border border-top-0 p-4 bg-white rounded-bottom">
    <?php $j=0; foreach ($gruplar as $g => $anahtarlar): ?>
      <div class="tab-pane fade <?= $j++===0?'show active':'' ?>" id="g-<?= md5($g) ?>">
        <?php foreach ($anahtarlar as $a):
          $cokSatir = in_array($a, ['harita_embed','seo_aciklama_tr','seo_aciklama_en','seo_aciklama_de','hero_alt_tr','hero_alt_en','hero_alt_de']);
        ?>
          <div class="mb-3">
            <label class="form-label small text-muted"><?= e($a) ?></label>
            <?php if ($cokSatir): ?>
              <textarea class="form-control" name="ayarlar[<?= e($a) ?>]" rows="3"><?= e($tum[$a] ?? '') ?></textarea>
            <?php else: ?>
              <input class="form-control" name="ayarlar[<?= e($a) ?>]" value="<?= e($tum[$a] ?? '') ?>">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save"></i> Tümünü Kaydet</button></div>
</form>
<?php require_once __DIR__ . '/inc/layout-alt.php'; ?>
