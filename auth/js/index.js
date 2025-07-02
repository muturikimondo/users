// Import core functions
import { validateForm } from './register/validateForm.js';
import { setupPasswordToggle } from './register/passwordToggle.js';
import { setupPhotoPreview } from './register/photoPreview.js';
import { showToast } from './register/toast.js';
import { setupRegisterFormHandlers } from './register/formHandlers.js';
import { setupDepartmentSectionDropdowns } from './register/loadDepartments.js';
import { setupLoginFormHandler } from './login/handleLoginForm.js';
import { initSelect2 } from './register/select2.js'; // Import Select2 configuration

// Import Select2 from CDN
import 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';

document.addEventListener("DOMContentLoaded", () => {
  // Initialize Select2 with custom settings
  initSelect2('.select2', 'Choose an option');

  // Initialize password toggle functionality
  setupPasswordToggle();

  // Initialize profile photo preview
  setupPhotoPreview();

  // Initialize login form handler (if the login form exists)
  setupLoginFormHandler(BASE_URL);

  // Initialize register form validation and submission with toast notifications
  setupRegisterFormHandlers();

  // Dynamically load departments and dependent sections
  setupDepartmentSectionDropdowns(BASE_URL);

  // Double-check form validation before submission
  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      if (!validateForm()) {
        e.preventDefault(); // Prevent form submission if invalid
      } else {
        showToast('info', 'Submitting registration form...');
      }
    });
  }

  // Initialize Bootstrap tooltips for elements with data-bs-toggle="tooltip"
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});
