export function setupTogglePassword() {
  // Attach toggle behavior to all eye icons
  document.querySelectorAll('.toggle-password-eye').forEach(icon => {
    icon.addEventListener('click', () => {
      const targetSelector = icon.getAttribute('data-toggle');
      const input = document.querySelector(targetSelector);

      if (input) {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bi-eye-fill', !isPassword);
        icon.classList.toggle('bi-eye-slash-fill', isPassword);
      }
    });
  });

  // Also manage the toggle switch
  const toggle = document.getElementById('togglePassword');
  const fields = document.getElementById('passwordFields');

  if (toggle && fields) {
    toggle.addEventListener('change', () => {
      fields.style.display = toggle.checked ? 'flex' : 'none';
    });
  }

  const modal = document.getElementById('editProfileModal');
  if (modal) {
    modal.addEventListener('hidden.bs.modal', () => {
      if (toggle) toggle.checked = false;
      if (fields) fields.style.display = 'none';

      // Reset password fields and icons
      document.querySelectorAll('.toggleable-password').forEach(field => {
        field.type = 'password';
      });
      document.querySelectorAll('.toggle-password-eye').forEach(icon => {
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
      });
    });
  }
}
