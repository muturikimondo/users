<?php
// coop/auth/ajax/register/check_email.php

header('Content-Type: application/json');

// Load config and DB connection
require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['available' => false, 'error' => 'Invalid request method']);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'error' => 'Invalid email format']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

$isTaken = $stmt->num_rows > 0;
$stmt->close();

echo json_encode([
    'available' => !$isTaken,
    'message'   => $isTaken ? 'Email already registered' : 'Email is available'
]);
