<?php
$page_title = "Register";
require_once __DIR__ . '/../templates/header.php';
?>

<!-- Sidebar Toggle Button (for small screens) -->
<button class="btn btn-outline-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
  <i class="bi bi-list"></i> Menu
</button>

<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar Offcanvas -->
  <aside class="offcanvas-md offcanvas-start sidebar-glass p-3 shadow-lg rounded-end-4" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header d-md-none">
      <h5 class="offcanvas-title">Navigation</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
      <?php include BASE_PATH . 'templates/sidebar.php'; ?>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="flex-grow-1 d-flex flex-column">
    <main class="flex-grow-1 container-fluid mt-4">
      <div class="row justify-content-center">

        <!-- Centered Column -->
        <div class="col-md-8 col-lg-6">

          <!-- Page Section Header -->
          <?php
          include BASE_PATH . 'templates/components/page_section_header.php';
          renderSectionHeader([
            'icon' => 'bi-person-plus-fill',
            'title' => 'User Registration',
            'badge' => 'Join the System',
            'subtitle' => 'Fill in your details to request access.'
          ]);
          ?>

          <!-- Register Card -->
          <div class="card card-glass border-0 shadow-lg rounded-4 overflow-hidden mt-3 animate__animated animate__fadeIn mb-5">

            <div class="card-body px-4 px-md-5 pt-4 pb-5">

              <form id="registerForm" method="POST" enctype="multipart/form-data" novalidate>
  <!-- Username -->
  <div class="form-floating position-relative mb-3">
    <input type="text" class="form-control icon-input" id="username" name="username" placeholder="e.g. johndoe" required>
    <label for="username"><i class="bi bi-person floating-icon"></i> Username</label>
  </div>

  <!-- Email -->
  <div class="form-floating position-relative mb-3">
    <input type="email" class="form-control icon-input" id="email" name="email" placeholder="e.g. user@example.com" required>
    <label for="email"><i class="bi bi-envelope floating-icon"></i> Email address</label>
  </div>

  <!-- Department -->
  <div class="form-floating position-relative mb-3">
    <select class="form-select select2" id="department" name="department_id" data-placeholder="Select department" required>
      <option></option>
    </select>
    <label for="department"><i class="bi bi-diagram-3 floating-icon"></i> Department</label>
  </div>

  <!-- Section -->
  <div class="form-floating position-relative mb-3">
    <select class="form-select select2" id="section" name="section_id" data-placeholder="Select section">
      <option></option>
    </select>
    <label for="section"><i class="bi bi-diagram-2 floating-icon"></i> Section</label>
  </div>

  <!-- Password -->
  <div class="form-floating position-relative mb-3">
    <input type="password" class="form-control icon-input" id="password" name="password" placeholder="********" required>
    <label for="password"><i class="bi bi-lock floating-icon"></i> Password</label>
    <div id="passwordHint" class="form-text ms-2 mt-1 small text-muted"></div>
    <i class="bi bi-eye-fill toggle-password position-absolute top-50 end-0 translate-middle-y me-3"></i>
  </div>

  <!-- Profile Photo -->
  <div class="mb-4">
    <label for="photo" class="form-label fw-semibold">Profile Photo</label>
    <div class="position-relative d-inline-block w-100">
      <img id="photoPreview"
           src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='150' height='150'><rect width='100%' height='100%' fill='%23ccc'/></svg>"
           class="rounded-circle shadow-sm border border-2 d-block mx-auto"
           style="width: 100px; height: 100px; object-fit: cover;"
           alt="Preview">
      <input type="file" id="photo" name="photo" accept="image/*" class="form-control glass-file mt-2 w-100" style="max-width: 300px; display: block; margin: 10px auto 0;" />
      <div class="form-text small text-center">Max 2MB. JPG, PNG supported.</div>
    </div>
  </div>

  <!-- Hidden fields -->
  <input type="hidden" name="role_id" value="4">
  <input type="hidden" name="status" value="pending">
  <input type="hidden" name="is_disabled" value="0">

  <!-- Submit Button -->
  <div class="d-grid mt-4">
    <button type="submit" class="btn btn-glass-success btn-lg shadow-sm">
      <i class="bi bi-person-check-fill me-2"></i> Register
    </button>
  </div>

  <!-- Already Registered? -->
  <div class="text-center mt-4">
    <small class="text-muted">Already have an account?
      <a href="<?= asset('auth/login.php') ?>" class="fw-semibold text-decoration-none">Log in</a>
    </small>
  </div>
</form>

            </div>
          </div>

          <!-- Toasts -->
          <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            <div id="formToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                <div id="toastIcon" class="toast-icon p-2"><i class="bi"></i></div>
                <div class="toast-body ps-2" id="toastMsg">Toast message goes here</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
            </div>
          </div>

        </div><!-- /col -->
      </div><!-- /row -->
    </main>

    <!-- Optional Footer -->
    <?php include BASE_PATH . 'templates/footer.php'; ?>
  </div><!-- /flex-grow-1 -->
</div><!-- /flex layout -->

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="<?= asset('auth/js/index.js') ?>"></script>
</body>
</html>
