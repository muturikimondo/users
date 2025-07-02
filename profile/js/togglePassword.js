document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.toggle-password');
  const passwordField = document.querySelector('#password');

  if (toggle && passwordField) {
    toggle.addEventListener('click', () => {
      const type = passwordField.type === 'password' ? 'text' : 'password';
      passwordField.type = type;
      toggle.classList.toggle('bi-eye');
      toggle.classList.toggle('bi-eye-slash');
    });
  }
});
