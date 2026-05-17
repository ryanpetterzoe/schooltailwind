<?php $adminPageTitle = 'Custom Menu Navbar'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Info -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 text-sm text-blue-700 dark:text-blue-300">
        <i class="fas fa-info-circle mr-1"></i>
        Menu custom yang aktif akan muncul di <strong>navbar publik</strong> (setelah menu bawaan). Anda bisa menambah link ke halaman lain, sosial media, atau dokumen. Toggle on/off untuk menampilkan/menyembunyikan tanpa menghapus.
    </div>

    <!-- Add new form -->
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-plus-circle text-blue-600"></i>Tambah Menu Baru
        </h5>
        <form method="POST" action="<?= APP_URL ?>/admin/custom-menu/simpan" class="grid sm:grid-cols-2 gap-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Label <span class="text-red-500">*</span></label>
                <input type="text" name="label" required placeholder="cth: E-Learning, Alumni" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">URL <span class="text-red-500">*</span></label>
                <input type="text" name="url" required placeholder="https://... atau /halaman" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Icon (opsional)</label>
                <input type="text" name="icon" placeholder="fas fa-link" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Urutan</label>
                <input type="number" name="sort_order" value="0" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-4 sm:col-span-2">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="open_new_tab" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Buka di tab baru
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Aktif
                </label>
                <button type="submit" class="ml-auto px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
            </div>
        </form>
    </div>

    <!-- Existing menus list -->
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-bars text-blue-600"></i>Daftar Menu Custom (<?= count($menus) ?>)
            </h5>
        </div>
        <?php if (empty($menus)): ?>
        <div class="p-8 text-center text-slate-400 dark:text-slate-500">
            <i class="fas fa-link text-3xl mb-2 block"></i>
            Belum ada menu custom. Tambah di atas untuk mulai.
        </div>
        <?php else: ?>
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
            <?php foreach ($menus as $menu): ?>
            <div class="flex items-center gap-4 px-4 sm:px-6 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="<?= htmlspecialchars($menu['icon'] ?: 'fas fa-link') ?> text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-slate-800 dark:text-white text-sm truncate"><?= htmlspecialchars($menu['label']) ?></div>
                    <div class="text-xs text-slate-400 truncate"><?= htmlspecialchars($menu['url']) ?><?= $menu['open_new_tab'] ? ' <i class="fas fa-external-link-alt"></i>' : '' ?></div>
                </div>
                <span class="text-xs text-slate-400 flex-shrink-0">#<?= (int)$menu['sort_order'] ?></span>
                <span class="px-2 py-0.5 text-xs font-bold rounded-full flex-shrink-0 <?= $menu['is_active'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-600 text-slate-500' ?>">
                    <?= $menu['is_active'] ? 'ON' : 'OFF' ?>
                </span>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <a href="<?= APP_URL ?>/admin/custom-menu/toggle/<?= $menu['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors" title="Toggle On/Off">
                        <i class="fas fa-toggle-<?= $menu['is_active'] ? 'on' : 'off' ?> text-xs"></i>
                    </a>
                    <a href="<?= APP_URL ?>/admin/custom-menu/hapus/<?= $menu['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus menu '<?= htmlspecialchars($menu['label']) ?>'?" title="Hapus">
                        <i class="fas fa-trash text-xs"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
