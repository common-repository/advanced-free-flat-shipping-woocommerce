<?php

class pisol_efrs_procharges_tab{
    function __construct(){
        
        add_action('pi_efrs_additional_charges_tab', array($this, 'addTab'),10,1);

        add_action('pi_efrs_additional_charges_tab_content', array($this, 'addTabContent'),10,1);
    }

    function addTab($data){
        

        pisol_efrs_additional_charges_form::tabName('Based on Product Quantity <br><span class="badge badge-success">Pro</span>', 'product-quantity');
        pisol_efrs_additional_charges_form::tabName('Based on Category Quantity <br><span class="badge badge-success">Pro</span>', 'category-quantity');
        pisol_efrs_additional_charges_form::tabName('Based on Shippingclass Quantity <br><span class="badge badge-success">Pro</span>', 'shippingclass-quantity');

        pisol_efrs_additional_charges_form::tabName('Based on Product Subtotal <br><span class="badge badge-success">Pro</span>', 'product-subtotal');
        pisol_efrs_additional_charges_form::tabName('Based on Category Subtotal <br><span class="badge badge-success">Pro</span>', 'category-subtotal');
        pisol_efrs_additional_charges_form::tabName('Based on Shippingclass Subtotal <br><span class="badge badge-success">Pro</span>', 'shippingclass-subtotal');

        pisol_efrs_additional_charges_form::tabName('Based on Product Weight <br><span class="badge badge-success">Pro</span>', 'product-weight');
        pisol_efrs_additional_charges_form::tabName('Based on Category Weight <br><span class="badge badge-success">Pro</span>', 'category-weight');
        pisol_efrs_additional_charges_form::tabName('Based on Shippingclass Weight <br><span class="badge badge-success">Pro</span>', 'shippingclass-weight');

        pisol_efrs_additional_charges_form::tabName('Based on Virtual Category Quantity <br><span class="badge badge-success">Pro</span>', 'virtual-category-quantity');
        pisol_efrs_additional_charges_form::tabName('Based on Virtual Category Subtotal <br><span class="badge badge-success">Pro</span>', 'virtual-category-subtotal');
    }

    function addTabContent($data){
        $slugs = array('product-quantity' => 'lD7gm9PHkvE', 'category-quantity' => '6S1eVLuR6b8', 'shippingclass-quantity' => 'DK04pdaB4u0', 'product-subtotal' => 'sFdiwsoWvBw', 'category-subtotal' => 'XPNsq5U6FHA', 'shippingclass-subtotal' => 'GFuvQlEiELE', 'product-weight' => 'aOjKK5LfR04', 'category-weight'=>'gyhR2OvUDgw', 'shippingclass-weight' => 'qIZM7VUUy1c','virtual-category-quantity'=>'', 'virtual-category-subtotal' => '');
        foreach($slugs as $slug => $video){
        
        $active = ($slug == 'cart-quantity') ? 'pi-active-tab' : '';
        echo '<div class="p-2 border additional-charges-tab-content '.esc_attr($active).'" id="add-charges-tab-content-'.esc_attr($slug).'">';
        ?>
        <?php if(!empty($video)): ?>
        <div class="card mb-2 text-center"><strong><?php pisol_help::youtube($video,'Know more about this Charge'); ?> Click to Know more about this feature </strong></div>
        <?php endif; ?>
        <?php
            echo sprintf('<div class="free-version"><img src="%s" class="img-fluid "></div>', esc_url( plugin_dir_url(__FILE__).'/image/'.$slug.'.png'));
        echo '</div>';
        }
     }

}
new pisol_efrs_procharges_tab();