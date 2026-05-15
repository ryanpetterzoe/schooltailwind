<?php
$pageTitle = 'Kontak - ' . ($settings['school_name'] ?? 'SMK Pertamaku');
require_once __DIR__ . '/../layouts/header.php';
?>

<section class="relative bg-gradient-to-br from-blue-600 to-indigo-700 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Kontak Kami</h1>
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= APP_URL ?>/" class="text-white/70 hover:text-white transition-colors">Beranda</a>
      <i class="fas fa-chevron-right text-white/40 text-xs"></i>
      <span class="text-white">Kontak</span>
    </nav>
  </div>
</section>

<section class="py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php if (!empty($success)): ?>
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm flex items-center gap-2" role="alert" data-dismiss-auto>
      <i class="fas fa-check-circle"></i><?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm flex items-center gap-2" role="alert" data-dismiss-auto>
      <i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-10">
      <!-- Contact Info -->
      <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Informasi Kontak</h3>
        <div class="space-y-5">
          <?php if (!empty($settings['school_address'])): ?>
          <div class="flex gap-4">
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-map-marker-alt text-blue-600"></i></div>
            <div><strong class="block text-sm text-slate-700 dark:text-slate-200 mb-0.5">Alamat</strong><span class="text-sm text-slate-400"><?= htmlspecialchars($settings['school_address']) ?></span></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($settings['school_phone'])): ?>
          <div class="flex gap-4">
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-phone text-blue-600"></i></div>
            <div><strong class="block text-sm text-slate-700 dark:text-slate-200 mb-0.5">Telepon</strong><a href="tel:<?= preg_replace('/[^0-9+]/','',$settings['school_phone']) ?>" class="text-sm text-slate-400 hover:text-blue-600"><?= htmlspecialchars($settings['school_phone']) ?></a></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($settings['school_email'])): ?>
          <div class="flex gap-4">
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-envelope text-blue-600"></i></div>
            <div><strong class="block text-sm text-slate-700 dark:text-slate-200 mb-0.5">Email</strong><a href="mailto:<?= htmlspecialchars($settings['school_email']) ?>" class="text-sm text-slate-400 hover:text-blue-600"><?= htmlspecialchars($settings['school_email']) ?></a></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($settings['whatsapp_number'])): ?>
          <div class="flex gap-4">
            <div class="w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fab fa-whatsapp text-green-500"></i></div>
            <div><strong class="block text-sm text-slate-700 dark:text-slate-200 mb-0.5">WhatsApp</strong><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$settings['whatsapp_number']) ?>" target="_blank" class="text-sm text-slate-400 hover:text-green-500">Chat Sekarang</a></div>
          </div>
          <?php endif; ?>
        </div>

        <?php
        $db = getDB();
        $smRes = $db->query("SELECT * FROM social_media WHERE is_active=1");
        $socialMedia = $smRes ? $smRes->fetch_all(MYSQLI_ASSOC) : [];
        if ($socialMedia): ?>
        <h6 class="mt-8 mb-3 font-semibold text-sm text-slate-700 dark:text-slate-200">Media Sosial</h6>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($socialMedia as $sm): ?>
          <a href="<?= htmlspecialchars($sm['url']) ?>" target="_blank" rel="noopener" class="px-3 py-1.5 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 text-xs font-semibold rounded-lg hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all">
            <i class="<?= htmlspecialchars($sm['icon'] ?? 'fas fa-link') ?> mr-1"></i><?= htmlspecialchars($sm['platform']) ?>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Contact Form -->
      <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">
          <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Kirim Pesan</h3>
          <form method="POST" action="<?= APP_URL ?>/kontak">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" required maxlength="150" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Nama Anda" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="email@contoh.com" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nomor HP/WhatsApp</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Subjek</label>
                <input type="text" name="subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" placeholder="Subjek pesan" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                <textarea name="message" required rows="5" maxlength="1000" placeholder="Tulis pesan Anda..." class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-y"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>
              <div class="sm:col-span-2">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                  <i class="fas fa-paper-plane"></i>Kirim Pesan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Map -->
    <?php if (!empty($settings['maps_embed'])): ?>
    <div class="mt-12">
      <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Lokasi Sekolah</h4>
      <div class="rounded-2xl overflow-hidden h-[400px] border border-slate-200 dark:border-slate-700">
        <iframe src="<?= htmlspecialchars($settings['maps_embed']) ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
