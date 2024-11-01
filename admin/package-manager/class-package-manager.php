<?php 

namespace PISOL\EFRS\Package;

class PackageManager{

    public $package_product_in_cart = [];

    public $remaining_products = [];

    public $package = [];

    public $custom_package = [];

    function __construct($package, $custom_package){
        $this->package = $package;
        $this->custom_package = $custom_package;

        $this->init();
    }

    function init(){
        $package_product_in_cart = [];
        foreach($this->package['contents'] as $cart_item_key => $cart_item){
            $product_id = $cart_item['product_id'];
            $variation_id = $cart_item['variation_id'];
            $vir_cat_id = get_post_meta($this->custom_package, 'pi_group', true);

            if(empty($vir_cat_id)){
                $this->remaining_products[$cart_item_key] = $cart_item;
            }

            if(\pisol_efrs_custom_group_common::productBelongToVirtualCat( $product_id, $variation_id, $vir_cat_id, $cart_item  )){
                $this->package_product_in_cart[$cart_item_key] = $cart_item;
            }else{
                $this->remaining_products[$cart_item_key] = $cart_item;
            }
        }
    }

    function is_valid_package(){

        if(!$this->virtual_product_present()) return false;

        //if(!$this->subtotal_logic_satisfied()) return false;

        //if(!$this->quantity_logic_satisfied()) return false;

        return true;
    }

    function virtual_product_present(){
        if(count($this->package_product_in_cart) > 0) return true;

        return false;
    }

    function subtotal_logic_satisfied(){
        $subtotal_logic = get_post_meta($this->custom_package, 'pi_subtotal_logic', true);

        if(empty($subtotal_logic)) return true;

        $subtotal = get_post_meta($this->custom_package, 'pi_subtotal', true);
        $subtotal = floatval($subtotal);

        if(empty($subtotal)) $subtotal = 0;

        $package_subtotal = $this->get_package_subtotal();

        switch($subtotal_logic){
            case 'greater':
                if($package_subtotal > $subtotal) return true;
                break;
            case 'greater_equal':
                if($package_subtotal >= $subtotal) return true;
                break;
            case 'less':
                if($package_subtotal < $subtotal) return true;
                break;
            case 'less_equal':
                if($package_subtotal <= $subtotal) return true;
                break;
            case 'equal':
                if($package_subtotal == $subtotal) return true;
                break;
        }

        return false;
    }

    function quantity_logic_satisfied(){
        $quantity_logic = get_post_meta($this->custom_package, 'pi_quantity_logic', true);

        if(empty($quantity_logic)) return true;

        $quantity = get_post_meta($this->custom_package, 'pi_quantity', true);
        $quantity = intval($quantity);

        if(empty($quantity)) $quantity = 0;

        $package_quantity = $this->get_package_quantity();

        switch($quantity_logic){
            case 'greater':
                if($package_quantity > $quantity) return true;
                break;
            case 'greater_equal':
                if($package_quantity >= $quantity) return true;
                break;
            case 'less':
                if($package_quantity < $quantity) return true;
                break;
            case 'less_equal':
                if($package_quantity <= $quantity) return true;
                break;
            case 'equal':
                if($package_quantity == $quantity) return true;
                break;
            case 'multiple':
                if(!empty($quantity) && $package_quantity % $quantity == 0) return true;
                break;
            case 'not_multiple':
                if(!empty($quantity) && $package_quantity % $quantity != 0) return true;
                break;
        }

        return false;
    }

    function get_package(){
        $package = $this->package;
        $package['contents'] = $this->package_product_in_cart;
        $package['package_name'] = get_the_title($this->custom_package);
        $package = $this->adjust_package_total($package);
        return $package;
    }

    function get_remaining_package(){
        if(count($this->remaining_products) == 0) return false;
        $package = $this->package;
        $package['contents'] = $this->remaining_products;
        $package = $this->adjust_package_total($package);
        return $package;
    }

    function adjust_package_total($package){
        $package['contents_cost']   = array_sum(wp_list_pluck($package['contents'], 'line_total'));
        return $package;
    }

    function get_package_subtotal(){
        $package = $this->get_package();
        return $package['contents_cost'];
    }

    function get_package_quantity(){
        $package = $this->get_package();
        $qty = 0;
        foreach($package['contents'] as $cart_item_key => $cart_item){
            $qty += $cart_item['quantity'];
        }
        return $qty;
    }
}