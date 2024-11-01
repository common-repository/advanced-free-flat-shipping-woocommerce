<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Extended_Flat_Rate_Shipping_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'extended-flat-rate-shipping-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
