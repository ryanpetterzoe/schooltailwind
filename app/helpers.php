<?php

function redirect($url) {
    header("Location: " . APP_URL . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/admin/login');
    }
}

function clean($data) {
    $db = getDB();
    return $db->real_escape_string(htmlspecialchars(strip_tags(trim($data))));
}

function cleanRaw($data) {
    $db = getDB();
    return $db->real_escape_string(trim($data));
}

/**
 * Image compression / resize using GD.
 * - JPEG  -> re-encoded JPEG quality 82
 * - PNG   -> kept PNG (compression level 8) so transparency survives
 * - WEBP  -> re-encoded WebP quality 82
 * - GIF   -> left untouched (may be animated)
 * Resizes so that the longest side is at most $maxSide pixels.
 * Strips EXIF/metadata as a side-effect of re-encoding.
 * Returns true on success, false on failure (caller can leave the original in place).
 */
function compressImage($path, $maxSide = 1920, $quality = 82) {
    if (!extension_loaded('gd') || !file_exists($path)) return false;

    $info = @getimagesize($path);
    if (!$info) return false;
    list($w, $h, $type) = $info;

    // GIFs are kept as-is (preserve animation; GD only renders first frame).
    if ($type === IMAGETYPE_GIF) return true;

    // Load source
    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($path); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($path);  break;
        case IMAGETYPE_WEBP: $src = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null; break;
        default: return false;
    }
    if (!$src) return false;

    // Auto-rotate JPEG using EXIF orientation if available
    if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($path);
        if (!empty($exif['Orientation'])) {
            switch ((int)$exif['Orientation']) {
                case 3: $src = imagerotate($src, 180, 0); break;
                case 6: $src = imagerotate($src, -90, 0); $tmp = $w; $w = $h; $h = $tmp; break;
                case 8: $src = imagerotate($src, 90, 0);  $tmp = $w; $w = $h; $h = $tmp; break;
            }
        }
    }

    // Compute new dimensions (only shrink, never upscale)
    $longest = max($w, $h);
    if ($longest > $maxSide) {
        $ratio  = $maxSide / $longest;
        $newW   = (int)round($w * $ratio);
        $newH   = (int)round($h * $ratio);
        $resized = imagecreatetruecolor($newW, $newH);

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);
        }
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($src);
        $src = $resized;
    } elseif ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
        // Even without resize, keep alpha intact during re-encode
        imagealphablending($src, false);
        imagesavealpha($src, true);
    }

    // Save
    $ok = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $ok = imagejpeg($src, $path, $quality);
            break;
        case IMAGETYPE_PNG:
            // PNG quality is 0 (no compression) - 9 (max)
            $pngLevel = 8;
            $ok = imagepng($src, $path, $pngLevel);
            break;
        case IMAGETYPE_WEBP:
            $ok = function_exists('imagewebp') ? imagewebp($src, $path, $quality) : false;
            break;
    }
    imagedestroy($src);
    return (bool)$ok;
}

function uploadFile($file, $prefix = 'img') {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return '';
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed)) return '';
    $filename = $prefix . '_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $dest = UPLOAD_PATH . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        // Auto-compress (resize >1920px, re-encode, strip EXIF). On failure
        // we keep the original file so upload still succeeds.
        compressImage($dest, 1920, 82);
        return $filename;
    }
    return '';
}

function getSetting($key) {
    $db = getDB();
    $key = $db->real_escape_string($key);
    $res = @$db->query("SELECT value FROM settings WHERE `key` = '$key' LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) return $row['value'];
    return '';
}

function getSettings() {
    $db = getDB();
    $res = @$db->query("SELECT `key`, value FROM settings");
    $data = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[$row['key']] = $row['value'];
        }
    }
    return $data;
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    if ($time < 60) return $time . ' detik lalu';
    if ($time < 3600) return round($time/60) . ' menit lalu';
    if ($time < 86400) return round($time/3600) . ' jam lalu';
    if ($time < 604800) return round($time/86400) . ' hari lalu';
    return date('d M Y', strtotime($datetime));
}

function safeRichHtml($html) {
    if ($html === null || $html === '') return '';
    // Plain text fallback for legacy entries
    if (strip_tags($html) === $html) {
        return nl2br(htmlspecialchars($html));
    }
    // Remove <script>, <style> blocks entirely
    $clean = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html);
    $clean = preg_replace('#<(script|style)\b[^>]*/?>#is', '', $clean);

    // Whitelist iframes from trusted video/social platforms.
    // Replace trusted iframes with a placeholder, strip the rest, then restore.
    $iframePlaceholders = [];
    $trustedDomains = [
        'youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com',
        'player.vimeo.com', 'vimeo.com',
        'www.facebook.com', 'web.facebook.com',
        'www.tiktok.com',
        'www.instagram.com',
        'www.dailymotion.com', 'geo.dailymotion.com',
        'drive.google.com',
    ];
    $clean = preg_replace_callback('#<iframe\b([^>]*)>(.*?)</iframe>#is', function($m) use (&$iframePlaceholders, $trustedDomains) {
        $attrs = $m[1];
        // Extract src
        if (preg_match('#src\s*=\s*["\']([^"\']+)["\']#i', $attrs, $srcMatch)) {
            $src = $srcMatch[1];
            $host = @parse_url($src, PHP_URL_HOST);
            if ($host) {
                foreach ($trustedDomains as $d) {
                    if ($host === $d || substr($host, -(strlen($d)+1)) === '.' . $d) {
                        // Sanitize: keep only src, width, height, frameborder, allow, allowfullscreen, title
                        $safeAttrs = '';
                        if (preg_match('#src\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        elseif (preg_match("#src\s*=\s*'[^']*'#i", $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#width\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#height\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#title\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        $safeAttrs .= ' frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"';
                        // Wrap in responsive container
                        $placeholder = '<!--IFRAME_SAFE_' . count($iframePlaceholders) . '-->';
                        $iframePlaceholders[$placeholder] = '<div class="ql-video-responsive"><iframe' . $safeAttrs . '></iframe></div>';
                        return $placeholder;
                    }
                }
            }
        }
        // Not trusted — strip
        return '';
    }, $clean);

    // Also handle self-closing iframes (Quill sometimes renders <iframe .../>)
    $clean = preg_replace_callback('#<iframe\b([^>]*)/>#is', function($m) use (&$iframePlaceholders, $trustedDomains) {
        $attrs = $m[1];
        if (preg_match('#src\s*=\s*["\']([^"\']+)["\']#i', $attrs, $srcMatch)) {
            $src = $srcMatch[1];
            $host = @parse_url($src, PHP_URL_HOST);
            if ($host) {
                foreach ($trustedDomains as $d) {
                    if ($host === $d || substr($host, -(strlen($d)+1)) === '.' . $d) {
                        $safeAttrs = '';
                        if (preg_match('#src\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        elseif (preg_match("#src\s*=\s*'[^']*'#i", $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#width\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#height\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        if (preg_match('#title\s*=\s*"[^"]*"#i', $attrs, $a)) $safeAttrs .= ' ' . $a[0];
                        $safeAttrs .= ' frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"';
                        $placeholder = '<!--IFRAME_SAFE_' . count($iframePlaceholders) . '-->';
                        $iframePlaceholders[$placeholder] = '<div class="ql-video-responsive"><iframe' . $safeAttrs . '></iframe></div>';
                        return $placeholder;
                    }
                }
            }
        }
        return '';
    }, $clean);

    // Strip any remaining iframes (untrusted)
    $clean = preg_replace('#<iframe\b[^>]*>.*?</iframe>#is', '', $clean);
    $clean = preg_replace('#<iframe\b[^>]*/>#is', '', $clean);

    // Strip inline event handlers (onclick=, onerror=, ...)
    $clean = preg_replace('#\son[a-z]+\s*=\s*"[^"]*"#i', '', $clean);
    $clean = preg_replace("#\son[a-z]+\s*=\s*'[^']*'#i", '', $clean);
    $clean = preg_replace('#\son[a-z]+\s*=\s*[^\s>]+#i', '', $clean);
    // Disallow javascript: URLs
    $clean = preg_replace('#(href|src)\s*=\s*"javascript:[^"]*"#i', '$1="#"', $clean);
    $clean = preg_replace("#(href|src)\s*=\s*'javascript:[^']*'#i", '$1="#"', $clean);

    // Restore trusted iframe placeholders
    if (!empty($iframePlaceholders)) {
        $clean = str_replace(array_keys($iframePlaceholders), array_values($iframePlaceholders), $clean);
    }

    return $clean;
}

/**
 * Plain-text excerpt from a string that may contain HTML. Strips tags,
 * collapses whitespace, then truncates at $len characters. Use for card
 * previews / list pages where rich formatting would break the layout.
 */
function richExcerpt($html, $len = 120) {
    if ($html === null || $html === '') return '';
    // Drop script/style content so the inner text doesn't leak through strip_tags
    $tmp = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html);
    $text = trim(html_entity_decode(strip_tags($tmp), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $text = preg_replace('/\s+/u', ' ', $text);
    if (mb_strlen($text) <= $len) return $text;
    return mb_substr($text, 0, $len);
}

function activeMenu($page, $current) {
    return $page === $current ? 'active' : '';
}

function formatDate($date) {
    $months = ['','Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('j', strtotime($date));
    $m = $months[(int)date('n', strtotime($date))];
    $y = date('Y', strtotime($date));
    return "$d $m $y";
}

function paginate($total, $perPage, $currentPage, $url) {
    $totalPages = ceil($total / $perPage);
    if ($totalPages <= 1) return '';
    $html = '<nav class="pagination-nav"><ul class="pagination">';
    if ($currentPage > 1) {
        $html .= '<li><a href="'.$url.'?page='.($currentPage-1).'">&laquo;</a></li>';
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $currentPage ? ' class="active"' : '';
        $html .= '<li'.$active.'><a href="'.$url.'?page='.$i.'">'.$i.'</a></li>';
    }
    if ($currentPage < $totalPages) {
        $html .= '<li><a href="'.$url.'?page='.($currentPage+1).'">&raquo;</a></li>';
    }
    $html .= '</ul></nav>';
    return $html;
}


/* ============================================================
   ACCENT PALETTE GENERATOR
   Given one hex color (e.g. "#16a34a"), produces a full
   Tailwind-quality 50→900 palette plus an auto-computed
   readable text-on-primary color, so the entire site can be
   re-themed by overriding the `blue`/`indigo` Tailwind palettes.
   ============================================================ */

if (!function_exists('accentPalette')) {
    function accentPalette($hex) {
        $hex = ltrim((string)$hex, '#');
        if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) $hex = '2563eb';

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // RGB -> HSL
        $rN = $r / 255; $gN = $g / 255; $bN = $b / 255;
        $max = max($rN, $gN, $bN); $min = min($rN, $gN, $bN);
        $l = ($max + $min) / 2;
        if ($max == $min) {
            $h = 0; $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            if ($max == $rN)      $h = ($gN - $bN) / $d + ($gN < $bN ? 6 : 0);
            elseif ($max == $gN)  $h = ($bN - $rN) / $d + 2;
            else                  $h = ($rN - $gN) / $d + 4;
            $h /= 6;
        }
        $hDeg = $h * 360;
        $sPct = $s * 100;

        $hsl2rgb = function ($h, $s, $l) {
            $h /= 360; $s /= 100; $l /= 100;
            if ($s == 0) {
                $r = $g = $b = $l;
            } else {
                $h2r = function ($p, $q, $t) {
                    if ($t < 0) $t += 1;
                    if ($t > 1) $t -= 1;
                    if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
                    if ($t < 1/2) return $q;
                    if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
                    return $p;
                };
                $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
                $p = 2 * $l - $q;
                $r = $h2r($p, $q, $h + 1/3);
                $g = $h2r($p, $q, $h);
                $b = $h2r($p, $q, $h - 1/3);
            }
            return [(int)round($r * 255), (int)round($g * 255), (int)round($b * 255)];
        };

        // Tailwind-like shade targets (lightness %, saturation scale)
        $targets = [
            '50'  => [97, 0.55],
            '100' => [93, 0.70],
            '200' => [86, 0.85],
            '300' => [77, 0.95],
            '400' => [64, 1.00],
            '500' => [53, 1.00],
            '600' => [47, 1.00],
            '700' => [39, 0.95],
            '800' => [31, 0.85],
            '900' => [24, 0.75],
        ];

        $palette = [];
        foreach ($targets as $shade => $tt) {
            list($tl, $satScale) = $tt;
            $satAdj = min(100, $sPct * $satScale);
            $palette[$shade] = $hsl2rgb($hDeg, $satAdj, $tl);
        }
        // Anchor shade-600 to user's exact color so the picker preview matches reality
        $palette['600'] = [$r, $g, $b];

        // Readable text-on-primary based on perceived brightness
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $textOn = $luminance > 0.62 ? '15 23 42' : '255 255 255'; // slate-900 vs white

        return [
            'palette'  => $palette,
            'textOn'   => $textOn,
            'hex'      => '#' . strtolower($hex),
            'isLight'  => $luminance > 0.62,
        ];
    }
}

/**
 * Render <style> + <script> snippet that re-themes Tailwind's `blue`
 * and `indigo` color scales to use the school's accent colors.
 * Called from header.php and admin_header.php BEFORE the Tailwind CDN script.
 */
if (!function_exists('renderAccentTheme')) {
    function renderAccentTheme(array $settings) {
        $primaryHex = $settings['accent_primary'] ?? '#2563eb';
        $darkHex    = $settings['accent_dark']    ?? '#1d4ed8';

        $primary = accentPalette($primaryHex);
        $dark    = accentPalette($darkHex);

        // Build CSS variables: --c-blue-50..900, --c-blue-text, --c-indigo-50..900, --c-indigo-text
        $vars = [];
        foreach ($primary['palette'] as $shade => $rgb) {
            $vars[] = sprintf('--c-blue-%s: %d %d %d;', $shade, $rgb[0], $rgb[1], $rgb[2]);
        }
        $vars[] = '--c-blue-text: ' . $primary['textOn'] . ';';
        foreach ($dark['palette'] as $shade => $rgb) {
            $vars[] = sprintf('--c-indigo-%s: %d %d %d;', $shade, $rgb[0], $rgb[1], $rgb[2]);
        }
        $vars[] = '--c-indigo-text: ' . $dark['textOn'] . ';';
        // Backward-compat aliases used by 404 page and any custom CSS
        $p600 = $primary['palette']['600'];
        $d700 = $dark['palette']['700'];
        $vars[] = sprintf('--primary: rgb(%d %d %d);', $p600[0], $p600[1], $p600[2]);
        $vars[] = sprintf('--primary-dark: rgb(%d %d %d);', $d700[0], $d700[1], $d700[2]);
        $vars[] = '--text-on-primary: rgb(var(--c-blue-text));';

        // Tailwind config snippet (overrides `blue` and `indigo` palettes)
        $shadeKeys = ['50','100','200','300','400','500','600','700','800','900'];
        $blueObj = []; $indigoObj = [];
        foreach ($shadeKeys as $k) {
            $blueObj[]   = "$k:'rgb(var(--c-blue-$k) / <alpha-value>)'";
            $indigoObj[] = "$k:'rgb(var(--c-indigo-$k) / <alpha-value>)'";
        }
        $blueJs   = '{' . implode(',', $blueObj) . '}';
        $indigoJs = '{' . implode(',', $indigoObj) . '}';

        $css = ":root{" . implode('', $vars) . "}";
        // Force text-white inside accent backgrounds to use auto-readable color
        $css .= "
:is(.bg-blue-500,.bg-blue-600,.bg-blue-700,.bg-blue-800,.bg-blue-900,.bg-indigo-500,.bg-indigo-600,.bg-indigo-700,.bg-indigo-800,.bg-indigo-900) .text-white,
:is(.bg-blue-500,.bg-blue-600,.bg-blue-700,.bg-blue-800,.bg-blue-900,.bg-indigo-500,.bg-indigo-600,.bg-indigo-700,.bg-indigo-800,.bg-indigo-900).text-white,
[class*='from-blue-'] .text-white,[class*='from-blue-'].text-white,
[class*='to-blue-'] .text-white,[class*='to-blue-'].text-white,
[class*='from-indigo-'] .text-white,[class*='from-indigo-'].text-white,
[class*='to-indigo-'] .text-white,[class*='to-indigo-'].text-white{
    color: rgb(var(--c-blue-text)) !important;
}
";

        $tailwindOverride = <<<JS
window.__accentTailwindColors = {
  blue: $blueJs,
  indigo: $indigoJs
};
JS;

        return "<style id=\"accent-theme-vars\">$css</style>\n<script>$tailwindOverride</script>";
    }
}



/* ============================================================
   AUTO-MIGRATION
   For features added after the initial install (so existing
   users don't need to re-run the installer). Idempotent —
   `CREATE TABLE IF NOT EXISTS` is a no-op if it already exists.
   ============================================================ */
if (!function_exists('ensureProgramImagesTable')) {
    function ensureProgramImagesTable() {
        static $checked = false;
        if ($checked) return;
        $checked = true;
        $db = getDB();
        if (!$db) return;
        @$db->query("CREATE TABLE IF NOT EXISTS `program_images` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `program_id` INT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            `caption` VARCHAR(250) DEFAULT NULL,
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_program_images_program` (`program_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}

/**
 * Fetch all gallery images attached to a program, ordered.
 * Returns array of rows or [].
 */
if (!function_exists('getProgramImages')) {
    function getProgramImages($programId) {
        ensureProgramImagesTable();
        $db = getDB();
        $programId = (int)$programId;
        $res = @$db->query("SELECT * FROM program_images WHERE program_id=$programId ORDER BY sort_order ASC, id ASC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}



/* ============================================================
   FACILITIES (modul baru)
   facilities = item fasilitas sekolah (Lab, WiFi, dll)
   facility_images = foto-foto pendukung tiap fasilitas (multi-upload)
   Auto-create kedua tabel saat method ini dipanggil pertama kali, jadi
   site yang sudah running pre-feature tidak perlu re-run installer.
   ============================================================ */
if (!function_exists('ensureFacilitiesSchema')) {
    function ensureFacilitiesSchema() {
        static $checked = false;
        if ($checked) return;
        $checked = true;
        $db = getDB();
        if (!$db) return;
        @$db->query("CREATE TABLE IF NOT EXISTS `facilities` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(200) NOT NULL,
            `description` LONGTEXT,
            `image` VARCHAR(255) DEFAULT NULL,
            `icon` VARCHAR(100) DEFAULT 'fas fa-building',
            `is_active` TINYINT(1) DEFAULT 1,
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        @$db->query("CREATE TABLE IF NOT EXISTS `facility_images` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `facility_id` INT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            `caption` VARCHAR(250) DEFAULT NULL,
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_facility_images_facility` (`facility_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Pre-seed 6 default facilities on first run, only if the table
        // was just created and is empty. Replicates the items that used
        // to be hardcoded inside views/public/about.php so existing
        // installations keep showing something useful out of the box.
        $cntRes = @$db->query("SELECT COUNT(*) AS c FROM facilities");
        if ($cntRes) {
            $row = $cntRes->fetch_assoc();
            if ($row && (int)$row['c'] === 0) {
                $defaults = [
                    ['Lab Komputer',      '<p>Laboratorium komputer modern dengan spesifikasi terkini untuk praktik pemrograman, jaringan, dan multimedia.</p>', 'fas fa-desktop',  1],
                    ['Internet WiFi',     '<p>Akses internet cepat di seluruh area sekolah untuk mendukung kegiatan belajar mengajar berbasis digital.</p>',      'fas fa-wifi',     2],
                    ['Perpustakaan',      '<p>Koleksi buku lengkap dan ruang baca yang nyaman, mendukung literasi siswa di luar jam pelajaran.</p>',           'fas fa-book',     3],
                    ['Lapangan Olahraga', '<p>Sarana olahraga lengkap untuk berbagai kegiatan: bola, voli, basket, dan upacara.</p>',                          'fas fa-futbol',   4],
                    ['Laboratorium',      '<p>Lab sains dan teknologi yang modern, untuk mendukung praktik mata pelajaran kejuruan.</p>',                       'fas fa-flask',    5],
                    ['Kantin Sehat',      '<p>Kantin dengan makanan bergizi dan higienis, ramah kantong siswa.</p>',                                            'fas fa-utensils', 6],
                ];
                foreach ($defaults as $d) {
                    $name = $db->real_escape_string($d[0]);
                    $desc = $db->real_escape_string($d[1]);
                    $icon = $db->real_escape_string($d[2]);
                    $sort = (int)$d[3];
                    @$db->query("INSERT INTO `facilities` (`name`,`description`,`icon`,`sort_order`,`is_active`) VALUES ('$name','$desc','$icon',$sort,1)");
                }
            }
        }
    }
}

/**
 * Fetch all images attached to a facility, ordered. Returns [] if none.
 */
if (!function_exists('getFacilityImages')) {
    function getFacilityImages($facilityId) {
        ensureFacilitiesSchema();
        $db = getDB();
        $facilityId = (int)$facilityId;
        $res = @$db->query("SELECT * FROM facility_images WHERE facility_id=$facilityId ORDER BY sort_order ASC, id ASC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

/**
 * Fetch active facilities (with their image counts) for the public
 * "Profil > Fasilitas" tab. Each row carries an extra `image_count`
 * key so the view can decide whether to show a slideshow vs single
 * cover. Triggers schema auto-create on first call.
 */
if (!function_exists('getActiveFacilities')) {
    function getActiveFacilities() {
        ensureFacilitiesSchema();
        $db = getDB();
        if (!$db) return [];
        $res = @$db->query("SELECT f.*, (SELECT COUNT(*) FROM facility_images fi WHERE fi.facility_id=f.id) AS image_count
                            FROM facilities f
                            WHERE f.is_active=1
                            ORDER BY f.sort_order ASC, f.id ASC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}
