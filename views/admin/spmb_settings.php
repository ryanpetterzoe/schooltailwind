<?php $adminPageTitle = 'Pengaturan SPMB'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/spmb/pengaturan">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <?php if (!empty($spmb['id'])): ?>
                <input type="hidden" name="id" value="<?= $spmb['id'] ?>">
            <?php endif; ?>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <input type="text" name="academic_year" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="2025/2026" required value="<?= htmlspecialchars($spmb['academic_year'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Buka <span class="text-red-500">*</span></label>
                        <input type="date" name="open_date" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($spmb['open_date'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="close_date" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($spmb['close_date'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Pengumuman</label>
                        <input type="date" name="announcement_date" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($spmb['announcement_date'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Total Kuota</label>
                        <input type="number" name="quota_total" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= (int)($spmb['quota_total'] ?? 144) ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status Pendaftaran</label>
                        <label class="relative inline-flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($spmb['is_active'] ?? 0) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Dibuka</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Informasi SPMB</label>
                    <textarea name="info" data-rich-editor data-editor-height="200" class="w-full" rows="4" placeholder="Informasi umum tentang SPMB yang tampil di halaman publik..."><?= htmlspecialchars($spmb['info'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Persyaratan Pendaftaran</label>
                    <textarea name="requirements" data-rich-editor data-editor-height="240" class="w-full" rows="6" placeholder="1. Persyaratan pertama&#10;2. Persyaratan kedua&#10;..."><?= htmlspecialchars($spmb['requirements'] ?? '') ?></textarea>
                    <small class="text-xs text-slate-400 dark:text-slate-500 mt-1 block">Gunakan list (bullet/nomor) dari toolbar untuk merapikan persyaratan.</small>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
