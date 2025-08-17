<?php
require_once __DIR__ . '/config.php'; // Ensure BASE_URL and BASE_PATH are available

// Securely start session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    session_regenerate_id(true);
}

/**
 * Initialize session after successful login
 */
function initializeSession(array $user): void {
    global $conn;

    $_SESSION['user_id']       = $user['id'];
    $_SESSION['username']      = $user['username'];
    $_SESSION['email']         = $user['email'];
    $_SESSION['role_id']       = $user['role_id'];
    $_SESSION['role']          = getRoleName($user['role_id']);
    $_SESSION['status']        = $user['status'];
    $_SESSION['login_count']   = $user['login_count'];
    $_SESSION['last_login_at'] = $user['last_login_at'];

    // ✅ Photo URL (consistent with DB field)
    if (!empty($user['photo'])) {
        // DB already stores: core/uploads/photos/filename...
        $_SESSION['user_photo'] = asset($user['photo']);
    } else {
        // fallback placeholder
        $_SESSION['user_photo'] = asset("core/uploads/photos/default.png");
    }

    // Department and section
    $_SESSION['department'] = getDepartmentName($user['department_id'] ?? null);
    $_SESSION['section']    = getSectionName($user['section_id'] ?? null);

    // Permissions
    cacheUserPermissions();
}


/**
 * Retrieve department name from DB
 */
function getDepartmentName(?int $departmentId): string {
    global $conn;
    if (!$departmentId) return 'Not assigned';

    $stmt = $conn->prepare("SELECT name FROM departments WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $name = $result->fetch_assoc()['name'] ?? 'Not assigned';
    $stmt->close();

    return $name;
}

/**
 * Retrieve section name from DB
 */
function getSectionName(?int $sectionId): string {
    global $conn;
    if (!$sectionId) return 'Not assigned';

    $stmt = $conn->prepare("SELECT name FROM sections WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $sectionId);
    $stmt->execute();
    $result = $stmt->get_result();
    $name = $result->fetch_assoc()['name'] ?? 'Not assigned';
    $stmt->close();

    return $name;
}

/**
 * Ensure user is logged in (and optionally has a specific role)
 */
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

/**
 * Return role name from DB
 */
function getRoleName(int $roleId): string {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM roles WHERE id = ?");
    $stmt->bind_param("i", $roleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $role = $result->fetch_assoc();
    return $role['name'] ?? 'Unknown';
}

/**
 * Return dashboard path based on role
 */
function getRedirectUrl(string $role): string {
    return match ($role) {
        'System Admin'       => BASE_URL . 'access/admin/dashboard.php',
        'System Owner'       => BASE_URL . 'access/owner/dashboard.php',
        'Section Supervisor' => BASE_URL . 'access/supervisor/dashboard.php',
        'Line User'          => BASE_URL . 'access/user/dashboard.php',
        default              => BASE_URL . 'auth/login.php',
    };
}

/**
 * Destroy session and logout
 */
function destroySession(): void {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit();
}

/**
 * Cache user permissions from role and direct assignment
 */
function cacheUserPermissions(): void {
    global $conn;
    $userId = $_SESSION['user_id'] ?? null;
    $roleId = $_SESSION['role_id'] ?? null;

    if (!$userId || !$roleId) return;

    $permissions = [];

    // Role permissions
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

/**
 * Check if current user has a given permission
 */
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

/**
 * Require a permission or redirect
 */
function requirePermission(string $permissionName): void {
    global $conn;
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId || !userHasPermission($permissionName)) {
        logAction($userId, 'access_denied', "Permission '$permissionName' was denied.");
        $_SESSION['error_message'] = "You are not authorized to access this page.";
        header('Location: ' . BASE_URL . 'unauthorized.php');
        exit();
    }
}

/**
 * Log unauthorized permission attempt
 */
function logPermissionDenied(int $userId, string $permissionName): void {
    logAction($userId, 'permission_denied', "User attempted: {$permissionName}");
}

/**
 * General logging function
 */
function logAction(int $userId, string $action, string $details): void {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $action, $details);
    $stmt->execute();
    $stmt->close();
}

/**
 * Clear permission cache from session
 */
function clearCachedPermissions(): void {
    unset($_SESSION['cached_permissions']);
}

/**
 * Render session timer JavaScript
 */
function renderSessionTimerScript(int $elapsedSeconds): void {
    ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        let seconds = <?= $elapsedSeconds ?>;
        const timerElement = document.getElementById('session-timer');

        function pad(n) {
          return String(n).padStart(2, '0');
        }

        function updateTimer() {
          seconds++;
          if (timerElement) {
            const hrs = pad(Math.floor(seconds / 3600));
            const mins = pad(Math.floor((seconds % 3600) / 60));
            const secs = pad(seconds % 60);
            timerElement.textContent = `${hrs}:${mins}:${secs}`;
          }
        }

        updateTimer();
        setInterval(updateTimer, 1000);
      });
    </script>
    <?php
}
