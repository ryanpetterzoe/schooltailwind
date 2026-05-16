<?php
requireLogin();
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminRole = $_SESSION['admin_role'] ?? 'admin';
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$db = getDB();
$unreadRes = @$db->query("SELECT COUNT(*) as cnt FROM contacts WHERE is_read=0");
$unreadCount = $unreadRes ? (int)$unreadRes->fetch_assoc()['cnt'] : 0;
$pendingRes = @$db->query("SELECT COUNT(*) as cnt FROM spmb_registrations WHERE status='pending'");
$pendingCount = $pendingRes ? (int)$pendingRes->fetch_assoc()['cnt'] : 0;

$pageTitle = $adminPageTitle ?? 'Admin Panel';
$settings = getSettings();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> - Admin Panel</title>
  <!-- Accent palette (CSS vars + Tailwind override window var) -->
  <?= renderAccentTheme($settings) ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class',theme:{extend:{colors:Object.assign({primary:window.__accentTailwindColors.blue,accent:window.__accentTailwindColors.indigo},window.__accentTailwindColors),fontFamily:{sans:['Inter','system-ui','sans-serif']}}}}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../../assets/css/style.css') ?: time() ?>">
  <!-- Quill 2 (rich text editor, MIT licensed, no API key) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css">
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin-editor.css?v=<?= @filemtime(__DIR__ . '/../../assets/css/admin-editor.css') ?: time() ?>">
  <script>(function(){var t=localStorage.getItem('smk_theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased">

<div class="flex min-h-screen">
<!-- ========== SIDEBAR ========== -->
<aside class="admin-sidebar bg-slate-900 flex flex-col" id="adminSidebar">
  <a href="<?= APP_URL ?>/admin/dashboard" class="flex items-center gap-3 px-5 py-5 border-b border-slate-800">
    <i class="fas fa-graduation-cap text-blue-400 text-lg"></i>
    <span class="text-white font-bold">SMK Panel</span>
  </a>

  <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
    <!-- Dashboard -->
    <a href="<?= APP_URL ?>/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'dashboard') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
      <i class="fas fa-tachometer-alt w-5 text-center"></i>Dashboard
    </a>

    <!-- Konten -->
    <div class="pt-4"><span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Konten</span></div>
    <a href="<?= APP_URL ?>/admin/berita" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/berita') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-newspaper w-5 text-center"></i>Berita</a>
    <a href="<?= APP_URL ?>/admin/galeri" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/galeri') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-images w-5 text-center"></i>Galeri</a>
    <a href="<?= APP_URL ?>/admin/slider" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/slider') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-images w-5 text-center"></i>Slider</a>
    <a href="<?= APP_URL ?>/admin/prestasi" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/prestasi') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-trophy w-5 text-center"></i>Prestasi</a>
    <a href="<?= APP_URL ?>/admin/testimonial" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/testimonial') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-quote-left w-5 text-center"></i>Testimonial</a>
    <a href="<?= APP_URL ?>/admin/agenda" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/agenda') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-calendar-alt w-5 text-center"></i>Agenda</a>

    <!-- Akademik -->
    <div class="pt-4"><span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Akademik</span></div>
    <a href="<?= APP_URL ?>/admin/jurusan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/jurusan') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-book w-5 text-center"></i>Jurusan</a>
    <a href="<?= APP_URL ?>/admin/guru" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/guru') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-chalkboard-teacher w-5 text-center"></i>Guru</a>
    <a href="<?= APP_URL ?>/admin/staff" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/staff') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-users w-5 text-center"></i>Staff</a>

    <!-- SPMB -->
    <div class="pt-4"><span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">SPMB</span></div>
    <a href="<?= APP_URL ?>/admin/spmb/pendaftar" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/spmb/pendaftar') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-user-plus w-5 text-center"></i>Pendaftar<?php if ($pendingCount > 0): ?><span class="ml-auto px-2 py-0.5 bg-amber-500 text-white text-[10px] font-bold rounded-full"><?= $pendingCount ?></span><?php endif; ?></a>
    <a href="<?= APP_URL ?>/admin/spmb/pengaturan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/spmb/pengaturan') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-sliders-h w-5 text-center"></i>Pengaturan SPMB</a>

    <!-- Komunikasi -->
    <div class="pt-4"><span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Komunikasi</span></div>
    <a href="<?= APP_URL ?>/admin/kontak" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/kontak') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-envelope w-5 text-center"></i>Pesan Masuk<?php if ($unreadCount > 0): ?><span class="ml-auto px-2 py-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full"><?= $unreadCount ?></span><?php endif; ?></a>

    <!-- Pengaturan -->
    <div class="pt-4"><span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pengaturan</span></div>
    <a href="<?= APP_URL ?>/admin/settings/umum" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/settings/umum') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-cog w-5 text-center"></i>Pengaturan Umum</a>
    <a href="<?= APP_URL ?>/admin/settings/tampilan" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors <?= strpos($currentPath,'/admin/settings/tampilan') !== false ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>"><i class="fas fa-palette w-5 text-center"></i>Tampilan & Warna</a>
  </nav>

  <!-- Sidebar Footer -->
  <div class="px-3 py-4 border-t border-slate-800">
    <a href="<?= APP_URL ?>/admin/logout" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 transition-colors">
      <i class="fas fa-sign-out-alt w-5 text-center"></i>Keluar
    </a>
  </div>
</aside>

<!-- ========== MAIN CONTENT ========== -->
<div class="admin-content flex-1 flex flex-col">
  <!-- Top Bar -->
  <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-700 px-4 sm:px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <button id="sidebarToggle" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">
        <i class="fas fa-bars"></i>
      </button>
      <h1 class="text-lg font-bold text-slate-800 dark:text-white"><?= htmlspecialchars($pageTitle) ?></h1>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= APP_URL ?>/" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-blue-600 transition-colors" title="Lihat Website"><i class="fas fa-external-link-alt text-sm"></i></a>
      <button id="themeToggle" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-colors" title="Toggle Theme">
        <i class="fas fa-moon theme-icon-light"></i><i class="fas fa-sun theme-icon-dark hidden"></i>
      </button>
      <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm"><?= strtoupper(substr($adminName, 0, 1)) ?></div>
      <div class="hidden sm:block">
        <div class="text-sm font-semibold text-slate-800 dark:text-white"><?= htmlspecialchars($adminName) ?></div>
        <div class="text-xs text-slate-400"><?= ucfirst($adminRole) ?></div>
      </div>
      <a href="<?= APP_URL ?>/admin/logout" class="w-9 h-9 flex items-center justify-center rounded-lg border border-red-200 dark:border-red-800 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Logout"><i class="fas fa-sign-out-alt text-sm"></i></a>
    </div>
  </header>

  <!-- Inline sidebar toggle for mobile (ensures it works immediately) -->
  <script>
  (function(){
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    if(toggle && sidebar){
      toggle.addEventListener('click', function(){
        sidebar.classList.toggle('open');
      });
      // Close sidebar when clicking outside
      document.addEventListener('click', function(e){
        if(window.innerWidth < 1024 && sidebar.classList.contains('open')){
          if(!sidebar.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)){
            sidebar.classList.remove('open');
          }
        }
      });
    }

    // Theme toggle (replicates the public layout so admin can switch
    // dark/light without needing main.js to attach listeners). Inline
    // so it works immediately on first paint, even before main.js loads.
    function syncThemeIcons(){
      var nowDark = document.documentElement.classList.contains('dark');
      document.querySelectorAll('.theme-icon-light').forEach(function(el){ el.style.display = nowDark ? 'none' : ''; });
      document.querySelectorAll('.theme-icon-dark').forEach(function(el){ el.style.display = nowDark ? '' : 'none'; });
    }
    function doToggleTheme(){
      var isDark = document.documentElement.classList.contains('dark');
      if(isDark){
        document.documentElement.classList.remove('dark');
        localStorage.setItem('smk_theme','light');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('smk_theme','dark');
      }
      syncThemeIcons();
    }
    document.querySelectorAll('#themeToggle, #themeToggleMobile').forEach(function(b){
      b.addEventListener('click', doToggleTheme);
    });
    syncThemeIcons();
  })();
  </script>

  <!-- Inner Content -->
  <div class="flex-1 p-4 sm:p-6 lg:p-8">

    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400 text-sm flex items-center justify-between" role="alert" data-dismiss-auto>
      <span><i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($_SESSION['flash_success']) ?></span>
      <button data-dismiss-alert class="text-green-400 hover:text-green-600">&times;</button>
    </div>
    <?php unset($_SESSION['flash_success']); endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm flex items-center justify-between" role="alert" data-dismiss-auto>
      <span><i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?></span>
      <button data-dismiss-alert class="text-red-400 hover:text-red-600">&times;</button>
    </div>
    <?php unset($_SESSION['flash_error']); endif; ?>
