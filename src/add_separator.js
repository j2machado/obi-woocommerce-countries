jQuery(function($) {
    var select = $('select.country_to_state, select.state_select');

    // Keep track of the previously selected value
    var previousValue = select.val();
    
    // Listen to the focus event
    select.on('focus', function() {
        // Remember the last-selected value
        previousValue = $(this).val();
    }).change(function() {
        // Prevent selection of the separator
        if ($(this).val() === '---') {
            $(this).val(previousValue);
        }
    });
});