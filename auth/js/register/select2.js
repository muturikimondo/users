export function initSelect2(selector = '.select2', placeholderText = 'Select an option') {
  $(selector).select2({
    width: '100%',                      // Ensure it occupies full width for fluid responsiveness
    allowClear: true,                   // Enable the clearing of the selection
    placeholder: function () {          // Define placeholder text, either from the data-attribute or a default value
      return $(this).data('placeholder') || placeholderText;
    },
    //theme: 'classic',                   // Apply the classic theme for more control over custom styling
    dropdownAutoWidth: false,            // Disable dropdown width adjustment to control it manually
    minimumResultsForSearch: 3,         // Hide the search box if there are less than 10 options
    closeOnSelect: true,                // Close the dropdown when an item is selected for better UX
    containerCssClass: 'glass-select2', // Add a custom CSS class to the container for extra styling
    matcher: function(params, data) {   // Implement a custom matcher function for better search experience
      if ($.trim(params.term) === '') {
        return data; // Return all data if search is empty
      }
      if (data.text.toUpperCase().includes(params.term.toUpperCase())) {
        return data; // Only return options that match search query
      }
      return null; // Exclude non-matching data
    }
  });
}
