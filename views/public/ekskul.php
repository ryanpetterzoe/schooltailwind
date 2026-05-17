<?php
$pageTitle = 'Ekstrakurikuler - ' . ($settings['school_name'] ?? 'Sekolah');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot; fill=&quot;white&quot; fill-opacity=&quot;0.1&quot;/%3E%3C/svg%3E')]"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Ekstrakurikuler</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Ekstrakurikuler</span>
    </nav>
  </div>
</section>

<section class="py-16 lg:py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white mb-3">Kegiatan Ekstrakurikuler</h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">Kami memiliki <?= count($extracurriculars) ?> kegiatan ekstrakurikuler untuk mengembangkan bakat dan minat siswa</p>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
      <?php foreach ($extracurriculars as $e): ?>
      <div class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-xl transition-all flex flex-col">

        <!-- Banner image (or icon-only fallback) -->
        <a href="<?= APP_URL ?>/ekskul/<?= $e['id'] ?>" class="relative block h-44 bg-gradient-to-br from-blue-600 to-indigo-600 overflow-hidden">
          <?php if (!empty($e['image'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($e['image']) ?>"
               alt="<?= htmlspecialchars($e['name']) ?>"
               loading="lazy" decoding="async"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent"></div>
          <?php else: ?>
          <div class="w-full h-full flex items-center justify-center text-white/30 text-7xl">
            <i class="<?= htmlspecialchars($e['icon'] ?? 'fas fa-futbol') ?>"></i>
          </div>
          <?php endif; ?>
          <!-- Floating icon badge -->
          <div class="absolute bottom-3 left-3 w-12 h-12 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center text-blue-600 text-xl shadow-lg ring-2 ring-white/20">
            <i class="<?= htmlspecialchars($e['icon'] ?? 'fas fa-futbol') ?>"></i>
          </div>
        </a>

        <div class="p-5 flex-1 flex flex-col">
          <h5 class="font-bold text-slate-800 dark:text-white mb-2 group-hover:text-blue-600 transition-colors">
            <a href="<?= APP_URL ?>/ekskul/<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></a>
          </h5>
          <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-4 flex-1"><?= htmlspecialchars(richExcerpt($e['description'] ?? '', 180)) ?>...</p>
          <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="<?= APP_URL ?>/ekskul/<?= $e['id'] ?>" class="px-4 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-bold rounded-lg shadow hover:-translate-y-0.5 transition-all inline-flex items-center gap-1">Detail <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
