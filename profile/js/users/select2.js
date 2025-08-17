// select2.js — universal Select2 initializer
export function initSelect2(selector = '.select2', placeholderText = 'Select an option') {
  setTimeout(() => {
    $(selector).each(function () {
      const $el = $(this);

      $el.select2({
        width: '100%',
        allowClear: true,
        dropdownParent: $el.closest('.modal'),
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

      const currentValue = $el.find('option[selected]').val();
      if (currentValue) {
        $el.val(currentValue).trigger('change');
      }
    });
  }, 100); // Delay ensures DOM and modal are fully rendered
}

export function refreshSelect2(selector = '.select2') {
  const $el = $(selector);
  if ($el.hasClass('select2-hidden-accessible')) {
    $el.select2('destroy');
  }

  $el.select2({
    width: '100%',
    allowClear: true,
    dropdownParent: $el.closest('.modal'),
    placeholder: $el.data('placeholder') || 'Select an option',
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

  const currentValue = $el.find('option[selected]').val();
  if (currentValue) {
    $el.val(currentValue).trigger('change');
  }
}
