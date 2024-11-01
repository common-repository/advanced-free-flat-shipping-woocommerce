<?php

/**
 * Fired during plugin activation
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Extended_Flat_Rate_Shipping_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		delete_transient( 'wc_shipping_method_count_legacy' );
		delete_transient( 'wc_shipping_method_count' );
	}

}
