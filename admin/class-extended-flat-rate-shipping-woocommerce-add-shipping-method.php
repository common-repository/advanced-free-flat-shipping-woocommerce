<?php

class Class_Pi_Efrs_Add_Edit{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'pi_efrs_add_shipping';

    private $tab_name = "Add flat shipping";

    private $setting_key = 'pi_efrs_add_shipping';
    
    public $tab;
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

       
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        //add_action($this->plugin_name.'_tab', array($this,'tab'),2);
        add_action('wp_ajax_pisol_affsw_change_status', array(__CLASS__,'enableDisable'));
        add_action('wp_ajax_pisol_efrs_save_method', array($this,'ajaxSave'));

    }

    
    function tab(){
        $page =  sanitize_text_field(filter_input( INPUT_GET, 'page'));
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ) ); ?>">
            <?php echo esc_html( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
       $this->addEditShippingMethod();
    }

    function addEditShippingMethod(){
        $data = $this->formDate();

        if($data === false){
            echo '<div class="alert alert-danger mt-2">Shipping method you are trying to edit does not exist, check the existing Shipping methods list</div>';
            return;
        }
        
        include plugin_dir_path( __FILE__ ) . 'partials/addShippingMethod.php';
    }

    function ajaxSave(){
        $message = array();
        $error =  $this->validate();
        if(is_wp_error($error)){
            $error_msg = $this->showError($error);
            wp_send_json( array('error'=> $error_msg) );
        }else{
            $post = $_POST;
            /** Save form and redirect to list */
            $save_form_result = $this->saveForm($post);
            if($save_form_result === false){
                wp_send_json( array('error'=>array("There was some error in saving refresh the page and try again")));
            }else{
                if($save_form_result !== true){
                    $redirect_url =  $save_form_result;
                    wp_send_json( array('success'=>"Shipping method saved", 'redirect' => $redirect_url));
                }
                wp_send_json( array('success'=>"Shipping method saved"));
            }
        }
    }

    function formDate(){
        $action_value = sanitize_text_field(filter_input( INPUT_GET, 'action'));
        $id_value     = sanitize_text_field(filter_input( INPUT_GET, 'id'));
        $data = array();
        $present_shipping_classes = function_exists('WC') && is_object(WC()->shipping) ? WC()->shipping->get_shipping_classes() : array();

        $data['present_shipping_classes'] = !empty($present_shipping_classes) ? $present_shipping_classes : array();

        $data['present_shipping_classes'][] = (object)['term_id' => 'pi-no-shipping-class', 'name'=> __('No shipping class')];
        
        if ( isset( $action_value ) && 'edit' === $action_value ) {

            if(!self::methodExist($id_value)) return false;

            $data['post_id']                 = $id_value;
            $data['pi_status']               = get_post_meta( $data['post_id'], 'pi_status', true );
            $data['pi_title']               =  get_the_title( $data['post_id'] );
            $data['pi_cost']                 = get_post_meta( $data['post_id'], 'pi_cost', true );
            $data['pi_desc']        = get_post_meta( $data['post_id'], 'pi_desc', true );
            $data['pi_is_taxable']           = get_post_meta( $data['post_id'], 'pi_is_taxable', true );
            $data['shipping_extra_cost']           = get_post_meta( $data['post_id'], 'shipping_extra_cost', true );
            $data['pi_extra_cost_calc_type'] = get_post_meta( $data['post_id'], 'pi_extra_cost_calc_type', true );
            $data['pi_metabox']              = get_post_meta( $data['post_id'], 'pi_metabox', true );
            $data['min_days'] = get_post_meta( $data['post_id'], 'min_days', true );
            $data['max_days'] = get_post_meta( $data['post_id'], 'max_days', true );
            $data['pi_condition_logic'] = empty(get_post_meta( $data['post_id'], 'pi_condition_logic', true )) ? 'and' : get_post_meta( $data['post_id'], 'pi_condition_logic', true ); 

            $data['pi_free_when_free_shipping_coupon'] = get_post_meta( $data['post_id'], 'pi_free_when_free_shipping_coupon', true );

            $data['pi_currency']    = get_post_meta($data['post_id'], 'pi_currency', true);
        } else {
            $data['post_id']                = '';
            $data['pi_status']               = '';
            $data['pi_title']                = '';
            $data['pi_cost']                 = '';
            $data['pi_desc']         = '';
            $data['pi_is_taxable']           = '';
            $data['pi_condition_logic']           = 'and';
            $data['min_days'] = "";
            $data['max_days'] = "";
            $data['shipping_extra_cost']           = array();
            $data['pi_extra_cost_calc_type'] = '';
            $data['pi_metabox']              = array();

            $data['pi_free_when_free_shipping_coupon'] = '';

            $data['pi_currency'] = [];
        }
        
        $data['pi_status']       = ( ( ! empty( $data['pi_status'] ) && 'on' === $data['pi_status'] ) || empty( $data['pi_status'] ) ) ? 'checked' : '';
        $data['pi_title']        = ! empty( $data['pi_title'] ) ? esc_attr( stripslashes( $data['pi_title'] ) ) : '';
        $data['pi_cost']         = ( '' !== $data['pi_cost'] ) ? esc_attr( stripslashes( $data['pi_cost'] ) ) : '';
        $data['pi_desc'] = ! empty( $data['pi_desc'] ) ? $data['pi_desc'] : '';

        $data['pi_free_when_free_shipping_coupon']       = ! empty( $data['pi_free_when_free_shipping_coupon'] ) && 'on' === $data['pi_free_when_free_shipping_coupon']  ? 'checked' : '';

        return apply_filters('pi_efrs_shipping_method_form_data',$data);
    }

    static function methodExist($id){

        if(!filter_var($id, FILTER_VALIDATE_INT)) return false;

        $post_exists = (new WP_Query(['post_type' => 'pi_shipping_method', 'p'=>$id]))->found_posts > 0;

        return $post_exists;
    }

    function validate(){
        $error = new WP_Error();

        if ( !current_user_can('editor') && !current_user_can('administrator') 
        ) {
            $error->add( 'access', 'You are not authorized to make this changes ' );
        } 

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_shipping_method' ) 
        ) {
            $error->add( 'invalid-nonce', 'Form has expired Reload the form and try again ' );
        } 

        if ( empty( $_POST['pi_title'] ) ) {
            $error->add( 'empty', 'Shipping Method Name cant be empty' );
        }

       

        if ( empty( $_POST['pi_selection'] ) ) {
            $error->add( 'empty', 'You have not added any Selection Rules' );
        }

        $error = apply_filters('pisol_efrs_validate_shipping_method', $error);

        if ( !empty( $error->get_error_codes() ) ) {
            return $error;
        }
    
        return true;
    }

    function showError($error){

        return $error->get_error_messages();
    }

    /**
     * return true (in case of editing of existing method), 
     * false, 
     * redirect url (in case of newly created shipping method)
     */
    function saveForm($post){

        $redirect_url = "";

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_shipping_method' ) 
        ) {

        return false;
        } 

        if ( empty( $post ) || !current_user_can( 'manage_options' )) {
			return false;
        }
        
        $post_type = sanitize_text_field(filter_input( INPUT_POST, 'post_type'));
		if ( isset( $post_type ) && 'pi_shipping_method' === $post_type ) {
            if ($post['post_id'] === '' ) {
				$shipping_method_post = array(
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
					'post_type'   => 'pi_shipping_method',
				);
				$post_id  = wp_insert_post( $shipping_method_post );
                $redirect_url = admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_shipping&action=edit&id='.$post_id);
			} else {
				$shipping_method_post = array(
					'ID'          => (int)$post['post_id'],
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
				);
				$post_id  = wp_update_post( $shipping_method_post );
            }
            
            if ( isset( $post['pi_status'] ) ) {
				update_post_meta( $post_id, 'pi_status', "on" );
			} else {
				update_post_meta( $post_id, 'pi_status', "off");
			}
			if ( isset( $post['pi_cost'] ) ) {
				update_post_meta( $post_id, 'pi_cost', sanitize_text_field( $post['pi_cost'] ) );
			}
			if ( isset( $post['pi_desc'] ) ) {
				update_post_meta( $post_id, 'pi_desc', sanitize_textarea_field( $post['pi_desc'] ) );
			}
			if ( isset( $post['pi_is_taxable'] ) ) {
				update_post_meta( $post_id, 'pi_is_taxable', sanitize_text_field( $post['pi_is_taxable'] ) );
			}
			if ( isset( $post['shipping_extra_cost'] ) ) {
				update_post_meta( $post_id, 'shipping_extra_cost', array_map('sanitize_text_field', $post['shipping_extra_cost']) );
			}
			if ( isset( $post['pi_extra_cost_calc_type'] ) ) {
				update_post_meta( $post_id, 'pi_extra_cost_calc_type', sanitize_text_field( $post['pi_extra_cost_calc_type'] ) );
            }

            if ( isset( $post['min_days'] ) ) {
				update_post_meta( $post_id, 'min_days', sanitize_text_field( $post['min_days'] ) );
            }

            if ( isset( $post['max_days'] ) ) {
				update_post_meta( $post_id, 'max_days', sanitize_text_field( $post['max_days'] ) );
            }

            if ( isset( $post['pi_condition_logic'] ) ) {
				update_post_meta( $post_id, 'pi_condition_logic', sanitize_text_field( $post['pi_condition_logic'] ) );
            }else{
                update_post_meta( $post_id, 'pi_condition_logic', 'and' );
            }

            if ( isset( $post['pi_free_when_free_shipping_coupon'] ) ) {
				update_post_meta( $post_id, 'pi_free_when_free_shipping_coupon', "on" );
			} else {
				update_post_meta( $post_id, 'pi_free_when_free_shipping_coupon', "off");
            }

            if(isset($post['pi_currency']) && is_array($post['pi_currency'])){
                update_post_meta( $post_id, 'pi_currency', ($post['pi_currency']) );
            }else{
                update_post_meta( $post_id, 'pi_currency', []);
            }


            $pi_selection  = array();
            $conditions = isset($post['pi_selection']) ? $post['pi_selection'] : array();
            if(is_array($conditions)){
            foreach($conditions as $key => $condition){
                $pi_selection[] = array(
                    'pi_condition'=>$condition['pi_efrs_condition'],
                    'pi_logic'=>isset($condition['pi_efrs_logic']) ? $condition['pi_efrs_logic'] : "",
                    'pi_value'=>isset($condition['pi_efrs_condition_value']) ? $condition['pi_efrs_condition_value'] : ""
                );
            }
            }

            if(is_array($pi_selection)){
                update_post_meta( $post_id, 'pi_metabox', $pi_selection );
            }

            do_action('pisol_efrs_save_shipping_method', $post_id);
            
            if(!empty($redirect_url)){
                return $redirect_url;
            }

            return true;

        }
    }

    static function enableDisable(){
        $post_id = sanitize_text_field(filter_input(INPUT_POST,'id'));
        $status = sanitize_text_field(filter_input(INPUT_POST,'status'));
        $nonce = sanitize_text_field(filter_input(INPUT_POST,'nonce'));

        if(!wp_verify_nonce($nonce,'pisol-efrs-action-status')) return;

        if(!current_user_can('administrator') || empty($post_id)) return;
        
        if ( !empty($status) ) {
            update_post_meta( $post_id, 'pi_status', "on" );
        } else {
            update_post_meta( $post_id, 'pi_status', "off");
        }
        
    }

    static function get_currency($saved_currency = array()){
        if(!is_array($saved_currency)) $saved_currency = array();

        $all_currencies = get_woocommerce_currencies();
        foreach($all_currencies as $currency => $name){
            $selected = in_array($currency, $saved_currency) ? 'selected' : '';
            echo '<option value="'.esc_attr($currency).'" '.esc_attr($selected).'>'.esc_html($name).'</option>';
        }
    }
    
}

new Class_Pi_Efrs_Add_Edit($this->plugin_name);