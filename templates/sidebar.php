<!-- templates/layout/sidebar.php -->
<aside class="sidebar-glass d-flex flex-column flex-shrink-0 p-3" style="width: 260px; min-height: 100vh;">
  <a href="<?= asset('admin/dashboard.php') ?>" 
     class="d-flex align-items-center mb-4 text-decoration-none text-white sidebar-brand">
    <i class="bi bi-shield-lock-fill me-2 fs-4"></i>
    <span class="fs-5 fw-bold">Admin Panel</span>
  </a>

  <ul class="nav nav-pills flex-column gap-2 mb-auto">
    <li>
      <a href="<?= asset('admin/dashboard.php') ?>" 
         class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : 'text-white-50' ?>">
        <i class="bi bi-house-door-fill me-2"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="<?= asset('admin/approvals.php') ?>" 
         class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'approvals.php' ? 'active' : 'text-white-50' ?>">
        <i class="bi bi-hourglass-top me-2"></i> Approvals
      </a>
    </li>
    <li>
      <a href="#" class="nav-link text-white-50" onclick="confirmNavigation('<?= asset('auth/register.php') ?>'); return false;">
        <i class="bi bi-person-plus-fill me-2"></i> Register
      </a>
    </li>
<li>
  <a href="<?= asset('profile/view_profile.php') ?>" 
     class="nav-link text-white-50"
     onclick="return confirmNavigation(this.href);">
    <i class="bi bi-person-circle me-2"></i> Profiles
  </a>
</li>

<li>
  <a href="<?= asset('auth/logout.php') ?>" 
     class="nav-link text-white-50" 
     onclick="return confirmLogout(this.href);">
    <i class="bi bi-box-arrow-right me-2"></i> Logout
  </a>
</li>


    
  </ul>
</aside>

<!-- Confirmation Scripts -->
<script>
function confirmNavigation(url) {
  bootbox.confirm({
    title: "Confirm Registration",
    message: "Proceed to user registration?",
    buttons: {
      confirm: { label: 'Yes', className: 'btn-success' },
      cancel: { label: 'No', className: 'btn-secondary' }
    },
    callback: function (result) {
      if (result) window.location.href = url;
    }
  });
}



function confirmNavigation(url) {
    if (confirm("Are you sure you want to open the Profiles page?")) {
      window.location.href = url;
    }
    return false; // Prevent default link behavior
  }

  function confirmLogout(url) {
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = url;
      return false;
    }
    return false;
  }





</script>
