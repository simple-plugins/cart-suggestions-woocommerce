<?php


/**
 * Plugin Name: Cart Suggestion WooCommerce
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


	add_action( 'woocommerce_before_cart', 'cart_suggestion_message', 1 );

	function cart_suggestion_message() {
		/** @var WC_Product $product */
		$product = wc_get_product( 11 );
		$display = true;
		foreach ( WC()->cart->get_cart_contents() as $item ) {
			if ( $item['data']->get_id() == $product->get_id() ) {
				$display = false;
			}
		}

		if ( $display ) {

			echo '<div class="woocommerce-info">Add our <a href="' . site_url() . '/cart?add-to-cart=' . $product->get_id() . '">' .
			     $product->get_name()
			     . '</a> for an extra ' .
			     $product->get_price_html() . '!</div>';
		}

	}
}
