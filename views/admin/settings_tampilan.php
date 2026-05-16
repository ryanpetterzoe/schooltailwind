<?php $adminPageTitle = 'Pengaturan Tampilan'; require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 sm:p-8">

        <div class="flex items-start justify-between flex-wrap gap-3 mb-6">
            <div>
                <h5 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-palette text-blue-600"></i>Pengaturan Warna &amp; Tampilan
                </h5>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pilih warna khas sekolah. Seluruh tombol, link, gradient, badge dan navigasi otomatis ikut. Teks di atas warna akan menyesuaikan agar tetap terbaca.</p>
            </div>
            <button type="button" id="resetBtn" class="px-3 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <i class="fas fa-undo mr-1"></i> Reset Default
            </button>
        </div>

        <form method="POST" action="<?= APP_URL ?>/admin/settings/tampilan" id="tampilanForm">
            <input type="hidden" name="_token" value="<?= htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '') ?>">

            <div class="grid lg:grid-cols-2 gap-8">

                <!-- LEFT: CONTROLS -->
                <div class="space-y-6">

                    <!-- Preset Sekolah Indonesia -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                            <i class="fas fa-school text-blue-600 mr-1"></i>Preset Tema Sekolah
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <?php
                            $presets = [
                                ['name'=>'Biru Klasik',    'primary'=>'#2563eb','dark'=>'#1d4ed8'],
                                ['name'=>'Hijau NU',       'primary'=>'#15803d','dark'=>'#14532d'],
                                ['name'=>'Hijau Tosca',    'primary'=>'#0d9488','dark'=>'#0f766e'],
                                ['name'=>'Merah',          'primary'=>'#dc2626','dark'=>'#991b1b'],
                                ['name'=>'Kuning Emas',    'primary'=>'#ca8a04','dark'=>'#854d0e'],
                                ['name'=>'Oranye',         'primary'=>'#ea580c','dark'=>'#c2410c'],
                                ['name'=>'Ungu Royal',     'primary'=>'#7c3aed','dark'=>'#5b21b6'],
                                ['name'=>'Pink',           'primary'=>'#db2777','dark'=>'#9d174d'],
                                ['name'=>'Hitam Elegan',   'primary'=>'#1f2937','dark'=>'#0f172a'],
                            ];
                            $currentPrimary = strtolower($settings['accent_primary'] ?? '#2563eb');
                            foreach ($presets as $p):
                                $isSelected = strtolower($p['primary']) === $currentPrimary;
                            ?>
                            <button type="button"
                                    class="preset-btn group relative flex items-center gap-2 px-3 py-2.5 rounded-lg border-2 text-left transition-all hover:shadow-md <?= $isSelected ? 'border-slate-400 dark:border-slate-300 ring-2 ring-offset-2 ring-offset-white dark:ring-offset-slate-800 ring-slate-300' : 'border-slate-200 dark:border-slate-700' ?>"
                                    data-primary="<?= $p['primary'] ?>"
                                    data-dark="<?= $p['dark'] ?>">
                                <span class="w-7 h-7 rounded-md shadow-sm flex-shrink-0" style="background:linear-gradient(135deg,<?= $p['primary'] ?>,<?= $p['dark'] ?>);"></span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate"><?= $p['name'] ?></span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Custom Colors -->
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                            <i class="fas fa-eye-dropper text-blue-600 mr-1"></i>Warna Kustom
                        </label>

                        <!-- Primary -->
                        <div class="mb-4">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Warna Utama</p>
                            <p class="text-[11px] text-slate-400 mb-2">Tombol, link, badge, ikon aktif.</p>
                            <div class="flex items-center gap-2">
                                <input type="color"
                                       id="accent_primary_color"
                                       name="accent_primary"
                                       class="w-14 h-11 p-0.5 rounded-lg cursor-pointer border border-slate-200 dark:border-slate-700"
                                       value="<?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?>">
                                <input type="text"
                                       id="accent_primary_hex"
                                       class="flex-1 px-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="<?= htmlspecialchars($settings['accent_primary'] ?? '#2563eb') ?>"
                                       placeholder="#2563eb"
                                       maxlength="7">
                            </div>
                        </div>

                        <!-- Dark variant -->
                        <div>
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Warna Sekunder (Gelap)</p>
                            <p class="text-[11px] text-slate-400 mb-2">Hover, ujung gradient, dark mode accent.</p>
                            <div class="flex items-center gap-2">
                                <input type="color"
                                       id="accent_dark_color"
                                       name="accent_dark"
                                       class="w-14 h-11 p-0.5 rounded-lg cursor-pointer border border-slate-200 dark:border-slate-700"
                                       value="<?= htmlspecialchars($settings['accent_dark'] ?? '#1d4ed8') ?>">
                                <input type="text"
                                       id="accent_dark_hex"
                                       class="flex-1 px-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       value="<?= htmlspecialchars($settings['accent_dark'] ?? '#1d4ed8') ?>"
                                       placeholder="#1d4ed8"
                                       maxlength="7">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2"><i class="fas fa-magic mr-1"></i>Tip: kosongkan / klik <em>Hitung Otomatis</em> untuk derive dari warna utama.</p>
                            <button type="button" id="autoDeriveBtn" class="mt-1 text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                <i class="fas fa-wand-magic-sparkles mr-1"></i>Hitung varian gelap otomatis
                            </button>
                        </div>
                    </div>

                    <!-- Default Theme -->
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                            <i class="fas fa-circle-half-stroke text-blue-600 mr-1"></i>Tema Default Pengunjung
                        </label>
                        <p class="text-xs text-slate-400 mb-3">Mode default untuk pengunjung baru sebelum mereka toggle manual.</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="radio" name="theme_default" value="light" class="text-blue-600 focus:ring-blue-500" <?= (($settings['theme_default'] ?? 'light') === 'light') ? 'checked' : '' ?>>
                                <i class="fas fa-sun text-amber-400"></i>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Light</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="radio" name="theme_default" value="dark" class="text-blue-600 focus:ring-blue-500" <?= (($settings['theme_default'] ?? 'light') === 'dark') ? 'checked' : '' ?>>
                                <i class="fas fa-moon text-indigo-500"></i>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Dark</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

                <!-- RIGHT: LIVE PREVIEW -->
                <div>
                    <div class="sticky top-20">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <i class="fas fa-eye mr-1"></i>Live Preview
                            </span>
                            <span id="readabilityBadge" class="text-[11px] px-2 py-0.5 rounded-full font-semibold"></span>
                        </div>
                        <div id="previewSurface" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 space-y-5 transition-all">
                            <!-- Mock card with gradient -->
                            <div class="rounded-xl p-5 text-white shadow-lg" id="pv-gradient" style="background:linear-gradient(135deg,var(--pv-primary),var(--pv-dark));color:var(--pv-text);">
                                <div class="text-[11px] font-bold uppercase tracking-wider opacity-80">SMK Pertamaku</div>
                                <div class="text-lg font-extrabold mt-1">Selamat Datang!</div>
                                <div class="text-xs opacity-90 mt-1">Tombol, gradient, dan teks di atas warna akan tampak seperti ini di seluruh website.</div>
                                <div class="flex gap-2 mt-3">
                                    <span class="px-3 py-1.5 rounded-lg text-[11px] font-bold bg-white/15 backdrop-blur-sm">Akreditasi A</span>
                                    <span class="px-3 py-1.5 rounded-lg text-[11px] font-bold bg-white/15 backdrop-blur-sm">NPSN 12345</span>
                                </div>
                            </div>

                            <!-- Buttons row -->
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm" style="background:var(--pv-primary);color:var(--pv-text);">Tombol Utama</button>
                                <button type="button" class="px-4 py-2 rounded-lg text-sm font-bold shadow-sm" style="background:var(--pv-dark);color:var(--pv-text-dark);">Tombol Hover</button>
                                <button type="button" class="px-4 py-2 rounded-lg text-sm font-semibold border-2" style="border-color:var(--pv-primary);color:var(--pv-primary);">Tombol Outline</button>
                            </div>

                            <!-- Link & badge row -->
                            <div class="flex items-center gap-3 flex-wrap">
                                <a href="javascript:void(0)" class="text-sm font-semibold underline-offset-2 hover:underline" style="color:var(--pv-primary);">Link teks</a>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full" style="background:var(--pv-bg-soft);color:var(--pv-primary);">Kategori</span>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full" style="background:var(--pv-primary);color:var(--pv-text);">Featured</span>
                            </div>

                            <!-- Card sample -->
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-100 dark:border-slate-700">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-md" style="background:var(--pv-primary);color:var(--pv-text);">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-slate-800 dark:text-white">Kartu Konten</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ikon, judul, dan tombol mini.</div>
                                        <button type="button" class="mt-2 text-xs font-semibold inline-flex items-center gap-1" style="color:var(--pv-primary);">
                                            Selengkapnya <i class="fas fa-arrow-right text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hex display -->
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="rounded-lg py-2 px-3" style="background:var(--pv-primary);color:var(--pv-text);">
                                    <div class="text-[10px] uppercase tracking-wider opacity-80">Primary</div>
                                    <div class="text-xs font-mono font-bold" id="pv-primary-hex">#2563eb</div>
                                </div>
                                <div class="rounded-lg py-2 px-3" style="background:var(--pv-dark);color:var(--pv-text-dark);">
                                    <div class="text-[10px] uppercase tracking-wider opacity-80">Dark</div>
                                    <div class="text-xs font-mono font-bold" id="pv-dark-hex">#1d4ed8</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    var primaryColor = document.getElementById('accent_primary_color');
    var primaryHex   = document.getElementById('accent_primary_hex');
    var darkColor    = document.getElementById('accent_dark_color');
    var darkHex      = document.getElementById('accent_dark_hex');
    var pvSurface    = document.getElementById('previewSurface');
    var pvPrimaryLbl = document.getElementById('pv-primary-hex');
    var pvDarkLbl    = document.getElementById('pv-dark-hex');
    var readBadge    = document.getElementById('readabilityBadge');

    function clamp(v, a, b){ return Math.max(a, Math.min(b, v)); }
    function isHex(v){ return /^#[0-9a-fA-F]{6}$/.test(v); }
    function hexToRgb(h){
        h = h.replace('#','');
        return { r:parseInt(h.slice(0,2),16), g:parseInt(h.slice(2,4),16), b:parseInt(h.slice(4,6),16) };
    }
    function luminance(rgb){
        return (0.299*rgb.r + 0.587*rgb.g + 0.114*rgb.b) / 255;
    }
    function readableText(hex){
        return luminance(hexToRgb(hex)) > 0.62 ? '#0f172a' : '#ffffff';
    }
    // Derive a darker variant by reducing lightness in HSL
    function darken(hex, amount){
        var rgb = hexToRgb(hex);
        var r = rgb.r/255, g = rgb.g/255, b = rgb.b/255;
        var max = Math.max(r,g,b), min = Math.min(r,g,b);
        var h, s, l = (max+min)/2;
        if (max===min){ h=0; s=0; }
        else {
            var d = max-min;
            s = l > 0.5 ? d/(2-max-min) : d/(max+min);
            if (max===r) h=(g-b)/d + (g<b?6:0);
            else if (max===g) h=(b-r)/d + 2;
            else h=(r-g)/d + 4;
            h /= 6;
        }
        l = clamp(l - amount, 0.05, 0.95);
        function h2r(p,q,t){ if(t<0)t+=1; if(t>1)t-=1;
            if(t<1/6) return p+(q-p)*6*t;
            if(t<1/2) return q;
            if(t<2/3) return p+(q-p)*(2/3-t)*6;
            return p;
        }
        var q = l < 0.5 ? l*(1+s) : l+s-l*s;
        var p = 2*l - q;
        var nr = h2r(p,q,h+1/3), ng = h2r(p,q,h), nb = h2r(p,q,h-1/3);
        var toHex = function(x){ var s = Math.round(x*255).toString(16); return s.length===1?'0'+s:s; };
        return '#' + toHex(nr) + toHex(ng) + toHex(nb);
    }

    // Soft tinted background (like bg-blue-50)
    function softTint(hex){
        var rgb = hexToRgb(hex);
        return 'rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.10)';
    }

    function applyPreview(){
        var p = primaryColor.value, d = darkColor.value;
        var pTxt = readableText(p), dTxt = readableText(d);
        pvSurface.style.setProperty('--pv-primary', p);
        pvSurface.style.setProperty('--pv-dark', d);
        pvSurface.style.setProperty('--pv-text', pTxt);
        pvSurface.style.setProperty('--pv-text-dark', dTxt);
        pvSurface.style.setProperty('--pv-bg-soft', softTint(p));
        if (pvPrimaryLbl) pvPrimaryLbl.textContent = p.toUpperCase();
        if (pvDarkLbl)    pvDarkLbl.textContent    = d.toUpperCase();

        // Readability badge (contrast hint)
        if (readBadge){
            var lum = luminance(hexToRgb(p));
            if (lum > 0.62) {
                readBadge.textContent = 'Teks gelap (otomatis)';
                readBadge.className = 'text-[11px] px-2 py-0.5 rounded-full font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300';
            } else {
                readBadge.textContent = 'Teks putih (otomatis)';
                readBadge.className = 'text-[11px] px-2 py-0.5 rounded-full font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300';
            }
        }
    }

    function syncPair(colorEl, hexEl){
        colorEl.addEventListener('input', function(){
            hexEl.value = colorEl.value.toUpperCase();
            applyPreview();
        });
        hexEl.addEventListener('input', function(){
            var v = hexEl.value.trim();
            if (v && v[0] !== '#') v = '#' + v;
            if (isHex(v)) {
                colorEl.value = v.toLowerCase();
                applyPreview();
            }
        });
        hexEl.addEventListener('blur', function(){
            hexEl.value = colorEl.value.toUpperCase();
        });
    }
    syncPair(primaryColor, primaryHex);
    syncPair(darkColor, darkHex);

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            primaryColor.value = btn.getAttribute('data-primary');
            darkColor.value    = btn.getAttribute('data-dark');
            primaryHex.value   = primaryColor.value.toUpperCase();
            darkHex.value      = darkColor.value.toUpperCase();
            // Visual selection state
            document.querySelectorAll('.preset-btn').forEach(function(b){
                b.classList.remove('border-slate-400','dark:border-slate-300','ring-2','ring-offset-2','ring-offset-white','dark:ring-offset-slate-800','ring-slate-300');
                b.classList.add('border-slate-200','dark:border-slate-700');
            });
            btn.classList.remove('border-slate-200','dark:border-slate-700');
            btn.classList.add('border-slate-400','dark:border-slate-300','ring-2','ring-offset-2','ring-offset-white','dark:ring-offset-slate-800','ring-slate-300');
            applyPreview();
        });
    });

    // Auto-derive dark variant
    document.getElementById('autoDeriveBtn').addEventListener('click', function(){
        darkColor.value = darken(primaryColor.value, 0.13);
        darkHex.value   = darkColor.value.toUpperCase();
        applyPreview();
    });

    // Reset
    document.getElementById('resetBtn').addEventListener('click', function(){
        primaryColor.value = '#2563eb';
        darkColor.value    = '#1d4ed8';
        primaryHex.value   = '#2563EB';
        darkHex.value      = '#1D4ED8';
        applyPreview();
    });

    applyPreview();
})();
</script>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
