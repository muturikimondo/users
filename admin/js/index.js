// --- Imports ---
import { setupApproveUserModal } from './users/approveUserModal.js';
import { setupUserActions } from './users/userActions.js';
import { setupPagination } from './users/userPagination.js';
import { initSelect2 } from './utils/select2.js'; // ✅ Reuse utility

// --- Initialize all components after DOM is fully loaded ---
document.addEventListener('DOMContentLoaded', () => {
  setupApproveUserModal();    // Handles approving users via modal
  setupUserActions();         // Handles rejecting users with bootbox
  setupPagination();          // Handles AJAX pagination and rendering

  // Globally initialize Select2 elements
  initSelect2('.select2');
});
