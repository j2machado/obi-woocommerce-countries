<?php

namespace ObiWooCommerceCountries;

class CountryOrder {
    private static $instance = null;
    private $countries;

    private function __construct() {
        $this->countries = $this->get_order_counts_by_country();
        add_action('init', array($this, 'get_countries'));
        add_filter('woocommerce_countries', array($this, 'custom_woocommerce_countries'), 10, 1);
        //add_filter('woocommerce_form_field_country', array($this, 'modify_country_dropdown'), 10, 4);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new CountryOrder();
        }

        return self::$instance;
    }

    public function enqueue_scripts() {
        wp_enqueue_script('country_order', plugins_url('country_order.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('add_separator', plugins_url('add_separator.js', __FILE__), array('jquery'), '1.0', true);
    
        // Localize the script with new data
        $country_data = array(
            'countries' => array_keys($this->get_countries()),
                    );
        wp_localize_script('country_order', 'countryOrder', $country_data);
    }
    
    

    private function get_order_counts_by_country() {
        global $wpdb;

        // Query to get count of orders by country code
        $query = "
            SELECT meta_value AS country, COUNT(*) AS count
            FROM {$wpdb->prefix}postmeta
            WHERE meta_key = '_billing_country'
            AND post_id IN (
                SELECT ID 
                FROM {$wpdb->prefix}posts
                WHERE post_type = 'shop_order'
                AND post_status = 'wc-completed'
            )
            GROUP BY meta_value
            ORDER BY count DESC
        ";

        // Execute the query
        $results = $wpdb->get_results($query, OBJECT_K);

        // Check for possible error
        if (is_null($results)) {
            // Handle error as needed
            error_log('Database query failed.');
            return array();
        }

        // Initialize the array
        $countries = array();

        // Convert the $results object into the desired array format
        foreach ($results as $country_code => $result) {
            $countries[$country_code] = $result->count;
        }

        return $countries;
    }

    public function get_countries() {
        /*
        echo '<pre>';
        var_dump($this->countries);
        echo '</pre>';

        
        exit();
        */

        error_log(print_r($this->countries, true));

        return $this->countries;
    }

    public function custom_woocommerce_countries($countries) {

        error_log("Before processing: " . print_r($countries, true));


        // Get the top country codes from our data
        $top_country_codes = array_keys($this->get_countries());
    
        // Get the names of the top countries from $countries array
        $top_countries = array_intersect_key($countries, array_flip($top_country_codes));
    
        // Remove top countries from $countries array
        $countries = array_diff_key($countries, $top_countries);
    
        // Add a separator to the list of top countries
        $top_countries['---'] = '---';
    
        error_log("After processing: " . print_r($countries, true));

        // Now combine them, with top countries first
        return $top_countries + $countries;
    }
    
    
}

