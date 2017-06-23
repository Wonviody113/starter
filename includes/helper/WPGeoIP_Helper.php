<?php

class WPGeoIP_Helper {

    public function __construct() {
        
    }

    function file_upload() {
        if (!function_exists('wp_handle_upload')) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $uploadedfile = $_FILES['file'];

        $upload_overrides = array('test_form' => false, 'mimes' => array('csv' => 'text/csv'));

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            return $movefile;
        } else {
            echo $movefile['error'];
        }
    }

    function read_csv_header_row($fileName) {

        $csvArray = array();

        if (($handle = fopen($fileName, "r")) !== FALSE) {
            $arrayIndex1 = 0;
            while (($lineArray = fgetcsv($handle, 0, ",")) !== FALSE) {
                for ($arrayIndex2 = 0; $arrayIndex2 < count($lineArray); $arrayIndex2++) {
                    $csvArray[$arrayIndex1][$arrayIndex2] = $lineArray[$arrayIndex2];
                }
                $arrayIndex1++;
                if ($arrayIndex1 > 1)
                    break;
            }
            fclose($handle);
        }
        return $csvArray;
    }

    function read_csv($fileName) {
        $csvArray = array();

        if (($handle = fopen($fileName, "r")) !== FALSE) {
            $arrayIndex1 = 0;
            while (($lineArray = fgetcsv($handle, 0, ",")) !== FALSE) {
                for ($arrayIndex2 = 0; $arrayIndex2 < count($lineArray); $arrayIndex2++) {
                    $csvArray[$arrayIndex1][$arrayIndex2] = $lineArray[$arrayIndex2];
                }
                $arrayIndex1++;
                if ($arrayIndex1 > 100000)
                    break;
            }
            fclose($handle);
        }
        return $csvArray;
    }

    public function write_log($message) {
        $file = WPGEOIPACTION_PLUGIN_DIR . "/log.txt";
        $current = file_get_contents($file);
        $current .= $message . "\n";
        file_put_contents($file, $current);
    }

    public function read_log() {
        $file = WPGEOIPACTION_PLUGIN_DIR . "/log.txt";
        $filearray = file($file);
        $logs = array_slice($filearray, -10);
        $logs = array_reverse($logs);
        return $logs;
    }

    public function get_currentURL() {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}
