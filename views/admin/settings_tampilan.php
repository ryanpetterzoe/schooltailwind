<?php $adminPageTitle = 'Pengaturan Tampilan'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">

        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2 mb-6"><i class="fas fa-palette text-blue-600"></i>Pengaturan Warna & Tampilan</h5>

        <form method="POST" action="<?= APP_URL ?>/admin/settings/tampilan">
            <input type="hidden" name="_token" value="<?= htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '') ?>">

            <!-- Color Preview -->
            <div class="mb-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                    <i class="fas fa-info-circle mr-1"></i>Preview warna akan terlihat setelah disimpan dan halaman direfresh.
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg shadow-md" style="background:<?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?>;"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-300">Warna primer saat ini: <strong><?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?></strong></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Primary Color -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        <i class="fas fa-circle mr-1 text-blue-600"></i>Warna Primer (Accent)
                    </label>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">Warna utama: tombol, link, highlight, badge.</p>
                    <div class="flex items-center gap-2">
                        <input type="color" name="accent_primary" class="w-14 h-10 p-0.5 rounded-lg cursor-pointer border border-slate-200 dark:border-slate-700"
                               value="<?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?>">
                        <input type="text" id="accent_primary_hex" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="<?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?>"
                               placeholder="#2563eb"
                               oninput="syncColorPicker('accent_primary', this.value)">
                    </div>
                </div>

                <!-- Dark variant -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        <i class="fas fa-circle mr-1 text-blue-800"></i>Warna Primer (Gelap)
                    </label>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">Varian gelap untuk hover, gradient, dark mode.</p>
                    <div class="flex items-center gap-2">
                        <input type="color" name="accent_dark" class="w-14 h-10 p-0.5 rounded-lg cursor-pointer border border-slate-200 dark:border-slate-700"
                               value="<?= htmlspecialchars($settings['accent_dark'] ?? '#1d4ed8') ?>">
                        <input type="text" id="accent_dark_hex" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="<?= htmlspecialchars($settings['accent_dark'] ?? '#1d4ed8') ?>"
                               placeholder="#1d4ed8"
                               oninput="syncColorPicker('accent_dark', this.value)">
                    </div>
                </div>
            </div>

            <!-- Preset Colors -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Preset Warna Populer</label>
                <div class="flex flex-wrap gap-2 mt-2">
                    <?php
                    $presets = [
                        ['name'=>'Biru (Default)','primary'=>'#2563eb','dark'=>'#1d4ed8'],
                        ['name'=>'Ungu','primary'=>'#7c3aed','dark'=>'#6d28d9'],
                        ['name'=>'Merah','primary'=>'#dc2626','dark'=>'#b91c1c'],
                        ['name'=>'Hijau','primary'=>'#16a34a','dark'=>'#15803d'],
                        ['name'=>'Oranye','primary'=>'#ea580c','dark'=>'#c2410c'],
                        ['name'=>'Teal','primary'=>'#0d9488','dark'=>'#0f766e'],
                        ['name'=>'Pink','primary'=>'#db2777','dark'=>'#be185d'],
                        ['name'=>'Hitam','primary'=>'#1e293b','dark'=>'#0f172a'],
                    ];
                    foreach ($presets as $p): ?>
                    <button type="button" class="preset-btn w-9 h-9 rounded-lg border-[3px] cursor-pointer transition-all hover:scale-110 <?= (($settings['accent_primary'] ?? '#2563eb') === $p['primary']) ? 'border-slate-300 dark:border-slate-500 scale-110' : 'border-transparent' ?>"
                            data-primary="<?= $p['primary'] ?>"
                            data-dark="<?= $p['dark'] ?>"
                            title="<?= $p['name'] ?>"
                            onclick="applyPreset(this)"
                            style="background:<?= $p['primary'] ?>;">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Default Theme -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tema Default Website</label>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-3">Tema yang digunakan pengunjung baru (sebelum toggle manual).</p>
                <div class="flex flex-wrap gap-3">
                    <label class="flex items-center gap-2 cursor-pointer px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 has-[:checked]:border-blue-500">
                        <input type="radio" name="theme_default" value="light" class="text-blue-600 focus:ring-blue-500" <?= (($settings['theme_default'] ?? 'light') === 'light') ? 'checked' : '' ?>>
                        <i class="fas fa-sun text-amber-400"></i>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Light Mode</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 has-[:checked]:border-blue-500">
                        <input type="radio" name="theme_default" value="dark" class="text-blue-600 focus:ring-blue-500" <?= (($settings['theme_default'] ?? 'light') === 'dark') ? 'checked' : '' ?>>
                        <i class="fas fa-moon text-indigo-500"></i>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Dark Mode</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Simpan Tampilan
                </button>
                <button type="button" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" onclick="resetToDefault()">
                    <i class="fas fa-undo mr-1"></i> Reset ke Default
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function syncColorPicker(name, hexVal) {
    var picker = document.querySelector('input[name="' + name + '"][type="color"]');
    var text = document.getElementById(name + '_hex');
    if (/^#[0-9a-fA-F]{6}$/.test(hexVal)) { if (picker) picker.value = hexVal; }
    if (picker) { picker.addEventListener('input', function() { if (text) text.value = this.value; }); }
}
document.querySelectorAll('input[type="color"]').forEach(function(picker) {
    var name = picker.name;
    var text = document.getElementById(name + '_hex');
    picker.addEventListener('input', function() { if (text) text.value = this.value; });
});
function applyPreset(btn) {
    var primary = btn.getAttribute('data-primary'), dark = btn.getAttribute('data-dark');
    var p1 = document.querySelector('input[name="accent_primary"][type="color"]');
    var p2 = document.querySelector('input[name="accent_dark"][type="color"]');
    var t1 = document.getElementById('accent_primary_hex');
    var t2 = document.getElementById('accent_dark_hex');
    if (p1) p1.value = primary; if (p2) p2.value = dark;
    if (t1) t1.value = primary; if (t2) t2.value = dark;
    document.querySelectorAll('.preset-btn').forEach(function(b) { b.style.borderColor = 'transparent'; b.style.transform = 'scale(1)'; });
    btn.style.borderColor = '#94a3b8'; btn.style.transform = 'scale(1.15)';
}
function resetToDefault() {
    var p1 = document.querySelector('input[name="accent_primary"][type="color"]');
    var p2 = document.querySelector('input[name="accent_dark"][type="color"]');
    var t1 = document.getElementById('accent_primary_hex');
    var t2 = document.getElementById('accent_dark_hex');
    if (p1) p1.value = '#2563eb'; if (p2) p2.value = '#1d4ed8';
    if (t1) t1.value = '#2563eb'; if (t2) t2.value = '#1d4ed8';
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
