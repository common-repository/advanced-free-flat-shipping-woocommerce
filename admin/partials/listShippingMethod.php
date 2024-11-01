<div class="row">
    <div class="col-12 py-3 text-right"><a class="btn btn-primary btn-sm mr-3" href="<?php echo esc_url( admin_url( 'admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_shipping' ) ); ?>"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Add shipping method','advanced-free-flat-shipping-woocommerce'); ?></a>
    </div>
</div>
<?php

$shipping_methods = get_posts(array(
    'post_type'=>'pi_shipping_method',
    'numberposts'      => -1
));

?>
<div id="pisol-efrs-shipping-method-list-view">
<table class="table text-center table-striped" >
				<thead>
				<tr class="afrsm-head">
					<th><?php esc_html_e( 'Shipping Method', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Amount', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Enabled', 'advanced-free-flat-shipping-woocommerce'); ?></th>
					<th><?php esc_html_e( 'Actions', 'advanced-free-flat-shipping-woocommerce'); ?></th>
				</tr>
				</thead>
                <tbody >
                

<?php
if(count($shipping_methods) > 0){
foreach($shipping_methods as $method){
    $shipping_cost   = get_post_meta( $method->ID, 'pi_cost', true );
    $shipping_title  = get_the_title( $method->ID ) ? get_the_title( $method->ID ) : 'Shipping Method';
    $shipping_status = get_post_meta( $method->ID, 'pi_status', true );
    echo '<tr id="pisol_tr_container_'.esc_attr($method->ID).'">';
    echo '<td class="pisol-aafsw-td-name"><a href="'.esc_url( admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_shipping&action=edit&id='.$method->ID ) ).'">'.esc_html( $shipping_title ).'</a></td>';
    echo '<td>';
    
								if ( $shipping_cost > 0 ) {
									echo esc_html( get_woocommerce_currency_symbol()) . '&nbsp;' . esc_html( $shipping_cost );
								} else {
									echo esc_html( $shipping_cost );
								}
							
    echo '</td>';
    echo '<td>';
    echo '<div class="custom-control custom-switch">
    <input type="checkbox" value="1" '.checked($shipping_status,'on', false).' class="custom-control-input pi-affsw-status-change" name="pi_status" id="pi_status_'.esc_attr( $method->ID ).'" data-id="'.esc_attr($method->ID).'" data-nonce="'.esc_attr(wp_create_nonce('pisol-efrs-action-status')).'">
    <label class="custom-control-label" for="pi_status_'.esc_attr( $method->ID ).'"></label>
    </div>';
    echo '</td>';
    echo '<td>';
    echo '<a href="'.esc_url(admin_url( '/admin.php?page=pisol-efrs-notification&tab=pi_efrs_add_shipping&action=edit&id='.$method->ID )).'" class="btn btn-primary btn-sm m-2" title="'.esc_attr__('Edit shipping method','advanced-free-flat-shipping-woocommerce').'"><span class="dashicons dashicons-admin-customizer"></span></a>';
    echo '<a href="'.esc_url(admin_url( '/admin.php?page=pisol-efrs-notification&action=efrs_delete&id='.$method->ID.'&nonce='.wp_create_nonce('pisol-efrs-action-delete') )).'" class="btn btn-warning btn-sm m-2"  title="'.esc_attr__('Delete shipping method','advanced-free-flat-shipping-woocommerce').'"><span class="dashicons dashicons-trash "></span></a>';
    echo '</td>';
    echo '</tr>';
}
}else{
    echo '<tr>';
    echo '<td colspan="4" class="text-center">';
    echo esc_html__('There are no shipping methods added yet, add them first','advanced-free-flat-shipping-woocommerce' );
    echo '</td>';
    echo '</tr>';
}
?>
</tbody>
</table>
</div>