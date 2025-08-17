<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . 'includes/sessions.php';
require_once BASE_PATH . 'includes/db.php';
require_once BASE_PATH . 'templates/header.php';

checkUserSession('System Admin'); // Ensure only System Admins can access

// Get user info from session
$user = [
    'photo'          => $_SESSION['user_photo'] ?? BASE_URL . 'core/uploads/photos/default.png',
    'username'       => $_SESSION['username'] ?? 'Unknown',
    'email'          => $_SESSION['email'] ?? 'Not set',
    'department'     => $_SESSION['department'] ?? 'Not assigned',
    'department_id'  => $_SESSION['department_id'] ?? null,
    'section_id'     => $_SESSION['section_id'] ?? null,
    'role'           => $_SESSION['role'] ?? 'Unknown',
    'status'         => $_SESSION['status'] ?? 'active',
    'login_count'    => $_SESSION['login_count'] ?? 0,
    'last_login_at'  => $_SESSION['last_login_at'] ?? null
];

// Fetch departments for the edit profile form
$stmt = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
$departments = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!-- Sidebar Toggle for Mobile -->
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

  <!-- Main Content -->
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

          <!-- Profile Display -->
          <div class="card card-glass border-0 rounded-4 shadow-lg px-4 pt-5 pb-4">
            <div class="row align-items-center">
              <!-- Photo -->
              <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="position-relative">
                  <img src="<?= htmlspecialchars($user['photo']) ?>" 
                       class="rounded-circle shadow-sm border border-2 mb-3 p-2"
                       style="width: 140px; height: 140px; object-fit: cover;"
                       alt="User Photo">
                  <button class="btn btn-outline-secondary btn-sm rounded-pill mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
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

          

        </div>
      </div>
    </main>

    <?php include BASE_PATH . 'templates/footer.php'; ?>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content glassmorphic p-0 border-0">
      <div class="card  card-glass card-modal shadow-lg rounded-4 border-0 overflow-hidden">
        <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
          <h5 class="modal-title fw-bold text-white" id="editProfileLabel">Edit Profile</h5>
          <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal"></button>
        </div>

        <form id="editProfileForm" enctype="multipart/form-data" novalidate>
          <div class="card-body pt-3 px-4">

            <!-- Username -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-person-fill floating-icon"></i>
              <input type="text" class="form-control icon-input" id="username" name="username"
                     value="<?= htmlspecialchars($user['username']) ?>" required>
              <label for="username">Username</label>
            </div>

            <!-- Email -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-envelope-fill floating-icon"></i>
              <input type="email" class="form-control icon-input" id="email" name="email"
                     value="<?= htmlspecialchars($user['email']) ?>" required>
              <label for="email">Email</label>
            </div>

            <!-- Department -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-building floating-icon"></i>
              <select id="department_id" name="department_id" class="icon-input select2" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?= $dept['id'] ?>" <?= ($dept['id'] == $user['department_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="department_id">Department</label>
            </div>

            <!-- Section -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-diagram-3-fill floating-icon"></i>
              <select id="section_id" name="section_id" class="icon-input select2" required>
                <option value="">Select Section</option>
              </select>
              <label for="section_id">Section</label>
            </div>

            <!-- Password Toggle -->
            <div class="form-check form-switch mb-3">
              <input type="checkbox" class="form-check-input" id="changePasswordToggle">
              <label class="form-check-label fw-semibold text-white" for="changePasswordToggle">Change Password</label>
            </div>

            <!-- Password Fields -->
            <div id="passwordSection" class="d-none">
              <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control icon-input" id="newPassword" name="password">
                <label for="newPassword">New Password</label>
                <i class="bi bi-eye-fill floating-icon end-0 me-3 toggle-eye" data-target="newPassword"></i>
              </div>

              <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control icon-input" id="confirmPassword" name="confirm_password">
                <label for="confirmPassword">Confirm Password</label>
                <i class="bi bi-eye-fill floating-icon end-0 me-3 toggle-eye" data-target="confirmPassword"></i>
              </div>
            </div>

            <!-- Profile Photo -->
            <div class="mb-3">
              <label for="photo" class="form-label text-white">Profile Photo</label>
              <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
              <div class="mt-2">
                <img id="photoPreview"
                     src="<?= htmlspecialchars($user['photo']) ?>"
                     alt="Preview"
                     class="img-thumbnail"
                     style="max-width: 150px;">
              </div>
            </div>

          </div>
          <div class="card-footer border-0 bg-transparent px-4 pb-4">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Update Profile</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script type="module" src="<?= asset('profile/js/index.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
