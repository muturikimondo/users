<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . 'includes/db.php';

$pageTitle = "Pending Approvals";
include BASE_PATH . 'templates/header.php';
?>

<!-- Sidebar Offcanvas Toggle Button for Small Screens -->
<button class="btn btn-outline-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
  <i class="bi bi-list"></i> Menu
</button>

<div class="d-flex flex-column flex-md-row min-vh-100">

  <!-- Sidebar (Offcanvas on small screens) -->
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
  <div class="col-md-8">
    <?php
      include BASE_PATH . 'templates/components/page_section_header.php';
      renderSectionHeader([
        'icon' => 'bi-hourglass-split',
        'title' => 'Pending User Approvals',
        'badge' => 'Approval Queue',
        'subtitle' => 'Review and take action on new user registrations.'
      ]);
    ?>
  </div>
</div>


      <div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card card-glass p-4 mt-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-gradient-header rounded-top shadow-sm">
  <tr>
    <th>Photo</th>
    <th>Username</th>
    <th>Email</th>
    <th>Status</th>
    <th class="text-center">Actions</th>
  </tr>
</thead>

          <tbody id="pendingUsersTableBody">
            <!-- Populated via AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>





        <div class="d-flex justify-content-center mt-3">
          <nav>
            <ul class="pagination" id="paginationControls"></ul>
          </nav>
        </div>
      </div>
    </main>

    <!-- Sticky Footer -->
    <footer class="mt-auto">
      <?php include BASE_PATH . 'templates/footer.php'; ?>
    </footer>
  </div>
</div>

<!-- Modals -->
<?php include BASE_PATH . 'templates/modals/approve_user_modal.php'; ?>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
  <div id="approvalToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="approvalToastMsg">Processing...</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script type="module" src="<?= asset('admin/js/index.js') ?>"></script>
