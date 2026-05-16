<?php
$pageTitle = 'Tentang Sekolah - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';

// Determine active tab based on URL
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = defined('APP_BASE') ? APP_BASE : '';
$cleanUri = str_replace($base, '', $currentUri);
$cleanUri = '/' . ltrim($cleanUri, '/');

$tabMap = [
    '/profil' => 'profil',
    '/visi-misi' => 'visiMisi',
    '/sejarah' => 'sejarah',
    '/kepala-sekolah' => 'kepalaSekolah',
];
$activeTab = isset($tabMap[$cleanUri]) ? $tabMap[$cleanUri] : 'profil';
?>

<!-- Page Header -->
<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot; fill=&quot;white&quot; fill-opacity=&quot;0.1&quot;/%3E%3C/svg%3E')]"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Tentang Sekolah</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Tentang Sekolah</span>
    </nav>
  </div>
</section>

<section class="py-16 lg:py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Tabs -->
    <div data-tab-group class="flex flex-wrap gap-1 border-b border-slate-200 dark:border-slate-700 mb-10 overflow-x-auto">
      <button data-tab-target="profil" class="px-4 py-3 text-sm font-semibold border-b-2 <?= $activeTab === 'profil' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' ?> transition-colors flex items-center gap-2 whitespace-nowrap">
        <i class="fas fa-school"></i>Profil
      </button>
      <button data-tab-target="visiMisi" class="px-4 py-3 text-sm font-semibold border-b-2 <?= $activeTab === 'visiMisi' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' ?> transition-colors flex items-center gap-2 whitespace-nowrap">
        <i class="fas fa-bullseye"></i>Visi & Misi
      </button>
      <button data-tab-target="sejarah" class="px-4 py-3 text-sm font-semibold border-b-2 <?= $activeTab === 'sejarah' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' ?> transition-colors flex items-center gap-2 whitespace-nowrap">
        <i class="fas fa-history"></i>Sejarah
      </button>
      <button data-tab-target="kepalaSekolah" class="px-4 py-3 text-sm font-semibold border-b-2 <?= $activeTab === 'kepalaSekolah' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' ?> transition-colors flex items-center gap-2 whitespace-nowrap">
        <i class="fas fa-user-tie"></i>Kepala Sekolah
      </button>
      <button data-tab-target="fasilitas" class="px-4 py-3 text-sm font-semibold border-b-2 <?= $activeTab === 'fasilitas' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-blue-600' ?> transition-colors flex items-center gap-2 whitespace-nowrap">
        <i class="fas fa-building"></i>Fasilitas
      </button>
    </div>

    <div data-tab-content>
      <!-- Profil Tab -->
      <div data-tab-panel="profil" class="<?= $activeTab !== 'profil' ? 'hidden' : '' ?>">
        <div class="grid lg:grid-cols-2 gap-10">
          <div>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Profil Sekolah</h3>
            <div class="space-y-3">
              <?php
              $infoItems = [
                ['label'=>'Nama Sekolah','value'=>$settings['school_name'] ?? '-'],
                ['label'=>'NPSN','value'=>$settings['school_npsn'] ?? '-'],
                ['label'=>'Akreditasi','value'=>$settings['school_accreditation'] ?? 'A','badge'=>true],
                ['label'=>'Alamat','value'=>$settings['school_address'] ?? '-'],
                ['label'=>'Telepon','value'=>$settings['school_phone'] ?? '-'],
                ['label'=>'Email','value'=>$settings['school_email'] ?? '-'],
                ['label'=>'Website','value'=>$settings['school_website'] ?? '-'],
              ];
              foreach ($infoItems as $item): ?>
              <div class="flex border-b border-slate-100 dark:border-slate-700 pb-3">
                <span class="w-36 flex-shrink-0 text-sm text-slate-400"><?= $item['label'] ?></span>
                <?php if (!empty($item['badge'])): ?>
                <span class="px-2.5 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-sm font-semibold rounded-full"><?= htmlspecialchars($item['value']) ?></span>
                <?php else: ?>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-200"><?= htmlspecialchars($item['value']) ?></span>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div>
            <?php if (!empty($settings['school_building_photo'])): ?>
            <img src="<?= UPLOAD_URL . htmlspecialchars($settings['school_building_photo']) ?>" alt="Gedung" class="w-full h-72 object-cover rounded-2xl shadow-lg">
            <?php else: ?>
            <div class="w-full h-72 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-600 flex items-center justify-center">
              <i class="fas fa-school text-5xl text-blue-200 dark:text-blue-700"></i>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Visi Misi Tab -->
      <div data-tab-panel="visiMisi" class="<?= $activeTab !== 'visiMisi' ? 'hidden' : '' ?>">
        <div class="grid md:grid-cols-2 gap-6">
          <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-8">
            <h4 class="text-xl font-bold text-blue-600 mb-4 flex items-center gap-2"><i class="fas fa-eye"></i>Visi</h4>
            <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
              <p class="text-slate-600 dark:text-slate-300 leading-relaxed prose prose-slate dark:prose-invert max-w-none"><?= safeRichHtml($settings['about_vision'] ?? '') ?></p>
            </div>
          </div>
          <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-8">
            <h4 class="text-xl font-bold text-blue-600 mb-4 flex items-center gap-2"><i class="fas fa-list-check"></i>Misi</h4>
            <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
              <div class="text-slate-600 dark:text-slate-300 leading-relaxed prose prose-slate dark:prose-invert max-w-none"><?= safeRichHtml($settings['about_mission'] ?? '') ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sejarah Tab -->
      <div data-tab-panel="sejarah" class="<?= $activeTab !== 'sejarah' ? 'hidden' : '' ?>">
        <div class="max-w-3xl mx-auto">
          <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Sejarah Sekolah</h3>
          <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-8">
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed prose prose-slate dark:prose-invert max-w-none"><?= !empty($settings['about_history']) ? safeRichHtml($settings['about_history']) : 'Informasi sejarah belum tersedia.' ?></p>
          </div>
        </div>
      </div>

      <!-- Kepala Sekolah Tab -->
      <div data-tab-panel="kepalaSekolah" class="<?= $activeTab !== 'kepalaSekolah' ? 'hidden' : '' ?>">
        <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-8">
          <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="text-center flex-shrink-0">
              <?php if (!empty($settings['principal_photo'])): ?>
              <img src="<?= UPLOAD_URL . htmlspecialchars($settings['principal_photo']) ?>" alt="Kepala Sekolah" class="w-36 h-36 rounded-full object-cover border-4 border-blue-500 mx-auto">
              <?php else: ?>
              <div class="w-36 h-36 rounded-full bg-blue-50 dark:bg-blue-900/30 border-4 border-blue-500 flex items-center justify-center mx-auto">
                <i class="fas fa-user-tie text-4xl text-blue-400"></i>
              </div>
              <?php endif; ?>
              <h5 class="mt-4 font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($settings['principal_name'] ?? '-') ?></h5>
              <span class="inline-block mt-1 px-3 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-semibold rounded-full">Kepala Sekolah</span>
            </div>
            <div class="flex-1">
              <h4 class="text-lg font-bold text-blue-600 mb-3">Sambutan Kepala Sekolah</h4>
              <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed italic prose prose-slate dark:prose-invert max-w-none">"<?= safeRichHtml($settings['principal_message'] ?? '') ?>"</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Fasilitas Tab -->
      <div data-tab-panel="fasilitas" class="<?= $activeTab !== 'fasilitas' ? 'hidden' : '' ?>">
        <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Fasilitas Sekolah</h3>
        <p class="text-slate-400 mb-8">Kami menyediakan fasilitas lengkap untuk mendukung proses belajar mengajar</p>

        <?php if (empty($facilities)): ?>
        <div class="bg-white dark:bg-slate-800 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl p-10 text-center text-slate-400 dark:text-slate-500">
          <i class="fas fa-building text-3xl mb-2 block"></i>
          Belum ada fasilitas yang ditampilkan.
        </div>
        <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php foreach ($facilities as $idx => $f):
            // Bangun list slide untuk tiap card. Gallery diutamakan; kalau
            // tidak ada gallery, single cover image dipakai. Kalau dua-duanya
            // kosong card tampil hanya dengan ikon (compact look).
            $slides = [];
            if (!empty($f['images'])) {
              foreach ($f['images'] as $fi) {
                $slides[] = ['image' => $fi['image'], 'caption' => $fi['caption'] ?? ''];
              }
            } elseif (!empty($f['image'])) {
              $slides[] = ['image' => $f['image'], 'caption' => ''];
            }
            $hasMedia    = !empty($slides);
            $isSlideshow = count($slides) > 1;
            $cardId      = 'facCard' . (int)$f['id'];
          ?>
          <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all flex flex-col">

            <?php if ($hasMedia): ?>
            <!-- Cover / slideshow -->
            <div id="<?= $cardId ?>" class="relative aspect-video bg-slate-200 dark:bg-slate-900 overflow-hidden" data-fac-slideshow="<?= $isSlideshow ? '1' : '0' ?>">
              <?php foreach ($slides as $i => $s): ?>
              <div class="fac-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?= $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' ?>" data-index="<?= $i ?>">
                <img src="<?= UPLOAD_URL . htmlspecialchars($s['image']) ?>"
                     alt="<?= htmlspecialchars($s['caption'] ?: $f['name']) ?>"
                     loading="lazy" decoding="async"
                     class="w-full h-full object-cover">
              </div>
              <?php endforeach; ?>

              <!-- Floating ikon di atas gambar -->
              <div class="absolute top-3 left-3 w-11 h-11 bg-white/95 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600 text-lg shadow-lg">
                <i class="<?= htmlspecialchars($f['icon'] ?? 'fas fa-building') ?>"></i>
              </div>

              <?php if ($isSlideshow): ?>
              <!-- Indicator dots -->
              <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5">
                <?php foreach ($slides as $i => $_): ?>
                <button type="button" data-fac-indicator="<?= $i ?>"
                        class="fac-indicator h-1.5 rounded-full transition-all duration-300 <?= $i === 0 ? 'w-5 bg-white' : 'w-1.5 bg-white/60' ?>"></button>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="p-6 flex-1 flex flex-col">
              <?php if (!$hasMedia): ?>
              <!-- Compact look (icon-only) — sama seperti versi lama -->
              <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-lg mb-4 shadow-lg shadow-blue-500/20">
                <i class="<?= htmlspecialchars($f['icon'] ?? 'fas fa-building') ?>"></i>
              </div>
              <?php endif; ?>
              <h5 class="font-bold text-slate-800 dark:text-white mb-2"><?= htmlspecialchars($f['name']) ?></h5>
              <div class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed prose prose-sm prose-slate dark:prose-invert max-w-none">
                <?= safeRichHtml($f['description'] ?? '') ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Inline tab init to ensure tabs work even if main.js loads late -->
<script>
(function(){
  document.querySelectorAll('[data-tab-target]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var group = btn.closest('[data-tab-group]');
      if(!group) return;
      group.querySelectorAll('[data-tab-target]').forEach(function(t){
        t.classList.remove('border-blue-600','text-blue-600');
        t.classList.add('border-transparent','text-slate-500');
      });
      btn.classList.remove('border-transparent','text-slate-500');
      btn.classList.add('border-blue-600','text-blue-600');
      var target = btn.getAttribute('data-tab-target');
      var content = document.querySelector('[data-tab-content]');
      if(!content) return;
      content.querySelectorAll('[data-tab-panel]').forEach(function(p){ p.classList.add('hidden'); });
      var panel = content.querySelector('[data-tab-panel="'+target+'"]');
      if(panel) panel.classList.remove('hidden');
    });
  });

  /* ────────────────────────────────────────────────────────────
     Per-card slideshow untuk tab Fasilitas. Aktif hanya pada
     wrapper yang punya data-fac-slideshow="1" (>1 foto). Setiap
     kartu jalan independen, auto-rotate 4 detik, jeda saat hover.
     ──────────────────────────────────────────────────────────── */
  document.querySelectorAll('[data-fac-slideshow="1"]').forEach(function(box){
    var slides     = box.querySelectorAll('.fac-slide');
    var indicators = box.querySelectorAll('.fac-indicator');
    if (slides.length < 2) return;
    var current = 0;
    var paused  = false;
    function show(i){
      current = (i + slides.length) % slides.length;
      slides.forEach(function(s, idx){
        s.classList.toggle('opacity-100', idx === current);
        s.classList.toggle('opacity-0',   idx !== current);
        s.classList.toggle('pointer-events-none', idx !== current);
      });
      indicators.forEach(function(b, idx){
        var on = idx === current;
        b.classList.toggle('w-5',         on);
        b.classList.toggle('bg-white',    on);
        b.classList.toggle('w-1.5',       !on);
        b.classList.toggle('bg-white/60', !on);
      });
    }
    indicators.forEach(function(b){
      b.addEventListener('click', function(){
        show(parseInt(b.getAttribute('data-fac-indicator'), 10));
      });
    });
    box.addEventListener('mouseenter', function(){ paused = true; });
    box.addEventListener('mouseleave', function(){ paused = false; });
    setInterval(function(){ if (!paused) show(current + 1); }, 4000);
  });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
