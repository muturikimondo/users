<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';
require_once BASE_PATH . 'includes/functions/pagination.php';

$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Count total pending users
$countStmt = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'pending' AND is_disabled = 0");
$totalRows = $countStmt ? ($countStmt->fetch_assoc()['total'] ?? 0) : 0;
$totalPages = ceil($totalRows / $limit);

// Fetch current page users
$stmt = $conn->prepare("
  SELECT id, username, email, photo, status 
  FROM users 
  WHERE status = 'pending' AND is_disabled = 0 
  ORDER BY created_at DESC 
  LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$pendingUsers = [];
while ($row = $result->fetch_assoc()) {
  $pendingUsers[] = $row;
}
