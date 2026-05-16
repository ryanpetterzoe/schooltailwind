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
