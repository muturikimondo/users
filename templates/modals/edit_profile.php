<?php
// coop/templates/modals/edit_profile.php

$departments = [];
$department_stmt = $conn->prepare("SELECT * FROM departments ORDER BY name ASC");
$department_stmt->execute();
$department_result = $department_stmt->get_result();
if ($department_result && $department_result->num_rows > 0) {
  while ($row = $department_result->fetch_assoc()) {
    $departments[] = $row;
  }
}

$sections = [];
$section_stmt = $conn->prepare("SELECT * FROM sections ORDER BY name ASC");
$section_stmt->execute();
$section_result = $section_stmt->get_result();
if ($section_result && $section_result->num_rows > 0) {
  while ($row = $section_result->fetch_assoc()) {
    $sections[] = $row;
  }
}
?>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-glass border-0 rounded-4 shadow-lg">

      <!-- Modal Header -->
      <div class="modal-header modal-glass-header text-white rounded-top-4">
        <h5 class="modal-title d-flex align-items-center gap-2" id="editProfileModalLabel">
          <i class="bi bi-pencil-square"></i> Edit Profile
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Form -->
      <form id="editProfileForm" enctype="multipart/form-data" novalidate>
  <div class="modal-body profile-edit-container">

    <!-- Username -->
    <div class="form-floating position-relative mb-3">
      <input type="text" class="form-control icon-input" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
      <label for="username"><i class="bi bi-person floating-icon"></i> Username</label>
    </div>

    <!-- Email -->
    <div class="form-floating position-relative mb-3">
      <input type="email" class="form-control icon-input" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      <label for="email"><i class="bi bi-envelope floating-icon"></i> Email address</label>
    </div>

    <!-- Department -->
    <div class="form-floating position-relative mb-3">
      <select class="form-select select2" id="department_id" name="department_id" data-placeholder="Select department" required>
        <option></option>
        <?php foreach ($departments as $department): ?>
          <option value="<?= (int) $department['id'] ?>">
            <?= htmlspecialchars($department['id']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <label for="department_id"><i class="bi bi-building floating-icon"></i> Department</label>
    </div>

    <!-- Section -->
    <div class="form-floating position-relative mb-3">
      <select class="form-select select2" id="section_id" name="section_id" data-placeholder="Select section" required>
  <option></option> <!-- Sections will be populated dynamically -->
</select>

      <label for="section_id"><i class="bi bi-diagram-3 floating-icon"></i> Section</label>
    </div>

    <!-- Profile Photo -->
    <div class="mb-4">
      <label for="photo" class="form-label fw-semibold">Profile Photo</label>
      <div class="position-relative d-inline-block w-100">
        <img id="photoPreview"
             src="<?= asset($user['photo'] ?? 'core/uploads/photos/default.png') ?>"
             class="rounded-circle shadow-sm border border-2 d-block mx-auto"
             style="width: 100px; height: 100px; object-fit: cover;"
             alt="Preview">
        <input type="file" id="photo" name="photo" accept="image/*" class="form-control glass-file mt-2 w-100" style="max-width: 300px; display: block; margin: 10px auto 0;" />
        <div class="form-text small text-center">Max 2MB. JPG, PNG supported.</div>
      </div>
    </div>

    <!-- Change Password Switch -->
    <div class="form-check form-switch mb-3 profile-switch-group">
      <input class="form-check-input profile-switch" type="checkbox" role="switch" id="togglePassword">
      <label class="form-check-label profile-switch-label" for="togglePassword">Change Password</label>
    </div>

    <!-- Password Fields -->
    <!-- Password Fields -->
<div class="row g-3 mb-4" id="passwordFields" style="display: none;">
  <div class="col-md-6 form-floating position-relative">
    <input type="password" class="form-control icon-input toggleable-password" id="password" name="password">
    <label for="password"><i class="bi bi-key floating-icon"></i> New Password</label>
    <i class="bi bi-eye-fill toggle-password-eye position-absolute top-50 end-0 translate-middle-y me-3" data-toggle="#password"></i>
  </div>
  <div class="col-md-6 form-floating position-relative">
    <input type="password" class="form-control icon-input toggleable-password" id="confirm_password" name="confirm_password">
    <label for="confirm_password"><i class="bi bi-key-fill floating-icon"></i> Confirm Password</label>
    <i class="bi bi-eye-fill toggle-password-eye position-absolute top-50 end-0 translate-middle-y me-3" data-toggle="#confirm_password"></i>
  </div>
</div>

  </div>

  <!-- Modal Footer -->
  <div class="modal-footer border-top-0 d-flex justify-content-end gap-2">
    <button type="submit" class="btn btn-glass-success px-4 rounded-pill d-flex align-items-center gap-2">
      <i class="bi bi-check-circle-fill"></i> Save Changes
    </button>
    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
      Cancel
    </button>
  </div>
</form>


    </div>
  </div>
</div>

