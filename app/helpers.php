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

function uploadFile($file, $prefix = 'img') {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return '';
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (!in_array($ext, $allowed)) return '';
    $filename = $prefix . '_' . time() . '_' . rand(1000,9999) . '.' . $ext;
    $dest = UPLOAD_PATH . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
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
