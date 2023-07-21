<?php

namespace ObiWooCommerceCountries;

class CountryOrder {
    private static $instance = null;
    private $countries;

    private function __construct() {
        $this->countries = $this->get_order_counts_by_country();
        add_action('init', array($this, 'get_countries'));
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new CountryOrder();
        }

        return self::$instance;
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
        
        return $this->countries;
    }
}
