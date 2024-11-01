<?php

class Class_Pi_Efrs_Package_manager_list{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'package-manager';

    private $tab_name = "Split order in multiple shipping packages";

    private $setting_key = 'pi_efrs_list_package_manager';

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
        if($action == 'efrs_package_delete'){
            $this->post_id = sanitize_text_field(filter_input(INPUT_POST, 'method_id'));
            add_action('init',array($this,'deletePost' ));
        }

    }

    
    function tab(){
        $this->tab_name = __("Split order in multiple shipping packages",'extended-flat-rate-shipping-woocommerce');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>" title="this allows you to divide your order into multiple shipping packages and you can give multiple shipping option for each shipping package.">
            <?php echo esc_html( $this->tab_name); ?> <span class="ml-2 dashicons dashicons-info-outline"></span>
        </a>
        <?php
    }

    function tab_content(){
       $this->listShippingMethod();
    }

    function listShippingMethod(){
        include plugin_dir_path( __FILE__ ) . 'partials/listPackages.php';
    }

    function deletePost(){
        $cap = Pi_Efrs_Menu::getCapability();
        if(!current_user_can( $cap )) {
            wp_safe_redirect( esc_url( admin_url( '/admin.php?page=pisol-efrs-notification' ) ) );
            exit();
        }
        wp_delete_post($this->post_id);
        wp_safe_redirect(  admin_url( '/admin.php?page=pisol-efrs-notification&tab=package-manager' )  );
        exit();
    }
    
}

new Class_Pi_Efrs_Package_manager_list($this->plugin_name);