<?php $adminPageTitle = 'Pengaturan Umum'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<!-- Tabs -->
<div class="mb-6 border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
    <nav class="flex gap-1 -mb-px" id="settingsTabs">
        <button class="tab-btn active px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-blue-600 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20" data-target="generalTab"><i class="fas fa-school mr-1"></i>Umum</button>
        <button class="tab-btn px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" data-target="aboutTab"><i class="fas fa-info-circle mr-1"></i>Tentang</button>
        <button class="tab-btn px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" data-target="statsTab"><i class="fas fa-chart-bar mr-1"></i>Statistik</button>
        <button class="tab-btn px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" data-target="seoTab"><i class="fas fa-search mr-1"></i>SEO</button>
        <button class="tab-btn px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" data-target="socialTab"><i class="fas fa-share-alt mr-1"></i>Sosial Media</button>
        <button class="tab-btn px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" data-target="footerTab"><i class="fas fa-copyright mr-1"></i>Footer</button>
    </nav>
</div>

<!-- General Tab -->
<div class="tab-panel" id="generalTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/settings/umum" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="group" value="general">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Sekolah</label>
                        <input type="text" name="school_name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['school_name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tagline</label>
                        <input type="text" name="school_tagline" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['school_tagline'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">NPSN</label>
                        <input type="text" name="school_npsn" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['school_npsn'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Akreditasi</label>
                        <select name="school_accreditation" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php foreach (['A','B','C','Belum Terakreditasi'] as $acc): ?>
                            <option <?= ($settings['school_accreditation'] ?? '') === $acc ? 'selected' : '' ?>><?= $acc ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Alamat Sekolah</label>
                    <textarea name="school_address" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"><?= htmlspecialchars($settings['school_address'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Telepon</label>
                        <input type="text" name="school_phone" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['school_phone'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                        <input type="email" name="school_email" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['school_email'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">WhatsApp</label>
                        <input type="text" name="whatsapp_number" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="62812xxxxxxx" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Logo Sekolah</label>
                        <?php if (!empty($settings['school_logo'])): ?>
                        <div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($settings['school_logo']) ?>" alt="Logo" class="h-12 rounded-lg border border-slate-200 dark:border-slate-700 p-1 bg-white dark:bg-slate-900"></div>
                        <?php endif; ?>
                        <input type="file" name="school_logo_file" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 image-upload-input" accept="image/*" data-preview="#logoPreview">
                        <img id="logoPreview" src="" class="hidden h-12 mt-2 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5"><i class="fas fa-building mr-1 text-blue-600"></i>Foto Gedung Sekolah</label>
                        <small class="text-xs text-slate-400 dark:text-slate-500 block mb-1.5">Ditampilkan di halaman Tentang & Beranda. Rasio 16:9.</small>
                        <?php if (!empty($settings['school_building_photo'])): ?>
                        <div class="mb-2 relative"><img src="<?= UPLOAD_URL . htmlspecialchars($settings['school_building_photo']) ?>" alt="Foto Gedung" class="w-full max-h-36 object-cover rounded-lg border border-slate-200 dark:border-slate-700"></div>
                        <?php endif; ?>
                        <input type="file" name="school_building_photo_file" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" onchange="previewBuilding(this)">
                        <img id="buildingPreview" src="" class="hidden w-full max-h-36 object-cover rounded-lg mt-2 border-2 border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Google Maps Embed URL</label>
                    <input type="text" name="maps_embed" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://maps.google.com/maps?..." value="<?= htmlspecialchars($settings['maps_embed'] ?? '') ?>">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>


<!-- About Tab -->
<div class="tab-panel hidden" id="aboutTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/settings/umum" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="group" value="about">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Visi Sekolah</label>
                    <textarea name="about_vision" data-rich-editor data-editor-height="180" class="w-full" rows="3"><?= htmlspecialchars($settings['about_vision'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Misi Sekolah</label>
                    <textarea name="about_mission" data-rich-editor data-editor-height="220" class="w-full" rows="6"><?= htmlspecialchars($settings['about_mission'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Sejarah Sekolah</label>
                    <textarea name="about_history" data-rich-editor data-editor-height="280" class="w-full" rows="6"><?= htmlspecialchars($settings['about_history'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Kepala Sekolah</label>
                        <input type="text" name="principal_name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['principal_name'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Foto Kepala Sekolah</label>
                        <?php if (!empty($settings['principal_photo'])): ?>
                        <div class="mb-2"><img src="<?= UPLOAD_URL . htmlspecialchars($settings['principal_photo']) ?>" alt="" class="w-14 h-14 rounded-full object-cover border-2 border-slate-200 dark:border-slate-600"></div>
                        <?php endif; ?>
                        <input type="file" name="principal_photo_file" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Sambutan Kepala Sekolah</label>
                    <textarea name="principal_message" data-rich-editor data-editor-height="220" class="w-full" rows="5"><?= htmlspecialchars($settings['principal_message'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-6"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</div>

<!-- Stats Tab -->
<div class="tab-panel hidden" id="statsTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/settings/umum">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="group" value="stats">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Siswa</label><input type="number" name="stats_students" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['stats_students'] ?? '0') ?>"></div>
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Guru & Staff</label><input type="number" name="stats_teachers" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['stats_teachers'] ?? '0') ?>"></div>
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Jurusan</label><input type="number" name="stats_programs" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['stats_programs'] ?? '0') ?>"></div>
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Alumni</label><input type="number" name="stats_alumni" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['stats_alumni'] ?? '0') ?>"></div>
            </div>
            <div class="mt-6"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</div>

<!-- SEO Tab -->
<div class="tab-panel hidden" id="seoTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/settings/umum">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="group" value="seo">
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Meta Title</label><input type="text" name="meta_title" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($settings['meta_title'] ?? '') ?>"></div>
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Meta Description</label><textarea name="meta_description" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea></div>
                <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Google Analytics ID</label><input type="text" name="ga_code" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="G-XXXXXXXXXX" value="<?= htmlspecialchars($settings['ga_code'] ?? '') ?>"></div>
            </div>
            <div class="mt-6"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</div>


<!-- Social Tab -->
<div class="tab-panel hidden" id="socialTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm"><i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?><?php unset($_SESSION['flash_success']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?><?php unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-share-alt text-blue-600"></i>Kelola Tombol Sosial Media</h5>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors" onclick="document.getElementById('addSocialModal').classList.remove('hidden');document.getElementById('addSocialModal').classList.add('flex');">
                <i class="fas fa-plus mr-1"></i>Tambah
            </button>
        </div>

        <?php
        $db = getDB();
        $smRes = $db->query("SELECT * FROM social_media ORDER BY id ASC");
        $socialList = $smRes ? $smRes->fetch_all(MYSQLI_ASSOC) : [];
        ?>

        <?php if (empty($socialList)): ?>
            <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                <i class="fas fa-share-alt text-4xl mb-3 opacity-30"></i>
                <p>Belum ada sosial media. Klik <strong>Tambah</strong> untuk menambahkan.</p>
            </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-12">Icon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Platform</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">URL</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Class Icon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-24">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach ($socialList as $sm): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-4 py-3"><i class="<?= htmlspecialchars($sm['icon'] ?? 'fas fa-link') ?> text-lg text-blue-600"></i></td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($sm['platform']) ?></td>
                        <td class="px-4 py-3"><a href="<?= htmlspecialchars($sm['url']) ?>" target="_blank" class="text-blue-600 hover:underline text-xs" rel="noopener"><?= htmlspecialchars($sm['url']) ?></a></td>
                        <td class="px-4 py-3"><code class="text-xs bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded"><?= htmlspecialchars($sm['icon'] ?? '') ?></code></td>
                        <td class="px-4 py-3">
                            <a href="<?= APP_URL ?>/admin/sosmed/toggle/<?= $sm['id'] ?>" class="px-2 py-0.5 text-xs font-bold rounded-full <?= $sm['is_active'] ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>">
                                <?= $sm['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors"
                                    onclick="editSocial(<?= $sm['id'] ?>, '<?= htmlspecialchars(addslashes($sm['platform'])) ?>', '<?= htmlspecialchars(addslashes($sm['url'])) ?>', '<?= htmlspecialchars(addslashes($sm['icon'] ?? '')) ?>', <?= $sm['is_active'] ?>)">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <a href="<?= APP_URL ?>/admin/sosmed/hapus/<?= $sm['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus sosial media ini?">
                                    <i class="fas fa-trash text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">
            <strong><i class="fas fa-info-circle mr-1"></i>Referensi class icon:</strong>
            <div class="mt-2 flex gap-3 flex-wrap">
                <?php foreach ([['fab fa-facebook-f','Facebook'],['fab fa-instagram','Instagram'],['fab fa-youtube','YouTube'],['fab fa-x-twitter','Twitter/X'],['fab fa-tiktok','TikTok'],['fab fa-whatsapp','WhatsApp'],['fab fa-telegram','Telegram'],['fab fa-linkedin-in','LinkedIn']] as $ic): ?>
                <span title="<?= $ic[1] ?>"><i class="<?= $ic[0] ?> mr-1"></i><code><?= $ic[0] ?></code></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer Tab -->
<div class="tab-panel hidden" id="footerTab">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
        <form method="POST" action="<?= APP_URL ?>/admin/settings/umum">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="group" value="footer">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="fas fa-align-left mr-1 text-blue-600"></i>Teks Tentang di Footer</label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-1.5">Deskripsi singkat sekolah di kolom pertama footer.</small>
                    <textarea name="footer_about" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"><?= htmlspecialchars($settings['footer_about'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="fas fa-copyright mr-1 text-blue-600"></i>Teks Copyright</label>
                    <small class="text-xs text-slate-400 dark:text-slate-500 block mb-1.5">Teks setelah &copy; [tahun] [nama sekolah].</small>
                    <input type="text" name="footer_copyright" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Hak cipta dilindungi undang-undang." value="<?= htmlspecialchars($settings['footer_copyright'] ?? 'Hak cipta dilindungi undang-undang.') ?>">
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    <small class="text-xs text-slate-400 dark:text-slate-500"><i class="fas fa-eye mr-1"></i><strong>Preview:</strong></small>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">&copy; <?= date('Y') ?> <strong><?= htmlspecialchars($settings['school_name'] ?? 'SMK Pertamaku') ?></strong>. <span id="previewCopyright"><?= htmlspecialchars($settings['footer_copyright'] ?? 'Hak cipta dilindungi undang-undang.') ?></span></p>
                </div>
            </div>
            <div class="mt-6"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-2"></i>Simpan Pengaturan Footer</button></div>
        </form>
    </div>
</div>


<!-- Modal Tambah Sosial Media -->
<div id="addSocialModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl w-full max-w-md">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white"><i class="fas fa-plus-circle mr-2"></i>Tambah Sosial Media</h5>
            <button onclick="document.getElementById('addSocialModal').classList.add('hidden');document.getElementById('addSocialModal').classList.remove('flex');" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= APP_URL ?>/admin/sosmed/simpan">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="p-4 sm:p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Platform <span class="text-red-500">*</span></label>
                    <input type="text" name="platform" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="cth: Instagram" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">URL <span class="text-red-500">*</span></label>
                    <input type="url" name="url" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://instagram.com/namaakun" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Class Icon <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="text" name="icon" id="addIconInput" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="fab fa-instagram" required oninput="previewAddIcon(this.value)">
                        <span class="w-10 h-10 flex items-center justify-center border border-slate-200 dark:border-slate-700 rounded-lg"><i id="addIconPreview" class="fas fa-link text-lg text-blue-600"></i></span>
                    </div>
                    <small class="text-xs text-slate-400 mt-1 block">Contoh: <code>fab fa-instagram</code></small>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select name="is_active" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 p-4 sm:p-6 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('addSocialModal').classList.add('hidden');document.getElementById('addSocialModal').classList.remove('flex');" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Sosial Media -->
<div id="editSocialModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl w-full max-w-md">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white"><i class="fas fa-edit mr-2"></i>Edit Sosial Media</h5>
            <button onclick="document.getElementById('editSocialModal').classList.add('hidden');document.getElementById('editSocialModal').classList.remove('flex');" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="editSocialForm" action="">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="p-4 sm:p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Platform <span class="text-red-500">*</span></label>
                    <input type="text" name="platform" id="editPlatform" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">URL <span class="text-red-500">*</span></label>
                    <input type="url" name="url" id="editUrl" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Class Icon <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="text" name="icon" id="editIcon" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required oninput="previewEditIcon(this.value)">
                        <span class="w-10 h-10 flex items-center justify-center border border-slate-200 dark:border-slate-700 rounded-lg"><i id="editIconPreview" class="fas fa-link text-lg text-blue-600"></i></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select name="is_active" id="editIsActive" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 p-4 sm:p-6 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('editSocialModal').classList.add('hidden');document.getElementById('editSocialModal').classList.remove('flex');" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Tab switcher
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.remove('active','border-blue-600','text-blue-600','dark:text-blue-400','bg-blue-50','dark:bg-blue-900/20');
            b.classList.add('border-transparent','text-slate-500','dark:text-slate-400');
        });
        this.classList.add('active','border-blue-600','text-blue-600','dark:text-blue-400','bg-blue-50','dark:bg-blue-900/20');
        this.classList.remove('border-transparent','text-slate-500','dark:text-slate-400');
        document.querySelectorAll('.tab-panel').forEach(function(p) { p.classList.add('hidden'); });
        document.getElementById(this.getAttribute('data-target')).classList.remove('hidden');
    });
});

function previewBuilding(input) {
    var preview = document.getElementById('buildingPreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var copyrightInput = document.querySelector('input[name="footer_copyright"]');
    var previewEl = document.getElementById('previewCopyright');
    if (copyrightInput && previewEl) { copyrightInput.addEventListener('input', function() { previewEl.textContent = this.value; }); }
});

function previewAddIcon(val) { var el = document.getElementById('addIconPreview'); if (el) el.className = val || 'fas fa-link'; }
function previewEditIcon(val) { var el = document.getElementById('editIconPreview'); if (el) el.className = val || 'fas fa-link'; }

function editSocial(id, platform, url, icon, isActive) {
    document.getElementById('editPlatform').value = platform;
    document.getElementById('editUrl').value = url;
    document.getElementById('editIcon').value = icon;
    document.getElementById('editIsActive').value = isActive ? '1' : '0';
    previewEditIcon(icon);
    document.getElementById('editSocialForm').action = '<?= APP_URL ?>/admin/sosmed/edit/' + id;
    document.getElementById('editSocialModal').classList.remove('hidden');
    document.getElementById('editSocialModal').classList.add('flex');
}

// Auto-open tab from hash
if (window.location.hash === '#social') { document.querySelector('[data-target="socialTab"]').click(); }
if (window.location.hash === '#footer') { document.querySelector('[data-target="footerTab"]').click(); }
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
