<?php
$settings = getSettings();
$schoolName = $settings['school_name'] ?? 'SMK Pertamaku';
$footerAbout = $settings['footer_about'] ?? '';
$address = $settings['school_address'] ?? '';
$phone = $settings['school_phone'] ?? '';
$email = $settings['school_email'] ?? '';
$whatsapp = $settings['whatsapp_number'] ?? '';
$footerCopyright = $settings['footer_copyright'] ?? 'Hak cipta dilindungi undang-undang.';
$currentYear = date('Y');

$db = getDB();
$progRes = $db->query("SELECT id, name FROM programs WHERE is_active=1 ORDER BY sort_order LIMIT 6");
$footerPrograms = $progRes ? $progRes->fetch_all(MYSQLI_ASSOC) : [];

$smRes = $db->query("SELECT * FROM social_media WHERE is_active=1");
$socialMedia = $smRes ? $smRes->fetch_all(MYSQLI_ASSOC) : [];
?>
</main>

<!-- ═══════════════════════════════════════════════════════════
     FOOTER
     ═══════════════════════════════════════════════════════════ -->
<footer class="bg-slate-900 dark:bg-slate-950 pt-16 pb-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12">

      <!-- Col 1: School Info -->
      <div>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-lg shadow-lg">🎓</div>
          <span class="text-white font-bold text-lg"><?= htmlspecialchars($schoolName) ?></span>
        </div>
        <p class="text-slate-400 text-sm leading-relaxed mb-5"><?= htmlspecialchars($footerAbout) ?></p>
        <?php if ($socialMedia): ?>
        <div class="flex gap-2">
          <?php foreach ($socialMedia as $sm): ?>
          <a href="<?= htmlspecialchars($sm['url']) ?>" target="_blank" rel="noopener"
             class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-800 text-slate-400 hover:bg-blue-600 hover:text-white transition-all" title="<?= htmlspecialchars($sm['platform']) ?>">
            <i class="<?= htmlspecialchars($sm['icon'] ?? 'fas fa-link') ?>"></i>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Tautan Cepat</h5>
        <ul class="space-y-2.5">
          <li><a href="<?= APP_URL ?>/" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Beranda</a></li>
          <li><a href="<?= APP_URL ?>/profil" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Tentang Kami</a></li>
          <li><a href="<?= APP_URL ?>/berita" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Berita</a></li>
          <li><a href="<?= APP_URL ?>/galeri" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Galeri</a></li>
          <li><a href="<?= APP_URL ?>/prestasi" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Prestasi</a></li>
          <li><a href="<?= APP_URL ?>/kontak" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>Kontak</a></li>
          <li><a href="<?= APP_URL ?>/spmb" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i>SPMB</a></li>
        </ul>
      </div>

      <!-- Col 3: Programs -->
      <div>
        <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Program Keahlian</h5>
        <ul class="space-y-2.5">
          <?php foreach ($footerPrograms as $prog): ?>
          <li><a href="<?= APP_URL ?>/jurusan/<?= $prog['id'] ?>" class="text-slate-400 text-sm hover:text-white hover:pl-1 transition-all flex items-center gap-2"><i class="fas fa-chevron-right text-[10px] text-blue-500"></i><?= htmlspecialchars($prog['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div>
        <h5 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Informasi Kontak</h5>
        <ul class="space-y-3">
          <?php if ($address): ?>
          <li class="flex gap-3">
            <i class="fas fa-map-marker-alt text-blue-400 mt-1 w-4 text-center flex-shrink-0"></i>
            <span class="text-slate-400 text-sm leading-relaxed"><?= htmlspecialchars($address) ?></span>
          </li>
          <?php endif; ?>
          <?php if ($phone): ?>
          <li class="flex gap-3">
            <i class="fas fa-phone text-blue-400 mt-0.5 w-4 text-center flex-shrink-0"></i>
            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>" class="text-slate-400 text-sm hover:text-white transition-colors"><?= htmlspecialchars($phone) ?></a>
          </li>
          <?php endif; ?>
          <?php if ($email): ?>
          <li class="flex gap-3">
            <i class="fas fa-envelope text-blue-400 mt-0.5 w-4 text-center flex-shrink-0"></i>
            <a href="mailto:<?= htmlspecialchars($email) ?>" class="text-slate-400 text-sm hover:text-white transition-colors"><?= htmlspecialchars($email) ?></a>
          </li>
          <?php endif; ?>
          <?php if ($whatsapp): ?>
          <li class="flex gap-3">
            <i class="fab fa-whatsapp text-green-400 mt-0.5 w-4 text-center flex-shrink-0"></i>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $whatsapp) ?>" target="_blank" class="text-slate-400 text-sm hover:text-white transition-colors">Chat via WhatsApp</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
      <p class="text-center text-slate-500 text-sm">
        &copy; <?= $currentYear ?> <?= htmlspecialchars($schoolName) ?>. <?= htmlspecialchars($footerCopyright) ?>
      </p>
    </div>
  </div>
</footer>

<!-- Back to Top -->
<button id="backToTop" title="Kembali ke atas">
  <i class="fas fa-chevron-up"></i>
</button>

<!-- WhatsApp Float -->
<?php if ($whatsapp): ?>
<a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $whatsapp) ?>?text=Halo+<?= urlencode($schoolName) ?>%2C+saya+ingin+bertanya..."
   class="whatsapp-float" target="_blank" rel="noopener" title="Chat WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Lightbox Modal -->
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox()">
  <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">&times;</button>
  <img id="lightboxImg" src="" alt="Gallery">
  <p id="lightboxCaption" class="absolute bottom-4 text-white text-center w-full"></p>
</div>

<!-- Custom JS -->
<script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>
