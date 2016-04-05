<?php
if (!class_exists('Post_Type_Chronosly')) {
    /**
     * A PostTypeTemplate class that provides 3 additional meta fields
     */


    class Post_Type_Chronosly
    {

        const POST_TYPE	= "chronosly";

        private $_meta = array(
            'organizer',
            'places',
            'calendar',
            'ev-from',
            'ev-from-h',
            'ev-from-m',
            'ev-to',
            'ev-to-h',
            'ev-to-m',
            'ev-repeat',
            'ev-repeat-every',
            'ev-repeat-option',
            'ev-until',
            'ev-end_count',
            'featured',
            'order',
            'tickets',
            'events',
            'seasons'
        );
        public $template, $settings;

        /**
         * The Constructor
         */
        public function __construct($template)
        {
            // register actions
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'admin_init'));
             add_action( 'wp_head', array(&$this,'insert_og_in_head'), 5 );
            // add_action('admin_head', array(&$this, 'load_vars'));

            add_action( 'admin_enqueue_scripts',  array(&$this,'add_admin_scripts') );
            add_filter('body_class', array(&$this,'chronosly_body_class'));



            $this->template = $template;
        } // END public function __construct()

        /**
         * hook into WP's init action hook
         */
        public function init()
        {
            // Initialize Post Type
            //Cargamos los settings
            $this->settings = unserialize(get_option("chronosly-settings"));
            //creamos el post type
            $this->create_post_type();

            //Si hemos configurado una base, debemos cargar los templates a partir de shortcode base
            if( isset($this->settings["chronosly-base-templates-id"]) and $this->settings["chronosly-base-templates-id"] != 0 and !isset($_REQUEST["js_render"])){

                add_filter('single_template', array(&$this,'base_template_code'),1,1);
                add_filter('archive_template', array(&$this,'base_template_code'),1,1);
            }
            add_action('save_post', array(&$this, 'save_post'));




        } // END public function init()

        function add_admin_scripts( $hook ) {

            global $post, $taxonomy;
            if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit.php' || $hook == 'edit-tags.php' ) {
                if ( stripos($post->post_type, 'chronosly') !== false || (is_string($taxonomy) and stripos($taxonomy, 'chronosly') !== false )) {

                wp_print_scripts(  'jquery-ui-core', get_bloginfo("url").'/wp-includes/js/ui/core-min.js' );
                wp_print_scripts(  'jquery-ui-datepicker', get_bloginfo("url").'/wp-includes/js/ui/datepicker-min.js' );
                wp_print_scripts(  'jquery-ui-tabs', get_bloginfo("url").'/wp-includes/js/ui/tabs-min.js' );
                wp_print_scripts(  'jquery-ui-tooltip', get_bloginfo("url").'/wp-includes/js/ui/tootltip-min.js' );
                wp_print_scripts(  'jquery-ui-resizable', get_bloginfo("url").'/wp-includes/js/ui/resizable-min.js' );
                wp_print_scripts(  'jquery-ui-draggable', get_bloginfo("url").'/wp-includes/js/ui/draggable-min.js' );

                wp_enqueue_script(  'chronosly-quickedit', CHRONOSLY_URL.'/js/admin-quickedit.js' );
                wp_enqueue_script(  'chronosly-spectrum', CHRONOSLY_URL.'/js/spectrum/spectrum.js' );
                wp_enqueue_style(  'chronosly-template', CHRONOSLY_URL.'/css/admin_template1.css' );
                wp_enqueue_style(  'chronosly-spectrum', CHRONOSLY_URL.'/js/spectrum/spectrum.css' );
                wp_enqueue_style(  'chronosly-jquery-ui', CHRONOSLY_URL.'/css/smoothness/jquery-ui-1.10.4.custom.css' );
                }
            }
        }



        /**
         * Create the post type
         */
        public function create_post_type()
        {
            $slug = "chronosly";
            if($this->settings['chronosly-slug']) $slug = $this->settings['chronosly-slug'];
            add_rewrite_rule($slug.'/(.+?)/repeat_([0-9]+)_([0-9]+)/?$','index.php?chronosly=$matches[1]&repeat=$matches[2]_$matches[3]','top');
           add_rewrite_rule($slug.'/(.+?)/repeat_([0-9]+)_([0-9]+)_([0-9]+)_([0-9]+)/?$','index.php?chronosly=$matches[1]&repeat=$matches[2]_$matches[3]&season=$matches[4]_$matches[5]','top');
             add_filter('query_vars',  array("Post_Type_Chronosly",'add_query_vars'));

            //echo "<br><BR><br><br>".$slug;
            register_post_type(self::POST_TYPE,
                array(
                    'labels' => array(
                        'name' => __("Events", "chronosly"),
                        'singular_name' => __("Event", "chronosly"),
                        'add_new' =>  __("Add new event", "chronosly"),
                        'add_new_item' =>  __("Add new event", "chronosly"),
                        'view_item' =>  __("View event", "chronosly"),
                        'search_items' =>  __("Search event", "chronosly"),


                    ),
                    'rewrite' => array('slug' => $slug, 'with_front' => false, 'feeds' => true),
                    'public' => true,
                    'show_ui' => true,
                    'map_meta_cap'	=> true,
                    'capability_type' => 'chronosly',
                    'capabilities' => array(
                        'publish_posts' => 'publish_chronoslys',
                        'edit_posts' => 'edit_chronoslys',
                        'edit_others_posts' => 'edit_others_chronoslys',
                        'edit_private_posts' => 'edit_private_chronoslys',
                        'edit_published_posts' => 'edit_published_chronoslys',
                        'delete_posts' => 'delete_chronoslys',
                        'delete_others_posts' => 'delete_others_chronoslys',
                        'read_private_posts' => 'read_private_chronoslys',
                        'delete_private_posts' => 'delete_private_chronoslys',
                        'delete_published_posts' => 'delete_published_chronoslys',
                        'edit_post' => 'edit_chronosly',
                        'delete_post' => 'delete_chronosly',
                        'read_post' => 'read_chronosly',
                    ),
                    'hierarchical' => true,
                    'has_archive'	=> true,
                    'menu_position' => 20,
                    'menu_icon' => 'dashicons-calendar',
                    'capability' => 'chronosly_author',
                    'description' => __("Events type for managing your events", "chronosly"),
                    'query_var' => true,
                    'supports' => array(
                        'title', 'editor','excerpt','thumbnail'
                    )
                )
            );
            if(isset($this->settings) and isset($this->settings['chronosly-allow-flush']) and $this->settings['chronosly-allow-flush'] and !$this->settings['chronosly-events-flushed']) {
                flush_rewrite_rules();
                $this->settings['chronosly-events-flushed'] = 1;
                update_option('chronosly-settings', serialize($this->settings));
            }
              add_filter( 'map_meta_cap', array(&$this,'chronosly_map_meta_cap'), 10, 4 );
            add_filter( 'template_include', array(&$this,'chronosly_templates') );
            //add_filter( 'the_content', array(&$this,'chronosly_templates_content') );
            //add_filter( 'the_title', array(&$this,'chronosly_templates_title') );


            $this->add_caps();


        }


        public static function add_query_vars($aVars) {

            $aVars[] = "repeat"; // represents of the repeats

            return $aVars;
        }


        //EN principio no se utiliza pues tenemos el element position
        public static  function add_custom_orderby( $orderby ) {
            global $wpdb, $pastformat, $wp_query ;
            //set the order
            if(has_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') )) remove_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') );

            $order =array();
            $settings = unserialize(get_option("chronosly-settings"));
            $metas = Post_Type_Chronosly::add_custom_post_vars_call($wp_query , true);
            $metaquery = $metas["meta_query"];
            $taxquery = $metas["tax_query"];
            $limit =  ((isset($_REQUEST["count"]) and $_REQUEST["count"])?$_REQUEST["count"]:$settings["chronosly_events_x_page"]);

            if((!isset($settings["chronosly_show_past_events"]) or !$settings["chronosly_show_past_events"]) and (!isset($_REQUEST["ch_show_past"]) or !$_REQUEST["ch_show_past"]) and (!isset($_REQUEST["ch_from"]) or !isset($_REQUEST["ch_to"]))) {
                for($i= 0; $i < count($metaquery); ++$i){
                    foreach($metaquery[$i] as $k => $val){
                        if($k == "key" and $val == "ev-to") {
                            $metaquery[$i] = array("key" => "ev-to", "value" => date("Y-m-d"), 'compare' => '>=');
                        }
                    }
                }

            }

            $ordertype = (isset($_REQUEST["orderby"]))?$_REQUEST["orderby"]:$settings["chronosly_events_order"];
            $orderdir = (isset($_REQUEST["orderdir"]))?$_REQUEST["orderdir"]:$settings["chronosly_events_orderdir"];

            switch($ordertype ){
                case "date":
                    if($pastformat){
                        $order = array('meta_key' => "ev-from",
                            'orderby' => 'meta_value', 'order' => 'DESC');
                    } else {
                        $order = array('meta_key' => "ev-from",
                            'orderby' => 'meta_value', 'order' => $orderdir);
                    }

                    break;
                case "order":
                    $order = array('meta_key' => "order",
                        'orderby' => 'meta_value_num', 'order' => $orderdir);
                    $meta = array('key' => 'order');
                    break;
                case "event":
                    $order = array('orderby' => 'title', 'order' => $orderdir);
                    $meta = "";
                    break;
                 case "category":
                     $order = array('meta_key' => "chronosly_category",'orderby' => array('meta_value' , 'title'));
                 break;
             /*    case "organizer":
                     $order = "organizer";
                 break;
                 case "place":
                     $order = "place";
                 break;
                 case "price":
                     $order = "ticket";
                 break;*/

            }
            if(isset($_REQUEST["orderby"]) and isset($_REQUEST["orderdir"])){
                if(isset($meta)) $metaquery[] = $meta;

                $metaquery1 =  $metaquery;

                $args = array(
                        'post_type' => 'chronosly',
                        'post_status'      => 'publish',
                        'order' => $_REQUEST["orderdir"],
                        'posts_per_page'   => -1,
                        'numberposts'       => -1,
                        "meta_query" => $metaquery1,
                        "tax_query" => $taxquery


                    )+$order;
                if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');
            }
            else if($settings["chronosly_featured_first"] and !$pastformat and !isset($_REQUEST["featured"])){




                if(isset($meta)) $metaquery[] = $meta;

                $metaquery1 =  $metaquery;
                $metaquery1[]=  array(
                    'key' => 'featured',
                    'value' => "",
                    'compare' => '='
                );

                $args = array(
                        'post_type' => 'chronosly',
                        'post_status'      => 'publish',
                         'order' => $orderdir,
                        'posts_per_page'   => -1,
                        'numberposts'       => -1,
                        "meta_query" => $metaquery1,
                        "tax_query" => $taxquery


                    )+$order;
                if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

                $normal_args = $args;

                $metaquery2 =  $metaquery;
                $metaquery2[]=  array(
                    'key' => 'featured',
                    'value' => "",
                    'compare' => '='
                );



                $args = array(
                        'post_type' => 'chronosly',
                        'post_status'      => 'publish',
                        'order' => $orderdir,
                        'posts_per_page'   => -1,
                        'numberposts'       => -1,
                        "meta_query" => $metaquery2,
                        "tax_query" => $taxquery
                    )+$order;
                if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

                $featured_args = $args;
                $normal = get_posts( $normal_args );
                $featured = get_posts( $featured_args );

                //foreach($featured as $f) echo $f->ID." ";
                //foreach($normal as $f) echo $f->ID." ";
                $posts =  (array) array_merge((array) $featured,(array) $normal);
                // echo "<pre>";print_r($normal);
                if ( $posts ) {

                    // add custom ordering
                    $sql = ' CASE';
                    $i = count( $posts );
                    foreach ( $posts as $post ) {
                        $sql .= " WHEN $wpdb->posts.ID = $post->ID THEN $i";
                        $i--;
                    }
                    $sql .= ' ELSE 0 END DESC';

                    $orderby = $sql ;
                }

            }
            else {

                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $args = array(
                        'post_type' => 'chronosly',
                        'post_status'      => 'publish',
                        'order' => $orderdir,
                        'posts_per_page'   => -1,
                        'numberposts'       => -1,
                        "meta_query" => $metaquery,
                        "tax_query" => $taxquery

                    )+$order;
               /* if($_REQUEST["debu"]) {
                    echo "<pre>";
                    print_r($metaquery);
                }*/
                if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

                $normal_args = $args;



                $normal = get_posts( $normal_args );

                $posts =  (array)  $normal;


                if ( $posts ) {

                    // add custom ordering
                    $sql = ' CASE';
                    $i = count( $posts );
                    foreach ( $posts as $post ) {
                        $sql .= " WHEN $wpdb->posts.ID = $post->ID THEN $i";
                        $i--;
                    }
                    $sql .= ' ELSE 0 END DESC';

                    $orderby = $sql ;
                }

            }


              //echo $orderby;
            return $orderby;
        }

        //shoortcoding functions





        //set some filteres added in settings
        public static function add_custom_post_vars( $query ) {
            Post_Type_Chronosly::add_custom_post_vars_call($query, false);
            /*print_r($query);*/

        }

     public static function add_custom_post_vars_call($query, $return){
            global $pastformat;
            $past_set = 0;

            if($query->get('post_type') !=  "chronosly" and $query->get('post_type') !=  "chronosly_category" and !$query->get('chronosly_category') and $query->get('post_type') !=  "chronosly_tag" and !$query->get('chronosly_tag')) return 0;
            $settings = unserialize(get_option("chronosly-settings"));
            if((!isset($settings["chronosly_show_past_events"]) or !$settings["chronosly_show_past_events"]) and (!isset($_REQUEST["ch_show_past"]) or !$_REQUEST["ch_show_past"]) and (!$_REQUEST["ch_from"] or !$_REQUEST["ch_to"])) {
                $meta_query = $query->get('meta_query');
                $meta_query[] = array("key" => "ev-to", "value" => date("Y-m-d"), 'compare' => '>=');
                if(!$return) $query->set('meta_query',$meta_query);
            }
            if($_REQUEST["ch_from"] and $_REQUEST["ch_to"]){

                $fromc = $_REQUEST["ch_to"];
                $toc = $_REQUEST["ch_from"];
                $meta_query = $query->get('meta_query');
                $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                if(!$return) $query->set('meta_query',$meta_query);

            } else {
                $listado = ((isset($_REQUEST["chronosly_event_list_format"]) and $_REQUEST["chronosly_event_list_format"])?$_REQUEST["chronosly_event_list_format"]:$settings["chronosly_event_list_format"]);

                $time =  ((isset($_REQUEST["chronosly_event_list_time"]) and $_REQUEST["chronosly_event_list_time"])?$_REQUEST["chronosly_event_list_time"]:$settings["chronosly_event_list_time"]);
                switch($listado){
                    case "year":
                        if(!$time or $time == "current"){
                            $fromc =  date("Y-12-31");
                            $toc = date("Y-01-01");
                        } else {
                            if($pastformat){
                                $fromc =  date("Y-m-d", time() - 60 * 60 * 24);
                                $toc = date("Y")."-01-01";

                           }
                            else{
                                $fromc =  "$time-12-31";
                                $toc = "$time-01-01";
                            }
                        }
                        $meta_query = $query->get('meta_query');
                        $evtoset = 0;
                        $evfromset = 0;
                        for($i = 0; $i < count($meta_query); ++$i){
                            if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-to") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evtoset = 1;
                            }
                            else if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-from") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evfromset = 1;
                            }

                        }
                        if(!$evtoset) $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                        if(!$evfromset) $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                        if(!$return) $query->set('meta_query',$meta_query);
                        break;
                    case "month":
                        if(!$time  or $time == "current"){
                            $fromc =  date("Y-m-t");
                            $toc = date("Y-m-01");
                        } else {
                            if($pastformat){
                                $fromc =  date("Y-m-d", time() - 60 * 60 * 24);
                                $toc = date("Y-m-01");
                            }
                            else{
                                if((int)$time < 10) $time = "0".$time;
                                $y = "Y";
                                if($_REQUEST["y"]) $y = $_REQUEST["y"];
                                $fromc =  date("Y-m-t", strtotime(date("$y-$time-01")));
                                $toc = date("$y-$time-01");
                            }
                        }
                        $meta_query = $query->get('meta_query');
                        $$evtoset = 0;
                        $evfromset = 0;
                        for($i = 0; $i < count($meta_query); ++$i){
                            if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-to") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evtoset = 1;
                            }
                            else if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-from") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evfromset = 1;
                            }

                        }
                        if(!$evtoset) $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                        if(!$evfromset) $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                        if(!$return) $query->set('meta_query',$meta_query);
                        break;
                    case "week":
                        if(!$time  or $time == "current"){
                            $monday = strtotime('last monday', strtotime('tomorrow'));
                            if($settings["chronosly_week_start"] == 1) {
                                $monday -= (60*60*24);
                            }
                            $fromc =  date("Y-m-d",$monday+(6*60*60*24));
                            $toc = date("Y-m-d",$monday);

                        } else {
                            if($pastformat){
                                $monday = strtotime('last monday', strtotime('tomorrow'));
                                if($settings["chronosly_week_start"] == 1) {
                                    $monday -= (60*60*24);
                                }
                                $fromc =  date("Y-m-d", time() - 60 * 60 * 24);
                                $toc = date("Y-m-d",$monday);
                            }
                            else {
                                $y = date("Y");
                                if($_REQUEST["y"]) $y = $_REQUEST["y"];
                                $monday = strtotime($y . 'W' . str_pad($time, 2, '0', STR_PAD_LEFT));
                                if($settings["chronosly_week_start"] == 1) {
                                    $monday -= (60*60*24);
                                }
                                $fromc =  date("Y-m-d",$monday+(6*60*60*24));
                                $toc = date("Y-m-d", $monday);
                            }
                        }
                        $meta_query = $query->get('meta_query');
                        $evtoset = 0;
                        $evfromset = 0;
                        for($i = 0; $i < count($meta_query); ++$i){
                            if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-to") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evtoset = 1;
                            }
                            else if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-from") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evfromset = 1;
                            }

                        }
                        if(!$evtoset) $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                        if(!$evfromset) $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                        if(!$return) $query->set('meta_query',$meta_query);
                        break;
                    case "day":
                        if(!$time  or $time == "current"){
                            $fromc =  date("Y-m-d");
                            $toc = date("Y-m-d");
                        }
                        else if($pastformat){
                            $fromc =  date("Y-m-d",time() - 60 * 60 * 24);
                            $toc = date("Y-m-d",time() - 60 * 60 * 24);
                        }
                        else {
                            $fromc =  $time;
                            $toc = $time;
                        }
                        $meta_query = $query->get('meta_query');
                        $evtoset = 0;
                        $evfromset = 0;
                        for($i = 0; $i < count($meta_query); ++$i){
                            if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-to") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evtoset = 1;
                            }
                            else if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-from") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evfromset = 1;
                            }

                        }
                        if(!$evtoset) $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                        if(!$evfromset) $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                        if(!$return) $query->set('meta_query',$meta_query);
                        break;
                    case "upcoming":
                        if(!$time){
                            if($pastformat){
                                $toc =  date("Y-m-d", strtotime("-1 week"));
                                $fromc = date("Y-m-d");

                            }
                            else {
                                $fromc =  date("Y-m-d", strtotime("+1 week"));
                                if($settings["chronosly_show_past_events"]) $toc =  date("Y-m-d", strtotime("-1 week"));
                                else $toc = date("Y-m-d");
                            }

                        } else {
                            if($pastformat){
                                $toc =  date("Y-m-d",strtotime("-5000 day"));
                                $fromc = date("Y-m-d");

                            }
                            else {
                                $fromc =  date("Y-m-d",strtotime("+$time day"));
                                if((!isset($_REQUEST["ch_show_past"]) and $settings["chronosly_show_past_events"])  or $_REQUEST["ch_show_past"]) $toc =  date("Y-m-d",strtotime("-$time day"));
                                else $toc = date("Y-m-d");
                                // echo $toc;
                                // print_r($settings);
                            }
                        }
                        $meta_query = $query->get('meta_query');
                        $evtoset = 0;
                        $evfromset = 0;
                        for($i = 0; $i < count($meta_query); ++$i){
                            if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-to") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evtoset = 1;
                            }
                            else if(isset($meta_query[$i]["key"]) and $meta_query[$i]["key"] == "ev-from") {
                                // $meta_query[$i] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                                $evfromset = 1;
                            }

                        }
                        if(!$evtoset) $meta_query[] = array("key" => "ev-to", "value" => $toc, 'compare' => '>=');
                        if(!$evfromset) $meta_query[] = array("key" => "ev-from", "value" => $fromc, 'compare' => '<=');
                        if(!$return) $query->set('meta_query',$meta_query);
                        break;
                }
            }

            if(isset($_REQUEST["category"]) and $_REQUEST["category"]){
                $cats = explode(",",$_REQUEST["category"]);
                $tax_query = array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'chronosly_category',
                        'field' => 'id',
                        'terms' => $cats
                    ));
                if(!$return) $query->set('tax_query',$tax_query);


            }
           // if(has_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars') )) remove_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars') );
        // print_r($meta_query);
        // print_r($query);
            if($return) return array("meta_query" => $meta_query, "tax_query" => $tax_query);
        }

        //events with repeat enabled
        public static function get_events_repeated_by_date($limit, $paged, $extra=array()){
            global $wp_query,$pastformat;
            $settings = unserialize(get_option("chronosly-settings"));
            $listado = ((isset($_REQUEST["chronosly_event_list_format"]) and $_REQUEST["chronosly_event_list_format"])?$_REQUEST["chronosly_event_list_format"]:$settings["chronosly_event_list_format"]);
            $time =  ((isset($_REQUEST["chronosly_event_list_time"]) and $_REQUEST["chronosly_event_list_time"])?$_REQUEST["chronosly_event_list_time"]:$settings["chronosly_event_list_time"]);
            $fromc = "";
            switch($listado){
                case "year":
                    if(!$time or $time == "current"){
                        $fromc =  date("Y-12-31");
                    } else {
                        $fromc =  "$time-12-31";
                    }

                    break;
                case "month":
                    if(!$time or $time == "current"){
                        $fromc =  date("Y-m-t");
                    } else {
                        if((int)$time < 10) $time = "0".$time;
                        $y = "Y";
                        if($_REQUEST["y"]) $y = $_REQUEST["y"];
                        $fromc =  date("Y-m-t", strtotime(date("$y-$time-01")));
                    }

                    break;
                case "week":
                    if(!$time or $time == "current"){
                        $monday = strtotime('last monday', strtotime('tomorrow'));
                        if($settings["chronosly_week_start"] == 1) {
                            $monday -= (60*60*24);
                        }
                        $fromc =  date("Y-m-d",$monday+(6*60*60*24));

                    } else {
                        $y = date("Y");
                        if($_REQUEST["y"]) $y = $_REQUEST["y"];
                        $monday = strtotime($y . 'W' . str_pad($time, 2, '0', STR_PAD_LEFT));
                        if($settings["chronosly_week_start"] == 1) {
                            $monday -= (60*60*24);
                        }
                        $fromc =  date("Y-m-d",$monday+(6*60*60*24));
                    }

                    break;
                case "day":
                    if(!$time or $time == "current"){
                        $fromc =  date("Y-m-d");
                    } else {
                        $fromc =  $time;
                    }
                    break;
                case "upcoming":
                    if(!$time){
                        $fromc =  date("Y-m-d", strtotime("+1 week"));
                    } else {
                        $fromc =  date("Y-m-d",strtotime("+$time day"));
                    }

                    break;
            }
            if($pastformat) $fromc =  date("Y-m-d");
            if($_REQUEST["ch_from"] and $_REQUEST["ch_to"]){
                $fromc = $_REQUEST["ch_to"];
            }
                $metaquery = array(
                    'relation' => "AND",
                    array(
                        'key' => 'ev-repeat',
                        'value' => "",
                        'compare' => '!='
                    ),
                    array(
                        'key' => 'ev-from',
                        'value' => $fromc,
                        'compare' => '<='
                    )
                );

            $args  = array(
                'numberposts'       => -1,
                'posts_per_page'       => -1,
                'nopaging'       => true,

                'category'         => '',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_query' => $metaquery,
                'post_type'        => 'chronosly',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'post_status'      => 'publish'
            );
            //echo "<pre>";print_r($    args);
            foreach($wp_query->query_vars["meta_query"] as $id=>$meta){
                if($meta["key"] == "ev-from") unset($wp_query->query_vars["meta_query"][$id]);
            }
             // echo "<pre>";print_r($wp_query->query_vars);
            $args = array_merge($wp_query->query_vars, $args);
            $args["name"] = "";
            $args["pagename"] = "";
            // echo "<pre>";print_r($args);
            // echo "<pre>";print_r($wp_query->query_vars);
            if(count($extra)){
                if(isset($extra["meta_query"])) $args["meta_query"][] = $extra["meta_query"];
                else $args += $extra;

            }
            if(isset($_REQUEST["category"]) and $_REQUEST["category"]){
                $cats = explode(",",$_REQUEST["category"]);

                $args['tax_query'] = array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'chronosly_category',
                        'field' => 'id',
                        'terms' => $cats
                    ));

            }
            if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');
            if($_REQUEST["ch-price-min"]) $args["post_meta_price_min"] = $_REQUEST["ch-price-min"];
            if($_REQUEST["ch-price-max"]) $args["post_meta_price_max"] = $_REQUEST["ch-price-max"];
             if(has_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars') )) remove_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars') );
            $query2 = new WP_Query( $args );

          // if($_REQUEST["debug"]){echo "<pre>"; print_r($query2);}
            return  $query2;
        }

        //devuelve el array con los eventos ordenados por dia
        public static function get_days_by_date($query,$repeated,$limit, $paged){
            global $pastformat;
            $settings = unserialize(get_option("chronosly-settings"));

            $listado = ((isset($_REQUEST["chronosly_event_list_format"]) and $_REQUEST["chronosly_event_list_format"])?$_REQUEST["chronosly_event_list_format"]:$settings["chronosly_event_list_format"]);
            $time =  ((isset($_REQUEST["chronosly_event_list_time"]) and $_REQUEST["chronosly_event_list_time"])?$_REQUEST["chronosly_event_list_time"]:$settings["chronosly_event_list_time"]);

            switch($listado){
                case "year":
                    if(!$time or $time == "current"){
                        $fromc =  date("Y-12-31");
                        $toc = date("Y-01-01");
                    } else {

                        if($pastformat) $fromc =  date("Y-m-d");
                        else $fromc =  "$time-12-31";
                        $toc = "$time-01-01";
                    }

                    break;
                case "month":
                    if(!$time or $time == "current"){

                        $fromc =  date("Y-m-t");
                        $toc = date("Y-m-01");
                    } else {
                        if((int)$time < 10) $time = "0".$time;
                        $y = "Y";
                        if($_REQUEST["y"]) $y = $_REQUEST["y"];
                        if($pastformat) $fromc =  date("Y-m-d");
                        else $fromc =  date("Y-m-t", strtotime(date("$y-$time-01")));
                        $toc = date("$y-$time-01");
                    }

                    break;
                case "week":
                    if(!$time or $time == "current"){
                        $monday = strtotime('last monday', strtotime('tomorrow'));
                        if($settings["chronosly_week_start"] == 1) {
                            $monday -= (60*60*24);
                        }
                        $fromc =  date("Y-m-d",$monday+(6*60*60*24));
                        $toc = date("Y-m-d",$monday);

                    } else {
                        $y = date("Y");
                        if($_REQUEST["y"]) $y = $_REQUEST["y"];
                        $monday = strtotime($y . 'W' . str_pad($time, 2, '0', STR_PAD_LEFT));
                        if($settings["chronosly_week_start"] == 1) {
                            $monday -= (60*60*24);
                        }
                        if($pastformat) $fromc =  date("Y-m-d");
                        else $fromc =  date("Y-m-d",$monday+(6*60*60*24));
                        $toc = date("Y-m-d", $monday);
                    }

                    break;
                case "day":
                    if(!$time or $time == "current"){
                        $fromc =  date("Y-m-d");
                        $toc = date("Y-m-d");
                    } else {
                        $fromc =  $time;
                        $toc = $time;
                    }
                    break;
                case "upcoming":
                    if(!$time){
                        $fromc =  date("Y-m-d", strtotime("+1 week"));
                        $toc = date("Y-m-d");
                    } else {
                       if($pastformat) {
                            $toc =  date("Y-m-d",strtotime("-5000 day"));
                            $fromc = date("Y-m-d");
                        }
                        else {
                            $fromc =  date("Y-m-d",strtotime("+$time day"));
                            if((!isset($_REQUEST["ch_show_past"]) and $settings["chronosly_show_past_events"])  or $_REQUEST["ch_show_past"]) $toc =  date("Y-m-d",strtotime("-$time day"));
                            else $toc = date("Y-m-d");
                        }
                    }

                    break;
            }
            if((!isset($settings["chronosly_show_past_events"]) or !$settings["chronosly_show_past_events"]) and (!isset($_REQUEST["ch_show_past"]) or !$_REQUEST["ch_show_past"]) ) $toc = date("Y-m-d");//en caso que no tengamos que mostrar los pasts
            if($_REQUEST["ch_from"] and $_REQUEST["ch_to"]){
                $fromc = $_REQUEST["ch_to"];
                $toc = $_REQUEST["ch_from"];
            }
            $elements = array();
            $elements =  Post_Type_Chronosly::get_array_days_by_query($query,$toc, $fromc);
            if($repeated != "") $elements =  Post_Type_Chronosly::get_array_days_by_repeated($toc,$fromc, $repeated, $elements);//he cambiado el orden from y to porque es al reves aqui
            ksort($elements );
            // echo count($elements);
            // print_r($elements);
            // echo $limit;
            if($limit > 1){
                $els = array_chunk($elements, $limit, true);
            } else $els = array($elements);
            if($paged-1) $prev = 1;
            if(count($els) > $paged) $next = 1;
//            echo "<pre>";
//            print_r($els);
            //echo "<br>";
            //print_r($els[$paged-1]);
            //echo $paged;

            return array($els[$paged-1], $prev, $next);


        }



        //devolvemos la posicion en la que deberia ir segun los settings
        public static function get_event_position($name, $meta, $id, $time, $h=-1, $m=-1){
            global $pastformat;
            $settings =  unserialize(get_option("chronosly-settings"));
            $pos = $id;
            $ordertype = (isset($_REQUEST["orderby"]))?$_REQUEST["orderby"]:$settings["chronosly_events_order"];
            if(!isset($_REQUEST["orderdir"])) $_REQUEST["orderdir"] = $settings["chronosly_events_orderdir"];
            switch($ordertype ){
                case "date":
                    $pos = $id;//max id = 99999
                  //  141773760 00000
                    $pos += $time*10000;
                    //echo $time;
                    if($h < 0 && $m < 0){
                        if(isset($meta["ev-from-h"][0]) and $meta["ev-from-h"][0] != "") $pos += $meta["ev-from-h"][0]*60*60*10000;
                        if(isset($meta["ev-from-m"][0])) $pos += $meta["ev-from-m"][0]*10000*60;
                    } else {
                        //no se suma nada porque ya esta en time de las seasons
                        // $pos += $h*60*60*10000;
                        // $pos += $m*10000*60;
                    }
//                    else $pos += 10000000;
                    if( $settings["chronosly_featured_first"] and !$pastformat and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos += 10000000000000;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC") $pos = 10000000000000-$pos;
                 break;
                case "order":
                    if(isset($meta["order"][0]) and $meta["order"][0] != "") $pos += $meta["order"][0]*100000;
                    else $pos += 100000000;
                    if(!$_REQUEST["orderdir"] and $settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos += 10000000000000;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC") $pos = 10000000000000-$pos;
                    break;
                case "event":
                    $pos = strtolower(str_replace(" ", "",$name)).$time;
                    if(!$_REQUEST["orderdir"] and $settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos = "~~".$pos;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC"){
                        $pos = str_ireplace("a", "z", str_ireplace("b", "y",str_ireplace("c", "x",str_ireplace("d","w", str_ireplace("e", "v",str_ireplace("f","u", str_ireplace("g","t", str_ireplace("h","s", str_ireplace("i","r", str_ireplace("j","q", str_ireplace("k","p", str_ireplace("l", "o", str_ireplace("m","n",str_ireplace("n","m", str_ireplace("o","l", str_ireplace("p","k", str_ireplace("q","j", str_ireplace("r","i", str_ireplace("s","h", str_ireplace("t","g", str_ireplace("u","f", str_ireplace("v","e", str_ireplace("w","d", str_ireplace("x","c", str_ireplace("y","b", str_ireplace("z", "a", $pos))))))))))))))))))))))))));
                    }
                    break;
                case "category":
                    $category = get_the_terms($id, "chronosly_category");
                    if(!count($category)) $category = "~~~";
                    else $category = @array_shift(array_values($category))->name;
                    $pos = strtolower(str_replace(" ", "",$category).str_replace(" ", "",$name)).$time;
                    if(!$_REQUEST["orderdir"] and $settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos = "~~".$pos;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC"){
                        $pos = str_ireplace("a", "z", str_ireplace("b", "y",str_ireplace("c", "x",str_ireplace("d","w", str_ireplace("e", "v",str_ireplace("f","u", str_ireplace("g","t", str_ireplace("h","s", str_ireplace("i","r", str_ireplace("j","q", str_ireplace("k","p", str_ireplace("l", "o", str_ireplace("m","n",str_ireplace("n","m", str_ireplace("o","l", str_ireplace("p","k", str_ireplace("q","j", str_ireplace("r","i", str_ireplace("s","h", str_ireplace("t","g", str_ireplace("u","f", str_ireplace("v","e", str_ireplace("w","d", str_ireplace("x","c", str_ireplace("y","b", str_ireplace("z", "a", $pos))))))))))))))))))))))))));
                   }
                    break;
                case "organizer":
                    if(isset($meta["organizer"][0])){
                        $organizer = unserialize($meta["organizer"][0]);
                    }
                    if(is_array($organizer)){
                        $organizer = $organizer[0];
                        $organizer = get_post($organizer);
                        $organizer = $organizer->post_title;
                    }
                    else $organizer = "~~~";

                    $pos = strtolower(str_replace(" ", "",$organizer).str_replace(" ", "",$name)).$time;
                    if(!$_REQUEST["orderdir"] and $settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos = "~~".$pos;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC"){
                        $pos = str_ireplace("a", "z", str_ireplace("b", "y",str_ireplace("c", "x",str_ireplace("d","w", str_ireplace("e", "v",str_ireplace("f","u", str_ireplace("g","t", str_ireplace("h","s", str_ireplace("i","r", str_ireplace("j","q", str_ireplace("k","p", str_ireplace("l", "o", str_ireplace("m","n",str_ireplace("n","m", str_ireplace("o","l", str_ireplace("p","k", str_ireplace("q","j", str_ireplace("r","i", str_ireplace("s","h", str_ireplace("t","g", str_ireplace("u","f", str_ireplace("v","e", str_ireplace("w","d", str_ireplace("x","c", str_ireplace("y","b", str_ireplace("z", "a", $pos))))))))))))))))))))))))));
                   }
                break;
                case "place":
                    if(isset($meta["places"][0])){
                        $place = unserialize($meta["places"][0]);
                    }
                    if(is_array($place)){
                        $place = $place[0];
                        $place = get_post($place);
                        $place = $place->post_title;
                    }
                    else $place = "~~~";

                    $pos = strtolower(str_replace(" ", "",$place).str_replace(" ", "",$name)).$time;
                    if(!$_REQUEST["orderdir"] and $settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos = "~~".$pos;
                    if($pastformat or $_REQUEST["orderdir"] == "DESC"){
                        $pos = str_ireplace("a", "z", str_ireplace("b", "y",str_ireplace("c", "x",str_ireplace("d","w", str_ireplace("e", "v",str_ireplace("f","u", str_ireplace("g","t", str_ireplace("h","s", str_ireplace("i","r", str_ireplace("j","q", str_ireplace("k","p", str_ireplace("l", "o", str_ireplace("m","n",str_ireplace("n","m", str_ireplace("o","l", str_ireplace("p","k", str_ireplace("q","j", str_ireplace("r","i", str_ireplace("s","h", str_ireplace("t","g", str_ireplace("u","f", str_ireplace("v","e", str_ireplace("w","d", str_ireplace("x","c", str_ireplace("y","b", str_ireplace("z", "a", $pos))))))))))))))))))))))))));
                   }
                break;
                case "price":
                    if(isset($meta["tickets"][0])){
                        $ticket = json_decode($meta["tickets"][0]);

                    }
                    if(is_object($ticket)){
                        $ticket = $ticket->tickets;
                        if(is_array($ticket)){
                            $ticket =  $ticket[1];
                            foreach($ticket as $t){
                                if($t->name == "price") {
                                    $price = intval($t->value);
                                    break;
                                }
                            }
                            if($price == "") $price = 0;
                        }
                        else $price = 0;
                    }
                    else $price = 0;
                    $price += 1;

                    $pos = ($price*100000);
                    if($pastformat or $_REQUEST["orderdir"] == "DESC") $pos = 10000000000000-$pos;
                    $pos += $id;
                    $pos += $time*10000;
                break;
            }
            return (string)$pos;
        }


        //filter con organizers y places demomento
        public static function filter($id){
            $find = 1;
            if(isset($_REQUEST["organizer"]) and $_REQUEST["organizer"]){
                $find = 0;
                $organizers = explode(",", $_REQUEST["organizer"]);
                $meta = get_post_meta($id);
                if(isset($meta['organizer'])){
                    $orgs =  $meta['organizer'][0];
                    $org = unserialize($orgs);
                    if(count($org) > 1){
                        foreach($org as $o) {
                            if(in_array($o, $organizers)) {
                                $find = 1;
                                break;
                            }
                        }
                    } else if(is_array($org) and in_array($org[0], $organizers)) $find = 1;


                }
            }
            if($find and  isset($_REQUEST["place"]) and $_REQUEST["place"]){
                $find = 0;
                $places = explode(",", $_REQUEST["place"]);

                $meta = get_post_meta($id);
                if(isset($meta["places"])){
                    $orgs = $meta['places'][0];
                    $org = unserialize($orgs);
                    if(count($org) > 1){
                        foreach($org as $o) {
                            if(in_array($o, $places)) {
                                $find = 1;
                                break;
                            }
                        }
                    } else if(is_array($org) and in_array($org[0], $places)) $find = 1;
                }
            }
            return $find;
        }

        //llenamos el array de dates con el post que tocan
        public static function get_array_days_by_query($query, $from, $to){
            $elements = array();
            $settings =  unserialize(get_option("chronosly-settings"));
            while ( $query->have_posts() ){
                $query->the_post();
                $meta = get_post_meta(get_the_ID());
                if(isset($meta["ev-from"][0])){
                    //si empieza en el mismo año que el calendario debemos empezar en el dia especifico

                    $start =  strtotime($meta["ev-from"][0]);
                    
                     if(class_exists("Chronosly_Tickets_and_Repeats_Extended") and isset($meta["events"][0])){
                        if($_REQUEST["repeats_past_hide"] and time() > strtotime($from)) {
                            $from = date("Y-m-d");
                        } 
                        $eventos = json_decode($meta["events"][0]);
                        $find = 0;
                        $sets = unserialize(get_option("chronosly_settings_tickets_and_repeats_extended"));
                        // echo $from." 00:00";
                        // echo $to." 23:59";
                        // echo "<pre>";print_r($eventos);
                        if(!$sets["seasons_normal_display"]){
                            foreach ($eventos as $ev) {
                                if(strtotime($ev->start) >= strtotime($from." 00:00")
                                    and strtotime($ev->start) <= strtotime($to." 23:59")){
                                     $find = 1;
                                    }
                            }
                            if(!$find) continue;
                            $pos = Post_Type_Chronosly::get_event_position(get_the_title(),$meta, get_the_ID(), $start);
                            if(Post_Type_Chronosly::filter(get_the_ID())) {
                                $elements[$pos] = get_the_ID();
                            }
                        } else {
                            $tickets = array();
                            if( isset($meta["tickets"][0])) $tickets = json_decode($meta["tickets"][0]);
                            if(Post_Type_Chronosly::filter(get_the_ID())) Post_Type_Chronosly::seasons($eventos, strtotime($from), strtotime($to),$meta, $name, get_the_ID(), $elements, $tickets);
                        }
                    }
                    else {
                        $pos = Post_Type_Chronosly::get_event_position(get_the_title(),$meta, get_the_ID(), $start);
                        if(Post_Type_Chronosly::filter(get_the_ID())) {
                            $elements[$pos] = get_the_ID();
                        }
                    }


                }

            }
            return $elements;

        }

         public static function seasons($eventos, $start, $end ,$meta, $name, $id, &$elements, $tickets){
            $end = strtotime(date("Y-m-d", $end)." 23:59");
            $settings =  unserialize(get_option("chronosly-settings"));
            
                // echo "<pre>";print_r($eventos);
            foreach($eventos as $evento){
                $from = strtotime(substr($evento->start, 0, stripos($evento->start, "+")-1));
                $to = strtotime(substr($evento->end, 0, stripos( $evento->end, "+")-1));
                if($_REQUEST["ch-price-min"]){
                    if(!isset($evento->tickets)) continue;
                    $tick = 0;
                    //faltaria los sales que no los he podido controlar bien
                    foreach ($evento->tickets as $t) {
                        if($t->id && isset($tickets->tickets[$t->id])){
                            foreach ($tickets->tickets[$t->id] as $t1) {
                                if($t1->name == "price" && $t1->value >= $_REQUEST["ch-price-min"]){
                                    $tick = 1;
                                } else if($t->sale && $t1->name == "sales-price" && $t1->value >= $_REQUEST["ch-price-min"]){
                                    $tick = 1;
                                }
                            }

                        }
                     }  
                    if(!$tick) continue;
                }
                if($_REQUEST["ch-price-max"]){
                    $tick = 0;
                    //faltaria los sales que no los he podido controlar bien
                    foreach ($evento->tickets as $t) {
                        if($t->id && isset($tickets->tickets[$t->id])){
                            foreach ( $tickets->tickets[$t->id] as $t1) {
                                if($t1->name == "price" && $t1->value <= $_REQUEST["ch-price-max"]){
                                    $tick = 1;
                                } else if($t->sale && $t1->name == "sales-price" && $t1->value <= $_REQUEST["ch-price-max"]){
                                    $tick = 1;
                                }
                            }

                        }
                     }  
                    if(!$tick) continue;
                }
                // print_r($evento);
                if(date('Y-m-d',$from) != date('Y-m-d',$to)) $to -= 60*60*24;

                    if($to >= $start and $from <= $end){
                    

                           if((!isset($_REQUEST["ch_show_past"]) and $settings["chronosly_show_past_events"])  or $_REQUEST["ch_show_past"] or $from >= strtotime(date("Y-m-d"))) {
                                $pos = Post_Type_Chronosly::get_event_position($name,$meta, $id, $from, date("H", $from), date("i", $from));
                                // echo $pos." ";
                                $elements[$pos]= array("id" => $id,"start" => $from, "end" => $to , "h" => date("H", $from), "m"=> date("i", $from), "eh" => date("H", $to), "em"=> date("i", $to));
                            } 
                          
                    }
                }
                
            return $elements;
           
        }

        public static function get_array_days_by_repeated($from,$to, $query, $elements){
            $settings =  unserialize(get_option("chronosly-settings"));
            while ( $query->have_posts() ){
                $query->the_post();
                $meta = get_post_meta(get_the_ID());
                if(isset($meta["ev-from"][0])){
                    //Miramos cuando empieza y acaba y generamos sus repeticiones para ver si hay que mostrarlo
                    $start =  strtotime($meta["ev-from"][0]);
                    $end =  strtotime($meta["ev-to"][0]);
                    // if($settings["chronosly_week_start"] == 1) {
                    //     $start -= (60*60*24);
                    //     $end -= (60*60*24);
                    // }


                    //do repeats
                    if(Post_Type_Chronosly::filter(get_the_ID())) $elements =  Post_Type_Chronosly::repeats($meta,$from,$to, $start, $end, get_the_ID(), get_the_title(),$elements);
                }

            }


            return $elements;

        }

        public static function repeats($meta, $from,$to, $start, $end, $id, $name, $elements){
            $settings =  unserialize(get_option("chronosly-settings"));



            if(isset($meta["ev-repeat"][0]) and $meta["ev-repeat"][0] != "" and isset($meta["ev-repeat-every"][0]) and $meta["ev-repeat-every"][0] > 0){
                $start_min = strtotime($from);//start building array
                $end_top = strtotime($to);//limit of repeats per year
                if(isset($meta["ev-repeat-option"][0]) and $meta["ev-repeat-option"][0] == "until" and
                    isset($meta["ev-until"][0]) and strtotime($meta["ev-until"][0]) < $end_top){
                    $end_top = strtotime($meta["ev-until"][0]);
                }

                if($start_min > $end_top) return $elements;//el ev until elimina los eventos que no llega ni al inicio

                $count = -1;//for count repetitions
                if(isset($meta["ev-repeat-option"][0]) and $meta["ev-repeat-option"][0] == "count" and
                    isset($meta["ev-end_count"][0]) and $meta["ev-end_count"][0] > 0){
                    $count = $meta["ev-end_count"][0];
                }

                $distance = $meta["ev-repeat-every"][0]; //distance between repeats
                $event_days = $end - $start; // event days duration

                switch($meta["ev-repeat"][0]){
                    case "day":

                        $start = $end;
                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start += ($distance*60*60*24);//añadimos la distancia de dias

                            $end = $start+$event_days;//recalculamos el final
                            --$count;
                            if($start >= $start_min and $start <= $end_top){
                                $pos = Post_Type_Chronosly::get_event_position($name,$meta, $id, $start);
                                if(Post_Type_Chronosly::filter($id)) $elements[$pos]= array("id" => $id, "start" => $start, "end" => $end);
                            }
                            $start = $end;//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }

                        break;
                    case "week":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start += ($distance*7*60*60*24)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo
                            $end = $start+$event_days;//recalculamos el final
                            --$count;
                            if($start >= $start_min  and $start <= $end_top){
                                $pos = Post_Type_Chronosly::get_event_position($name,$meta, $id, $start);
                                if(Post_Type_Chronosly::filter($id)) $elements[$pos]= array("id" => $id, "start" => $start, "end" => $end);
                            }
                            $start = $end;//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                    case "month":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start = strtotime("+$distance month", $start)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo

                            $end = $start+$event_days;//recalculamos el final
                            --$count;
                            //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            if($start >= $start_min  and $start <= $end_top){
                                $pos = Post_Type_Chronosly::get_event_position($name,$meta, $id, $start);
                                if(Post_Type_Chronosly::filter($id)) $elements[$pos]= array("id" => $id, "start" => $start, "end" => $end);
                            }
                            $start = $end;//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                    case "year":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start = strtotime("+$distance year", $start)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo

                            $end = $start+$event_days;//recalculamos el final
                            --$count;
                            if($start >= $start_min  and $start <= $end_top){
                                $pos = Post_Type_Chronosly::get_event_position($name,$meta, $id, $start);
                                if(Post_Type_Chronosly::filter($id)) $elements[$pos]= array("id" => $id, "start" => $start, "end" => $end);
                            }
                            $start = $end;//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                }

            }
            return $elements;
        }

        public function chronosly_body_class($classes = ''){
            global $chronosly_running;
            if($chronosly_running){
                $classes[] = "chronosly";
            }
            return $classes;
        }

        public function id_for_base($obj){
            if($obj->ID){
                $settings = unserialize(get_option('chronosly-settings'));
                $base_ids = array("ch-events" => $settings["chronosly-events-base-templates-id"], "ch-event" => $settings["chronosly-events-single-base-templates-id"], "ch-organizers" => $settings["chronosly-organizers-base-templates-id"], "ch-organizer" => $settings["chronosly-organizers-single-base-templates-id"], "ch-places" => $settings["chronosly-places-base-templates-id"], "ch-place" => $settings["chronosly-places-single-base-templates-id"], "ch-category" => $settings["chronosly-category-single-base-templates-id"], "ch-categories" => $settings["chronosly-category-base-templates-id"]);
                if(in_array($obj->ID, $base_ids)){
                    $base_ids = array_flip($base_ids);
                    return $base_ids[$obj->ID];
                }
            }
            return 0;
        }

        //funcion que sirve para los users que tengan problemas con su theme y no quieran tocar codigo
        public static function base_template_code($template){
            global $wp_query,$wp_the_query, $chronosly_running, $chshortcode;
            $settings = unserialize(get_option('chronosly-settings'));
            $obj = get_queried_object();
            $custom_post_type =  get_post_type();
            if(!$custom_post_type or stripos($custom_post_type, "chronosly") === FALSE){
                $obj =  $wp_query->query_vars;
                $custom_post_type = $obj["post_type"];

            }
            $archive = 0;
            if(stripos($template,"ch-event") !== FALSE or stripos($template,"ch-organizer") !== FALSE or stripos($template,"ch-place") !== FALSE or stripos($template,"ch-category") !== FALSE or stripos($template,"ch-calendar") !== FALSE or self::POST_TYPE === $custom_post_type or "chronosly_category" === $custom_post_type or "chronosly_organizer" === $custom_post_type or "chronosly_places" === $custom_post_type or "chronosly_calendar" === $custom_post_type){
                Post_Type_Chronosly::get_shortcode_base();
                if(!is_search())$chronosly_running = 1;
                $chshortcode .= " navigation='1'";
                $chshortcode .= " before_events='1'";
                $chshortcode .= " after_events='1'";

                $template_page_id =  $settings["chronosly-base-templates-id"];
                if($template_page_id != 0){
                    if(is_archive() and $obj->taxonomy == "chronosly_category"){

                        $copy_fields = array(
                            'term_id',"slug", "taxonomy"
                        );
                        $original = array();
                        $original["chronosly_category"] = $obj->slug;
                        foreach( $copy_fields as $field){
                            $original[$field] = $obj->$field;
                        }
                    }  else if(is_archive()){

                        $archive = 1;
                        $original["post_title"] = $obj->labels->name;


                    } else {
                        $copy_fields = array(
                            'ID',"post_title"
                        );
                        $original = array();
                        foreach( $copy_fields as $field){
                            $original[$field] = $obj->$field;
                        }
                    }

                    global $wp_filter;
                    if(isset($wp_filter['pre_get_posts'])){
                        $bak = $wp_filter['pre_get_posts'];
                        unset($wp_filter['pre_get_posts']);
                    }else{
                        $bak = false;
                    }

                    $wp_query = new WP_Query('page_id='.$template_page_id);

                    $obj = $wp_query->get_queried_object();
                    $wrap = $obj->post_content;

                    global $post;

                    $post = $obj;

                    $post = is_object($post)?$post:(object)array();
                    $post->post_status = 'publish';//force it as publish

                    $template = get_page_template();//fetch template before overwritting post.
                    if(false!==$bak){
                        $wp_filter['pre_get_posts'] = $bak;
                    }
                    if($archive){
                        $post->post_title = $original["post_title"];
                    }
                    else {
                        foreach( $copy_fields as $field){
                            $post->$field = $original[$field];
                        }
                        if(isset( $original["chronosly_category"]))  $post->chronosly_category = $original["chronosly_category"];
                    }
                    $post->post_content = $obj->post_content;
                    $wp_query->post = $post;
                    $wp_the_query = $wp_query;
                }
            }
            return $template;
        }

        public static function insert_og_in_head() {
        global $post;

        $settings = unserialize(get_option("chronosly-settings"));
        if((!isset($settings["chronosly-display-ogmetas"]) || !$settings["chronosly-display-ogmetas"]) &&  !stripos($post->post_content, "chronoslybase")) return ;

        remove_action( 'wp_head', 'rel_canonical' );

         if( ($settings["chronosly-display-ogmetas"] || (isset($settings["chronosly-base-templates-id"]) and $settings["chronosly-base-templates-id"] != 0 )) and !$_REQUEST["js_render"]){
            if ( !is_singular()) //if it is not a post or a page
                return;
                echo '<meta property="og:title" content="' . get_the_title() . '"/>';
                echo '<meta property="og:type" content="article"/>';
                echo '<meta property="og:url" content="' . get_permalink($post->ID) . '"/>';
                echo "<link rel='canonical' href='".get_permalink($post->ID)."' />";

                // echo '<meta property="og:site_name" content="Your Site NAME Goes HERE"/>';
                $thumb = "";
            if(has_post_thumbnail( $post->ID )) {
               $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
               $thumb = esc_attr( $thumbnail_src[0] );
            }
           if(!$thumb) {
                if(class_exists("Chronosly_Social_Media_Share")){
                    $settingsSM = unserialize(get_option("chronosly_settings_social_media_share"));
                    $thumb = $settingsSM["default_image"];
                }
           }
            if($thumb) echo '<meta property="og:image" content="' . $thumb . '"/>';
            echo "\r\n";
        }
    }


        public static function chronosly_templates($template)
        {

            global $chronosly_running, $wp_query;
            $original_query = $wp_query;


            $settings = unserialize(get_option('chronosly-settings'));
            $custom_post_type = get_post_type();
            if(!$custom_post_type or stripos($custom_post_type, "chronosly") === FALSE){
                $obj =  $wp_query->query_vars;
                $custom_post_type = $obj["post_type"];

            }
            wp_register_style( 'chronosly-front-css'.CHRONOSLY_VERSION, CHRONOSLY_URL.'/css/front_template.css');
            wp_register_style( 'chronosly-custom-css', CHRONOSLY_URL.'/css/custom.css');
            wp_register_script( 'chronosly-gmap', 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array( 'jquery' ));
            wp_register_script( 'chronosly-scroll', CHRONOSLY_URL.'/js/scroll/jquery.mCustomScrollbar.concat.min.js', array( 'jquery' ));
            wp_register_style( 'chronosly-scroll-css', CHRONOSLY_URL.'/js/scroll/jquery.mCustomScrollbar.css');
            wp_register_style( 'chronosly-icons', CHRONOSLY_URL.'/css/icons/styles.css');
            wp_register_style( 'chronosly-fa-icons', "http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css");
            wp_register_script( 'chronosly-colorbox', CHRONOSLY_URL.'/js/colorbox/jquery.colorbox.js', array( 'jquery' ));
            wp_register_style( 'chronosly-colorbox-css', CHRONOSLY_URL.'/js/colorbox/css/colorbox.css');
            wp_register_style( 'chronosly-templates-base', CHRONOSLY_URL.'/css/templates_base.css');

            /* if($base = Post_Type_Chronosly::id_for_base($obj)){

                 Post_Type_Chronosly::base_template_code($base);
             }*/
            if (stripos($template, "shortcode") !== FALSE or self::POST_TYPE === $custom_post_type or "chronosly_category" === $custom_post_type or "chronosly_organizer" === $custom_post_type or "chronosly_places" === $custom_post_type or "chronosly_calendar" === $custom_post_type) {

                if(stripos($template, "shortcode") !== FALSE ){
                     wp_print_styles( 'chronosly-front-css'.CHRONOSLY_VERSION);
                    if(file_exists(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."custom.css")){
                        wp_print_styles( 'chronosly-custom-css');
                    }
                    if(!$settings["chronosly-disable-gmap-js"]) {
                        wp_enqueue_script( 'chronosly-gmap');

                    }
                    wp_enqueue_script( 'chronosly-scroll');
                    wp_print_styles( 'chronosly-scroll-css');
                    wp_print_styles( 'chronosly-icons');
                    wp_print_styles( 'chronosly-fa-icons');
                    wp_enqueue_script('chronosly-colorbox');
                    wp_print_styles('chronosly-colorbox-css');

                    if(!is_admin() or  stripos($_SERVER["REQUEST_URI"], "wp-admin") === FALSE){
                        wp_register_script( 'chronosly-front-js', CHRONOSLY_URL.'/js/front.js', array( 'jquery' ));
                        $translation_array  = array(
                            "scrollOnOpen" => !$settings["disable_slide_on_show"],
                            "weburl" => get_site_url(),
                            "calendarurl" => Post_Type_Chronosly_Calendar::get_permalink(),
                            'ajaxurl' => admin_url( 'admin-ajax.php' )

                        );
                        wp_localize_script( 'chronosly-front-js', 'translated1', $translation_array );
                        wp_enqueue_script( 'chronosly-front-js');
                        wp_enqueue_script( 'jquery-ui-core');
                        wp_enqueue_script( 'jquery-ui-datepicker');

                        wp_enqueue_script('jquery-ui-tabs');
                        wp_enqueue_script('jquery-ui-tooltip');
                        wp_enqueue_script('jquery-ui-resizable');
                        wp_enqueue_script('jquery-ui-draggable');
                    }
                    wp_print_styles( 'chronosly-templates-base');
                    //templates and addons css
                }
                else {
                    wp_enqueue_style( 'chronosly-front-css'.CHRONOSLY_VERSION);
                    if(file_exists(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."custom.css")){
                        wp_enqueue_style( 'chronosly-custom-css');
                    }
                    if(!$settings["chronosly-disable-gmap-js"]) {
                        wp_enqueue_script( 'chronosly-gmap');

                    }
                    wp_enqueue_script( 'chronosly-scroll');
                    wp_enqueue_style( 'chronosly-scroll-css');
                    wp_enqueue_style( 'chronosly-icons');
                    wp_enqueue_style( 'chronosly-fa-icons');
                    wp_enqueue_script('chronosly-colorbox');
                    wp_enqueue_style('chronosly-colorbox-css');

                    if(!is_admin() or  stripos($_SERVER["REQUEST_URI"], "wp-admin") === FALSE){
                        wp_register_script( 'chronosly-front-js', CHRONOSLY_URL.'/js/front.js', array( 'jquery' ));
                        $translation_array  = array(
                            "scrollOnOpen" => !$settings["disable_slide_on_show"],
                            "weburl" => get_site_url(),
                            "calendarurl" => Post_Type_Chronosly_Calendar::get_permalink(),
                            'ajaxurl' => admin_url( 'admin-ajax.php' )

                        );
                        wp_localize_script( 'chronosly-front-js', 'translated1', $translation_array );
                        wp_enqueue_script( 'chronosly-front-js');
                        wp_enqueue_script( 'jquery-ui-core');
                        wp_enqueue_script( 'jquery-ui-datepicker');

                        wp_enqueue_script('jquery-ui-tabs');
                        wp_enqueue_script('jquery-ui-tooltip');
                        wp_enqueue_script('jquery-ui-resizable');
                        wp_enqueue_script('jquery-ui-draggable');
                    }
                    wp_enqueue_style( 'chronosly-templates-base');
                    //templates and addons css
            }
                do_action("chronosly_custom_frontend_css");
                Post_Type_Chronosly::get_shortcode_base();
                if(stripos($template, "shortcode") === FALSE and !is_search()) {
                    $chronosly_running = 1;
                } else if(stripos($template, "shortcode") !== FALSE){
                    $chronosly_shortcode = 1;
                }
                $wp_query = $original_query;
                 // echo $template." ".$chronosly_shortcode." ";
                if((is_tax("chronosly_category") and !$chronosly_shortcode) or $template == "shortcode_category" or $template == "shortcode_categories" or $_REQUEST["shortcode_category"] or $_REQUEST["shortcode_categories"] ){
                    if(is_tax("chronosly_category","list_all_cats") or $template == "shortcode_categories" or $_REQUEST["shortcode_categories"]){
                        //falta añadir aqui los shortcodes...
                        //esto esta replicado en chronosly category, en template redirect, por lo que no hace caso de esto aqui
                        if($template != "shortcode_categories" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."archive-category-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."archive-category-chronosly.php";
                        if($template != "shortcode_categories" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";
                        if(!$settings["chronosly-base-templates-id"] or $_REQUEST["js_render"] or $template == "shortcode_categories" or $_REQUEST["shortcode_categories"]) return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'archive-category-chronosly.php';
                    }
                    else{
                        //add_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') );
                        add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                        if($template != "shortcode_category" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."single-category-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."single-category-chronosly.php";
                        if($template != "shortcode_category" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";
                        if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_category" or $_REQUEST["shortcode_category"])return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-category-chronosly.php';
                    }
                }
                else if(is_tax("chronosly_tag")  and !$chronosly_shortcode ){
                    // if(!has_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') )) add_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') );
                    if(!has_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  ))  add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                    return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-tag-chronosly.php';
                }
                else if((self::POST_TYPE === $custom_post_type  and !$chronosly_shortcode)  or $template == "shortcode_event" or $template == "shortcode_events"){
                    if(is_archive() or $template == "shortcode_events"){
                        // add_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') );
                        add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                        if($template != "shortcode_events" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."archive-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."archive-chronosly.php";
                        if($template != "shortcode_events" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";
                        if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_events") return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'archive-chronosly.php';
                    }
                    else if (is_single() or $template == "shortcode_event") {

                        if($template != "shortcode_event" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."single-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."single-chronosly.php";
                        if($template != "shortcode_event" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";

                        if(!$settings["chronosly-base-templates-id"] or $_REQUEST["js_render"]  or $template == "shortcode_event" ) return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-chronosly.php';
                    }
                }
                else if(("chronosly_organizer" === $custom_post_type   and !$chronosly_shortcode) or $template == "shortcode_organizer" or $template == "shortcode_organizers"){
                    add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                    if(is_archive() or $template == "shortcode_organizers"){
                        if($template != "shortcode_organizers" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."archive-organizer-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."archive-orgabizer-chronosly.php";
                        if($template != "shortcode_organizers" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";
                        if(!$settings["chronosly-base-templates-id"] or $_REQUEST["js_render"]  or $template == "shortcode_organizers" ) return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'archive-organizer-chronosly.php';
                    }
                    else if (is_single() or $template == "shortcode_organizer") {
                        if($template != "shortcode_organizer" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."single-organizer-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."single-organizer-chronosly.php";
                        if($template != "shortcode_organizer" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";

                        if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_organizer" ) return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-organizer-chronosly.php';
                    }
                }
                else if(("chronosly_places" === $custom_post_type   and !$chronosly_shortcode) or $template == "shortcode_place" or $template == "shortcode_places"){
                    add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );

                    if(is_archive() or $template == "shortcode_places") {
                        if($template != "shortcode_places" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."archive-places-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."archive-places-chronosly.php";
                        if($template != "shortcode_places" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";

                        if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_places") return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'archive-places-chronosly.php';
                    }
                    else if (is_single() or $template == "shortcode_place") {
                        if($template != "shortcode_place" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."single-places-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."single-places-chronosly.php";
                        if($template != "shortcode_place" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";

                        if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_place") return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'single-places-chronosly.php';
                    }
                }
                else if(("chronosly_calendar" === $custom_post_type   and !$chronosly_shortcode)  or $template == "shortcode_calendar")
                {
                    // if(!has_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') )) add_action( 'posts_orderby', array("Post_Type_Chronosly",'add_custom_orderby') );
                    if($template != "shortcode_calendar" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."calendar-chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."calendar-chronosly.php";
                    if($template != "shortcode_calendar" and file_exists(get_template_directory().DIRECTORY_SEPARATOR."chronosly.php")) return get_template_directory().DIRECTORY_SEPARATOR."chronosly.php";

                    if(!$settings["chronosly-base-templates-id"]  or $_REQUEST["js_render"]  or $template == "shortcode_calendar") {
                        add_action('wp_head',array("Post_Type_Chronosly", 'noindex'));

                        return CHRONOSLY_PATH.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.'calendar-chronosly.php';
                    }
                }


            }
            return $template;
        }


        public static function noindex()
        {

            $output='<meta name="robots" content="noindex, nofollow" />';

            echo $output;
        }

        //añade los parametros necesarios para las bases de los shrotcodes, en caso de usar themes compatibility
        public static function get_shortcode_base(){
            global $chshortcode, $wp_query;
            $settings =  unserialize(get_option("chronosly-settings"));
            $custom_post_type = get_post_type();
           if(!$custom_post_type or stripos($custom_post_type, "chronosly") === FALSE){
                $obj =  $wp_query->query_vars;
                $custom_post_type = $obj["post_type"];

            }

            if(self::POST_TYPE === $custom_post_type){
                if (is_single()) $chshortcode = "type='event' single='1' id='".get_queried_object_id()."'";
                else {
                    $chshortcode = "type='event' pagination='1' count='".$settings["chronosly_events_x_page"]."'";
                }

            }
            else if("chronosly_organizer" === $custom_post_type){
                if (is_single()) $chshortcode = "type='organizer' single='1' id='".get_queried_object_id()."'";
                else $chshortcode = "type='organizer'  pagination='1'  count='".$settings["chronosly_organizers_x_page"]."'";

            }
            else if("chronosly_places" === $custom_post_type){
                if (is_single()) $chshortcode = "type='place' single='1' id='".get_queried_object_id()."'";
                else $chshortcode = "type='place'  pagination='1'  count='".$settings["chronosly_places_x_page"]."'";

            } else if(is_tax("chronosly_category")){
                if(is_tax("chronosly_category","list_all_cats")) $chshortcode = "type='category' pagination='1' ";
                else $chshortcode = "type='category' single='1' id='".get_queried_object_id()."'";

            }
            $repeat = get_query_var("repeat");
            if($repeat) $_REQUEST["repeat"] = $repeat;
            $y = get_query_var("y");
            if($y) $_REQUEST["y"] = $y;
            $mo = get_query_var("mo");
            if($mo) $_REQUEST["mo"] = $mo;
            $week = get_query_var("week");
            if($week) $_REQUEST["week"] = $week;

        }




        /*
        function chronosly_templates_content($content)
        {
            if ( self::POST_TYPE === $custom_post_type )
                $content = '<p>here we are on my custom post type</p>';

            return $content;
        }

        function chronosly_templates_title($content)
        {
            if ( self::POST_TYPE === $custom_post_type )
                $content = '';

            return $content;
        }*/


        public function add_caps()
        {
            $roles = array("Super Admin", "administrator");
            foreach ($roles as $r) {
                $role = get_role( $r);
                if(!$role) continue;
                foreach ( array('publish','delete','delete_others','delete_private','delete_published','edit','edit_others','edit_private','edit_published','read_private') as $cap ) {
                    $role->add_cap( $cap."_chronoslys" );
                }
            }

            $roles = array("editor");
            foreach ($roles as $r) {
                $role = get_role( $r);
                if(!$role) continue;
                foreach ( array('publish','delete','delete_others','delete_private','delete_published','edit','edit_others','edit_private','edit_published','read_private') as $cap ) {
                    $role->add_cap( $cap."_chronoslys" );
                }
            }
        }

        public function chronosly_map_meta_cap($caps, $cap, $user_id, $args)
        {
            /* If editing, deleting, or reading , get the post and post type object. */
            if ('edit_chronosly' == $cap || 'delete_chronosly' == $cap || 'read_chronosly' == $cap) {
                $post = get_post( $args[0] );
                $post_type = get_post_type_object( $post->post_type );

                /* Set an empty array for the caps. */
                $caps = array();
            }

            /* If editing a chronosly, assign the required capability. */
            if ('edit_chronosly' == $cap) {
                if ( $user_id == $post->post_author )
                    $caps[] = $post_type->cap->edit_posts;
                else
                    $caps[] = $post_type->cap->edit_others_posts;
            }

            /* If deleting a chronosly, assign the required capability. */
            elseif ('delete_chronosly' == $cap) {
                if ( $user_id == $post->post_author )
                    $caps[] = $post_type->cap->delete_posts;
                else
                    $caps[] = $post_type->cap->delete_others_posts;
            }

            /* If reading a private chronosly, assign the required capability. */
            elseif ('read_chronosly' == $cap) {

                if ( 'private' != $post->post_status )
                    $caps[] = 'read';
                elseif ( $user_id == $post->post_author )
                    $caps[] = 'read';
                else
                    $caps[] = $post_type->cap->read_private_posts;
            }

            /* Return the capabilities required by the user. */

            return $caps;
        }

        /**
         * Save the metaboxes for this custom post type
         */
        public function save_post($post_id)
        {
            // verify if this is an auto save routine.
            // If it is our form has not been submitted, so we dont want to do anything
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
// handle the case when the custom post is quick edited
// otherwise all custom meta fields are cleared out
            if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')){
                Chronosly_Cache::delete_item($post_id);
                if(!class_exists("Chronosly_Tickets_and_Repeats_Extended"))$req = array("ev-to","ev-to-h","ev-to-m","ev-from","ev-from-h","ev-from-m","ev-repeat-every", "ev-repeat", "ev-repeat-option", "ev-until","ev-end_count","organizer", "places", "featured","order");
                else $req = array("organizer", "places", "featured","order"); 
                foreach ($this->_meta as $field_name) {
                    // Update the post's meta field
                    // if($field_name == "tickets"){
                    //     $tick_send = $_POST[$field_name];
                    //     $tickets = @get_post_meta($post_id, 'tickets', true);
                    //     $tickets= json_decode($tickets[0]);
                    //     if(isset($tickets->tickets)){
                    //         for($i = 1; $i < count($tickets->tickets);++$i){
                    //             $ticket = $tickets->tickets[$i];
                    //             foreach($ticket as $t){
                    //                 $tick[$t->name] = $t->value;
                    //                 if(($t->name == "price" or $t->name == "soldout") and $tick_send[$i][$t->name]) $t->value = $tick_send[$i][$t->name];
                    //                 $tickets->tickets[$i][] = array("name" => $t->name, "value" => $t->value);

                    //             }
                    //         }
                    //         update_post_meta($post_id, $field_name, json_encode($tickets));
                    //     }
                    // }
                    // else
                     if(in_array($field_name, $req)) update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }
            }
            if (isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id)) {
                Chronosly_Cache::delete_item($post_id);
                foreach ($this->_meta as $field_name) {
                    // Update the post's meta field
                     update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }

            } else {
                return;
            } // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
        } // END public function save_post($post_id)

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            add_action( 'admin_print_scripts-post-new.php', array(&$this,'admin_script'), 11 );
            add_action( 'admin_print_scripts-post.php',  array(&$this,'admin_script'), 11 );
            add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
            add_filter( 'manage_chronosly_posts_columns',  array(&$this,'modify_admin_table' ));
            add_action( 'manage_chronosly_posts_custom_column', array(&$this,'modify_admin_table_row'), 10, 2 );
            add_action('quick_edit_custom_box',   array(&$this,'admin_add_quick_edit'), 10, 2);


        }

        public function admin_script()
        {
            // Add metaboxes
            global $post_type;
            if( 'chronosly' != $post_type and 'chronosly_organizer' != $post_type and 'chronosly_places' != $post_type and 'chronosly_category' != $post_type and get_page_template() != "edit-chronosly_category") return;

            $this->inicia_scripts();




        } // END public function admin_init()

        public function inicia_scripts(){

            wp_enqueue_media();
            wp_register_style( 'chronosly-custom_wp_admin_css', CHRONOSLY_URL .'/css/admin_template1.css', false, '1.0.0' );
            wp_enqueue_style( 'chronosly-custom_wp_admin_css' );
            $wpScripts = new WP_Scripts();
            // if(!$wpScripts->query('jquery','enqueued')){
            //     wp_register_script( 'chronosly-jquery', CHRONOSLY_URL.'/js/jquery.js');
            // }
            wp_register_script( 'chronosly-admin', CHRONOSLY_URL.'/js/admin.js', array( 'jquery' ));
            wp_register_script( 'chronosly-front', CHRONOSLY_URL.'/js/front.js', array( 'jquery' ));
            $translation_array	= array(
                "scrollOnOpen" => !$this->settings["disable_slide_on_show"],
                "weburl" => get_site_url(),
                "calendarurl" => Post_Type_Chronosly_Calendar::get_permalink(),
                'ajaxurl' => admin_url( 'admin-ajax.php' )


            );
            wp_localize_script( 'chronosly-front-js', 'translated1', $translation_array );
            if(!$this->settings["chronosly-disable-gmap-js"]) {
                wp_register_script( 'chronosly-gmap', 'http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array( 'jquery' ));
                wp_enqueue_script('chronosly-gmap');

            }
            $translation_array	= array(
                "days" => __("days", "chronosly"),
                "weeks" => __("weeks", "chronosly"),
                "months" => __("months", "chronosly"),
                "year" => __("year", "chronosly"),
                "color_cancel" => __("Cancel", "chronosly"),
                "color_choose" => __("Choose", "chronosly"),
                "stop_prev" => __("Slower preview", "chronosly"),
                "start_prev" => __("Faster preview", "chronosly"),
                "overwritted" => __("overwritted", "chronosly"),
                "specify_name" => __("You have to specify a name or select a template to update", "chronosly"),
                "wrong_name" => __("The name have incorrect characters, only are alowed chars between a-z,A-Z,0-9 and - or _", "chronosly"),
                "succes" => __("Saved successfully", "chronosly"),
                "duplicate_succes" => __("Cloned successfully", "chronosly"),
                "save_url" => CHRONOSLY_URL."ev-functions/save_template.php",
                "guardamos" => $this->settings["chronosly_template_default"],
                'ajaxurl' => admin_url( 'admin-ajax.php' )

            );
            wp_localize_script( 'chronosly-admin', 'translated1', $translation_array );

            // if(!$this->settings["jquery-admin-disable"]) wp_enqueue_script('chronosly-jquery');
            //load progress

            wp_register_script( 'chronosly-dateformat', CHRONOSLY_URL.'/js/dateFormat.js', array( 'jquery' ));
            wp_enqueue_script('chronosly-dateformat');
            wp_register_script( 'chronosly-colorbox', CHRONOSLY_URL.'/js/colorbox/jquery.colorbox.js', array( 'jquery' ));
            wp_enqueue_script('chronosly-colorbox');
            wp_register_style( 'chronosly-colorbox-css', CHRONOSLY_URL.'/js/colorbox/css/colorbox.css');
            wp_enqueue_style('chronosly-colorbox-css');
            wp_enqueue_script('chronosly-admin');

            if(!is_admin() or  stripos($_SERVER["REQUEST_URI"], "wp-admin") === FALSE)  wp_enqueue_script('chronosly-front');

                         wp_enqueue_script( 'jquery-ui-core');
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
            wp_register_style( 'chronosly-templates-base', CHRONOSLY_URL.'/css/templates_base.css');
            wp_enqueue_style( 'chronosly-templates-base');
            wp_register_style( 'chronosly-icons', CHRONOSLY_URL.'/css/icons/styles.css');
            wp_enqueue_style( 'chronosly-icons');
            wp_register_style( 'chronosly-fa-icons', "http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css");
            wp_enqueue_style( 'chronosly-fa-icons');
            do_action("chronosly_custom_backend_css");


            //select efects
            /*wp_register_script( 'chronosly-selectizer', CHRONOSLY_URL.'/js/selectize/dist/js/standalone/selectize.js');
            wp_enqueue_script('chronosly-selectizer');
            wp_register_style( 'chronosly-selectizer-css', CHRONOSLY_URL.'/js/selectize/dist/css/selectize.bootstrap3.css');
            wp_enqueue_style('chronosly-selectizer-css');*/
            /*   wp_register_script( 'chronosly-select2', CHRONOSLY_URL.'/js/select2/select2.js');
               wp_enqueue_script('chronosly-select2');
               wp_register_style( 'chronosly-select2-css', CHRONOSLY_URL.'/js/select2/select2.css');
               wp_enqueue_style('chronosly-select2-css');
   */
        }

        function modify_admin_table( $column ) {
            $column['ch_date'] = __('Date', "chronosly");
            $column['ch_price'] = __('Price', "chronosly");

            if($this->settings["chronosly_organizers"] and $this->settings["chronosly_organizers_addon"]) $column['ch_organizer'] = __('Organizer', "chronosly");
            if($this->settings["chronosly_places"] and $this->settings["chronosly_places_addon"]) $column['ch_place'] = __('Place', "chronosly");
            $column['ch_visibility'] = __('Visibility', "chronosly");

            return $column;
        }

        function modify_admin_table_row( $column_name, $post_id ) {

            $custom_fields = get_post_custom( $post_id );
            $settings = unserialize(get_option("chronosly-settings"));

            switch ($column_name) {
                case 'ch_date' :
                    if(!class_exists("Chronosly_Tickets_and_Repeats_Extended"))$req = array("ev-to","ev-to-h","ev-to-m","ev-from","ev-from-h","ev-from-m","ev-repeat-every", "ev-repeat", "ev-repeat-option", "ev-until","ev-end_count","tickets","organizer", "places", "featured","order");
                    else $req = array("organizer", "places", "featured","order");
                    foreach($req as $r){
                        if($r == "organizer" or $r == "places") $vars[$r] = isset($custom_fields[$r][0])?unserialize($custom_fields[$r][0]):"";
                        else if($r == "tickets"){
                            if(isset($custom_fields[$r][0])) {
                                $t = json_decode($custom_fields[$r][0]);
                                 $vars[$r] = isset($t->tickets)?$t->tickets:"";
                            }else {
                                $vars[$r] = "";
                            }
                        }
                        else $vars[$r] = isset($custom_fields[$r][0])?$custom_fields[$r][0]:"";
                    }

                    echo "<div style='display:none;' class='chronosly-qe-vars'>".str_replace("\\", "",json_encode($vars))."</div>";
                if(!class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                    echo "<b>".__("From", "chronosly").":</b> ".$custom_fields['ev-from'][0]." ".$custom_fields['ev-from-h'][0].":".$custom_fields['ev-from-m'][0]."<br/>";
                    echo "<b>".__("To", "chronosly").":</b> ".$custom_fields['ev-to'][0]." ".$custom_fields['ev-to-h'][0].":".$custom_fields['ev-to-m'][0]."<br/>";
                    $num = isset($custom_fields['ev-repeat-every'][0])?$custom_fields['ev-repeat-every'][0]:0;
                    $rep = isset($custom_fields['ev-repeat'][0])?$custom_fields['ev-repeat'][0]:"";
                    switch($rep){
                        default:
                        case "":
                            $repeat = 0;
                            $repeatn = __("Never", "chronosly");
                            break;
                        case "day":
                            $repeat = 1;
                            $repeatn = sprintf(__("Every %d day", "chronosly"), $num);
                            break;
                        case "week":
                            $repeat = 2;
                            $repeatn = sprintf(__("Every %d week", "chronosly"), $num);
                            break;
                        case "month":
                            $repeat = 3;
                            $repeatn = sprintf(__("Every %d month", "chronosly"), $num);
                            break;
                        case "year":
                            $repeat = 4;
                            $repeatn = sprintf(__("Every %d year", "chronosly"), $num);
                            break;
                    }


                    echo "<b>".__("Repeat", "chronosly").":</b> $repeatn<br/>";
                    if($repeat){
                        switch($custom_fields['ev-repeat-option'][0]){
                            default:
                            case "":
                                $repeatn =  __("Never", "chronosly");
                                break;
                            case "until":
                                $repeatn =  $custom_fields['ev-until'][0];
                                break;
                            case "count":
                                $repeatn =  $custom_fields['ev-end_count'][0]." ".__("times", "chronosly");
                                break;
                        }
                        echo "<b>Repeat end:</b> $repeatn";
                    }
                }
                else {
                    _e("Tickets and Repeats addon enabled", "chronosly");
                }

                    break;

                case 'ch_price' :
                    $tickets= isset($custom_fields['tickets'][0])?json_decode($custom_fields['tickets'][0]):"";
                    if(isset($tickets->tickets)){
                        for($i = 1; $i < count($tickets->tickets);++$i){
                            $ticket = $tickets->tickets[$i];
                            foreach($ticket as $t){
                                $tick[$t->name] = $t->value;
                            }
                            echo "<b>".$tick["title"]."</b> ".$settings["chronosly_currency"].$tick["price"]."<br/>";
                        }
                    }
                    break;
                case 'ch_organizer':

                    if(isset($custom_fields['organizer'])){
                        foreach($custom_fields['organizer'] as $orgs){
                            $orgs = unserialize($orgs);
                            if(isset($orgs) and $orgs != ""){
                                foreach($orgs as $org){

                                    $post = get_posts('post_type=chronosly_organizer&p='.$org);
                                    echo "<a href='post.php?post=$org&action=edit' target='_blank'>".$post[0]->post_title."</a><br/>";

                                }
                            }
                        }
                    }
                    break;
                case 'ch_place':

                    if(isset($custom_fields['places'])){
                        foreach($custom_fields['places'] as $orgs){
                            $orgs = unserialize($orgs);
                            if(isset($orgs) and $orgs != ""){
                                foreach($orgs as $org){

                                    $post = get_posts('post_type=chronosly_places&p='.$org);
                                    echo "<a href='post.php?post=$org&action=edit' target='_blank'>".$post[0]->post_title."</a><br/>";

                                }
                            }
                        }
                    }
                    break;
                case 'ch_visibility':
                    if(isset($custom_fields['featured']) and $custom_fields['featured'][0]){
                        $featimg ="";
                        if(!has_post_thumbnail($post_id)) $featimg = "(".__("without image", "chronosly").")";
                        echo "<b>".__("Featured", "chronosly")."</b> $featimg</br/>";
                    } else echo "<b>".__("Not Featured", "chronosly")."</b><br/>";
                    if(isset($custom_fields['order']) and $settings["chronosly_events_order"] == "order"){

                        echo "<b>".__("Order", "chronosly").":</b>".$custom_fields["order"][0]."</br/>";
                    }
                    break;
                default:
            }
        }


        function admin_add_quick_edit($column_name, $post_type) {
            switch($column_name) {
                case "ch_date":
                    ?>
                    <div id="chronosly_chronosly_data_section" class="chronosly-fields" style="clear:both">
                        <h4><?php _e("Chronosly fields", "chronosly")?></h4>
                        <fieldset class="inline-edit-col-left">
                        <?php 
                            if(!class_exists("Chronosly_Tickets_and_Repeats_Extended")){
                        ?>
                            <div class="inline-edit-col">
                                <label for="from"><?php echo __("From", "chronosly"); ?></label>
                                <input type="text" id="ev-from" name="ev-from" value="" />
                                <label for="from-h"><?php echo __("Hour", "chronosly"); ?></label>

                                <input type="text" id="ev-from-h" name="ev-from-h"  value=""  />:
                                <input type="text" id="ev-from-m" name="ev-from-m"  value="" />
                                <br/>
                                <label for="to"><?php echo __("To", "chronosly"); ?></label>
                                <input type="text" id="ev-to" name="ev-to"  value=""  />
                                <label for="from-h"><?php echo __("Hour", "chronosly"); ?></label>

                                <input type="text" id="ev-to-h" name="ev-to-h"  value="" />:
                                <input type="text" id="ev-to-m" name="ev-to-m"  value="" />
                                <div >
                                    <label for="repeat" ><?php echo __("Repeat", "chronosly");?></label>
                                    <select id='repeat' name="ev-repeat">
                                        <option value=""><?php _e("Never (Not a recurring event)", "chronosly")?></option>
                                        <option  value="day"><?php _e("Every day", "chronosly")?></option>
                                        <option  value="week"><?php _e("Every week", "chronosly")?></option>
                                        <option  value="month"><?php _e("Every month", "chronosly")?></option>
                                        <option  value="year"><?php _e("Every year", "chronosly")?></option>
                                    </select>
                                </div>


                                <div class="end-repeat-section" >
                                    <div class="field-hide field1"  >

                                        <label><?php _e("Repeat every","chronosly");?></label>
                                        <input type="text" id="ev-repeat-every" name="ev-repeat-every"  value="" />



                                        <label><?php _e("End of repeat","chronosly");?></label>
                                        <select id="repeat_end_type" name="ev-repeat-option" >
                                            <option  value="never"><?php _e("never", "chronosly");?></option>
                                            <option  value="until"><?php _e("by date", "chronosly");?></option>
                                            <option value="count"><?php _e("by number", "chronosly");?></option>
                                        </select>
                                        <div class="ch-clear"></div>
                                    </div>

                                    <div class="field-hide repeat_type_until">

                                        <label><?php _e("End date","chronosly");?></label>
                                        <input id="rrule_until" type="text" name="ev-until"  value="" />
                                    </div>

                                    <div class="field-hide repeat_type_count">

                                        <label ><?php _e("Number of repeats","chronosly");?></label>
                                        <input id="fc_end_count" type="text" name="ev-end_count"  value=""  />
                                    </div>
                                </div>
                                <?php /* <br/>
                            <span class="title inline-edit-tickets-label">Tickets</span>
                            <ul id="tickets">
                            </ul>*/

                             ?>
                            </div>
                            <?php } else {
                                _e("Tickets and Repeats addon enabled", "chronosly");
                            }?>
                        </fieldset>
                        <?php if($this->settings["chronosly_organizers"] and $this->settings["chronosly_organizers_addon"]) { ?>
                        <fieldset class="inline-edit-col-center">
                            <div class="inline-edit-col">

                                <span class="title inline-edit-organizers-label"><?php _e("Organizers", "chronosly");?></span>
                                <ul class="cat-checklist chronosly_organizer-checklist">
                                    <?php
                                    $posts = get_posts('post_type=chronosly_organizer&posts_per_page=-1&orderby=title&order=ASC&suppress_filters=0');
                                    foreach($posts as $p){
                                        echo '<li id="chronosly_organizer-'.$p->ID.'"><label class="selectit"><input value="'.$p->ID.'" type="checkbox" name="organizer[]" id="in-chronosly_organizer-'.$p->ID.'"> '.$p->post_title.'</label></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </fieldset>
                        <?php
                        }
                        if($this->settings["chronosly_places"]){
                        ?>
                        <fieldset class="inline-edit-col-center">
                            <div class="inline-edit-col">

                                <span class="title inline-edit-places-label"><?php _e("Places", "chronosly");?></span>
                                <ul class="cat-checklist chronosly_places-checklist">
                                    <?php
                                    $posts = get_posts('post_type=chronosly_places&posts_per_page=-1&orderby=title&order=ASC&suppress_filters=0');
                                    foreach($posts as $p){

                                        echo '<li id="chronosly_places-'.$p->ID.'"><label class="selectit"><input value="'.$p->ID.'" type="checkbox" name="places[]" id="in-chronosly_places-'.$p->ID.'"> '.$p->post_title.'</label></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </fieldset>
                        <?php } ?>
                        <fieldset class="inline-edit-col-center">
                            <div class="inline-edit-col">
                                <input type="checkbox" id="featured" name="featured" value="1" /> <span class="title"><?php _e("Featured", "chronosly");?></span><br/>
                                <span class="title"><?php _e("Order", "chronosly");?>  </span><input type="text" id="order" name="order" value="" />
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    break;
                case "ch-color":
                    ?>
                    <fieldset class="inline-edit-col-left">
                        <div class="inline-edit-col">
                            <span class="title"><?php _e("Color","chronosly");?> </span> <input class="cat-color" name="cat-color" type="text" value="" />
                        </div>
                    </fieldset>
                    <?php
                    break;
            }
        }

        /**
         * hook into WP's add_meta_boxes action hook
         */
        public function add_meta_boxes()
        {
            global $post;
            $metas = $this->_meta;
            $this->_meta = apply_filters("chronosly_metabox_events", $metas);


            add_meta_box(
                sprintf('chronosly_%s_data_section', self::POST_TYPE),
                __('Date and Time', 'chronosly'),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE,
                'normal',
                'high',
                array('type' => 'date', "post" => $post)

            );

            if( $this->settings["chronosly_tickets"]){
                add_meta_box(
                    sprintf('chronosly_%s_tikets_section', self::POST_TYPE),
                    __('Tickets', 'chronosly'),
                    array(&$this, 'add_inner_meta_boxes'),
                    self::POST_TYPE,
                    'normal',
                    'high',
                    array('type' => 'ticket', "post" => $post)

                );
            }

             if( $this->settings["chronosly_template_editor"]){
                add_meta_box(
                    sprintf('chronosly_%s_dad1_section', self::POST_TYPE),
                    __('Template', 'chronosly'),
                    array(&$this, 'add_inner_meta_boxes'),
                    self::POST_TYPE,
                    'normal',
                    'high',
                    array('type' => 'dad1', "post" => $post)

                );
            }

            add_meta_box(
                sprintf('chronosly_%s_vars_section', self::POST_TYPE),
                __('Options', 'chronosly'),
                array(&$this, 'add_inner_meta_boxes'),
                self::POST_TYPE,
                'side',
                'core',
                array('type' => 'vars', "post" => $post)

            );
            //Add organizers
            if( $this->settings["chronosly_organizers_addon"] and $this->settings["chronosly_organizers"]){

                add_meta_box(
                    sprintf('chronosly_%s_organizer_section', self::POST_TYPE),
                    __('Organizers', 'chronosly'),
                    array(&$this, 'add_inner_meta_boxes'),
                    self::POST_TYPE,
                    'side',
                    'core',
                    array('type' => 'organizers', "post" => $post)

                );
            }
            //Add Places
            if( $this->settings["chronosly_places_addon"] and $this->settings["chronosly_places"]){

                add_meta_box(
                    sprintf('chronosly_%s_places_section', self::POST_TYPE),
                    __('Places', 'chronosly'),
                    array(&$this, 'add_inner_meta_boxes'),
                    self::POST_TYPE,
                    'side',
                    'core',
                    array('type' => 'places', "post" => $post)

                );
            }
            //Add Calendar
            /* add_meta_box(
                 sprintf('chronosly_%s_calendar_section', self::POST_TYPE),
                 __('Calendar', 'chronosly'),
                 array(&$this, 'add_inner_meta_boxes'),
                 self::POST_TYPE,
                 'side',
                 'core',
                 array('type' => 'calendar', "post" => $post)

             );*/

        } // END public function add_meta_boxes()

        /**
         * called off of the add meta box
         */
        public function add_inner_meta_boxes($post, $metabox)
        {


            $post = $metabox['args']['post'];//solve problematical posts ids

            // Render the job order metabox
            if (count($metabox['args']) and isset($metabox['args']['type'])) {
                global $cats,$tags,$orgs,$plcs;
                //set de defaults vars for custmize contents
                if (!isset($cats)) {
                    $cats =$tags =$orgs =$plcs = array();

                    $places = @get_post_meta($post->ID, 'places', true);
                    if ($places) {
                        foreach ($places as $p) {
                            $l = new WP_Query( 'post_type=chronosly_places&suppress_filters=0&p='.$p);
                            $pl= $l->get_posts();
                            $plcs[]= $pl[0];

                        }
                    }
                    $organizers = @get_post_meta($post->ID, 'organizer', true);
                    if ($organizers) {
                        foreach ($organizers as $p) {
                            $l = new WP_Query( 'post_type=chronosly_organizer&suppress_filters=0&p='.$p);
                            $or= $l->get_posts();
                            $orgs[]= $or[0];
                        }
                    }
                    $cats = wp_get_object_terms( $post->ID , "chronosly_category");
                    $tags = wp_get_object_terms($post->ID, "chronosly_tag");
                }
                if ('organizers' == $metabox['args']['type'] and  $this->settings['chronosly_organizers_addon'] and  $this->settings['chronosly_organizers']) {
                   
                    $check = @get_post_meta($post->ID, 'organizer', true);
                    $posts = get_posts('post_type=chronosly_organizer&numberposts=-1&orderby=title&order=ASC&suppress_filters=0');

                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."_organizer_metabox.php");
                } elseif ('places' == $metabox['args']['type'] and  $this->settings['chronosly_places_addon'] and  $this->settings['chronosly_places']) {
                    $check = @get_post_meta($post->ID, 'places', true);
                    $posts = get_posts( 'post_type=chronosly_places&numberposts=-1&orderby=title&order=ASC&suppress_filters=0');

                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."_places_metabox.php");
                } elseif ('calendar' == $metabox['args']['type']) {
                    $check = @get_post_meta($post->ID, 'calendar', true);
                    $posts = get_posts( 'post_type=chronosly_calendar&numberposts=-1&orderby=title&order=ASC&suppress_filters=0');
                    foreach ($posts as $post) {

                        if(@in_array($post->ID, $check)) $checked = true;
                        else $checked = false;
                        require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."calendar_metabox.php");
                    }
                } elseif ('date' == $metabox['args']['type']) {
                    $vars = @get_post_meta($post->ID);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."_date_metabox.php");
                    do_action("chronosly_date_metabox", $vars);

                } elseif ('ticket' == $metabox['args']['type']) {
                    $vars = json_decode(@get_post_meta($post->ID, "tickets", true));
                     $settings = unserialize(get_option("chronosly-settings"));
                    $currency = $settings["chronosly_currency"];

                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."_tickets_metabox.php");

                } elseif ('vars' == $metabox['args']['type']) {
                    $vars = @get_post_meta($post->ID);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR.self::POST_TYPE."_vars_metabox.php");

                } elseif ('preview' == $metabox['args']['type']) {
                    echo __("If your admin runs slow try stopping template preview. Only use it when you really need it", "chronosly")." <input id='stop_preview' type='button' value='".__("Faster preview", "chronosly")."' /><div id='preview-chronosly'>".__("Loading your template", "chronosly")."...</div>";
                } elseif ('dad1' == $metabox['args']['type']) {




                    $vars = @get_post_meta($post->ID);


                    //
                    $dadcats = array();

                    $vistas = array(
                        "dad1" => __("All events list view", "chronosly"),
                        "dad2" => __("Single event view", "chronosly"),
                        "dad3" => __("Calendar view", "chronosly"),
                        "dad4" => __("Category events list view", "chronosly"),
                        "dad5" => __("Organizer events list view", "chronosly"),
                        "dad6" => __("Place events list view", "chronosly"),
                    );

                    //cargando templates
                    $perfil = $this->settings['chronosly_tipo_perfil'];
                    $selected_template = $this->template->get_tipo_template($post->ID, "dad1");
                    if($selected_template == "chbd")  $selected_template = "template_edited";
                    $select_options =  $this->template->build_templates_select($this->template->get_file_templates($post->ID, "dad1",$perfil), $selected_template);
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad1".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad1_select_metabox.php");
                    //load custom or default template
                    //$this->template->set_post($post);
                    $template = $this->template->print_template($post->ID, "dad1", $dadcats);



                    //save or overwrite template
                    require_once(CHRONOSLY_PATH.DIRECTORY_SEPARATOR."metaboxes".DIRECTORY_SEPARATOR."dad1".DIRECTORY_SEPARATOR.self::POST_TYPE."_dad1_save_metabox.php");
                    //print_r($GLOBALS);

                }


            }
        } // END public function add_inner_meta_boxes($post)

    } // END class Post_Type_Template
} // END if(!class_exists('Post_Type_Template'))

