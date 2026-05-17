<?php $adminPageTitle = 'Pengaturan Homepage'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<?php
$sectionsOrder = $settings['homepage_sections_order'] ?? 'slider,stats,programs,extracurriculars,announcement,news,achievements,testimonials,agenda';
$sectionsHidden = array_filter(explode(',', $settings['homepage_sections_hidden'] ?? ''));
$announcementActive = ($settings['homepage_announcement_active'] ?? '0') === '1';
$announcementContent = $settings['homepage_announcement_content'] ?? '';

$allSections = [
    'slider'           => ['label' => 'Hero Slider', 'icon' => 'fas fa-images'],
    'stats'            => ['label' => 'Statistik Sekolah', 'icon' => 'fas fa-chart-bar'],
    'programs'         => ['label' => 'Program Keahlian (Jurusan)', 'icon' => 'fas fa-book'],
    'extracurriculars' => ['label' => 'Ekstrakurikuler', 'icon' => 'fas fa-running'],
    'announcement'     => ['label' => 'Pengumuman / Video', 'icon' => 'fas fa-bullhorn'],
    'news'             => ['label' => 'Berita Terbaru', 'icon' => 'fas fa-newspaper'],
    'achievements'     => ['label' => 'Prestasi', 'icon' => 'fas fa-trophy'],
    'testimonials'     => ['label' => 'Testimoni', 'icon' => 'fas fa-quote-left'],
    'agenda'           => ['label' => 'Agenda / Event', 'icon' => 'fas fa-calendar-alt'],
];
?>

<div class="max-w-4xl mx-auto space-y-6">

    <form method="POST" action="<?= APP_URL ?>/admin/settings/homepage">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <!-- Section: Pengumuman / Video Banner -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6 mb-6">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white mb-1 flex items-center gap-2">
                <i class="fas fa-bullhorn text-blue-600"></i>Pengumuman / Video Homepage
            </h5>
            <p class="text-sm text-slate-400 mb-4">
                Sisipkan pengumuman, himbauan, atau video (YouTube, dll) yang tampil di halaman depan.
                Cocok untuk acara kelulusan, himbauan dinas, pengumuman PPDB, dll. Bisa di-show/hide kapan saja.
            </p>

            <div class="mb-4">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="homepage_announcement_active" value="1" class="sr-only peer" <?= $announcementActive ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600 relative"></div>
                    <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Tampilkan pengumuman di homepage</span>
                </label>
                <small class="block text-xs text-slate-400 mt-1">Matikan saat acara selesai supaya tidak tampil lagi, tanpa perlu hapus kontennya.</small>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Konten Pengumuman</label>
                <textarea name="homepage_announcement_content" data-rich-editor data-editor-height="280" class="w-full" rows="8" placeholder="Tulis pengumuman, sisipkan video YouTube dari toolbar Video di atas..."><?= htmlspecialchars($announcementContent) ?></textarea>
                <small class="text-xs text-slate-400 mt-1 block">
                    <i class="fas fa-video text-blue-500 mr-1"></i>
                    Klik tombol <strong>Video</strong> di toolbar editor untuk menyematkan video YouTube/sosmed. Bisa juga tulis teks, gambar, dll.
                </small>
            </div>
        </div>

        <!-- Section: Atur Tampilan Section Homepage -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6 mb-6">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white mb-1 flex items-center gap-2">
                <i class="fas fa-th-list text-blue-600"></i>Section Homepage
            </h5>
            <p class="text-sm text-slate-400 mb-4">
                Centang section yang ingin ditampilkan di halaman depan. Yang tidak dicentang akan disembunyikan.
            </p>

            <input type="hidden" name="homepage_sections_order" id="sectionsOrderInput" value="<?= htmlspecialchars($sectionsOrder) ?>">

            <div class="space-y-2" id="sectionsList">
                <?php
                $orderedKeys = array_filter(explode(',', $sectionsOrder));
                // Add any missing sections at the end
                foreach (array_keys($allSections) as $k) {
                    if (!in_array($k, $orderedKeys)) $orderedKeys[] = $k;
                }
                foreach ($orderedKeys as $key):
                    if (!isset($allSections[$key])) continue;
                    $sec = $allSections[$key];
                    $isHidden = in_array($key, $sectionsHidden);
                ?>
                <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 hover:border-blue-200 dark:hover:border-blue-800 transition-colors" data-section-key="<?= $key ?>">
                    <i class="fas fa-grip-vertical text-slate-300 dark:text-slate-600 cursor-move"></i>
                    <label class="inline-flex items-center gap-3 flex-1 cursor-pointer">
                        <input type="checkbox" name="homepage_sections_hidden[]" value="<?= $key ?>" <?= $isHidden ? 'checked' : '' ?> class="rounded border-slate-300 text-red-500 focus:ring-red-400">
                        <i class="<?= $sec['icon'] ?> text-blue-600 w-5 text-center"></i>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?= $sec['label'] ?></span>
                    </label>
                    <span class="text-[10px] text-slate-400 uppercase"><?= $isHidden ? 'HIDDEN' : 'VISIBLE' ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <small class="text-xs text-slate-400 mt-3 block">
                <i class="fas fa-eye-slash text-red-400 mr-1"></i>Centang = <strong>sembunyikan</strong> section tersebut dari homepage.
            </small>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan Homepage
            </button>
            <a href="<?= APP_URL ?>/admin/dashboard" class="px-4 py-2.5 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
