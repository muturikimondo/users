<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . 'includes/sessions.php';
checkUserSession('System Admin'); // Ensure only System Admins can access

include BASE_PATH . 'templates/header.php';

// Get user info from session
$user = [
    'photo'       => $_SESSION['user_photo'] ?? BASE_URL . 'core/uploads/photos/default.png',
    'username'    => $_SESSION['username'] ?? 'Unknown',
    'email'       => $_SESSION['email'] ?? 'Not set',
    'department'  => $_SESSION['department'] ?? 'Not assigned',
    'role'        => $_SESSION['role'] ?? 'Unknown',
    'status'      => $_SESSION['status'] ?? 'active',
    'login_count' => $_SESSION['login_count'] ?? 0,
    'last_login_at' => $_SESSION['last_login_at'] ?? null
];
?>

<!-- Sidebar Offcanvas Toggle -->
<button class="btn btn-outline-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
  <i class="bi bi-list"></i> Menu
</button>

<div class="d-flex flex-column flex-md-row min-vh-100">
  <!-- Sidebar -->
  <aside class="offcanvas-md offcanvas-start sidebar-glass p-3 shadow-lg rounded-end-4" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header d-md-none">
      <h5 class="offcanvas-title">Navigation</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <?php include BASE_PATH . 'templates/sidebar.php'; ?>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-grow-1 d-flex flex-column">
    <main class="flex-grow-1 container-fluid mt-4">
      <div class="row justify-content-center">
        <div class="col-lg-8">

          <!-- Section Header -->
          <?php
          include BASE_PATH . 'templates/components/page_section_header.php';
          renderSectionHeader([
            'icon' => 'bi-person-circle',
            'title' => 'Your Profile',
            'badge' => 'Admin Center',
            'subtitle' => 'Manage your credentials and visibility.'
          ]);
          ?>

          <!-- Profile Glass Card -->
          <div class="card card-glass border-0 rounded-4 shadow-lg px-4 pt-5 pb-4">
            <div class="row align-items-center">
              <!-- Photo -->
              <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="position-relative">
                  <img src="<?= htmlspecialchars($user['photo']) ?>" 
                       class="rounded-circle shadow-sm border border-2 mb-3 p-2"
                       style="width: 140px; height: 140px; object-fit: cover;"
                       alt="User Photo">
                  <button class="btn btn-outline-secondary btn-sm rounded-pill mt-2" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                    <i class="bi bi-camera-fill me-1"></i> Update Photo
                  </button>
                </div>
              </div>

              <!-- Details -->
              <div class="col-md-8">
                <div class="mb-3">
                  <h5 class="fw-bold gradient-text mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                  <p class="text-muted mb-1"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
                  <p class="text-muted mb-1"><i class="bi bi-diagram-3 me-2"></i><?= htmlspecialchars($user['department']) ?></p>
                  <p class="text-muted mb-1"><i class="bi bi-person-badge me-2"></i><?= ucfirst($user['role']) ?></p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                  <span class="badge bg-success-subtle text-success-emphasis border px-3 py-2 rounded-pill">
                    Status: <?= ucfirst($user['status']) ?>
                  </span>
                  <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    Logins: <?= (int)$user['login_count'] ?>
                  </span>
                  <span class="badge bg-info-subtle text-info-emphasis border px-3 py-2 rounded-pill">
                    Last: <?= $user['last_login_at'] ? date('M j, Y H:i', strtotime($user['last_login_at'])) : 'Never' ?>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Edit Profile Button -->
          <div class="text-center mt-4">
            <button class="btn btn-outline-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              <i class="bi bi-pencil-square me-2"></i> Edit Profile
            </button>
          </div>

        </div>
      </div>
    </main>

    <?php include BASE_PATH . 'templates/footer.php'; ?>
  </div>
</div>

<!-- Include modal -->
<?php include BASE_PATH . 'templates/modals/edit_profile.php'; ?>

<!-- JS -->
<script type="module" src="<?= asset('profile/js/index.js') ?>"></script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
