<footer class="site-footer mt-5 pt-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <img src="<?= SITE_URL ?>/img/logo2.jpg" alt="<?= e(ayar('site_adi')) ?>" class="footer-logo mb-3">
        <h6 class="fw-bold"><?= e(ayar('site_adi')) ?></h6>
        <p class="small text-white-50 mt-2"><?= e(ayarDilli('site_slogan')) ?></p>
        <div class="social-icons mt-3">
          <?php if (ayar('instagram')): ?><a href="<?= e(ayar('instagram')) ?>" target="_blank"><i class="bi bi-instagram"></i></a><?php endif; ?>
          <?php if (ayar('facebook')):  ?><a href="<?= e(ayar('facebook'))  ?>" target="_blank"><i class="bi bi-facebook"></i></a><?php endif; ?>
          <?php if (ayar('youtube')):   ?><a href="<?= e(ayar('youtube'))   ?>" target="_blank"><i class="bi bi-youtube"></i></a><?php endif; ?>
        </div>

        <?php
          // "Son güncelleme" = site dosyalarının en son değiştirildiği/yüklendiği tarih.
          // Hem kök sayfalar hem inc/ altındaki parçalar taranır — aksi halde sadece
          // footer/header güncellenince tarih eski kalıyordu.
          $ushFiles = array_merge(
              glob(__DIR__ . '/../*.php') ?: [],
              glob(__DIR__ . '/*.php') ?: []
          );
          $ushTimes = array_filter(array_map('filemtime', $ushFiles));
          $ushMtime = $ushTimes ? max($ushTimes) : time();
        ?>
        <div class="ushas-belge mt-4">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <img src="<?= SITE_URL ?>/img/ushas-logo.png" alt="USHAŞ"
                 style="height:64px;width:auto;background:#fff;border-radius:8px;padding:6px;">
            <a href="<?= SITE_URL ?>/img/ushas-belge.jpg" target="_blank" rel="noopener"
               title="USHAŞ Uluslararası Sağlık Turizmi Yetki Belgesi — Belge No: AK-1397">
              <img src="<?= SITE_URL ?>/img/ushas-belge.jpg"
                   alt="USHAŞ Uluslararası Sağlık Turizmi Yetki Belgesi — Belge No: AK-1397"
                   loading="lazy"
                   style="height:64px;width:auto;background:#fff;border-radius:8px;padding:6px;box-shadow:0 2px 10px rgba(0,0,0,.15);">
            </a>
          </div>
          <div class="small text-white-50 mt-2" style="line-height:1.6;">
            USHAŞ Belge No: AK-1397 &nbsp;·&nbsp; Son güncelleme: <?= date('d.m.Y', $ushMtime) ?>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="fw-bold mb-3"><?= t('hizli_baglantilar') ?></h6>
        <ul class="list-unstyled small">
          <li><a href="<?= e(url('saglik-hizmetleri')) ?>"><?= t('menu_hizmetler') ?></a></li>
          <li><a href="<?= e(url('konaklama')) ?>"><?= t('menu_konaklama') ?></a></li>
          <li><a href="<?= e(url('transfer')) ?>"><?= t('menu_transfer') ?></a></li>
          <li><a href="<?= e(url('bilet-seyahat')) ?>"><?= t('menu_bilet') ?></a></li>
          <li><a href="<?= e(url('sigorta')) ?>"><?= t('menu_sigorta') ?></a></li>
          <li><a href="<?= e(url('iletisim')) ?>"><?= t('menu_iletisim') ?></a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-3">
        <h6 class="fw-bold mb-3"><?= t('menu_partnerler') ?></h6>
        <ul class="list-unstyled small">
          <?php foreach (getList('partnerler','durum=1') as $p): ?>
            <li><a href="<?= e(url('partner-tesisler')) ?>#partner-<?= (int)$p['id'] ?>"><?= e($p['ad']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-lg-3">
        <h6 class="fw-bold mb-3"><?= t('iletisim_bilgi') ?></h6>
        <ul class="list-unstyled small text-white-50">
          <li class="mb-2"><i class="bi bi-geo-alt me-2"></i><?= e(ayarDilli('adres')) ?></li>
          <li class="mb-2"><i class="bi bi-telephone me-2"></i><a class="text-white" href="tel:<?= e(ayar('telefon')) ?>"><?= e(ayar('telefon')) ?></a></li>
          <li class="mb-2"><i class="bi bi-envelope me-2"></i><a class="text-white" href="mailto:<?= e(ayar('eposta')) ?>"><?= e(ayar('eposta')) ?></a></li>
          <li class="mb-2"><i class="bi bi-whatsapp me-2"></i><a class="text-white" href="https://wa.me/<?= e(preg_replace('/\D/','', ayar('whatsapp'))) ?>" target="_blank">WhatsApp</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-white-50 mt-4">
    <div class="d-md-flex justify-content-between small py-3">
      <div>© <?= date('Y') ?> <?= e(ayar('site_adi')) ?>. <?= t('haklar_saklidir') ?></div>
      <div>v1.0</div>
    </div>
  </div>
</footer>

<!-- WhatsApp float -->
<a class="wa-float" href="https://wa.me/<?= e(preg_replace('/\D/','', ayar('whatsapp'))) ?>" target="_blank" aria-label="WhatsApp">
  <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL ?>/js/main.js"></script>
</body>
</html>
