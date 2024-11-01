<div class="row py-4 bg-secondary">
    <div class="col-6"><label for="pi_enable_additional_charges" class="text-light mb-0">Additional Charges</label> <?php pisol_help::tooltip('You can adjust the final shipping charge of this shipping method using this extra conditions'); ?><?php pisol_help::youtube('oGE6daMXrOk','Know more about the Additional Charges'); ?> </div>
    <div class="col-6">
        <div class="custom-control custom-switch">
            <input type="checkbox" value="1" <?php echo esc_attr( $data['pi_enable_additional_charges'] ); ?> class="custom-control-input" name="pi_enable_additional_charges" id="pi_enable_additional_charges">
            <label class="custom-control-label" for="pi_enable_additional_charges"></label>
        </div>
    </div>
</div>
<div id="additional-charges-container"  style="margin:0 -15px;">
    <div class="row no-gutters">
        <div class="col-2">
            <?php do_action('pi_efrs_additional_charges_tab', $data); ?>
        </div>
        <div class="col-10">
            <?php do_action('pi_efrs_additional_charges_tab_content', $data); ?>
        </div>
    </div>
</div>