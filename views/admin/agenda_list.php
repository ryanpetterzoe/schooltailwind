<?php $adminPageTitle = 'Agenda Kegiatan'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-calendar-alt text-blue-600"></i>Daftar Agenda</h5>
        <a href="<?= APP_URL ?>/admin/agenda/tambah" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors inline-flex items-center gap-1">
            <i class="fas fa-plus"></i>Tambah Agenda
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Judul</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($agendas)): ?>
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada agenda</td></tr>
                <?php else: ?>
                <?php foreach ($agendas as $ag): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($ag['title']) ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                        <?= date('d M Y', strtotime($ag['start_date'])) ?>
                        <?php if (!empty($ag['end_date']) && $ag['end_date'] !== $ag['start_date']): ?>
                        <small class="text-slate-400 dark:text-slate-500"> s/d <?= date('d M Y', strtotime($ag['end_date'])) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($ag['location'] ?? '-') ?></td>
                    <td class="px-4 py-3">
                        <?php
                        $now = strtotime('today');
                        $start = strtotime($ag['start_date']);
                        if (!$ag['is_published']): ?>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">Draft</span>
                        <?php elseif ($start >= $now): ?>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Upcoming</span>
                        <?php else: ?>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300">Selesai</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/admin/agenda/edit/<?= $ag['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors"><i class="fas fa-edit text-xs"></i></a>
                            <a href="<?= APP_URL ?>/admin/agenda/hapus/<?= $ag['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus agenda ini?"><i class="fas fa-trash text-xs"></i></a>
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
