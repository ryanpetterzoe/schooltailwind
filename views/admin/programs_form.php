<?php
$isEdit = !empty($program);
$adminPageTitle = $isEdit ? 'Edit Jurusan' : 'Tambah Jurusan';
require_once __DIR__ . '/../layouts/admin_header.php';

/* ------------------------------------------------------------------
 * Curated icon set for SMK programs.
 * Grouped by theme so the picker can show categories. The user can
 * still type a custom Font Awesome class in the input below.
 * ------------------------------------------------------------------ */
$iconLibrary = [
    'Komputer & IT' => [
        'fas fa-laptop-code'      => 'Pemrograman',
        'fas fa-code'             => 'Coding',
        'fas fa-network-wired'    => 'Jaringan',
        'fas fa-server'           => 'Server',
        'fas fa-database'         => 'Database',
        'fas fa-microchip'         => 'Hardware',
        'fas fa-desktop'          => 'Komputer',
        'fas fa-laptop'           => 'Laptop',
        'fas fa-shield-alt'       => 'Keamanan Siber',
        'fas fa-mobile-alt'       => 'Aplikasi Mobile',
        'fas fa-globe'            => 'Internet',
        'fas fa-cloud'            => 'Cloud',
    ],
    'Multimedia & Desain' => [
        'fas fa-palette'          => 'Desain',
        'fas fa-paint-brush'      => 'Kreatif',
        'fas fa-camera'           => 'Fotografi',
        'fas fa-video'            => 'Videografi',
        'fas fa-film'             => 'Film',
        'fas fa-music'            => 'Audio',
        'fas fa-photo-film'       => 'Multimedia',
        'fas fa-pen-nib'          => 'Ilustrasi',
        'fas fa-vector-square'    => 'Desain Grafis',
    ],
    'Teknik & Otomotif' => [
        'fas fa-car'              => 'Otomotif',
        'fas fa-motorcycle'       => 'Sepeda Motor',
        'fas fa-truck'            => 'Kendaraan Berat',
        'fas fa-cogs'             => 'Mesin',
        'fas fa-tools'            => 'Perkakas',
        'fas fa-wrench'           => 'Bengkel',
        'fas fa-bolt'             => 'Listrik',
        'fas fa-plug'             => 'Instalasi Listrik',
        'fas fa-industry'         => 'Industri',
        'fas fa-hard-hat'         => 'Konstruksi',
        'fas fa-fan'              => 'Pendingin',
    ],
    'Bisnis & Manajemen' => [
        'fas fa-briefcase'        => 'Bisnis',
        'fas fa-chart-line'       => 'Manajemen',
        'fas fa-coins'            => 'Akuntansi',
        'fas fa-money-bill-wave'  => 'Keuangan',
        'fas fa-calculator'       => 'Perhitungan',
        'fas fa-handshake'        => 'Marketing',
        'fas fa-store'            => 'Pemasaran',
        'fas fa-cash-register'    => 'Kasir',
        'fas fa-file-invoice'     => 'Administrasi',
    ],
    'Pariwisata & Kuliner' => [
        'fas fa-utensils'         => 'Kuliner',
        'fas fa-mug-hot'          => 'Tata Boga',
        'fas fa-pizza-slice'      => 'Makanan',
        'fas fa-bed'              => 'Perhotelan',
        'fas fa-suitcase-rolling' => 'Pariwisata',
        'fas fa-plane-departure'  => 'Travel',
        'fas fa-cut'              => 'Tata Rias',
    ],
    'Kesehatan & Sains' => [
        'fas fa-heartbeat'        => 'Keperawatan',
        'fas fa-user-nurse'       => 'Perawat',
        'fas fa-pills'            => 'Farmasi',
        'fas fa-flask'            => 'Laboratorium',
        'fas fa-microscope'       => 'Analisis',
        'fas fa-tooth'            => 'Kesehatan Gigi',
    ],
    'Pertanian & Lingkungan' => [
        'fas fa-seedling'         => 'Pertanian',
        'fas fa-tractor'          => 'Agribisnis',
        'fas fa-fish'             => 'Perikanan',
        'fas fa-tree'             => 'Kehutanan',
        'fas fa-leaf'             => 'Hortikultura',
    ],
    'Umum' => [
        'fas fa-book'             => 'Buku',
        'fas fa-graduation-cap'   => 'Pendidikan',
        'fas fa-school'           => 'Sekolah',
        'fas fa-chalkboard-teacher' => 'Pengajaran',
        'fas fa-user-graduate'    => 'Siswa',
    ],
];

$currentIcon = $program['icon'] ?? 'fas fa-book';
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
                    <textarea name="description" data-rich-editor data-editor-simple data-editor-height="220" class="w-full" rows="5" placeholder="Deskripsi program keahlian..."><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
                </div>

                <!-- Icon Picker -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        <i class="fas fa-icons text-blue-600 mr-1"></i>Icon Jurusan
                    </label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-3">Klik salah satu ikon di bawah, atau ketik nama Font Awesome di kotak input bila perlu.</small>

                    <!-- Hidden input that actually submits -->
                    <input type="hidden" name="icon" id="iconInput" value="<?= htmlspecialchars($currentIcon) ?>">

                    <!-- Selected preview + manual override -->
                    <div class="flex items-center gap-3 mb-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-lg flex-shrink-0">
                            <i id="iconPreview" class="<?= htmlspecialchars($currentIcon) ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Icon dipilih:</div>
                            <input type="text" id="iconManual" class="w-full px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="<?= htmlspecialchars($currentIcon) ?>" placeholder="fas fa-laptop-code">
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="relative mb-3">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="iconSearch" placeholder="Cari ikon (cth: jaringan, bisnis, otomotif)..."
                               class="w-full pl-9 pr-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Icon grid grouped by category -->
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        <i class="fas fa-image text-blue-600 mr-1"></i>Gambar Jurusan
                    </label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-2">Tampil sebagai banner di halaman detail jurusan. Rasio 16:9 disarankan.</small>
                    <?php if (!empty($program['image'])): ?>
                    <div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($program['image']) ?>" alt="" class="w-full max-h-44 object-cover rounded-lg border border-slate-200 dark:border-slate-700"></div>
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 image-upload-input" accept="image/*" data-preview="#progImgPreview">
                    <img id="progImgPreview" src="" class="hidden w-full max-h-44 object-cover rounded-lg mt-2 border-2 border-blue-500">
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

<script>
(function () {
    var iconInput   = document.getElementById('iconInput');
    var iconManual  = document.getElementById('iconManual');
    var iconPreview = document.getElementById('iconPreview');
    var iconSearch  = document.getElementById('iconSearch');
    var grid        = document.getElementById('iconGrid');
    var noResult    = document.getElementById('iconNoResult');

    function setIcon(cls) {
        iconInput.value   = cls;
        iconManual.value  = cls;
        iconPreview.className = cls;
        // Highlight selected button
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

    // Click handler
    grid.addEventListener('click', function (e) {
        var btn = e.target.closest('.icon-btn');
        if (!btn) return;
        e.preventDefault();
        setIcon(btn.getAttribute('data-icon'));
    });

    // Manual input -> preview + sync hidden field
    iconManual.addEventListener('input', function () {
        var v = iconManual.value.trim();
        if (!v) return;
        iconInput.value = v;
        iconPreview.className = v;
    });

    // Search filter (case-insensitive substring match on label + class name)
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

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
