<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/sessions.php';
require_once __DIR__ . '/../includes/db.php';
checkUserSession(); // Ensure the user is logged in
?>
<?php include_once __DIR__ . '/../templates/header.php'; ?>

<style>
/* Glassmorphic styling */
.glassmorphic {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}
.user-card {
    transition: transform 0.2s ease;
}
.user-card:hover {
    transform: translateY(-5px);
}
</style>

<div class="container-fluid mt-4">
    <h4 class="mb-4">User Management</h4>

    <!-- Users Grid -->
    <div class="row" id="usersGrid">
        <!-- AJAX-loaded user cards will appear here -->
    </div>

    <!-- Pagination Controls -->
    <div id="paginationWrapper" class="mt-3">
        <!-- AJAX-loaded pagination buttons will appear here -->
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editUserForm" enctype="multipart/form-data">
            <div class="modal-content glassmorphic">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUserId">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
    <label for="editRole" class="form-label">Role</label>
    <select name="role_id" id="editRole" class="form-select"
            data-selected="<?php echo htmlspecialchars($user['role_id']); ?>" required>
        <option value="">Loading...</option>
    </select>
</div>

                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department</label>
                            <select id="department_id" name="department_id" class="form-select select2" required>
                                <option value="">Loading departments...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="section_id" class="form-label">Section</label>
                            <select id="section_id" name="section_id" class="form-select" required>
                                <option value="">Select department first</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Profile Photo</label>
                        <input type="file" name="photo" id="editPhoto" class="form-control">
                        <div class="mt-2">
                            <img id="currentPhotoPreview" src="" alt="Profile Photo" width="80" height="80" class="rounded-circle border">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="editStatus" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

               <div class="mb-3">
  <button type="button" class="btn btn-sm btn-outline-light rounded-pill" id="togglePasswordFields">
    Change Password
  </button>
</div>

<div id="passwordFields" style="display:none;">
  <div class="mb-3">
  <label for="editPassword" class="form-label">New Password</label>
  <div class="input-group">
    <input type="password" class="form-control" id="editPassword" name="password" placeholder="Enter new password">
    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="editPassword">
      <i class="bi bi-eye"></i>
    </button>
  </div>
</div>

<div class="mb-3">
  <label for="editConfirmPassword" class="form-label">Confirm Password</label>
  <div class="input-group">
    <input type="password" class="form-control" id="editConfirmPassword" name="confirm_password" placeholder="Confirm password">
    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="editConfirmPassword">
      <i class="bi bi-eye"></i>
    </button>
  </div>
</div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteUserForm">
            <div class="modal-content glassmorphic">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="deleteUserId">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>

<!-- jQuery with fallback -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
if (typeof jQuery == 'undefined') {
    document.write('<script src="<?php echo BASE_URL; ?>assets/js/jquery-3.6.0.min.js"><\/script>');
}
</script>

<!-- Custom JS -->
<script>
$(document).ready(function() {
    // ==================== USERS ====================
    function loadUsers(page) {
        $.post('ajax/get_users.php', { page: page }, function(response) {
            $('#usersGrid').html(response.users);
            $('#paginationWrapper').html(response.pagination);
        }, 'json');
    }

    // Initial load
    loadUsers(1);

    // Pagination handler
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadUsers(page);
    });

    // Open Edit Modal
    $(document).on('click', '.editUserBtn', function() {
        const id = $(this).data('id');
        $.get('ajax/edit_user.php', { id: id }, function(user) {
            $('#editUserId').val(user.id);
            $('#editUsername').val(user.username);
            $('#editEmail').val(user.email);
            $('#editStatus').val(user.status);
            $('#currentPhotoPreview').attr('src', user.photo_url);

            // Load roles and pre-select current
            loadRoles(user.role_id);

            // Load departments and sections
            loadDepartments(user.department_id, user.section_id);

            // Reset password section
            $("#passwordFields").hide().addClass("d-none");
            $("#togglePasswordFields").html('<i class="bi bi-key"></i> Change Password');
            $('#editPassword, #confirmPassword').val('');
            $('#passwordError').addClass('d-none');
            $('#passwordStrength, #passwordMatch').text("").removeClass();

            $('#editUserModal').modal('show');
        }, 'json');
    });

    // Submit Edit Form
    $('#editUserForm').submit(function(e) {
        if ($("#passwordFields").is(":visible")) {
            const password = $('#editPassword').val().trim();
            const confirmPassword = $('#confirmPassword').val().trim();
            if (password !== confirmPassword) {
                e.preventDefault();
                $('#passwordError').removeClass("d-none");
                return false;
            } else {
                $('#passwordError').addClass("d-none");
            }
        }

        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'ajax/update_user.php', // ✅ now points to update_user.php
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.status === 'success') {
                    $('#editUserModal').modal('hide');
                    loadUsers(1);
                } else {
                    alert(resp.message || "Failed to update user");
                }
            }
        });
    });

    // Open Delete Modal
    $(document).on('click', '.deleteUserBtn', function() {
        const id = $(this).data('id');
        const name = $(this).closest('.card').find('.card-title').text();
        $('#deleteUserId').val(id);
        $('#deleteUserName').text(name);
        $('#deleteUserModal').modal('show');
    });

    // Submit Delete Form
    $('#deleteUserForm').submit(function(e) {
        e.preventDefault();
        $.post('ajax/delete_user.php', $(this).serialize(), function(resp) {
            $('#deleteUserModal').modal('hide');
            loadUsers(1);
        });
    });

    // ==================== PASSWORD TOGGLE & VALIDATION ====================
    $(document).on("click", "#togglePasswordFields", function(e) {
        e.preventDefault();
        $("#passwordFields").slideToggle(300).toggleClass("d-none");

        // Swap text/icon
        if ($("#passwordFields").is(":visible")) {
            $(this).html('<i class="bi bi-x-circle"></i> Cancel Password Change');
        } else {
            $(this).html('<i class="bi bi-key"></i> Change Password');
            $('#editPassword, #confirmPassword').val('');
            $('#passwordStrength, #passwordMatch').text("").removeClass();
            $('#passwordError').addClass('d-none');
        }
    });

    // Show/Hide password fields individually
    $(document).on("click", ".toggle-password", function() {
        const target = $($(this).data("target"));
        const inputType = target.attr("type") === "password" ? "text" : "password";
        target.attr("type", inputType);
        $(this).find("i").toggleClass("bi-eye bi-eye-slash");
    });

    // Password strength checker
    $(document).on("input", "#editPassword", function() {
        const val = $(this).val();
        let strength = 0;

        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[@$!%*?&#]/.test(val)) strength++;

        const strengthText = $("#passwordStrength");
        strengthText.removeClass();

        if (!val) {
            strengthText.text("");
        } else if (strength <= 1) {
            strengthText.text("Weak password").addClass("password-weak");
        } else if (strength === 2) {
            strengthText.text("Medium strength").addClass("password-medium");
        } else {
            strengthText.text("Strong password").addClass("password-strong");
        }
    });

    // Confirm password check
    $(document).on("input", "#confirmPassword", function() {
        const password = $("#editPassword").val();
        const confirm = $(this).val();
        const matchText = $("#passwordMatch");
        matchText.removeClass();

        if (!confirm) {
            matchText.text("");
        } else if (confirm === password) {
            matchText.text("Passwords match").addClass("password-match");
        } else {
            matchText.text("Passwords do not match").addClass("password-mismatch");
        }
    });
});

// ==================== ROLE LOADER ====================
function loadRoles(selectedRoleId = null) {
    const roleSelect = document.getElementById("editRole");

    fetch("ajax/fetch_roles.php")
        .then(res => res.json())
        .then(data => {
            roleSelect.innerHTML = '<option value="">Select Role</option>';
            data.forEach(r => {
                const opt = document.createElement("option");
                opt.value = r.id;
                opt.textContent = r.name;
                if (selectedRoleId && r.id == selectedRoleId) {
                    opt.selected = true;
                }
                roleSelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error("Error loading roles:", err);
            roleSelect.innerHTML = '<option value="">Failed to load</option>';
        });
}

// ==================== DEPARTMENTS & SECTIONS ====================
function loadDepartments(selectedDepId = null, selectedSecId = null) {
    const departmentSelect = document.getElementById("department_id");
    const sectionSelect = document.getElementById("section_id");

    fetch("ajax/fetch_departments.php")
        .then(response => response.json())
        .then(data => {
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            data.forEach(dep => {
                const option = document.createElement("option");
                option.value = dep.id;
                option.textContent = dep.name;
                if (selectedDepId && dep.id == selectedDepId) {
                    option.selected = true;
                    loadSections(selectedDepId, selectedSecId);
                }
                departmentSelect.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Error loading departments:", err);
            departmentSelect.innerHTML = '<option value="">Failed to load</option>';
        });

    departmentSelect.addEventListener("change", function () {
        loadSections(this.value, null);
    });
}

function loadSections(depId, selectedSecId = null) {
    const sectionSelect = document.getElementById("section_id");
    if (!depId) {
        sectionSelect.innerHTML = '<option value="">Select department first</option>';
        return;
    }

    fetch("ajax/fetch_sections.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "department_id=" + encodeURIComponent(depId)
    })
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(sec => {
                const option = document.createElement("option");
                option.value = sec.id;
                option.textContent = sec.name;
                if (selectedSecId && sec.id == selectedSecId) {
                    option.selected = true;
                }
                sectionSelect.appendChild(option);
            });
        })
        .catch(err => {
            console.error("Error loading sections:", err);
            sectionSelect.innerHTML = '<option value="">Failed to load</option>';
        });
}
</script>



