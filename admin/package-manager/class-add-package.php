<?php

class Class_Pi_Efrs_Add_Edit_Package{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'pi_efrs_add_package';

    private $tab_name = "Add Package";

    private $setting_key = 'pi_efrs_add_package';

    public $post_id;
    
    public $tab;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

       
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action( 'admin_enqueue_scripts', array($this, 'enqueueScript') );
        //add_action($this->plugin_name.'_tab', array($this,'tab'),2);
        add_action('wp_ajax_pisol_efrs_save_package', array($this,'ajaxSave'));
        add_action('wp_ajax_pisol_affsw_package_manager_change_status', array(__CLASS__,'enableDisable'));
    }

    function enqueueScript(){
        if(isset($_GET['page'], $_GET['tab']) && $_GET['page'] === 'pisol-efrs-notification' && $_GET['tab'] === 'package-manager'){
            wp_enqueue_script( $this->plugin_name."_package_manager", plugin_dir_url( __FILE__ ) . 'js/package-manager.js', array('jquery'));
        }
    }

    function tab_content(){
       $this->addEditCustomGroup();
    }

    function addEditCustomGroup(){
        
        $data = $this->formDate();

        if($data === false){
            echo '<div class="alert alert-danger mt-2">Custom group you are trying to edit does not exist, check the existing Group list</div>';
            return;
        }

        $attributes = wc_get_attribute_taxonomies();

        include 'partials/addPackage.php';
       
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
                    wp_send_json( array('success'=>"Package created", 'redirect' => $redirect_url));
                }
                wp_send_json( array('success'=>"Package created"));
            }
        }
    }


    function formDate(){
        $action_value = sanitize_text_field(filter_input( INPUT_GET, 'action'));
        $id_value     = sanitize_text_field(filter_input( INPUT_GET, 'id'));
        $data = array();
        
        if ( isset( $action_value ) && 'edit' === $action_value ) {

            if(!self::methodExist($id_value)) return false;

            $data['post_id']                 = $id_value;
            $data['pi_priority']           = get_post_meta( $data['post_id'], 'pi_priority', true );
            $data['pi_status']               = get_post_meta( $data['post_id'], 'pi_status', true );
            $data['pi_title']               =  get_the_title( $data['post_id'] );
            $data['pi_desc']        = get_post_meta( $data['post_id'], 'pi_desc', true );
            $data['pi_group']       = get_post_meta( $data['post_id'], 'pi_group', true );

            $data['pi_subtotal_logic']       = get_post_meta( $data['post_id'], 'pi_subtotal_logic', true );
            $data['pi_subtotal']       = get_post_meta( $data['post_id'], 'pi_subtotal', true );

            $data['pi_quantity_logic']       = get_post_meta( $data['post_id'], 'pi_quantity_logic', true );
            $data['pi_quantity']       = get_post_meta( $data['post_id'], 'pi_quantity', true );
            
            
        } else {
            $data['post_id']                = '';
            $data['pi_priority']           = 1;
            $data['pi_status']               = 'on';
            $data['pi_title']               =  '';
            $data['pi_desc']        = '';
            $data['pi_group']       = '';

            $data['pi_subtotal_logic'] = '';
            $data['pi_subtotal'] = '';

            $data['pi_quantity_logic'] = '';
            $data['pi_quantity'] = '';
        }

        $data['pi_status']       = ( ( ! empty( $data['pi_status'] ) && 'on' === $data['pi_status'] ) || empty( $data['pi_status'] ) ) ? 'checked' : '';
        
        return $data;
    }

    static function methodExist($id){
        $post_exists = (new WP_Query(['post_type' => 'pi_efrs_package', 'p'=>$id]))->found_posts > 0;

        return $post_exists;
    }

    function validate(){
        $error = new WP_Error();
        $cap = Pi_Efrs_Menu::getCapability();
        if ( !current_user_can($cap) 
        ) {
            $error->add( 'access', 'You are not authorized to make this changes ' );
        } 

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_package' ) 
        ) {
            $error->add( 'invalid-nonce', 'Form has expired Reload the page and try again ' );
        } 

        if ( empty( $_POST['pi_title'] ) ) {
            $error->add( 'empty', 'Custom group name cant be empty' );
        }

        if ( empty( $_POST['pi_group'] ) ) {
            $error->add( 'empty', 'You must select a virtual category' );
        }


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

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_package' ) 
        ) {

        return false;
        } 

        $cap = Pi_Efrs_Menu::getCapability();
        if ( empty( $post ) || !current_user_can( $cap )) {
			return false;
        }
        
        $post_type = sanitize_text_field(filter_input( INPUT_POST, 'post_type'));
		if ( isset( $post_type ) && 'pi_efrs_package' === $post_type ) {
            if ($post['post_id'] === '' ) {
				$shipping_method_post = array(
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
					'post_type'   => 'pi_efrs_package',
				);
				$post_id  = wp_insert_post( $shipping_method_post );
                $redirect_url = admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_package&action=edit&id='.$post_id);
			} else {
				$shipping_method_post = array(
					'ID'          => (int)$post['post_id'],
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
				);
				$post_id  = wp_update_post( $shipping_method_post );
            }

            /**
             * this will register the title as a string translation in WPML
             */
            do_action( 'wpml_register_single_string','extended-flat-rate-shipping-woocommerce', 'pisol_efrs_package_title_'.$post_id, $post['pi_title']);

            if ( isset( $post['pi_status'] ) ) {
				update_post_meta( $post_id, 'pi_status', "on" );
			} else {
				update_post_meta( $post_id, 'pi_status', "off");
            }

            if ( isset( $post['pi_priority'] ) ) {
				update_post_meta( $post_id, 'pi_priority', sanitize_text_field( $post['pi_priority'] ) );
            }else{
                update_post_meta( $post_id, 'pi_priority', 1 );
            }
            
			if ( isset( $post['pi_desc'] ) ) {
				update_post_meta( $post_id, 'pi_desc', sanitize_textarea_field( $post['pi_desc'] ) );
			}

            if ( isset( $post['pi_group'] ) ) {
				update_post_meta( $post_id, 'pi_group', sanitize_textarea_field( $post['pi_group'] ) );
			}else{
                update_post_meta( $post_id, 'pi_group', "" );
            }

            if ( isset( $post['pi_subtotal_logic'] ) ) {
				update_post_meta( $post_id, 'pi_subtotal_logic', sanitize_textarea_field( $post['pi_subtotal_logic'] ) );
			}else{
                update_post_meta( $post_id, 'pi_subtotal_logic', "" );
            }

            if ( isset( $post['pi_subtotal'] ) ) {
				update_post_meta( $post_id, 'pi_subtotal', sanitize_textarea_field( $post['pi_subtotal'] ) );
			}else{
                update_post_meta( $post_id, 'pi_subtotal', "" );
            }

            if ( isset( $post['pi_quantity_logic'] ) ) {
				update_post_meta( $post_id, 'pi_quantity_logic', sanitize_textarea_field( $post['pi_quantity_logic'] ) );
			}else{
                update_post_meta( $post_id, 'pi_quantity_logic', "" );
            }

            if ( isset( $post['pi_quantity'] ) ) {
				update_post_meta( $post_id, 'pi_quantity', sanitize_textarea_field( $post['pi_quantity'] ) );
			}else{
                update_post_meta( $post_id, 'pi_quantity', "" );
            }
        
        
            if(!empty($redirect_url)){
                return $redirect_url;
            }
            return true;

        }
    }

    static function enableDisable(){
        $post_id = sanitize_text_field(filter_input(INPUT_POST,'id'));
        $status = sanitize_text_field(filter_input(INPUT_POST,'status'));
        $cap = Pi_Efrs_Menu::getCapability();
        if(!current_user_can($cap) || empty($post_id)) return;
        
        if ( !empty($status) ) {
            update_post_meta( $post_id, 'pi_status', "on" );
        } else {
            update_post_meta( $post_id, 'pi_status', "off");
        }
        
    }
}

new Class_Pi_Efrs_Add_Edit_Package($this->plugin_name);