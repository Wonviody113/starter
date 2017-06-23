jQuery(document).ready(function ($) {

    $.wc_geoip_actions = {
        init: function () {

            jQuery(".wpgeoip_settings").submit(function (event) {
                $.wc_geoip_actions.save_settings(event, this);
            });

            jQuery(".wpgeoip_import_ipdata").submit(function (event) {
                $.wc_geoip_actions.import_data(event, this);
            });

            jQuery("#wpgeoip_insert_row").click(function (event) {
                event.preventDefault();
                $.wc_geoip_actions.add_row();
            });

            jQuery("#wpgeoip_remove_row").click(function (event) {
                event.preventDefault();
                $.wc_geoip_actions.remove_row();
            });

            jQuery(".wpgeoip-select").chosen({allow_single_deselect: true,
                width: '100%'});
        },
        add_row: function () {
            var rows = jQuery("tr.ip_rows").length;
            var table = $('table#wpgeoip_action_table');
            var clone = table.find('tr.ip_rows').eq(0).clone();
            clone[0].setAttribute('id', clone[0].getAttribute('id') + rows);
            clone.find('select.wpgeoip-select').css('display', 'block').val('').removeClass('chzn-done').next('.chosen-container').remove();
            clone.find(".wpgeoip-country").val('-1');
            clone.find(".wpgeoip-region").val('');
            clone.find(".wpgeoip-city").val('');
            clone.find(".wpgeoip-action-details").val('');
            clone.find(".wpgeoip-action-list").val('');
            table.find('tbody').append(clone);

            $('select.wpgeoip-select').each(function () {
                $(this).chosen(
                   {allow_single_deselect: true,
                    width: '100%'});
            });

            $('table#wpgeoip_action_table').find('tr.ip_rows').each(function (count) {                
                $(this).find(".wpgeoip-country").attr("name", "data[" + count + "][country]");
                $(this).find(".wpgeoip-region").attr("name", "data[" + count + "][region]");
                $(this).find(".wpgeoip-city").attr("name", "data[" + count + "][city]");
                $(this).find(".wpgeoip-ip-action").attr("name", "data[" + count + "][ip_action]");
                $(this).find(".wpgeoip-action-details").attr("name", "data[" + count + "][action_details]");
                $(this).find(".wpgeoip-action-list").attr("name", "data[" + count + "][action_list][]");
                
                count++;
            });

            clone.find('select.wpgeoip-select option').trigger('chosen:updated');

        },
        remove_row: function () {
            $('.wpgeoip-remove-row').each(function ( ) {
                if ($(this).prop('checked')==true){ 
                    if ($('tr.ip_rows').length > 1) {
                        $(this).closest('tr.ip_rows').remove();
                    }
                    else if ($('tr.ip_rows').length == 1){
                        $('tr.ip_rows').find(".wpgeoip-country").val('-1').trigger( 'chosen:updated' );
                        $('tr.ip_rows').find(".wpgeoip-region").val('');
                        $('tr.ip_rows').find(".wpgeoip-city").val('');
                        $('tr.ip_rows').find(".wpgeoip-action-details").val('');
                        $('tr.ip_rows').find(".wpgeoip-action-list").val('').trigger( 'chosen:updated' );                        
                    }                    
                }
            });
        },
        save_settings: function (event, obj) {
            //    Handles ajax submission of settings            
            event.preventDefault();
            jQuery(".wpgeoip_ajax_load").show();
            var data = {
                action: 'wpgeoip_ajax_action',
                operation_type: 'wpgeoip_settings',
                post_data: jQuery(obj).serialize()
            };
            jQuery.post(ajaxurl, data, function (response)
            {
                    jQuery(".wpgeoip_ajax_result").html(response);
                    jQuery(".wpgeoip_ajax_load").hide();
            });

        },
        import_data: function (event, obj) {
            //    Handles ajax import of ip data
            event.preventDefault();
            var data = {
                action: 'wpgeoip_ajax_action',
                operation_type: 'wpgeoip_import_ipdata',
                post_data: jQuery(obj).serialize()
            };
            jQuery.post(ajaxurl, data, function (response)
            {

            });
        }

    };
    $.wc_geoip_actions.init();
});