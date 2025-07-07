<?php
require_once __DIR__ . '/../../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['department_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing department_id']);
    exit;
}

$department_id = (int) $_GET['department_id'];
$sections = [];

$stmt = $conn->prepare("SELECT id, name FROM sections WHERE department_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $sections[] = $row;
}

echo json_encode($sections);
