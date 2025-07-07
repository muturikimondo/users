<?php
$roles = [];
$role_stmt = $conn->prepare("SELECT id, name, description FROM roles ORDER BY name ASC");
$role_stmt->execute();
$role_result = $role_stmt->get_result();
if ($role_result && $role_result->num_rows > 0) {
  while ($row = $role_result->fetch_assoc()) {
    $roles[] = $row;
  }
}
?>

<div class="modal fade" id="approveUserModal" tabindex="-1" aria-labelledby="approveUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-glass border-0 rounded-4 shadow-lg">

      <!-- Modal Header -->
      <div class="modal-header modal-glass-header text-white rounded-top-4">
        <h5 class="modal-title d-flex align-items-center gap-2" id="approveUserModalLabel">
          <i class="bi bi-person-check-fill"></i> Approve User & Assign Role
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Form -->
      <form id="approveUserForm">
        <div class="modal-body">

          <!-- Hidden User ID -->
          <input type="hidden" id="approveUserId" name="user_id">

          <!-- Role Selector -->
          <div class="profile-input-group">
            <label for="role_id" class="profile-label d-flex align-items-center gap-2">
              <i class="bi bi-award"></i> Select Role
            </label>
            <select 
              id="role_id" 
              name="role_id" 
              class="form-select select2 profile-input" 
              required 
              data-placeholder="Choose role"
            >
              <option></option>
              <?php foreach ($roles as $role): ?>
                <option value="<?= (int) $role['id'] ?>">
                  <?= htmlspecialchars($role['name']) ?> — <?= htmlspecialchars($role['description']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text mt-1 text-light opacity-75">
              Assign the most appropriate role for this user’s responsibilities.
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer border-top-0 d-flex justify-content-end gap-2">
          <button type="submit" class="profile-submit-btn d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> Approve User
          </button>
          <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
