(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	function paymentMethod() {
		this.init = function () {
			this.dateChange();
		}

		this.dateChange = function () {
			var parent = this;

			/** this is needed to make it compatible with Different time for different pickup location plugin */
			var type = jQuery('input[name="pi_delivery_type"]:checked').val();
			if (type != 'delivery') return;
			/* end */

			jQuery(document).on('change', '#pi_delivery_date', function () {
				/**extra code added as it was leading to loop when used with different time for different pickup location addon plugin */
				var date = jQuery(this).val();
				var upd = localStorage.getItem('trigger_for_date');
				if (upd != date) {
					setTimeout(function () {
						jQuery('body').trigger('update_checkout', { update_shipping_method: true });
					}, 1000);

					localStorage.setItem('trigger_for_date', date);
				} else {
					setTimeout(function () {
						localStorage.removeItem('trigger_for_date');
					}, 1);
				}
			});
		}
	}

	jQuery(function () {
		var paymentMethod_Obj = new paymentMethod();
		paymentMethod_Obj.init();
	});

})(jQuery);
