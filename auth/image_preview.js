// imagePreview.js

document.addEventListener("DOMContentLoaded", () => {
  const photoInput = document.getElementById("photo");
  const photoPreview = document.getElementById("photoPreview"); // Assuming you have an img tag with id 'photoPreview'

  // Handle the image preview on file selection
  photoInput.addEventListener("change", () => {
    const file = photoInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(event) {
        // Display the preview image
        photoPreview.src = event.target.result;
        photoPreview.style.display = "block"; // Ensure the preview is visible
      };
      reader.readAsDataURL(file); // Convert the image to base64 and display it
    } else {
      photoPreview.style.display = "none"; // Hide preview if no file selected
    }
  });
});
