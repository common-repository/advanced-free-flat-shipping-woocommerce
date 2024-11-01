<?php 

namespace PISOL\EFRS;

class Package{
    
    static function get_country( $package ){
        return $package['destination']['country'] ?? WC()->customer->get_shipping_country();
    }

    static function get_state( $package ){
        return $package['destination']['state'] ?? WC()->customer->get_shipping_state();
    }

    static function get_postcode( $package ){
        return $package['destination']['postcode'] ?? WC()->customer->get_shipping_postcode();
    }

    static function get_city( $package ){
        return $package['destination']['city'] ??  WC()->customer->get_shipping_city();
    }

    static function get_products( $package ){
        $products = $package['contents'] ?? WC()->cart->get_cart();
        return $products;
    }

    static function get_quantity($package){
        $products = self::get_products($package);
        $quantity = 0;
        foreach($products as $product){
            $quantity += $product['quantity'];
        }
        return $quantity;
    }

    static function get_weight($package){
        $products = self::get_products($package);
        $total_weight = 0;
        foreach($products as $product){
            $quantity = $product['quantity'];
            $weight = $product['data']->get_weight();
            if(empty($weight)){
                $weight = 0;
            }
            
            $total_weight += $weight * $quantity;
        }
        return $total_weight;
    }

    static function get_subtotal($package){
        return $package['contents_cost'] ?? WC()->cart->get_displayed_subtotal();
    }
}