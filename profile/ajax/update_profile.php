<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';
require_once BASE_PATH . 'includes/sessions.php';

header('Content-Type: application/json');
checkUserSession(); // Any logged-in user

$user_id = $_SESSION['user_id'];

$response = ['success' => false, 'message' => 'Invalid data.'];

// Validate inputs
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$department_id = intval($_POST['department_id'] ?? 0);
$section_id = intval($_POST['section_id'] ?? 0);
$change_password = !empty($_POST['password']);
$password = $_POST['password'] ?? null;
$confirm_password = $_POST['confirm_password'] ?? null;

// Input validations
if ($username === '' || $email === '' || $department_id === 0 || $section_id === 0) {
    $response['message'] = 'Please fill in all required fields.';
    echo json_encode($response);
    exit;
}

if ($change_password && $password !== $confirm_password) {
    $response['message'] = 'Passwords do not match.';
    echo json_encode($response);
    exit;
}

// Start update
try {
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, department_id = ?, section_id = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssiii", $username, $email, $department_id, $section_id, $user_id);
    $stmt->execute();
    $stmt->close();

    // Handle password change
    if ($change_password && strlen($password) >= 8) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmp = $_FILES['photo']['tmp_name'];
        $photoName = basename($_FILES['photo']['name']);
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($photoExt, $allowed)) {
            $newPhotoName = 'user_' . $user_id . '_' . time() . '.' . $photoExt;
            $destination = BASE_PATH . 'core/uploads/photos/' . $newPhotoName;
            if (move_uploaded_file($photoTmp, $destination)) {
                $relativePath = BASE_URL . 'core/uploads/photos/' . $newPhotoName;

                // Save to DB
                $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
                $stmt->bind_param("si", $relativePath, $user_id);
                $stmt->execute();
                $stmt->close();

                $_SESSION['user_photo'] = $relativePath;
            }
        }
    }

    // Refresh session
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['department_id'] = $department_id;
    $_SESSION['section_id'] = $section_id;

    $response['success'] = true;
    $response['message'] = 'Profile updated successfully.';
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
