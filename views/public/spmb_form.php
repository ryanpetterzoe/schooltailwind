<?php
$pageTitle = 'Formulir Pendaftaran SPMB - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
$inputClass = 'w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent';
$labelClass = 'block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl font-extrabold text-white mb-1">Formulir Pendaftaran</h1>
    <p class="text-white/70">SPMB Tahun Ajaran <?= htmlspecialchars($spmbSettings['academic_year'] ?? date('Y') . '/' . (date('Y')+1)) ?></p>
  </div>
</section>

<section class="py-12">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

<?php if (!empty($error)): ?>
<div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm"><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div class="text-center py-10 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-8">
  <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
  <h4 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Pendaftaran Berhasil!</h4>
  <p class="text-slate-500 mb-2">Nomor pendaftaran Anda: <strong class="text-lg text-blue-600"><?= htmlspecialchars($regNumber ?? '') ?></strong></p>
  <p class="text-slate-400 text-sm mb-6">Simpan nomor ini untuk mengecek status pendaftaran Anda.</p>
  <div class="flex gap-3 justify-center">
    <a href="<?= APP_URL ?>/spmb/cek" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg hover:-translate-y-0.5 transition-all">Cek Status</a>
    <a href="<?= APP_URL ?>/spmb" class="px-5 py-2.5 border-2 border-blue-600 text-blue-600 rounded-xl font-semibold text-sm hover:bg-blue-600 hover:text-white transition-all">Kembali</a>
  </div>
</div>
<?php else: ?>

<!-- Step Progress -->
<div class="flex justify-between items-center mb-8 overflow-x-auto pb-2">
  <?php $steps = ['Data Pribadi','Asal Sekolah','Jurusan','Orang Tua','Dokumen','Preview'];
  foreach ($steps as $i => $step): ?>
  <div class="step-item flex flex-col items-center min-w-[60px] <?= $i === 0 ? 'text-blue-600' : 'text-slate-400' ?>">
    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold mb-1 <?= $i === 0 ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 dark:border-slate-600' ?>">
      <?= $i < count($steps)-1 ? $i+1 : '✓' ?>
    </div>
    <span class="text-[10px] sm:text-xs font-medium text-center"><?= $step ?></span>
  </div>
  <?php if ($i < count($steps)-1): ?><div class="flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-1 mt-[-16px]"></div><?php endif; ?>
  <?php endforeach; ?>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">
<form id="spmbForm" method="POST" action="<?= APP_URL ?>/spmb/daftar" enctype="multipart/form-data">
<input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

<!-- Step 1: Data Pribadi -->
<div class="step-panel active">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-blue-600"><span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">1</span>Data Pribadi</h5>
  <div class="grid sm:grid-cols-12 gap-4">
    <div class="sm:col-span-8"><label class="<?= $labelClass ?>" for="full_name">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="full_name" name="full_name" required placeholder="Nama lengkap sesuai akte"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="nick_name">Panggilan</label><input type="text" class="<?= $inputClass ?>" id="nick_name" name="nick_name" placeholder="Nama panggilan"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="gender">Jenis Kelamin <span class="text-red-500">*</span></label><select class="<?= $inputClass ?>" id="gender" name="gender" required><option value="">-- Pilih --</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="birth_place">Tempat Lahir <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="birth_place" name="birth_place" required placeholder="Kota lahir"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="birth_date">Tanggal Lahir <span class="text-red-500">*</span></label><input type="date" class="<?= $inputClass ?>" id="birth_date" name="birth_date" required></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="religion">Agama <span class="text-red-500">*</span></label><select class="<?= $inputClass ?>" id="religion" name="religion" required><option value="">-- Pilih --</option><option>Islam</option><option>Kristen</option><option>Katolik</option><option>Hindu</option><option>Buddha</option><option>Konghucu</option></select></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="phone">No HP</label><input type="text" class="<?= $inputClass ?>" id="phone" name="phone" placeholder="08xxxxxxxxxx"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="email">Email</label><input type="email" class="<?= $inputClass ?>" id="email" name="email" placeholder="email@contoh.com"></div>
    <div class="sm:col-span-12"><label class="<?= $labelClass ?>" for="address">Alamat Lengkap <span class="text-red-500">*</span></label><textarea class="<?= $inputClass ?> resize-y" id="address" name="address" rows="2" required placeholder="Alamat lengkap"></textarea></div>
  </div>
  <div class="flex justify-end mt-6"><button type="button" class="btn-next px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors inline-flex items-center gap-2">Selanjutnya <i class="fas fa-arrow-right"></i></button></div>
</div>

<!-- Step 2: Asal Sekolah -->
<div class="step-panel">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-blue-600"><span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">2</span>Data Asal Sekolah</h5>
  <div class="grid sm:grid-cols-12 gap-4">
    <div class="sm:col-span-8"><label class="<?= $labelClass ?>" for="school_origin">Nama SMP/MTs <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="school_origin" name="school_origin" required placeholder="Nama sekolah asal"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="nisn">NISN <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="nisn" name="nisn" required placeholder="10 digit" maxlength="10"></div>
    <div class="sm:col-span-4"><label class="<?= $labelClass ?>" for="un_score">Rata-rata Rapor</label><input type="number" class="<?= $inputClass ?>" id="un_score" name="un_score" placeholder="85.50" step="0.01" min="0" max="100"></div>
  </div>
  <div class="flex justify-between mt-6">
    <button type="button" class="btn-prev px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i>Sebelumnya</button>
    <button type="button" class="btn-next px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors inline-flex items-center gap-2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

<!-- Step 3: Jurusan -->
<div class="step-panel">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-blue-600"><span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">3</span>Pilihan Program Keahlian</h5>
  <div class="grid sm:grid-cols-2 gap-4">
    <div><label class="<?= $labelClass ?>" for="program_id">Pilihan 1 <span class="text-red-500">*</span></label><select class="<?= $inputClass ?>" id="program_id" name="program_id" required><option value="">-- Pilih Jurusan --</option><?php foreach ($programs as $prog): ?><option value="<?= $prog['id'] ?>"><?= htmlspecialchars($prog['name']) ?> (<?= htmlspecialchars($prog['code'] ?? '') ?>)</option><?php endforeach; ?></select></div>
    <div><label class="<?= $labelClass ?>" for="program_choice2">Pilihan 2</label><select class="<?= $inputClass ?>" id="program_choice2" name="program_choice2"><option value="">-- Opsional --</option><?php foreach ($programs as $prog): ?><option value="<?= $prog['id'] ?>"><?= htmlspecialchars($prog['name']) ?> (<?= htmlspecialchars($prog['code'] ?? '') ?>)</option><?php endforeach; ?></select></div>
  </div>
  <div class="flex justify-between mt-6">
    <button type="button" class="btn-prev px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i>Sebelumnya</button>
    <button type="button" class="btn-next px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors inline-flex items-center gap-2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

<!-- Step 4: Orang Tua -->
<div class="step-panel">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-blue-600"><span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">4</span>Data Orang Tua / Wali</h5>
  <div class="grid sm:grid-cols-12 gap-4">
    <div class="sm:col-span-12"><h6 class="text-sm text-slate-400 font-semibold">Data Ayah</h6></div>
    <div class="sm:col-span-6"><label class="<?= $labelClass ?>" for="father_name">Nama Ayah <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="father_name" name="father_name" required placeholder="Nama lengkap"></div>
    <div class="sm:col-span-3"><label class="<?= $labelClass ?>" for="father_job">Pekerjaan</label><input type="text" class="<?= $inputClass ?>" id="father_job" name="father_job"></div>
    <div class="sm:col-span-3"><label class="<?= $labelClass ?>" for="father_phone">HP Ayah</label><input type="text" class="<?= $inputClass ?>" id="father_phone" name="father_phone" placeholder="08xxx"></div>
    <div class="sm:col-span-12"><h6 class="text-sm text-slate-400 font-semibold">Data Ibu</h6></div>
    <div class="sm:col-span-6"><label class="<?= $labelClass ?>" for="mother_name">Nama Ibu <span class="text-red-500">*</span></label><input type="text" class="<?= $inputClass ?>" id="mother_name" name="mother_name" required placeholder="Nama lengkap"></div>
    <div class="sm:col-span-3"><label class="<?= $labelClass ?>" for="mother_job">Pekerjaan</label><input type="text" class="<?= $inputClass ?>" id="mother_job" name="mother_job"></div>
    <div class="sm:col-span-3"><label class="<?= $labelClass ?>" for="parent_income">Penghasilan</label><select class="<?= $inputClass ?>" id="parent_income" name="parent_income"><option value="">-- Pilih --</option><option>< Rp 1.000.000</option><option>Rp 1-3 Juta</option><option>Rp 3-5 Juta</option><option>> Rp 5.000.000</option></select></div>
  </div>
  <div class="flex justify-between mt-6">
    <button type="button" class="btn-prev px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i>Sebelumnya</button>
    <button type="button" class="btn-next px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors inline-flex items-center gap-2">Selanjutnya <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

<!-- Step 5: Dokumen -->
<div class="step-panel">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-blue-600"><span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-xs rounded-full mr-2">5</span>Upload Dokumen</h5>
  <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl text-sm text-blue-700 dark:text-blue-400 mb-5"><i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, PDF. Maks 2MB/file.</div>
  <div class="grid sm:grid-cols-2 gap-4">
    <div><label class="<?= $labelClass ?>" for="photo">Pas Foto 3x4 <span class="text-red-500">*</span></label><input type="file" class="<?= $inputClass ?> image-upload-input" id="photo" name="photo" accept="image/*" data-preview="#previewPhoto"><img id="previewPhoto" src="" class="hidden mt-2 max-h-28 rounded-lg"></div>
    <div><label class="<?= $labelClass ?>" for="doc_kk">Kartu Keluarga</label><input type="file" class="<?= $inputClass ?>" id="doc_kk" name="doc_kk" accept="image/*,.pdf"></div>
    <div><label class="<?= $labelClass ?>" for="doc_akta">Akta Kelahiran</label><input type="file" class="<?= $inputClass ?>" id="doc_akta" name="doc_akta" accept="image/*,.pdf"></div>
    <div><label class="<?= $labelClass ?>" for="doc_ijazah">Ijazah/SKL SMP</label><input type="file" class="<?= $inputClass ?>" id="doc_ijazah" name="doc_ijazah" accept="image/*,.pdf"></div>
    <div><label class="<?= $labelClass ?>" for="doc_raport">Rapor SMP</label><input type="file" class="<?= $inputClass ?>" id="doc_raport" name="doc_raport" accept="image/*,.pdf"></div>
  </div>
  <div class="flex justify-between mt-6">
    <button type="button" class="btn-prev px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i>Sebelumnya</button>
    <button type="button" class="btn-next px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors inline-flex items-center gap-2">Preview <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

<!-- Step 6: Preview -->
<div class="step-panel">
  <h5 class="font-bold text-slate-800 dark:text-white mb-5 pb-3 border-b-2 border-green-500"><span class="inline-flex items-center justify-center w-6 h-6 bg-green-500 text-white text-xs rounded-full mr-2">✓</span>Preview & Konfirmasi</h5>
  <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl text-sm text-amber-700 dark:text-amber-400 mb-5"><i class="fas fa-exclamation-triangle mr-1"></i>Periksa kembali data sebelum mengirim.</div>
  <div id="previewData" class="mb-5"></div>
  <div class="flex items-start gap-2 mb-5">
    <input type="checkbox" id="agreeCheck" required class="mt-1 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
    <label for="agreeCheck" class="text-sm text-slate-600 dark:text-slate-300">Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan.</label>
  </div>
  <div class="flex justify-between">
    <button type="button" class="btn-prev px-5 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i>Sebelumnya</button>
    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-green-500/25 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2"><i class="fas fa-paper-plane"></i>Kirim Pendaftaran</button>
  </div>
</div>

</form>
</div>
<?php endif; ?>
</div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
