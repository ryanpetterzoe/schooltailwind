<?php
$isEdit = !empty($ekskul);
$adminPageTitle = $isEdit ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler';
require_once __DIR__ . '/../layouts/admin_header.php';

/* ------------------------------------------------------------------
 * Curated icon set untuk ekstrakurikuler sekolah.
 * Dikelompokkan per kategori biar mudah dicari. Admin tetap bisa
 * mengetik nama Font Awesome class manual lewat input di bawah.
 * ------------------------------------------------------------------ */
$iconLibrary = [
    'Olahraga' => [
        'fas fa-futbol'           => 'Sepak Bola',
        'fas fa-basketball-ball'  => 'Basket',
        'fas fa-volleyball-ball'  => 'Voli',
        'fas fa-table-tennis'     => 'Tenis Meja',
        'fas fa-running'          => 'Atletik',
        'fas fa-swimmer'          => 'Renang',
        'fas fa-dumbbell'         => 'Fitness',
        'fas fa-baseball-ball'    => 'Softball',
        'fas fa-chess'            => 'Catur',
    ],
    'Seni & Budaya' => [
        'fas fa-music'            => 'Musik',
        'fas fa-guitar'           => 'Gitar',
        'fas fa-drum'             => 'Drum/Perkusi',
        'fas fa-theater-masks'    => 'Teater',
        'fas fa-palette'          => 'Seni Rupa',
        'fas fa-paint-brush'      => 'Melukis',
        'fas fa-pen-nib'          => 'Kaligrafi',
        'fas fa-camera'           => 'Fotografi',
        'fas fa-film'             => 'Sinematografi',
    ],
    'Kepanduan & Karakter' => [
        'fas fa-campground'       => 'Pramuka',
        'fas fa-flag'             => 'Paskibra',
        'fas fa-shield-alt'       => 'PMR',
        'fas fa-hands-helping'    => 'Bakti Sosial',
        'fas fa-praying-hands'    => 'Rohani',
        'fas fa-users'            => 'OSIS',
    ],
    'Sains & Teknologi' => [
        'fas fa-robot'            => 'Robotika',
        'fas fa-laptop-code'      => 'Programming',
        'fas fa-microscope'       => 'KIR/Sains',
        'fas fa-flask'            => 'Kimia',
        'fas fa-atom'             => 'Fisika',
        'fas fa-satellite-dish'   => 'Astronomi',
        'fas fa-microchip'        => 'Elektronika',
    ],
    'Bahasa & Literasi' => [
        'fas fa-book-reader'      => 'Literasi',
        'fas fa-language'         => 'Bahasa Asing',
        'fas fa-newspaper'        => 'Jurnalistik',
        'fas fa-microphone'       => 'Public Speaking',
        'fas fa-comments'         => 'Debat',
        'fas fa-pen-fancy'        => 'Menulis Kreatif',
    ],
    'Umum' => [
        'fas fa-star'             => 'Bintang',
        'fas fa-trophy'           => 'Piala',
        'fas fa-medal'            => 'Medali',
        'fas fa-certificate'      => 'Sertifikat',
        'fas fa-graduation-cap'   => 'Pendidikan',
        'fas fa-heart'            => 'Sosial',
    ],
];

$currentIcon = $ekskul['icon'] ?? 'fas fa-futbol';
?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/ekskul/<?= $isEdit ? 'edit/'.$ekskul['id'] : 'tambah' ?>" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Ekstrakurikuler <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required value="<?= htmlspecialchars($ekskul['name'] ?? '') ?>" placeholder="cth: Pramuka, Basket, Robotika, PMR">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" data-rich-editor data-editor-height="220" class="w-full" rows="5" placeholder="Deskripsi ekstrakurikuler — jadwal, prestasi, kegiatan, dll..."><?= htmlspecialchars($ekskul['description'] ?? '') ?></textarea>
                </div>


                <!-- Icon Picker -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        <i class="fas fa-icons text-blue-600 mr-1"></i>Icon Ekstrakurikuler
                    </label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-3">Klik salah satu ikon di bawah, atau ketik nama Font Awesome di kotak input bila perlu.</small>

                    <input type="hidden" name="icon" id="iconInput" value="<?= htmlspecialchars($currentIcon) ?>">

                    <div class="flex items-center gap-3 mb-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-lg flex-shrink-0">
                            <i id="iconPreview" class="<?= htmlspecialchars($currentIcon) ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Icon dipilih:</div>
                            <input type="text" id="iconManual" class="w-full px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?= htmlspecialchars($currentIcon) ?>" placeholder="fas fa-futbol">
                        </div>
                    </div>

                    <div class="relative mb-3">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="iconSearch" placeholder="Cari ikon (cth: basket, musik, pramuka, robotika)..."
                               class="w-full pl-9 pr-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 max-h-80 overflow-y-auto" id="iconGrid">
                        <?php foreach ($iconLibrary as $category => $icons): ?>
                        <div class="icon-group" data-category="<?= htmlspecialchars(strtolower($category)) ?>">
                            <div class="sticky top-0 px-3 py-2 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide z-10"><?= htmlspecialchars($category) ?></div>
                            <div class="grid grid-cols-6 sm:grid-cols-8 gap-1 p-2">
                                <?php foreach ($icons as $cls => $label): ?>
                                <button type="button"
                                        class="icon-btn group relative flex items-center justify-center aspect-square rounded-lg border-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-700 transition-all <?= $cls === $currentIcon ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 ring-2 ring-blue-500/30' : 'border-transparent' ?>"
                                        data-icon="<?= htmlspecialchars($cls) ?>"
                                        data-label="<?= htmlspecialchars(strtolower($label . ' ' . $cls)) ?>"
                                        title="<?= htmlspecialchars($label) ?>">
                                    <i class="<?= htmlspecialchars($cls) ?> text-lg text-slate-600 dark:text-slate-300 group-hover:text-blue-600"></i>
                                    <span class="absolute -top-7 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-slate-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap z-20"><?= htmlspecialchars($label) ?></span>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <div id="iconNoResult" class="hidden p-8 text-center text-sm text-slate-400">
                            <i class="fas fa-search-minus text-2xl mb-2 block"></i>
                            Tidak ada ikon yang cocok. Coba kata kunci lain atau ketik manual di kotak input.
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="w-full sm:w-48 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= (int)($ekskul['sort_order'] ?? 0) ?>">
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mt-1">Angka kecil tampil lebih dulu di halaman publik.</small>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        <i class="fas fa-image text-blue-600 mr-1"></i>Gambar Cover (opsional)
                    </label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-2">Gambar utama yang tampil di card ekstrakurikuler. Kalau tidak diisi, kartu hanya menampilkan ikon.</small>
                    <?php if (!empty($ekskul['image'])): ?>
                    <div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($ekskul['image']) ?>" alt="" class="w-full max-h-44 object-cover rounded-lg border border-slate-200 dark:border-slate-700"></div>
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 image-upload-input" accept="image/*" data-preview="#ekskulImgPreview">
                    <img id="ekskulImgPreview" src="" class="hidden w-full max-h-44 object-cover rounded-lg mt-2 border-2 border-blue-500">
                </div>


                <!-- ============================================================
                     GALERI FOTO KEGIATAN EKSKUL (multi-upload, sama dengan fasilitas)
                     ============================================================ -->
                <div class="border-t border-slate-100 dark:border-slate-700 pt-4 mt-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                        <i class="fas fa-images text-blue-600 mr-1"></i>Galeri Foto Kegiatan Ekskul (opsional)
                    </label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-3">
                        Upload beberapa foto kegiatan ekskul sekaligus. Bila ada lebih dari satu foto, akan tampil sebagai slideshow di kartu publik. Otomatis dikompres saat upload.
                    </small>

                    <input type="hidden" name="delete_image_ids" id="deleteImageIds" value="">

                    <?php if (!empty($ekskulImages)): ?>
                    <div class="mb-4">
                        <div class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">
                            <i class="fas fa-folder-open text-blue-500 mr-1"></i>Foto saat ini (<?= count($ekskulImages) ?>) — seret untuk ubah urutan
                        </div>
                        <div id="existingGallery" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            <?php foreach ($ekskulImages as $idx => $fi): ?>
                            <div class="existing-image-card relative group bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden cursor-move"
                                 draggable="true"
                                 data-image-id="<?= (int)$fi['id'] ?>">
                                <input type="hidden" name="existing_image_id[]" value="<?= (int)$fi['id'] ?>">
                                <input type="hidden" name="existing_image_sort[]" class="sort-input" value="<?= (int)$fi['sort_order'] ?>">
                                <div class="aspect-video overflow-hidden bg-slate-200 dark:bg-slate-700">
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($fi['image']) ?>" alt="<?= htmlspecialchars($fi['caption'] ?? '') ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="p-2">
                                    <input type="text"
                                           name="existing_image_caption[]"
                                           value="<?= htmlspecialchars($fi['caption'] ?? '') ?>"
                                           placeholder="Caption (opsional)"
                                           class="w-full px-2 py-1 text-xs border border-slate-200 dark:border-slate-700 rounded bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <button type="button"
                                        class="delete-image-btn absolute top-1 right-1 w-7 h-7 bg-red-600/90 hover:bg-red-700 text-white text-xs rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                        title="Hapus foto ini"
                                        data-image-id="<?= (int)$fi['id'] ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                                <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-slate-900/70 text-white text-[10px] rounded order-badge"><?= $idx + 1 ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                        <label class="block cursor-pointer">
                            <input type="file" name="gallery_images[]" id="galleryUploader" accept="image/*" multiple class="hidden">
                            <div class="text-center py-4">
                                <i class="fas fa-cloud-upload-alt text-3xl text-blue-500 mb-2"></i>
                                <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Klik untuk pilih foto (bisa lebih dari satu)</div>
                                <div class="text-xs text-slate-400 mt-1">JPG / PNG / WebP — maks 1920px (otomatis dikompres)</div>
                            </div>
                        </label>
                        <div id="newImagePreviews" class="hidden mt-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3"></div>
                    </div>
                </div>
                <!-- ============================================================ -->

                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($ekskul['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 relative"></div>
                        <span class="ml-3 text-sm text-slate-700 dark:text-slate-300">Tampilkan di halaman publik</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button>
                <a href="<?= APP_URL ?>/admin/ekskul" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>


<!-- Icon picker JS -->
<script>
(function () {
    var iconInput   = document.getElementById('iconInput');
    var iconManual  = document.getElementById('iconManual');
    var iconPreview = document.getElementById('iconPreview');
    var iconSearch  = document.getElementById('iconSearch');
    var grid        = document.getElementById('iconGrid');
    var noResult    = document.getElementById('iconNoResult');
    if (!grid) return;

    function setIcon(cls) {
        iconInput.value   = cls;
        iconManual.value  = cls;
        iconPreview.className = cls;
        grid.querySelectorAll('.icon-btn').forEach(function (b) {
            var on = b.getAttribute('data-icon') === cls;
            b.classList.toggle('border-blue-500',  on);
            b.classList.toggle('bg-blue-50',       on);
            b.classList.toggle('dark:bg-blue-900/30', on);
            b.classList.toggle('ring-2',           on);
            b.classList.toggle('ring-blue-500/30', on);
            b.classList.toggle('border-transparent', !on);
        });
    }

    grid.addEventListener('click', function (e) {
        var btn = e.target.closest('.icon-btn');
        if (!btn) return;
        e.preventDefault();
        setIcon(btn.getAttribute('data-icon'));
    });

    iconManual.addEventListener('input', function () {
        var v = iconManual.value.trim();
        if (!v) return;
        iconInput.value = v;
        iconPreview.className = v;
    });

    iconSearch.addEventListener('input', function () {
        var q = iconSearch.value.toLowerCase().trim();
        var anyVisible = false;
        grid.querySelectorAll('.icon-group').forEach(function (group) {
            var groupHasMatch = false;
            group.querySelectorAll('.icon-btn').forEach(function (b) {
                var hit = !q || b.getAttribute('data-label').indexOf(q) !== -1;
                b.style.display = hit ? '' : 'none';
                if (hit) { groupHasMatch = true; anyVisible = true; }
            });
            group.style.display = groupHasMatch ? '' : 'none';
        });
        noResult.classList.toggle('hidden', anyVisible);
    });
})();
</script>

<!-- Gallery management JS -->
<script>
(function () {
    var gallery = document.getElementById('existingGallery');
    var deletedField = document.getElementById('deleteImageIds');

    if (gallery) {
        gallery.addEventListener('click', function (e) {
            var btn = e.target.closest('.delete-image-btn');
            if (!btn) return;
            e.preventDefault();
            var card = btn.closest('.existing-image-card');
            var id = btn.getAttribute('data-image-id');
            if (!confirm('Hapus foto ini? Akan dihapus permanen saat form disimpan.')) return;
            var current = deletedField.value ? deletedField.value.split(',') : [];
            if (current.indexOf(id) === -1) current.push(id);
            deletedField.value = current.join(',');
            card.remove();
            renumberOrderBadges();
        });

        var dragSrc = null;
        gallery.addEventListener('dragstart', function (e) {
            var card = e.target.closest('.existing-image-card');
            if (!card) return;
            dragSrc = card;
            card.style.opacity = '0.4';
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', card.dataset.imageId || '');
        });
        gallery.addEventListener('dragend', function (e) {
            var card = e.target.closest('.existing-image-card');
            if (card) card.style.opacity = '';
            dragSrc = null;
        });
        gallery.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        gallery.addEventListener('drop', function (e) {
            e.preventDefault();
            if (!dragSrc) return;
            var target = e.target.closest('.existing-image-card');
            if (!target || target === dragSrc) return;
            var rect = target.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            if (after) target.parentNode.insertBefore(dragSrc, target.nextSibling);
            else       target.parentNode.insertBefore(dragSrc, target);
            renumberOrderBadges();
        });
    }

    function renumberOrderBadges() {
        if (!gallery) return;
        var cards = gallery.querySelectorAll('.existing-image-card');
        cards.forEach(function (c, i) {
            var badge = c.querySelector('.order-badge');
            if (badge) badge.textContent = (i + 1);
            var sortInp = c.querySelector('.sort-input');
            if (sortInp) sortInp.value = (i + 1);
        });
    }

    var fileInput = document.getElementById('galleryUploader');
    var previews  = document.getElementById('newImagePreviews');
    if (fileInput && previews) {
        fileInput.addEventListener('change', function () {
            previews.innerHTML = '';
            if (!fileInput.files || !fileInput.files.length) {
                previews.classList.add('hidden');
                return;
            }
            previews.classList.remove('hidden');
            Array.prototype.forEach.call(fileInput.files, function (file, idx) {
                var card = document.createElement('div');
                card.className = 'bg-white dark:bg-slate-800 border border-blue-300 dark:border-blue-700 rounded-lg overflow-hidden';
                card.innerHTML = ''
                    + '<div class="aspect-video overflow-hidden bg-slate-100 dark:bg-slate-700">'
                    +   '<img class="w-full h-full object-cover" alt="">'
                    + '</div>'
                    + '<div class="p-2">'
                    +   '<input type="text" name="new_image_caption[]" placeholder="Caption (opsional)" class="w-full px-2 py-1 text-xs border border-slate-200 dark:border-slate-700 rounded bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500">'
                    + '</div>';
                var img = card.querySelector('img');
                var reader = new FileReader();
                reader.onload = function (e) { img.src = e.target.result; };
                reader.readAsDataURL(file);
                previews.appendChild(card);
            });
        });
    }

    /* Cover image preview */
    var bannerInput = document.querySelector('input[name="image"]');
    var bannerPreview = document.getElementById('ekskulImgPreview');
    if (bannerInput && bannerPreview) {
        bannerInput.addEventListener('change', function () {
            if (bannerInput.files && bannerInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    bannerPreview.src = e.target.result;
                    bannerPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(bannerInput.files[0]);
            }
        });
    }
})();
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
