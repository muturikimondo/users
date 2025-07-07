export function setupDependentSections() {
  const departmentSelect = document.getElementById('department_id');
  const sectionSelect = document.getElementById('section_id');

  if (!departmentSelect || !sectionSelect) return;

  departmentSelect.addEventListener('change', () => {
    const departmentId = departmentSelect.value;

    sectionSelect.innerHTML = '<option></option>';
    $(sectionSelect).val(null).trigger('change');

    if (!departmentId) return;

    fetch(`${BASE_URL}profile/ajax/get_sections_by_department.php?department_id=${departmentId}`)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          console.error('Error:', data.error);
          return;
        }

        data.forEach(section => {
          const opt = document.createElement('option');
          opt.value = section.id;
          opt.textContent = section.name;
          sectionSelect.appendChild(opt);
        });

        $(sectionSelect).trigger('change');
      })
      .catch(err => console.error('Failed to fetch sections:', err));
  });
}
