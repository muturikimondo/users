<?php
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

$department_id = isset($_GET['department_id']) ? (int) $_GET['department_id'] : 0;

if ($department_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name FROM sections WHERE department_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = $row;
}

echo json_encode($sections);
