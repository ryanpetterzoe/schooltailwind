<?php
$pageTitle = 'Cek Status Pendaftaran - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Cek Status Pendaftaran</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <a href="<?= APP_URL ?>/spmb" class="text-white/70 hover:text-white transition-colors">SPMB</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Cek Status</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search Form -->
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 mb-6">
      <h4 class="font-bold text-slate-800 dark:text-white mb-4">Masukkan Nomor Pendaftaran</h4>
      <form method="POST" action="<?= APP_URL ?>/spmb/cek">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <div class="flex">
          <input type="text" name="reg_number" required value="<?= htmlspecialchars($_POST['reg_number'] ?? '') ?>" placeholder="Contoh: REG-2025-0001" class="flex-1 px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-l-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-r-xl font-semibold hover:opacity-90 transition-opacity">Cek</button>
        </div>
      </form>
    </div>

    <?php if (!empty($error)): ?>
    <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl text-amber-700 dark:text-amber-400 text-sm flex items-center gap-2">
      <i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($registration)):
      $statusColors = ['pending'=>'amber','verifikasi'=>'blue','diterima'=>'green','ditolak'=>'red'];
      $statusLabels = ['pending'=>'Menunggu Verifikasi','verifikasi'=>'Sedang Diverifikasi','diterima'=>'Diterima','ditolak'=>'Tidak Diterima'];
      $status = $registration['status'] ?? 'pending';
      $sc = $statusColors[$status] ?? 'slate';
      $sl = $statusLabels[$status] ?? ucfirst($status);
    ?>
    <div class="bg-white dark:bg-slate-800 border-t-4 border-t-<?= $sc ?>-500 border border-slate-100 dark:border-slate-700 rounded-2xl p-6">
      <div class="flex items-center justify-between mb-5">
        <h5 class="font-bold text-slate-800 dark:text-white">Hasil Pencarian</h5>
        <span class="px-3 py-1 bg-<?= $sc ?>-100 dark:bg-<?= $sc ?>-900/30 text-<?= $sc ?>-700 dark:text-<?= $sc ?>-400 text-sm font-bold rounded-full"><?= $sl ?></span>
      </div>
      <div class="space-y-3">
        <div class="flex justify-between py-2 border-b border-slate-50 dark:border-slate-700"><span class="text-sm text-slate-400">No. Pendaftaran</span><strong class="text-sm text-slate-800 dark:text-white"><?= htmlspecialchars($registration['registration_number']) ?></strong></div>
        <div class="flex justify-between py-2 border-b border-slate-50 dark:border-slate-700"><span class="text-sm text-slate-400">Nama Lengkap</span><span class="text-sm text-slate-700 dark:text-slate-200"><?= htmlspecialchars($registration['full_name']) ?></span></div>
        <div class="flex justify-between py-2 border-b border-slate-50 dark:border-slate-700"><span class="text-sm text-slate-400">Tahun Ajaran</span><span class="text-sm text-slate-700 dark:text-slate-200"><?= htmlspecialchars($registration['academic_year']) ?></span></div>
        <div class="flex justify-between py-2 border-b border-slate-50 dark:border-slate-700"><span class="text-sm text-slate-400">Pilihan Jurusan</span><span class="text-sm text-slate-700 dark:text-slate-200"><?= htmlspecialchars($registration['program_name'] ?? '-') ?></span></div>
        <div class="flex justify-between py-2 border-b border-slate-50 dark:border-slate-700"><span class="text-sm text-slate-400">Tanggal Daftar</span><span class="text-sm text-slate-700 dark:text-slate-200"><?= formatDate($registration['created_at']) ?></span></div>
        <div class="flex justify-between py-2"><span class="text-sm text-slate-400">Status</span><span class="px-2.5 py-0.5 bg-<?= $sc ?>-100 dark:bg-<?= $sc ?>-900/30 text-<?= $sc ?>-700 dark:text-<?= $sc ?>-400 text-xs font-bold rounded-full"><?= $sl ?></span></div>
      </div>

      <?php if (!empty($registration['notes'])): ?>
      <div class="mt-5 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl">
        <p class="text-sm text-blue-700 dark:text-blue-400"><strong><i class="fas fa-comment mr-1"></i>Catatan:</strong><br><?= nl2br(htmlspecialchars($registration['notes'])) ?></p>
      </div>
      <?php endif; ?>

      <?php if ($status === 'diterima'): ?>
      <div class="mt-5 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm">
        <i class="fas fa-check-circle mr-1"></i><strong>Selamat!</strong> Anda diterima. Silakan hubungi sekolah untuk informasi daftar ulang.
      </div>
      <?php elseif ($status === 'ditolak'): ?>
      <div class="mt-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm">
        <i class="fas fa-times-circle mr-1"></i><strong>Mohon maaf.</strong> Pendaftaran tidak dapat dilanjutkan. Hubungi sekolah untuk info lebih lanjut.
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="text-center mt-6">
      <a href="<?= APP_URL ?>/spmb" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">
        <i class="fas fa-arrow-left"></i>Kembali ke SPMB
      </a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
