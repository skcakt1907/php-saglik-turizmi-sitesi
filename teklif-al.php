<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/dil.php';
require_once __DIR__ . '/inc/helpers.php';

$basarili = false;
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['_csrf'] ?? null)) {
        $hata = t('form_hata');
    } else {
        $ad        = trim($_POST['ad']        ?? '');
        $eposta    = trim($_POST['eposta']    ?? '');
        $telefon   = trim($_POST['telefon']   ?? '');
        $ulke      = trim($_POST['ulke']      ?? '');
        $hizmet_id = (int)($_POST['hizmet_id'] ?? 0) ?: null;
        $tarih     = trim($_POST['tarih']     ?? '');
        $mesaj     = trim($_POST['mesaj']     ?? '');
        $hp        = trim($_POST['website']   ?? '');

        if ($hp !== '' || $ad === '' || !filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            $hata = t('form_hata');
        } else {
            $st = $db->prepare("INSERT INTO teklif_talepleri (ad,eposta,telefon,ulke,hizmet_id,tarih_tercih,mesaj,ip,ua,dil) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $st->execute([$ad, $eposta, $telefon, $ulke, $hizmet_id, $tarih, $mesaj, istemciIP(), substr($_SERVER['HTTP_USER_AGENT']??'',0,250), $DIL]);
            $basarili = true;
        }
    }
}

$sayfaBaslik = t('teklif_baslik');
require_once __DIR__ . '/inc/header.php';
$hizmetler = getList('hizmetler','durum=1');
$onSecHizmet = $_GET['hizmet'] ?? '';
?>
<section class="page-hero">
  <div class="container">
    <h1><?= t('teklif_baslik') ?></h1>
    <p class="mb-0 opacity-75"><?= t('teklif_alt') ?></p>
  </div>
</section>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="content-block">
          <?php if ($basarili): ?>
            <div class="alert alert-success">
              <h5><?= $DIL==='tr'?'Teklifiniz alındı! 🎉':($DIL==='de'?'Ihre Anfrage wurde empfangen! 🎉':'Your request has been received! 🎉') ?></h5>
              <p class="mb-0"><?= t('form_basarili') ?></p>
            </div>
            <a class="btn btn-primary mt-3" href="<?= e(url('')) ?>"><?= t('anasayfaya_don') ?></a>
          <?php else: ?>
            <?php if ($hata): ?><div class="alert alert-danger"><?= e($hata) ?></div><?php endif; ?>

            <form method="post" data-quote-form novalidate>
              <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
              <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;">

              <div class="quote-stepper">
                <div class="step active"><?= t('teklif_adim1') ?></div>
                <div class="step"><?= t('teklif_adim2') ?></div>
                <div class="step"><?= t('teklif_adim3') ?></div>
              </div>

              <!-- Adım 1: Hizmet -->
              <div data-step="1">
                <h5 class="text-primary mb-3"><?= t('form_hizmet') ?></h5>
                <div class="row g-3">
                  <?php foreach ($hizmetler as $h): ?>
                    <div class="col-md-6">
                      <label class="d-flex align-items-center p-3 border rounded-3 h-100" style="cursor:pointer;">
                        <input class="form-check-input me-3" type="radio" name="hizmet_id" value="<?= (int)$h['id'] ?>" <?= ($onSecHizmet === $h['slug']) ? 'checked' : '' ?> required>
                        <div>
                          <i class="bi <?= e($h['ikon']) ?> text-primary fs-4"></i>
                          <strong class="d-block mt-1"><?= e(dilliAlan($h,'baslik')) ?></strong>
                          <small class="text-muted"><?= e(mb_strimwidth(dilliAlan($h,'ozet'),0,80,'…')) ?></small>
                        </div>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="text-end mt-4">
                  <button type="button" class="btn btn-cta" data-next><?= t('ileri') ?> <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Adım 2: Bilgiler -->
              <div data-step="2" class="d-none">
                <h5 class="text-primary mb-3"><?= t('teklif_adim2') ?></h5>
                <div class="row g-3">
                  <div class="col-md-6"><label class="form-label"><?= t('form_ad') ?> *</label><input name="ad" class="form-control" required></div>
                  <div class="col-md-6"><label class="form-label"><?= t('form_eposta') ?> *</label><input type="email" name="eposta" class="form-control" required></div>
                  <div class="col-md-6"><label class="form-label"><?= t('form_telefon') ?></label><input name="telefon" class="form-control"></div>
                  <div class="col-md-6"><label class="form-label"><?= t('form_ulke') ?></label><input name="ulke" class="form-control"></div>
                  <div class="col-md-6"><label class="form-label"><?= t('form_tarih') ?></label><input name="tarih" type="text" placeholder="<?= e($DIL==='tr'?'Örn: Mayıs 2026':($DIL==='de'?'z.B. Mai 2026':'e.g. May 2026')) ?>" class="form-control"></div>
                  <div class="col-12"><label class="form-label"><?= t('form_mesaj') ?></label><textarea name="mesaj" rows="3" class="form-control"></textarea></div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                  <button type="button" class="btn btn-outline-primary" data-prev><i class="bi bi-arrow-left"></i> <?= t('geri') ?></button>
                  <button type="button" class="btn btn-cta" data-next><?= t('ileri') ?> <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Adım 3: Onay -->
              <div data-step="3" class="d-none">
                <h5 class="text-primary mb-3"><?= t('teklif_adim3') ?></h5>
                <p class="text-muted"><?= $DIL==='tr'?'Bilgilerinizi gönderin, ekibimiz 24 saat içinde size özel teklif hazırlayıp dönüş yapacak.':($DIL==='de'?'Senden Sie Ihre Daten ab — unser Team meldet sich innerhalb von 24 Stunden mit einem individuellen Angebot.':'Submit your details — our team will respond within 24 hours with a custom quote.') ?></p>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="kvkk" required>
                  <label class="form-check-label" for="kvkk"><?= t('form_kvkk') ?></label>
                </div>
                <div class="d-flex justify-content-between">
                  <button type="button" class="btn btn-outline-primary" data-prev><i class="bi bi-arrow-left"></i> <?= t('geri') ?></button>
                  <button type="submit" class="btn btn-cta"><i class="bi bi-send me-1"></i> <?= t('gonder') ?></button>
                </div>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/inc/footer.php'; ?>
