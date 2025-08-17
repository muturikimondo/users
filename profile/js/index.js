// index.js — main entry point

import { initSelect2, refreshSelect2 } from './users/select2.js';
import { setupTogglePassword } from './users/togglePassword.js';
import { setupPhotoPreview } from './users/photoPreview.js';
import { setupDepartmentSectionSelects } from './users/getSectionsByDepartment.js';
import { setupProfileUpdateForm } from './users/updateUser.js';

document.addEventListener('DOMContentLoaded', () => {
  initSelect2();

  const departmentIdSelector = "#department_id";
  const sectionIdSelector = "#section_id";

  if (document.querySelector(departmentIdSelector) && document.querySelector(sectionIdSelector)) {
    if (typeof CURRENT_SECTION_ID !== 'undefined') {
      setupDepartmentSectionSelects(departmentIdSelector, sectionIdSelector, CURRENT_SECTION_ID);
    } else {
      setupDepartmentSectionSelects(departmentIdSelector, sectionIdSelector);
    }
  }

  setupProfileUpdateForm();
});

// 🔁 Re-initialize components when the modal opens
const modal = document.getElementById('editProfileModal');
if (modal) {
  modal.addEventListener('shown.bs.modal', () => {
    setupTogglePassword();
    setupPhotoPreview();

    const departmentIdSelector = "#department_id";
    const sectionIdSelector = "#section_id";

    // 🔁 Reinitialize select2 inside modal to ensure options are styled
    refreshSelect2(departmentIdSelector);
    refreshSelect2(sectionIdSelector);

    // Re-attach section loader logic
    if (document.querySelector(departmentIdSelector) && document.querySelector(sectionIdSelector)) {
      if (typeof CURRENT_SECTION_ID !== 'undefined') {
        setupDepartmentSectionSelects(departmentIdSelector, sectionIdSelector, CURRENT_SECTION_ID);
      } else {
        setupDepartmentSectionSelects(departmentIdSelector, sectionIdSelector);
      }
    }
  });
}
