<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/sessions.php';

// Ensure the user is logged in
checkUserSession();

// Require specific permission for this page
requirePermission('access_owner_dashboard');

// Optional: Session metrics
$sessionStartTime = $_SESSION['login_time'] ?? time();
$userPhoto = $_SESSION['user_photo'] ?? BASE_URL . 'core/uploads/photos/default.jpg';
$sessionDuration = time() - $sessionStartTime;
$sessionDurationFormatted = gmdate("H:i:s", $sessionDuration);

include BASE_PATH . 'templates/header.php';
?>

<!-- Main Layout Wrapper -->
<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar -->
  <aside class="sidebar-glass p-3 border-end shadow" style="min-width: 240px;">
    <?php include BASE_PATH . 'templates/sidebar.php'; ?>
  </aside>

  <!-- Main Content -->
  <div class="flex-grow-1 d-flex flex-column">
    <main class="flex-grow-1 container py-5">
      <h2 class="text-center section-header-glass gradient-text">Admin Dashboard</h2>
      <p class="lead text-center text-muted">
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?>. You have full access to system management.
      </p>

      <!-- User Info Cards -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="nav nav-pills nav-justified mb-3 card-glass p-4 gap-4 flex-wrap justify-content-center align-items-center">
            <div class="nav-item text-center">
              <img src="<?= htmlspecialchars($userPhoto); ?>" alt="User Photo" class="rounded-circle shadow mb-2" width="100" height="100">
              <h5 class="nav-link active gradient-text"><?= htmlspecialchars($_SESSION['username']); ?></h5>
              <p class="text-muted"><?= htmlspecialchars($_SESSION['role']); ?></p>
            </div>
            <div class="nav-item text-center">
              <h5 class="nav-link gradient-text">Session Duration</h5>
              <p class="text-muted" id="session-timer"><?= $sessionDurationFormatted; ?></p>

            </div>
            <div class="nav-item text-center">
              <h5 class="nav-link gradient-text">Account Info</h5>
              <p class="text-muted">Manage your account settings and preferences.</p>
            </div>
            <div class="nav-item text-center">
              <a href="<?= BASE_URL ?>auth/logout.php" class="nav-link gradient-text btn-logout">Logout</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Tools -->
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title gradient-text">User Management</h5>
              <p class="card-text">Manage users, roles, and permissions across the system.</p>
              <a href="user_management.php" class="btn btn-primary">Go to User Management</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title gradient-text">System Settings</h5>
              <p class="card-text">Configure system settings and workflows.</p>
              <a href="system_settings.php" class="btn btn-primary">Go to System Settings</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-glass h-100">
            <div class="card-body">
              <h5 class="card-title gradient-text">Activity Logs</h5>
              <p class="card-text">View user activity logs and audit trails.</p>
              <a href="activity_logs.php" class="btn btn-primary">View Logs</a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
<?php renderSessionTimerScript($sessionDuration); ?>


<?php include BASE_PATH . 'templates/footer.php'; ?>
