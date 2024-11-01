<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="row border-bottom align-items-center">
    <div class="col-12 py-2 bg-primary">
        <strong class="h5 text-light"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ?  esc_html__('Edit Virtual Category','advanced-free-flat-shipping-woocommerce') : esc_html__('Add New Virtual Category','advanced-free-flat-shipping-woocommerce'); ?></strong>
    </div>
</div>
<form method="post" id="pisol-efrs-new-method">
<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_title" class="h6"><?php echo esc_html__('Virtual category name','advanced-free-flat-shipping-woocommerce'); ?> <span class="text-primary">*</span></label><?php pisol_help::tooltip(esc_html__('You can club multiple categories and product to form a virtual category of products and latter on use that inside the Shipping method rules','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <input type="text" required value="<?php echo esc_attr( $data['pi_title'] ); ?>" class="form-control" name="pi_title" id="pi_title">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_desc" class="h6"><?php echo esc_html__('Description','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('This description is for your own reference','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <textarea type="text"  class="form-control" name="pi_desc" id="pi_desc"><?php echo esc_html( $data['pi_desc'] ); ?></textarea>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label class="h6"><?php echo esc_html__('Include','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('You can add all the product in this virtual category and then exclude product or you can only add selected product to the virtual category','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm align-items-center">
        <div class="row align-items-center">
        <div class="col-6">
        <input type="radio" name="pi_match_type" value="all" id="match-type-all" <?php checked($data['pi_match_type'], 'all'); ?>> <label for="match-type-all" class="my-0"><?php esc_html_e('All Products','advanced-free-flat-shipping-woocommerce'); ?></label>
        </div>
        <div class="col-6">
        <input type="radio" name="pi_match_type" value="selected" id="match-type-selected" <?php checked($data['pi_match_type'], 'selected'); ?>> <label for="match-type-selected"  class="my-0"><?php esc_html_e('Selected Products','advanced-free-flat-shipping-woocommerce'); ?></label>
        </div>
        </div>
    </div>
</div>

<div id="pi-selection-columns">

<div id="pi-include-product-group">
<div class="row py-4 border-bottom align-items-center bg-secondary">
    <div class="col-12">
        <strong class="h6 text-light"><?php echo esc_html__("Include Products",'advanced-free-flat-shipping-woocommerce'); ?></strong><?php pisol_help::tooltip(esc_html__('Include product to this virtual group using below selectors','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
</div>
<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_categories" class="h6"><?php echo esc_html__('Categories to include','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product directly belonging to this category will be part of virtual category, product belonging to the child category of the included category will not be part of virtual category.','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi_efrs_custom_group_search_category form-control" name="pi_categories[]" multiple="multiple">
            <?php echo wp_kses( self::savedCategories(  $data['pi_categories'] ), [
                'option' => [
                    'value' => [],
                    'selected' => [],
                ],
            ]); ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_shipping_classes" class="h6"><?php echo esc_html__('Shipping class to include','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Products belonging to this shipping class will be part of this virtual category','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi-efrs-custom-group-simple-select form-control" name="pi_shipping_classes[]" multiple="multiple">
            <?php  
                $shipping_classes = self::allShippingClasses();
                $data['pi_shipping_classes'] = is_array($data['pi_shipping_classes']) ? $data['pi_shipping_classes'] : array();
                foreach($shipping_classes as $class_id => $class){
                    echo '<option value="'.esc_attr($class_id).'" '.(in_array($class_id, $data['pi_shipping_classes']) ? ' selected="selected" ': '').'>'.esc_html($class).'</option>';
                }
            ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_products" class="h6"><?php echo esc_html__('Products to include','advanced-free-flat-shipping-woocommerce'); ?></label><p><?php esc_html_e('Product that you want to be part of this virtual category, you can even include a variation of a product','advanced-free-flat-shipping-woocommerce'); ?></p>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi_efrs_custom_group_search_product form-control" name="pi_products[]" multiple="multiple">
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_stock_status" class="h6"><?php echo esc_html__('Include product with Stock status','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product stock status will decide if product can be part of this virtual category or not','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="form-control" name="pi_stock_status" id="pi_stock_status">
            <?php  
                $stock_status = array(
                    'instock' => __('In stock','advanced-free-flat-shipping-woocommerce'),
                    'onbackorder' => __('On backorder','advanced-free-flat-shipping-woocommerce'),
                );
                
                echo '<option value="">'.esc_html__('Select product (In Stock / On Back Order)','advanced-free-flat-shipping-woocommerce').'</option>';
                foreach($stock_status as $status_id => $status){
                    $select_status = '';
                    echo '<option value="'.esc_attr($status_id).'" >'.esc_html($status).'</option>';
                }
            ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_product_subtotal" class="h6"><?php echo esc_html__('Include Product with subtotal','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product with subtotal matching the logic will be excluded from the group','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm-4">
        <select type="text" class="form-control" name="pi_product_subtotal_logic">
            <option value=""><?php echo esc_html_e('Don\'t consider this rule','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="equal_to"><?php echo esc_html_e('Equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="less_equal_to"><?php echo esc_html_e('Less then equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="less_then"><?php echo esc_html_e('Less then','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="greater_equal_to"><?php echo esc_html_e('Grater then equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="greater_then"><?php echo esc_html_e('Grater then','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="not_equal_to"><?php echo esc_html_e('Not equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
        </select>
    </div>
    <div class="col-12 col-sm-3">
        <input type="number" class="form-control" step="0.01" min="0" name="pi_product_subtotal" placeholder="<?php esc_attr_e('Insert subtotal','advanced-free-flat-shipping-woocommerce'); ?>">
    </div>
</div>

</div>


<div id="pi-exclude-product-group">
<div class="row py-4 border-bottom align-items-center bg-danger">
    <div class="col-12">
        <strong class="h6 text-light"><?php echo esc_html__("Exclude Products",'advanced-free-flat-shipping-woocommerce'); ?></strong><?php pisol_help::tooltip(esc_html__('Exclude product from this virtual group using below selectors','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_excluded_categories" class="h6"><?php echo esc_html__('Categories to exclude','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product directly belonging to this category will be excluded from virtual category','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi_efrs_custom_group_search_category form-control" name="pi_excluded_categories[]" multiple="multiple">
            <?php echo wp_kses( self::savedCategories(  $data['pi_excluded_categories'] ) , [
                'option' => [
                    'value' => [],
                    'selected' => [],
                ],
            ]); ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_excluded_shipping_classes" class="h6"><?php echo esc_html__('Shipping class to exclude','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Products belonging to this shipping class will not be part of virtual category','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi-efrs-custom-group-simple-select form-control" name="pi_excluded_shipping_classes[]" multiple="multiple">
            <?php  
                $data['pi_excluded_shipping_classes'] = is_array($data['pi_excluded_shipping_classes']) ? $data['pi_excluded_shipping_classes'] : array();
                foreach($shipping_classes as $class_id2 => $class2){
                    echo '<option value="'.esc_attr($class_id2).'" '.(in_array($class_id2, $data['pi_excluded_shipping_classes']) ? ' selected="selected" ': '').'>'.esc_html($class2).'</option>';
                }
            ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_excluded_products" class="h6"><?php echo esc_html__('Products to exclude','advanced-free-flat-shipping-woocommerce'); ?></label><p><?php echo esc_html__('Product that you want to be excluded from this virtual category, you can even include a variation of a product','advanced-free-flat-shipping-woocommerce'); ?></p>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="pi_efrs_custom_group_search_product form-control" name="pi_excluded_products[]" multiple="multiple">
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_excluded_stock_status" class="h6"><?php echo esc_html__('Exclude product with Stock status','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product stock status will decide if product can be part of this virtual category or not','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select type="text" class="form-control" name="pi_excluded_stock_status" id="pi_excluded_stock_status">
            <?php  
                $stock_status = array(
                    'instock' => __('In stock','advanced-free-flat-shipping-woocommerce'),
                    'onbackorder' => __('On backorder','advanced-free-flat-shipping-woocommerce'),
                );
                
                echo '<option value="">'.esc_html__('Select product (In Stock / On Back Order)','advanced-free-flat-shipping-woocommerce').'</option>';
                foreach($stock_status as $status_id => $status){
                    $select_status =  '';
                    echo '<option value="'.esc_attr($status_id).'" >'.esc_html($status).'</option>';
                }
            ?>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_excluded_product_subtotal" class="h6"><?php echo esc_html__('Exclude Product with subtotal','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product with subtotal matching the logic will be excluded from the group','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm-4">
        <select type="text" class="form-control" name="pi_excluded_product_subtotal_logic">
            <option value=""><?php echo esc_html__('Don\'t consider this rule','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="equal_to"><?php echo esc_html__('Equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="less_equal_to"><?php echo esc_html__('Less then equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="less_then"><?php echo esc_html__('Less then','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="greater_equal_to"><?php echo esc_html__('Grater then equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="greater_then"><?php echo esc_html__('Grater then','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option value="not_equal_to"><?php echo esc_html__('Not equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
        </select>
    </div>
    <div class="col-12 col-sm-3">
        <input type="number" class="form-control" step="0.01" min="0" name="pi_excluded_product_subtotal" placeholder="<?php echo esc_attr__('Insert subtotal','advanced-free-flat-shipping-woocommerce'); ?>">
    </div>
</div>

</div>

</div>


<?php wp_nonce_field( 'add_custom_group', 'pisol_efrs_nonce'); ?>
<input type="hidden" name="post_type" value="pi_efrs_custom_group">
<input type="hidden" name="post_id" value="<?php echo esc_attr( $data['post_id'] ); ?>">
<input type="hidden" name="action" value="pisol_efrs_save_custom_group">
<input type="submit" value="<?php esc_attr_e('Save Package','advanced-free-flat-shipping-woocommerce'); ?>" name="submit" class="m-2 mt-5 btn btn-primary btn-lg" id="pi-efrs-new-shipping-method-form">
</form>
