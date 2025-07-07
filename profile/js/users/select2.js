// coop/profile/js/users/select2.js
export function initSelect2(selector = '.select2', placeholderText = 'Select an option') {
  // Wait for DOM to stabilize, especially when inside modals
  setTimeout(() => {
    $(selector).each(function () {
      const $el = $(this);
      $el.select2({
        width: '100%',
        allowClear: true,
        dropdownParent: $el.closest('.modal'), // Important for modals
        placeholder: $el.data('placeholder') || placeholderText,
        dropdownAutoWidth: false,
        minimumResultsForSearch: 3,
        closeOnSelect: true,
        containerCssClass: 'glass-select2',
        matcher: function (params, data) {
          if ($.trim(params.term) === '') return data;
          if (data.text.toUpperCase().includes(params.term.toUpperCase())) return data;
          return null;
        }
      });
    });
  }, 100); // Small delay to ensure modals are open and DOM is ready
}
