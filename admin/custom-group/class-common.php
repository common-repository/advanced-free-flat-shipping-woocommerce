<?php

class pisol_efrs_custom_group_common{

    static function getAllVirtualCategory(){
        $custom_groups = get_posts(array(
            'post_type'=>'pi_efrs_custom_group',
            'numberposts'      => -1
        ));
        $all_categories = [];
        foreach($custom_groups as $method){
            $all_categories[$method->ID] = $method->post_title." (#{$method->ID})";
        }
        return $all_categories;
    }

    static function productBelongToVirtualCat( $product_id, $variation_id, $vir_cat_id  ){  
        $match_type = self::getMatchType( $vir_cat_id);

        if($match_type == 'all'){
            $product_part_of_vc = true;
            if(self::excludedProductRules($product_id, $variation_id, $vir_cat_id)){
                $product_part_of_vc = false;
            }

            if(self::includedProductRules($product_id, $variation_id, $vir_cat_id)){
                $product_part_of_vc = true;
            }

        }elseif($match_type == 'selected'){
            $product_part_of_vc = false;

            if(self::includedProductRules($product_id, $variation_id, $vir_cat_id)){
                $product_part_of_vc = true;
            }

            if(self::excludedProductRules($product_id, $variation_id, $vir_cat_id)){
                $product_part_of_vc = false;
            }
        }

        return $product_part_of_vc;
    }

    static function getMatchType($vir_cat_id){
        $match_type = get_post_meta( $vir_cat_id, 'pi_match_type', true);
        return in_array( $match_type, array('all', 'selected')) ? $match_type : 'selected';
    }

    static function excludedProductRules( $product_id, $variation_id, $vir_cat_id ){
        if(self::excludedCategoryProduct($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        if(self::excludedProduct($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        if(self::excludedShippingClass($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        return false;
    }

    static function includedProductRules( $product_id, $variation_id, $vir_cat_id ){
        if(self::includedCategoryProduct($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        if(self::includedProduct($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        if(self::includedShippingClass($product_id, $variation_id, $vir_cat_id)){
            return true;
        }

        return false;
    }

    static function excludedProduct($product_id, $variation_id, $vir_cat_id){
        $excluded_products = (array)get_post_meta( $vir_cat_id, 'pi_excluded_products', true);
        $excluded_products = pisol_wpml_affsw_object($excluded_products, 'product');

        if(in_array($product_id, $excluded_products) || (!empty($variation_id) && in_array($variation_id, $excluded_products))) return true;

        return false;
    }

    static function includedProduct($product_id, $variation_id, $vir_cat_id){
        $products = (array)get_post_meta( $vir_cat_id, 'pi_products', true);
        $products = pisol_wpml_affsw_object($products, 'product');

        if(in_array($product_id, $products) || (!empty($variation_id) && in_array($variation_id, $products))) return true;

        return false;
    }

    static function excludedCategoryProduct($product_id, $variation_id, $vir_cat_id){
        $categories = (array)get_post_meta( $vir_cat_id, 'pi_excluded_categories', true);
        $categories = pisol_wpml_affsw_object($categories, 'product_cat');

        /** empty check is needed without that if blank array of category is passed it will return true for all product */
        if(!empty($categories) && has_term($categories, 'product_cat', $product_id)) return true;

        return false;
    }

    static function includedCategoryProduct($product_id, $variation_id, $vir_cat_id){
        $categories = (array)get_post_meta( $vir_cat_id, 'pi_categories', true);
        $categories = pisol_wpml_affsw_object($categories, 'product_cat');

        /** empty check is needed without that if blank array of category is passed it will return true for all product */
        if(!empty($categories) && has_term($categories, 'product_cat', $product_id)) return true;

        return false;
    }

    static function includedShippingClass($product_id, $variation_id, $vir_cat_id){
        $shipping_classes = (array)get_post_meta( $vir_cat_id, 'pi_shipping_classes', true);

        if(empty($shipping_classes)) return false;

        $shipping_classes = pisol_wpml_affsw_object($shipping_classes, 'product_shipping_class');

        if(empty($variation_id)){
            $product = wc_get_product($product_id);
        }else{
            $product = wc_get_product($variation_id);
        }

        if(!is_object($product)) return false;

        $shipping_method_id = $product->get_shipping_class_id();

        if ( in_array($shipping_method_id, $shipping_classes) ) {
            return true;
        }

        return false;
    }

    static function excludedShippingClass($product_id, $variation_id, $vir_cat_id){
        $shipping_classes = (array)get_post_meta( $vir_cat_id, 'pi_excluded_shipping_classes', true);

        if(empty($shipping_classes)) return false;

        $shipping_classes = pisol_wpml_affsw_object($shipping_classes, 'product_shipping_class');

        if(empty($variation_id)){
            $product = wc_get_product($product_id);
        }else{
            $product = wc_get_product($variation_id);
        }

        if(!is_object($product)) return false;

        $shipping_method_id = $product->get_shipping_class_id();

        if ( in_array($shipping_method_id, $shipping_classes) ) {
            return true;
        }

        return false;
    }
}