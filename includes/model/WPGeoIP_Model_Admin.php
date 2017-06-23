<?php

class WPGeoIP_Model_Admin {

    public function __construct() {
        
    }

    public function get_countries() {
        $coutries = get_option("wpgeoip_countries");
        return $coutries;
    }

    public function get_wpgeoip_settings() {
        $settings = get_option("wpgeoip_settings");
        return $settings;
    }

    public function save_settings($data) {
        return update_option("wpgeoip_settings", $data);
    }

    public function get_ipdata() {
        global $wpdb;
        $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';
        $ipdata = $wpdb->get_results("SELECT * FROM $wpgeoip_data ORDER BY `country_name`");
        return $ipdata;
    }

    public function import_ipdata($fileName, $col) {
        global $wpdb;
        $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';
        $data = array();
        set_time_limit(0);
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            $rowIndex = 0;
            while (($lineArray = fgetcsv($handle, 0, ",")) !== FALSE) {
                for ($col_count = 0; $col_count < count($lineArray); $col_count++) {
                    $data[trim($col[$col_count])] = $lineArray[$col_count];
                }

                $wpdb->insert($wpgeoip_data, $data);
                
                $rowIndex++;
            }
            fclose($handle);
        }
    }
    public function import_ipdata_local_infile($fileName) {
        global $wpdb;
        $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';

        $sql = "LOAD DATA LOCAL INFILE   '$fileName' 
                  INTO TABLE " . $wpgeoip_data . "
                  FIELDS
                  TERMINATED BY \",\"
                  ENCLOSED BY \"\\\"\"
                  LINES
                  TERMINATED BY \"\\n\"
                  (
                    `start_ip_range`, `end_ip_range`, `start_no`, `end_no`, `country_code`, `country_name`, `region_name`,`city_name`,`zipcode`
                  )";
        
        $wpdb->query( $sql );
    }
    
    public function get_ipdata_count() {
        global $wpdb;
        $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';
        return $wpdb->get_results("SELECT COUNT(*) AS total_rows FROM ".$wpgeoip_data);
    }

    public function get_actions($action_type) {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpgeoip_user_data WHERE `action_type`=%s ORDER BY `wpgeoip_user_data_id`", $action_type));
        return $data;
    }

    public function save_ip_actions($rows) {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $wpdb->delete($wpgeoip_user_data, array('action_type' => sanitize_text_field($_POST["action_type"])));
        foreach ($rows["data"] as $row) {
            $data["country_code"] = sanitize_text_field($row["country"]);
            $data["region_name"] = sanitize_text_field($row["region"]);
            $data["city_name"] = sanitize_text_field($row["city"]);
            $data["ip_action"] = sanitize_text_field($row["ip_action"]);
            $data["action_details"] = sanitize_text_field($row["action_details"]);
            $data["action_type"] = sanitize_text_field($_POST["action_type"]);
            if (isset($row["action_list"])) {
                $data["action_list"] = implode(",", $row["action_list"]);
            }
           
            //validations
            if ( (!empty($data["country_code"]) && $data["country_code"] != -1) || (!empty($data["region_name"])) || (!empty($data["city_name"]))){
                $wpdb->insert($wpgeoip_user_data, $data);
            }
            
        }
    }

    public function get_user_regions($cc) {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(region_name) FROM $wpgeoip_user_data WHERE country_code=%s AND region_name!='' ORDER BY region_name", $cc));
        return $data;
    }

    public function get_user_city($cc, $region) {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(city_name) FROM $wpgeoip_user_data WHERE country_code=%s AND region_name=%s AND city_name!='' ORDER BY city_name", $cc, $region));
        return $data;
    }
    
    public function get_redirection($cc, $region, $city) {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results($wpdb->prepare("SELECT action_details FROM $wpgeoip_user_data WHERE country_code=%s AND region_name=%s AND city_name=%s  ORDER BY `action_type`", $cc, $region, $city));
        return $data;
    }

}
