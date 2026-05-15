<?php
/**
 * SMK Pertamaku Website
 * Main entry point - bootstraps config and routes
 */

// Redirect to installer if not yet installed
if (!file_exists(__DIR__ . '/install.lock') && !file_exists(__DIR__ . '/config/env.php')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    header('Location: ' . $scheme . '://' . $host . $dir . '/install.php');
    exit;
}

// Load app configuration (session, DB, helpers)
require_once __DIR__ . '/config/app.php';

// Load and execute router
require_once __DIR__ . '/routes/web.php';
