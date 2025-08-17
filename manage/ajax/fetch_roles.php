<?php
require_once __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$query = "SELECT id, name FROM roles ORDER BY name ASC";
$result = $conn->query($query);

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = [
        'id' => $row['id'],
        'name' => $row['name']
    ];
}

echo json_encode($roles);
