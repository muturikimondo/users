// togglePassword.js

export function setupTogglePassword() {
  const toggleCheckbox = document.getElementById('changePasswordToggle');
  const passwordSection = document.getElementById('passwordSection');
  const toggleIcons = document.querySelectorAll('.toggle-eye');

  // Handle show/hide of password section
  if (toggleCheckbox && passwordSection) {
    toggleCheckbox.addEventListener('change', () => {
      passwordSection.classList.toggle('d-none', !toggleCheckbox.checked);
    });
  }

  // Handle show/hide for each password field
  toggleIcons.forEach(icon => {
    const targetId = icon.getAttribute('data-target');
    const input = document.getElementById(targetId);

    if (input) {
      icon.addEventListener('click', () => {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bi-eye-fill', !isPassword);
        icon.classList.toggle('bi-eye-slash-fill', isPassword);
      });
    }
  });
}
