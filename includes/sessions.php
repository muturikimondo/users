<?php
// Start session securely
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    session_regenerate_id(true); // Prevent session hijacking
}

// Initialize session after login
function initializeSession(array $user): void {
    $_SESSION['user_id']        = $user['id'];
    $_SESSION['username']       = $user['username'];
    $_SESSION['role_id']        = $user['role_id'];
    $_SESSION['role']           = getRoleName($user['role_id']);
    $_SESSION['department_id']  = $user['department_id'];
    $_SESSION['section_id']     = $user['section_id'];

    cacheUserPermissions(); // Cache permissions upon login
}

// Check user login status and optionally validate role
function checkUserSession(string $requiredRole = null): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit();
    }

    if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
        header('Location: ' . getRedirectUrl($_SESSION['role']));
        exit();
    }
}

// Get role name from DB
function getRoleName(int $roleId): string {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM roles WHERE id = ?");
    $stmt->bind_param("i", $roleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $role = $result->fetch_assoc();
    return $role['name'] ?? 'Unknown';
}

// Redirect based on role
function getRedirectUrl(string $role): string {
    switch ($role) {
        case 'System Admin':
            return BASE_URL . 'access/admin/dashboard.php';
        case 'System Owner':
            return BASE_URL . 'access/owner/dashboard.php';
        case 'Section Supervisor':
            return BASE_URL . 'access/supervisor/dashboard.php';
        case 'Line User':
            return BASE_URL . 'access/user/dashboard.php';
        default:
            return BASE_URL . 'auth/login.php';
    }
}

// Destroy session and log out
function destroySession(): void {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit();
}

// Caches user's permissions (both role and user-based)
function cacheUserPermissions(): void {
    global $conn;

    $userId = $_SESSION['user_id'] ?? null;
    $roleId = $_SESSION['role_id'] ?? null;

    if (!$userId || !$roleId) return;

    $permissions = [];

    // Role-based permissions
    $stmt = $conn->prepare("
        SELECT p.name FROM permissions p
        JOIN role_permissions rp ON rp.permission_id = p.id
        WHERE rp.role_id = ?
    ");
    $stmt->bind_param("i", $roleId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permissions[] = $row['name'];
    }
    $stmt->close();

    // User-specific permissions
    $stmt = $conn->prepare("
        SELECT p.name FROM permissions p
        JOIN user_permissions up ON up.permission_id = p.id
        WHERE up.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $permissions[] = $row['name'];
    }
    $stmt->close();

    $_SESSION['cached_permissions'] = array_unique($permissions);
}

// Checks if user has a specific permission
function userHasPermission(string $permissionName): bool {
    if (!isset($_SESSION['cached_permissions'])) {
        cacheUserPermissions();
    }

    $hasPermission = in_array($permissionName, $_SESSION['cached_permissions'] ?? [], true);

    if (!$hasPermission) {
        logPermissionDenied($_SESSION['user_id'] ?? 0, $permissionName);
    }

    return $hasPermission;
}

// Requires a permission to access the page
function requirePermission(string $permissionName): void {
    global $conn;

    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId || !userHasPermission($permissionName)) {
        // Log the denied access attempt
        $action  = 'access_denied';
        $details = "Permission '$permissionName' was denied.";
        $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $action, $details);
        $stmt->execute();
        $stmt->close();

        // Flash message
        $_SESSION['error_message'] = "You are not authorized to access this page.";

        // Redirect to unauthorized page
        header('Location: ' . BASE_URL . 'unauthorized.php');
        exit();
    }
}

// Logs denied permission attempts for auditing
function logPermissionDenied(int $userId, string $permissionName): void {
    global $conn;
    $action  = 'permission_denied';
    $details = "User attempted: {$permissionName}";

    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $action, $details);
    $stmt->execute();
    $stmt->close();
}

// Clear cached permissions (e.g., on role change or logout)
function clearCachedPermissions(): void {
    unset($_SESSION['cached_permissions']);
}

function renderSessionTimerScript(int $elapsedSeconds): void {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let seconds = <?= $elapsedSeconds ?>;

        function pad(num) {
            return String(num).padStart(2, '0');
        }

        function updateTimer() {
            seconds++;
            const hrs = pad(Math.floor(seconds / 3600));
            const mins = pad(Math.floor((seconds % 3600) / 60));
            const secs = pad(seconds % 60);
            const timerElement = document.getElementById('session-timer');
            if (timerElement) {
                timerElement.textContent = `${hrs}:${mins}:${secs}`;
            }
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
    </script>
    <?php
}


