import { initSelect2 } from './users/select2.js';
import { setupTogglePassword } from './users/togglePassword.js';
import { setupPhotoPreview } from './users/photoPreview.js';
import { setupDependentSections } from './users/getSectionsByDepartment.js';


document.addEventListener('DOMContentLoaded', () => {
  initSelect2('.select2');
  setupTogglePassword();
  setupPhotoPreview();
  setupDependentSections();
});
