<?php
$pageTitle = ($settings['school_name'] ?? 'SMK Pertamaku') . ' — ' . ($settings['school_tagline'] ?? 'Sekolah Menengah Kejuruan Unggulan');
// Homepage section visibility
ensureHomepageSettings();
$_hpSettings = getSettings(); // refresh after ensure
// Stored in $GLOBALS so hpVisible() (defined as a top-level function) can
// reliably read it regardless of which scope this view is required from.
// Previously this was a local variable + `global` inside the function, which
// returned null because the view is included from inside a controller method
// (its locals never reach the true global scope), causing a fatal
// `in_array(): Argument #2 must be of type array, null given`.
$GLOBALS['_hpHidden'] = array_filter(explode(',', $_hpSettings['homepage_sections_hidden'] ?? ''));
$_hpAnnouncementActive = ($_hpSettings['homepage_announcement_active'] ?? '0') === '1';
$_hpAnnouncementContent = $_hpSettings['homepage_announcement_content'] ?? '';
// Load extracurriculars for homepage section
$_hpExtracurriculars = function_exists('getActiveExtracurriculars') ? getActiveExtracurriculars() : [];

if (!function_exists('hpVisible')) {
    function hpVisible($section) {
        $hidden = $GLOBALS['_hpHidden'] ?? [];
        if (!is_array($hidden)) $hidden = [];
        return !in_array($section, $hidden, true);
    }
}
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO SLIDER (Tailwind-native, fade transition)
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('slider')): ?>
<section class="hero-slider relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950 overflow-hidden h-[85vh] min-h-[560px]">
  <!-- Decorative shapes -->
  <div class="absolute inset-0 pointer-events-none z-0">
    <div class="absolute w-72 h-72 bg-blue-500/10 rounded-full -top-20 right-[10%] animate-pulse"></div>
    <div class="absolute w-44 h-44 bg-indigo-500/10 rounded-full bottom-[10%] left-[8%] animate-pulse delay-1000"></div>
  </div>

  <!-- Slides -->
  <?php foreach ($sliders as $i => $slide): ?>
  <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?= $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' ?>">
    <?php if (!empty($slide['image'])): ?>
    <img src="<?= UPLOAD_URL . htmlspecialchars($slide['image']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>"
         <?= $i === 0 ? 'fetchpriority="high" decoding="async"' : 'loading="lazy" decoding="async"' ?>
         class="absolute inset-0 w-full h-full object-cover opacity-20">
    <?php endif; ?>
    <div class="absolute inset-0 flex items-center justify-center z-10">
      <div class="text-center px-4 max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 text-xs font-semibold text-white/90 uppercase tracking-wide mb-5">
          <i class="fas fa-star text-yellow-400"></i>
          <?= htmlspecialchars($settings['school_name'] ?? 'SMK Pertamaku') ?> · Akreditasi <?= htmlspecialchars($settings['school_accreditation'] ?? 'A') ?>
        </div>
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
          <?= htmlspecialchars($slide['title'] ?? '') ?>
        </h1>
        <p class="text-base sm:text-lg text-white/75 max-w-xl mx-auto mb-8 leading-relaxed">
          <?= htmlspecialchars($slide['subtitle'] ?? '') ?>
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
          <?php if (!empty($slide['button_text'])): ?>
          <a href="<?= APP_URL . htmlspecialchars($slide['button_url'] ?? '/spmb') ?>"
             class="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold text-sm shadow-xl shadow-blue-600/30 hover:-translate-y-0.5 hover:shadow-blue-600/50 transition-all inline-flex items-center gap-2">
            <i class="fas fa-pencil-alt"></i><?= htmlspecialchars($slide['button_text']) ?>
          </a>
          <?php endif; ?>
          <a href="<?= APP_URL ?>/profil"
             class="px-7 py-3.5 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white rounded-xl font-semibold text-sm hover:bg-white/20 hover:border-white/50 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
            <i class="fas fa-play-circle"></i>Tentang Kami
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <!-- Controls -->
  <button id="heroPrev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-all">
    <i class="fas fa-chevron-left"></i>
  </button>
  <button id="heroNext" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-all">
    <i class="fas fa-chevron-right"></i>
  </button>

  <!-- Indicators -->
  <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
    <?php foreach ($sliders as $i => $slide): ?>
    <button type="button" data-index="<?= $i ?>" class="hero-indicator h-2 rounded-full transition-all duration-300 <?= $i === 0 ? 'w-6 bg-white' : 'w-2 bg-white/50' ?>"></button>
    <?php endforeach; ?>
  </div>
</section>


<?php endif; /* slider */ ?>

<!-- ═══════════════════════════════════════════════════════════
     STATS BAR
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('stats')): ?>
<section class="relative bg-gradient-to-r from-blue-600 to-indigo-600 py-16 overflow-hidden">
  <div class="absolute inset-0 opacity-10" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot; fill=&quot;white&quot;/%3E%3C/svg%3E');"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      <?php
      $statsData = [
        ['icon'=>'fas fa-user-graduate','key'=>'stats_students','default'=>750,'label'=>'Siswa Aktif'],
        ['icon'=>'fas fa-chalkboard-teacher','key'=>'stats_teachers','default'=>45,'label'=>'Guru & Staff'],
        ['icon'=>'fas fa-book-open','key'=>'stats_programs','default'=>4,'label'=>'Program Keahlian'],
        ['icon'=>'fas fa-users','key'=>'stats_alumni','default'=>2000,'label'=>'Alumni'],
      ];
      foreach ($statsData as $s):
        $val = (int)($settings[$s['key']] ?? $s['default']);
      ?>
      <div class="text-center">
        <div class="w-14 h-14 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-3">
          <i class="<?= $s['icon'] ?> text-xl text-white"></i>
        </div>
        <span class="stat-number text-3xl sm:text-4xl font-black text-white block" data-target="<?= $val ?>">0</span>
        <span class="text-yellow-300 font-bold text-lg">+</span>
        <div class="text-white/70 text-xs sm:text-sm font-semibold uppercase tracking-wider mt-1"><?= $s['label'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; /* stats */ ?>


<!-- ═══════════════════════════════════════════════════════════
     ABOUT SNIPPET — putih sempurna
     ═══════════════════════════════════════════════════════════ -->
<section class="py-20 lg:py-28 bg-white dark:bg-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-12 gap-12 items-center">
      <!-- Visual -->
      <div class="hidden lg:block lg:col-span-5 relative">
        <?php if (!empty($settings['school_building_photo'])): ?>
        <img src="<?= UPLOAD_URL . htmlspecialchars($settings['school_building_photo']) ?>"
             alt="Gedung <?= htmlspecialchars($settings['school_name'] ?? '') ?>"
             loading="lazy" decoding="async"
             class="w-full h-[400px] object-cover rounded-2xl shadow-2xl">
        <?php else: ?>
        <div class="w-full h-[400px] bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-600 flex flex-col items-center justify-center gap-3">
          <i class="fas fa-school text-5xl text-blue-300 dark:text-blue-600"></i>
          <span class="text-sm text-slate-400">Foto Gedung Sekolah</span>
        </div>
        <?php endif; ?>
        <!-- Badge -->
        <div class="absolute -bottom-4 -left-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-4 shadow-lg flex items-center gap-3">
          <div class="w-11 h-11 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-xl">🏆</div>
          <div>
            <strong class="block text-sm text-slate-800 dark:text-white">Akreditasi <?= htmlspecialchars($settings['school_accreditation'] ?? 'A') ?></strong>
            <span class="text-xs text-slate-400">BAN-S/M Terakreditasi</span>
          </div>
        </div>
      </div>

      <!-- Text -->
      <div class="lg:col-span-7">
        <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
          <i class="fas fa-graduation-cap"></i> Tentang Kami
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-3 leading-tight">
          <?= htmlspecialchars($settings['school_name'] ?? 'SMK Pertamaku') ?>
        </h2>
        <p class="text-blue-600 dark:text-blue-400 font-semibold mb-4"><?= htmlspecialchars($settings['school_tagline'] ?? '') ?></p>
        <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
          <?= htmlspecialchars(richExcerpt($settings['about_history'] ?? '', 280)) ?>...
        </p>

        <div class="grid sm:grid-cols-2 gap-4 mb-8">
          <?php
          $checks = [
            ['title'=>'Terakreditasi '.($settings['school_accreditation'] ?? 'A'), 'sub'=>'Badan Akreditasi Nasional'],
            ['title'=>'NPSN: '.($settings['school_npsn'] ?? '-'), 'sub'=>'Nomor Pokok Sekolah Nasional'],
            ['title'=>(int)($settings['stats_programs'] ?? 4).' Program Keahlian', 'sub'=>'Siap kerja & industri'],
            ['title'=>(int)($settings['stats_alumni'] ?? 2000).'+ Alumni Sukses', 'sub'=>'Berkarier di berbagai bidang'],
          ];
          foreach ($checks as $c): ?>
          <div class="flex gap-3">
            <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
              <i class="fas fa-check text-blue-600 text-xs"></i>
            </div>
            <div>
              <strong class="block text-sm text-slate-700 dark:text-slate-200"><?= htmlspecialchars($c['title']) ?></strong>
              <span class="text-xs text-slate-400"><?= htmlspecialchars($c['sub']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="flex flex-wrap gap-3">
          <a href="<?= APP_URL ?>/profil" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>Selengkapnya
          </a>
          <a href="<?= APP_URL ?>/visi-misi" class="px-5 py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
            Visi & Misi
          </a>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- ═══════════════════════════════════════════════════════════
     PROGRAM KEAHLIAN — putih agak abu (slate-50) + soft glow
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('programs')): ?>
<section class="relative py-20 lg:py-28 bg-slate-50 dark:bg-slate-800/40 overflow-hidden">
  <!-- subtle dot pattern di sudut, hanya untuk variasi visual -->
  <div class="absolute -top-10 -right-10 w-72 h-72 bg-blue-500/[0.04] rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-indigo-500/[0.04] rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
        <i class="fas fa-book-open"></i> Program Keahlian
      </div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-3">Pilih <span class="text-blue-600">Jurusan</span> Impianmu</h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">4 program keahlian unggulan yang menyiapkan kamu untuk karir di era industri modern</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($programs as $prog): ?>
      <a href="<?= APP_URL ?>/jurusan/<?= (int)$prog['id'] ?>" class="group block bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-1.5 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-700 transition-all duration-300 relative">
        <?php if (!empty($prog['image'])): ?>
        <div class="relative h-32 overflow-hidden">
          <img src="<?= UPLOAD_URL . htmlspecialchars($prog['image']) ?>"
               alt="<?= htmlspecialchars($prog['name']) ?>"
               loading="lazy" decoding="async"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
          <div class="absolute bottom-2 left-3 w-11 h-11 bg-white/95 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600 text-base shadow-md ring-1 ring-white/30">
            <i class="<?= htmlspecialchars($prog['icon'] ?? 'fas fa-book') ?>"></i>
          </div>
        </div>
        <div class="p-6 pt-4">
        <?php else: ?>
        <div class="p-6">
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></div>
          <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl mb-4 shadow-lg shadow-blue-500/25 group-hover:scale-110 group-hover:-rotate-3 transition-transform">
            <i class="<?= htmlspecialchars($prog['icon'] ?? 'fas fa-book') ?>"></i>
          </div>
        <?php endif; ?>
          <h5 class="font-bold text-slate-800 dark:text-white mb-2"><?= htmlspecialchars($prog['name']) ?></h5>
          <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-4"><?= htmlspecialchars(richExcerpt($prog['description'] ?? '', 90)) ?>...</p>
          <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"><i class="fas fa-users mr-1"></i>Kuota <?= (int)$prog['quota'] ?></span>
            <span class="w-7 h-7 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 text-xs group-hover:bg-blue-600 group-hover:text-white transition-all"><i class="fas fa-arrow-right"></i></span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-10">
      <a href="<?= APP_URL ?>/jurusan" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
        <i class="fas fa-th-large"></i>Lihat Semua Jurusan
      </a>
    </div>
  </div>
</section>
<?php endif; /* programs */ ?>

<!-- ═══════════════════════════════════════════════════════════
     EKSTRAKURIKULER
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('extracurriculars') && !empty($_hpExtracurriculars)): ?>
<section class="py-16 lg:py-20 bg-white dark:bg-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold rounded-full uppercase tracking-wider mb-3"><i class="fas fa-running"></i>Ekskul</span>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white mb-3">Ekstrakurikuler</h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">Kegiatan pengembangan bakat dan minat siswa di luar jam pelajaran</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach (array_slice($_hpExtracurriculars, 0, 8) as $hpE): ?>
      <a href="<?= APP_URL ?>/ekskul/<?= $hpE['id'] ?>" class="group bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5 hover:-translate-y-1 hover:shadow-lg transition-all text-center">
        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-xl mb-3 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
          <i class="<?= htmlspecialchars($hpE['icon'] ?? 'fas fa-futbol') ?>"></i>
        </div>
        <h5 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($hpE['name']) ?></h5>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if (count($_hpExtracurriculars) > 8): ?>
    <div class="text-center mt-8">
      <a href="<?= APP_URL ?>/ekskul" class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua ekskul <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     PENGUMUMAN / VIDEO BANNER
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('announcement') && $_hpAnnouncementActive && !empty($_hpAnnouncementContent)): ?>
<section class="py-12 lg:py-16 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-slate-800 dark:to-slate-800 border-y border-amber-200 dark:border-amber-900/30">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-start gap-4 mb-4">
      <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
        <i class="fas fa-bullhorn"></i>
      </div>
      <div>
        <h3 class="font-bold text-slate-800 dark:text-white text-lg">Pengumuman</h3>
        <p class="text-xs text-slate-400">Informasi penting dari sekolah</p>
      </div>
    </div>
    <div class="prose prose-slate dark:prose-invert max-w-none bg-white dark:bg-slate-900 rounded-xl p-6 border border-amber-200 dark:border-amber-900/30 shadow-sm">
      <?= safeRichHtml($_hpAnnouncementContent) ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════════
     BERITA TERKINI — putih sempurna
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('news')): ?>
<section class="py-20 lg:py-28 bg-white dark:bg-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-12">
      <div>
        <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
          <i class="fas fa-newspaper"></i> Berita & Informasi
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-2">Kabar <span class="text-blue-600">Terkini</span></h2>
        <p class="text-slate-500 dark:text-slate-400">Ikuti perkembangan informasi dan kegiatan terbaru</p>
      </div>
      <a href="<?= APP_URL ?>/berita" class="mt-4 lg:mt-0 inline-flex items-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
        Semua Berita <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($news as $article): ?>
      <article class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-xl transition-all duration-300 flex flex-col">
        <div class="relative h-48 overflow-hidden">
          <?php if (!empty($article['image'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>"
               loading="lazy" decoding="async"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <?php else: ?>
          <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
            <i class="fas fa-newspaper text-4xl text-slate-300 dark:text-slate-500"></i>
          </div>
          <?php endif; ?>
        </div>
        <div class="p-5 flex flex-col flex-1">
          <div class="flex items-center gap-2 mb-3 flex-wrap">
            <span class="text-[11px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 rounded-full uppercase"><?= htmlspecialchars($article['category']) ?></span>
            <span class="text-xs text-slate-400 flex items-center gap-1"><i class="fas fa-clock"></i><?= timeAgo($article['published_at']) ?></span>
          </div>
          <h5 class="font-bold text-slate-800 dark:text-white mb-2 leading-snug group-hover:text-blue-600 transition-colors">
            <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>"><?= htmlspecialchars($article['title']) ?></a>
          </h5>
          <p class="text-sm text-slate-400 leading-relaxed flex-1"><?= htmlspecialchars(mb_substr($article['excerpt'] ?? '', 0, 110)) ?>...</p>
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>" class="text-sm font-semibold text-blue-600 flex items-center gap-1.5 hover:gap-2.5 transition-all">
              Baca <i class="fas fa-arrow-right text-xs"></i>
            </a>
            <span class="text-xs text-slate-400"><i class="fas fa-eye mr-1"></i><?= (int)($article['views'] ?? 0) ?></span>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; /* news */ ?>



<!-- ═══════════════════════════════════════════════════════════
     PRESTASI — gradient slate-100/70 -> slate-50 -> white + amber halo
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('achievements') && !empty($achievements)): ?>
<section class="relative py-20 lg:py-28 bg-gradient-to-b from-slate-100/70 via-slate-50 to-white dark:from-slate-800/60 dark:via-slate-800/40 dark:to-slate-900 overflow-hidden">
  <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-96 h-96 bg-amber-300/[0.05] rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
        <i class="fas fa-trophy"></i> Prestasi
      </div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-3">Kebanggaan <span class="text-blue-600">Bersama</span></h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Pencapaian gemilang yang membuktikan kualitas pendidikan</p>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <?php foreach ($achievements as $ach): ?>
      <div class="flex gap-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5 hover:-translate-y-0.5 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700 transition-all">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0
          <?php if($ach['level']==='nasional'||$ach['level']==='internasional'): ?>bg-gradient-to-br from-yellow-400 to-amber-500
          <?php elseif($ach['level']==='provinsi'): ?>bg-gradient-to-br from-blue-400 to-blue-600
          <?php elseif($ach['level']==='kabupaten'): ?>bg-gradient-to-br from-green-400 to-emerald-500
          <?php else: ?>bg-blue-50 dark:bg-blue-900/30 text-blue-600<?php endif; ?>">🏆</div>
        <div class="flex-1 min-w-0">
          <h6 class="font-bold text-sm text-slate-800 dark:text-white mb-1"><?= htmlspecialchars($ach['title']) ?></h6>
          <p class="text-xs text-slate-400 leading-relaxed mb-2"><?= htmlspecialchars(richExcerpt($ach['description'] ?? '', 120)) ?></p>
          <div class="flex items-center gap-2">
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full uppercase
              <?php if($ach['level']==='nasional'||$ach['level']==='internasional'): ?>bg-amber-50 text-amber-600 dark:bg-amber-900/30
              <?php elseif($ach['level']==='provinsi'): ?>bg-blue-50 text-blue-600 dark:bg-blue-900/30
              <?php elseif($ach['level']==='kabupaten'): ?>bg-green-50 text-green-600 dark:bg-green-900/30
              <?php else: ?>bg-slate-50 text-slate-600 dark:bg-slate-700<?php endif; ?>"><?= ucfirst($ach['level']) ?></span>
            <?php if (!empty($ach['year'])): ?>
            <span class="text-xs text-slate-400"><i class="fas fa-calendar mr-1"></i><?= $ach['year'] ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-10">
      <a href="<?= APP_URL ?>/prestasi" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
        <i class="fas fa-trophy"></i>Semua Prestasi
      </a>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════
     TESTIMONIALS — putih sempurna
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('testimonials') && !empty($testimonials)): ?>
<section class="py-20 lg:py-28 bg-white dark:bg-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
      <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
        <i class="fas fa-quote-left"></i> Testimoni
      </div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-3">Kata <span class="text-blue-600">Mereka</span></h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Apa yang alumni dan orang tua siswa katakan tentang kami</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach (array_slice($testimonials, 0, 6) as $t): ?>
      <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 hover:-translate-y-1 hover:shadow-lg transition-all relative">
        <div class="absolute top-3 left-5 text-6xl text-blue-100 dark:text-blue-900/40 font-serif leading-none">"</div>
        <div class="text-yellow-400 text-sm mb-3 relative"><?= str_repeat('★', (int)($t['rating'] ?? 5)) ?></div>
        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-5 italic relative">"<?= htmlspecialchars($t['content']) ?>"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            <?= strtoupper(substr($t['name'], 0, 1)) ?>
          </div>
          <div>
            <div class="font-semibold text-sm text-slate-800 dark:text-white"><?= htmlspecialchars($t['name']) ?></div>
            <div class="text-xs text-slate-400"><?= htmlspecialchars($t['position'] ?? '') ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════
     AGENDA KEGIATAN — slate-50 + soft glow
     ═══════════════════════════════════════════════════════════ -->
<?php if (hpVisible('agenda') && !empty($agenda)): ?>
<section class="relative py-20 lg:py-28 bg-slate-50 dark:bg-slate-800/40 overflow-hidden">
  <div class="absolute -top-10 right-1/4 w-72 h-72 bg-blue-500/[0.04] rounded-full blur-3xl pointer-events-none"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-5 gap-12 items-start">
      <div class="lg:col-span-2">
        <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
          <i class="fas fa-calendar-alt"></i> Agenda
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 dark:text-white mb-3">Agenda <span class="text-blue-600">Kegiatan</span></h2>
        <p class="text-slate-500 dark:text-slate-400 mb-6">Jadwal kegiatan sekolah yang akan datang. Jangan sampai terlewat!</p>
        <a href="<?= APP_URL ?>/kontak" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">
          <i class="fas fa-envelope"></i>Hubungi Kami
        </a>
      </div>
      <div class="lg:col-span-3 space-y-3">
        <?php foreach ($agenda as $ag): ?>
        <div class="flex gap-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-4 hover:translate-x-1 hover:border-blue-200 dark:hover:border-blue-700 transition-all">
          <div class="w-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex flex-col items-center justify-center py-2 flex-shrink-0">
            <span class="text-lg font-black text-white leading-none"><?= date('d', strtotime($ag['start_date'])) ?></span>
            <span class="text-[10px] text-white/80 uppercase font-semibold"><?= date('M', strtotime($ag['start_date'])) ?></span>
          </div>
          <div class="min-w-0">
            <h6 class="font-bold text-sm text-slate-800 dark:text-white mb-1"><?= htmlspecialchars($ag['title']) ?></h6>
            <?php if (!empty($ag['location'])): ?>
            <p class="text-xs text-slate-400 mb-1"><i class="fas fa-map-marker-alt text-blue-500 mr-1"></i><?= htmlspecialchars($ag['location']) ?></p>
            <?php endif; ?>
            <?php if (!empty($ag['description'])): ?>
            <p class="text-xs text-slate-400 leading-relaxed"><?= htmlspecialchars(mb_substr($ag['description'], 0, 90)) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ═══════════════════════════════════════════════════════════
     CTA SECTION
     ═══════════════════════════════════════════════════════════ -->
<section class="relative py-24 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 overflow-hidden">
  <div class="absolute w-60 h-60 bg-white/5 rounded-full -top-20 right-[5%]"></div>
  <div class="absolute w-36 h-36 bg-white/5 rounded-full -bottom-10 left-[10%]"></div>
  <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Siap Bergabung Bersama Kami? 🎓</h2>
    <p class="text-white/75 text-lg max-w-xl mx-auto mb-8 leading-relaxed">
      Daftarkan diri sekarang dan jadilah bagian dari keluarga besar. Pendaftaran online mudah, cepat, dan bisa dilakukan kapan saja!
    </p>
    <div class="flex flex-wrap gap-3 justify-center">
      <a href="<?= APP_URL ?>/spmb/daftar" class="px-7 py-3.5 bg-white text-blue-700 rounded-xl font-bold text-sm shadow-xl hover:-translate-y-0.5 hover:shadow-2xl transition-all inline-flex items-center gap-2">
        <i class="fas fa-pencil-alt"></i>Daftar Sekarang
      </a>
      <a href="<?= APP_URL ?>/spmb/cek" class="px-7 py-3.5 bg-transparent border-2 border-white/50 text-white rounded-xl font-semibold text-sm hover:bg-white/10 hover:border-white hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
        <i class="fas fa-search"></i>Cek Status
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
