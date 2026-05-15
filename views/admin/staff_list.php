<?php $adminPageTitle = 'Staff / Karyawan'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-users text-blue-600"></i>Daftar Staff</h5>
        <a href="<?= APP_URL ?>/admin/staff/tambah" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors inline-flex items-center gap-1">
            <i class="fas fa-plus"></i>Tambah Staff
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Jabatan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Departemen</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($staff)): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada data staff</td></tr>
                <?php else: ?>
                <?php foreach ($staff as $s): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3">
                        <?php if (!empty($s['photo'])): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($s['photo']) ?>" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-600">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                            <?= strtoupper(substr($s['name'], 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($s['name']) ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($s['position'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($s['department'] ?? '-') ?></td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $s['is_active'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' ?>"><?= $s['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/admin/staff/edit/<?= $s['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors"><i class="fas fa-edit text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/staff/hapus/<?= $s['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus staff ini?"><i class="fas fa-trash text-xs"></i></a>
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
