document.addEventListener("DOMContentLoaded", () => {
  // Toggle password visibility
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const passwordIcon = document.getElementById('passwordIcon');

  togglePassword.addEventListener('click', () => {
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    passwordIcon.classList.toggle('bi-eye');
    passwordIcon.classList.toggle('bi-eye-slash');
  });

  // Handle login form submission
  document.querySelector('#loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.querySelector('#email').value.trim();
    const password = document.querySelector('#password').value.trim();
    const remember = document.querySelector('#rememberMe').checked;

    const loginText = document.querySelector('#loginText');
    const spinner = document.querySelector('#spinner');
    const errorMessage = document.querySelector('#errorMessage');

    loginText.textContent = 'Logging in...';
    spinner.classList.remove('d-none');
    errorMessage.style.display = 'none';

    $.ajax({
      url: BASE_URL + 'auth/ajax/login_process.php',
      method: 'POST',
      data: { email, password, remember }, // ✅ email instead of username
      dataType: 'json',
      success: function (response) {
        loginText.textContent = 'Login';
        spinner.classList.add('d-none');

        if (response.success) {
          window.location.href = response.redirect;
        } else {
          errorMessage.textContent = response.message || 'Login failed. Please try again.';
          errorMessage.style.display = 'block';
        }
      },
      error: function () {
        loginText.textContent = 'Login';
        spinner.classList.add('d-none');
        errorMessage.textContent = 'An error occurred. Please try again.';
        errorMessage.style.display = 'block';
      }
    });
  });
});
