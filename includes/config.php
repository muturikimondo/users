<?php
// ----------------------------------------
// BASE PATH & URL CONFIGURATION
// ----------------------------------------

define('BASE_PATH', realpath(__DIR__ . '/../') . '/');

// Ensure BASE_URL always points to /coop/
$baseRelativePath = '/coop/'; // <-- this is now correct for your folder structure

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . $host . $baseRelativePath);

// ----------------------------------------
// ASSET HELPER FUNCTION
// ----------------------------------------
function asset($path) {
    return BASE_URL . ltrim($path, '/');
}

// ----------------------------------------
// BRANDING & METADATA
// ----------------------------------------

$site_title         = "Records Portal";
$site_tagline       = "Bring Every Detail Together";
$cta_button_text    = "Visit the Website";
$cta_button_link    = "https://laikipia.go.ke";
$chief_officer_email = "jgatweku@gmail.com";

$logoPath = 'core/users/uploads/logo/logo.png';

// ----------------------------------------
// BRAND COLORS FOR THEMING
// ----------------------------------------
$brand_colors = [
    'primary' => '#00632d',
    'accent'  => '#02a554',
    'dark'    => '#432d14',
    'beige'   => '#c2aa8d',
    'light'   => '#f2e1d7'
];

// ----------------------------------------
// NAVIGATION MENU (CAN BE USED IN HEADER)
// ----------------------------------------
$nav_items = [
    ['label' => 'Home',           'link' => asset('index.php')],
    ['label' => 'Assets',         'link' => '#'],
    ['label' => 'Classes',        'link' => '#'],
    ['label' => 'Teachers',       'link' => '#'],
    ['label' => 'Infrastructure', 'link' => '#']
];

// ----------------------------------------
// ACTIVE LINK DETECTION FOR NAVIGATION
// ----------------------------------------
function isActive($link) {
    $current = $_SERVER['PHP_SELF'];
    return strpos($current, $link) !== false ? 'active' : '';
}

// ----------------------------------------
// INCLUDE DATABASE CONNECTION
// ----------------------------------------
require_once BASE_PATH . 'includes/db.php';
