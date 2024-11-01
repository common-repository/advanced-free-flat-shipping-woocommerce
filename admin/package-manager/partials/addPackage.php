<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="row border-bottom align-items-center">
    <div class="col-12 py-2 bg-primary">
        <strong class="h5 text-light"><?php echo isset($_GET['action']) && $_GET['action'] === 'edit' ?  esc_html__('Edit Package','advanced-free-flat-shipping-woocommerce') : esc_html__('Add New Package','advanced-free-flat-shipping-woocommerce'); ?></strong>
    </div>
</div>
<form method="post" id="pisol-efrs-new-method">
<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_status" class="h6"><?php echo esc_html__('Status','advanced-free-flat-shipping-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <div class="custom-control custom-switch">
        <input type="checkbox" value="1" <?php echo esc_attr( $data['pi_status'] ); ?> class="custom-control-input" name="pi_status" id="pi_status">
        <label class="custom-control-label" for="pi_status"></label>
        </div>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_priority" class="h6"><?php echo esc_html__('Package priority','advanced-free-flat-shipping-woocommerce'); ?></label>
    </div>
    <div class="col-12 col-sm">
        <input type="number" value="<?php echo esc_attr( $data['pi_priority'] ); ?>" step="1"  class="form-control" name="pi_priority" id="pi_priority">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center">
    <div class="col-12 col-sm-5">
        <label for="pi_title" class="h6"><?php echo esc_html__('Package name','advanced-free-flat-shipping-woocommerce'); ?> <span class="text-primary">*</span></label><?php pisol_help::tooltip(esc_html__('You can club product in multiple shipping package withing one order and user can select different shipping method for each such package','advanced-free-flat-shipping-woocommerce')); ?>
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
        <label for="pi_desc" class="h6"><?php echo esc_html__('Select Virtual category','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Product belonging to this virtual category will be grouped as one shipping package','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm">
        <?php
            $groups = get_posts(array(
                'post_type'=>'pi_efrs_custom_group',
                'numberposts'      => -1
            ));

            if(empty($groups)){
                echo '<div class="alert alert-warning">'.esc_html__('Create a Virtual category first','advanced-free-flat-shipping-woocommerce').' <a class="btn btn-primary btn-sm mr-3" href="'.esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' ) ).'"><span class="dashicons dashicons-plus"></span> Add virtual category</a></div>';
            }else{
        ?>
        <select name="pi_group" id="pi_group" class="form-control" required="required">
            <option value=""><?php echo esc_html__('Select Virtual Category','advanced-free-flat-shipping-woocommerce'); ?></option>
            <?php
            $groups = get_posts(array(
                'post_type'=>'pi_efrs_custom_group',
                'numberposts'      => -1
            ));
            foreach($groups as $group){
                $selected = $data['pi_group'] == $group->ID ? 'selected' : '';
                echo '<option '.esc_attr( $selected ).' value="'.esc_attr( $group->ID ).'">'.esc_html( get_the_title( $group->ID ) ).'</option>';
            }
        ?>
        </select>
        <?php
            }
        ?>
    </div>
</div>

<div class="row py-4 border-bottom align-items-center free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_subtotal_logic" class="h6"><?php echo esc_html__('When virtual category products total','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Create package when Virtual category product subtotal satisfies the selected logic','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm-3">
        <select name="pi_subtotal_logic" id="pi_subtotal_logic" class="form-control">
            <option <?php echo empty($data['pi_subtotal_logic']) ? 'selected' : ''; ?> value=""><?php echo esc_html__('Select Logic','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_subtotal_logic'] == 'greater' ? 'selected' : ''; ?> value="greater"><?php echo esc_html__('Greater than','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_subtotal_logic'] == 'greater_equal' ? 'selected' : ''; ?> value="greater_equal"><?php echo esc_html__('Greater than equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_subtotal_logic'] == 'less' ? 'selected' : ''; ?> value="less"><?php echo esc_html__('Less than','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_subtotal_logic'] == 'less_equal' ? 'selected' : ''; ?> value="less_equal"><?php echo esc_html__('Less than equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_subtotal_logic'] == 'equal' ? 'selected' : ''; ?> value="equal"><?php echo esc_html__('Equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
        </select>
    </div>
    <div class="col-12 col-sm-3">
        <input type="number" value="<?php echo esc_attr( $data['pi_subtotal'] ); ?>" step="0.001"  class="form-control" name="pi_subtotal" id="pi_subtotal" placeholder="<?php esc_attr_e('Total','advanced-free-flat-shipping-woocommerce'); ?>">
    </div>
</div>

<div class="row py-4 border-bottom align-items-center  free-version">
    <div class="col-12 col-sm-5">
        <label for="pi_quantity_logic" class="h6"><?php echo esc_html__('When virtual category products quantity','advanced-free-flat-shipping-woocommerce'); ?></label><?php pisol_help::tooltip(esc_html__('Create package when Virtual category product quantity satisfies the selected logic','advanced-free-flat-shipping-woocommerce')); ?>
    </div>
    <div class="col-12 col-sm-3">
        <select name="pi_quantity_logic" id="pi_quantity_logic" class="form-control">
            <option <?php echo empty($data['pi_quantity_logic']) ? 'selected' : ''; ?> value=""><?php echo esc_html__('Select Logic','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'greater' ? 'selected' : ''; ?> value="greater"><?php echo esc_html__('Greater than','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'greater_equal' ? 'selected' : ''; ?> value="greater_equal"><?php echo esc_html__('Greater than equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'less' ? 'selected' : ''; ?> value="less"><?php echo esc_html__('Less than','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'less_equal' ? 'selected' : ''; ?> value="less_equal"><?php echo esc_html__('Less than equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'equal' ? 'selected' : ''; ?> value="equal"><?php echo esc_html__('Equal to','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'multiple' ? 'selected' : ''; ?> value="multiple"><?php echo esc_html__('Multiple of','advanced-free-flat-shipping-woocommerce'); ?></option>
            <option <?php echo $data['pi_quantity_logic'] == 'not_multiple' ? 'selected' : ''; ?> value="not_multiple"><?php echo esc_html__('Not Multiple of','advanced-free-flat-shipping-woocommerce'); ?></option>
        </select>
    </div>
    <div class="col-12 col-sm-3">
        <input type="number" value="<?php echo esc_attr( $data['pi_quantity'] ); ?>" step="1"  class="form-control" name="pi_quantity" id="pi_quantity" placeholder="<?php esc_attr_e('Quantity','advanced-free-flat-shipping-woocommerce'); ?>">
    </div>
</div>

<?php wp_nonce_field( 'add_package', 'pisol_efrs_nonce'); ?>
<input type="hidden" name="post_type" value="pi_efrs_package">
<input type="hidden" name="post_id" value="<?php echo esc_attr( $data['post_id'] ); ?>">
<input type="hidden" name="action" value="pisol_efrs_save_package">
<input type="submit" value="<?php esc_attr_e('Save Method','advanced-free-flat-shipping-woocommerce'); ?>" name="submit" class="m-2 mt-5 btn btn-primary btn-lg" id="pi-efrs-new-shipping-method-form">
</form>
