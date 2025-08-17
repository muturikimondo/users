

<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

if (!isset($_GET['department_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing department_id']);
    exit;
}

$department_id = intval($_GET['department_id']);

$stmt = $conn->prepare("SELECT id, name FROM sections WHERE department_id = ? ORDER BY name ASC");
$stmt->bind_param('i', $department_id);
$stmt->execute();
$result = $stmt->get_result();

$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = ['id' => $row['id'], 'name' => $row['name']];
}

header('Content-Type: application/json');
echo json_encode($sections);
