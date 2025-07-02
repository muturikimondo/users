export function setupPagination() {
  fetchAndRenderUsers(1); // Initial load

  document.addEventListener('click', function (e) {
    const target = e.target.closest('.page-link[data-page]');
    if (target) {
      e.preventDefault();
      const page = parseInt(target.getAttribute('data-page'), 10);
      if (!isNaN(page)) {
        fetchAndRenderUsers(page);
      }
    }
  });
}

function fetchAndRenderUsers(page = 1) {
  $.getJSON('ajax/users/fetch_pending.php?page=' + page, function (data) {
    const tbody = $('#pendingUsersTableBody').empty();
    const pagination = $('#paginationControls').empty();

    if (data.users.length === 0) {
      tbody.append(`<tr><td colspan="5" class="text-center text-muted">No pending approvals at the moment.</td></tr>`);
      return;
    }

    data.users.forEach(user => {
      const row = `
        <tr data-user-id="${user.id}">
          <td><img src="${user.photo_url}" class="rounded-circle shadow-sm" style="width:50px; height:50px; object-fit:cover;" /></td>
          <td>${escapeHTML(user.username)}</td>
          <td>${escapeHTML(user.email)}</td>
          <td><span class="badge bg-warning text-dark">${escapeHTML(user.status)}</span></td>
         <td class="text-center">
  <div class="d-flex justify-content-center flex-wrap gap-2">
    <button class="btn btn-sm btn-success approve-user-btn" data-bs-toggle="tooltip" title="Approve">
      <i class="bi bi-check-circle-fill"></i>
    </button>
    <button class="btn btn-sm btn-danger reject-user-btn" data-bs-toggle="tooltip" title="Reject">
      <i class="bi bi-x-circle-fill"></i>
    </button>
  </div>
</td>

        </tr>`;
      tbody.append(row);
    });

    // Custom styled pagination
    const current = data.currentPage;
    const total = data.totalPages;

    const createBtn = (page, iconClass, label, disabled = false) => `
      <li class="page-item ${disabled ? 'disabled' : ''}">
        <a class="page-link rounded-pill" href="#" data-page="${page}" title="${label}">
          <i class="${iconClass}"></i>
        </a>
      </li>`;

    // First and Previous
    pagination.append(createBtn(1, 'bi bi-chevron-double-left', 'First', current === 1));
    pagination.append(createBtn(current - 1, 'bi bi-chevron-left', 'Previous', current === 1));

    // Page numbers (around current)
    const range = 2;
    const start = Math.max(1, current - range);
    const end = Math.min(total, current + range);

    for (let i = start; i <= end; i++) {
      pagination.append(`
        <li class="page-item ${i === current ? 'active' : ''}">
          <a class="page-link rounded-pill" href="#" data-page="${i}">${i}</a>
        </li>`);
    }

    // Next and Last
    pagination.append(createBtn(current + 1, 'bi bi-chevron-right', 'Next', current === total));
    pagination.append(createBtn(total, 'bi bi-chevron-double-right', 'Last', current === total));

    // Rebind tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].forEach(el => new bootstrap.Tooltip(el));
  });
}

// Escape HTML utility
function escapeHTML(str) {
  return $('<div>').text(str).html();
}
