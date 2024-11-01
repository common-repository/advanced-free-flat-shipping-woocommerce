<?php

/**
 * Fired during plugin deactivation
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Extended_Flat_Rate_Shipping_Woocommerce
 * @subpackage Extended_Flat_Rate_Shipping_Woocommerce/includes
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Extended_Flat_Rate_Shipping_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_transient( 'wc_shipping_method_count_legacy' );
		delete_transient( 'wc_shipping_method_count' );
	}

}
