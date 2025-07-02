<?php
// This modal should be included after DB connection is already available.
$roles = [];
$role_stmt = $conn->prepare("SELECT id, name, description FROM roles ORDER BY name ASC");
$role_stmt->execute();
$role_result = $role_stmt->get_result();
while ($row = $role_result->fetch_assoc()) {
    $roles[] = $row;
}
?>

<div class="modal fade" id="approveUserModal" tabindex="-1" aria-labelledby="approveUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-glass border-0 rounded-4 shadow-lg">

      <!-- Modal Header -->
      <div class="modal-header modal-glass-header text-white rounded-top-4">
        <h5 class="modal-title" id="approveUserModalLabel">
          <i class="bi bi-person-check-fill me-2"></i> Approve User & Assign Role
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Form -->
      <form id="approveUserForm">
        <div class="modal-body">

          <input type="hidden" id="approveUserId" name="user_id">

          <!-- Role Selector -->
          <div class="mb-3">
            <label for="role_id" class="form-label fw-semibold text-dark">
              <i class="bi bi-award me-1"></i> Select Role
            </label>
            <select id="role_id" name="role_id" class="form-select select2 rounded-pill" required data-placeholder="Choose role">
              <option></option>
              <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>">
                  <?= htmlspecialchars($role['name']) ?> — <?= htmlspecialchars($role['description']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text text-muted mt-1">
              Assign the role this user should assume upon approval.
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer border-top-0">
          <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Approve User
          </button>
          <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
