(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	function enableDisable() {
		jQuery(document).on('click', '.pi-affsw-status-change', function (e) {
			var id = jQuery(this).data('id');
			var status = jQuery(this).is(":checked") ? 1 : 0;
			jQuery("#pisol-efrs-shipping-method-list-view").addClass('blocktable');
			var nonce = jQuery(this).data('nonce');
			jQuery.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					id: id,
					status: status,
					nonce:nonce,
					action: 'pisol_affsw_change_status'
				}
			}).always(function (d) {
				jQuery("#pisol-efrs-shipping-method-list-view").removeClass('blocktable');
			})
		});
	}
	enableDisable();

	jQuery(function ($) {
		function ajaxSubmit() {
			$('#pisol-efrs-new-method').submit(function (e) {
				e.preventDefault();
				var form = $(this);
				blockUI()
				$.ajax({
					type: "POST",
					url: ajaxurl,
					dataType: 'json',
					data: form.serialize(), // serializes the form's elements.
					success: function (data) {


						if (data.error != undefined) {
							var html = ''

							jQuery.each(data.error, function (index, val) {
								html += '<p class="pi-effrs-notice error">' + val + '<span class="pi-close-notification dashicons dashicons-no-alt"></span></p>';
							});

							jQuery("#pisol-efrs-notices").html(html);

							$.alert({
								title: 'Error',
								content: html,
								type: 'red',
								columnClass: 'small'
							});
						}

						if (data.success != undefined) {
							var html = '<p class="pi-effrs-notice success">' + data.success + '<span class="pi-close-notification dashicons dashicons-no-alt"></span></p>';
							jQuery("#pisol-efrs-notices").html(html);

							$.alert({
								title: 'Success',
								content: html,
								type: 'green',
								columnClass: 'small'
							});
						}

						if (data.redirect != undefined) {
							window.location = data.redirect;
						}
					}
				}).always(function () {
					unblockUI();
				});
			});
		}
		ajaxSubmit();

		function blockUI() {
			jQuery("#pisol-efrs-new-method").addClass('pi-blocked')
		}

		function unblockUI() {
			jQuery("#pisol-efrs-new-method").removeClass('pi-blocked')
		}

		function hideNotification() {
			jQuery(document).on('click', '.pi-close-notification', function () {
				jQuery(this).parent().remove();
			})
		}
		hideNotification();

		jQuery("#pi_currency").selectWoo();
	});


})(jQuery);
