<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

// JSON response helper
function json_response($type, $msg) {
    echo json_encode(['status' => $type, 'message' => $msg]);
    exit;
}

// Collect and sanitize POST input
$username   = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$dept_id    = $_POST['department_id'] ?? null;
$section_id = $_POST['section_id'] ?? null;

// Validate required fields
if (!$username || !$email || !$password || !$dept_id) {
    json_response('error', 'Please fill all required fields.');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response('error', 'Invalid email address.');
}

// Validate password strength
$strongPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
if (!preg_match($strongPassword, $password)) {
    json_response('error', 'Password must include uppercase, number, symbol, and be 8+ characters.');
}

// Check email uniqueness
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    json_response('error', 'Email already registered.');
}
$stmt->close();

// Generate verification token
$token = bin2hex(random_bytes(16));

// Set defaults
$role_id     = 4;
$status      = 'pending';
$is_disabled = 0;
$now         = date('Y-m-d H:i:s');
$photoName   = 'core/uploads/photos/default.jpg'; // fallback path

// Handle image upload
if (!empty($_FILES['photo']['tmp_name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['photo']['tmp_name'];
    $fileInfo = getimagesize($tmpName);

    if ($fileInfo) {
        $mime = $fileInfo['mime'];
        $src = null;
        $ext = '';

        switch ($mime) {
            case 'image/jpeg': $src = imagecreatefromjpeg($tmpName); $ext = 'jpg'; break;
            case 'image/png':  $src = imagecreatefrompng($tmpName);  $ext = 'png'; break;
            default:
                json_response('error', 'Only JPEG and PNG formats are supported.');
        }

        if ($src) {
            $uploadDir = BASE_PATH . 'core/uploads/photos/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            // Generate filename: username_uniqueid.ext
            $safeUsername = preg_replace('/[^a-zA-Z0-9_-]/', '', $username);
            $uniqueId = uniqid();
            $filename = $safeUsername . '_' . $uniqueId . '.' . $ext;
            $path = $uploadDir . $filename;

            // Resize to 300x300
            $resized = imagescale($src, 300, 300);

            // Save image
            $saved = false;
            if ($ext === 'jpg') {
                $saved = imagejpeg($resized, $path, 85);
            } elseif ($ext === 'png') {
                $saved = imagepng($resized, $path, 8);
            }

            if ($saved) {
                // Store relative path in DB
                $photoName = 'core/uploads/photos/' . $filename;
            }

            imagedestroy($src);
            imagedestroy($resized);
        }
    }
}

// Hash password securely
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user into DB
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id, department_id, section_id, photo, status, is_disabled, verification_token, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssiiississs",
    $username,
    $email,
    $hashedPassword,
    $role_id,
    $dept_id,
    $section_id,
    $photoName,
    $status,
    $is_disabled,
    $token,
    $now,
    $now
);

if ($stmt->execute()) {
    json_response('success', 'Account created successfully! Check your email to verify.');
} else {
    json_response('error', 'Registration failed: ' . $stmt->error);
}
