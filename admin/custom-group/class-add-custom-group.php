<?php

class Class_Pi_Efrs_Add_Edit_Custom_Group{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'pi_efrs_add_custom_group';

    private $tab_name = "Add Virtual Category";

    private $setting_key = 'pi_efrs_add_custom_group';
    
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
        add_action('wp_ajax_pisol_efrs_save_custom_group', array($this,'ajaxSave'));
        add_action('wp_ajax_pi_efrs_custom_group_category', array($this,'search_category'));
        add_action('wp_ajax_pi_efrs_custom_group_product', array($this,'search_product'));
    }

    function enqueueScript(){
        if(isset($_GET['page'], $_GET['tab']) && $_GET['page'] === 'pisol-efrs-notification' && $_GET['tab'] === 'pi_efrs_add_custom_group'){
            wp_enqueue_script( $this->plugin_name."_custom_group", plugin_dir_url( __FILE__ ) . 'js/custom-group.js', array('jquery'));
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

        include 'partials/addCustomGroup.php';
       
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
                    wp_send_json( array('success'=>"Virtual category saved", 'redirect' => $redirect_url));
                }
                wp_send_json( array('success'=>"Virtual category saved"));
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
            
            $data['pi_title']               =  get_the_title( $data['post_id'] );
            $data['pi_desc']        = get_post_meta( $data['post_id'], 'pi_desc', true );
            
            $data['pi_match_type']           = get_post_meta( $data['post_id'], 'pi_match_type', true );
            
            if(empty($data['pi_match_type'])){
                $data['pi_match_type'] = 'selected';
            }

            $data['pi_categories']           = get_post_meta( $data['post_id'], 'pi_categories', true );

            $data['pi_products']           = get_post_meta( $data['post_id'], 'pi_products', true );

            $data['pi_shipping_classes']   = get_post_meta( $data['post_id'], 'pi_shipping_classes', true );

            $data['pi_excluded_categories']           = get_post_meta( $data['post_id'], 'pi_excluded_categories', true );

            $data['pi_excluded_products']           = get_post_meta( $data['post_id'], 'pi_excluded_products', true );

            $data['pi_excluded_shipping_classes']   = get_post_meta( $data['post_id'], 'pi_excluded_shipping_classes', true );
            
        } else {
            $data['post_id']                = '';
            $data['pi_title']               =  '';
            $data['pi_desc']        = '';
            $data['pi_match_type'] = 'selected';
            $data['pi_categories']           = array();
            $data['pi_shipping_classes'] = array();
            $data['pi_products']           = array();
            $data['pi_excluded_categories'] = array();
            $data['pi_excluded_products']           = array();
            $data['pi_excluded_shipping_classes'] = array();
        }
        
        return $data;
    }

    static function methodExist($id){
        $post_exists = (new WP_Query(['post_type' => 'pi_efrs_custom_group', 'p'=>$id]))->found_posts > 0;

        return $post_exists;
    }

    function validate(){
        $error = new WP_Error();
        $cap = Pi_Efrs_Menu::getCapability();
        if ( !current_user_can($cap) 
        ) {
            $error->add( 'access', 'You are not authorized to make this changes ' );
        } 

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_custom_group' ) 
        ) {
            $error->add( 'invalid-nonce', 'Form has expired Reload the page and try again ' );
        } 

        if ( empty( $_POST['pi_title'] ) ) {
            $error->add( 'empty', 'Custom group name cant be empty' );
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

        if ( ! isset( $_POST['pisol_efrs_nonce'] ) || ! wp_verify_nonce( $_POST['pisol_efrs_nonce'], 'add_custom_group' ) 
        ) {

        return false;
        } 

        $cap = Pi_Efrs_Menu::getCapability();
        if ( empty( $post ) || !current_user_can( $cap )) {
			return false;
        }
        
        $post_type = sanitize_text_field(filter_input( INPUT_POST, 'post_type'));
		if ( isset( $post_type ) && 'pi_efrs_custom_group' === $post_type ) {
            if ($post['post_id'] === '' ) {
				$shipping_method_post = array(
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
					'post_type'   => 'pi_efrs_custom_group',
				);
				$post_id  = wp_insert_post( $shipping_method_post );
                $redirect_url = admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group&action=edit&id='.$post_id);
			} else {
				$shipping_method_post = array(
					'ID'          => (int)$post['post_id'],
					'post_title'  => $post['pi_title'],
					'post_status' => 'publish',
				);
				$post_id  = wp_update_post( $shipping_method_post );
            }
            
			if ( isset( $post['pi_desc'] ) ) {
				update_post_meta( $post_id, 'pi_desc', sanitize_textarea_field( $post['pi_desc'] ) );
			}

            if( isset( $post['pi_match_type'] ) && in_array($post['pi_match_type'], array('all', 'selected'))){
                update_post_meta( $post_id, 'pi_match_type',  $post['pi_match_type'] );
            }else{
                update_post_meta( $post_id, 'pi_match_type',  'selected' );
            }

            if( isset( $post['pi_categories'] ) && is_array($post['pi_categories'])){
                update_post_meta( $post_id, 'pi_categories',  $post['pi_categories'] );
            }else{
                update_post_meta( $post_id, 'pi_categories',  array() );
            }

            if( isset( $post['pi_products'] ) && is_array($post['pi_products'])){
                update_post_meta( $post_id, 'pi_products',  $post['pi_products'] );
            }else{
                update_post_meta( $post_id, 'pi_products',  array() );
            }

            if( isset( $post['pi_shipping_classes'] ) && is_array($post['pi_shipping_classes'])){
                update_post_meta( $post_id, 'pi_shipping_classes',  $post['pi_shipping_classes'] );
            }else{
                update_post_meta( $post_id, 'pi_shipping_classes',  array() );
            }

            if( isset( $post['pi_excluded_categories'] ) && is_array($post['pi_excluded_categories'])){
                update_post_meta( $post_id, 'pi_excluded_categories',  $post['pi_excluded_categories'] );
            }else{
                update_post_meta( $post_id, 'pi_excluded_categories',  array() );
            }

            if( isset( $post['pi_excluded_products'] ) && is_array($post['pi_excluded_products'])){
                update_post_meta( $post_id, 'pi_excluded_products',  $post['pi_excluded_products'] );
            }else{
                update_post_meta( $post_id, 'pi_excluded_products',  array() );
            }

            if( isset( $post['pi_excluded_shipping_classes'] ) && is_array($post['pi_excluded_shipping_classes'])){
                update_post_meta( $post_id, 'pi_excluded_shipping_classes',  $post['pi_excluded_shipping_classes'] );
            }else{
                update_post_meta( $post_id, 'pi_excluded_shipping_classes',  array() );
            }

            
			
            if(!empty($redirect_url)){
                return $redirect_url;
            }
            return true;

        }
    }

    static function allShippingClasses(){
        if(!function_exists('WC') || !is_object(WC()->shipping)) return array();

        $all_shipping_classes_obj = WC()->shipping->get_shipping_classes();
         
        $all_shipping_classes = array();
        foreach( $all_shipping_classes_obj as $obj ){
            $all_shipping_classes[$obj->term_id] = $obj->name;
        }
        return $all_shipping_classes;
    }

    public function search_category() {
        $cap = Pi_Efrs_Menu::getCapability();
		if ( ! current_user_can( $cap ) ) {
			return;
		}

		ob_start();

		$keyword = sanitize_text_field(filter_input( INPUT_GET, 'keyword'));

		if ( empty( $keyword ) ) {
			die();
		}
		$categories = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
				'search'   => $keyword,
				'number'   => 100
			)
		);
		$items      = array();
		if ( count( $categories ) ) {
			foreach ( $categories as $category ) {
				$item    = array(
					'id'   => $category->term_id,
					'text' => $category->name." (#{$category->term_id})"
				);
				$items[] = $item;
			}
		}
		wp_send_json( $items );
		die;
    }

    static function savedCategories( $categories_ids ){
        $html = '';
		if(empty($categories_ids)){
			$html = '';
			return $html;
		}
		$cat_names = array();
		foreach($categories_ids as $categories_id){
			$term_obj = get_term($categories_id);
			
			$cat_names[$categories_id]   = $term_obj->name;
			
		}

		foreach($cat_names as $key => $name){
			$html .= '<option value="'.$key.'" selected="selected">'.$name." (#{$key})".'</option>';
		}
		return $html;
	}

    public function search_product( $x = '', $post_types = array( 'product' ) ) {

		$cap = Pi_Efrs_Menu::getCapability();
		if ( ! current_user_can( $cap ) ) {
			return;
		}

        ob_start();
        
        if(!isset($_GET['keyword'])) die;

		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );

                if($prd->is_type('grouped') || $prd->is_type('external') || !is_object($prd)){
					continue;
				}

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
                    /** This is for the variable product */
                    $found_products[] = array(
                        'id'   => get_the_ID(),
                        'text' => strip_tags($prd->get_formatted_name())
                    );;
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							
                            $child_wc  = wc_get_product( $product_child );
                            $product   = array(
                                'id'   => $product_child,
                                'text' => strip_tags($child_wc->get_formatted_name())
                            );

							
							$found_products[] = $product;
						}

                    }
                    
                    
				} else {
					$product_id    = get_the_ID();
					$product_title = strip_tags($prd->get_formatted_name());
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
        }
		wp_send_json( $found_products );
		die;
    }


    function savedProducts( $product_ids ){
        $html = '';

		if(empty($product_ids)){
			$html = '';
			return $html;
		}

		$product_names = array();
		foreach($product_ids as $product_id){
			$child_wc  = wc_get_product( $product_id );
			if(!is_object($child_wc)) continue;
			$product_names[$product_id]   = strip_tags($child_wc->get_formatted_name());
		}

		foreach($product_names as $key => $name){
			$html .= '<option value="'.$key.'" selected="selected">'.$name.'</option>';
		}
		return $html;
	}

}

new Class_Pi_Efrs_Add_Edit_Custom_Group($this->plugin_name);