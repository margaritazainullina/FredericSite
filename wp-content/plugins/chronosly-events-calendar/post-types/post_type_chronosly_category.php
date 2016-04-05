<?php
if(!class_exists('Post_Type_Chronosly_Category'))
{
	/**
	 */
	class Post_Type_Chronosly_Category
	{
		const POST_TYPE	= "chronosly_category";


        public $template, $settings;

        /**
    	 * The Constructor
    	 */
    	public function __construct()
    	{
            global $Post_Type_Chronosly;
    		// register actions
    		add_action('init', array(&$this, 'init'));
    		add_action('admin_init', array(&$this, 'admin_init'));
            //qtranslate category
            if(function_exists("qtrans_modifyTermFormFor")){
                add_action('chronosly_category_add_form', 'qtrans_modifyTermFormFor');
                add_action('chronosly_category_edit_form', 'qtrans_modifyTermFormFor');

            }
            $this->template = $Post_Type_Chronosly->template;

        } // END public function __construct()

    	/**
    	 * hook into WP's init action hook
    	 */
    	public function init()
    	{
            $this->settings = unserialize(get_option("chronosly-settings"));
            // Initialize Post Type
            $this->create_post_type();
            add_action('save_post', array(&$this, 'save_post'));
            add_filter('template_redirect',array(&$this,'list_all_categories'));
            add_filter( 'wp_title', array(&$this,'cat_title'), 100 );


    	} // END public function init()

        function cat_title($title) {
           if( get_query_var("chronosly_category") == "list_all_cats") $title = __('Categories', "chronosly");

            return $title;
        }

        function list_all_categories( ) {

            if( is_main_query() and get_query_var("chronosly_category") != "") {
                wp_register_style( 'chronosly-front-css'.CHRONOSLY_VERSION, CHRONOSLY_URL.'/css/front_template.css');
                wp_enqueue_style( 'chronosly-front-css'.CHRONOSLY_VERSION);
                if(file_exists(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."custom.css")){
                    wp_register_style( 'chronosly-custom-css', CHRONOSLY_URL.'/css/custom.css');
                    wp_enqueue_style( 'chronosly-custom-css');
                }
                if(!$settings["chronosly-disable-gmap-js"]) {
                    wp_register_script( 'chronosly-gmap', 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array( 'jquery' ));
                    wp_enqueue_script( 'chronosly-gmap');
                }

               wp_enqueue_script( 'jquery-ui-core');
                wp_enqueue_script( 'jquery-ui-datepicker');

                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_script('jquery-ui-tooltip');
                wp_enqueue_script('jquery-ui-resizable');
                wp_enqueue_script('jquery-ui-draggable');


                wp_register_style( 'chronosly-icons', CHRONOSLY_URL.'/css/icons/styles.css');
                wp_enqueue_style( 'chronosly-icons');
                wp_register_style( 'chronosly-fa-icons', "http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css");
                wp_enqueue_style( 'chronosly-fa-icons');

                if(!is_admin() or  stripos($_SERVER["REQUEST_URI"], "wp-admin") === FALSE){
                    wp_register_script( 'chronosly-front-js', CHRONOSLY_URL.'/js/front.js', array( 'jquery' ));
                    $translation_array	= array(
                        "weburl" => get_site_url(),
                        "calendarurl" => Post_Type_Chronosly_Calendar::get_permalink(),
                        'ajaxurl' => admin_url( 'admin-ajax.php' )

                    );
                    wp_localize_script( 'chronosly-front-js', 'translated1', $translation_array );
                    wp_enqueue_script( 'chronosly-front-js');
                }
                wp_register_style( 'chronosly-templates-base', CHRONOSLY_URL.'/css/templates_base.css');
                wp_enqueue_style( 'chronosly-templates-base');
                //templates and addons css
                do_action("chronosly_custom_frontend_css");
                $sets = unserialize(get_option("chronosly-settings"));
                if( isset($sets["chronosly-base-templates-id"]) and $sets["chronosly-base-templates-id"] != 0 and !$_REQUEST["js_render"]){
                    if(get_query_var("chronosly_category") == "list_all_cats") $_REQUEST["shortcode_categories"] = 1;
                    else $_REQUEST["shortcode_category"] = 1;
                    Post_Type_Chronosly::base_template_code("ch-category");

                    //exit;
                } else {
                    if(get_query_var("chronosly_category") == "list_all_cats") include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'archive-category-chronosly.php');
                    else {
                        add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                        include(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-category-chronosly.php');
                    }
                    exit;

                }

            }

        }

    	public function create_post_type()
    	{
            global $Post_Type_Chronosly;
            $slug = "chronosly-category";
            if($Post_Type_Chronosly->settings['chronosly-category-slug']) $slug = $Post_Type_Chronosly->settings['chronosly-category-slug'];
    		register_taxonomy(self::POST_TYPE,array("chronosly"),
    			array(
    				'labels' => array(
    					'name' => __("Event Categories", "chronosly"),
    					'singular_name' => __("Event Category", "chronosly"),
						'add_new' =>  __("Add new category", "chronosly"),
						'add_new_item' =>  __("Add new category", "chronosly"),
						'view_item' =>  __("View category", "chronosly"),


    				),
					'hierarchical' => true,
                    'rewrite' => array('slug' => $slug, 'with_front' => false, 'feeds' => true),
                    'map_meta_cap'	=> true,
                    'capability_type' => 'chronosly',
                    'capabilities' => array(
                        'publish_posts' => 'publish_chronoslys',
                        'edit_posts' => 'edit_chronoslys',
                        'edit_others_posts' => 'edit_others_chronoslys',
                        'edit_private_posts' => 'edit_private_chronoslys',
                        'edit_published_posts' => 'edit_published_chronoslys',
                        'delete_posts' => 'delete_chronoslys',
                        'delete_others_posts' => 'delete_others_chronoslya',
                        'read_private_posts' => 'read_private_chronoslys',
                        'delete_private_posts' => 'delete_private_chronoslys',
                        'delete_published_posts' => 'delete_published_chronoslys',
                        'edit_post' => 'edit_chronosly',
                        'delete_post' => 'delete_chronosly',
                        'read_post' => 'read_chronosly',
                    ),
                    'hierarchical' => true,
                    'has_archive'	=> $slug,
                    'capability' => 'chronosly_author',
                    'has_archive' => true,
    				'public' => true,
					'sort' => true,

    			)
    		);
            add_rewrite_rule($slug.'/?$','index.php?chronosly_category=list_all_cats','top');
            add_rewrite_rule($slug.'/page/([0-9]{1,})/?$','index.php?chronosly_category=list_all_cats&paged=$matches[1]','top');
            if(isset($Post_Type_Chronosly->settings['chronosly-allow-flush']) and $Post_Type_Chronosly->settings['chronosly-allow-flush'] and !$Post_Type_Chronosly->settings['chronosly-cats-flushed']) {
                flush_rewrite_rules();
                $Post_Type_Chronosly->settings['chronosly-cats-flushed'] = 1;
                update_option('chronosly-settings', serialize($Post_Type_Chronosly->settings));

            }
           // add_filter( 'map_meta_cap', array("Post_Type_Chronosly",'chronosly_map_meta_cap'), 10, 4 );
            add_filter( 'template_include', array("Post_Type_Chronosly",'chronosly_templates') );
    	}

    	/**
    	 * Save the metaboxes for this custom post type
    	 */
    	public function save_post($post_id)
    	{
            // verify if this is an auto save routine.
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }
            // handle the case when the custom post is quick edited
            // otherwise all custom meta fields are cleared out
            if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
                return;

            if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    		{
                Chronosly_Cache::delete_item($post_id);

                foreach($this->_meta as $field_name)
    			{
    				// Update the post's meta field
    				update_post_meta($post_id, $field_name, $_POST[$field_name]);
    			}
    		}
    		else
    		{
    			return;
    		} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    	} // END public function save_post($post_id)

        // A callback function to save our extra taxonomy field(s)
        public function save_taxonomy_custom_fields( $term_id ) {
            $t_id = $term_id;
            Chronosly_Cache::delete_item($term_id);

            $metas = array(
                "featured"=>$_POST["featured"],
                "order"=> $_POST["order"],
                "cat-color"=> $_POST["cat-color"],

            );
            update_option('chronosly-taxonomy_'.$t_id, serialize($metas));

            }


    	/**
    	 * hook into WP's admin_init action hook
    	 */
    	public function admin_init()
    	{
    		// Add metaboxes
            add_action( 'chronosly_category_edit_form', array(&$this, 'call_metabox'), 10, 2 );

            // Save the changes made on the taxonomy, using our callback function
            add_action( 'edited_chronosly_category',  array(&$this,'save_taxonomy_custom_fields'), 10, 2 );
            add_action( 'create_chronosly_category',  array(&$this,'save_taxonomy_custom_fields'), 10, 2 );
            add_filter("manage_edit-chronosly_category_columns", array(&$this, 'tax_columns'));
            add_filter( 'manage_chronosly_category_custom_column',  array(&$this,'manage_admin_columns' ),10, 3);



        } // END public function admin_init()



        function tax_columns($columns) {
            $columns["ch-color"] = __("Color", "chronosly");
            return $columns;
        }
        function manage_admin_columns($out, $column_name, $tax_id) {

            switch ($column_name) {
                case 'ch-color':
                    $settings = unserialize(get_option("chronosly-settings"));
                    $metas = unserialize(get_option('chronosly-taxonomy_'.$tax_id));
                    if($metas["cat-color"]) $color = $metas["cat-color"];
                    else $color = $settings["chronosly_category_color"];
                    $out .= '<span class="cat-color" style="background:'.$color.'" ></span>';
                    break;

                default:
                    break;
            }
            return $out;
        }

        public function call_metabox($term){
            global $post, $Post_Type_Chronosly;
          $Post_Type_Chronosly->inicia_scripts();

            echo "<br/><h2>".__("Chronosly Configuration", "chronosly")."</h2>";
            // Render the job order metabox

                //set de defaults vars for custmize contents
                    // put the term ID into a variable
            $t_id = $term->term_id;
            register_setting('chronosly-group', 'chronosly-taxonomy_'.$t_id);
            if(!get_option('chronosly-taxonomy_'.$t_id)){
                $metas = array(
                    "feature"=>"",
                    "order"=>"",
                    "cat-color"=>"",

                );
                update_option('chronosly-taxonomy_'.$t_id, serialize($metas));
            }
            if(!get_option('chronosly-taxonomy-dad11_'.$t_id)){
                $metas = "";

                update_option('chronosly-taxonomy-dad11_'.$t_id, $metas);
            }
            if(!get_option('chronosly-taxonomy-dad12_'.$t_id)){
                $metas = "";

                update_option('chronosly-taxonomy-dad12_'.$t_id, $metas);
            }
                    // retrieve the existing value(s) for this meta field. This returns an array
                    $vars = unserialize(get_option('chronosly-taxonomy_'.$t_id ));
           // print_r($vars);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."chronosly_cat_vars_metabox.php");

                    //cargando vistas
            if( $this->settings["chronosly_template_editor"]){

                    $vistas = array(
                        "dad11" => __("All categories list view", "chronosly"),
                        "dad12" => __("Single category view", "chronosly"),

                    );
                    //drag and drop 11
                    //get custom templates
                    if ($handle = opendir(CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR)) {

                        while (false !== ($entry = readdir($handle))) {
                            if($entry != "." and $entry != "..") $custom_templates[] = str_replace(".php", "",$entry);

                        }

                        closedir($handle);
                    }


                    $dadcats = array();









                    $perfil = $this->settings['chronosly_tipo_perfil'];
                    $selected_template = $this->template->get_tipo_template($t_id, "dad11");
                    if($selected_template == "chbd")  $selected_template = "template_edited";
                    $select_options =  $this->template->build_templates_select($this->template->get_file_templates($t_id, "dad11",$perfil), $selected_template);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad11_select_metabox.php");
                    //load custom or default template
                    //$this->template->set_post($post);
                    $template = $this->template->print_template($t_id, "dad11", $dadcats);


                    //save or overwrite template
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad11_save_metabox.php");
                    //print_r($GLOBALS);
            }


        }

    	/**
    	 * hook into WP's add_meta_boxes action hook
    	 */
    /*	public function add_meta_boxes()
    	{
    		// Add this metabox to every selected post
            add_meta_box(
                sprintf('chronosly_%s_cat_vars_section', self::POST_TYPE),
                __('Category extra fields', 'chronosly'),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE,
                'normal',
                'low',
                array('type' => 'cat_vars', "post" => $post)

            );
            add_meta_box(
                sprintf('chronosly_%s_dad11_section', self::POST_TYPE),
                __('Category page template', 'chronosly'),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE,
                'normal',
                'low',
                array('type' => 'dad11', "post" => $post)

            );
    	} // END public function add_meta_boxes()


		public function add_inner_meta_boxes($post, $metabox)
		{
            global $Post_Type_Chronosly;
            $post = $metabox['args']['post'];//solve problematical posts ids

            // Render the job order metabox
            if(count($metabox['args']) and isset($metabox['args']['type'])){
                //set de defaults vars for custmize contents

                if('cat_vars' == $metabox['args']['type']){
                    $vars = @get_post_meta($post->ID);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."chronosly_cat_vars_metabox.php");
                }
                else if('dad11' == $metabox['args']['type']){
                    //cargando vistas

                    $vistas = array(
                        "dad7" => __("All organizers list view", "chronosly"),
                        "dad8" => __("Single organizer view", "chronosly"),

                    );
                    //drag and drop 7
                    //get custom templates
                    if ($handle = opendir(CHRONOSLY_TEMPLATES_PATH.DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR)) {

                        while (false !== ($entry = readdir($handle))) {
                            if($entry != "." and $entry != "..") $custom_templates[] = str_replace(".php", "",$entry);

                        }

                        closedir($handle);
                    }


                    $dadcats = array();
                    $id = $post->ID;
                    $dadcats[__("Category", "chronosly")][__("name", "chronosly")] = array(__("Value", "chronosly") => $id,  "type" => "organizer-name", "format" => "hidden");
                    $dadcats[__("Category", "chronosly")][__("description", "chronosly")] = array(__("Value", "chronosly") => $id,  "type" => "organizer-description", "format" => "hidden");

                    $dadcats[__("Other", "chronosly")] = array(
                        __("Custom text", "chronosly") => array(
                            __("Value", "chronosly") => "" , "type" =>"c-text")
                    ,__("Custom text box", "chronosly") => array(
                            __("Value", "chronosly") => "", "type" => "c-textarea", "format" => "wyswyg")
                    ,__("Custom link", "chronosly") => array(
                            __("Url", "chronosly") => "", "type" => "link", "format" => "hidden")
                    ,__("Custom code", "chronosly") => array(
                            __("Value", "chronosly") => "", "type" => "code")
                    ,__("Custom html tags", "chronosly") => array(
                            __("Html tags", "chronosly") => "", "type" => "html")

                    );

                    $dadcats[__("Images", "chronosly")] = array(
                       __("Custom image", "chronosly") => array(
                            __("Source", "chronosly") => "", "type" => "c-image", "format" => "image")
                    ,__("Gallery", "chronosly") => array(
                            __("Gallery code", "chronosly") => "", "type" => "gallery", "format" => "gallery")
                    );










                    //select template
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad11_select_metabox.php");
                    //load custom or default template
                    $this->template->set_post($post);

                    $template = @get_post_meta($post->ID, "dad7", true);
                    if(!$template) $this->template->render_template("default", "back", 'dad11', "", $dadcats );
                    else if($template) {
                        $this->template->render_template("dad_framework", "back", 0, json_decode($template), $dadcats );
                    }

                    //save or overwrite template
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad11".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad11_save_metabox.php");
                    //print_r($GLOBALS);
                }
            }

         } // END public function add_inner_meta_boxes($post)
        */
	} // END class Post_Type_Template
} // END if(!class_exists('Post_Type_Template'))
