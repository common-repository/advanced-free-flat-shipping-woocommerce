<?php
class pisol_efrs_disable_shipping_method_cache{
    function __construct(){
        add_action('wp_loaded', array($this, 'clear_wc_shipping_rates_cache'));
        add_filter( "option_woocommerce_shipping_cost_requires_address", array($this, 'disableHideUntilAddress') );
    }

    function clear_wc_shipping_rates_cache(){
        if(!function_exists('WC') || !isset(WC()->cart)) return;
        
        if ( !WC()->customer ) return; // this allows it to work with woocommerce point of sales plugin
        
        $packages = WC()->cart->get_shipping_packages();

        foreach ($packages as $key => $value) {
            $shipping_session = "shipping_for_package_$key";

            unset(WC()->session->$shipping_session);
        }
        
   }

   function disableHideUntilAddress($val){
       if(function_exists('is_admin') && is_admin()) return $val;

       return 'no';
   }
}

new pisol_efrs_disable_shipping_method_cache();