<?php

/**
 * Setup the menu for the options panel.
 *
 * @param array $menu
 *
 * @return array
 */
function csw_setup_menu($menu) {
	$menu['page_title'] = __('Cart Suggestion Settings');
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
	$settings = get_option('csw_settings');
	$product = wc_get_product( (int) $settings['product_id'] );
	$display = true;

	if (!isset($settings['enable_always'])) {
		foreach ( WC()->cart->get_cart_contents() as $item ) {
			if ( $item['data']->get_id() == $product->get_id() ) {
				$display = false;
			}
		}
	}

	if ( $display ) {

		echo '<div class="woocommerce-info">Add our <a href="' . site_url() . '/cart?add-to-cart=' . $product->get_id() . '">' .
		     $product->get_name()
		     . '</a> for an extra ' .
		     $product->get_price_html() . '!</div>';
	}

}
