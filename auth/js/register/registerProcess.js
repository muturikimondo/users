// coop/auth/js/register/registerProcess.js
import { validateForm } from './validateForm.js';
import { showToast } from './toast.js';

export function setupRegisterHandler() {
  const REGISTER_URL = '../../register/register_process.php';
  const form = document.getElementById("registerForm");

  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    const submitBtn = form.querySelector("button[type=submit]");
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch(REGISTER_URL, {
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
