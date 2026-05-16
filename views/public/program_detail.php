<?php
$pageTitle = htmlspecialchars($program['name'] ?? 'Detail Jurusan') . ' - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
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
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">
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
          <div class="text-slate-600 dark:text-slate-300 leading-relaxed"><?= nl2br(htmlspecialchars($program['description'] ?? '')) ?></div>
        </div>

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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
