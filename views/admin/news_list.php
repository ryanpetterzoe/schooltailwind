<?php $adminPageTitle = 'Manajemen Berita'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-newspaper text-blue-600"></i>Daftar Berita</h5>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" class="w-full sm:w-48 px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari berita..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button class="px-3 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors">Cari</button>
            </form>
            <a href="<?= APP_URL ?>/admin/berita/tambah" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-1"></i>Tambah Berita
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Judul</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Penulis</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Dilihat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($news)): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada berita. <a href="<?= APP_URL ?>/admin/berita/tambah" class="text-blue-600 hover:underline">Tambah sekarang</a></td></tr>
                <?php else: ?>
                <?php foreach ($news as $article): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800 dark:text-white max-w-[280px] truncate"><?= htmlspecialchars($article['title']) ?></div>
                        <small class="text-slate-400 dark:text-slate-500"><?= htmlspecialchars($article['slug']) ?></small>
                    </td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300"><?= htmlspecialchars($article['category']) ?></span></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($article['author']) ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= number_format($article['views']) ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= date('d/m/Y', strtotime($article['published_at'])) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $article['is_published'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>">
                            <?= $article['is_published'] ? 'Dipublikasi' : 'Draft' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/berita/<?= htmlspecialchars($article['slug']) ?>" target="_blank" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors" title="Preview"><i class="fas fa-eye text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/berita/edit/<?= $article['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors" title="Edit"><i class="fas fa-edit text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/berita/toggle/<?= $article['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-amber-600 hover:border-amber-300 transition-colors" title="<?= $article['is_published'] ? 'Arsipkan' : 'Publikasi' ?>"><i class="fas fa-<?= $article['is_published'] ? 'eye-slash' : 'check' ?> text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/berita/hapus/<?= $article['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" title="Hapus" data-confirm="Hapus berita '<?= htmlspecialchars($article['title']) ?>'?"><i class="fas fa-trash text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (($totalPages ?? 1) > 1): ?>
    <div class="flex justify-center p-4 border-t border-slate-100 dark:border-slate-700">
        <div class="flex items-center gap-1">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="<?= APP_URL ?>/admin/berita?page=<?= $p ?><?= !empty($_GET['q']) ? '&q='.urlencode($_GET['q']) : '' ?>" class="px-3 py-1.5 rounded-lg text-sm <?= $p == ($currentPage ?? 1) ? 'bg-blue-600 text-white font-semibold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' ?> transition-colors"><?= $p ?></a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
