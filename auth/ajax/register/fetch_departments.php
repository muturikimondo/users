<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

$sql = "SELECT id, name FROM departments ORDER BY name";
$result = $conn->query($sql);

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

header('Content-Type: application/json');
echo json_encode($departments);
