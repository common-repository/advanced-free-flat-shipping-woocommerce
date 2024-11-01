<?php

class Pi_efrs_selection_rule_virtual_category_quantity{
    public $slug;
    public $condition;
    
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'virtual_category_qty';
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
            'name'=>__('Virtual category quantity'),
            'group'=>'virtual_category',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
    
            $html .= '<option value=\'equal_to\'>Equal to ( = )</option>';
			$html .= '<option value=\'less_equal_to\'>Less or Equal to ( &lt;= )</option>';
			$html .= '<option value=\'less_then\'>Less than ( &lt; )</option>';
			$html .= '<option value=\'greater_equal_to\'>Greater or Equal to ( &gt;= )</option>';
			$html .= '<option value=\'greater_then\'>Greater than ( &gt; )</option>';
			$html .= '<option value=\'not_equal_to\'>Not Equal to ( != )</option>';
        
        $html .= '</select>";';
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';

            $html .= '<option value=\'equal_to\' '.selected($saved_logic , "equal_to",false ).'>Equal to ( = )</option>';
			$html .= '<option value=\'less_equal_to\' '.selected($saved_logic , "less_equal_to",false ).'>Less or Equal to ( &lt;= )</option>';
			$html .= '<option value=\'less_then\' '.selected($saved_logic , "less_then",false ).'>Less than ( &lt; )</option>';
			$html .= '<option value=\'greater_equal_to\' '.selected($saved_logic , "greater_equal_to",false ).'>Greater or Equal to ( &gt;= )</option>';
			$html .= '<option value=\'greater_then\' '.selected($saved_logic , "greater_then",false ).'>Greater than ( &gt; )</option>';
			$html .= '<option value=\'not_equal_to\' '.selected($saved_logic , "not_equal_to",false ).'>Not Equal to ( != )</option>';
        
        
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
        $virtual_cats = $this->allVirtualCategories();
        if(!empty($virtual_cats)){
            $html_class = self::createSelect($virtual_cats, $count,$this->condition,  "",null,'static');
            $html_total =  self::createNumberField($count, $this->condition, null);
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo self::bootstrapRow($html_class, $html_total);
        }else{
            echo '<p>You have not created any virtual category group</p><a href="'.esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' )).'">Click to create Virtual category</a>';
        }
        die;

    }

    static function bootstrapRow($left, $right){
        return sprintf('<div class="row"><div class="col-6">%s</div><div class="col-6">%s</div></div>', $left, $right);
    }

    function savedDropdown($html, $values, $count){
        $virtual_cats = $this->allVirtualCategories();
        if(!empty($virtual_cats)){
            $vir_cat = isset($values['virtual_cat']) ? $values['virtual_cat'] : '';
            $html_class = self::createSelect($virtual_cats, $count, $this->condition,  "", $vir_cat,'static');
            $total = isset($values['qty']) ? $values['qty'] : '';
            $html_total = self::createNumberField($count, $this->condition,  $total);
        }else{
            $html = '<p>You have not created any virtual category group</p><a href="'.esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' )).'">Click to create Virtual category</a>';
            return $html;
        }
        return self::bootstrapRow($html_class, $html_total);
    }

    static function createSelect($array, $count, $condition ="",  $multiple = "",  $values = "", $dynamic = ""){

        if($multiple === 'multiple'){
            $multiple = ' multiple="multiple" ';
        }else{
            $multiple = '';
        }

        $html = '<select class="form-control pi_condition_value pi_values_'.$dynamic.'" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_EFRS_SELECTION_RULE_SLUG.'_condition_value][virtual_cat]" '.$multiple.' placeholder="Select">';
        foreach ($array as $key => $value){
                $selected = "";
                if($values == $key){
                    $selected = ' selected="selected" ';
                }
                $html .= '<option value="'.$key.'" '.$selected.'>';
            $html .= $value;
            $html .= '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function createNumberField($count, $condition ="", $value = "", $step = 'any'){

        
        $html = '<input type="number" step="'.$step.'" class="form-control" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_EFRS_SELECTION_RULE_SLUG.'_condition_value][qty]" value="'.$value.'" placeholder="'.__('Total Qty').'" >';
        return $html;
    }

    function allVirtualCategories(){
        $virtual_cats = pisol_efrs_custom_group_common::getAllVirtualCategory();
        return $virtual_cats;
    }

    function conditionCheck($result, $package, $logic, $values, $package_support){
        
                    $or_result = false;
                    $vir_cat_qty = $this->getVirtualCategoryQty((isset($values['virtual_cat']) ? $values['virtual_cat'] : 0), $package, $package_support);
                   
                    $rule_cart_qty = apply_filters('pisol_efrs_virtual_category_qty_value', (isset($values['qty']) ? $values['qty'] : ''));
                    if($rule_cart_qty === '') return false;
                    
                        switch ($logic){
                            case 'equal_to':
                                if($vir_cat_qty == $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
    
                            case 'less_equal_to':
                                if($vir_cat_qty <= $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
    
                            case 'less_then':
                                if($vir_cat_qty < $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
    
                            case 'greater_equal_to':
                                if($vir_cat_qty >= $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
    
                            case 'greater_then':
                                if($vir_cat_qty > $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
    
                            case 'not_equal_to':
                                if($vir_cat_qty != $rule_cart_qty){
                                    $or_result = true;
                                }
                            break;
                        }

               
        return  $or_result;
    }

    function getVirtualCategoryQty( $vir_cat_id, $package, $package_support ){
        if(empty($vir_cat_id)) return 0;

        if(!function_exists('WC') || !is_object(WC()->cart)) return 0;
        $package_support = 'package';
        if($package_support == 'cart'){
            $products = WC()->cart->get_cart();
        }else{
            $products = PISOL\EFRS\Package::get_products($package);
        }
        
        $vir_cat_qty = 0;
        foreach($products as $product){
            $product_id = $product['product_id'];
            $variation_id = $product['variation_id'];
            if(pisol_efrs_custom_group_common::productBelongToVirtualCat( $product_id, $variation_id, $vir_cat_id )){
                $vir_cat_qty = $vir_cat_qty + $product['quantity'];
            }
        }
        return $vir_cat_qty;
    }
}


new Pi_efrs_selection_rule_virtual_category_quantity(PI_EFRS_SELECTION_RULE_SLUG);