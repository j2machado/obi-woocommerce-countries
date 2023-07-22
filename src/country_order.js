jQuery(document).ready(function($){
    // Get the dropdown element
    var $dropdown = $('.woocommerce-input-wrapper select');

    // Get the top countries and move them to the top of the dropdown
    var $topCountries = $dropdown.find('option[value="' + countryOrder.countries.join('"], option[value="') + '"]');
    $dropdown.prepend($topCountries);

    console.log(countryOrder.countries);

});