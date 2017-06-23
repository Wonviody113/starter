<?php

class WPGeoIP_View_Admin {

    public function __construct() {
        
    }

    public function display_settings($data) {
        ?>
        <form method="post" class="wpgeoip_settings">
            <div class="postbox">
                <h3 class="handle"><?php _e('General settings', 'wp-geoip-action'); ?></h3>
                <div class="wpgeoip_ajax_result"></div>
                <div class="wpgeoip_ajax_load">  
                    <img src="<?php echo WPGEOIPACTION_PLUGIN_URL . '/images/ajax-loader.gif' ?>" class="ajax-loader">
                </div>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <?php _e('Get IP Address Data', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('IP Address Data', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <input type="radio" name="wpgeoip_api" value="maxamind" <?php $this->checked($data["wpgeoip_api"], "maxamind"); ?>> <?php _e('Maxamind', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="wpgeoip_api" value="ip-api" <?php $this->checked($data["wpgeoip_api"], "ip-api"); ?>> <?php _e('IP-API', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="wpgeoip_api" value="geoplugin" <?php $this->checked($data["wpgeoip_api"], "geoplugin"); ?>> <?php _e('Geo Plugin', 'wp-geoip-action'); ?>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Select the data to be used from different IP based data provider"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e('Mass redirection', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Mass redirection', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <input type="radio" name="mass_redirect" value="no_redirection" <?php $this->checked($data["mass_redirect"], "no_redirection"); ?>> <?php _e('No Action', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="mass_redirect" value="show_popup" <?php $this->checked($data["mass_redirect"], "show_popup"); ?>> <?php _e('Show Popup', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="mass_redirect" value="redirect_to_url" <?php $this->checked($data["mass_redirect"], "redirect_to_url"); ?>> <?php _e('Redirect URL', 'wp-geoip-action'); ?>                                            
                                        </label>
                                        <label>
                                            <input type="text" name="mass_redirect_url" value="<?php echo $data["mass_redirect_url"]; ?>" placeholder="Specify redirection url" />
                                        </label>
                                        <p class="description">
                                            <?php _e('"What to do if no redirection rule found?"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e('Allow ?no_action', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Allow ?no_action', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <input type="radio" name="allow_no_action" value="yes" <?php $this->checked($data["allow_no_action"], "yes"); ?>> <?php _e('Yes', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="allow_no_action" value="no" <?php $this->checked($data["allow_no_action"], "no"); ?>> <?php _e('No', 'wp-geoip-action'); ?>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Allow ?no_action parameter so no redirection happens"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                             <tr>
                                <th scope="row">
                                    <?php _e('Action for Category rules', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Action for Category rules', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <input type="radio" name="category_rules_action" value="category_only" <?php $this->checked($data["category_rules_action"], "category_only"); ?>> <?php _e('Category Pages Only', 'wp-geoip-action'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="category_rules_action" value="category_with_pages" <?php $this->checked($data["category_rules_action"], "category_with_pages"); ?>> <?php _e('All Post/Pages having that category', 'wp-geoip-action'); ?>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Whether Category wise rules will be application on category page only or all pages/post matching that category."', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e('Select custom post types', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Select custom post types', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <?php $this->get_all_post_types($data['post_types']); ?>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Different posts of these post-types will appear in post/page wise action section"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e('Select custom taxonomies', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Select custom taxonomies', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <?php $this->get_all_taxonomies($data['post_taxonomies']); ?>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Different categories of these custom taxonomies will appear in category wise action section"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <?php _e('Specify pages with no actions', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Specify pages with no action', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <textarea name="no_redirection_urls" cols="70" rows="10" placeholder="Specify url of pages that will have no effect of any action in comma seperated form"><?php echo $data["no_redirection_urls"]; ?></textarea>
                                        </label>
                                        <p class="description">
                                            <?php _e('"Specify url of pages that will have no effect of any action in comma seperated form"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>                
                    <div class="submit">
                        <input type="submit" name="submit" class="button-primary" id="wpgeoip_settings" value="Save">
                    </div>  
                </div>    
            </div>
        </form>
        <?php
    }

    public function display_logs($logs) {
        ?>
        <div class="postbox">
            <h3 class="handle"><?php _e('Logs (Showing last 10 entries)', 'wp-geoip-action'); ?></h3>           
            <div class="inside">
                <table class="form-table">
                    <tbody>
                        <?php
                        if ($logs) {
                            foreach ($logs as $log) {
                                ?>
                                <tr>
                                    <td>
                                        <?php _e($log, 'wp-geoip-action'); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <?php _e("To read complete logs, <a href='" . WPGEOIPACTION_PLUGIN_URL . "/log.txt'>Click here</a>", 'wp-geoip-action'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>    
            </div>
        </div>
        <?php
    }

    public function display_ipdata($filename, $rows) {
        ?>
        <form method="post" enctype="multipart/form-data" class="wpgeoip_upload_ipdata">
            <div class="postbox">
                <h3 class="handle"><?php _e('Upload Data by uploading CSV file using browser (for small files)', 'wp-geoip-action'); ?></h3>
                <h3 class="handle"><?php _e('Total Rows Present: ' . $rows[0]->total_rows, 'wp-geoip-action'); ?></h3>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <?php _e('Upload IP Address Data', 'wp-geoip-action'); ?>
                                </th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span><?php _e('Upload IP Address Data', 'wp-geoip-action'); ?></span>
                                        </legend>
                                        <label>
                                            <input type="file" name="file" id="uploadFile"/>      
                                        </label>                                        
                                        <p class="description">
                                            <?php _e('"Upload the data files from different IP based data provider"', 'wp-geoip-action'); ?>
                                        </p>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="submit">
                    <input type="submit" name="wpgeoip_upload" class="button-primary" id="wpgeoip_upload" value="Upload">
                </div>  
            </div>
        </form> 
        <form method="post" enctype="multipart/form-data" class="wpgeoip_upload_ipdata_local_infile">
            <div class="postbox">
                <h3 class="handle"><?php _e('Upload Data by placing CSV file in FTP (Good for large files)', 'wp-geoip-action'); ?></h3>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr>                                
                                <td>
                                    <p class="description">
                                        <?php _e('"You need to upload the file at location = "' . $filename . '" <br/> and press the upload button <br/>"', 'wp-geoip-action'); 
                                        _e('Format for CSV Columns:  (`start_ip_range`, `end_ip_range`, `start_no`, `end_no`, `country_code`, `country_name`, `region_name`,`city_name`,`zipcode`)<br/>', 'wp-geoip-action'); 
                                        if (file_exists($filename)) {
                                            _e(' <br/><br/>csv file <b>found</b>, press the upload button to start uploading', 'wp-geoip-action');
                                        } else {
                                            _e(' <br/><br/>csv file not <b>found</b>, please place csv file at the location speficied above', 'wp-geoip-action');
                                        }
                                        ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="submit">
                    <input type="submit" name="wpgeoip_upload_ipdata_local_infile" class="button-primary" id="wpgeoip_upload_ipdata_local_infile" value="Click to upload data from file">
                </div>  
            </div>
        </form> 
        <?php
    }

    public function map_fields($data, $mapfields, $file_path) {
        ?>
        <form method="post"  class="wpgeoip_import_ipdata">
            <div class="heading">
                <span><?php _e('Map Fields', 'wp-geoip-action'); ?></span>
            </div>
            <table class="tblData">
                <thead>
                <th> <?php _e('Header Row', 'wp-geoip-action'); ?> </th>
                <th> <?php _e('Map Fields', 'wp-geoip-action'); ?> </th>
                <th> <?php _e('Data Row', 'wp-geoip-action'); ?> </th>
                </thead>
                <tbody>
                    <?php
                    if (isset($data[0])) {
                        $totalCols = count($data[0]);
                        for ($col = 0; $col < $totalCols; $col++) {
                            $data[0] = array_values($data[0]);
                            ?>
                            <tr>
                                <td><?php echo $data[0][$col]; ?></td>
                                <td>
                                    <select class="selectData" name="dbColumn<?php echo $col; ?>" data-loopId="<?php echo $col; ?>" id="dbColumn<?php echo $col; ?>">
                                        <option <?php selected($value, ""); ?> value="--select--"><?php _e('Select', 'wp-geoip-action'); ?></option>
                                        <?php
                                        $mapCol = 0;
                                        foreach ($mapfields as $key => $val) {
                                            $selected = "";
                                            if ($col == $mapCol)
                                                $selected = "selected=selected";
                                            ?>
                                            <option <?php if (!is_int($key)) echo "value='" . $key . "'" ?> <?php echo $selected; ?>>
                                                <?php echo $val; ?>
                                            </option>
                                            <?php
                                            $mapCol++;
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <?php
                                    if (isset($data[1][$col])) {
                                        $data[1] = array_values($data[1]);
                                        echo substr($data[1][$col], 0, 80);
                                        if (strlen($data[1][$col]) > 80)
                                            echo"..";
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr>
                        <td>
                        <td class="importInfo">
                            <input type="submit" value="Import " name="wpgeoip_ip_mapping" id="wpgeoip_ip_mapping" class="submit"/>
                            <input name="dbColumn" type="hidden" value="<?php echo $totalCols; ?>" />
                            <input name="import_file" type="hidden" value="<?php echo $file_path; ?>" />         
                        </td>
                    </tr>
            </table>
        </form>
        <?php
    }

    public function display_actions($data, $action_type, $countries, $action_message = "", $settings = "") {
        ?>
        <form action="" method="post">
            <div class="postbox">
                <h3 class="handle"><?php _e('Add Action Rules ' . $action_message, 'wp-geoip-action'); ?></h3>
                <div class="inside">
                    <table class="wc-geoip-actions widefat" id="wpgeoip_action_table">
                        <thead>
                            <tr>
                                <th width="5%"><?php _e('', 'wp-geoip-action'); ?></th>
                                <th width="5%"><?php _e('Country', 'wp-geoip-action'); ?></th>
                                <th width="10%"><?php _e('Region', 'wp-geoip-action'); ?></th>
                                <th width="18%"><?php _e('City', 'wp-geoip-action'); ?></th>
                                <th width="18%"><?php _e('Action', 'wp-geoip-action'); ?>
                                </th>
                                <th width="18%"><?php _e('Action Details', 'wp-geoip-action'); ?></th>
                                <?php if ($action_type == WPGEOIP_CATEGORY) { ?>
                                    <th width="30%"><?php _e('Categories', 'wp-geoip-action'); ?></th>
                                <?php } else if ($action_type == WPGEOIP_POSTTYPE) { ?>
                                    <th width="30%"><?php _e('PosType', 'wp-geoip-action'); ?></th>
                                <?php } else if ($action_type == WPGEOIP_POST) { ?>
                                    <th width="30%"><?php _e('Posts/Pages', 'wp-geoip-action'); ?></th>
                                <?php } ?>
                            </tr>
                        </thead>            
                        <tbody>
                            <?php
                            if (count($data) == 0) {
                                ?>
                                <tr class="ip_rows">
                                    <td><input type="checkbox" value="remove" class="wpgeoip-remove-row" /></td>
                                    <td class="country" width="10%">
                                        <?php echo $this->countries($countries, "data[0][country]"); ?>
                                    </td>
                                    <td class="region" width="10%">
                                        <input type="text" name="data[0][region]" value="" placeholder="<?php _e('Region Code', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-region" />
                                    </td>
                                    <td class="city" width="18%">
                                        <input type="text" name="data[0][city]" value="" placeholder="<?php _e('City Name', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-city" />
                                    </td>
                                    <td class="ip_action" width="18%">
                                        <select name="data[0][ip_action]" class="wpgeoip-select wpgeoip-ip-action" >
                                            <option value="redirect"><?php _e('Redirect', 'wp-geoip-action'); ?></option>
                                            <option value="no-redirection"><?php _e('No-Redirection', 'wp-geoip-action'); ?></option>
                                            <option value="hide"><?php _e('Hide', 'wp-geoip-action'); ?></option>
                                            <option value="show"><?php _e('Show', 'wp-geoip-action'); ?></option>
                                        </select>                           
                                    </td>
                                    <td class="action_details" width="18%">                           
                                        <input type="text" name="data[0][action_details]" value="" placeholder="<?php _e('Redirect URL', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-action-details" />
                                    </td>
                                    <td class="action_type" width="20%">
                                        <?php
                                        if ($action_type == WPGEOIP_CATEGORY)
                                            echo $this->categories("data[0][action_list][]", "", $settings);
                                        else if ($action_type == WPGEOIP_POST)
                                            echo $this->posts("data[0][action_list][]", "", $settings);
                                        else if ($action_type == WPGEOIP_POSTTYPE)
                                            echo $this->custom_post_types("data[0][action_list][]");
                                        ?>
                                    </td>                     
                                </tr>
                                <?php
                            } else {
                                $count = 0;
                                foreach ($data as $row) {
                                    ?>
                                    <tr class="ip_rows" id="<?php echo $count; ?>">
                                        <td><input type="checkbox" value="remove" class="wpgeoip-remove-row" /></td>
                                        <td class="country" width="10%">
                                            <?php echo $this->countries($countries, "data[$count][country]", $row->country_code); ?>
                                        </td>
                                        <td class="region" width="10%">
                                            <input type="text" name="data[<?php echo $count; ?>][region]" value="<?php echo $row->region_name; ?>" placeholder="<?php _e('Region Code', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-region" />
                                        </td>
                                        <td class="city" width="18%">
                                            <input type="text" name="data[<?php echo $count; ?>][city]" value="<?php echo $row->city_name; ?>" placeholder="<?php _e('City Name', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-city" />
                                        </td>
                                        <td class="ip_action" width="18%">
                                            <select name="data[<?php echo $count; ?>][ip_action]" class="wpgeoip-select wpgeoip-ip-action" >
                                                <option <?php selected($row->ip_action, "redirect"); ?> value="redirect"><?php _e('Redirect', 'wp-geoip-action'); ?></option>
                                                <option <?php selected($row->ip_action, "no-redirection"); ?> value="no-redirection"><?php _e('No-Redirection', 'wp-geoip-action'); ?></option>
                                                <option <?php selected($row->ip_action, "hide"); ?> value="hide"><?php _e('Hide', 'wp-geoip-action'); ?></option>
                                                <option <?php selected($row->ip_action, "show"); ?> value="show"><?php _e('Show', 'wp-geoip-action'); ?></option>
                                            </select>                           
                                        </td>
                                        <td class="action_details" width="18%">                           
                                            <input type="text" name="data[<?php echo $count; ?>][action_details]" value="<?php echo $row->action_details; ?>" placeholder="<?php _e('Redirect URL', 'wp-geoip-action'); ?>" class="wpgeoip-input wpgeoip-action-details" />
                                        </td>
                                        <td class="action_type" width="20%">
                                            <?php
                                            if ($action_type == WPGEOIP_CATEGORY)
                                                echo $this->categories("data[$count][action_list][]", $row->action_list, $settings);
                                            else if ($action_type == WPGEOIP_POST)
                                                echo $this->posts("data[$count][action_list][]", $row->action_list, $settings);
                                            else if ($action_type == WPGEOIP_POSTTYPE)
                                                echo $this->custom_post_types("data[$count][action_list][]", $row->action_list);
                                            ?>
                                        </td>                     
                                    </tr>
                                    <?php
                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8">
                                    <a href="#" id="wpgeoip_insert_row" class="button plus insert-row"><?php _e('Add row', 'wp-geoip-action'); ?></a>
                                    <a href="#" id="wpgeoip_remove_row" class="button minus remove-row"><?php _e('Remove selected row(s)', 'wp-geoip-action'); ?></a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="submit">
                        <input type="submit" name="wpgeoip_save_ip_actions" id="submit" class="button button-primary" value="Save Changes"  />
                        <input type="hidden" name="action_type" value="<?php echo $action_type; ?>" />
                    </div>    
                </div>         
        </form>
        <?php
    }

    public function custom_post_types($name, $data = "") {
        $custom_post_types = get_post_types();
        $selected_posttypes = array();
        if (isset($data))
            $selected_posttypes = explode(",", $data);
        ?>
        <select multiple="multiple"  name="<?php echo $name; ?>" class="wpgeoip-select wpgeoip-action-list" id="<?php echo $name; ?>">
            <?php
            if (!empty($custom_post_types)) {
                foreach ($custom_post_types as $post_type) {
                    $selected = in_array($post_type, $selected_posttypes) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo _e($post_type); ?>" <?php echo $selected; ?>><?php echo $post_type; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    public function posts($name, $data = "", $settings = "") {
        if (isset($settings['post_types'])) {
            $post_type_arr = array('post');
            foreach ($settings['post_types'] as $custom_post_types) {
                $post_type_arr[] = $custom_post_types;
            }
            $posts = get_posts(array('posts_per_page' => -1, 'orderby' => 'title', 'post_type' => $post_type_arr));
        } else {
            $posts = get_posts(array('posts_per_page' => -1, 'orderby' => 'title'));
        }

        $selected_posts = array();
        if (isset($data))
            $selected_posts = explode(",", $data);
        ?>
        <select multiple="multiple"  name="<?php echo $name; ?>" class="wpgeoip-select wpgeoip-action-list" id="<?php echo $name; ?>">
            <?php
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $selected = in_array($post->ID, $selected_posts) ? 'selected="selected"' : '';
                    ?>
                    <option value="<?php echo _e($post->ID); ?>" <?php echo $selected; ?>><?php echo (strlen($post->post_title) > 30) ? substr($post->post_title, 0, 30) . ".." : $post->post_title; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    public function categories($name, $data = "", $settings = "") {
        if (isset($settings['post_taxonomies'])) {
            $taxonomy = array();
            foreach ($settings['post_taxonomies'] as $custom_tax) {
                $taxonomy[] = $custom_tax;
            }
            $categories = get_categories(array('orderby' => 'name', 'hide_empty' => 0, 'taxonomy' => $taxonomy));
        }
        else {
            $categories = get_categories(array('orderby' => 'name', 'hide_empty' => 0));
        }
        $selected_cat = array();
        if (isset($data))
            $selected_cat = explode(",", $data);
        ?>

        <select multiple="multiple" name="<?php echo $name; ?>" class="wpgeoip-select wpgeoip-action-list" id="<?php echo $name; ?>">
        <?php
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $selected = in_array($cat->term_id, $selected_cat) ? 'selected="selected"' : '';
                ?>
                    <option value="<?php echo _e($cat->term_id); ?>" <?php echo $selected; ?>><?php echo _e($cat->name); ?></option>
                    <?php
                }
            }
            ?>
        </select>
            <?php
        }

        public function countries($countries, $name, $value = "") {
            ?>              
        <select name="<?php echo $name; ?>" class="wpgeoip-select wpgeoip-country">
            <option value="-1"><?php _e('Select Country', 'wp-geoip-action'); ?></option>
        <?php
        if (!empty($countries)) {
            foreach ($countries as $country_code => $country_name) {
                ?>
                    <option <?php selected($value, $country_code); ?> value="<?php echo $country_code ?>"><?php _e($country_name, 'wp-geoip-action'); ?></option>
                    <?php
                }
            }
            ?>
        </select>
            <?php
        }

        public function get_all_post_types($data = "") {
            $args = array(
                'public' => true,
                '_builtin' => false
            );

            $output = 'names';
            $operator = 'and';

            $post_types = get_post_types($args, $output, $operator);

            foreach ($post_types as $post_type) {
                $checked = "";
                if (!empty($data)) {
                    $checked = in_array($post_type, $data) ? "checked='checked'" : "";
                }
                echo '<p><input type="checkbox" ' . $checked . ' value="' . $post_type . '" name="post_types[]">' . $post_type . '</p>';
            }
        }

        public function get_all_taxonomies($data = '') {
            $taxonomies = get_taxonomies();

            foreach ($taxonomies as $taxonomy) {
                $checked = "";
                if (!empty($data)) {
                    $checked = in_array($taxonomy, $data) ? "checked='checked'" : "";
                }
                echo '<p><input type="checkbox" ' . $checked . ' value="' . $taxonomy . '" name="post_taxonomies[]">' . $taxonomy . '</p>';
            }
        }

        public function checked($field_val, $required_val) {
            echo ($field_val == $required_val) ? "checked=checked" : "";
        }

    }