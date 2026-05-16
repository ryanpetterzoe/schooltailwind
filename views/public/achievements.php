<?php
$pageTitle = 'Prestasi - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
$levelFilter = htmlspecialchars($_GET['level'] ?? '');
$levels = ['sekolah','kabupaten','provinsi','nasional','internasional'];
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Prestasi</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Prestasi</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Level Filter -->
    <div class="flex flex-wrap gap-2 mb-8 justify-center">
      <a href="<?= APP_URL ?>/prestasi" class="px-4 py-2 text-sm font-semibold rounded-full transition-all <?= !$levelFilter ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>">Semua Tingkat</a>
      <?php foreach ($levels as $lvl): ?>
      <a href="<?= APP_URL ?>/prestasi?level=<?= $lvl ?>" class="px-4 py-2 text-sm font-semibold rounded-full transition-all <?= $levelFilter === $lvl ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>"><?= ucfirst($lvl) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($achievements)): ?>
    <div class="text-center py-16">
      <i class="fas fa-trophy text-5xl text-slate-200 dark:text-slate-700 mb-4"></i>
      <p class="text-slate-400">Belum ada prestasi yang tercatat.</p>
    </div>
    <?php else: ?>
    <div class="grid md:grid-cols-2 gap-4">
      <?php foreach ($achievements as $ach): ?>
      <div class="flex gap-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5 hover:-translate-y-0.5 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700 transition-all">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0
          <?php if($ach['level']==='nasional'||$ach['level']==='internasional'): ?>bg-gradient-to-br from-yellow-400 to-amber-500
          <?php elseif($ach['level']==='provinsi'): ?>bg-gradient-to-br from-blue-400 to-blue-600
          <?php elseif($ach['level']==='kabupaten'): ?>bg-gradient-to-br from-green-400 to-emerald-500
          <?php else: ?>bg-blue-50 dark:bg-blue-900/30<?php endif; ?>">
          <i class="fas fa-trophy <?php if($ach['level']==='sekolah'): ?>text-blue-600<?php else: ?>text-white<?php endif; ?>"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2 mb-1">
            <h6 class="font-bold text-sm text-slate-800 dark:text-white"><?= htmlspecialchars($ach['title']) ?></h6>
            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full uppercase flex-shrink-0
              <?php if($ach['level']==='nasional'||$ach['level']==='internasional'): ?>bg-amber-50 text-amber-600 dark:bg-amber-900/30
              <?php elseif($ach['level']==='provinsi'): ?>bg-blue-50 text-blue-600 dark:bg-blue-900/30
              <?php elseif($ach['level']==='kabupaten'): ?>bg-green-50 text-green-600 dark:bg-green-900/30
              <?php else: ?>bg-slate-50 text-slate-600 dark:bg-slate-700 dark:text-slate-300<?php endif; ?>"><?= ucfirst($ach['level']) ?></span>
          </div>
          <?php if (!empty($ach['description'])): ?>
          <p class="text-xs text-slate-400 leading-relaxed mb-2"><?= htmlspecialchars(richExcerpt($ach['description'], 200)) ?></p>
          <?php endif; ?>
          <?php if (!empty($ach['year'])): ?>
          <span class="text-xs text-slate-400"><i class="fas fa-calendar mr-1"></i>Tahun <?= $ach['year'] ?></span>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
