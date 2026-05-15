<?php
$isEdit = !empty($teacher);
$adminPageTitle = $isEdit ? 'Edit Data Guru' : 'Tambah Guru';
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/guru/<?= $isEdit ? 'edit/'.$teacher['id'] : 'tambah' ?>" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($teacher['name'] ?? '') ?>" placeholder="Nama lengkap beserta gelar">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">NIP</label>
                        <input type="text" name="nip" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($teacher['nip'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jabatan</label>
                        <input type="text" name="position" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kepala Sekolah, Guru, dll" value="<?= htmlspecialchars($teacher['position'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Mata Pelajaran</label>
                        <input type="text" name="subject" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Matematika, IPA, dll" value="<?= htmlspecialchars($teacher['subject'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pendidikan Terakhir</label>
                        <input type="text" name="education" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="S1 Pendidikan Matematika" value="<?= htmlspecialchars($teacher['education'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($teacher['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">No HP</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($teacher['phone'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= (int)($teacher['sort_order'] ?? 0) ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Foto</label>
                    <?php if (!empty($teacher['photo'])): ?>
                    <div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($teacher['photo']) ?>" class="w-16 h-16 rounded-full object-cover border-2 border-slate-200 dark:border-slate-600"></div>
                    <?php endif; ?>
                    <input type="file" name="photo" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 image-upload-input" accept="image/*" data-preview="#teacherPhotoPreview">
                    <img id="teacherPhotoPreview" src="" class="hidden w-16 h-16 rounded-full object-cover mt-2">
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($teacher['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 relative"></div>
                        <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Guru Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button>
                <a href="<?= APP_URL ?>/admin/guru" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
