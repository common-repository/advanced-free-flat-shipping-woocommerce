<?php

class pisol_efrs_pro_rules{
    public $slug;

    function __construct($slug){
        $this->slug = $slug;
         /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
    }

    function addRule($rules){
        $rules['state'] = array(
            'name'=>__('State (Available in PRO Version)'),
            'group'=>'location_related',
            'condition'=>'state',
            'pro'=>true
        );
        $rules['postcode'] = array(
            'name'=>__('Postcode (Available in PRO Version)'),
            'group'=>'location_related',
            'condition'=>'postcode',
            'pro'=>true
        );
        $rules['all_zones'] = array(
            'name'=>__('User address matches with Zones (Available in PRO Version)'),
            'group'=>'location_related',
            'condition'=>'all_zones',
            'pro'=>true
        );
        $rules['city'] = array(
            'name'=>__('City/Town (Available in PRO Version)'),
            'group'=>'location_related',
            'condition'=>'zones',
            'pro'=>true
        );
        $rules['variable_product'] = array(
            'name'=>__('Cart has variable product (Available in PRO Version)'),
            'group'=>'product_related',
            'condition'=>'variable_product',
            'pro'=>true
        );
        $rules['product_quantity'] = array(
            'name'=>__('Product Quantity (Available in PRO Version)'),
            'group'=>'product_related',
            'condition'=>'product_quantity',
            'pro'=>true
        );
        $rules['product_tag'] = array(
            'name'=>__('Product Tag (Available in PRO Version)'),
            'group'=>'product_related',
            'condition'=>'product_tag',
            'pro'=>true
        );
        $rules['selected_nth_delivery_date'] = array(
            'name'=>__('Selected nth delivery date from today (Available in PRO Version)'),
            'group'=>'order_date_time_plugin',
            'condition'=>'selected_nth_delivery_date',
            'pro'=>true
        );
        $rules['selected_delivery_date'] = array(
            'name'=>__('Selected delivery date (Available in PRO Version)'),
            'group'=>'order_date_time_plugin',
            'condition'=>'selected_delivery_date',
            'pro'=>true
        );
        $rules['between_time'] = array(
            'name'=>__('Start & End time (Available in PRO Version)'),
            'group'=>'other',
            'condition'=>'between_time',
            'pro'=>true
        );
        $rules['user_role'] = array(
            'name'=>__('User role (Available in PRO Version)'),
            'group'=>'user_related',
            'condition'=>'user_role',
            'pro'=>true
        );
        
        $rules['subtotal_after_discount'] = array(
            'name'=>__('Cart Subtotal (After Discount) (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'subtotal_after_discount',
            'pro'=>true
        );

        $rules['cart_subtotal_after_discount_exclude_virtual'] = array(
            'name'=>__('Cart Subtotal (After Discount Excluding virtual product) (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'subtotal_after_discount',
            'pro'=>true
        );

        $rules['weight'] = array(
            'name'=>__('Total Product Weight in Cart (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'weight',
            'pro'=>true
        );

        $rules['width'] = array(
            'name'=>__('Max Width in cart (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'width',
            'pro'=>true
        );

        $rules['height'] = array(
            'name'=>__('Max Height in cart (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'height',
            'pro'=>true
        );

        $rules['length'] = array(
            'name'=>__('Max Length in cart (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'length',
            'pro'=>true
        );

        $rules['coupon'] = array(
            'name'=>__('Coupons used (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'coupon',
            'pro'=>true
        );

        $rules['shipping_class'] = array(
            'name'=>__('Shipping class (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'shipping_class',
            'pro'=>true
        );

        $rules['shipping_class_total'] = array(
            'name'=>__('Shipping class total (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'shipping_class_total',
            'pro'=>true
        );

        $rules['shipping_class_weight'] = array(
            'name'=>__('Shipping class weight (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'shipping_class_weight',
            'pro'=>true
        );

        $rules['shipping_class_quantity'] = array(
            'name'=>__('Shipping class total quantity in cart (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'shipping_class_quantity',
            'pro'=>true
        );

        $rules['payment_method'] = array(
            'name'=>__('Payment Method (Available in PRO Version)'),
            'group'=>'cart_related',
            'condition'=>'payment_method',
            'pro'=>true
        );

        $rules['day'] = array(
            'name'=>__('Day of the week (Available in PRO Version)'),
            'group'=>'other',
            'condition'=>'day',
            'pro'=>true
        );

        $rules['first_order'] = array(
            'name'=>__('First order (Available in PRO Version)','advanced-free-flat-shipping-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'first_order',
            'pro'=>true
        );

        $rules['last_order'] = array(
            'name'=>__('Last order total (Available in PRO Version)','advanced-free-flat-shipping-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'last_order',
            'pro'=>true
        );

        $rules['number_of_order'] = array(
            'name'=>__('Number of orders during a period (Available in PRO Version)','advanced-free-flat-shipping-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'first_order',
            'pro'=>true
        );

        $rules['total_of_orders'] = array(
            'name'=>__('Total amount spend during a period (Available in PRO Version)','advanced-free-flat-shipping-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'last_order',
            'pro'=>true
        );

        if(function_exists('wc_get_attribute_taxonomies')){
            $attributes = wc_get_attribute_taxonomies();
            if(is_array($attributes)){
                foreach($attributes as $att){
                    if(is_object($att)){
                        $rules['product_attribute_'.$att->attribute_id] = array(
                            'name'=>$att->attribute_label.' (Available in PRO Version)',
                            'group'=>'product_attributes',
                            'condition'=>'product_attribute_'.$att->attribute_id,
                            'pro'=>true
                        );
                    }
                }
            }
        }
        
        return $rules;
    }
}

new pisol_efrs_pro_rules(PI_EFRS_SELECTION_RULE_SLUG);