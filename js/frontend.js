jQuery(document).ready(function () {

    jQuery('#wpgeoip_popup').popup({autoopen: true});

    jQuery("#wpgeoip_country").on("change", function () {
        var data = {
            action: 'wpgeoip_frontend_ajax_actions',
            operation: 'load_region',
            cc: jQuery(this).val()
        };
        jQuery.post(wpgeoip_global.ajaxurl, data, function (response)
        {
            if (response.length > 1) {
                jQuery("#wpgeoip_region").html(response);
                jQuery(".wpgeoip_region").show();
            }
            else{
                jQuery(".wpgeoip_region").hide();
            }
                
        });
    });

    jQuery("#wpgeoip_region").on("change", function () {
        var data = {
            action: 'wpgeoip_frontend_ajax_actions',
            operation: 'load_city',
            cc: jQuery("#wpgeoip_country").val(),
            region: jQuery(this).val()
        };
        jQuery.post(wpgeoip_global.ajaxurl, data, function (response)
        {
            if (response.length > 1) {
                jQuery("#wpgeoip_city").html(response);
                jQuery(".wpgeoip_city").show();
            }
            else{
                jQuery(".wpgeoip_city").hide();
            }
        });
    });

    jQuery("#submit_wpgeoip_popup_option").on("click", function () {
        var data = {
            action: 'wpgeoip_frontend_ajax_actions',
            operation: 'submit_wpgeoip_popup',
            cc: jQuery("#wpgeoip_country").val(),
            region: jQuery("#wpgeoip_region").val(),
            city: jQuery("#wpgeoip_city").val(),
        };
        jQuery.post(wpgeoip_global.ajaxurl, data, function (response)
        {
            //redirect to chosen website with no redirect parameter
            if (response.length > 1) {
                window.location.href = response;
            }
        });
    });


});