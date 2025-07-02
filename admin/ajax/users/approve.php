<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');



require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

//header('Content-Type: application/json');

$user_id = intval($_POST['user_id'] ?? 0);
$role_id = intval($_POST['role_id'] ?? 0);

// Prepare a debug array to pass back to JS
$debug = [
  'received_user_id' => $user_id,
  'received_role_id' => $role_id,
];

// Validate input
if ($user_id <= 0 || $role_id <= 0) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Invalid user or role.',
    'debug' => $debug
  ]);
  exit;
}

// Confirm user exists and is pending
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND status = 'pending' AND is_disabled = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  echo json_encode([
    'status' => 'error',
    'message' => 'User not found or already approved.',
    'debug' => $debug
  ]);
  $stmt->close();
  exit;
}
$stmt->close();

// Approve user
$update = $conn->prepare("UPDATE users SET status = 'approved', role_id = ?, updated_at = NOW() WHERE id = ?");
$update->bind_param("ii", $role_id, $user_id);

if ($update->execute()) {
  echo json_encode([
    'status' => 'success',
    'message' => 'User approved successfully.',
    'debug' => $debug
  ]);
} else {
  echo json_encode([
    'status' => 'error',
    'message' => 'Database update failed.',
    'debug' => array_merge($debug, ['db_error' => $update->error])
  ]);
}
