// coop/auth/js/register/checkEmail.js
export function setupEmailChecker() {
  const emailField = document.getElementById("email");

  if (!emailField) return;

  emailField.addEventListener("blur", () => {
    const email = emailField.value.trim();
    if (email === "") return;

    $.ajax({
      url: BASE_URL + "auth/ajax/register/check_email.php",
      method: "POST",
      data: { email },
      success: function(response) {
        const res = JSON.parse(response);
        if (res.exists === false || res.available === true) {
          emailField.classList.remove("is-invalid");
        } else {
          alert("This email is already in use.");
          emailField.classList.add("is-invalid");
        }
      },
      error: function() {
        console.error("Email check failed.");
      }
    });
  });
}
