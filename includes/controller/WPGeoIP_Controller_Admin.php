<?php

class WPGeoIP_Controller_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'wpgeoip_menu'));
        add_action("admin_enqueue_scripts", array($this, 'wpgeoip_admin_scripts'));
        add_action('wp_ajax_wpgeoip_ajax_action', array($this, 'wpgeoip_ajax_action_callback'));
        add_action('wp_ajax_wpgeoip_frontend_ajax_actions', array($this, 'wpgeoip_frontend_ajax_actions'));
        add_action('wp_ajax_nopriv_wpgeoip_frontend_ajax_actions', array($this, 'wpgeoip_frontend_ajax_actions'));
        add_action('admin_head', array($this, 'wpgeoip_add_admin_css'));
        if (isset($_POST["wpgeoip_save_ip_actions"]))
            $this->wpgeoip_save_ip_actions();
    }
    
    public function wpgeoip_add_admin_css() {
        $screen = get_current_screen();
        if($screen->id == 'toplevel_page_geo_ip_action' || $screen->id == 'geo-ip-actions_page_wpgeoip-posttype-action' || $screen->id == 'geo-ip-actions_page_wpgeoip-category-wise-action' || $screen->id == 'geo-ip-actions_page_wpgeoip-site-wide-action')
        {
        echo '<style>
                .widefat td, .widefat th{
                  overflow:initial !important;
                } 
              </style>';
        }
    }

    public function wpgeoip_admin_scripts() {
        wp_enqueue_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
        wp_enqueue_style('wpgeoip-admin-css', plugins_url('css/admin.css', WPGEOIP_FILE));
        wp_enqueue_style('chosen', plugins_url('css/chosen.min.css', WPGEOIP_FILE));

        wp_enqueue_script('jquery-ui-js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array('jquery'));
        wp_enqueue_script('chosen', plugins_url('js/chosen.jquery.min.js', WPGEOIP_FILE), array('jquery'), WPGEOIPACTION_VERSION);
        wp_enqueue_script('wpgeoip-custom', plugins_url('js/admin.js', WPGEOIP_FILE), array('jquery'), WPGEOIPACTION_VERSION);
    }

    public function wpgeoip_menu() {
        add_menu_page('Geo IP Actions', 'Geo IP Actions', 'manage_options', 'geo_ip_action', array($this, 'wpgeoip_action_post'));
        add_submenu_page('geo_ip_action', 'Post/page wise actions', 'Post wise actions', 'manage_options', 'geo_ip_action', array($this, 'wpgeoip_action_post'));
        add_submenu_page('geo_ip_action', 'Post-type actions', 'Post type actions', 'manage_options', 'wpgeoip-posttype-action', array($this, 'wpgeoip_action_posttype'));
        add_submenu_page('geo_ip_action', 'Categoy wise actions', 'Category actions', 'manage_options', 'wpgeoip-category-wise-action', array($this, 'wpgeoip_action_category'));
        add_submenu_page('geo_ip_action', 'Site wide actions', 'Site wide actions', 'manage_options', 'wpgeoip-site-wide-action', array($this, 'wpgeoip_action_site'));
        add_submenu_page('geo_ip_action', 'Upload IP Data', 'IP Data', 'manage_options', 'wpgeoip-ip-data', array($this, 'wpgeoip_ipdata'));
        add_submenu_page('geo_ip_action', 'Settings', 'Settings', 'manage_options', 'wpgeoip-settings', array($this, 'wpgeoip_setting'));
        add_submenu_page('geo_ip_action', 'Logs', 'Logs', 'manage_options', 'wpgeoip-logs', array($this, 'wpgeoip_logs'));
    }

    public function wpgeoip_action_site() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $data = $wpgeoip_model->get_actions(WPGEOIP_SITE);
        $countries = $wpgeoip_model->get_countries();
        $wpgeoip_view->display_actions($data, WPGEOIP_SITE, $countries, "- Site Wide");
    }

    public function wpgeoip_action_category() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $data = $wpgeoip_model->get_actions(WPGEOIP_CATEGORY);
        $countries = $wpgeoip_model->get_countries();
        $settings=$wpgeoip_model->get_wpgeoip_settings();
        $wpgeoip_view->display_actions($data, WPGEOIP_CATEGORY, $countries, "- Category Wide", $settings);
    }

    public function wpgeoip_action_posttype() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $data = $wpgeoip_model->get_actions(WPGEOIP_POSTTYPE);
        $countries = $wpgeoip_model->get_countries();
        $wpgeoip_view->display_actions($data, WPGEOIP_POSTTYPE, $countries, "- Post type wise");
    }

    public function wpgeoip_action_post() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $data = $wpgeoip_model->get_actions(WPGEOIP_POST);
        $countries = $wpgeoip_model->get_countries();
        $settings=$wpgeoip_model->get_wpgeoip_settings();
        $wpgeoip_view->display_actions($data, WPGEOIP_POST, $countries, "- Post/page wise",$settings);
    }

    public function wpgeoip_save_ip_actions() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_model->save_ip_actions($_POST);
    }

    public function wpgeoip_ipdata() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();
        $filename = WPGEOIPACTION_PLUGIN_DIR . "/uploadcsv/GeoIPCountryWhois.csv";
        $rows = $wpgeoip_model->get_ipdata_count();
        
        if (isset($_POST["wpgeoip_upload"])) {
            $this->upload_ipdata_file();
        } else if (isset($_POST["wpgeoip_upload_ipdata_local_infile"])) {
            $wpgeoip_model->import_ipdata_local_infile($filename);
        } else {
            $wpgeoip_view->display_ipdata($filename, $rows);
        }
    }

    public function upload_ipdata_file() {
        $wpgeoip_helper = new WPGeoIP_Helper();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $file = $wpgeoip_helper->file_upload();
        $data = $wpgeoip_helper->read_csv_header_row($file["file"]);
        $mapfields = array("start_ip_range", "end_ip_range", "start_no", "end_no", "country_code", "country_name", "region_name", "region_code", "city_name", "zipcode");
        $wpgeoip_view->map_fields($data, $mapfields, $file["file"]);
    }

    public function wpgeoip_ajax_action_callback() {
        $ot = $_POST["operation_type"];
        parse_str($_POST['post_data'], $data);
        switch ($ot) {
            case "wpgeoip_settings":
                $wpgeoip_model = new WPGeoIP_Model_Admin();
                $wpgeoip_model->save_settings($data);
                _e('Information updated successfully', 'wp-geoip-action');
                break;
            case "wpgeoip_import_ipdata":
                $this->wpgeoip_import_ipdata($data);
                break;
            default:
        }
        die();
    }

    public function wpgeoip_import_ipdata($data) {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_helper = new WPGeoIP_Helper();

        $dbColumn = $data['dbColumn'];

        for ($column_count = 0; $column_count < $dbColumn; $column_count++) {
            $col[] = $data['dbColumn' . $column_count];
        }
        $wpgeoip_model->import_ipdata($data['import_file'], $col);
    }

    public function wpgeoip_setting() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $wpgeoip_view = new WPGeoIP_View_Admin();

        $settings = $wpgeoip_model->get_wpgeoip_settings();
        $wpgeoip_view->display_settings($settings);
    }

    public function wpgeoip_logs() {
        $wpgeoip_view = new WPGeoIP_View_Admin();
        $wpgeoip_helper = new WPGeoIP_Helper();
        $logs = $wpgeoip_helper->read_log();
        $wpgeoip_view->display_logs($logs);
    }

    public function wpgeoip_frontend_ajax_actions() {
        $wpgeoip_model = new WPGeoIP_Model_Admin();
        $ot = $_POST["operation"];
        $cc = $_POST["cc"];
        $data = array();
        switch ($ot) {
            case "load_region": $data = $wpgeoip_model->get_user_regions($cc);
                if ($data) {
                        foreach ($data as $row) {
                            $response.="<option></option>";
                            if (!empty($row))
                                $response.="<option>$row->region_name</option>";
                        }
                        echo $response;
                    }
                break;
            case "load_city":
                $region = $_POST["region"];
                $data = $wpgeoip_model->get_user_city($cc, $region);
                if ($data) {
                        foreach ($data as $row) {
                            $response.="<option></option>";
                            if (!empty($row))
                                $response.="<option>$row->city_name</option>";
                        }
                        echo $response;
                    }
                break;
            case "submit_wpgeoip_popup":
                $region = $_POST["region"];
                $city = $_POST["city"];
                $data = $wpgeoip_model->get_redirection($cc, $region, $city);
                if ($data) {
                    $url = $data[0]->action_details;
                    $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;
                    echo $url;
                }
                die();
                break;
            default: break;
        }
        
        die();
    }

}