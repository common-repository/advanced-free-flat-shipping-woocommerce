<?php

class pisol_affsw_options{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'extra_options';

    private $tab_name = "Extra settings";

    private $setting_key = 'affsw_extra_setting';
    
    public $tab;
    

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;


        $this->settings = array(

            array('field'=>'pi_efrs_show_only_one_method', 'label'=>__('Give only single shipping method option to customer'), 'desc'=>__('If you only want to show one shipping method so user dont have to choose from multiple option then use this setting'), 'type'=>'select', 'default'=>"", 'value' => ['' => "Select an option", 'lowest' => 'Minimum: Show the shipping method with lowest shipping charge', 'highest' => 'Maximum: Show the shipping method with highest shipping charge']),

            array('field'=>'pisol_affsw_show_zero_cost', 'label'=>__('Show zero charge for shipping method'), 'desc'=>__('When shipping method is free woocommerce will not show the charge, once this is enabled it will show $0 charge next to the shipping method that are free'), 'type'=>'switch', 'default'=>"0"),

            array('field'=>'pi_affsw_moveto_top', 'label'=>__('Move the plugin methods to top'), 'desc'=>__('When enabled it will move the plugin added shipping method to the top of the list of the shipping method '), 'type'=>'switch', 'default'=>"1", 'pro'=>true),

            array('field'=>'pi_affsw_show_desc_on_front', 'label'=>__('Show shipping description'), 'desc'=>__('Show shipping method description below the shipping method name or as a tooltip on the checkout page '), 'type'=>'select', 'default'=>"0", 'value' => [0 => "Dont show", '1' => 'Show below shipping method name', 'tooltip' => 'Show as tooltip'], 'pro'=>true),

        );
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),10);

       
        $this->register_settings();

        add_action('woocommerce_after_shipping_rate', array($this,'getMethodName'),9999,2);
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        $this->tab_name = __("Extra settings",'extended-flat-rate-shipping-woocommerce');
        ?>
        <a class=" px-3 py-2 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ) ); ?>">
            <?php echo esc_html( $this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_affsw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

    function getMethodName($method, $index){
        $view_name = get_option('pisol_affsw_show_system_name', 0);
        $require_capability = Pi_Efrs_Menu::getCapability();
        if(current_user_can( $require_capability ) && !empty($view_name)){
            echo '<small>System name: <strong>'.esc_html( $method->get_id() ).'</strong></small>';
        }
    }
}

new pisol_affsw_options($this->plugin_name);