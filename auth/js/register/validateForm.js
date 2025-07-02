// coop/auth/js/register/validateForm.js
import { showToast } from './toast.js';

export function validateForm() {
  const form = document.getElementById('registerForm');
  const password = form.password.value.trim();
  const username = form.username.value.trim();
  const email = form.email.value.trim();
  const dept = form.department_id.value;

  const errors = [];

  if (username.length < 3) {
    errors.push("Username must be at least 3 characters.");
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    errors.push("Enter a valid email address.");
  }

  const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  if (!strongPassword.test(password)) {
    errors.push("Password must have 8+ characters, including UPPERCASE, number, and symbol.");
  }

  if (!dept) {
    errors.push("Please select a department.");
  }

  if (errors.length > 0) {
    showToast('error', errors[0]); // Only show the first error at a time
    return false;
  }

  return true;
}
