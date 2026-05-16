<?php
$pageTitle = htmlspecialchars($program['name'] ?? 'Detail Jurusan') . ' - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
$programImages = $programImages ?? [];
$hasGallery = !empty($programImages);
$hasBanner  = !empty($program['image']);

// Build the slideshow source list:
// - If admin uploaded multiple gallery photos, use them.
// - Else fall back to single banner image (if present) so existing data
//   without gallery still renders nicely.
$slides = [];
if ($hasGallery) {
    foreach ($programImages as $pi) {
        $slides[] = [
            'image'   => $pi['image'],
            'caption' => $pi['caption'] ?? '',
        ];
    }
} elseif ($hasBanner) {
    $slides[] = ['image' => $program['image'], 'caption' => ''];
}

require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2"><?= htmlspecialchars($program['name'] ?? '') ?></h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <a href="<?= APP_URL ?>/jurusan" class="text-white/70 hover:text-white transition-colors">Jurusan</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white"><?= htmlspecialchars($program['name'] ?? '') ?></span>
    </nav>
  </div>
</section>

<section class="py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">

        <!-- ============================================================
             COVER (slideshow if multiple, single image if not, nothing
             if neither). Rasio 16:9. Klik foto buka lightbox.
             ============================================================ -->
        <?php if (!empty($slides)): ?>
        <div id="programCover" class="relative rounded-2xl overflow-hidden mb-6 shadow-lg bg-slate-200 dark:bg-slate-800 aspect-video">
          <?php foreach ($slides as $i => $s): ?>
          <div class="program-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?= $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' ?>"
               data-index="<?= $i ?>">
            <img src="<?= UPLOAD_URL . htmlspecialchars($s['image']) ?>"
                 alt="<?= htmlspecialchars($s['caption'] ?: $program['name']) ?>"
                 loading="<?= $i === 0 ? 'eager' : 'lazy' ?>"
                 decoding="async"
                 data-lightbox-src="<?= UPLOAD_URL . htmlspecialchars($s['image']) ?>"
                 data-lightbox-caption="<?= htmlspecialchars($s['caption']) ?>"
                 class="w-full h-full object-cover cursor-zoom-in">
          </div>
          <?php endforeach; ?>

          <!-- Gradient overlay + label -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent pointer-events-none"></div>
          <div class="absolute bottom-4 left-4 flex items-center gap-3 pointer-events-none">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/95 dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600 text-lg sm:text-xl shadow-lg">
              <i class="<?= htmlspecialchars($program['icon'] ?? 'fas fa-book') ?>"></i>
            </div>
            <div class="text-white">
              <div class="text-[10px] sm:text-xs uppercase tracking-wider opacity-80">Jurusan</div>
              <div class="font-bold text-sm sm:text-lg drop-shadow"><?= htmlspecialchars($program['name'] ?? '') ?></div>
            </div>
          </div>

          <!-- Caption (per-slide, jika ada) -->
          <?php foreach ($slides as $i => $s): ?>
          <?php if (!empty($s['caption'])): ?>
          <div class="program-slide-caption absolute bottom-4 right-4 max-w-[60%] px-3 py-1.5 bg-slate-900/70 backdrop-blur-sm text-white text-xs sm:text-sm rounded-lg <?= $i === 0 ? '' : 'hidden' ?>"
               data-caption-for="<?= $i ?>">
            <?= htmlspecialchars($s['caption']) ?>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>

          <!-- Slideshow controls (only if more than one slide) -->
          <?php if (count($slides) > 1): ?>
          <button type="button" id="programPrev" aria-label="Sebelumnya"
                  class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/15 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-colors">
            <i class="fas fa-chevron-left text-sm"></i>
          </button>
          <button type="button" id="programNext" aria-label="Berikutnya"
                  class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/15 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-colors">
            <i class="fas fa-chevron-right text-sm"></i>
          </button>
          <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
            <?php foreach ($slides as $i => $s): ?>
            <button type="button"
                    data-program-indicator="<?= $i ?>"
                    class="program-indicator h-1.5 rounded-full transition-all duration-300 <?= $i === 0 ? 'w-6 bg-white' : 'w-1.5 bg-white/50' ?>"></button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Description card -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">
          <?php if (empty($slides)): ?>
          <!-- Header card (kalau benar-benar tidak ada gambar sama sekali) -->
          <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20">
              <i class="<?= htmlspecialchars($program['icon'] ?? 'fas fa-book') ?>"></i>
            </div>
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($program['name'] ?? '') ?></h2>
              <?php if (!empty($program['code'])): ?>
              <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full"><?= htmlspecialchars($program['code']) ?></span>
              <?php endif; ?>
            </div>
          </div>
          <?php elseif (!empty($program['code'])): ?>
          <div class="mb-4">
            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full"><?= htmlspecialchars($program['code']) ?></span>
          </div>
          <?php endif; ?>
          <div class="text-slate-600 dark:text-slate-300 leading-relaxed prose prose-slate dark:prose-invert max-w-none"><?= safeRichHtml($program['description'] ?? '') ?></div>
        </div>

        <!-- ============================================================
             FOTO KEGIATAN (grid lengkap dengan lightbox)
             Hanya tampil jika ada >1 foto (kalau cuma 1 sudah jadi cover)
             ============================================================ -->
        <?php if (count($programImages) > 1): ?>
        <div class="mt-8">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
              <i class="fas fa-images"></i>
            </div>
            <div>
              <h5 class="font-bold text-slate-800 dark:text-white">Foto Kegiatan Jurusan</h5>
              <p class="text-xs text-slate-400"><?= count($programImages) ?> foto · klik untuk perbesar</p>
            </div>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <?php foreach ($programImages as $pi): ?>
            <button type="button"
                    class="group relative block aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 hover:shadow-md transition-all"
                    data-lightbox-src="<?= UPLOAD_URL . htmlspecialchars($pi['image']) ?>"
                    data-lightbox-caption="<?= htmlspecialchars($pi['caption'] ?? '') ?>">
              <img src="<?= UPLOAD_URL . htmlspecialchars($pi['image']) ?>"
                   alt="<?= htmlspecialchars($pi['caption'] ?? $program['name']) ?>"
                   loading="lazy" decoding="async"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <?php if (!empty($pi['caption'])): ?>
              <div class="absolute bottom-2 left-2 right-2 text-white text-xs font-medium drop-shadow opacity-0 group-hover:opacity-100 transition-opacity line-clamp-2">
                <?= htmlspecialchars($pi['caption']) ?>
              </div>
              <?php endif; ?>
              <div class="absolute top-2 right-2 w-7 h-7 bg-white/90 dark:bg-slate-900/90 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fas fa-search-plus text-xs"></i>
              </div>
            </button>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($relatedNews)): ?>
        <div class="mt-8">
          <div class="flex items-center justify-between mb-4">
            <h5 class="font-bold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-newspaper text-blue-500"></i>Berita Jurusan Ini</h5>
            <a href="<?= APP_URL ?>/berita?program=<?= $program['id'] ?>" class="text-sm font-semibold text-blue-600 hover:underline">Semua Berita <i class="fas fa-arrow-right ml-1"></i></a>
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <?php foreach ($relatedNews as $n): ?>
            <article class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden hover:shadow-lg transition-all flex flex-col">
              <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($n['slug']) ?>" class="relative block h-36 overflow-hidden">
                <?php if (!empty($n['image'])): ?>
                <img src="<?= UPLOAD_URL . htmlspecialchars($n['image']) ?>" alt="" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                <?php else: ?>
                <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center"><i class="fas fa-newspaper text-2xl text-slate-300"></i></div>
                <?php endif; ?>
              </a>
              <div class="p-4 flex-1 flex flex-col">
                <span class="text-[11px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-full w-fit mb-2"><?= htmlspecialchars($n['category'] ?? 'Berita') ?></span>
                <h6 class="font-semibold text-sm text-slate-800 dark:text-white mb-1 leading-snug"><a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($n['slug']) ?>" class="hover:text-blue-600"><?= htmlspecialchars($n['title']) ?></a></h6>
                <span class="text-xs text-slate-400 mt-auto"><?= timeAgo($n['published_at']) ?></span>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h5 class="font-bold text-slate-800 dark:text-white mb-4">Informasi Jurusan</h5>
          <div class="border-t border-slate-100 dark:border-slate-700 pt-4 space-y-3">
            <div class="flex justify-between"><span class="text-sm text-slate-400">Kode</span><strong class="text-sm text-slate-700 dark:text-white"><?= htmlspecialchars($program['code'] ?? '-') ?></strong></div>
            <div class="flex justify-between"><span class="text-sm text-slate-400">Kuota</span><strong class="text-sm text-slate-700 dark:text-white"><?= (int)($program['quota'] ?? 36) ?> Siswa</strong></div>
            <?php if (!empty($programImages)): ?>
            <div class="flex justify-between"><span class="text-sm text-slate-400">Foto Kegiatan</span><strong class="text-sm text-slate-700 dark:text-white"><?= count($programImages) ?> foto</strong></div>
            <?php endif; ?>
          </div>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 text-center">
          <h5 class="font-bold text-slate-800 dark:text-white mb-2">Tertarik Mendaftar?</h5>
          <p class="text-sm text-slate-400 mb-4">Daftarkan diri Anda sekarang dan pilih jurusan ini</p>
          <a href="<?= APP_URL ?>/spmb/daftar" class="block w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">Daftar Sekarang</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     LIGHTBOX (shared by cover slides + grid thumbnails)
     ============================================================ -->
<div id="programLightbox"
     class="fixed inset-0 z-[9999] hidden bg-slate-900/95 backdrop-blur-sm items-center justify-center p-4"
     style="display:none;">
  <button type="button" id="programLightboxClose" aria-label="Tutup"
          class="absolute top-4 right-4 w-11 h-11 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white text-xl transition-colors">
    <i class="fas fa-times"></i>
  </button>
  <img id="programLightboxImg" src="" alt="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl">
  <div id="programLightboxCaption" class="absolute bottom-6 left-1/2 -translate-x-1/2 max-w-[90%] px-4 py-2 bg-slate-900/80 text-white text-sm rounded-lg hidden"></div>
</div>

<script>
(function () {
    /* ---- Slideshow ---- */
    var cover  = document.getElementById('programCover');
    if (cover) {
        var slides     = cover.querySelectorAll('.program-slide');
        var captions   = cover.querySelectorAll('.program-slide-caption');
        var indicators = cover.querySelectorAll('.program-indicator');
        var prevBtn    = document.getElementById('programPrev');
        var nextBtn    = document.getElementById('programNext');
        var current    = 0;
        var auto       = null;
        var paused     = false;

        function show(i) {
            current = (i + slides.length) % slides.length;
            slides.forEach(function (s, idx) {
                s.classList.toggle('opacity-100', idx === current);
                s.classList.toggle('opacity-0',   idx !== current);
                s.classList.toggle('pointer-events-none', idx !== current);
            });
            captions.forEach(function (c) {
                var idx = parseInt(c.getAttribute('data-caption-for'), 10);
                c.classList.toggle('hidden', idx !== current);
            });
            indicators.forEach(function (b, idx) {
                var on = idx === current;
                b.classList.toggle('w-6',      on);
                b.classList.toggle('bg-white', on);
                b.classList.toggle('w-1.5',    !on);
                b.classList.toggle('bg-white/50', !on);
            });
        }
        function next() { show(current + 1); }
        function prev() { show(current - 1); }
        function startAuto() {
            if (slides.length < 2) return;
            stopAuto();
            auto = setInterval(function () { if (!paused) next(); }, 4500);
        }
        function stopAuto() { if (auto) { clearInterval(auto); auto = null; } }

        if (slides.length > 1) {
            if (prevBtn) prevBtn.addEventListener('click', function () { prev(); startAuto(); });
            if (nextBtn) nextBtn.addEventListener('click', function () { next(); startAuto(); });
            indicators.forEach(function (b) {
                b.addEventListener('click', function () {
                    show(parseInt(b.getAttribute('data-program-indicator'), 10));
                    startAuto();
                });
            });
            cover.addEventListener('mouseenter', function () { paused = true; });
            cover.addEventListener('mouseleave', function () { paused = false; });
            startAuto();
        }
    }

    /* ---- Lightbox ---- */
    var lb        = document.getElementById('programLightbox');
    var lbImg     = document.getElementById('programLightboxImg');
    var lbCap     = document.getElementById('programLightboxCaption');
    var lbClose   = document.getElementById('programLightboxClose');

    function openLightbox(src, caption) {
        if (!src) return;
        lbImg.src = src;
        if (caption) {
            lbCap.textContent = caption;
            lbCap.classList.remove('hidden');
        } else {
            lbCap.classList.add('hidden');
            lbCap.textContent = '';
        }
        lb.style.display = 'flex';
        lb.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        lb.style.display = 'none';
        lb.classList.add('hidden');
        lbImg.src = '';
        document.body.style.overflow = '';
    }
    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lb) lb.addEventListener('click', function (e) { if (e.target === lb) closeLightbox(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && lb && lb.style.display !== 'none') closeLightbox();
    });

    // Wire any element with data-lightbox-src
    document.querySelectorAll('[data-lightbox-src]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            // Don't intercept when the click was on a slideshow control
            if (e.target.closest('button') && el.tagName === 'IMG') return;
            e.preventDefault();
            openLightbox(
                el.getAttribute('data-lightbox-src'),
                el.getAttribute('data-lightbox-caption') || ''
            );
        });
    });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
