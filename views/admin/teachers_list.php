<?php $adminPageTitle = 'Manajemen Guru'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-chalkboard-teacher text-blue-600"></i>Daftar Guru</h5>
        <a href="<?= APP_URL ?>/admin/guru/tambah" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors inline-flex items-center gap-1">
            <i class="fas fa-plus"></i>Tambah Guru
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Foto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">NIP</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Jabatan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Mata Pelajaran</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($teachers)): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada data guru.</td></tr>
                <?php else: ?>
                <?php foreach ($teachers as $teacher): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3">
                        <?php if (!empty($teacher['photo'])): ?>
                            <img src="<?= UPLOAD_URL . htmlspecialchars($teacher['photo']) ?>" alt="" class="w-11 h-11 rounded-full object-cover border-2 border-slate-200 dark:border-slate-600">
                        <?php else: ?>
                            <div class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400"><i class="fas fa-user"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-slate-800 dark:text-white"><?= htmlspecialchars($teacher['name']) ?></div>
                        <?php if (!empty($teacher['email'])): ?><small class="text-slate-400 dark:text-slate-500"><?= htmlspecialchars($teacher['email']) ?></small><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs"><?= htmlspecialchars($teacher['nip'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($teacher['position'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($teacher['subject'] ?? '-') ?></td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $teacher['is_active'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>"><?= $teacher['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/admin/guru/edit/<?= $teacher['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors"><i class="fas fa-edit text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/guru/hapus/<?= $teacher['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus data guru ini?"><i class="fas fa-trash text-xs"></i></a>
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
