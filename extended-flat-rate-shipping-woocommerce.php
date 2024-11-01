<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             1.6.4.44
 * @package           Extended_Flat_Rate_Shipping_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Free - Flat shipping WooCommerce
 * Plugin URI:        piwebsolution.com/advanced-free-flat-shipping-woocommerce
 * Description:       WooCommerce conditional shipping & WooCommerce Advanced Flat rate shipping plugin to Create Advanced Flat rate shipping or Free shipping method, with different advanced criteria to apply this shipping method
 * Version:           1.6.4.44
 * Author:            PI Websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-free-flat-shipping-woocommerce
 * Domain Path:       /languages
 * WC tested up to: 9.3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pisol_efrs_free_deal() {
        ?>
        <div class="error notice">
            <p><?php esc_html_e( 'Advanced Free - Flat shipping WooCommerce:: Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'advanced-free-flat-shipping-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_efrs_free_deal' );
    return;
}

if(is_plugin_active( 'advanced-free-flat-shipping-woocommerce-pro/extended-flat-rate-shipping-woocommerce.php')){
    function pi_efrs_my_pro_notice() {
        ?>
        <div class="error notice">
            <p><?php esc_html_e( 'You have the PRO version of this plugin in your site', 'advanced-free-flat-shipping-woocommerce'); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_efrs_my_pro_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}else{

/**
 * Currently plugin version.
 * Start at version 1.6.4.44 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EXTENDED_FLAT_RATE_SHIPPING_WOOCOMMERCE_VERSION', '1.6.4.44' );
define('PI_EFRS_BUY_URL', 'https://www.piwebsolution.com/cart/?add-to-cart=2804&variation_id=2810&utm_campaign=advance-shipping&utm_source=website&utm_medium=direct-buy');
define('PI_EFRS_PRICE', '$39');
define('PI_EFRS_DELETE_SETTING', false);

/**
 * Declare compatible with HPOS new order table 
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );


/**
 * check if estimate plugin is present or not
 */
if(!function_exists('pisol_efrs_estimate_plugin_present')){
    function pisol_efrs_estimate_plugin_present(){
        if(is_plugin_active( 'estimate-delivery-date-for-woocommerce-pro/pi-edd.php') || is_plugin_active( 'estimate-delivery-date-for-woocommerce/pi-edd.php')){
            return true;
        }
        
        return false;
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-extended-flat-rate-shipping-woocommerce-activator.php
 */
function activate_extended_flat_rate_shipping_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extended-flat-rate-shipping-woocommerce-activator.php';
	Extended_Flat_Rate_Shipping_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-extended-flat-rate-shipping-woocommerce-deactivator.php
 */
function deactivate_extended_flat_rate_shipping_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extended-flat-rate-shipping-woocommerce-deactivator.php';
	Extended_Flat_Rate_Shipping_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_extended_flat_rate_shipping_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_extended_flat_rate_shipping_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-extended-flat-rate-shipping-woocommerce.php';

function efrs_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-efrs-notification' ) ) . '">' . __( 'Settings','advanced-free-flat-shipping-woocommerce' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="' . esc_url(PI_EFRS_BUY_URL) . '">' . __( 'Buy PRO Version','advanced-free-flat-shipping-woocommerce' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'efrs_plugin_link' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.6.4.44
 */
function run_extended_flat_rate_shipping_woocommerce() {

	$plugin = new Extended_Flat_Rate_Shipping_Woocommerce();
	$plugin->run();

}
run_extended_flat_rate_shipping_woocommerce();

}