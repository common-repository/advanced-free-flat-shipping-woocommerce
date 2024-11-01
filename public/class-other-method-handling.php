<?php

class pisol_efrs_handle_other_methods{
   function __construct(){
        add_filter( 'woocommerce_package_rates', array($this, 'shippingMethodFilter'), PHP_INT_MAX );

        $show_zero_cost = get_option('pisol_affsw_show_zero_cost', 0);
        if(!empty($show_zero_cost)){
            add_filter( 'woocommerce_cart_shipping_method_full_label', [$this, 'showZeroCost'], 10, 2);
        }

   }

   function shippingMethodFilter( $rates ) {
       
        $rates = $this->showOnlySingleShippingMethod($rates);

        return $rates;
   }

   function showOnlySingleShippingMethod($rates){
        $show_only_one_method = get_option('pi_efrs_show_only_one_method', '');

        if(empty($show_only_one_method)) return $rates;

        if($show_only_one_method === 'lowest'){
            $rates = $this->get_lowest_shipping_method($rates);
        }elseif($show_only_one_method === 'highest'){
            $rates = $this->get_highest_shipping_method($rates);
        }

        return $rates;
    }

    function get_lowest_shipping_method($rates){
        $lowest = 0;
        $lowest_rate = '';
        foreach($rates as $name => $rate){
            $cost = $rate->get_cost();
            if($lowest === 0){
                $lowest = $cost;
                $lowest_rate = $name;
            }else{
                if($cost < $lowest){
                    $lowest = $cost;
                    $lowest_rate = $name;
                }
            }
        }

        if(!empty($lowest_rate)){
            $rates = array($lowest_rate => $rates[$lowest_rate]);
        }

        return $rates;
    }

    function get_highest_shipping_method($rates){
        $highest = 0;
        $highest_rate = '';
        foreach($rates as $name => $rate){
            $cost = $rate->get_cost();
            if($highest === 0){
                $highest = $cost;
                $highest_rate = $name;
            }else{
                if($cost > $highest){
                    $highest = $cost;
                    $highest_rate = $name;
                }
            }
        }

        if(!empty($highest_rate)){
            $rates = array($highest_rate => $rates[$highest_rate]);
        }

        return $rates;
    }

    function showZeroCost( $label, $method ) {

        if ( $method->cost == 0 ) {
            $label = $method->get_label().': '.wc_price($method->cost);
        }
    
        return $label;
    
    }
}

new pisol_efrs_handle_other_methods();