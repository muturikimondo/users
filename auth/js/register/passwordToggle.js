// coop/auth/js/register/passwordToggle.js

export function setupPasswordToggle() {
  const passwordField = $('#password');
  const toggleIcon = $('.toggle-password');
  const hint = $('#passwordHint');

  // Toggle visibility
  toggleIcon.on('click', function () {
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    toggleIcon.toggleClass('bi-eye-fill bi-eye-slash-fill');
  });

  // Live password strength feedback
  passwordField.on('input', function () {
    const val = $(this).val();
    const checks = [
      /[A-Z]/.test(val),
      /[a-z]/.test(val),
      /[0-9]/.test(val),
      /[@$!%*?&]/.test(val),
      val.length >= 8
    ];
    const validCount = checks.filter(Boolean).length;

    hint.removeClass('text-danger text-warning text-success');

    if (validCount <= 2) {
      hint.addClass('text-danger').html("Weak — try adding uppercase, numbers, and symbols.");
    } else if (validCount === 3 || validCount === 4) {
      hint.addClass('text-warning').html("Moderate — almost there.");
    } else if (validCount === 5) {
      hint.addClass('text-success').html("Strong password.");
    }
  });

  // Activate Bootstrap 5 tooltips
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
}
