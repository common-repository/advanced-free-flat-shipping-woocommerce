<div class="row">
    <div class="col-12 py-3 text-right"><a class="btn btn-primary btn-sm mr-3" href="<?php echo esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group' ) ); ?>"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add virtual category','advanced-free-flat-shipping-woocommerce'); ?></a>
    </div>
</div>
<?php

$custom_groups = get_posts(array(
    'post_type'=>'pi_efrs_custom_group',
    'numberposts'      => -1
));

?>
<div id="pisol-efrs-shipping-method-list-view">
<table class="table text-center table-striped">
				<thead>
				<tr class="afrsm-head">
					<th class="text-left"><?php esc_html_e( 'Virtual Category', 'advanced-free-flat-shipping-woocommerce'); ?><?php pisol_help::tooltip(esc_html__('You can club multiple categories and product and exclude certain product to form a Virtual category, and you can use this Virtual category in shipping method rules','advanced-free-flat-shipping-woocommerce')); ?> </th>
					<th><?php esc_html_e( 'Description', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Actions', 'advanced-free-flat-shipping-woocommerce'); ?></th>
				</tr>
				</thead>
                <tbody >
                

<?php
if(count($custom_groups) > 0){
foreach($custom_groups as $method){
    $shipping_title  = get_the_title( $method->ID ) ? get_the_title( $method->ID ) : 'Shipping Method';
    $description = get_post_meta( $method->ID, 'pi_desc', true );
    echo '<tr id="pisol_tr_container_'.esc_attr($method->ID).'">';
    echo '<td class="pisol-aafsw-td-name text-left"><a href="'.esc_url( admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group&action=edit&id='.$method->ID ) ).'">'.esc_html($shipping_title).'</a></td>';
    
    echo '<td class="text-left">';
    echo esc_html($description);
    echo '</td>';
    echo '<td>';
    echo '<a href="'.esc_url( admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_custom_group&action=edit&id='.$method->ID ) ).'" class="btn btn-primary btn-sm m-2" title="Edit virtual category"><span class="dashicons dashicons-edit-page"></span></a>';
    echo '<form method="POST" class="d-inline"><input type="hidden" name="method_id" value="'.esc_attr( $method->ID ).'"><input type="hidden" name="action" value="efrs_custom_group_delete"><input type="hidden" name="nonce" value="'.esc_attr(wp_create_nonce('pisol-efrs-action-delete')).'"><button class="btn btn-warning btn-sm m-2 pisol-confirm"  title="Delete virtual category"><span class="dashicons dashicons-trash "></span> </button></form>';
    echo '</td>';
    echo '</tr>';
}
}else{
    echo '<tr>';
    echo '<td colspan="3" class="text-center">';
    echo esc_html__('There are no custom group added yet, add them','advanced-free-flat-shipping-woocommerce' );
    echo '</td>';
    echo '</tr>';
}
?>
</tbody>
</table>
</div>