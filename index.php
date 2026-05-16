<?php
/**
 * SMK Pertamaku Website
 * Main entry point - bootstraps config and routes
 */

/* ── Auto-detect existing installation ──────────────────────────
 * If install.lock is missing but env.php points to a working DB
 * with at least one admin user, the site has clearly been
 * installed before (e.g. user just overwrote the project files
 * onto an existing XAMPP install). Recreate the lock silently
 * instead of forcing them through the installer again.
 * If the env file or DB really is missing/broken → fall through
 * to the installer redirect. */
if (!file_exists(__DIR__ . '/install.lock')) {
    $alreadyInstalled = false;
    if (file_exists(__DIR__ . '/config/env.php')) {
        require_once __DIR__ . '/config/env.php';
        if (defined('DB_HOST') && defined('DB_USER') && defined('DB_NAME')) {
            $dbPort = defined('DB_PORT') ? (int)DB_PORT : 3306;
            $probe  = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, $dbPort);
            if (!$probe->connect_error) {
                $rs = @$probe->query("SELECT COUNT(*) AS c FROM admins");
                if ($rs) {
                    $row = $rs->fetch_assoc();
                    if ($row && (int)$row['c'] > 0) {
                        $alreadyInstalled = true;
                    }
                }
                $probe->close();
            }
        }
    }
    if ($alreadyInstalled) {
        @file_put_contents(__DIR__ . '/install.lock', date('Y-m-d H:i:s') . " (auto-detected)");
    } else {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        header('Location: ' . $scheme . '://' . $host . $dir . '/install.php');
        exit;
    }
}

// Load app configuration (session, DB, helpers)
require_once __DIR__ . '/config/app.php';

// Load and execute router
require_once __DIR__ . '/routes/web.php';
