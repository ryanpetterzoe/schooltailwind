<?php $adminPageTitle = 'Dashboard'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
  <?php
  $statCards = [
    ['icon'=>'fas fa-newspaper','num'=>$stats['news'] ?? 0,'label'=>'Total Berita','color'=>'blue'],
    ['icon'=>'fas fa-images','num'=>$stats['gallery'] ?? 0,'label'=>'Foto Galeri','color'=>'purple'],
    ['icon'=>'fas fa-user-plus','num'=>$stats['spmb_pending'] ?? 0,'label'=>'SPMB Pending','color'=>'amber'],
    ['icon'=>'fas fa-check-circle','num'=>$stats['spmb_accepted'] ?? 0,'label'=>'SPMB Diterima','color'=>'green'],
    ['icon'=>'fas fa-times-circle','num'=>$stats['spmb_rejected'] ?? 0,'label'=>'SPMB Ditolak','color'=>'red'],
    ['icon'=>'fas fa-envelope','num'=>$stats['unread_contacts'] ?? 0,'label'=>'Pesan Belum Dibaca','color'=>'teal'],
    ['icon'=>'fas fa-user-graduate','num'=>$stats['spmb_total'] ?? 0,'label'=>'Total Pendaftar','color'=>'blue'],
    ['icon'=>'fas fa-book','num'=>$stats['programs'] ?? 0,'label'=>'Program Keahlian','color'=>'green'],
  ];
  foreach ($statCards as $sc): ?>
  <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-4 hover:shadow-md transition-shadow">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-<?= $sc['color'] ?>-50 dark:bg-<?= $sc['color'] ?>-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
        <i class="<?= $sc['icon'] ?> text-<?= $sc['color'] ?>-500"></i>
      </div>
      <div>
        <div class="text-xl font-bold text-slate-800 dark:text-white"><?= $sc['num'] ?></div>
        <div class="text-xs text-slate-400"><?= $sc['label'] ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-3 gap-6">
  <!-- Recent SPMB -->
  <div class="lg:col-span-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-700">
      <h5 class="font-bold text-slate-800 dark:text-white text-sm flex items-center gap-2"><i class="fas fa-user-plus text-blue-500"></i>Pendaftar Terbaru</h5>
      <a href="<?= APP_URL ?>/admin/spmb/pendaftar" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No. Daftar</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jurusan</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
          <?php if (empty($recentSpmb)): ?>
          <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada pendaftar</td></tr>
          <?php else: foreach ($recentSpmb as $reg): ?>
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
            <td class="px-4 py-3 font-mono font-semibold text-slate-700 dark:text-slate-200"><?= htmlspecialchars($reg['registration_number']) ?></td>
            <td class="px-4 py-3 text-slate-700 dark:text-slate-200"><?= htmlspecialchars($reg['full_name']) ?></td>
            <td class="px-4 py-3 text-slate-500"><?= htmlspecialchars($reg['program_name'] ?? '-') ?></td>
            <td class="px-4 py-3 text-slate-500"><?= date('d/m/Y', strtotime($reg['created_at'])) ?></td>
            <td class="px-4 py-3">
              <?php
              $sColors = ['pending'=>'amber','verifikasi'=>'blue','diterima'=>'green','ditolak'=>'red'];
              $sC = $sColors[$reg['status']] ?? 'slate';
              ?>
              <span class="px-2 py-0.5 bg-<?= $sC ?>-50 dark:bg-<?= $sC ?>-900/30 text-<?= $sC ?>-600 dark:text-<?= $sC ?>-400 text-[11px] font-bold rounded-full"><?= ucfirst($reg['status']) ?></span>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="space-y-6">
    <!-- Quick Links -->
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5">
      <h5 class="font-bold text-slate-800 dark:text-white text-sm mb-4 flex items-center gap-2"><i class="fas fa-bolt text-amber-500"></i>Aksi Cepat</h5>
      <div class="space-y-2">
        <a href="<?= APP_URL ?>/admin/berita/tambah" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 hover:border-blue-200 transition-all"><i class="fas fa-plus w-4 text-center text-blue-500"></i>Tambah Berita</a>
        <a href="<?= APP_URL ?>/admin/galeri/tambah" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 hover:border-blue-200 transition-all"><i class="fas fa-image w-4 text-center text-blue-500"></i>Upload Galeri</a>
        <a href="<?= APP_URL ?>/admin/spmb/pendaftar" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-600 hover:border-amber-200 transition-all"><i class="fas fa-user-check w-4 text-center text-amber-500"></i>Verifikasi Pendaftar</a>
        <a href="<?= APP_URL ?>/admin/settings/umum" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all"><i class="fas fa-cog w-4 text-center text-slate-400"></i>Pengaturan</a>
      </div>
    </div>

    <!-- Recent Contacts -->
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl p-5">
      <h5 class="font-bold text-slate-800 dark:text-white text-sm mb-4 flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i>Pesan Terbaru</h5>
      <?php if (empty($recentContacts)): ?>
      <p class="text-sm text-slate-400">Belum ada pesan masuk</p>
      <?php else: ?>
      <div class="space-y-3">
        <?php foreach ($recentContacts as $msg): ?>
        <div class="flex gap-3 pb-3 border-b border-slate-100 dark:border-slate-700 last:border-0 last:pb-0">
          <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0"><i class="fas fa-user text-blue-500 text-xs"></i></div>
          <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold text-slate-700 dark:text-slate-200"><?= htmlspecialchars($msg['name']) ?></div>
            <div class="text-xs text-slate-400 truncate"><?= htmlspecialchars($msg['subject'] ?? '') ?></div>
          </div>
          <?php if (!$msg['is_read']): ?>
          <span class="px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-bold rounded-full self-center">Baru</span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <a href="<?= APP_URL ?>/admin/kontak" class="block mt-4 py-2 text-center border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">Lihat Semua Pesan</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
