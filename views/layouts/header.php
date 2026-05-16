<?php
$settings    = getSettings();
$schoolName  = $settings['school_name']   ?? 'SMK Pertamaku';
$metaTitle   = $settings['meta_title']    ?? ($schoolName . ' - Sekolah Menengah Kejuruan Unggulan');
$metaDesc    = $settings['meta_description'] ?? '';
$logo        = !empty($settings['school_logo'])    ? UPLOAD_URL . $settings['school_logo']    : '';
$favicon     = !empty($settings['school_favicon']) ? UPLOAD_URL . $settings['school_favicon'] : '';
$uri         = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$appBase     = defined('APP_BASE') ? APP_BASE : '/webpertamaku';

if (!function_exists('isActive')) {
    function isActive($path, $uri, $base) {
        $clean = str_replace($base, '', $uri);
        $clean = '/' . ltrim($clean, '/');
        if ($clean === '') $clean = '/';
        if ($path === '/' && ($clean === '/' || $clean === '')) return 'active';
        if ($path !== '/' && strpos($clean, $path) === 0) return 'active';
        return '';
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? $metaTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>">
  <meta name="robots" content="index, follow">
  <meta property="og:title"       content="<?= htmlspecialchars($pageTitle ?? $metaTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDesc) ?>">
  <meta property="og:type"        content="website">
  <?php if ($favicon): ?>
  <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($favicon) ?>">
  <?php endif; ?>
  <!-- Accent palette (CSS vars + Tailwind override window var) -->
  <?= renderAccentTheme($settings) ?>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: Object.assign({
            primary: window.__accentTailwindColors.blue,
            accent:  window.__accentTailwindColors.indigo,
          }, window.__accentTailwindColors),
          fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        }
      }
    }
  </script>
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
  <!-- Theme init — prevent flash -->
  <script>
    (function(){
      var t = localStorage.getItem('smk_theme') || '<?= htmlspecialchars($settings['theme_default'] ?? 'light') ?>';
      if(t==='dark') document.documentElement.classList.add('dark');
    })();
  </script>
  <?php if (!empty($settings['ga_code'])): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($settings['ga_code']) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= htmlspecialchars($settings['ga_code']) ?>');</script>
  <?php endif; ?>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased">

<!-- Preloader -->
<div id="preloader">
  <div class="preloader-spinner"></div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     NAVBAR
     ═══════════════════════════════════════════════════════════ -->
<nav class="sticky top-0 z-50 bg-white dark:bg-slate-900/95 backdrop-blur-xl border-b border-slate-200 dark:border-transparent shadow-sm dark:shadow-none transition-all duration-300" id="mainNav">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Brand -->
      <a href="<?= APP_URL ?>/" class="flex items-center gap-2 flex-shrink-0">
        <?php if ($logo): ?>
          <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($schoolName) ?>" class="h-9 w-auto rounded-lg">
        <?php else: ?>
          <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-sm shadow-lg shadow-blue-500/25">🎓</div>
        <?php endif; ?>
        <span class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white truncate max-w-[140px] sm:max-w-none"><?= htmlspecialchars($schoolName) ?></span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden lg:flex items-center gap-1">
        <a href="<?= APP_URL ?>/" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Beranda</a>

        <!-- Profil Dropdown -->
        <div class="relative group">
          <button class="px-3 py-2 text-sm font-medium rounded-lg transition-all text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 flex items-center gap-1">
            Profil <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
          </button>
          <div class="absolute top-full left-0 mt-1 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 translate-y-1 group-hover:translate-y-0">
            <a href="<?= APP_URL ?>/profil" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600"><i class="fas fa-school w-4 text-blue-500"></i>Tentang Sekolah</a>
            <a href="<?= APP_URL ?>/visi-misi" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600"><i class="fas fa-bullseye w-4 text-blue-500"></i>Visi & Misi</a>
            <a href="<?= APP_URL ?>/sejarah" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600"><i class="fas fa-history w-4 text-blue-500"></i>Sejarah</a>
            <a href="<?= APP_URL ?>/kepala-sekolah" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600"><i class="fas fa-user-tie w-4 text-blue-500"></i>Kepala Sekolah</a>
          </div>
        </div>

        <a href="<?= APP_URL ?>/jurusan" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/jurusan', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Jurusan</a>
        <a href="<?= APP_URL ?>/guru-staff" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/guru-staff', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Guru & Staff</a>
        <a href="<?= APP_URL ?>/berita" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/berita', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Berita</a>
        <a href="<?= APP_URL ?>/galeri" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/galeri', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Galeri</a>
        <a href="<?= APP_URL ?>/prestasi" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/prestasi', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Prestasi</a>
        <a href="<?= APP_URL ?>/kontak" class="px-3 py-2 text-sm font-medium rounded-lg transition-all <?= isActive('/kontak', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">Kontak</a>

        <!-- SPMB Button -->
        <a href="<?= APP_URL ?>/spmb" class="ml-2 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all">
          <i class="fas fa-pencil-alt mr-1"></i>SPMB
        </a>

        <!-- Theme Toggle -->
        <button id="themeToggle" class="ml-2 w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-all" title="Ganti Tema">
          <i class="fas fa-moon theme-icon-light"></i>
          <i class="fas fa-sun theme-icon-dark hidden"></i>
        </button>
      </div>

      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
        <i class="fas fa-bars text-lg" id="menuIcon"></i>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 pb-4">
    <div class="max-w-7xl mx-auto px-4 pt-3 space-y-1">
      <a href="<?= APP_URL ?>/" class="block px-4 py-2.5 rounded-lg text-sm font-medium <?= isActive('/', $uri, $appBase) === 'active' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30' : 'text-slate-700 dark:text-slate-300' ?>">Beranda</a>
      <a href="<?= APP_URL ?>/profil" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Tentang Sekolah</a>
      <a href="<?= APP_URL ?>/jurusan" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Jurusan</a>
      <a href="<?= APP_URL ?>/guru-staff" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Guru & Staff</a>
      <a href="<?= APP_URL ?>/berita" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Berita</a>
      <a href="<?= APP_URL ?>/galeri" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Galeri</a>
      <a href="<?= APP_URL ?>/prestasi" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Prestasi</a>
      <a href="<?= APP_URL ?>/kontak" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">Kontak</a>
      <a href="<?= APP_URL ?>/spmb" class="block px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 text-center mt-2">
        <i class="fas fa-pencil-alt mr-1"></i>SPMB
      </a>
      <div class="flex items-center justify-between px-4 pt-3 pb-1">
        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Mode Gelap</span>
        <button id="themeToggleMobile" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 active:scale-95 transition-all">
          <i class="fas fa-moon theme-icon-light"></i>
          <i class="fas fa-sun theme-icon-dark hidden"></i>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Inline scripts: mobile menu + theme toggle (ensures they work immediately) -->
<script>
(function(){
  // Mobile menu toggle
  var btn = document.getElementById('mobileMenuBtn');
  var menu = document.getElementById('mobileMenu');
  var icon = document.getElementById('menuIcon');
  if(btn && menu){
    btn.addEventListener('click', function(){
      if(menu.classList.contains('hidden')){
        menu.classList.remove('hidden');
        if(icon) icon.className='fas fa-times text-lg';
      } else {
        menu.classList.add('hidden');
        if(icon) icon.className='fas fa-bars text-lg';
      }
    });
  }

  // Theme toggle function
  function doToggleTheme(){
    var isDark = document.documentElement.classList.contains('dark');
    if(isDark){
      document.documentElement.classList.remove('dark');
      localStorage.setItem('smk_theme','light');
    } else {
      document.documentElement.classList.add('dark');
      localStorage.setItem('smk_theme','dark');
    }
    // Sync all theme icons
    var nowDark = document.documentElement.classList.contains('dark');
    document.querySelectorAll('.theme-icon-light').forEach(function(el){ el.style.display = nowDark ? 'none' : ''; });
    document.querySelectorAll('.theme-icon-dark').forEach(function(el){ el.style.display = nowDark ? '' : 'none'; });
  }

  // Attach to all theme toggle buttons
  document.querySelectorAll('#themeToggle, #themeToggleMobile').forEach(function(b){
    b.addEventListener('click', doToggleTheme);
  });

  // Sync icons on load
  var nowDark = document.documentElement.classList.contains('dark');
  document.querySelectorAll('.theme-icon-light').forEach(function(el){ el.style.display = nowDark ? 'none' : ''; });
  document.querySelectorAll('.theme-icon-dark').forEach(function(el){ el.style.display = nowDark ? '' : 'none'; });
})();
</script>

<main>
