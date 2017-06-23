<?php
/*
  Plugin Name: WP-GeoIP-Actions
  Plugin URI: http://demo.digitaldreamstech.com/wp-geoip-actions
  Description: WPGeoIP Actions plugin provides facility to perform actions (redirection/hide content) on the basis of geolocation of the visitor from the IP address.
  Version: 1.0.1
  Author: Priesh Gupta
  Author URI: http://demo.digitaldreamstech.com/wpdemo
  Text Domain: wp-geoip-actions
  Domain Path: /languages
 */

@session_start();
define('WPGEOIPACTION_VERSION', '1.0.1');
define('WPGEOIPACTION_PATH', dirname(__FILE__));
define('WPGEOIP_FILE', __FILE__);
define('WPGEOIP_POST', 'ACTION_1_POST');
define('WPGEOIP_POSTTYPE', 'ACTION_2_POSTTYPE');
define('WPGEOIP_CATEGORY', 'ACTION_3_CATEGORY');
define('WPGEOIP_SITE', 'ACTION_4_SITE');

if (!defined('WPGEOIPACTION_PLUGIN_DIR'))
    define('WPGEOIPACTION_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));

if (!defined('WPGEOIPACTION_PLUGIN_URL'))
    define('WPGEOIPACTION_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

add_action('plugins_loaded', 'wpgeoip_plugin_loaded');

function wpgeoip_plugin_loaded() {
    load_plugin_textdomain('wp-geoip-action', false, WPGEOIPACTION_PLUGIN_DIR . '/languages');
}

spl_autoload_register('wpgeoip_classes');

include ('includes/lib/noerror.php');

function wpgeoip_classes($class) {
    if (file_exists(WPGEOIPACTION_PATH . '/includes/controller/' . $class . '.php'))
        include WPGEOIPACTION_PATH . '/includes/controller/' . $class . '.php';
    if (file_exists(WPGEOIPACTION_PATH . '/includes/model/' . $class . '.php'))
        include WPGEOIPACTION_PATH . '/includes/model/' . $class . '.php';
    if (file_exists(WPGEOIPACTION_PATH . '/includes/helper/' . $class . '.php'))
        include WPGEOIPACTION_PATH . '/includes/helper/' . $class . '.php';
    if (file_exists(WPGEOIPACTION_PATH . '/includes/view/' . $class . '.php'))
        include WPGEOIPACTION_PATH . '/includes/view/' . $class . '.php';
}

if (is_admin()) {
    new WPGeoIP_Controller_Admin();
} else {
    new WPGeoIP_Controller_Frontend();
}
register_activation_hook(__FILE__, 'wpgeoip_install_settings');

// function to create the DB / Options / Defaults					
function wpgeoip_install_settings() {

    global $wpdb;

    $wpgeoip_data = $wpdb->prefix . 'wpgeoip_data';
    $user_data = $wpdb->prefix . 'wpgeoip_user_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql_wpgeoip_data = "CREATE TABLE $wpgeoip_data (
            `wpgeoip_id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `start_ip_range` char(16) NOT NULL,
            `end_ip_range`  char(16) NOT NULL,
            `start_no` int(11) NOT NULL,
            `end_no` int(11) NOT NULL,
            `country_name` varchar(100) NOT NULL,
            `country_code` char(5) NOT NULL,
            `region_name` varchar(100) NOT NULL,
            `region_code` char(5) NOT NULL,
            `city_name` varchar(100) NOT NULL,
            `zipcode` varchar(20) NOT NULL,
            UNIQUE KEY id (wpgeoip_id)
        ) $charset_collate;";

    $sql_wpgeoip_user_data = "CREATE TABLE $user_data (
            `wpgeoip_user_data_id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `country_code` char(5) NOT NULL,
            `region_name` varchar(200) NOT NULL,
            `city_name` varchar(200) NOT NULL,
            `ip_action` varchar(100) NOT NULL,
            `action_type` varchar(100) NOT NULL,
            `action_details` mediumtext NOT NULL,
            `action_list` mediumtext NOT NULL,
            UNIQUE KEY id (wpgeoip_user_data_id)
        ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql_wpgeoip_data);
    dbDelta($sql_wpgeoip_user_data);

    $settings = array(
        'mass_redirect' => 'no_redirection',
        'mass_redirect_url' => '',
        'wpgeoip_api' => 'maxamind',
        'allow_no_action' => 'yes',
        'no_redirection_urls' => '',
        'category_rules_action' => 'category_only',
        'post_taxonomies' => array(),
        'post_types' => array()
        );

    add_option('wpgeoip_settings', $settings, '', 'no');

    $countries = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia, Plurinational State of',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, the Democratic Republic of the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => "Cote d'Ivoire",
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => "Korea, Democratic People's Republic of",
        'KR' => 'Korea, Republic of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => "Lao People's Democratic Republic",
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia, the former Yugoslav Republic of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States of',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin (French part)',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan, Province of China',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic of',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela, Bolivarian Republic of',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );
    
    add_option('wpgeoip_countries', $countries, '', 'no');
}