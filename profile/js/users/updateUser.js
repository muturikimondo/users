// js/users/updateUser.js
export function setupProfileUpdateForm() {
  const form = document.getElementById('editProfileForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Saving...`;

    const formData = new FormData(form);

    try {
      const response = await fetch(`${BASE_URL}profile/ajax/update_profile.php`, {
        method: 'POST',
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        // Close modal, show success toast/snackbar/etc.
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
        modal.hide();

        // Optionally reload or update the profile view
        window.location.reload();
      } else {
        showToast(result.message || 'Update failed.', 'danger');
      }
    } catch (error) {
      console.error('Update error:', error);
      showToast('An error occurred. Please try again.', 'danger');
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = `<i class="bi bi-check-circle-fill"></i> Save Changes`;
    }
  });
}

// Basic toast function (optional, replace with your UI)
function showToast(message, type = 'info') {
  alert(`[${type.toUpperCase()}] ${message}`); // Replace with your custom toast
}
