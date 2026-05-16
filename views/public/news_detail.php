<?php
$pageTitle = htmlspecialchars($news['title'] ?? 'Berita') . ' - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Page Header -->
<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot; fill=&quot;white&quot; fill-opacity=&quot;0.1&quot;/%3E%3C/svg%3E')]"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-2 leading-tight"><?= htmlspecialchars($news['title'] ?? '') ?></h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <a href="<?= APP_URL ?>/berita" class="text-white/70 hover:text-white transition-colors">Berita</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Detail</span>
    </nav>
  </div>
</section>

<section class="py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-3 gap-8">
      <!-- Article -->
      <div class="lg:col-span-2">
        <article class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">
          <!-- Meta -->
          <div class="flex flex-wrap items-center gap-3 mb-5">
            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full"><?= htmlspecialchars($news['category'] ?? '') ?></span>
            <?php
            $db2 = getDB();
            $newsProgRes = $db2->query("SELECT p.name, p.code, p.id FROM news n LEFT JOIN programs p ON n.program_id=p.id WHERE n.id=".(int)$news['id']." LIMIT 1");
            $newsProg = $newsProgRes ? $newsProgRes->fetch_assoc() : null;
            if ($newsProg && !empty($newsProg['name'])): ?>
            <a href="<?= APP_URL ?>/berita?program=<?= $newsProg['id'] ?>" class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 border border-blue-100 dark:border-blue-800 text-xs font-bold rounded-full hover:bg-blue-100 transition-colors">
              <i class="fas fa-book mr-1"></i><?= htmlspecialchars($newsProg['name']) ?>
            </a>
            <?php endif; ?>
            <span class="text-xs text-slate-400"><i class="fas fa-user mr-1"></i><?= htmlspecialchars($news['author'] ?? 'Admin') ?></span>
            <span class="text-xs text-slate-400"><i class="fas fa-calendar mr-1"></i><?= formatDate($news['published_at']) ?></span>
            <span class="text-xs text-slate-400"><i class="fas fa-eye mr-1"></i><?= number_format($news['views'] ?? 0) ?></span>
          </div>

          <!-- Image -->
          <?php if (!empty($news['image'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($news['image']) ?>" alt="<?= htmlspecialchars($news['title'] ?? '') ?>" loading="lazy" decoding="async" class="w-full h-64 sm:h-80 object-cover rounded-xl mb-6">
          <?php endif; ?>

          <!-- Content -->
          <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed">
            <?= $news['content'] ?? '' ?>
          </div>

          <!-- Share -->
          <div class="border-t border-slate-100 dark:border-slate-700 mt-8 pt-6">
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-sm text-slate-400 mr-1">Bagikan:</span>
              <?php $shareUrl = urlencode(APP_URL . '/berita/' . ($news['slug'] ?? '')); $shareTitle = urlencode($news['title'] ?? ''); ?>
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" class="px-3 py-1.5 bg-[#1877f2] text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity"><i class="fab fa-facebook-f mr-1"></i>Facebook</a>
              <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" class="px-3 py-1.5 bg-[#1da1f2] text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity"><i class="fab fa-x-twitter mr-1"></i>Twitter</a>
              <a href="https://wa.me/?text=<?= $shareTitle ?>+<?= $shareUrl ?>" target="_blank" class="px-3 py-1.5 bg-[#25d366] text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity"><i class="fab fa-whatsapp mr-1"></i>WhatsApp</a>
            </div>
          </div>
        </article>

        <a href="<?= APP_URL ?>/berita" class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
          <i class="fas fa-arrow-left"></i>Kembali ke Berita
        </a>
      </div>

      <!-- Sidebar -->
      <div>
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
          <h5 class="font-bold text-slate-800 dark:text-white mb-4">Berita Terbaru</h5>
          <?php
          $db = getDB();
          $latestRes = $db->query("SELECT id,title,slug,published_at,image FROM news WHERE is_published=1 AND id != " . (int)($news['id'] ?? 0) . " ORDER BY published_at DESC LIMIT 5");
          $latestNews = $latestRes ? $latestRes->fetch_all(MYSQLI_ASSOC) : [];
          foreach ($latestNews as $ln): ?>
          <div class="flex gap-3 mb-4 last:mb-0">
            <div class="w-16 h-14 rounded-lg overflow-hidden flex-shrink-0 bg-slate-100 dark:bg-slate-700">
              <?php if (!empty($ln['image'])): ?>
              <img src="<?= UPLOAD_URL . htmlspecialchars($ln['image']) ?>" loading="lazy" decoding="async" class="w-full h-full object-cover" alt="">
              <?php else: ?>
              <div class="w-full h-full flex items-center justify-center"><i class="fas fa-newspaper text-slate-300 dark:text-slate-500"></i></div>
              <?php endif; ?>
            </div>
            <div class="min-w-0">
              <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($ln['slug']) ?>" class="text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-blue-600 transition-colors line-clamp-2 leading-snug"><?= htmlspecialchars($ln['title']) ?></a>
              <span class="text-xs text-slate-400 mt-1 block"><?= timeAgo($ln['published_at']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
