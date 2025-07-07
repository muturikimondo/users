// coop/profile/js/users/photoPreview.js

export function setupPhotoPreview() {
  const photoInput = document.querySelector('input[type="file"][name="photo"]');
  const previewImg = document.getElementById('photoPreview');

  if (!photoInput || !previewImg) return;

  photoInput.addEventListener('change', () => {
    const file = photoInput.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        previewImg.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
}
