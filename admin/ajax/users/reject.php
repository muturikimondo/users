<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

header('Content-Type: application/json');

$user_id = intval($_POST['user_id'] ?? 0);

if ($user_id <= 0) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid user ID.']);
  exit;
}

// Confirm the user exists and is pending
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND status = 'pending' AND is_disabled = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
  echo json_encode(['status' => 'error', 'message' => 'User not found or already processed.']);
  exit;
}
$stmt->close();

// Reject the user
$update = $conn->prepare("UPDATE users SET status = 'rejected', updated_at = NOW() WHERE id = ?");
$update->bind_param("i", $user_id);

if ($update->execute()) {
  echo json_encode(['status' => 'success']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Rejection failed.']);
}
