<?php
// ----------------------------------------
// DATABASE CONFIGURATION
// ----------------------------------------

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '254KeNYA&&!!';
$DB_NAME = 'coop'; // Updated from 'fin' to 'coop'
$DB_PORT = 3306;

// ----------------------------------------
// CONNECT TO DATABASE
// ----------------------------------------

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// Error handling
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("A database error occurred. Please contact the administrator.");
}

// Set proper charset
$conn->set_charset("utf8mb4");
