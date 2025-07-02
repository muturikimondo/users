<?php
// finance/auth/logout.php

session_start();

// Clear session variables
$_SESSION = [];
session_unset();
session_destroy();

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, "/");
}

// Redirect to login
require_once '../includes/config.php';
header('Location: ' . BASE_URL . 'auth/login.php');
exit;
