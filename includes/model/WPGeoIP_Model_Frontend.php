<?php

class WPGeoIP_Model_Frontend {

    public function get_wpgeoip_settings() {
        $settings = get_option("wpgeoip_settings");
        return $settings;
    }

    public function get_ip_data($ip) {
        global $wpdb;
        $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpgeoip_data WHERE %d BETWEEN start_no AND end_no", $ip));
        return $data;
    }

    public function get_actions() {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results("SELECT * FROM $wpgeoip_user_data ORDER BY action_type");
        return $data;
    }

    public function get_user_countries() {
        global $wpdb;
        $wpgeoip_user_data = $wpdb->prefix . 'wpgeoip_user_data';
        $data = $wpdb->get_results("SELECT DISTINCT(country_code) FROM $wpgeoip_user_data ORDER BY country_code");
        return $data;
    }

    public function get_all_countries() {
        $coutries = get_option("wpgeoip_countries");
        return $coutries;
    }

}
