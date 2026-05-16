<?php
$schoolName = getSetting('school_name') ?: 'SMK Pertamaku';
$schoolLogo = getSetting('school_logo');
$loginLogo  = !empty($schoolLogo) ? UPLOAD_URL . $schoolLogo : '';
$csrfToken  = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
$postUser   = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - <?= htmlspecialchars($schoolName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config={darkMode:'class'}</script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950 flex items-center justify-center font-[Inter,system-ui,sans-serif] p-6 relative">
  <!-- BG Glow -->
  <div class="fixed inset-0 pointer-events-none">
    <div class="absolute w-96 h-96 bg-blue-500/10 rounded-full -top-32 left-1/4 blur-3xl"></div>
    <div class="absolute w-64 h-64 bg-indigo-500/10 rounded-full bottom-0 right-1/4 blur-3xl"></div>
  </div>

  <div class="w-full max-w-md bg-white/5 backdrop-blur-2xl border border-white/10 rounded-2xl p-8 sm:p-10 relative z-10 shadow-2xl">
    <!-- Logo -->
    <div class="text-center mb-7">
      <?php if ($loginLogo): ?>
      <div class="w-20 h-20 bg-white/95 rounded-2xl flex items-center justify-center mx-auto mb-4 p-2 shadow-lg shadow-blue-600/30">
        <img src="<?= htmlspecialchars($loginLogo) ?>" alt="<?= htmlspecialchars($schoolName) ?>"
             class="max-w-full max-h-full object-contain" loading="eager" decoding="async">
      </div>
      <?php else: ?>
      <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg shadow-blue-600/30">🎓</div>
      <?php endif; ?>
      <h4 class="text-white font-bold text-lg"><?= htmlspecialchars($schoolName) ?></h4>
      <p class="text-slate-400 text-sm">Panel Administrasi</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-300 text-sm flex items-center gap-2">
      <i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-xl text-green-300 text-sm flex items-center gap-2">
      <i class="fas fa-check-circle"></i><?= htmlspecialchars($_SESSION['flash_success']) ?>
    </div>
    <?php unset($_SESSION['flash_success']); endif; ?>

    <form method="POST" action="<?= APP_URL ?>/admin/login">
      <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="mb-4">
        <label class="block text-slate-300 text-sm font-semibold mb-1.5">Username</label>
        <div class="flex">
          <span class="px-3 flex items-center bg-white/5 border border-white/10 border-r-0 rounded-l-xl text-slate-500"><i class="fas fa-user"></i></span>
          <input type="text" name="username" value="<?= $postUser ?>" placeholder="Masukkan username" required autofocus autocomplete="username"
                 class="flex-1 px-4 py-3 bg-white/5 border border-white/10 rounded-r-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-slate-300 text-sm font-semibold mb-1.5">Password</label>
        <div class="flex">
          <span class="px-3 flex items-center bg-white/5 border border-white/10 border-r-0 rounded-l-xl text-slate-500"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="pwdInput" placeholder="Masukkan password" required autocomplete="current-password"
                 class="flex-1 px-4 py-3 bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          <button type="button" onclick="togglePwd()" class="px-3 bg-white/5 border border-white/10 border-l-0 rounded-r-xl text-slate-500 hover:text-slate-300 transition-colors">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="flex items-center mb-6">
        <input type="checkbox" name="remember" id="rememberMe" class="w-4 h-4 rounded border-white/20 bg-white/5 text-blue-600 focus:ring-blue-500">
        <label for="rememberMe" class="ml-2 text-sm text-slate-400">Ingat Saya</label>
      </div>

      <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 hover:shadow-blue-600/40 transition-all">
        <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Admin
      </button>
    </form>

    <div class="text-center mt-5">
      <a href="<?= APP_URL ?>/" class="text-slate-500 text-sm hover:text-slate-300 transition-colors">
        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Website
      </a>
    </div>
  </div>

  <script>
  function togglePwd(){var i=document.getElementById('pwdInput'),e=document.getElementById('eyeIcon');if(i.type==='password'){i.type='text';e.className='fas fa-eye-slash';}else{i.type='password';e.className='fas fa-eye';}}
  </script>
</body>
</html>
