<?php $adminPageTitle = ($agenda ? 'Edit' : 'Tambah') . ' Agenda'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/agenda/<?= $agenda ? 'edit/' . $agenda['id'] : 'tambah' ?>">
            <input type="hidden" name="_token" value="<?= htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '') ?>">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Judul Agenda <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($agenda['title'] ?? '') ?>">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($agenda['start_date'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Selesai <span class="text-xs text-slate-400">(opsional)</span></label>
                        <input type="date" name="end_date" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($agenda['end_date'] ?? '') ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Lokasi</label>
                    <input type="text" name="location" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Aula Sekolah / Online / dll" value="<?= htmlspecialchars($agenda['location'] ?? '') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4"><?= htmlspecialchars($agenda['description'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" class="sr-only peer" <?= (!isset($agenda) || $agenda['is_published']) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 relative"></div>
                        <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Tampilkan ke publik</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-1"></i> Simpan</button>
                <a href="<?= APP_URL ?>/admin/agenda" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
