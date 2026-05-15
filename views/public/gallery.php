<?php
$pageTitle = 'Galeri - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
$catFilter = htmlspecialchars($_GET['category'] ?? '');
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Galeri Foto</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Galeri</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Category Filter -->
    <?php if (!empty($categories)): ?>
    <div class="flex flex-wrap gap-2 mb-8 justify-center">
      <a href="<?= APP_URL ?>/galeri" class="px-4 py-2 text-sm font-semibold rounded-full transition-all <?= !$catFilter ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>">Semua</a>
      <?php foreach ($categories as $cat): ?>
      <a href="<?= APP_URL ?>/galeri?category=<?= urlencode($cat) ?>" class="px-4 py-2 text-sm font-semibold rounded-full transition-all <?= $catFilter === $cat ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>"><?= htmlspecialchars($cat) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Gallery Grid -->
    <?php if (empty($gallery)): ?>
    <div class="text-center py-16">
      <i class="fas fa-images text-5xl text-slate-200 dark:text-slate-700 mb-4"></i>
      <p class="text-slate-400">Belum ada foto galeri.</p>
    </div>
    <?php else: ?>
    <div class="gallery-grid">
      <?php foreach ($gallery as $item): ?>
      <div class="gallery-item" data-src="<?= UPLOAD_URL . htmlspecialchars($item['image']) ?>" data-title="<?= htmlspecialchars($item['title']) ?>">
        <?php if (!empty($item['image'])): ?>
        <img src="<?= UPLOAD_URL . htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
        <?php else: ?>
        <div class="h-[220px] bg-slate-100 dark:bg-slate-800 flex items-center justify-center"><i class="fas fa-image text-3xl text-slate-300"></i></div>
        <?php endif; ?>
        <div class="gallery-overlay">
          <i class="fas fa-expand text-lg"></i>
          <span class="text-sm font-medium"><?= htmlspecialchars($item['title']) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-10 flex justify-center">
      <div class="flex items-center gap-1">
        <?php if ($currentPage > 1): ?>
        <a href="<?= APP_URL ?>/galeri?page=<?= $currentPage-1 ?><?= $catFilter ? '&category='.urlencode($catFilter) : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-left text-xs"></i></a>
        <?php endif; ?>
        <?php for ($p=1; $p<=$totalPages; $p++): ?>
        <a href="<?= APP_URL ?>/galeri?page=<?= $p ?><?= $catFilter ? '&category='.urlencode($catFilter) : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium <?= $p==$currentPage ? 'bg-blue-600 text-white' : 'border border-slate-200 dark:border-slate-700 text-slate-600 hover:bg-blue-50 hover:text-blue-600' ?> transition-colors"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="<?= APP_URL ?>/galeri?page=<?= $currentPage+1 ?><?= $catFilter ? '&category='.urlencode($catFilter) : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-right text-xs"></i></a>
        <?php endif; ?>
      </div>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
