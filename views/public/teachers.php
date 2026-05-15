<?php
$pageTitle = 'Guru & Staff - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Guru & Staff</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Guru & Staff</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Teachers -->
    <div class="mb-10">
      <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-2">Tenaga Pendidik</h2>
      <p class="text-slate-400 mb-8">Guru-guru berpengalaman dan berdedikasi tinggi</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-14">
      <?php foreach ($teachers as $teacher): ?>
      <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-center hover:-translate-y-1 hover:shadow-lg transition-all">
        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700">
          <?php if (!empty($teacher['photo'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($teacher['photo']) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user text-2xl text-slate-300 dark:text-slate-500"></i></div>
          <?php endif; ?>
        </div>
        <h6 class="font-semibold text-xs sm:text-sm text-slate-800 dark:text-white leading-tight mb-1"><?= htmlspecialchars($teacher['name']) ?></h6>
        <p class="text-[11px] text-slate-400 mb-0.5"><?= htmlspecialchars($teacher['position'] ?? '') ?></p>
        <?php if (!empty($teacher['subject'])): ?>
        <p class="text-[11px] text-blue-600 font-medium"><?= htmlspecialchars($teacher['subject']) ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Staff -->
    <?php if (!empty($staff)): ?>
    <div class="mb-10">
      <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-2">Tenaga Kependidikan</h2>
      <p class="text-slate-400 mb-8">Staff pendukung yang memastikan operasional sekolah berjalan lancar</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
      <?php foreach ($staff as $s): ?>
      <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-center hover:-translate-y-1 hover:shadow-lg transition-all">
        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700">
          <?php if (!empty($s['photo'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($s['photo']) ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user-friends text-2xl text-slate-300 dark:text-slate-500"></i></div>
          <?php endif; ?>
        </div>
        <h6 class="font-semibold text-xs sm:text-sm text-slate-800 dark:text-white leading-tight mb-1"><?= htmlspecialchars($s['name']) ?></h6>
        <p class="text-[11px] text-slate-400 mb-0.5"><?= htmlspecialchars($s['position'] ?? '') ?></p>
        <?php if (!empty($s['department'])): ?>
        <p class="text-[11px] text-blue-600 font-medium"><?= htmlspecialchars($s['department']) ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
