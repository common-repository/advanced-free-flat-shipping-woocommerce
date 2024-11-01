<?php

class Class_Pi_Efrs_Custom_group_List{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'custom-group';

    private $tab_name = "Virtual category";

    private $setting_key = 'pi_efrs_list_custom_group';
    
    public $post_id;

    public $tab;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

       
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $action = sanitize_text_field(filter_input(INPUT_POST, 'action'));
        if($action == 'efrs_custom_group_delete'){
            $this->post_id = sanitize_text_field(filter_input(INPUT_POST, 'method_id'));
            add_action('init',array($this,'deletePost' ));
        }

    }

    
    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>">
            <?php echo esc_html( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
       $this->listShippingMethod();
    }

    function listShippingMethod(){
        include plugin_dir_path( __FILE__ ) . 'partials/listShippingMethod.php';
    }

    function deletePost(){
        $nonce = sanitize_text_field(filter_input(INPUT_POST, 'nonce'));
        
        if(!wp_verify_nonce($nonce,'pisol-efrs-action-delete')){
            wp_die('Security check failed');
        }

        $cap = Pi_Efrs_Menu::getCapability();
        if(!current_user_can( $cap )) {
            wp_safe_redirect( esc_url( admin_url( '/admin.php?page=pisol-efrs-notification' ) ) );
            exit();
        }
        wp_delete_post($this->post_id);
        wp_safe_redirect(  admin_url( '/admin.php?page=pisol-efrs-notification&tab=custom-group' )  );
        exit();
    }
    
}

new Class_Pi_Efrs_Custom_group_List($this->plugin_name);