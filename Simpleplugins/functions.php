<?php

/**
 * Setup the menu for the options panel.
 *
 * @param array $menu
 *
 * @return array
 */
function csw_setup_menu( $menu ) {
	$menu['page_title'] = __( 'Cart Suggestion Settings' );
	$menu['menu_title'] = $menu['page_title'];

	return $menu;
}


/**
 * Register settings tabs.
 *
 * @param array $tabs
 *
 * @return array
 */
function csw_register_settings_tabs( $tabs ) {
	return array(
		'general' => __( 'General' ),
	);
}

/**
 * Register settings fields for the options panel.
 *
 * @param array $settings
 *
 * @return array
 */
function csw_register_settings( $settings ) {
	$settings = array(
		'general' => array(
			array(
				'id'   => 'product_id',
				'name' => __( 'Product ID' ),
				'desc' => __( 'What Product ID do you want to suggest?' ),
				'type' => 'text',
			),
			array(
				'id'   => 'cart_message',
				'name' => __( 'Message to display' ),
				'desc' => __( 'Variables are [name], [price], [id], [site_url]' ),
				'type' => 'textarea',
				'std'  => 'Add our <a href="[site_url]?add-to-cart=[id]">[name]</a> for an extra [price]!'
			),
			array(
				'id'   => 'enable_always',
				'name' => __( 'Disable same product' ),
				'desc' => __( 'If you check this option, the message will be displayed even if the user has the product in the cart.' ),
				'type' => 'checkbox',
			),
			array(
				'id'   => 'enable',
				'name' => __( 'Enable message on checkout' ),
				'type' => 'checkbox',
			),
		),
	);

	return $settings;
}

/**
 * Register settings subsections (optional)
 *
 * @param array $subsections
 *
 * @return array
 */
function csw_register_settings_subsections( $subsections ) {
	return $subsections;
}


function cart_suggestion_message() {
	/** @var WC_Product $product */
	$settings = get_option( 'csw_settings' );
	$product  = wc_get_product( (int) $settings['product_id'] );
	$display  = true;

	if ( ! isset( $settings['enable_always'] ) ) {
		foreach ( WC()->cart->get_cart_contents() as $item ) {
			if ( $item['data']->get_id() == $product->get_id() ) {
				$display = false;
			}
		}
	}

	if ( $display ) {

		$message = csw_get_filtered_message( $product, site_url() );

		echo '<div class="woocommerce-info">' . $message . '</div>';
	}

}

function filter_cart_message( $output ) {
	$allowed  = array('a' => array('href' => array ()), 'br' => array(), 'p' => array(), 'strong' => array());
	return wp_kses( $output, $allowed);
}


function csw_get_filtered_message( WC_Product $product, $url ) {

	$settings = get_option( 'csw_settings' );
	$message  = $settings['cart_message'];

	// [name], [price], [id], [site_url]
	$message = str_replace( '[name]', $product->get_name(), $message );
	$message = str_replace( '[price]', $product->get_price_html(), $message );
	$message = str_replace( '[id]', $product->get_id(), $message );
	$message = str_replace( '[site_url]', site_url(), $message );

	return $message;
}
