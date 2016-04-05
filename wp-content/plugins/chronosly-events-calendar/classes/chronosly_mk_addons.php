<?php
/**
 * Marketplace addons object
 *
 */
if(!class_exists('Chronosly_MK_Addons')){
    class Chronosly_MK_Addons{


        public function __construct()
        {
            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'), 15);
            add_action( 'admin_enqueue_scripts', array(&$this,'admin_template') );

        } // END public function __construct

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // register your plugin's settings
            register_setting('chronosly-group', 'chronosly-mk-addons');
            if(!get_option("chronosly-mk-addons")){
                $settings = array(
                    "addons-downloaded"=> array("t1", "t2"),
                );
                update_option('chronosly-mk-addons', serialize($settings));
            }

            add_action( 'wp_ajax_chronosly_load_mk_addons', array(&$this, 'load_mk_addons' ));
            add_action( 'wp_ajax_chronosly_load_mk_addons_downloaded', array(&$this, 'load_mk_addons_downloaded' ));


        } // END public static function activate
        public function add_menu()
        {
            global $mk_addons_page_hook_suffix;

            // Add a page to manage this plugin's settings
            add_submenu_page(
                'chronosly',
                'Chronosly Addons Marketplace',
                __('Download Addons',"chronosly"),
                'manage_options',
                'chronosly_mk_addons',
                array(&$this, 'mk_addons_page')
            );
           $mk_addons_page_hook_suffix=  add_submenu_page(
                'edit.php?post_type=chronosly',
                'Chronosly Addons Marketplace',
                __('Download Addons',"chronosly"),
                'manage_options',
                'chronosly_mk_addons',
                array(&$this, 'mk_addons_page')
            );


        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function mk_addons_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."marketplace".DIRECTORY_SEPARATOR."addons.php");
        }




        public function admin_template($hook){
            //load the template acording to mode
            global $mk_addons_page_hook_suffix;
            if( $hook != $mk_addons_page_hook_suffix) return;

            wp_register_style( 'chronosly-custom_wp_admin_css', CHRONOSLY_URL .'/css/admin_template1.css', false, '1.0.0' );
            wp_enqueue_style( 'chronosly-custom_wp_admin_css' );

            wp_register_script( 'chronosly_js_mk_addons', CHRONOSLY_URL .'/js/mk_addons_page.js', array( 'jquery' ), '1.0.0' );
            wp_enqueue_script( 'chronosly_js_mk_addons' );
            $translation_array	= array(
                "author" => __("author", "chronosly"),
                "price" => __("price", "chronosly"),
                "settings" => __("settings", "chronosly"),
                "download" => __("download", "chronosly"),
                "view" => __("info", "chronosly"),

            );
            wp_localize_script( 'chronosly_js_mk_addons', 'translated', $translation_array );

        }

        //cargamos los templates del server y marcamos los que ya tenemos descargados
        public function load_mk_addons(){

            $ex = new Chronosly_Extend;
            $cont = $ex->get_addons_feed();
            echo json_encode($cont);
           die();
        }

        //addons ya descargados
        public function load_mk_addons_downloaded(){


                //moking del json que se recibiria
                $addons = array();
                $addons_list = apply_filters("chronosly_addons_settings_item", $addons);
                foreach($addons_list as $k=>$v){
                    $class = new $v;
                    $addons_urls[$k] = "admin.php?page=".$class->settings_url;

                }
                echo json_encode(array("urls" => $addons_urls, "list" => $addons_list));
                die();
            }

    }
}
