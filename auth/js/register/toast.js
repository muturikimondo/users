// coop/auth/js/register/toast.js

export function showToast(type = 'info', message = 'Message') {
  const toastEl = document.getElementById("formToast");
  const toastMsg = document.getElementById("toastMsg");
  const toastIcon = document.getElementById("toastIcon");

  console.debug(`[TOAST INIT] Requested: ${type} - ${message}`);

  let iconClass = 'bi-info-circle';
  let bgClass = 'bg-warning text-dark';

  if (type === 'error') {
    iconClass = 'bi-exclamation-octagon';
    bgClass = 'bg-danger text-white';
  } else if (type === 'success') {
    iconClass = 'bi-check-circle';
    bgClass = 'bg-primary text-white';
  }

  if (!toastEl || !toastMsg || !toastIcon) {
    console.warn('[TOAST] Missing one or more toast elements.');
    return;
  }

  toastEl.className = `toast align-items-center ${bgClass} border-0`;
  toastMsg.textContent = message;
  toastIcon.innerHTML = `<i class="bi ${iconClass} fs-5"></i>`;

  const toast = new bootstrap.Toast(toastEl);
  toast.show();

  console.debug('[TOAST] Displayed with class:', toastEl.className);

  toastEl.addEventListener('shown.bs.toast', () => {
    console.debug('[TOAST] Toast shown in UI');
  });

  toastEl.addEventListener('hidden.bs.toast', () => {
    console.debug('[TOAST] Toast hidden/closed by user or timeout');
  });
}
