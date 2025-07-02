// toast.js or inline in approveUserModal.js
export function showToast(type, message) {
  const toastEl = document.getElementById('approvalToast');
  const toastMsg = document.getElementById('approvalToastMsg');

  if (!toastEl || !toastMsg) return;

  toastMsg.textContent = message || 'Done';
  toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
  
  if (type === 'error') {
    toastEl.classList.add('bg-danger', 'text-white');
  } else {
    toastEl.classList.add('bg-success', 'text-white');
  }

  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}

