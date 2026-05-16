<?php
$pageTitle = 'SPMB - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-1">SPMB <?= htmlspecialchars($spmbSettings['academic_year'] ?? '') ?></h1>
    <p class="text-white/70 mb-3">Seleksi Penerimaan Murid Baru</p>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">SPMB</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Status Banner -->
    <?php if (!empty($spmbSettings)): ?>
    <div class="flex items-center gap-4 p-5 mb-8 bg-white dark:bg-slate-800 border-l-4 <?= ($spmbSettings['is_active'] ?? 0) ? 'border-l-green-500' : 'border-l-red-500' ?> border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
      <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 <?= ($spmbSettings['is_active'] ?? 0) ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' ?>">
        <i class="fas fa-<?= ($spmbSettings['is_active'] ?? 0) ? 'check' : 'times' ?> text-lg <?= ($spmbSettings['is_active'] ?? 0) ? 'text-green-600' : 'text-red-500' ?>"></i>
      </div>
      <div class="flex-1">
        <h5 class="font-bold text-slate-800 dark:text-white">Pendaftaran <?= ($spmbSettings['is_active'] ?? 0) ? 'DIBUKA' : 'DITUTUP' ?></h5>
        <p class="text-sm text-slate-400">Periode: <?= formatDate($spmbSettings['open_date'] ?? '') ?> s/d <?= formatDate($spmbSettings['close_date'] ?? '') ?></p>
      </div>
      <?php if ($spmbSettings['is_active'] ?? 0): ?>
      <a href="<?= APP_URL ?>/spmb/daftar" class="hidden sm:inline-flex px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg hover:-translate-y-0.5 transition-all">Daftar Sekarang</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">
        <!-- Info -->
        <?php if (!empty($spmbSettings['info'])): ?>
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h4 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-info-circle text-blue-500"></i>Informasi SPMB</h4>
          <div class="border-t border-slate-100 dark:border-slate-700 pt-4 text-slate-600 dark:text-slate-300 leading-relaxed prose prose-slate dark:prose-invert max-w-none"><?= safeRichHtml($spmbSettings['info']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Requirements -->
        <?php if (!empty($spmbSettings['requirements'])): ?>
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h4 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-clipboard-list text-blue-500"></i>Persyaratan Pendaftaran</h4>
          <div class="border-t border-slate-100 dark:border-slate-700 pt-4 text-slate-600 dark:text-slate-300 leading-loose prose prose-slate dark:prose-invert max-w-none"><?= safeRichHtml($spmbSettings['requirements']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Timeline -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h4 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-calendar-alt text-blue-500"></i>Jadwal SPMB</h4>
          <div class="border-t border-slate-100 dark:border-slate-700 pt-4 space-y-4">
            <?php
            $timeline = [
              ['label'=>'Pendaftaran Dibuka','date'=>$spmbSettings['open_date'] ?? null,'icon'=>'fa-door-open','color'=>'bg-green-500'],
              ['label'=>'Batas Pendaftaran','date'=>$spmbSettings['close_date'] ?? null,'icon'=>'fa-door-closed','color'=>'bg-amber-500'],
              ['label'=>'Pengumuman Hasil','date'=>$spmbSettings['announcement_date'] ?? null,'icon'=>'fa-bullhorn','color'=>'bg-blue-500'],
            ];
            foreach ($timeline as $tl): if (empty($tl['date'])) continue; ?>
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 <?= $tl['color'] ?> rounded-full flex items-center justify-center flex-shrink-0"><i class="fas <?= $tl['icon'] ?> text-white text-sm"></i></div>
              <div>
                <div class="font-semibold text-sm text-slate-700 dark:text-slate-200"><?= $tl['label'] ?></div>
                <div class="text-sm text-slate-400"><?= formatDate($tl['date']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h5 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-list text-blue-500"></i>Kuota per Jurusan</h5>
          <div class="border-t border-slate-100 dark:border-slate-700 pt-4 space-y-3">
            <?php foreach ($programs as $prog): ?>
            <div class="flex justify-between items-center">
              <div>
                <div class="text-sm font-medium text-slate-700 dark:text-slate-200"><?= htmlspecialchars($prog['name']) ?></div>
                <?php if (!empty($prog['code'])): ?><span class="text-xs text-slate-400"><?= htmlspecialchars($prog['code']) ?></span><?php endif; ?>
              </div>
              <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full"><?= $prog['quota'] ?> Kursi</span>
            </div>
            <?php endforeach; ?>
            <div class="border-t border-slate-100 dark:border-slate-700 pt-3 flex justify-between">
              <strong class="text-sm text-slate-700 dark:text-white">Total Kuota</strong>
              <strong class="text-sm text-blue-600"><?= $spmbSettings['quota_total'] ?? 144 ?> Siswa</strong>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 space-y-3">
          <h5 class="font-bold text-slate-800 dark:text-white">Aksi</h5>
          <?php if ($spmbSettings['is_active'] ?? 0): ?>
          <a href="<?= APP_URL ?>/spmb/daftar" class="block w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">
            <i class="fas fa-pencil-alt mr-2"></i>Daftar Sekarang
          </a>
          <?php else: ?>
          <button disabled class="block w-full py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-400 text-center rounded-xl font-semibold text-sm cursor-not-allowed">Pendaftaran Belum Dibuka</button>
          <?php endif; ?>
          <a href="<?= APP_URL ?>/spmb/cek" class="block w-full py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 text-center rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
            <i class="fas fa-search mr-2"></i>Cek Status Pendaftaran
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
