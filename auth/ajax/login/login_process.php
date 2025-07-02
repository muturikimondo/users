<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';
require_once BASE_PATH . 'includes/db.php';
require_once BASE_PATH . 'includes/sessions.php'; // Include the session management file

header('Content-Type: application/json');

// 🔐 Enforce POST & JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    exit;
}

// 🔍 Look up user
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    logLoginAttempt(null, $email, false, 'No user found');
    echo json_encode(['success' => false, 'message' => 'No account associated with this email.']);
    exit;
}

// 🚫 Account checks
if ((int)$user['is_disabled'] === 1) {
    logLoginAttempt($user['id'], $email, false, 'Account is disabled');
    echo json_encode(['success' => false, 'message' => 'Your account is disabled.']);
    exit;
}

if (!$user['email_verified_at']) {
    logLoginAttempt($user['id'], $email, false, 'Email not verified');
    echo json_encode(['success' => false, 'message' => 'Please verify your email first.']);
    exit;
}

if ($user['status'] === 'pending') {
    logLoginAttempt($user['id'], $email, false, 'Account pending approval');
    echo json_encode(['success' => false, 'message' => 'Your account is pending approval.']);
    exit;
}

if ($user['status'] === 'rejected') {
    logLoginAttempt($user['id'], $email, false, 'Account rejected');
    echo json_encode(['success' => false, 'message' => 'Your account has been rejected.']);
    exit;
}

// ⛔ Too many failed attempts?
if ($user['failed_attempts'] >= 5 && strtotime($user['last_failed_login']) > strtotime('-15 minutes')) {
    logLoginAttempt($user['id'], $email, false, 'Temporarily locked out');
    echo json_encode(['success' => false, 'message' => 'Too many failed attempts. Try again later.']);
    exit;
}

// ✅ Check password
if (!password_verify($password, $user['password'])) {
    incrementFailedAttempts($conn, $user['id']);
    logLoginAttempt($user['id'], $email, false, 'Wrong password');
    echo json_encode(['success' => false, 'message' => 'Email or password incorrect.']);
    exit;
}

// ✅ All good — log in
resetFailedAttempts($conn, $user['id']);
updateLoginStats($conn, $user['id']);
logLoginAttempt($user['id'], $email, true);

// 🎉 Start secure session
regenerateSession();
initializeSession($user);  // Using the function to initialize session

// Redirect based on role
$redirect_url = getRedirectUrl($_SESSION['role']);
echo json_encode(['success' => true, 'message' => 'Login successful!', 'redirect' => $redirect_url]);
exit();

// === 🔧 Helpers ===

function regenerateSession() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_regenerate_id(true);
}

function incrementFailedAttempts($conn, $userId) {
    $sql = "UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_login = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
}

function resetFailedAttempts($conn, $userId) {
    $sql = "UPDATE users SET failed_attempts = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
}

function updateLoginStats($conn, $userId) {
    $sql = "UPDATE users SET login_count = login_count + 1, last_login_at = NOW(), updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
}

function logLoginAttempt($userId, $email, $success, $details = null) {
    global $conn;
    $action = $success ? 'login_success' : 'login_failed';
    $details = $details ?? "Tried email: $email";
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $userId, $action, $details);
    $stmt->execute();
}

require_once BASE_PATH . 'includes/sessions.php'; // Make sure this is included at the top

// 🎉 Start secure session
regenerateSession();
initializeSession($user);  // Calling the function from sessions.php






