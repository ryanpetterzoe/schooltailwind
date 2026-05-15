<?php
$pageTitle = 'Agenda Kegiatan - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Agenda Kegiatan</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Agenda</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <div class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide mb-4">
        <i class="fas fa-calendar-alt"></i> Jadwal
      </div>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white mb-2">Agenda <span class="text-blue-600">Kegiatan Sekolah</span></h2>
      <p class="text-slate-400 max-w-lg mx-auto">Kalender kegiatan dan jadwal penting sekolah</p>
    </div>

    <?php if (empty($agendas)): ?>
    <div class="text-center py-16">
      <i class="fas fa-calendar-times text-5xl text-slate-200 dark:text-slate-700 mb-4"></i>
      <p class="text-slate-400">Belum ada agenda kegiatan.</p>
    </div>
    <?php else: ?>
    <div class="max-w-3xl mx-auto space-y-3">
      <?php foreach ($agendas as $ag):
        $isPast = strtotime($ag['start_date']) < strtotime('today');
      ?>
      <div class="flex gap-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-4 hover:translate-x-1 hover:border-blue-200 dark:hover:border-blue-700 transition-all <?= $isPast ? 'opacity-60' : '' ?>">
        <div class="w-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex flex-col items-center justify-center py-2 flex-shrink-0">
          <span class="text-lg font-black text-white leading-none"><?= date('d', strtotime($ag['start_date'])) ?></span>
          <span class="text-[10px] text-white/80 uppercase font-semibold"><?= date('M', strtotime($ag['start_date'])) ?></span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <h6 class="font-bold text-sm text-slate-800 dark:text-white"><?= htmlspecialchars($ag['title']) ?></h6>
            <?php if ($isPast): ?>
            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-400 text-[10px] font-bold rounded-full">Selesai</span>
            <?php else: ?>
            <span class="px-2 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-600 text-[10px] font-bold rounded-full">Upcoming</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($ag['location'])): ?>
          <p class="text-xs text-slate-400 mb-1"><i class="fas fa-map-marker-alt text-blue-500 mr-1"></i><?= htmlspecialchars($ag['location']) ?></p>
          <?php endif; ?>
          <?php if (!empty($ag['end_date']) && $ag['end_date'] !== $ag['start_date']): ?>
          <p class="text-xs text-slate-400 mb-1"><i class="fas fa-calendar mr-1"></i>s/d <?= date('d M Y', strtotime($ag['end_date'])) ?></p>
          <?php endif; ?>
          <?php if (!empty($ag['description'])): ?>
          <p class="text-xs text-slate-400 leading-relaxed"><?= htmlspecialchars($ag['description']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
