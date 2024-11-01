<?php
class Extended_Flat_Rate_Shipping_Woocommerce_Public {

	private $plugin_name;
	
	private $version;

	public $shipping_method;
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'woocommerce_shipping_init', array( $this, 'initShippingMethod' ) );

		add_action( 'woocommerce_shipping_methods', array( $this, 'registerShippingMethod' ) );

	}

	public function registerShippingMethod( $methods ) {

		if ( class_exists( 'Efrs_Shipping_Method' ) ) {
			$methods[] = 'Efrs_Shipping_Method';
		}

		return $methods;
	}

	public function initShippingMethod() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-efrs-shipping-methods.php';
		$this->shipping_method = new Efrs_Shipping_Method();
	}

	
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extended-flat-rate-shipping-woocommerce-public.css', array(), $this->version, 'all' );

	}

	
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/extended-flat-rate-shipping-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}

}

if(!function_exists('pisol_hideShippingMethodInBackendMenu')){
function pisol_hideShippingMethodInBackendMenu( $section){
	unset($section['pisol_extended_flat_shipping']);
	return $section ;
}
add_filter('woocommerce_get_sections_shipping','pisol_hideShippingMethodInBackendMenu',10);
}