<?php
$pageTitle = 'Berita - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
$searchVal  = htmlspecialchars(isset($_GET['q']) ? $_GET['q'] : '');
$catVal     = htmlspecialchars(isset($_GET['category']) ? $_GET['category'] : '');
$activeProg = isset($activeProgram) ? (int)$activeProgram : 0;
?>

<!-- Page Header -->
<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot; fill=&quot;white&quot; fill-opacity=&quot;0.1&quot;/%3E%3C/svg%3E')]"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Berita & Informasi</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Berita</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Filter Tabs -->
    <div class="flex flex-wrap gap-2 items-center mb-5">
      <span class="text-xs font-semibold text-slate-400 mr-1">Filter:</span>
      <a href="<?= APP_URL ?>/berita<?= $searchVal ? '?q='.$searchVal : '' ?>" class="px-3 py-1.5 text-xs font-semibold rounded-full transition-all <?= $activeProg === 0 && !$catVal ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>">
        <i class="fas fa-globe mr-1"></i>Semua
      </a>
      <?php foreach ($programs as $prog): ?>
      <a href="<?= APP_URL ?>/berita?program=<?= $prog['id'] ?><?= $searchVal ? '&q='.$searchVal : '' ?>" class="px-3 py-1.5 text-xs font-semibold rounded-full transition-all <?= $activeProg === (int)$prog['id'] ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50' ?>">
        <i class="fas fa-book mr-1"></i><?= htmlspecialchars($prog['code'] ?: mb_substr($prog['name'],0,6)) ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Search & Category Filter -->
    <form method="GET" action="<?= APP_URL ?>/berita" class="mb-6">
      <?php if ($activeProg): ?><input type="hidden" name="program" value="<?= $activeProg ?>"><?php endif; ?>
      <div class="grid sm:grid-cols-12 gap-3">
        <div class="sm:col-span-5">
          <div class="flex">
            <input type="text" name="q" placeholder="Cari berita..." value="<?= $searchVal ?>"
                   class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-l-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-r-xl hover:bg-blue-700 transition-colors"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <div class="sm:col-span-3">
          <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $catVal === htmlspecialchars($cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php if ($searchVal || $catVal || $activeProg): ?>
        <div class="sm:col-span-2">
          <a href="<?= APP_URL ?>/berita" class="flex items-center justify-center gap-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <i class="fas fa-times"></i>Reset
          </a>
        </div>
        <?php endif; ?>
      </div>
    </form>

    <!-- Active program info -->
    <?php if ($activeProg && !empty($programs)):
      $activeProgramData = null;
      foreach ($programs as $p) if ((int)$p['id'] === $activeProg) { $activeProgramData = $p; break; }
      if ($activeProgramData): ?>
    <div class="flex items-center gap-3 mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl">
      <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">📚</div>
      <div class="flex-1">
        <div class="font-bold text-sm text-slate-800 dark:text-white">Berita Jurusan: <?= htmlspecialchars($activeProgramData['name']) ?></div>
        <div class="text-xs text-slate-400">Menampilkan berita khusus jurusan ini + berita umum</div>
      </div>
      <a href="<?= APP_URL ?>/jurusan/<?= $activeProg ?>" class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 border border-blue-600 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition-all">Info Jurusan <i class="fas fa-arrow-right"></i></a>
    </div>
    <?php endif; endif; ?>

    <!-- News Grid -->
    <?php if (empty($news)): ?>
    <div class="text-center py-16">
      <i class="fas fa-newspaper text-5xl text-slate-200 dark:text-slate-700 mb-4"></i>
      <p class="text-slate-400"><?= $activeProg ? 'Belum ada berita untuk jurusan ini.' : 'Belum ada berita ditemukan.' ?></p>
      <?php if ($searchVal || $catVal || $activeProg): ?>
      <a href="<?= APP_URL ?>/berita" class="mt-3 inline-flex items-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-600 hover:text-white transition-all">Tampilkan Semua</a>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($news as $article): ?>
      <article class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-xl transition-all duration-300 flex flex-col">
        <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>" class="relative block h-48 overflow-hidden">
          <?php if (!empty($article['image'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          <?php else: ?>
          <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center"><i class="fas fa-newspaper text-3xl text-slate-300 dark:text-slate-500"></i></div>
          <?php endif; ?>
          <?php if (!empty($article['program_name'])): ?>
          <span class="absolute top-3 right-3 px-2.5 py-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-bold rounded-full shadow"><?= htmlspecialchars($article['program_code'] ?: $article['program_name']) ?></span>
          <?php endif; ?>
        </a>
        <div class="p-5 flex flex-col flex-1">
          <div class="flex items-center gap-2 mb-3 flex-wrap">
            <span class="text-[11px] font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 rounded-full uppercase"><?= htmlspecialchars($article['category']) ?></span>
            <span class="text-xs text-slate-400"><i class="fas fa-clock mr-1"></i><?= timeAgo($article['published_at']) ?></span>
          </div>
          <h5 class="font-bold text-slate-800 dark:text-white mb-2 leading-snug group-hover:text-blue-600 transition-colors">
            <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>"><?= htmlspecialchars($article['title']) ?></a>
          </h5>
          <p class="text-sm text-slate-400 leading-relaxed flex-1"><?= htmlspecialchars(mb_substr($article['excerpt'] ?? '', 0, 110)) ?>...</p>
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
            <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>" class="text-sm font-semibold text-blue-600 flex items-center gap-1.5 hover:gap-2.5 transition-all">Baca <i class="fas fa-arrow-right text-xs"></i></a>
            <span class="text-xs text-slate-400"><i class="fas fa-user mr-1"></i><?= htmlspecialchars($article['author']) ?></span>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <nav class="mt-10 flex justify-center">
      <div class="flex items-center gap-1">
        <?php if ($currentPage > 1): ?>
        <a href="<?= APP_URL ?>/berita?page=<?= $currentPage-1 ?><?= $searchVal ? '&q='.$searchVal : '' ?><?= $catVal ? '&category='.urlencode($catVal) : '' ?><?= $activeProg ? '&program='.$activeProg : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-left text-xs"></i></a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= APP_URL ?>/berita?page=<?= $p ?><?= $searchVal ? '&q='.$searchVal : '' ?><?= $catVal ? '&category='.urlencode($catVal) : '' ?><?= $activeProg ? '&program='.$activeProg : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium transition-colors <?= $p == $currentPage ? 'bg-blue-600 text-white' : 'border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="<?= APP_URL ?>/berita?page=<?= $currentPage+1 ?><?= $searchVal ? '&q='.$searchVal : '' ?><?= $catVal ? '&category='.urlencode($catVal) : '' ?><?= $activeProg ? '&program='.$activeProg : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-right text-xs"></i></a>
        <?php endif; ?>
      </div>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
