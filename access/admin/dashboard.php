<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . 'includes/sessions.php';

// Ensure the user is logged in
checkUserSession();
requirePermission('access_admin_dashboard');

// Session metrics
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

 <!-- Admin Dashboard Unified Header -->
<div class="card card-glass shadow-xxl border-0 rounded-5 mb-5 p-5 text-center position-relative overflow-hidden">

  <!-- Subtle glowing background -->
  <div class="position-absolute top-0 start-0 w-100 h-100" 
       style="background: radial-gradient(circle at 20% 30%, rgba(0,99,45,0.12), transparent 60%), 
              radial-gradient(circle at 80% 70%, rgba(194,170,141,0.15), transparent 70%);
              filter: blur(80px); z-index: 0;"></div>

  <div class="position-relative z-1">

    <!-- Title -->
    <h2 class="fw-bold mb-4"
        style="font-size: 2.8rem; letter-spacing: -0.5px;
               background: linear-gradient(90deg, #00632d, #02a554, #c2aa8d);
               -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
      Admin Dashboard
    </h2>

    <!-- Welcome Text -->
    <p class="lead text-muted mb-5">
      Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.  
      You have <span class="fw-semibold text-success">full access</span> to system management.
    </p>

    <!-- Profile + Info Row -->
    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-5 mb-5">

      <!-- Profile -->
      <div class="text-center">
        <div class="p-2 rounded-circle shadow-lg" 
             style="background: linear-gradient(135deg, #00632d, #02a554); display:inline-block;">
          <img src="<?= htmlspecialchars($userPhoto); ?>" 
               alt="User Photo" 
               class="rounded-circle border border-4 border-light shadow-lg" 
               width="130" height="130">
        </div>
        <h4 class="fw-bold mt-3 text-gradient-green"><?= htmlspecialchars($_SESSION['username']); ?></h4>
        <p class="text-muted mb-0 fs-6">
          <i class="bi bi-shield-lock-fill me-1 text-gradient-brown"></i>
          <?= htmlspecialchars($_SESSION['role']); ?>
        </p>
      </div>

      <!-- Session Duration -->
      <div class="glass-card px-4 py-3 rounded-4 shadow-sm text-start">
        <h6 class="fw-semibold text-gradient-green mb-1">
          <i class="bi bi-clock-history me-2"></i> Session Duration
        </h6>
        <p class="text-muted fs-5 mb-0 fw-light" id="session-timer"><?= $sessionDurationFormatted; ?></p>
      </div>

      <!-- Account Info -->
      <div class="glass-card px-4 py-3 rounded-4 shadow-sm text-start">
        <h6 class="fw-semibold text-gradient-brown mb-1">
          <i class="bi bi-person-gear me-2"></i> Account Info
        </h6>
        <p class="text-muted mb-0 small">Manage your account settings and preferences with ease.</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="../../manage/users.php" 
         class="btn btn-gradient-green rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-people-fill"></i> Manage Users
      </a>
      <a href="<?= BASE_URL ?>auth/logout.php" 
         class="btn rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2"
         style="background: linear-gradient(135deg, #432d14, #c2aa8d); color: #fff; font-weight: 500;">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>

  </div>
</div>

<!-- Glass + Gradient Styles -->
<style>
  .card-glass {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.18);
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.15);
  }

  .text-gradient-green {
    background: linear-gradient(90deg, #00632d, #02a554);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .text-gradient-brown {
    background: linear-gradient(90deg, #432d14, #c2aa8d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .btn-gradient-green {
    background: linear-gradient(135deg, #00632d, #02a554);
    border: none;
    color: #fff !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .btn-gradient-green:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 99, 45, 0.4);
  }
</style>


      

      <!-- Admin Tools -->
      <div class="row g-4">

       <!-- User Management -->
<div class="col-md-4">
  <div class="card card-glass h-100 shadow-xxl border-0 rounded-5 overflow-hidden position-relative">
    
    <!-- Halo -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25"
         style="background: radial-gradient(circle at 20% 20%, rgba(0,99,45,0.25), transparent 70%); z-index:0;">
    </div>

    <div class="card-body d-flex flex-column p-4 position-relative z-1">

      <!-- Icon -->
      <div class="mb-3 d-flex align-items-center justify-content-center rounded-circle shadow-lg"
           style="width: 70px; height: 70px; background: linear-gradient(135deg, #00632d, #02a554);">
        <i class="bi bi-people-fill fs-2 text-white"></i>
      </div>

      <!-- Title -->
      <h4 class="fw-bold text-gradient-green mb-2">User Management</h4>

      <!-- Description -->
      <p class="card-text text-muted flex-grow-1" style="line-height:1.6;">
        Manage users, roles, and permissions across the system with ease and precision. 
        Designed for speed, security, and full control.
      </p>

      <!-- CTA -->
      <a href="../../manage/users.php" 
         class="btn btn-lg btn-gradient-green w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill mt-3 shadow-sm">
        <i class="bi bi-arrow-right-circle-fill"></i>
        <span>Go to User Management</span>
      </a>
    </div>
  </div>
</div>

<!-- System Settings -->
<div class="col-md-4">
  <div class="card card-glass h-100 shadow-xxl border-0 rounded-5 overflow-hidden position-relative">
    
    <!-- Halo -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25"
         style="background: radial-gradient(circle at 80% 20%, rgba(194,170,141,0.25), transparent 70%); z-index:0;">
    </div>

    <div class="card-body d-flex flex-column p-4 position-relative z-1">

      <!-- Icon -->
      <div class="mb-3 d-flex align-items-center justify-content-center rounded-circle shadow-lg"
           style="width: 70px; height: 70px; background: linear-gradient(135deg, #432d14, #c2aa8d);">
        <i class="bi bi-gear-fill fs-2 text-white"></i>
      </div>

      <!-- Title -->
      <h4 class="fw-bold text-gradient-brown mb-2">System Settings</h4>

      <!-- Description -->
      <p class="card-text text-muted flex-grow-1" style="line-height:1.6;">
        Configure workflows, security options, and advanced system preferences in one elegant space.
      </p>

      <!-- CTA -->
      <a href="system_settings.php" 
         class="btn btn-lg btn-gradient-brown w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill mt-3 shadow-sm">
        <i class="bi bi-sliders"></i>
        <span>Go to Settings</span>
      </a>
    </div>
  </div>
</div>

<!-- Activity Logs -->
<div class="col-md-4">
  <div class="card card-glass h-100 shadow-xxl border-0 rounded-5 overflow-hidden position-relative">

    <!-- Halo -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25"
         style="background: radial-gradient(circle at 50% 80%, rgba(242,225,215,0.25), transparent 70%); z-index:0;">
    </div>

    <div class="card-body d-flex flex-column p-4 position-relative z-1">

      <!-- Icon -->
      <div class="mb-3 d-flex align-items-center justify-content-center rounded-circle shadow-lg"
           style="width: 70px; height: 70px; background: linear-gradient(135deg, #c2aa8d, #f2e1d7);">
        <i class="bi bi-journal-text fs-2 text-white"></i>
      </div>

      <!-- Title -->
      <h4 class="fw-bold text-gradient-brown mb-2">Activity Logs</h4>

      <!-- Description -->
      <p class="card-text text-muted flex-grow-1" style="line-height:1.6;">
        Track system-wide activity and audit trails with clarity and transparency.
      </p>

      <!-- CTA -->
      <a href="activity_logs.php" 
         class="btn btn-lg btn-gradient-brown w-100 d-flex align-items-center justify-content-center gap-2 rounded-pill mt-3 shadow-sm">
        <i class="bi bi-eye-fill"></i>
        <span>View Logs</span>
      </a>
    </div>
  </div>
</div>

<!-- Extra CSS (matches User Info section style) -->
<style>
  .btn-gradient-green {
    background: linear-gradient(135deg, #00632d, #02a554);
    border: none;
    color: #fff !important;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .btn-gradient-green:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,99,45,.4);
  }

  .btn-gradient-brown {
    background: linear-gradient(135deg, #432d14, #c2aa8d);
    border: none;
    color: #fff !important;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .btn-gradient-brown:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(67,45,20,.4);
  }
</style>


      </div>

    </main>
  </div>
</div>

<?php renderSessionTimerScript($sessionDuration); ?>
<?php include BASE_PATH . 'templates/footer.php'; ?>
