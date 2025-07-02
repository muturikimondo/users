<?php
require_once __DIR__ . '/../includes/config.php';
include BASE_PATH . 'templates/header.php';
?>

<div class="d-flex flex-column flex-md-row min-vh-100">
  <!-- Sidebar Toggle -->
  <button class="btn btn-outline-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
    <i class="bi bi-list"></i> Menu
  </button>

  <div class="flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="card card-glass border-0 shadow-lg p-4 rounded-4" style="max-width: 480px; width: 100%;">
      <div class="text-center mb-4">
        <img src="<?= asset('core/uploads/logo/logo.png') ?>" alt="Logo" width="64">
        <h4 class="gradient-text mt-2 fw-bold">Login to Your Account</h4>
        <p class="text-muted small">Access your dashboard and manage your tasks.</p>
      </div>

      <!-- Login Form -->
      <form id="loginForm" class="needs-validation" novalidate>
        <div class="form-floating mb-3 position-relative">
          <i class="bi bi-envelope floating-icon"></i>
          <input type="email" name="email" id="email" class="form-control icon-input" placeholder="Email" required>
          <label for="email">Email address</label>
        </div>

        <div class="form-floating mb-3 position-relative">
          <i class="bi bi-lock floating-icon"></i>
          <input type="password" name="password" id="password" class="form-control icon-input" placeholder="Password" required>
          <label for="password">Password</label>
          <i class="bi bi-eye-slash toggle-password position-absolute end-0 top-50 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success gradient-btn rounded-pill shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i> Login
          </button>
        </div>
      </form>

      <div class="text-center mt-3 small text-muted">
        Need help? <a href="#">Contact support</a>
      </div>

      <!-- Toasts -->
      <div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1090;"></div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const toggle = document.getElementById('togglePassword');
    const toastContainer = document.querySelector('.toast-container');

    toggle.addEventListener('click', () => {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
      toggle.classList.toggle('bi-eye');
      toggle.classList.toggle('bi-eye-slash');
    });

    const showToast = (type = 'info', message = 'Message') => {
      const iconMap = {
        success: 'bi-check-circle-fill',
        danger: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill',
      };

      const toast = document.createElement('div');
      toast.className = `toast align-items-center text-bg-${type} border-0 show mb-2 shadow`;
      toast.setAttribute('role', 'alert');
      toast.innerHTML = `
        <div class="d-flex">
          <div class="toast-body">
            <i class="bi ${iconMap[type]} me-2"></i>${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
      toastContainer.appendChild(toast);
      setTimeout(() => toast.remove(), 6000);
    };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = form.email.value.trim();
      const password = form.password.value.trim();

      if (!email || !password) {
        showToast('warning', 'Please fill in both email and password.');
        return;
      }

      try {
        const res = await fetch(`${BASE_URL}auth/ajax/login/login_process.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email, password })
        });

        const result = await res.json();

        if (result.success) {
          showToast('success', result.message || 'Login successful!');
          setTimeout(() => {
            window.location.href = result.redirect || `${BASE_URL}dashboard.php`;
          }, 1200);
        } else {
          showToast('danger', result.message || 'Login failed.');
        }
      } catch (err) {
        console.error('Login request failed:', err);
        showToast('danger', 'Could not connect. Please try again.');
      }
    });
  });
</script>


<?php include BASE_PATH . 'templates/footer.php'; ?>
