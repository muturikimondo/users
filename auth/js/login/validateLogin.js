// coop/auth/js/login/validateLogin.js

export function validateLogin() {
  const email = document.getElementById("email")?.value.trim();
  const password = document.getElementById("password")?.value.trim();

  return (
    email.length > 5 &&
    password.length > 3 &&
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
  );
}
