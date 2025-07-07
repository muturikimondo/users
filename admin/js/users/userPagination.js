import { setupApproveUserModal } from './approveUserModal.js';
import { setupUserActions } from './userActions.js';

export function setupPagination() {
  // Initial load
  fetchAndRenderUsers(1);

  // Handle pagination button clicks (delegated)
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
  const baseUrl = typeof BASE_URL !== 'undefined' ? BASE_URL : '/coop/';
  console.log('Calling fetchAndRenderUsers, page:', page); // REMOVE IN PRODUCTION

  $.getJSON(`${baseUrl}admin/ajax/users/fetch_pending.php?page=${page}`, function (data) {
    console.log('Data received from server:', data); // REMOVE IN PRODUCTION

    const $tbody = $('#pendingUsersTableBody').empty();
    const $pagination = $('#paginationControls').empty();

    if (!data.users || data.users.length === 0) {
      $tbody.append(`<tr><td colspan="5" class="text-center text-muted">No pending approvals at the moment.</td></tr>`);
      return;
    }

    data.users.forEach(user => {
      const row = `
        <tr data-user-id="${user.id}">
          <td>
            <img src="${user.photo_url}" class="rounded-circle shadow-sm" 
                 style="width:50px; height:50px; object-fit:cover;" />
          </td>
          <td>${escapeHTML(user.username)}</td>
          <td>${escapeHTML(user.email)}</td>
          <td>
            <span class="badge bg-warning text-dark">
              ${escapeHTML(user.status)}
            </span>
          </td>
          <td class="text-center">
  <div class="d-flex justify-content-center flex-wrap gap-2">
    <button 
      class="btn-icon-glass approve-user-btn" 
      data-bs-toggle="tooltip" 
      data-user-id="${user.id}" 
      title="Approve"
    >
      <i class="bi bi-check-circle-fill"></i>
    </button>

    <button 
      class="btn-icon-glass reject-user-btn" 
      data-bs-toggle="tooltip" 
      data-user-id="${user.id}" 
      title="Reject"
    >
      <i class="bi bi-x-circle-fill"></i>
    </button>
  </div>
</td>

        </tr>`;
      $tbody.append(row);
    });

    renderPaginationControls($pagination, data.currentPage, data.totalPages);

    // Enable Bootstrap tooltips again
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].forEach(el => new bootstrap.Tooltip(el));

    // Re-bind modal and action handlers to newly rendered buttons
    setupApproveUserModal();
    setupUserActions();

  }).fail((jqXHR, textStatus, errorThrown) => {
    console.error('Failed to fetch users:', textStatus, errorThrown);
    $('#pendingUsersTableBody').html(`
      <tr><td colspan="5" class="text-danger text-center">
        Failed to load pending users. Try again later.
      </td></tr>`);
  });
}

function renderPaginationControls(container, current, total) {
  const createBtn = (page, iconClass, label, disabled = false) => `
    <li class="page-item ${disabled ? 'disabled' : ''}">
      <a class="page-link rounded-pill" href="#" data-page="${page}" title="${label}">
        <i class="${iconClass}"></i>
      </a>
    </li>`;

  container.append(createBtn(1, 'bi bi-chevron-double-left', 'First', current === 1));
  container.append(createBtn(current - 1, 'bi bi-chevron-left', 'Previous', current === 1));

  const range = 2;
  const start = Math.max(1, current - range);
  const end = Math.min(total, current + range);

  for (let i = start; i <= end; i++) {
    container.append(`
      <li class="page-item ${i === current ? 'active' : ''}">
        <a class="page-link rounded-pill" href="#" data-page="${i}">${i}</a>
      </li>`);
  }

  container.append(createBtn(current + 1, 'bi bi-chevron-right', 'Next', current === total));
  container.append(createBtn(total, 'bi bi-chevron-double-right', 'Last', current === total));
}

// Local utility to escape unsafe HTML content
function escapeHTML(str) {
  return $('<div>').text(str).html();
}
