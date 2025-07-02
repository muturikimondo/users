// coop/admin/js/users/approveUserModal.js
import { showToast } from '../utils/toasts.js';

export function setupApproveUserModal() {
  const $modal = $('#approveUserModal');

  if (!$modal.hasClass('select2-initialized')) {
    $modal.find('.select2').select2({
      dropdownParent: $modal,
      theme: 'bootstrap-5',
      width: '100%',
      allowClear: true,
      placeholder: function () {
        return $(this).data('placeholder') || 'Select a role';
      }
    });
    $modal.addClass('select2-initialized');
  }

  $(document).on('click', '.approve-user-btn', function () {
    const userId = $(this).closest('tr').data('user-id');
    $('#approveUserId').val(userId);
    $('#approveUserModal').modal('show');
  });

  $('#approveUserForm').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize();
    const userId = $('#approveUserId').val();

    $.post(`${BASE_URL}admin/ajax/users/approve.php`, formData, function (response) {
      if (response.status === 'success') {
        $('#approveUserModal').modal('hide');
        showToast('success', 'User approved and role assigned!');

        const $row = $(`tr[data-user-id="${userId}"]`);
        $row.fadeOut(400, () => {
          $row.remove();
          checkEmptyTable();
        });
      } else {
        showToast('error', response.message || 'Approval failed.');
      }
    }, 'json').fail(() => {
      showToast('error', 'Server error. Please try again.');
    });
  });
}

function checkEmptyTable() {
  const $tableBody = $('table tbody');
  if ($tableBody.children('tr:visible').length === 0) {
    $tableBody.html(`
      <tr>
        <td colspan="5" class="text-center text-muted">No pending approvals at the moment.</td>
      </tr>
    `);
  }
}
