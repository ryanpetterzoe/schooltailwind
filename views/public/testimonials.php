<?php
$pageTitle = 'Testimoni - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Testimoni</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Testimoni</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
        <i class="fas fa-quote-left"></i> Kata Mereka
      </div>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white mb-2">Apa Kata <span class="text-blue-600">Alumni & Orang Tua</span></h2>
      <p class="text-slate-400 max-w-lg mx-auto">Pengalaman nyata dari alumni dan orang tua siswa</p>
    </div>

    <?php if (empty($testimonials)): ?>
    <div class="text-center py-16">
      <i class="fas fa-comment-slash text-5xl text-slate-200 dark:text-slate-700 mb-4"></i>
      <p class="text-slate-400">Belum ada testimoni.</p>
    </div>
    <?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($testimonials as $t): ?>
      <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 hover:-translate-y-1 hover:shadow-lg transition-all relative">
        <div class="absolute top-3 left-5 text-6xl text-blue-100 dark:text-blue-900/40 font-serif leading-none">"</div>
        <div class="text-yellow-400 text-sm mb-3 relative"><?php for ($i = 0; $i < (int)($t['rating'] ?? 5); $i++): ?>★<?php endfor; ?></div>
        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-5 italic relative">"<?= htmlspecialchars($t['content']) ?>"</p>
        <div class="flex items-center gap-3">
          <?php if (!empty($t['photo'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($t['photo']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" decoding="async" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
          <?php else: ?>
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            <?= strtoupper(substr($t['name'], 0, 1)) ?>
          </div>
          <?php endif; ?>
          <div>
            <div class="font-semibold text-sm text-slate-800 dark:text-white"><?= htmlspecialchars($t['name']) ?></div>
            <div class="text-xs text-slate-400"><?= htmlspecialchars($t['position'] ?? '') ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
