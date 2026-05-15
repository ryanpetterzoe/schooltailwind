<?php $adminPageTitle = 'Detail Pendaftar'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Data -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-user text-blue-600"></i>Data Pribadi</h5>
                <?php
                $statusColors = ['pending'=>'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300','verifikasi'=>'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300','diterima'=>'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300','ditolak'=>'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300'];
                ?>
                <span class="px-3 py-1 text-sm font-bold rounded-full <?= $statusColors[$reg['status']] ?? 'bg-slate-100 text-slate-600' ?>"><?= ucfirst($reg['status']) ?></span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php
                $fields = [
                    'No. Pendaftaran' => $reg['registration_number'],
                    'Nama Lengkap' => $reg['full_name'],
                    'Nama Panggilan' => $reg['nick_name'] ?? '-',
                    'Jenis Kelamin' => $reg['gender'] === 'L' ? 'Laki-laki' : 'Perempuan',
                    'Tempat Lahir' => $reg['birth_place'] ?? '-',
                    'Tanggal Lahir' => !empty($reg['birth_date']) ? formatDate($reg['birth_date']) : '-',
                    'Agama' => $reg['religion'] ?? '-',
                    'Alamat' => $reg['address'] ?? '-',
                    'No HP' => $reg['phone'] ?? '-',
                    'Email' => $reg['email'] ?? '-',
                ];
                foreach ($fields as $label => $val): ?>
                <div>
                    <div class="text-xs text-slate-400 dark:text-slate-500"><?= $label ?></div>
                    <div class="text-sm font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($val) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- School Data -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2 mb-4"><i class="fas fa-school text-blue-600"></i>Data Asal Sekolah & Jurusan</h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php
                $schoolFields = [
                    'Asal Sekolah' => $reg['school_origin'] ?? '-',
                    'NISN' => $reg['nisn'] ?? '-',
                    'Nilai Rata-rata' => $reg['un_score'] ? number_format($reg['un_score'], 2) : '-',
                    'Pilihan 1' => $reg['program_name'] ?? '-',
                    'Pilihan 2' => $reg['program_choice2_name'] ?? '-',
                ];
                foreach ($schoolFields as $label => $val): ?>
                <div>
                    <div class="text-xs text-slate-400 dark:text-slate-500"><?= $label ?></div>
                    <div class="text-sm font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($val) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Parent Data -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2 mb-4"><i class="fas fa-users text-blue-600"></i>Data Orang Tua</h5>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <?php
                $parentFields = [
                    'Nama Ayah' => $reg['father_name'] ?? '-',
                    'Pekerjaan Ayah' => $reg['father_job'] ?? '-',
                    'HP Ayah' => $reg['father_phone'] ?? '-',
                    'Nama Ibu' => $reg['mother_name'] ?? '-',
                    'Pekerjaan Ibu' => $reg['mother_job'] ?? '-',
                    'Penghasilan' => $reg['parent_income'] ?? '-',
                ];
                foreach ($parentFields as $label => $val): ?>
                <div>
                    <div class="text-xs text-slate-400 dark:text-slate-500"><?= $label ?></div>
                    <div class="text-sm font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($val) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Documents -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white flex items-center gap-2 mb-4"><i class="fas fa-file-alt text-blue-600"></i>Dokumen Upload</h5>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?php
                $docs = [
                    'Pas Foto' => $reg['photo'] ?? '',
                    'Kartu Keluarga' => $reg['doc_kk'] ?? '',
                    'Akta Kelahiran' => $reg['doc_akta'] ?? '',
                    'Ijazah/SKL' => $reg['doc_ijazah'] ?? '',
                    'Rapor' => $reg['doc_raport'] ?? '',
                ];
                foreach ($docs as $docLabel => $docFile): ?>
                <div>
                    <div class="text-xs text-slate-400 dark:text-slate-500 mb-1.5"><?= $docLabel ?></div>
                    <?php if (!empty($docFile)): ?>
                        <a href="<?= UPLOAD_URL . htmlspecialchars($docFile) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-medium hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <i class="fas fa-download"></i>Lihat Dokumen
                        </a>
                    <?php else: ?>
                        <span class="text-xs text-slate-400 dark:text-slate-500">Tidak diupload</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: Status Update -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Update Status</h5>
            <form method="POST" action="<?= APP_URL ?>/admin/spmb/status/<?= $reg['id'] ?>">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status Pendaftaran</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php foreach (['pending'=>'Menunggu Verifikasi','verifikasi'=>'Sedang Diverifikasi','diterima'=>'Diterima','ditolak'=>'Ditolak'] as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $reg['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan</label>
                        <textarea name="notes" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Catatan untuk calon siswa..."><?= htmlspecialchars($reg['notes'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-6">
            <h5 class="text-base font-semibold text-slate-800 dark:text-white mb-4">Info Pendaftaran</h5>
            <div class="space-y-3">
                <div>
                    <small class="text-xs text-slate-400 dark:text-slate-500">Mendaftar</small>
                    <div class="text-sm font-medium text-slate-800 dark:text-white"><?= formatDate($reg['created_at']) ?></div>
                </div>
                <div>
                    <small class="text-xs text-slate-400 dark:text-slate-500">Tahun Ajaran</small>
                    <div class="text-sm font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($reg['academic_year']) ?></div>
                </div>
            </div>
            <hr class="border-slate-200 dark:border-slate-700 my-4">
            <a href="<?= APP_URL ?>/admin/spmb/pendaftar" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-left"></i>Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
