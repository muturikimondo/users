<?php
include_once __DIR__ . '/../../includes/config.php';
include_once BASE_PATH . 'includes/db.php';

// Optionally load current user info for prepopulation
$user = $_SESSION['user'] ?? []; // adjust based on your session structure

// Fetch departments (for static fallback or prepopulation)
$stmt = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
$departments = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content glassmorphic p-0 border-0">

      <div class="card card-glass shadow-lg rounded-4 border-0 overflow-hidden">

        <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
          <h5 class="modal-title fw-bold text-white" id="editProfileLabel">Edit Profile</h5>
          <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="editProfileForm" enctype="multipart/form-data" novalidate>
          <div class="card-body pt-3 px-4">

            <!-- Username -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-person-fill floating-icon"></i>
              <input type="text" class="form-control icon-input" id="username" name="username"
                     placeholder="Username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
              <label for="username">Username</label>
            </div>

            <!-- Email -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-envelope-fill floating-icon"></i>
              <input type="email" class="form-control icon-input" id="email" name="email"
                     placeholder="Email Address" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
              <label for="email">Email</label>
            </div>

            <!-- Department -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-building floating-icon"></i>
              <select id="department_id" name="department_id" class="form-select icon-input select2"
                      data-placeholder="Select Department" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                  <option value="<?= $dept['id'] ?>" <?= ($dept['id'] == ($user['department_id'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="department_id">Department</label>
            </div>

            <!-- Section -->
            <div class="form-floating mb-3 position-relative">
              <i class="bi bi-diagram-3-fill floating-icon"></i>
              <select id="section_id" name="section_id" class="form-select icon-input select2"
                      data-placeholder="Select Section" required>
                <option value="">Select Section</option>
                <!-- Sections should be populated dynamically via JS based on department -->
              </select>
              <label for="section_id">Section</label>
            </div>

            <!-- Change Password Toggle -->
            <div class="form-check form-switch mb-3">
              <input type="checkbox" class="form-check-input" id="changePasswordToggle">
              <label class="form-check-label fw-semibold text-white" for="changePasswordToggle">Change Password</label>
            </div>

            <!-- Password Fields -->
            <div id="passwordSection" class="d-none">
              <div class="form-floating position-relative mb-3">
                <input type="password" class="form-control icon-input" id="newPassword" name="password" placeholder="New Password">
                <label for="newPassword">New Password</label>
                <i class="bi bi-eye-fill floating-icon end-0 me-3 toggle-eye" data-target="newPassword" style="cursor:pointer;"></i>
              </div>

              <div class="form-floating position-relative mb-3">
                <input type="password" class="form-control icon-input" id="confirmPassword" name="confirm_password" placeholder="Confirm Password">
                <label for="confirmPassword">Confirm Password</label>
                <i class="bi bi-eye-fill floating-icon end-0 me-3 toggle-eye" data-target="confirmPassword" style="cursor:pointer;"></i>
              </div>
            </div>

            <!-- Photo Upload -->
            <div class="mb-3">
              <label for="photo" class="form-label text-white">Profile Photo</label>
              <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
              <div class="mt-2">
                <img id="photoPreview"
                     src="<?= isset($user['photo']) ? htmlspecialchars($user['photo']) : '#' ?>"
                     alt="Preview"
                     class="img-thumbnail"
                     style="max-width: 150px; <?= isset($user['photo']) ? '' : 'display: none;' ?>">
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
