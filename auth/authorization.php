<?php
require_once BASE_PATH . 'includes/config.php';
require_once BASE_PATH . 'includes/log_helpers.php'; // You can use this for logging denied access

function userHasPermission($permissionName) {
    global $conn;

    $userId = $_SESSION['user_id'] ?? null;
    $roleId = $_SESSION['role_id'] ?? null;

    if (!$userId || !$roleId) {
        return false;
    }

    // Cache permissions in session
    if (!isset($_SESSION['cached_permissions'])) {
        $_SESSION['cached_permissions'] = fetchUserPermissions($userId, $roleId);
    }

    return in_array($permissionName, $_SESSION['cached_permissions']);
}

function fetchUserPermissions($userId, $roleId) {
    global $conn;

    $permissions = [];

    // Fetch role-based permissions
    $sql = "SELECT p.name FROM permissions p
            JOIN role_permissions rp ON rp.permission_id = p.id
            WHERE rp.role_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roleId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permissions[] = $row['name'];
    }

    // Fetch user-specific permissions
    $sql = "SELECT p.name FROM permissions p
            JOIN user_permissions up ON up.permission_id = p.id
            WHERE up.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permissions[] = $row['name'];
    }

    return array_unique($permissions);
}
