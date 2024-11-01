<?php

class Pi_Efrs_Menu{

    public $plugin_name;
    public $menu;
    public $version;
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'Flat Rate Shipping'),
            __( 'Flat Rate Shipping'),
            'manage_options',
            'pisol-efrs-notification',
            array($this, 'menu_option_page'),
            plugin_dir_url( __FILE__ ).'img/pi.svg',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        
 
    }

    public function bootstrap_style() {

        wp_enqueue_script('thickbox', null, array('jquery'));

        wp_enqueue_style( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name."_toast", plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array('jquery'), $this->version);

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/extended-flat-rate-shipping-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-additional-charges', plugin_dir_url( __FILE__ ) . 'js/extended-flat-rate-shipping-additional-charges.js', array( 'jquery' ), $this->version, false );

        // include the thickbox styles
        wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
        
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extended-flat-rate-shipping-woocommerce-admin.css', array(), $this->version, 'all' );
		
	}

    static function  getCapability(){
        $capability = 'manage_options';

        return (string)apply_filters('pisol_efrs_settings_cap', $capability);
    }

    function menu_option_page(){
        ?>
        <div class="bootstrap-wrapper">
        <div class="pisol-container-fluid mt-2">
            <div class="pisol-row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="pisol-row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                                <a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-primary ml-auto mr-0" href="https://www.piwebsolution.com/advance-flat-rate-shipping/" target="_blank">
                                Documentation 
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="pisol-row">
                <div class="col-12">
                <div id="pisol-efrs-notices"></div>
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
        include_once 'help.php';
    }

    function promotion(){
        if(isset($_GET['tab']) && $_GET['tab'] == 'pi_efrs_add_shipping') return;
        ?>
        <div class="col-12 col-sm-12 col-md-4 pt-3">

                <div class="bg-dark text-light text-center mb-3">
                    <a href="<?php echo esc_url( PI_EFRS_BUY_URL ); ?>&utm_ref=discount_banner" target="_blank">
                    <?php  new pisol_promotion("pi_efrs_installation_date"); ?>
                    </a>
                </div>

                <div class="pi-shadow">
                <div class="pisol-row justify-content-center">
                    <div class="col-md-7 col-sm-12">
                        <div class="p-2  text-center">
                            <img class="img-fluid" src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>img/bg.svg">
                        </div>
                    </div>
                </div>
                <div class="text-center py-2">
                    <a class="btn btn-success btn-sm text-uppercase mb-2 " href="<?php echo esc_url(PI_EFRS_BUY_URL); ?>&utm_ref=top_link" target="_blank">Buy Now !!</a>
                    <a class="btn btn-sm mb-2 btn-secondary text-uppercase" href="https://websitemaintenanceservice.in/flat_shipping/" target="_blank">Try Demo</a>
                </div>
                <h2 id="pi-banner-tagline" class="mb-0">Get Pro for <?php echo esc_html(PI_EFRS_PRICE); ?> Only</h2>
                <div class="inside">
                    <ul class="text-left pisol-pro-feature-list">
                        <li class="border-top font-weight-light h6">If user is from a specific <strong class="text-primary">State / County</strong></li>
                        <li class="border-top font-weight-light h6">When the user is from specific <strong class="text-primary">Postcode</strong></li>
                        <li class="border-top font-weight-light h6">Allows you to specify <strong class="text-primary">Range of postal code</strong> E.g: 9001...9050</li>
                        <li class="border-top font-weight-light h6">If customer is buying a specific <strong class="text-primary">Product (Support Variable Product)</strong></li>
                        <li class="border-top font-weight-light h6">If the <strong class="text-primary">Cart Subtotal before discount / Cart Subtotal after discount</strong> is grater then, or less then or equal to specific value set by you</li>
                        <li class="border-top font-weight-light h6">Based on <strong class="text-primary">User Role</strong></li>
                        <li class="border-top font-weight-light h6">If user is from specific <strong class="text-primary">Shipping zone</strong></li>
                        <li class="border-top font-weight-light h6">If the <strong class="text-primary">Total Weight of product</strong> in cart is grater, less or equal to specific value set by you</li>
                        <li class="border-top font-weight-light h6">If the product with the max <strong class="text-primary">Width</strong> in the cart is grater then, less then or equal to your set value</li>
                        <li class="border-top font-weight-light h6">If the product with the max <strong class="text-primary">Height</strong> in the cart is grater then, less then or equal to your set value</li>
                        <li class="border-top font-weight-light h6">If the product with the max <strong class="text-primary">Length</strong> in the cart is grater then, less then or equal to your set value</li>
                        <li class="border-top font-weight-light h6">When user apply some specific <strong class="text-primary">Coupon Code</strong></li>
                        <li class="border-top font-weight-light h6">Apply shipping method when product with specific <strong class="text-primary">Shipping Class</strong> is present in the cart</li>
                        <li class="border-top font-weight-light h6">When a specific <strong class="text-primary">Payment Method</strong> is selected by the customer</li>
                        <li class="border-top font-weight-light h6">Create <strong class="text-primary">Unlimited method</strong> based on various combinations of the above rules</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Set method priority</strong> so higher priority method will be above, and this priority is used in other things rules as well</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Remove all other methods</strong> when a particular method is activated</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Remove all other methods of this plugin</strong>, when a particular method is activated</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Remove all other methods of low priority of this plugin</strong>, when a particular method is activated</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Shipping class total</strong> this rule applies when customer has purchased an x amount of product from specific shipping class</li> 
                        <li class="border-top font-weight-light h6"><strong class="text-primary">Shipping class total quantity of product in cart</strong> this rule applies when customer has added x unit of product from a specific shipping class in his cart</li>
                        <li class="border-top font-weight-light h6"><strong class="text-primary">User city based method</strong>: You can offer method based on user city, it is string comparison</li> 
                        <li class="border-top font-weight-light h6">Shipping method based on <strong class="text-primary">Day of the week</strong></li>
                        <li class="border-top font-weight-light h6">Support for <strong class="text-primary">[qty] and [fee]</strong> short code in the Shipping charges field </li>
                        <li class="border-top font-weight-light h6">Support for <strong class="text-primary">[qty]</strong> short code in the Shipping class charges field </li>
                        <li class="border-top font-weight-light h6">Add extra charge based on <strong class="text-primary">Product quantity, Product subtotal, Product Weight</strong></li>
                        <li class="border-top font-weight-light h6">Add extra charge based on <strong class="text-primary">Category quantity, Category subtotal, Category Weight</strong></li>
                        <li class="border-top font-weight-light h6">Add extra charge based on <strong class="text-primary">Shipping class quantity, Shipping class subtotal, Shipping class Weight</strong></li>
                        <li class="border-top font-weight-light h6"> <strong class="text-primary">Attribute</strong> based shipping method</li>
                        <li class="border-top font-weight-light h6"> <strong class="text-primary">Add product or variation of product to Virtual category</strong></li>
                        <li class="border-top font-weight-light h6"> <strong class="text-primary">Exclude product or variation of product from Virtual category</strong></li>
                        <li class="border-top font-weight-light h6"> <strong class="text-primary">Combine multiple shipping methods</strong> in to a single method</li>
                    </ul>
                    <div class="text-center pb-3 pt-2">
                        <a class="btn btn-primary btn-md" href="<?php echo esc_url( PI_EFRS_BUY_URL ); ?>&utm_ref=bottom_link" target="_blank">BUY PRO VERSION</a>
                    </div>
                </div>
                </div>
        </div>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}