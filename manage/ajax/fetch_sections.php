<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

$department_id = intval($_POST['department_id'] ?? 0);
$sections = [];

if ($department_id > 0) {
    $stmt = $conn->prepare("SELECT id, name FROM sections WHERE department_id = ? ORDER BY name");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($sections);
