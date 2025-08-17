<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Get total number of pending users
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'pending' AND is_disabled = 0");
$total = $totalQuery->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($total / $limit);

// Fetch paginated results
$stmt = $conn->prepare("SELECT id, username, email, photo, status 
                        FROM users 
                        WHERE status = 'pending' AND is_disabled = 0 
                        ORDER BY created_at DESC 
                        LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['photo'])) {
        // Photo already stored as "core/uploads/photos/filename..."
        $row['photo_url'] = asset($row['photo']);
    } else {
        // Use placeholder if no photo
        $row['photo_url'] = asset("core/uploads/photos/user-placeholder.webp");
    }
    $users[] = $row;
}

echo json_encode([
    'users' => $users,
    'currentPage' => $page,
    'totalPages' => $totalPages
]);
