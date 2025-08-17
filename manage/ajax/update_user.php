<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';
require_once BASE_PATH . 'includes/sessions.php';

checkUserSession(); // Ensure user is logged in
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Something went wrong'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Sanitize input
    $user_id       = intval($_POST['id'] ?? 0);
    $username      = trim($_POST['username'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $role_id       = intval($_POST['role_id'] ?? 0);
    $department_id = intval($_POST['department_id'] ?? 0);
    $section_id    = intval($_POST['section_id'] ?? 0);
    $status        = intval($_POST['status'] ?? 1);
    $password      = $_POST['password'] ?? '';

    if ($user_id <= 0 || !$username || !$email || $role_id <= 0) {
        throw new Exception('Please fill all required fields');
    }

    // Fetch current photo
    $stmtOld = $conn->prepare("SELECT photo FROM users WHERE id=?");
    $stmtOld->bind_param("i", $user_id);
    $stmtOld->execute();
    $resultOld = $stmtOld->get_result();
    $oldPhoto = $resultOld->fetch_assoc()['photo'] ?? null;
    $stmtOld->close();

    // Handle photo upload
    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = BASE_PATH . 'core/uploads/photos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $uniqueName = $username . '_' . uniqid('', true) . '.' . strtolower($ext);
        $targetFile = $uploadDir . $uniqueName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            throw new Exception('Failed to upload photo');
        }

        // Correct relative path to store in DB
        $photoPath = 'core/uploads/photos/' . $uniqueName;

        // Delete old photo if it exists
        if ($oldPhoto && file_exists(BASE_PATH . $oldPhoto)) {
            @unlink(BASE_PATH . $oldPhoto);
        }
    }

    // Build SQL dynamically
    $sql = "UPDATE users SET username=?, email=?, role_id=?, department_id=?, section_id=?, status=?, updated_at=NOW()";
    $params = [$username, $email, $role_id, $department_id, $section_id, $status];
    $types  = "ssiiii";

    if ($password) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password=?";
        $params[] = $hashed;
        $types .= "s";
    }

    if ($photoPath) {
        $sql .= ", photo=?";
        $params[] = $photoPath;
        $types .= "s";
    }

    $sql .= " WHERE id=?";
    $params[] = $user_id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Failed to prepare statement: ' . $conn->error);

    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) throw new Exception('Database error: ' . $stmt->error);

    // Log action
    logAction($_SESSION['user_id'], "Updated user profile for user_id=$user_id", "update_user");

    $response = ['status' => 'success', 'message' => 'User updated successfully'];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
