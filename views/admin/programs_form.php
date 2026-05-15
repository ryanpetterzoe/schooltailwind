<?php
$isEdit = !empty($program);
$adminPageTitle = $isEdit ? 'Edit Jurusan' : 'Tambah Jurusan';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/jurusan/<?= $isEdit ? 'edit/'.$program['id'] : 'tambah' ?>" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Jurusan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($program['name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kode Jurusan</label>
                        <input type="text" name="code" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="TKJ, RPL, dll" value="<?= htmlspecialchars($program['code'] ?? '') ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" placeholder="Deskripsi program keahlian..."><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Icon (Font Awesome)</label>
                        <input type="text" name="icon" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="fas fa-laptop-code" value="<?= htmlspecialchars($program['icon'] ?? 'fas fa-book') ?>">
                        <small class="text-xs text-slate-400 dark:text-slate-500">Contoh: fas fa-laptop-code</small>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kuota Siswa</label>
                        <input type="number" name="quota" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= (int)($program['quota'] ?? 36) ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= (int)($program['sort_order'] ?? 0) ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Gambar Jurusan</label>
                    <?php if (!empty($program['image'])): ?><div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($program['image']) ?>" class="max-h-20 rounded-lg"></div><?php endif; ?>
                    <input type="file" name="image" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($program['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 relative"></div>
                        <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Jurusan Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button>
                <a href="<?= APP_URL ?>/admin/jurusan" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
