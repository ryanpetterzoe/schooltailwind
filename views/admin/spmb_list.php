<?php $adminPageTitle = 'Data Pendaftar SPMB'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <?php
    $statusStats = ['pending'=>['label'=>'Menunggu','color'=>'text-orange-500','bg'=>'bg-orange-50 dark:bg-orange-900/20'],'verifikasi'=>['label'=>'Verifikasi','color'=>'text-blue-500','bg'=>'bg-blue-50 dark:bg-blue-900/20'],'diterima'=>['label'=>'Diterima','color'=>'text-green-500','bg'=>'bg-green-50 dark:bg-green-900/20'],'ditolak'=>['label'=>'Ditolak','color'=>'text-red-500','bg'=>'bg-red-50 dark:bg-red-900/20']];
    foreach ($statusStats as $st => $info):
    ?>
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg <?= $info['bg'] ?> flex items-center justify-center <?= $info['color'] ?>"><i class="fas fa-user"></i></div>
        <div>
            <div class="text-xl font-bold text-slate-800 dark:text-white"><?= $statusCount[$st] ?? 0 ?></div>
            <div class="text-xs text-slate-500 dark:text-slate-400"><?= $info['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filter & Table -->
<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <h5 class="text-lg font-semibold text-slate-800 dark:text-white flex items-center gap-2"><i class="fas fa-user-plus text-blue-600"></i>Daftar Pendaftar</h5>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <select name="status" class="px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="verifikasi" <?= ($_GET['status'] ?? '') === 'verifikasi' ? 'selected' : '' ?>>Verifikasi</option>
                    <option value="diterima" <?= ($_GET['status'] ?? '') === 'diterima' ? 'selected' : '' ?>>Diterima</option>
                    <option value="ditolak" <?= ($_GET['status'] ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
                <select name="program" class="px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($programs ?? [] as $prog): ?>
                    <option value="<?= $prog['id'] ?>" <?= ($_GET['program'] ?? '') == $prog['id'] ? 'selected' : '' ?>><?= htmlspecialchars($prog['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="q" class="w-full sm:w-44 px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari nama..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button class="px-3 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors">Cari</button>
            </form>
            <a href="<?= APP_URL ?>/admin/spmb/export" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors">
                <i class="fas fa-file-csv mr-1"></i>Export CSV
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">No. Daftar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Jurusan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">NISN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Asal Sekolah</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tgl Daftar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php if (empty($registrations)): ?>
                <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">Belum ada pendaftar</td></tr>
                <?php else: ?>
                <?php foreach ($registrations as $reg): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-300"><?= htmlspecialchars($reg['registration_number']) ?></td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800 dark:text-white"><?= htmlspecialchars($reg['full_name']) ?></div>
                        <small class="text-slate-400 dark:text-slate-500"><?= $reg['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></small>
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($reg['program_name'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= htmlspecialchars($reg['nisn'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 max-w-[160px] truncate"><?= htmlspecialchars($reg['school_origin'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?= date('d/m/Y', strtotime($reg['created_at'])) ?></td>
                    <td class="px-4 py-3">
                        <?php
                        $statusColors = ['pending'=>'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300','verifikasi'=>'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300','diterima'=>'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300','ditolak'=>'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300'];
                        ?>
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full <?= $statusColors[$reg['status']] ?? 'bg-slate-100 text-slate-600' ?>"><?= ucfirst($reg['status']) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="<?= APP_URL ?>/admin/spmb/detail/<?= $reg['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 transition-colors"><i class="fas fa-eye text-xs"></i></a>
                            <button type="button" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-green-600 hover:border-green-300 transition-colors" onclick="updateStatus(<?= $reg['id'] ?>,'<?= htmlspecialchars($reg['full_name']) ?>','<?= $reg['status'] ?>')"><i class="fas fa-edit text-xs"></i></button>
                            <a href="<?= APP_URL ?>/admin/spmb/hapus/<?= $reg['id'] ?>" class="p-1.5 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 transition-colors" data-confirm="Hapus data pendaftar ini?"><i class="fas fa-trash text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl w-full max-w-md">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700">
            <h5 class="text-lg font-semibold text-slate-800 dark:text-white">Update Status Pendaftar</h5>
            <button onclick="closeStatusModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="statusForm" action="">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="p-4 sm:p-6 space-y-4">
                <p id="statusModalName" class="font-semibold text-slate-800 dark:text-white"></p>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                    <select name="status" id="statusSelect" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Menunggu Verifikasi</option>
                        <option value="verifikasi">Sedang Diverifikasi</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Catatan</label>
                    <textarea name="notes" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Catatan untuk pendaftar (opsional)"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 p-4 sm:p-6 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateStatus(id, name, status) {
    document.getElementById('statusForm').action = '<?= APP_URL ?>/admin/spmb/status/' + id;
    document.getElementById('statusModalName').textContent = 'Pendaftar: ' + name;
    document.getElementById('statusSelect').value = status;
    var modal = document.getElementById('statusModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeStatusModal() {
    var modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
