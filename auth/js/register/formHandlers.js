// coop/auth/js/register/formHandlers.js

import { validateForm } from './validateForm.js';
import { showToast } from './toast.js';



export function setupRegisterFormHandlers() {
  const form = document.getElementById("registerForm");
  const emailField = document.getElementById("email");

  // Email availability check
  if (emailField) {
    emailField.addEventListener("blur", () => {
      const email = emailField.value.trim();
      if (email === "") return;

      $.ajax({
        url: BASE_URL + "auth/ajax/register/check_email.php",
        method: "POST",
        data: { email },
        success: function (response) {
          const res = JSON.parse(response);
          if (res.exists === false || res.available === true) {
            emailField.classList.remove("is-invalid");
          } else {
            showToast("error", "This email is already in use.");
            emailField.classList.add("is-invalid");
          }
        },
        error: function () {
          showToast("error", "Could not verify email.");
          console.error("Email check failed.");
        }
      });
    });
  }

  // Register form submission (AJAX)
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    const submitBtn = form.querySelector("button[type=submit]");
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch(BASE_URL + "auth/ajax/register/register_process.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        showToast(data.status, data.message);
        if (data.status === "success") {
          form.reset();
        }
      })
      .catch(err => {
        showToast("error", "Unexpected error occurred.");
        console.error(err);
      })
      .finally(() => {
        submitBtn.disabled = false;
      });
  });
}
