import { showToast } from '../utils/toasts.js';

export function setupUserActions() {
  // Delegate reject button click ONCE at document level
  $(document).off('click.rejectUser').on('click.rejectUser', '.reject-user-btn', function () {
    const $row = $(this).closest('tr');
    const userId = $row.data('user-id');

    if (!userId) {
      console.warn('Missing user ID');
      return;
    }

    bootbox.confirm({
      title: 'Reject User',
      message: 'Are you sure you want to reject this user?',
      buttons: {
        confirm: {
          label: '<i class="bi bi-x-circle"></i> Reject',
          className: 'btn btn-danger'
        },
        cancel: {
          label: 'Cancel',
          className: 'btn btn-secondary'
        }
      },
      callback: function (confirmed) {
        if (!confirmed) return;

        $.post(`${BASE_URL}admin/ajax/users/reject.php`, { user_id: userId }, function (res) {
          if (res.status === 'success') {
            showToast('success', 'User successfully rejected.');
            $row.fadeOut(400, () => {
              $row.remove();
              checkEmptyTable();
            });
          } else {
            showToast('error', res.message || 'Rejection failed.');
          }
        }, 'json').fail(() => {
          showToast('error', 'Server error. Please try again.');
        });
      }
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
