<div class="row mt-3">
    <div class="col-12 col-md-7">
        <div class="alert alert-info">
            <?php esc_html_e('Checkout this video to know more about Package manager','advanced-free-flat-shipping-woocommerce'); ?> <?php pisol_help::youtube('MVPPdukqLp0', esc_html__('Know more about the package manager','advanced-free-flat-shipping-woocommerce')); ?>
        </div>
    </div>
    <div class="col-12 col-md-5 py-3 text-right"><a class="btn btn-primary btn-sm mr-3" href="<?php echo esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_package' )); ?>"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add Package','advanced-free-flat-shipping-woocommerce'); ?></a>
    </div>
</div>
<?php

$custom_groups = get_posts(array(
    'post_type'=>'pi_efrs_package',
    'numberposts'      => -1,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'pi_priority', 
            'compare' => 'EXISTS'
        ),
        array(
            'key' => 'pi_priority', 
            'compare' => 'NOT EXISTS'
        )
    ),
    'orderby'=>'meta_value_num',
    'order'=>'DESC'
));

?>
<div id="pisol-efrs-shipping-method-list-view">
<table class="table text-center table-striped">
				<thead>
				<tr class="afrsm-head">
					<th class="text-left"><?php esc_html_e( 'Package name', 'advanced-free-flat-shipping-woocommerce'); ?><?php pisol_help::tooltip(esc_html__( 'you club product in one shipping package and ship them together and other product in different shipping package withing single order ', 'advanced-free-flat-shipping-woocommerce')); ?> </th>
					<th><?php esc_html_e( 'Description', 'advanced-free-flat-shipping-woocommerce'); ?></th>
                    <th><?php esc_html_e( 'Priority', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Status', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Actions', 'advanced-free-flat-shipping-woocommerce'); ?></th>
				</tr>
				</thead>
                <tbody >
                

<?php
if(count($custom_groups) > 0){
foreach($custom_groups as $method){
    $shipping_title  = get_the_title( $method->ID ) ? get_the_title( $method->ID ) : 'Package';
    $description = get_post_meta( $method->ID, 'pi_desc', true );
    $priority = get_post_meta( $method->ID, 'pi_priority', true );
    $status = get_post_meta( $method->ID, 'pi_status', true );
    echo '<tr id="pisol_tr_container_'.esc_attr( $method->ID ).'">';
    echo '<td class="pisol-aafsw-td-name text-left"><a href="'.esc_url( admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_package&action=edit&id='.$method->ID )).'">'.esc_html($shipping_title).' (ID: '.esc_html( $method->ID ).')</a></td>';
    
    echo '<td class="text-left">';
    echo esc_html($description);
    echo '</td>';
    echo '<td class="text-center">';
    echo esc_html($priority);
    echo '</td>';
    echo '<td class="text-center">';
    echo '<div class="custom-control custom-switch">
    <input type="checkbox" value="1" '.checked($status,'on', false).' class="custom-control-input pi-affsw-package-manager-status-change" name="pi_status" id="pi_status_'.esc_attr($method->ID).'" data-id="'.esc_attr($method->ID).'">
    <label class="custom-control-label" for="pi_status_'.esc_attr($method->ID).'"></label>
    </div>';
    echo '</td>';
    echo '<td>';
    echo '<a href="'.esc_url( admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_package&action=edit&id='.$method->ID ) ).'" class="btn btn-primary btn-sm m-2" title="Edit package"><span class="dashicons dashicons-edit-page"></span></a>';
    echo '<form method="POST" class="d-inline"><input type="hidden" name="method_id" value="'.esc_attr( $method->ID ).'"><input type="hidden" name="action" value="efrs_package_delete"><button class="btn btn-warning btn-sm m-2 pisol-confirm"  title="Delete package"><span class="dashicons dashicons-trash "></span> </button></form>';
    echo '</td>';
    echo '</tr>';
}
}else{
    echo '<tr>';
    echo '<td colspan="5" class="text-center">';
    echo esc_html__('There are no package created','advanced-free-flat-shipping-woocommerce' );
    echo '</td>';
    echo '</tr>';
}
?>
</tbody>
</table>
</div>