<?php
// includes/log_helpers.php

require_once __DIR__ . '/config.php';

/**
 * Log a user action into the logs table.
 *
 * @param int|null $userId       The ID of the user performing the action.
 * @param string   $action       The type of action performed (e.g., 'login_success', 'access_denied').
 * @param string   $details      Additional context or description of the action.
 * @param mysqli|null $connOverride Optional connection override (for unit testing or alternative DB).
 */
function logUserAction($userId, string $action, string $details = '', $connOverride = null): void
{
    $conn = $connOverride ?? $GLOBALS['conn'];

    if (!$conn || !$userId || empty($action)) {
        error_log("logUserAction failed: missing user ID, action, or DB connection.");
        return;
    }

    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
    if (!$stmt) {
        error_log("logUserAction prepare failed: " . $conn->error);
        return;
    }

    $stmt->bind_param("iss", $userId, $action, $details);
    if (!$stmt->execute()) {
        error_log("logUserAction execute failed: " . $stmt->error);
    }

    $stmt->close();
}

/**
 * Shortcut for logging denied permission access.
 *
 * @param string $permissionName The name of the permission that was denied.
 */
function logDeniedAccess(string $permissionName): void
{
    $userId = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['username'] ?? 'Unknown';

    $details = "Permission '{$permissionName}' denied to user '{$username}'";
    logUserAction($userId, 'access_denied', $details);
}

/**
 * Shortcut for logging successful login.
 */
function logLoginSuccess(): void
{
    $userId = $_SESSION['user_id'] ?? null;
    $email = $_SESSION['email'] ?? 'unknown';
    $details = "Login success using email: {$email}";

    logUserAction($userId, 'login_success', $details);
}

/**
 * Shortcut for logging failed login.
 *
 * @param string $reason Description for failure.
 * @param string|null $email The email attempted.
 */
function logLoginFailure(string $reason, ?string $email = null): void
{
    $userId = $_SESSION['user_id'] ?? null;
    $email = $email ?? $_POST['email'] ?? 'unknown';
    $details = "Login failed for email: {$email}. Reason: {$reason}";

    logUserAction($userId, 'login_failed', $details);
}
