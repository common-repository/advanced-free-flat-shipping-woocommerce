(function ($) {
    'use strict';

    jQuery(function ($) {

        jQuery(".pi_efrs_custom_group_search_category").selectWoo({
            minimumInputLength: 3,
            closeOnSelect: false,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                type: "GET",
                delay: 1000,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: "pi_efrs_custom_group_category"
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            }
        });

        jQuery(".pi_efrs_custom_group_search_product").selectWoo({
            minimumInputLength: 3,
            closeOnSelect: false,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                type: "GET",
                delay: 1000,
                data: function (params) {
                    return {
                        keyword: params.term,
                        action: "pi_efrs_custom_group_product"
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            }
        });

        jQuery('.pi-efrs-custom-group-simple-select').selectWoo();

        jQuery(document).on('change', 'input[name="pi_match_type"]', function () {
            var val = jQuery('input[name="pi_match_type"]:checked').val();
            if (val == 'all') {
                jQuery("#pi-exclude-product-group").css('order', 1);
                jQuery("#pi-include-product-group").css('order', 2);
            } else {
                jQuery("#pi-exclude-product-group").css('order', 2);
                jQuery("#pi-include-product-group").css('order', 1);
            }
        });

        jQuery('input[name="pi_match_type"]').trigger('change');

    });


})(jQuery);