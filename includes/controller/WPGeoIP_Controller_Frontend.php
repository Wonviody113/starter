<?php

class WPGeoIP_Controller_Frontend {

    private $ip;
    private $country;
    private $settings_category;

    public function __construct() {
        add_action('wp_head', array($this, 'wpgeoip_ip_actions'));
        add_action('wp_enqueue_scripts', array($this, 'wpgeoip_enqueue_scripts'));
    }

    public function wpgeoip_enqueue_scripts() {
        wp_enqueue_style('wpgeoip-frontend-css', plugins_url('css/frontend.css', WPGEOIP_FILE));
        wp_enqueue_script('jquery');
        wp_enqueue_script('wpgeoip-popup', plugins_url('js/jquery.popupoverlay.js', WPGEOIP_FILE), array("jquery"));
        wp_register_script('wpgeoip-frontend', plugins_url('js/frontend.js', WPGEOIP_FILE), array("wpgeoip-popup"));
        $wpgeoip_global = array('ajaxurl' => admin_url('admin-ajax.php'));
        wp_localize_script('wpgeoip-frontend', 'wpgeoip_global', $wpgeoip_global);
        wp_enqueue_script('wpgeoip-frontend');
    }

    public function wpgeoip_ip_actions() {
        if (!isset($this->ip))
            $this->ip = $_SERVER['REMOTE_ADDR'];
        $wpgeoip_helper = new WPGeoIP_Helper();
        $wpgeoip_model = new WPGeoIP_Model_Frontend();
        $setting = $wpgeoip_model->get_wpgeoip_settings();

        //check for no action settings
        if (isset($setting["allow_no_action"]) && $setting["allow_no_action"] === "yes") {
            if (isset($_GET["no_action"]) && $_GET["no_action"] == "yes") {
                $_SESSION["no_action"] = true;
            }
        }
        if (isset($_SESSION["no_action"]) && $_SESSION["no_action"] === true) {
            $wpgeoip_helper->write_log("Performing 'no action' as user is associated no_action filter : " . date("Y-m-d h:i:s A"));
            return;
        }
        if(isset($setting["category_rules_action"]))
            $this->settings_category =  $setting["category_rules_action"];
        else
            $this->settings_category =  "category_only";
        
        if (!empty($setting["no_redirection_urls"])) {
            $urls = explode(",", $setting["no_redirection_urls"]);
            $current_url = $wpgeoip_helper->get_currentURL();
            foreach ($urls as $url) {
                if ($url === $current_url) {
                    $wpgeoip_helper->write_log("Performing 'no action' as this url($url) is spefificed with no redirection : " . date("Y-m-d h:i:s A"));
                    return;
                }
            }
        }
        $actions = $wpgeoip_model->get_actions();
        $data = array();
        switch ($setting["wpgeoip_api"]) {
            case "ip-api": $data = $this->ip_api_data();
                break;
            case "geoplugin": $data = $this->geoplugin_data();
                break;
            case "maxamind": $data = $this->maxamind_data();
                break;
            default: break;
        }
        if (count($data) == 0) {
            $wpgeoip_helper->write_log("No matching IP address entry found for IP Address " . $this->ip . " :" . date("Y-m-d h:i:s A"));
        }
        
        $action_performed = false;
        if(isset($data["country_code"]))
         $this->country = $data["country_code"];

        foreach ($actions as $row) {

            if (!empty($row->country_code) && !empty($row->region_name) && !empty($row->city_name)) {
                if (isset($data["country_code"]) && isset($data["region_name"]) && isset($data["city_name"]) && $this->match_country($data["country_code"], $row->country_code) && $this->match_region($data["region_name"], $row->region_name) && $this->match_city($data["city_name"], $row->city_name)) 
                {
                    if ($this->wpgeoip_perform_action($row->ip_action, $row->action_type, $row->action_details, $row->action_list))
                        $action_performed = true;
                }
            } 
            else if (!empty($row->country_code) && (!empty($row->region_name) || !empty($row->city_name))) {
                if (isset($data["country_code"]) && isset($data["region_name"]) && $this->match_country($data["country_code"], $row->country_code) && $this->match_region($data["region_name"], $row->region_name)) 
                {
                    if ($this->wpgeoip_perform_action($row->ip_action, $row->action_type, $row->action_details, $row->action_list))
                        $action_performed = true;
                }
                else if (isset($data["country_code"]) && isset($data["city_name"]) && $this->match_country($data["country_code"], $row->country_code) && $this->match_city($data["city_name"], $row->city_name)) 
                {
                    if ($this->wpgeoip_perform_action($row->ip_action, $row->action_type, $row->action_details, $row->action_list))
                        $action_performed = true;
                }
            }
            else if (!empty($row->country_code)){
                 if (isset($data["country_code"]) && $this->match_country($data["country_code"], $row->country_code)) 
                {
                    if ($this->wpgeoip_perform_action($row->ip_action, $row->action_type, $row->action_details, $row->action_list))
                        $action_performed = true;
                }                
            }
            
            if($action_performed) break;                
                
        }
        //if nothing matches, check for mass redirection/popup/no redirection as per setting
        if (!$action_performed) {
            switch ($setting["mass_redirect"]) {
                case "redirect_to_url": $this->wpgeoip_action_redirect($setting["mass_redirect_url"]);
                    break;
                case "show_popup": $this->wpgeoip_popup();
                    break;
                case "no_redirection": break;
            }
        }
    }

    public function wpgeoip_perform_action($ip_action, $action_type, $action_details, $action_list) {
        global $post;
        $post_id = $post->ID;
        $postytpe = get_post_type($post_id);
        if(isset($this->settings_category) && trim($this->settings_category)==="category_only"){
              $category = get_category(get_query_var('cat'));
              $cat_id = $category->cat_ID;
        }
        else{
            $cat_id = wp_get_post_categories($post_id);  
        }
        
        switch ($action_type) {
            case WPGEOIP_POST: $matched = $this->match_posts($post_id, $action_list);
                break;
            case WPGEOIP_POSTTYPE: $matched = $this->match_postytpe($postytpe, $action_list);
                break;
            case WPGEOIP_CATEGORY: $matched = $this->match_categories($cat_id, $action_list);
                break;
            case WPGEOIP_SITE: $matched = true;
        }

        //apply action
        if ($matched) {
            $wpgeoip_helper = new WPGeoIP_Helper();
            $wpgeoip_helper->write_log("Performing '" . $ip_action . "' for country = " . $this->country . " (IP Address:" . $this->ip . ") @" . date("Y-m-d h:i:s A"));
            switch ($ip_action) {
                case "redirect": $this->wpgeoip_action_redirect($action_details);
                    break;
                case "hide": $this->wpgeoip_action_hide($action_details);
                    break;
                 case "show": $this->wpgeoip_action_show($action_details);
                    break;
                case "no-redirection": break;
            }
            return true;
        }

        return false;
    }

    public function wpgeoip_action_redirect($url) {
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;
        $wpgeoip_model = new WPGeoIP_Model_Frontend();
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '"/>';
        return true;
    }

    public function wpgeoip_action_hide($action_details) {
        echo '<style type=text/css>';
        $data = explode(",", $action_details);
        foreach ($data as $row) {
            echo $row . '{display:none}';
        }
        echo "</style>";
    }
    
    public function wpgeoip_action_show($action_details) {
        echo '<style type=text/css>';
        $data = explode(",", $action_details);
        foreach ($data as $row) {
            echo $row . '{display:block}';
        }
        echo "</style>";
    }

    public function wpgeoip_popup() {
        $wpgeoip_model = new WPGeoIP_Model_Frontend();
        $wpgeoip_view = new WPGeoIP_View_Frontend();
        $user_countries = $wpgeoip_model->get_user_countries();
        $all_countries = $wpgeoip_model->get_all_countries();
        $wpgeoip_view->display_popup($user_countries, $all_countries);
    }

    public function match_country($geoip_country, $action_country) {
        if (isset($action_country) && !empty($action_country) && strtolower($geoip_country) === strtolower($action_country)) {
            return true;
        }
        return false;
    }

    public function match_region($geoip_region, $action_region) {
        if (isset($action_region) && !empty($action_region) && strtolower($geoip_region) === strtolower($action_region)) {
            return true;
        }
        return false;
    }

    public function match_city($geoip_city, $action_city) {
        if (isset($action_city) && !empty($action_city) && strtolower($geoip_city) === strtolower($action_city)) {
            return true;
        }
        return false;
    }

    public function match_categories($current_category, $action_category) {

        if (isset($action_category) && !empty($action_category) && isset($current_category) && !empty($current_category)) {
            $categories = explode(",", $action_category);
            
            if(is_array($current_category)){
                foreach($current_category as $cat)
                {
                  if(in_array($cat,$categories))
                      return true;
                }
            }
            else{
                 if (in_array($current_category, $categories))
                     return true;
            }
        }
        return false;
    }

    public function match_posts($current_post, $action_post) {
        if (isset($action_post) && !empty($action_post) && isset($current_post) && !empty($current_post)) {
            $posts = explode(",", $action_post);
            if (in_array($current_post, $posts))
                return true;
        }
        return false;
    }

    public function match_postytpe($current_posttype, $action_posttype) {

        if (isset($action_posttype) && !empty($action_posttype) && isset($current_posttype) && !empty($current_posttype)) {
            $postytpe = explode(",", $action_posttype);
            if (in_array($current_posttype, $postytpe))
                return true;
        }
        return false;
    }

    public function ip_api_data() {
        $url = 'http://ip-api.com/php/' . $this->ip;

        $response = wp_remote_get($url);

        $response_body = @maybe_unserialize(wp_remote_retrieve_body($response));

        $data = array();
        if (isset($response_body['status']) && $response_body['status'] === 'success') {
            $data["country_code"] = $response_body["countryCode"];
            $data["region_name"] = $response_body["regionName"];
            $data["city_name"] = $response_body["city"];
            $data["zip"] = $response_body["zip"];
            return $data;
        }

        return;
    }

    public function geoplugin_data() {
        $url = 'http://www.geoplugin.net/php.gp?ip=' . $this->ip;

        $response = wp_remote_get($url);

        $response_body = @maybe_unserialize(wp_remote_retrieve_body($response));

        if (isset($response_body['geoplugin_status'])) {
            $data["country_code"] = $response_body["geoplugin_countryCode"];
            $data["region_name"] = $response_body["geoplugin_region"];
            $data["city_name"] = $response_body["geoplugin_city"];
            $data["zip"] = $response_body["geoplugin_areaCode"];
            return $data;
        }

        return;
    }

    public function maxamind_data() {
        $ip_range = sprintf("%u", ip2long($this->ip));
        $wpgeoip_model = new WPGeoIP_Model_Frontend();
        $response = $wpgeoip_model->get_ip_data($ip_range);
        $data = array();
        if ($response) {
            foreach ($response as $response_body) {
                $data["country_code"] = $response_body->country_code;
                $data["region_name"] = $response_body->region_name;
                $data["city_name"] = $response_body->city_name;
                $data["zip"] = $response_body->zipcode;
            }
        }
        return $data;
    }

}