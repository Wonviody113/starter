<?php

class WPGeoIP_View_Frontend {

    public function display_popup($user_countries, $all_countries) {
        ?>
        <div id="wpgeoip_popup">
            <button class="wpgeoip_popup_close">Close</button>
            <ul>
                <li>
                    <?php _e('Select Country', 'wp-geoip-action'); ?>
                </li>
                <li>
                    <select id="wpgeoip_country" name="wpgeoip_country">
                        <option value="-1"></option>
                        <?php
                        if (!empty($user_countries)) {
                            foreach ($user_countries as $country) {
                                ?>
                                <option value="<?php echo $country->country_code; ?>"><?php _e($all_countries[$country->country_code], 'wp-geoip-action'); ?></option>
                                <?php
                            }
                        }
                        ?>

                    </select>
                </li>
                <li class="wpgeoip_region">
                    <?php _e('Select Region/State', 'wp-geoip-action'); ?>
                </li>
                <li class="wpgeoip_region">
                    <select id="wpgeoip_region" name="wpgeoip_region">                        
                    </select>
                </li>
                <li class="wpgeoip_city">
                    <?php _e('Select City', 'wp-geoip-action'); ?>
                </li>
                <li class="wpgeoip_city">
                    <select id="wpgeoip_city" name="wpgeoip_city">                        
                    </select>
                </li>
                <li class="wpgeoip_popup_btn">
                    <button id="submit_wpgeoip_popup_option"> <?php _e('Submit', 'wp-geoip-action'); ?></button>
                </li>    
        </div>    

        <?php
    }

}
