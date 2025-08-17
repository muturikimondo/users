<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

$limit = 6; // cards per page
$page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int) $_POST['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Count total users
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalRow = $totalQuery->fetch_assoc();
$totalRecords = (int) $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch users
$sql = "
    SELECT u.id, u.username, u.email, u.role_id, u.department_id, u.section_id,
           u.photo, u.status, u.is_disabled, u.last_login_at, u.created_at,
           r.name AS role_name,
           d.name AS department_name,
           s.name AS section_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.id
    LEFT JOIN departments d ON u.department_id = d.id
    LEFT JOIN sections s ON u.section_id = s.id
    ORDER BY u.id DESC
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);

$userCards = '';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $photoUrl = !empty($row['photo'])
            ? BASE_URL . htmlspecialchars($row['photo']) // ✅ Only prepend BASE_URL
            : BASE_URL . 'core/uploads/photos/default.png';

        $statusBadge = $row['status'] == 1
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';

        $disabledBadge = $row['is_disabled'] == 1
            ? '<span class="badge bg-danger">Disabled</span>'
            : '<span class="badge bg-success">Enabled</span>';

        $userCards .= '
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <img src="' . $photoUrl . '" alt="Photo" class="rounded-circle border mb-3" width="80" height="80">
                    <h5 class="card-title">' . htmlspecialchars($row['username']) . '</h5>
                    <p class="text-muted small mb-1">' . htmlspecialchars($row['email']) . '</p>
                    <p class="mb-1"><strong>Role:</strong> ' . htmlspecialchars($row['role_name'] ?? '—') . '</p>
                    <p class="mb-1"><strong>Dept:</strong> ' . htmlspecialchars($row['department_name'] ?? '—') . '</p>
                    <p class="mb-1"><strong>Section:</strong> ' . htmlspecialchars($row['section_name'] ?? '—') . '</p>
                    <div class="mb-2">' . $statusBadge . ' ' . $disabledBadge . '</div>
                    <small class="text-muted">Last Login: ' . htmlspecialchars($row['last_login_at'] ?? 'Never') . '</small><br>
                    <small class="text-muted">Created: ' . htmlspecialchars($row['created_at']) . '</small>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-sm btn-primary editUserBtn" data-id="' . (int)$row['id'] . '"><i class="bi bi-pencil"></i> Edit</button>
                    <button class="btn btn-sm btn-danger deleteUserBtn" data-id="' . (int)$row['id'] . '"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>';
    }
} else {
    $userCards .= '<div class="col-12 text-center"><p>No users found.</p></div>';
}

// Pagination controls
$pagination = '';
if ($totalPages > 1) {
    $pagination .= '<nav><ul class="pagination justify-content-center">';

    // Prev
    $prevDisabled = $page <= 1 ? ' disabled' : '';
    $pagination .= '<li class="page-item' . $prevDisabled . '">
                        <a class="page-link pagination-link" href="#" data-page="' . ($page - 1) . '">&laquo;</a>
                    </li>';

    // Pages
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $page ? ' active' : '';
        $pagination .= '<li class="page-item' . $active . '">
                            <a class="page-link pagination-link" href="#" data-page="' . $i . '">' . $i . '</a>
                        </li>';
    }

    // Next
    $nextDisabled = $page >= $totalPages ? ' disabled' : '';
    $pagination .= '<li class="page-item' . $nextDisabled . '">
                        <a class="page-link pagination-link" href="#" data-page="' . ($page + 1) . '">&raquo;</a>
                    </li>';

    $pagination .= '</ul></nav>';
}

// Return JSON
echo json_encode([
    'users' => $userCards,
    'pagination' => $pagination
]);
