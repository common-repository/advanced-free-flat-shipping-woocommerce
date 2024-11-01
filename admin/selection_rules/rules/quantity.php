<?php

class Pi_efrs_selection_rule_quantity{
    public $slug;
    public $condition;
    
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'quantity';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field blank of populated */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );


        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,5);

        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Quantity'),
            'group'=>'cart_related',
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
        if(!current_user_can( 'manage_options' )) {
            return;
            die;
        }
        $count = sanitize_text_field(filter_input(INPUT_POST,'count'));
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo Pi_efrs_selection_rule_main::createNumberField($count, $this->condition, null,1);
        die;
    }

    function savedDropdown($html, $values, $count){
        $html = Pi_efrs_selection_rule_main::createNumberField($count, $this->condition,  $values,1);
        return $html;
    }

    function get_user_quantity($package, $package_support){
        $package_support = 'package';
        if($package_support == 'cart'){
            return function_exists('WC') && is_object(WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
        }
        
        return PISOL\EFRS\Package::get_quantity($package);
        
    }


    function conditionCheck($result, $package, $logic, $values, $package_support){
        
                    $or_result = false;
                    $cart_quantity = $this->get_user_quantity($package, $package_support);
                    $rule_cart_quantity = (float)$values[0];
                    switch ($logic){
                        case 'equal_to':
                            if($cart_quantity == $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;

                        case 'less_equal_to':
                            if($cart_quantity <= $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;

                        case 'less_then':
                            if($cart_quantity < $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;

                        case 'greater_equal_to':
                            if($cart_quantity >= $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;

                        case 'greater_then':
                            if($cart_quantity > $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;

                        case 'not_equal_to':
                            if($cart_quantity != $rule_cart_quantity){
                                $or_result = true;
                            }
                        break;
                    }
               
        return  $or_result;
    }
}

new Pi_efrs_selection_rule_quantity(PI_EFRS_SELECTION_RULE_SLUG);