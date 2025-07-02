export function setupPhotoPreview() {
  const input = document.getElementById("photo");
  const preview = document.getElementById("photoPreview");

  if (!input || !preview) return;

  input.addEventListener("change", function () {
    const file = input.files[0];
    if (!file) return;

    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    const maxSizeMB = 2;

    if (!allowedTypes.includes(file.type)) {
      showToast('error', 'Only JPG, PNG, or WebP images are allowed.');
      input.value = '';
      return;
    }

    if (file.size > maxSizeMB * 1024 * 1024) {
      showToast('error', `Image size must be under ${maxSizeMB}MB.`);
      input.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = () => preview.src = reader.result;
    reader.readAsDataURL(file);
  });
}
