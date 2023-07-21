<?php

/**
 * Plugin Name: Obi WooCommerce Countries
 * Description: Change the countries in WooCommerce checkout with the most popular countries.
 * Version: 1.0.0
 * Author: Obi Juan
 * Author URI: https://obijuan.dev
 * Plugin URI: https://obijuan.dev/obi-remove-post-types-from-search
 * License: GPL2 or later
 * Textdomain: obi-woocommerce-countries
 * @since 1.0.0 
 * 
 */

if (!defined('ABSPATH')) {

    exit('Trying what?');
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use ObiWooCommerceCountries\CountryOrder;

final class Obi_WooCommerce_Countries
{

    private static $instance;

    private function __construct()
    {

        self::define_constants();
    }

    public static function get_instance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private static function define_constants()
    {

        define('OBI_WOOCOMMERCE_COUNTRIES_VERSION', '1.0.0');
        define('OBI_WOOCOMMERCE_COUNTRIES__TEXTDOMAIN', 'obi-remove-post-types-from-search');
        define('OBI_WOOCOMMERCE_COUNTRIES_DIRNAME', plugin_basename(dirname(__FILE__)));
        define('OBI_WOOCOMMERCE_COUNTRIES_FILE', __FILE__);
        define('OBI_WOOCOMMERCE_COUNTRIES_PREFIX', 'obi_woocommerce_countries');
        define('OBI_WOOCOMMERCE_COUNTRIES_PATH', plugin_dir_path(OBI_WOOCOMMERCE_COUNTRIES_FILE));
        define('OBI_WOOCOMMERCE_COUNTRIES_URL', plugin_dir_url(OBI_WOOCOMMERCE_COUNTRIES_FILE));
    }

    public static function load_obi_plugin()
    {
        // On plugins loaded...
        CountryOrder::get_instance();
    }

    public static function activate()
    {

        // On plugin activation...



    }

    public static function deactivate()
    {

        // On plugin deactivation...

    }
}


$obi_plugin = Obi_WooCommerce_Countries::get_instance();

register_activation_hook(OBI_WOOCOMMERCE_COUNTRIES_FILE, array($obi_plugin, 'activate'));
add_action('plugins_loaded', array($obi_plugin, 'load_obi_plugin'));