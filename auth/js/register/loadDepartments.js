export function setupDepartmentSectionDropdowns(BASE_URL) {
  const deptSelect = $('#department');
  const sectionSelect = $('#section');

  // Load departments from backend
  fetch(`${BASE_URL}auth/ajax/register/fetch_departments.php`)
    .then(res => res.json())
    .then(data => {
      deptSelect.empty().append('<option></option>'); // Clear + blank option for Select2
      data.forEach(d => {
        deptSelect.append(`<option value="${d.id}">${d.name}</option>`);
      });
      deptSelect.trigger('change.select2');
    })
    .catch(err => {
      console.error("Failed to load departments:", err);
    });

  // When a department is selected, fetch its sections
  deptSelect.on('change', function () {
    const deptId = $(this).val();
    sectionSelect.empty().append('<option></option>');

    if (!deptId) return;

    fetch(`${BASE_URL}auth/ajax/register/fetch_sections.php`, {
  method: "POST",
  headers: {
    "Content-Type": "application/x-www-form-urlencoded",
  },
  body: `department_id=${encodeURIComponent(deptId)}`
})
  .then(res => res.json())
  .then(data => {
    sectionSelect.empty().append('<option></option>'); // Clear and placeholder

    if (data.length === 0) {
      sectionSelect.append(`<option disabled>No sections found</option>`);
    } else {
      data.forEach(s => {
        sectionSelect.append(`<option value="${s.id}">${s.name}</option>`);
      });
    }

    sectionSelect.trigger('change.select2'); // Reinitialize Select2
  })
  .catch(err => {
    console.error("Failed to load sections:", err);
  });

  });
}
