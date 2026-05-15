<?php
$isEdit = !empty($news);
$adminPageTitle = $isEdit ? 'Edit Berita' : 'Tambah Berita';
$csrfToken = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
$_db = getDB();
$_progRes = $_db->query("SELECT id, name, code FROM programs WHERE is_active=1 ORDER BY sort_order");
$_programs = $_progRes ? $_progRes->fetch_all(MYSQLI_ASSOC) : [];
require_once __DIR__ . '/../layouts/admin_header.php';
?>

<form method="POST"
      action="<?= APP_URL ?>/admin/berita/<?= $isEdit ? 'edit/'.$news['id'] : 'tambah' ?>"
      enctype="multipart/form-data">
<input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- KOLOM KIRI: Konten -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Judul & Slug -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="titleField" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required
                       placeholder="Judul berita yang menarik"
                       value="<?= htmlspecialchars($news['title'] ?? '') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Slug (URL)</label>
                <input type="text" name="slug" id="slugField" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="judul-berita-url"
                       value="<?= htmlspecialchars($news['slug'] ?? '') ?>">
                <small class="text-slate-400 dark:text-slate-500 text-xs mt-1 block">
                    Preview: <?= APP_URL ?>/berita/<span id="slugPreview"><?= htmlspecialchars($news['slug'] ?? '') ?></span>
                </small>
            </div>
        </div>

        <!-- Excerpt -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Ringkasan (Excerpt)</label>
            <textarea name="excerpt" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"
                      placeholder="Ringkasan singkat untuk ditampilkan di daftar berita..."><?= htmlspecialchars($news['excerpt'] ?? '') ?></textarea>
            <small class="text-slate-400 dark:text-slate-500 text-xs mt-1 block">Maks. 200 karakter. Jika kosong, akan diambil dari awal konten.</small>
        </div>

        <!-- Konten -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Konten Berita <span class="text-red-500">*</span></label>

            <!-- Mini toolbar -->
            <div class="mb-2 flex gap-1 flex-wrap">
                <?php
                $btns = [
                    ['label'=>'<b>B</b>','open'=>'<strong>','close'=>'</strong>'],
                    ['label'=>'<i>I</i>','open'=>'<em>','close'=>'</em>'],
                    ['label'=>'P','open'=>'<p>','close'=>'</p>'],
                    ['label'=>'H2','open'=>'<h2>','close'=>'</h2>'],
                    ['label'=>'H3','open'=>'<h3>','close'=>'</h3>'],
                    ['label'=>'<i class="fas fa-list-ul"></i>','open'=>'<ul>\n<li>','close'=>'</li>\n</ul>'],
                    ['label'=>'<i class="fas fa-link"></i>','open'=>'<a href="">','close'=>'</a>'],
                    ['label'=>'IMG','open'=>'<img src="" alt="" style="max-width:100%;">','close'=>''],
                ];
                foreach ($btns as $b):
                ?>
                <button type="button" class="px-2 py-1 border border-slate-200 dark:border-slate-600 rounded-lg text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        onclick="insertTag('<?= addslashes($b['open']) ?>','<?= addslashes($b['close']) ?>')">
                    <?= $b['label'] ?>
                </button>
                <?php endforeach; ?>
            </div>

            <textarea name="content" id="newsContent" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono" rows="18" required
                      placeholder="Tulis konten berita lengkap di sini..."><?= htmlspecialchars($news['content'] ?? '') ?></textarea>
            <small class="text-slate-400 dark:text-slate-500 text-xs mt-1 block">Mendukung HTML dasar. Gunakan tombol di atas untuk menyisipkan tag.</small>
        </div>
    </div>

    <!-- KOLOM KANAN: Sidebar -->
    <div class="space-y-6">

        <!-- Aksi -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-paper-plane text-blue-600"></i>Publikasi</h5>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" class="sr-only peer" <?= (!$isEdit || !empty($news['is_published'])) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Tampilkan ke publik</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Penulis</label>
                <input type="text" name="author" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="<?= htmlspecialchars($news['author'] ?? (isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin')) ?>">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kategori</label>
                <input type="text" name="category" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" list="categoryList"
                       placeholder="Pilih atau ketik kategori"
                       value="<?= htmlspecialchars($news['category'] ?? 'Berita') ?>">
                <datalist id="categoryList">
                    <option value="Berita">
                    <option value="Pengumuman">
                    <option value="Prestasi">
                    <option value="Kegiatan">
                    <option value="Info">
                </datalist>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    <i class="fas fa-book mr-1 text-blue-600"></i>Jurusan
                </label>
                <select name="program_id" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Umum (semua jurusan) —</option>
                    <?php foreach ($_programs as $prog): ?>
                    <option value="<?= $prog['id'] ?>"
                        <?= ((int)($news['program_id'] ?? 0) === (int)$prog['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prog['name']) ?>
                        <?php if ($prog['code']): ?>(<?= htmlspecialchars($prog['code']) ?>)<?php endif; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-slate-400 dark:text-slate-500 text-xs mt-1 block">
                    Pilih jurusan jika berita khusus untuk jurusan tertentu. Kosong = berita umum.
                </small>
            </div>

            <div class="flex flex-col gap-2 mt-6">
                <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i><?= $isEdit ? 'Update Berita' : 'Simpan & Publikasi' ?>
                </button>
                <a href="<?= APP_URL ?>/admin/berita" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-center">
                    <i class="fas fa-times mr-1"></i>Batal
                </a>
            </div>
        </div>

        <!-- Thumbnail -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><i class="fas fa-image text-blue-600"></i>Thumbnail Berita</h5>

            <?php if (!empty($news['image'])): ?>
            <div class="mb-3" id="existingImageWrap">
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1.5">Gambar saat ini:</p>
                <img src="<?= UPLOAD_URL . htmlspecialchars($news['image']) ?>" alt="Thumbnail" class="w-full rounded-lg border border-slate-200 dark:border-slate-700 object-cover max-h-44">
            </div>
            <?php endif; ?>

            <div id="newImagePreviewWrap" class="hidden mb-3">
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1.5">Preview gambar baru:</p>
                <img id="newImagePreview" src="" alt="Preview" class="w-full rounded-lg border-2 border-blue-500 object-cover max-h-44">
            </div>

            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                <?= !empty($news['image']) ? 'Ganti Gambar' : 'Upload Thumbnail' ?>
            </label>
            <input type="file" name="image" id="imageInput" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   accept="image/jpeg,image/png,image/webp,image/gif"
                   onchange="previewThumbnail(this)">
            <small class="text-slate-400 dark:text-slate-500 text-xs mt-1 block">
                Format: JPG, PNG, WebP. Rasio 16:9 (1280×720px). Maks. 2MB.
            </small>

            <?php if (!empty($news['image'])): ?>
            <div class="mt-2">
                <small class="text-slate-400 dark:text-slate-500 text-xs">
                    <i class="fas fa-info-circle mr-1"></i>Kosongkan jika tidak ingin mengganti gambar.
                </small>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tips -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl p-6">
            <h6 class="text-blue-700 dark:text-blue-300 font-semibold mb-2"><i class="fas fa-lightbulb mr-1"></i>Tips</h6>
            <ul class="text-slate-600 dark:text-slate-400 text-xs space-y-1 list-disc pl-4">
                <li>Thumbnail muncul di halaman daftar berita &amp; beranda</li>
                <li>Gunakan gambar horizontal (landscape) agar tampil optimal</li>
                <li>Isi <strong>Ringkasan</strong> agar tampil di kartu berita</li>
                <li>Slug otomatis dari judul, bisa diedit manual</li>
            </ul>
        </div>
    </div>

</div>
</form>

<script>
var slugManuallyEdited = false;
document.getElementById('titleField').addEventListener('input', function () {
    if (slugManuallyEdited) return;
    var slug = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-');
    document.getElementById('slugField').value = slug;
    document.getElementById('slugPreview').textContent = slug;
});
document.getElementById('slugField').addEventListener('input', function () {
    slugManuallyEdited = true;
    document.getElementById('slugPreview').textContent = this.value;
});

function previewThumbnail(input) {
    var wrap = document.getElementById('newImagePreviewWrap');
    var preview = document.getElementById('newImagePreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { preview.src = e.target.result; wrap.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    } else { wrap.classList.add('hidden'); preview.src = ''; }
}

function insertTag(open, close) {
    var ta = document.getElementById('newsContent');
    var start = ta.selectionStart, end = ta.selectionEnd;
    var sel = ta.value.substring(start, end);
    ta.value = ta.value.substring(0, start) + open + sel + close + ta.value.substring(end);
    ta.selectionStart = start + open.length;
    ta.selectionEnd = start + open.length + sel.length;
    ta.focus();
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
