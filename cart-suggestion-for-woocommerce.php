<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
/**
 * Plugin Name: Cart Suggestion for WooCommerce
 * Plugin URI: http://woocommerce.com/products/woocommerce_extension/
 * Description: Displays a notification before the cart to encourage a customer to purchase the item.
 * Version: 0.8.0
 * Developer: Simple Plugins
 * Developer URI: https://simpleplugins.io/
 * Text Domain: cart_suggestion_woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 5.1
 * WC tested up to: 5.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl_3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$prefix = 'csw';
	$panel  = new \TDP\OptionsKit( $prefix );
	$panel->set_page_title(__('Cart Suggestion Settings'));

	add_filter( 'csw_menu', 'csw_setup_menu' );
	add_filter( 'csw_settings_tabs', 'csw_register_settings_tabs' );
	add_filter( 'csw_registered_settings_sections', 'csw_register_settings_subsections' );
	add_filter( 'csw_registered_settings', 'csw_register_settings' );
	add_filter( 'csw_settings_sanitize_cart_message', 'filter_cart_message', 10, 1 );
	$settings = get_option('csw_settings');
	if (isset($settings) && $settings['enable'] && !empty($settings['product_id'])) {
		add_action( 'woocommerce_before_cart', 'cart_suggestion_message', 1 );
	}

}
