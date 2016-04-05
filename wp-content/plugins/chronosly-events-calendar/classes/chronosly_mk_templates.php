<?php
/**
 * Marketplace templates object
 */


if(!class_exists('Chronosly_MK_Templates')){
    class Chronosly_MK_Templates{

        public function __construct()
        {

            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'), 11);
            add_action( 'admin_enqueue_scripts', array(&$this,'admin_template') );
            add_action( 'wp_ajax_chronosly_load_mk_templates', array(&$this, 'load_mk_templates' ));
            add_action( 'wp_ajax_chronosly_load_mk_templates_downloaded', array(&$this, 'load_mk_templates_downloaded' ));


        } // END public function __construct

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            register_setting('chronosly-group', 'chronosly-mk-templates');
            if(!get_option("chronosly-mk-templates")){
                $settings = array(
                    "templates-downloaded"=> array("t1"),
                );
                update_option('chronosly-mk-templates', serialize($settings));
            }



        } // END public static function activate
        public function add_menu()
        {
            global $mk_templates_page_hook_suffix;
            // Add a page to manage this plugin's settings
            add_submenu_page(
                'chronosly',
                'Chronosly Templates Marketplace',
                __('Download Templates',"chronosly"),
                'manage_options',
                'chronosly_mk_templates',
                array(&$this, 'mk_templates_page')
            );
            $mk_templates_page_hook_suffix =  add_submenu_page(
                'edit.php?post_type=chronosly',
                'Chronosly Templates Marketplace',
                __('Download Templates',"chronosly"),
                'manage_options',
                'chronosly_mk_templates',
                array(&$this, 'mk_templates_page')
            );


        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function mk_templates_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."marketplace".DIRECTORY_SEPARATOR."templates.php");
        }




        public function admin_template($hook){
            //load the template acording to mode
            global $mk_templates_page_hook_suffix;
            if( $hook != $mk_templates_page_hook_suffix) return;
            wp_register_style( 'chronosly-custom_wp_admin_css', CHRONOSLY_URL .'/css/admin_template1.css', false, '1.0.0' );
            wp_enqueue_style( 'chronosly-custom_wp_admin_css' );

            wp_register_script( 'chronosly_js_mk_templates', CHRONOSLY_URL .'/js/mk_templates_page.js', array( 'jquery' ), '1.0.0' );
            wp_enqueue_script( 'chronosly_js_mk_templates' );
            $translation_array	= array(
                "author" => __("author", "chronosly"),
                "price" => __("price", "chronosly"),
                "download" => __("download", "chronosly"),
                "view" => __("view", "chronosly"),

            );
            wp_localize_script( 'chronosly_js_mk_templates', 'translated', $translation_array );

        }

        //cargamos los templates del server y marcamos los que ya tenemos descargados
        public function load_mk_templates(){
            $ex = new Chronosly_Extend;
            $cont = $ex->get_templates_feed();

            echo json_encode($cont);
            die();
        }

        //templates ya descargados
        public function load_mk_templates_downloaded(){

            $temp = new Chronosly_Templates;

            //moking del json que se recibiria
            $templates_list =  $temp->load_template_settings($temp->get_templates_options(1), 1);
            echo json_encode($templates_list);
            die();
        }

    }
}
