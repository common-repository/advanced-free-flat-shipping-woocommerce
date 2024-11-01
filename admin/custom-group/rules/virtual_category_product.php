<?php

class Pi_efrs_selection_rule_virtual_category_product{
    public $slug;
    public $condition;
    
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'virtual_category_product';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field to store condition value either select or text box */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );

        /* This gives our field with saved value */
        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        /* This perform condition check */
        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,5);

        /* This gives out logic dropdown */
        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        /* This give saved logic dropdown */
        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Has product of Virtual category'),
            'group'=>'virtual_category',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
        
            $html .= '<option value=\'equal_to\'>Equal to (=)</option>';
            $html .= '<option value=\'not_equal_to\'>Not Equal to (!=)</option>';
           
        
        $html .= '</select>";';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';
        
            $html .= '<option value="equal_to" '.selected($saved_logic , "equal_to",false ).'>Equal to (=)</option>';
            $html .= '<option value="not_equal_to" '.selected($saved_logic , "not_equal_to",false ).'>Not Equal to (!=)</option>';
           
        
        $html .= '</select>';
        return $html;
    }

    function ajaxCall(){
        $cap = Pi_Efrs_Menu::getCapability();
        if(!current_user_can( $cap )) {
            return;
            die;
        }
        $count = sanitize_text_field(filter_input(INPUT_POST,'count'));
        $virtual_cats = $this->allCategories();
        if(!empty($virtual_cats)){
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo Pi_efrs_selection_rule_main::createSelect($virtual_cats, $count, $this->condition,  "multiple", null,'static');
        }else{
            echo '<p>You have not created any virtual category group</p><a href="'.esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' ) ).'">Click to create Virtual category</a>';
        }
        die;
    }

    function savedDropdown($html, $values, $count){
        $virtual_cats = $this->allCategories();
        if(!empty($virtual_cats)){
            $html = Pi_efrs_selection_rule_main::createSelect($virtual_cats, $count, $this->condition,  "multiple", $values,'static');
        }else{
            $html = '<p>You have not created any virtual category group</p><a href="'.esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' )).'">Click to create Virtual category</a>';
        }
        return $html;
    }

    function allCategories(){
        $virtual_cats = pisol_efrs_custom_group_common::getAllVirtualCategory();
        return $virtual_cats;
    }

    function conditionCheck($result, $package, $logic, $values, $package_support){
        
                    $or_result = false;
                    $cart_products = $this->getProductsFromOrder($package, $package_support);
                    $virtual_cats = $values;

                    if($logic == 'equal_to'){
                        foreach($cart_products as $product){
                            foreach($virtual_cats as $vir_cat_id){
                                if(pisol_efrs_custom_group_common::productBelongToVirtualCat( $product['product_id'], $product['variation_id'], $vir_cat_id )){
                                    $or_result = true;
                                }
                            }
                        }
                    }else{
                        $or_result = true;
                        foreach($cart_products as $product){
                            foreach($virtual_cats as $vir_cat_id){
                                if(pisol_efrs_custom_group_common::productBelongToVirtualCat( $product['product_id'], $product['variation_id'], $vir_cat_id )){
                                    $or_result = false;
                                }
                            }
                        }
                    }
               
        return  $or_result;
    }

    function getProductsFromOrder($package, $package_support){
        
        if(!function_exists('WC') || !is_object(WC()->cart)) return array();
        $package_support = 'package';
        if($package_support == 'cart'){
            $products = WC()->cart->get_cart();
        }else{
            $products = PISOL\EFRS\Package::get_products($package);
        }
        
        $user_products = array();
        foreach($products as $product){

            $user_products[] = array('product_id' => $product['product_id'], 'variation_id' => $product['variation_id']);
        }
        return $user_products;
    }
}

new Pi_efrs_selection_rule_virtual_category_product(PI_EFRS_SELECTION_RULE_SLUG);
