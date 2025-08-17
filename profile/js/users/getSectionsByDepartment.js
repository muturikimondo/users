// getSectionsByDepartment.js
import { refreshSelect2 } from './select2.js';

let isBound = false;

export function setupDepartmentSectionSelects(departmentSelector, sectionSelector, preSelectedSectionId = null) {
  console.log("Initializing setupDepartmentSectionSelects...");

  if (isBound) {
    console.log("Event listener already bound. Skipping...");
    return;
  }

  isBound = true;

  const departmentSelect = document.querySelector(departmentSelector);
  const sectionSelect = document.querySelector(sectionSelector);

  if (!departmentSelect || !sectionSelect) {
    console.error("Department or Section select element not found.");
    return;
  }

  console.log("Initial department value:", departmentSelect.value);
  console.log("Listening for department dropdown changes...");

  const loadSections = async (departmentId) => {
    if (!departmentId) {
      sectionSelect.innerHTML = `<option value="">Select Section</option>`;
      refreshSelect2(sectionSelector); // Reset the select2
      return;
    }

    try {
      const response = await fetch(`/coop/profile/ajax/get_sections_by_department.php?department_id=${departmentId}`);
      const data = await response.json();

      // Empty current options
      sectionSelect.innerHTML = '';

      if (data.length === 0) {
        sectionSelect.innerHTML = `<option value="">No sections available</option>`;
      } else {
        sectionSelect.innerHTML = `<option value="">Select Section</option>`;
        data.forEach(section => {
          const option = document.createElement('option');
          option.value = section.id;
          option.textContent = section.name;

          if (preSelectedSectionId && section.id === preSelectedSectionId.toString()) {
            option.selected = true;
          }

          sectionSelect.appendChild(option);
        });
      }

      refreshSelect2(sectionSelector);
    } catch (error) {
      console.error("Error loading sections:", error);
    }
  };

  // Initial load if department is already selected
  if (departmentSelect.value) {
    loadSections(departmentSelect.value);
  }

  departmentSelect.addEventListener('change', () => {
    const selectedDeptId = departmentSelect.value;
    console.log("Department changed to:", selectedDeptId);
    loadSections(selectedDeptId);
  });
}
