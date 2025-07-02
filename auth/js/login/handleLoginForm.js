export function setupLoginFormHandler(BASE_URL) {
  const loginForm = document.getElementById("loginForm");
  if (!loginForm) return;

  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = loginForm.email.value.trim();
    const password = loginForm.password.value;

    if (!email || !password) {
      showToast('warning', 'Please enter both email and password.');
      return;
    }

    try {
      const res = await fetch(`${BASE_URL}auth/ajax/login_process.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();

      if (data.success) {
        showToast('success', 'Login successful. Redirecting...');
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 1000);
      } else {
        showToast('danger', data.message || 'Login failed.');
      }
    } catch (err) {
      console.error(err);
      showToast('danger', 'An unexpected error occurred.');
    }
  });
}
