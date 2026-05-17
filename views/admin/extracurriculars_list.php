<?php $adminPageTitle = 'Manajemen Ekstrakurikuler'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-running text-blue-600"></i>Daftar Ekstrakurikuler</h5>
        <a href="<?= APP_URL ?>/admin/ekskul/tambah" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors inline-flex items-center gap-1">
            <i class="fas fa-plus"></i>Tambah Ekstrakurikuler
        </a>
    </div>

    <div class="px-4 sm:px-6 pt-4 text-xs text-slate-500 dark:text-slate-400">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Ekstrakurikuler yang aktif akan tampil di halaman publik /ekskul dan di dropdown navbar.
    </div>

    <div class="overflow-x-auto mt-2">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama Ekstrakurikuler</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Urutan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($extracurriculars)): ?>
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">
                    Belum ada ekstrakurikuler.
                </td></tr>
                <?php else: ?>
                <?php foreach ($extracurriculars as $e): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i class="<?= htmlspecialchars($e['icon'] ?? 'fas fa-futbol') ?>"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 dark:text-white"><?= htmlspecialchars($e['name']) ?></div>
                                <small class="text-slate-400 dark:text-slate-500"><?= htmlspecialchars(richExcerpt($e['description'] ?? '', 80)) ?>...</small>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= (int)$e['sort_order'] ?></td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $e['is_active'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>"><?= $e['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/admin/ekskul/edit/<?= $e['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors" title="Edit"><i class="fas fa-edit text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/ekskul/hapus/<?= $e['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus ekstrakurikuler ini?" title="Hapus"><i class="fas fa-trash text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
