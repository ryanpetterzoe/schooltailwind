<?php
$pageTitle = 'Program Keahlian - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot; fill=&quot;white&quot; fill-opacity=&quot;0.1&quot;/%3E%3C/svg%3E')]"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Program Keahlian</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Jurusan</span>
    </nav>
  </div>
</section>

<section class="py-16 lg:py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white mb-3">Pilih Jurusan Terbaik</h2>
      <p class="text-slate-500 dark:text-slate-400 max-w-lg mx-auto">Kami memiliki <?= count($programs) ?> program keahlian unggulan yang siap membawa Anda menuju karir impian</p>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
      <?php foreach ($programs as $prog): ?>
      <div class="bg-white dark:bg-slate-800 border-l-4 border-l-blue-600 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 hover:-translate-y-1 hover:shadow-xl transition-all">
        <div class="flex gap-5">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-blue-500/20 flex-shrink-0">
            <i class="<?= htmlspecialchars($prog['icon'] ?? 'fas fa-book') ?>"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <h5 class="font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($prog['name']) ?></h5>
              <?php if (!empty($prog['code'])): ?>
              <span class="px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full"><?= htmlspecialchars($prog['code']) ?></span>
              <?php endif; ?>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed mb-4"><?= htmlspecialchars(substr($prog['description'] ?? '', 0, 180)) ?>...</p>
            <div class="flex items-center gap-4">
              <span class="text-sm text-slate-400"><i class="fas fa-users mr-1 text-blue-500"></i>Kuota: <strong class="text-slate-700 dark:text-white"><?= $prog['quota'] ?></strong></span>
              <a href="<?= APP_URL ?>/jurusan/<?= $prog['id'] ?>" class="px-4 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-bold rounded-lg shadow hover:-translate-y-0.5 transition-all inline-flex items-center gap-1">Detail <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="relative py-20 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 overflow-hidden">
  <div class="max-w-3xl mx-auto px-4 text-center relative z-10">
    <h2 class="text-3xl font-extrabold text-white mb-4">Tertarik Bergabung?</h2>
    <p class="text-white/75 mb-8">Daftarkan diri Anda sekarang dan pilih jurusan impian Anda</p>
    <a href="<?= APP_URL ?>/spmb/daftar" class="px-7 py-3.5 bg-white text-blue-700 rounded-xl font-bold shadow-xl hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">Daftar Sekarang</a>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
