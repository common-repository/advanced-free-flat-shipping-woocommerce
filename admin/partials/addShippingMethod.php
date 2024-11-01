<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="row border-bottom align-items-center">
    <div class="col-12 py-2 bg-secondary">
        <strong class="h5 text-light"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ?  esc_html__('Edit shipping method','advanced-free-flat-shipping-woocommerce') : esc_html__('Add new shipping method','advanced-free-flat-shipping-woocommerce'); ?></strong>
    </div>
</div>
<form method="post" id="pisol-efrs-new-method">
<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_status" class="h6"><?php echo esc_html__('Status','advanced-free-flat-shipping-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <div class="custom-control custom-switch">
        <input type="checkbox" value="1" <?php echo esc_attr($data['pi_status']); ?> class="custom-control-input" name="pi_status" id="pi_status">
        <label class="custom-control-label" for="pi_status"></label>
        </div>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_title" class="h6"><?php echo esc_html__('Shipping Method Name','advanced-free-flat-shipping-woocommerce'); ?> <span class="text-primary">*</span></label>
    </div>
    <div class="col-12 col-sm">
        <input type="text" required value="<?php echo esc_attr($data['pi_title']); ?>" class="form-control" name="pi_title" id="pi_title">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_cost" class="h6"><?php echo esc_html__('Shipping Charges','advanced-free-flat-shipping-woocommerce'); ?> <?php echo '(' . esc_html( get_woocommerce_currency_symbol() ) . ')' ?> <span class="text-primary">*</span></label> <?php pisol_help::inline('shipping_charge_short_code_help','Creating complex shipping charges using short code'); ?>
    </div>
    <div class="col-12 col-sm">
        <input type="number" required value="<?php echo esc_attr($data['pi_cost']); ?>" class="form-control" name="pi_cost" id="pi_cost" step="any" min="0">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_desc" class="h6"><?php echo esc_html__('Description','advanced-free-flat-shipping-woocommerce'); ?></label> <?php pisol_help::tooltip(esc_html__('You can show this description below the shipping method name on the front end of the website by enabling the option present in the Extra settings tab','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <textarea type="text"  class="form-control" name="pi_desc" id="pi_desc"><?php echo esc_html($data['pi_desc']); ?></textarea>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_is_taxable" class="h6"><?php echo esc_html__('Taxable','advanced-free-flat-shipping-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_is_taxable" id="pi_is_taxable">
            <option value="no" <?php selected( $data['pi_is_taxable'], "no" ); ?>>No</option>
            <option value="yes" <?php selected( $data['pi_is_taxable'], "yes" ); ?>>Yes</option>
        </select>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_free_when_free_shipping_coupon" class="h6"><?php echo esc_html__('Make free when free shipping coupon is applied','advanced-free-flat-shipping-woocommerce'); ?></label>
        <p><?php echo esc_html__('Cost of this shipping method will become zero when user will apply Free shipping coupon','advanced-free-flat-shipping-woocommerce'); ?></p>
    </div>
    <div class="col-12 col-sm">
        <div class="custom-control custom-switch">
        <input type="checkbox" value="1" <?php echo esc_attr( $data['pi_free_when_free_shipping_coupon'] ); ?> class="custom-control-input" name="pi_free_when_free_shipping_coupon" id="pi_free_when_free_shipping_coupon">
        <label class="custom-control-label" for="pi_free_when_free_shipping_coupon"></label>
        </div>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_currency" class="h6"><?php echo esc_html__('Apply for currency (useful for multi currency website only)','advanced-free-flat-shipping-woocommerce'); ?></label><br><strong><?php echo esc_html__('Leave empty if you want to apply for all currency OR you have single currency','advanced-free-flat-shipping-woocommerce'); ?></strong><?php pisol_help::tooltip(esc_html__('Select the currency for which to apply the rule, if left blank it will apply for all the currency','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <select name="pi_currency[]" id="pi_currency" multiple="multiple">
                <?php self::get_currency($data['pi_currency']); ?>
        </select>
    </div>
</div>

<div class="border-bottom">
<?php
$selection_rule_obj = new Pi_efrs_selection_rule_main(
    esc_html__('Below conditions determine when to show this shipping method','advanced-free-flat-shipping-woocommerce'),
    $data['pi_metabox'], $data
);
wp_nonce_field( 'add_shipping_method', 'pisol_efrs_nonce');
?>
</div>

<div class="row py-4 border-bottom align-items-center bg-secondary">
<div class="col-12 col-md-5">
        <label for="pi_enable_other_method_modification" class="h6 text-light"><?php echo esc_html__('Modify other shipping methods when this method is applied','advanced-free-flat-shipping-woocommerce'); ?><?php pisol_help::tooltip('What happens to other shipping method when this shipping method becomes available for selection'); ?>
        <br>
        <span class="text-warning"><?php esc_html_e('(This is only available in pro)','advanced-free-flat-shipping-woocommerce'); ?></span>
        </label>
    </div>
<div class="col-12 col-md">
<select name="pi_what_to_do_to_other_methods-pro" id="" class="form-control">
    <option value="" selected="selected" ><?php esc_html_e('Select an option','advanced-free-flat-shipping-woocommerce'); ?></option>
    <option value="hide-all-other-methods" disabled><?php esc_html_e('Hide all other methods (PRO)','advanced-free-flat-shipping-woocommerce'); ?></option>
    <option value="hide-all-other-methods-exclude-local-pickup" disabled><?php esc_html_e('Hide all other methods (excluding WC Local Pickup method) (PRO)','advanced-free-flat-shipping-woocommerce'); ?></option>
    <option value="hide-all-non-plugin-methods-exclude-local-pickup" disabled><?php esc_html_e('Hide all methods except the one added by this plugin and WC Local pickup (PRO)','advanced-free-flat-shipping-woocommerce'); ?></option>
    <option value="hide-all-non-plugin-methods" disabled><?php esc_html_e('Hide all methods except the one added by this plugin (PRO)','advanced-free-flat-shipping-woocommerce'); ?></option>
    <option value="hide-all-plugin-method-with-lower-priority" disabled><?php esc_html_e('Hide all plugin methods with lower priority (PRO)','advanced-free-flat-shipping-woocommerce'); ?></option>
</select>
</div>
</div>

<div class="row py-4 border-bottom align-items-center bg-secondary">
    <div class="col-12">
        <strong class="h6 text-light"><?php echo esc_html__("Shipping Classes",'advanced-free-flat-shipping-woocommerce'); ?></strong><?php pisol_help::tooltip(esc_html__('These costs can optionally be added to shipping charge based on the product shipping class','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
</div>
<?php if(!empty($data['present_shipping_classes'])){ ?>
<?php foreach($data['present_shipping_classes'] as $shipping_class): 
    $shipping_extra_cost = isset( $data['shipping_extra_cost']["$shipping_class->term_id"] ) && ( $data['shipping_extra_cost']["$shipping_class->term_id"] !== '' ) ? $data['shipping_extra_cost']["$shipping_class->term_id"] : "";
    ?>
<div class="row py-2 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
    <label for="extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>"><?php echo esc_html( $shipping_class->name ); ?></label> <?php pisol_help::inline('shipping_class_charge_short_code_help','Creating complex shipping charges for shipping class'); ?>
    </div>
    <div class="col-12 col-sm">
    <input type="text" name="shipping_extra_cost[<?php echo esc_attr( $shipping_class->term_id ); ?>]" class="text-class"
										       id="shipping_extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>" value="<?php echo esc_attr( htmlentities( $shipping_extra_cost ) ); ?>"
										       placeholder="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>">
    </div>
</div>
<?php endforeach; ?>
<?php } ?>
<div class="row py-2 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
    <label for="pi_extra_cost_calc_type"><?php echo esc_html__('Calculation type','advanced-free-flat-shipping-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <select class="form-control" name="pi_extra_cost_calc_type" id="pi_extra_cost_calc_type" >
			<option value="class" <?php selected( $data['pi_extra_cost_calc_type'], "class" ); ?>><?php echo esc_html__('Per class: Charge shipping for each shipping class individually','advanced-free-flat-shipping-woocommerce'); ?></option>
			<option value="order" <?php selected( $data['pi_extra_cost_calc_type'], "order" ); ?>><?php echo esc_html__('Per order: Charge shipping for the most expensive shipping class','advanced-free-flat-shipping-woocommerce'); ?></option>
		</select>
    </div>
</div>


<?php do_action('pi_efrs_extra_form_fields', $data); ?>

<input type="hidden" name="post_type" value="pi_shipping_method">
<input type="hidden" name="post_id" value="<?php echo esc_attr($data['post_id']); ?>">
<input type="hidden" name="action" value="pisol_efrs_save_method">
<input type="submit" value="<?php esc_attr_e('Save Method', 'advanced-free-flat-shipping-woocommerce'); ?>" name="submit" class="m-2 mt-5 btn btn-primary btn-lg">
</form>
