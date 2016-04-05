<?php
if(!class_exists('Chronosly_Settings'))
{
	class Chronosly_Settings
	{
		/**
		 * Construct the plugin object
		 */
        public $settings_menu;
        public $addons_menu;
        public $addons_settings;
		public function __construct()
		{
			// register actions
            add_action('admin_menu', array(&$this, 'admin_init'));
            add_action( 'admin_menu', array( $this, 'go_welcome' ) );
            add_action( 'chronosly-addon-head', array( $this, 'chronosly_addon_head' ) );
            add_action( 'chronosly-addon-foot', array( $this, 'chronosly_addon_foot' ) );

            //add_action('admin_menu', array(&$this, 'add_menu'));
			add_action( 'admin_enqueue_scripts', array(&$this,'admin_template') );

		} // END public function __construct

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('chronosly-group', 'chronosly-settings');
        	if(!get_option("chronosly-settings")){
				$settings = array(
                    //urls config
					"chronosly-slug" => "events",
					"chronosly-history-slug" => "events",
					"chronosly-organizer-slug" => "organizers",
					"chronosly-organizer-history-slug" => "organizers",
					"chronosly-place-slug" => "places",
					"chronosly-place-history-slug" => "places",
					"chronosly-calendar-slug" => "calendar",
					"chronosly-calendar-history-slug" => "calendar",
                    "chronosly-category-slug" => "event_categories",
					"chronosly-category-history-slug" => "event_categories",
                    "chronosly-tag-slug" => "event_tags",
					"chronosly-tag-history-slug" => "event_tags",
                    "chronosly-event-list-url" => "",
                    "chronosly-organazires-list-url" => "",
                    "chronosly-places-list-url" => "",
                    "chronosly-calendar-url" => "",
                     "chronosly-event-list-title" => "Events list",
                    "chronosly-organizers-list-title" => "Organizers list",
                    "chronosly-places-list-title" => "Places list",

                    //templates config
                    "chronosly_tipo_perfil" => "1",
                    "chronosly_template_default" => "default",
                    "chronosly_template_default_active" => "",
                    "chronosly_calendar_template_default" => "default",
                    "chronosly_titles_template_default" => "default",
                    "chronosly_category_color" => "#1BBC9D",
                    "chronosly_font_color" => "#fff",
                    "chronosly_sale_color" => "#ff9711",
                    "chronosly_font_family" => "Arial",
                    "chronosly_font_size" => "14",
                    "chronosly_background_color" => "#fff",
                    "chronosly_background_round" => "15",
                    "chronosly_padding" => "10",
                    "chronosly_margin" => "10",
                    "chronosly_box_background_color" => "#fff",
                    "chronosly_box_background_round" => "15",
                    "chronosly_box_border_color" => "#fff",
                    "chronosly_box_border_size" => "2",
                    "chronosly_box_border_style" => "solid",
                    "chronosly_box_padding" => "10",
                    "chronosly_box_margin" => "10",
                    "chronosly_events_order" => "date",
                    "chronosly_events_orderdir" => "ASC",
                    "chronosly_format_date_time" => "d-m-y H:i",
                    "chronosly_format_time" => " H:i",
                    "chronosly_format_date" => "d-m-y",
                    "chronosly_full_datetime_separator" => "to",
                    "chronosly_currency" => "&#x20AC;",
                    "chronosly_template_max" => "800",
                    "chronosly_template_min" => "",
                    "chronosly_dad_event_title_link" => "1",
                    "chronosly_dad_organizer_title_link" => "1",
                    "chronosly_dad_place_title_link" => "1",
                    "chronosly_dad_category_title_link" => "1",
                    "chronosly_dad_tag_title_link" => "1",
                    "chronosly_dad_link_action" => "1",
                    "chronosly_dad_link_new_window" => "1",
                    "chronosly_dad_link_nofollow" => "0",
                    "chronosly_dad_readmore" => "Change me",
                    "chronosly_dad_buy_tiket" => "Buy",
                    "chronosly_dad_cat_separator" => ", ",
                    "chronosly_dad_tag_separator" => ", ",
                   // "chronosly_dad_img_width" => "40%",
                    //"chronosly_dad_gallery_box" => "Change me",
                    //"chronosly_dad_tikets_box" => "Change me",
                    "chronosly_dad_gmap_zoom" => "16",
                    "chronosly_dad_show_load_template" => "1",
                   // "chronosly_dad_show_save_template" => "1",
                    "chronosly_dad_show_load_view" => "1",
                   /* "chronosly_paint_show_vars" => "1",
                    "chronosly_paint_show_text" => "1",
                    "chronosly_paint_show_back" => "1",
                    "chronosly_paint_show_space" => "1",
                    "chronosly_paint_show_border" => "1",
                    "chronosly_paint_show_custom" => "1",
                    "chronosly_paint_show_custom_all" => "1",*/
                    "chronosly_show_past_events" => "0",
                    //"chronosly_show_filters_on_events" => "1",
                    //"chronosly_show_filters_on_categories" => "1",
                    //"chronosly_show_filters_on_tags" => "1",
                   // "chronosly_show_filters_on_organizers" => "1",
                   // "chronosly_show_filters_on_places" => "1",
                   // "chronosly_show_filters_on_calendar" => "1",
                   // "chronosly_show_help" => "1",
                    "chronosly_featured_first" => "1",
                    "chronosly_week_start" => "2",
                    "chronosly_core_autoupdate" => "0",
                    "chronosly_addons_autoupdate" => "0",
                    "chronosly_templates_autoupdate" => "0",
                    "chronosly_license" => "",
                    "chronosly_tickets" => 1,
                    "chronosly_template_editor" => 1,
                    "chronosly_organizers" => 1,
                    "chronosly_places" => 1,
                    "chronosly_organizers_addon" => 0,
                    "chronosly_places_addon" => 0,
                    "chronosly_list_events_select" => 1,
                    "chronosly_list_events_start" => "",
                    "chronosly_list_events_end" => "",
                    "chronosly_event_list_format" => "year",
                    "chronosly_event_list_time" => "",
                    "chronosly_events_x_page" => "15",
                    "chronosly_organizers_x_page" => "10",
                    "chronosly_places_x_page" => "10",
                    "chronosly-allow-flush" => 1,
                    "chronosly-show-repeats-organizer" => 1,
                    "chronosly-show-repeats-place" => 1,
                    "chronosly-base-templates-id" => 0,
                    "chronosly-events-base-templates-id" => 0,
                    "chronosly-organizers-base-templates-id" => 0,
                    "chronosly-places-base-templates-id" => 0,
                    "chronosly-calendar-base-templates-id" => 0,
                    "chronosly-category-templates-id" => 0,
                    "chronosly-events-single-base-templates-id" => 0,
                    "chronosly-organizers-single-base-templates-id" => 0,
                    "chronosly-places-single-base-templates-id" => 0,
                    "chronosly-category-single-templates-id" => 0,
                    "chronosly-disable-gmap-js" => 0,
                    "jquery-admin-disable" => 0,
                    "disable_cache" => 0,
                    "hide_past_on_calendar" => 0,
                    "disable_slide_on_show" => 0,
                    "chronosly-events-flushed" => 0,
                    "chronosly-organizers-flushed" => 0,
                    "chronosly-places-flushed" => 0,
                    "chronosly-calendar-flushed" => 0,
                    "chronosly-cats-flushed" => 0,
                    "chronosly-tags-flushed" => 0,
                    "chronosly_admin_memory_limit" => 0,    


				);
				update_option('chronosly-settings', serialize($settings));

			} else{

                $settings = unserialize(get_option("chronosly-settings"));
                if(!isset( $settings["chronosly-event-list-title"])) $settings["chronosly-event-list-title"] = "Events list";
                if(!isset( $settings["chronosly-organizers-list-title"])) $settings["chronosly-organizers-list-title"] = "Organizers list";
                if(!isset( $settings["chronosly-places-list-title"])) $settings["chronosly-places-list-title"] = "Places list";
                if(!isset( $settings["chronosly_sale_color"])) $settings["chronosly_sale_color"] = "#ff9711";
                update_option('chronosly-settings', serialize($settings));
            }

            //add settings menus
            $configs = array();
            $this->settings_menu = apply_filters("chronosly_config_page_menu_item", $configs);
            //add addons settings menus
            $addons = array();
            $this->addons_settings = apply_filters("chronosly_addons_settings_item", $addons);
            $addons = array();
            $this->addons_menu = apply_filters("chronosly_addons_settings_menu_item", $addons);

            $this->add_menu();

        } // END public static function activate






        /* return an array counting the addons that needs update */
        public function get_addons_need_update(){
            $update = 0;
            $addon = array();
            foreach($this->addons_settings as $k){
                $class = new $k;
                if(isset($class->settings) and is_array($class->settings)){$sets = $class->settings;}
                else {$class->addon_settings(1);$sets = $class->settings;}
               // print_r($sets);
                if(floatval($sets["needed_version"]) > floatval($sets["version"])){
                    $update++;
                    $addon[$class->id] = $class->name;
                }
            }
            return array("count"=>$update, "names"=>$addon);
        }

        /**
         * add a menu
         */
        public function add_menu()
        {
            global $settings_page_hook_suffix, $profile_page_hook_suffix, $template_edit_page_hook_suffix, $template_status_page_hook_suffix,
                   $addons_config_page_hook_suffix,$status_page_hook_suffix,$lang_page_hook_suffix,$support_page_hook_suffix;
            // Add a page to manage this plugin's settings

            $updates_count = $this->get_addons_need_update();
          //print_r($updates_count);
            $updates_count = $updates_count["count"];
            $settings_page_hook_suffix =   add_menu_page(
        	    'Chronosly Settings',
        	    'Chronosly'.' <span class="update-plugins count-'.$updates_count.'"><span class="plugin-count">'.$updates_count.'</span></span>',
        	    'manage_options',
        	    'chronosly',
        	    array(&$this, 'plugin_settings_page'),
                CHRONOSLY_URL."css/img/menu-ico.png"
        	);

            $settings_page_hook_suffix =   add_submenu_page(
                'chronosly',
        	    'Chronosly Settings',
                __('Settings',"chronosly"),
                'manage_options',
        	    'chronosly',
        	    array(&$this, 'plugin_settings_page')
        	);
     $template_edit_page_hook_suffix =  add_submenu_page(
                    'chronosly',
                    'Chronosly Settings',
                    __('Edit Templates',"chronosly"),
                    'manage_options',
                    'chronosly_edit_templates',
                    array(&$this, 'template_edit_page')
                );

            $template_status_page_hook_suffix =  add_submenu_page(
                'chronosly',
        	    'Chronosly Settings',
        	    __('Templates status',"chronosly"),
        	    'manage_options',
        	    'chronosly_templates_status',
        	    array(&$this, 'templates_status')
        	);
            $addons_config_page_hook_suffix =  add_submenu_page(
                'chronosly',
        	    'Chronosly Settings',
        	    __('Addons settings',"chronosly").' <span class="update-plugins count-'.$updates_count.'"><span class="plugin-count">'.$updates_count.'</span></span>',
        	    'manage_options',
        	    'chronosly_addons_configs',
        	    array(&$this, 'addons_settings_page')
        	);


            $support_page_hook_suffix =  add_submenu_page(
                'chronosly',
        	    'Chronosly Settings',
        	    __('Support',"chronosly"),
        	    'manage_options',
        	    'chronosly_support',
        	    array(&$this, 'plugin_support_page')
        	);

        } // END public function add_menu()

        //Pendiente 2.0
        public function go_welcome(){
            if ( ! get_transient( '_chronosly_welcome' )  )
                return;

            // Delete the redirect transient
            delete_transient( '_chronosly_welcome' );
            wp_safe_redirect( admin_url( 'admin.php?page=chronosly_profile' ) );
            exit;
        }

        /**
         * Menu Callback
         */
        public function plugin_settings_page(){
            global $Post_Type_Chronosly;

            if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}



            wp_register_script( 'chronosly-spectrum', CHRONOSLY_URL.'/js/spectrum/spectrum.js', array( 'jquery' ));
            wp_enqueue_script('chronosly-spectrum');
            wp_register_style( 'chronosly-spectrum-css', CHRONOSLY_URL.'/js/spectrum/spectrum.css');
            wp_enqueue_style('chronosly-spectrum-css');

            if(isset($_POST['chronosly_nonce'])) {
                //save vars
                if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_save_settings" ) ){
                    Chronosly_Cache::clear_cache();
                    $vars = unserialize(get_option('chronosly-settings'));
                    $vars2 = array();
                    foreach($_POST as $pf=>$pv){
                        if(stripos($pf, "history-slug") !== FALSE){
                            $prev = str_replace("history-", "", $pf);
                            $hist = explode(",", $pv);
                            //save a history of slugs to make 301 redirects
                            if($vars2[$prev] and !in_array($vars2[$prev], $hist)) $vars2[$pf] = $pv.",".$vars2[$prev];
                            else  $vars2[$pf] = $pv;
                        }
                        else $vars2[$pf] = $pv;
                    }
                    $vars = array_merge($vars2);
                    update_option('chronosly-settings', serialize($vars));

                } else {
                    die( __( 'Action failed. Please refresh the page and retry.', 'chronosly' ) );
                }

            } else if(isset($_REQUEST["create_base"])){

                if( !current_user_can('manage_options'))
                {
                    wp_die(__('You do not have sufficient permissions to access this page.'));
                }

                Chronosly_Cache::clear_cache();

                $settings = unserialize(get_option('chronosly-settings'));

                if(!$settings["chronosly-base-templates-id"]){
                    $args = array(
                        "post_title" => "Chronosly base",
                        'post_status'      => 'publish',
                        "post_type" => 'page',
                        "post_content" => '[chronoslybase]',
                        "post_parent"  => 0,
                    );

                    $settings["chronosly-base-templates-id"] = wp_insert_post ($args);
                    update_option('chronosly-settings', serialize($settings));
                    echo  "<div class='bubblegreen'>The Chronosly base has been created.</div>";
                } else {
                    echo  "<div class='bubblegreen'>The Chronosly base is already created.</div>";
                }


        } else if(isset($_REQUEST["delete_base"])){

            if( !current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            Chronosly_Cache::clear_cache();

            $settings = unserialize(get_option('chronosly-settings'));

            if($settings["chronosly-base-templates-id"]){

                wp_delete_post($settings["chronosly-base-templates-id"]);

                $settings["chronosly-base-templates-id"] = 0;
                update_option('chronosly-settings', serialize($settings));
                echo  "<div class='bubblegreen'>The Chronosly base has been removed.</div>";

            } else {

                echo  "<div class='bubblegreen'>The Chronosly base is already removed.</div>";

            }
        }
            flush_rewrite_rules();
            $vars = unserialize(get_option("chronosly-settings"));


            $this->print_settings_menu();
            $vars = unserialize(get_option("chronosly-settings"));
            $templates_options = $Post_Type_Chronosly->template->get_templates_options(0, $vars["chronosly_template_default"]);
            $templates_calendar_options = $Post_Type_Chronosly->template->get_templates_options(0, $vars["chronosly_calendar_template_default"]);
            $templates_titles_options = $Post_Type_Chronosly->template->get_templates_options(0, $vars["chronosly_titles_template_default"]);
            $currency = $Post_Type_Chronosly->template->currency_selector($vars['chronosly_currency']);
        	$templates_licenses = $this->get_template_licenses();
            // Render the settings template
        	include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."settings.php");

        }



        public function print_settings_menu(){
            echo '<div style="clear:both;margin-top:20px;"></div>';
            echo "<div class='settings-menu'>";
            foreach($this->settings_menu as $k=>$v){
                echo "<a class='set-menu' href='admin.php?page=$k".__($v, "chronosly")."</a>";
            }
            echo "</div>";
        }

        public function addons_settings_page(){
            echo "<div style='clear:both;margin-top:20px;'></div>";
            $this->print_addons_menu();
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."addons-settings.php");

        }

        public function print_addons_menu(){
            global $Post_Type_Chronosly;

            $Post_Type_Chronosly->inicia_scripts();
            echo "<div class='addons-menu'>";
            $current = "";
            if(isset($_REQUEST['page']) and  "chronosly_addons_configs" == $_REQUEST['page']) $current =" current";
            echo "<a class='set-menu$current' href='admin.php?page=chronosly_addons_configs' >".__("Main page", "chronosly")."</a>";
            foreach($this->addons_menu as $k=>$v){
                $current = "";
                if(isset($_REQUEST['page']) and $k == $_REQUEST['page']) $current =" current";
                echo "<a class='set-menu$current' href='admin.php?page=$k' >".__($v, "chronosly")."</a>";
            }
            echo "</div>";
        }

        public function print_addons_main_page(){


            echo "<div class='addons-main-page'>";

            foreach($this->addons_settings as $k){
                $class = new $k;
                if(isset($class->settings) and is_array($class->settings)){$sets = $class->settings;}
                else {$class->addon_settings(1);$sets = $class->settings;}
                if(floatval($sets["needed_version"]) > floatval($sets["version"])) $update = 1;
                echo "<div class='addon{$class->id} addon";
                if($update) echo " needupdate";
                if(isset($class->settings_url)) $sets_url = "href='admin.php?page=$class->settings_url'";
                echo "'>
                            <a {$sets_url}><div class='icon'></div></a>
                            <div class='title'><a {$sets_url}>{$class->name}</a></div>
                            <div class='description'><a {$sets_url}>{$class->description}</a></div>
                            <div class='version'><p>".__("Version", "chronosly")." {$sets["version"]}</p>";
                                 if($update) echo "<a href='admin.php?page=chronosly_addons_configs&update_addon=1&addon={$class->id}' class='update'>".__("required update to", "chronosly")." {$sets["needed_version"]}</a>";
                                 if($class->id != "organizers_and_places") echo "<a href='admin.php?page=chronosly_addons_configs&delete={$class->id}' class='update'>".__("Delete", "chronosly")."</a>";
                            echo "</div>

                </div>";
            }
            echo "</div>";
        }

        public function chronosly_addon_head($addon){

            echo '<div style="clear:both;margin-top:20px;"></div>';

            $this->print_addons_menu();

            echo '<div class="wrapch right">';
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            if(isset($_POST['chronosly_nonce'])) {
                //uploading templates

                if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_addon_save_settings" )){
                    Chronosly_Cache::clear_cache();
                    $vars2 = unserialize(get_option('chronosly_settings_'.$addon));
                        $vars = array();
                        foreach($_POST as $pf=>$pv){
                           if(is_array($pv)) $vars[$pf] = serialize($pv);
                           else $vars[$pf] = $pv;
                        }
                        //ponemos los parametros que no tiene el form
                        $vars["version"] = $vars2["version"];
                        $vars["needed_version"] = $vars2["needed_version"];
                        $vars["templates_auto_updated"] = $vars2["templates_auto_updated"];
                        update_option('chronosly_settings_'.$addon, serialize($vars));


                }
            }


              echo '<form method="post" action="" enctype="multipart/form-data">';
                wp_nonce_field( "chronosly_addon_save_settings", 'chronosly_nonce' );
        }

        public function chronosly_addon_foot($addon){
            echo '<br/><input class="submit" type="submit" value="'.__("Save", "chronosly").'" />';
            echo "</form></div>";
        }

        public function print_addons_status(){
            $ex = new Chronosly_Extend;
            $addons = $ex->get_addons_feed();

            echo "<div class='version'>";
            if(is_array($addons) or is_object($addons)){
                foreach($addons as $ad){
                    $c = $this->addons_settings[$ad->id];
                    if(class_exists($c)) $class = new $c;
                    else continue;
                    if(isset($class->settings) and is_array($class->settings)){$sets = $class->settings;}
                    else {$class->addon_settings(1);$sets = $class->settings;}
                    // echo $ad->version;
                    if($ad->version != $sets["version"]){
                        echo "<a class='update' href='admin.php?page=chronosly_addons_configs&update_addon=1&addon={$class->id}'><b>{$class->name}</b> ".__("newer version", "chronosly")." ".$ad->version."</a><br/>";
                    }



                }
            }
            echo "</div>";
        }

        //pendiente 2.0
        public function plugin_welcome_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}

        	// Render the settings template
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."welcome.php");
        }


        public function langs_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}

        	// Render the settings template
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."langs.php");
        }


        public function templates_status(){
            global $Post_Type_Chronosly;
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            wp_register_script( 'chronosly_js_setting', CHRONOSLY_URL .'/js/settings_page.js', array( 'jquery' ), '1.0.0' );
            wp_enqueue_script( 'chronosly_js_setting' );


            wp_register_script( 'chronosly_js_chart', CHRONOSLY_URL .'/js/chartjs/Chart.js', array( 'jquery' ), '1.0.0' );
            wp_enqueue_script( 'chronosly_js_chart' );
            wp_register_script( 'chronosly_js_chart_legend', CHRONOSLY_URL .'/js/chartjs/legend/src/legend.js', array( 'jquery' ), '1.0.0' );
            wp_enqueue_script( 'chronosly_js_chart_legend' );
            wp_register_script( 'chronosly-spectrum', CHRONOSLY_URL.'/js/spectrum/spectrum.js', array( 'jquery' ));
            wp_enqueue_script('chronosly-spectrum');
            wp_register_style( 'chronosly-spectrum-css', CHRONOSLY_URL.'/js/spectrum/spectrum.css');
            wp_enqueue_style('chronosly-spectrum-css');


            wp_register_style( 'chronosly-custom_wp_admin_css', CHRONOSLY_URL .'/css/admin_template1.css', false, '1.0.0' );
            wp_enqueue_style( 'chronosly-custom_wp_admin_css' );
            $status = $Post_Type_Chronosly->template->template_status();
            $print_status =  json_encode($this->print_templates_status($status));
            $translation_array	= array(
                "templates_items" => $print_status,
                "without" => __("Without","chronosly"),
                "category" => __("Category","chronosly"),
                "organizer" => __("Organizer","chronosly"),
                "place" => __("Place","chronosly"),
                "event" => __("Event","chronosly"),
                "template" => __("Template","chronosly"),
                "dad1" => __("All events list view", "chronosly"),
                "dad2" => __("Single event view", "chronosly"),
                "dad3" => __("Calendar view", "chronosly"),
                "dad4" => __("Category events list view", "chronosly"),
                "dad5" => __("Organizer events list view", "chronosly"),
                "dad6" => __("Place events list view", "chronosly"),
                "dad7" => __("All organizers list view", "chronosly"),
                "dad8" => __("Single organizer view", "chronosly"),
                "dad9" => __("All places list view", "chronosly"),
                "dad10" => __("Single place view", "chronosly"),
                "dad11" => __("All categories list view", "chronosly"),
                "dad12" => __("Single category view", "chronosly"),
                "bd_template" => __("Individualy edited","chronosly"),
                "title_chart" => __("Chart representation of template usage","chronosly"),
            );
            wp_localize_script( 'chronosly_js_setting', 'translated', $translation_array );
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."templates-status.php");

        }

        public function plugin_support_page(){
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."support.php");
        }

         public function print_templates_status($status){
            $ret = array();
             $sets = unserialize(get_option("chronosly-settings"));
            $dads = array("dad1","dad2","dad3","dad4","dad5","dad6","dad7","dad8","dad9","dad10","dad11","dad12");
            if(!isset($status)) return;
            foreach($status as $id=>$ev){
                $return = array();
                if(isset($ev["ev-to"])) $data = $ev["ev-to"][0];
                $title = $ev["title"];
                $cat_ids = $cat_names = "";
                if(isset($ev["cats_vars"])){
                    foreach($ev["cats_vars"] as $cat){
                        $cat_ids[] = $cat->term_id;
                        $cat_names[] = $cat->name;

                    }
                }
                $cat_id = @implode("|",$cat_ids);
                $cat_n = @implode("|",$cat_names);
                if(!$cat_id) $cat_id = $cat_n=  "";
                foreach($ev as $k=>$t){
                    if(!$t) continue;
                    if(in_array($k, $dads)){

                        $return[]= array("view"=>$k, "templ"=>$t, "cats"=>$cat_id, "cats-n"=>$cat_n, "id"=>$id, "name"=>$title, "data"=>$data);
                    }
                }

                if(!count($return)){
                    foreach ($dads as $dad) {
                        $return[]= array("view"=>$dad, "templ"=>$sets["chronosly_template_default"], "cats"=>$cat_id, "cats-n"=>$cat_n, "id"=>$id, "name"=>$title, "data"=>$data);

                    }
                }

                $ret= array_merge($ret, $return);

            }
            return $ret;
        }


		public function admin_template($hook){
			//load the template acording to mode
            global $settings_page_hook_suffix, $profile_page_hook_suffix, $template_edit_page_hook_suffix, $template_status_page_hook_suffix,
                   $addons_config_page_hook_suffix,$status_page_hook_suffix,$lang_page_hook_suffix,$support_page_hook_suffix;
            if( $hook != $settings_page_hook_suffix and $hook != $profile_page_hook_suffix and $hook != $support_page_hook_suffix) return;
            wp_register_style( 'chronosly-custom_wp_admin_css', CHRONOSLY_URL .'/css/admin_template1.css', false, '1.0.0' );
			wp_enqueue_style( 'chronosly-custom_wp_admin_css' );

           wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script( 'jquery-ui-datepicker');

            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script('jquery-ui-resizable');
            wp_enqueue_script('jquery-ui-draggable');

            wp_register_style( 'chronosly-admin-jquery-ui-css', CHRONOSLY_URL.'/css/smoothness/jquery-ui-1.10.4.custom.css');
            wp_enqueue_style('chronosly-admin-jquery-ui-css');
            //colorpicker
            wp_register_script( 'chronosly-spectrum', CHRONOSLY_URL.'/js/spectrum/spectrum.js', array( 'jquery' ));
            wp_enqueue_script('chronosly-spectrum');
            wp_register_style( 'chronosly-spectrum-css', CHRONOSLY_URL.'/js/spectrum/spectrum.css');
            wp_enqueue_style('chronosly-spectrum-css');



            wp_register_script( 'chronosly_js_setting', CHRONOSLY_URL .'/js/settings_page.js', array( 'jquery' ), '1.0.0' );
			wp_enqueue_script( 'chronosly_js_setting' );





        }

          public function template_edit_page()
        {
            global $Post_Type_Chronosly;
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            if(isset($_POST['chronosly_nonce'])) {
                //uploading templates

                if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_addons_upload" )){

                    if(!empty($_FILES['chronosly_addon']['name'])) {
                        Chronosly_Cache::clear_cache();
                        //print_r($_FILES['chronosly_addon']);
                        // Setup the array of supported file types. In this case, it's just PDF.
                        $supported_types = array('application/zip');

                        // Get the file type of the upload
                        $arr_file_type = wp_check_filetype(basename($_FILES['chronosly_addon']['name']));
                        $uploaded_type = $arr_file_type['type'];

                        // Check if the type is supported. If not, throw an error.
                        if(in_array($uploaded_type, $supported_types)) {

                            // Use the WordPress API to upload the file
                            $upload = wp_upload_bits($_FILES['chronosly_addon']['name'], null, file_get_contents($_FILES['chronosly_addon']['tmp_name']));

                            if(isset($upload['error']) && $upload['error'] != 0) {
                                echo '<div class="bubbleerror">'.__(sprintf('There was an error uploading your file. The error is: %s' , "chronosly"), $upload['error'])."</div>";
                            } else {
                                WP_Filesystem();
                                $destination_path = CHRONOSLY_TEMPLATES_PATH;
                                $upload['file'] = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $upload['file']);
                                $unzipfile = unzip_file( $upload['file'], $destination_path);
                                unlink( $upload['file'] );
                                if ( $unzipfile === true ) {
                                    $names = explode(DIRECTORY_SEPARATOR,  $upload['file']);
                                    $name = str_replace(".zip", "",$names[count($names)-1]);
                                    //volvemos a construir los addons
                                    Chronosly_Extend::rebuild_template_addons( $name);
                                    echo  "<div class='bubblegreen'>$name ".__("template installed", "chronosly")."</div>";

                                    wp_redirect("admin.php?page=chronosly_edit_templates&installed=$name");

                                } else {
                                    echo '<div class="bubbleerror">'.__("There was an error installing this template", "chronosly").'</div>';
                                }
                            }

                        } else {
                            echo '<div class="bubbleerror">'.__("The file type that you've uploaded is not a Chronosly Template ZIP.", "chronosly")."</div>";
                        } // end if/else

                    } // end if


                }
            }
            $Post_Type_Chronosly->inicia_scripts();

            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."settings".DIRECTORY_SEPARATOR."template-editor.php");

        }

         public function get_template_licenses(){
            global $Post_Type_Chronosly;
            $ret = "";
            $m_templates = $Post_Type_Chronosly->template->load_template_settings($Post_Type_Chronosly->template->get_templates_options(1), 1);
            if(!count($m_templates)) return "";
            $sets = unserialize(get_option("chronosly-settings"));
            foreach($m_templates as $t=>$cont){
                if($t != "default") $ret .= "<label>".$t." ".__("license", "chronosly")."</label><input type='text' name='chronosly-settings_templates_license_$t' value='".$sets["chronosly-settings_templates_license_$t"]."' /><br/>";

            }
            return $ret;
        }

		// END public function plugin_settings_page()





    } // END
} // END


