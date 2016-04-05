<?php
if(!class_exists('Chronosly_Shortcode'))
{
    class Chronosly_Shortcode
    {


        public function __construct()
        {


            // register actions

            add_filter( 'posts_where', array(&$this,'title_like_where'), 10, 2 );
            add_filter( 'posts_where', array(&$this,'meta_like_where'), 10, 2 );
            add_filter( 'posts_where', array(&$this,'meta_price_where'), 10, 2 );
            add_filter( 'posts_where', array(&$this,'meta_near_where'), 10, 2 );
            add_shortcode( 'chronosly', array($this, 'chronosly_shortcode' ));
            add_shortcode( 'chronoslybase', array($this, 'chronosly_base_shortcode' ));
            add_action( 'wp_ajax_nopriv_ch_run_shortcode', array(&$this, 'js_ch_run_shortcode' ));
            add_action( 'wp_ajax_ch_run_shortcode', array(&$this, 'js_ch_run_shortcode' ));

        } // END public function __construct



        public function chronosly_base_shortcode($atts){
            global $chshortcode;
            if(!$chshortcode) return "";
            return do_shortcode("[chronosly $chshortcode base=1]");
        }

        public function chronosly_shortcode($atts){



            $settings = unserialize(get_option("chronosly-settings"));
            $args = array(
                'type' => 'calendar',//type: allowed calendar, event, organizer, place, category, map_navigation
                "upcoming" => 0, //upcoming: days for upcoming events
                "year" => 0,//year: for filtering calendar and event
                "month" => 0,//month: for filtering calendar and event
                "week" => 0,//week: for filtering calendar and event
                "day" => 0,//day: for filtering event,
                'id' => 0,//id: for individual reference of types, coma separated
                'exclude' => 0,//id of excluded elements, coma separated
                'single' => 0, //set if we want to show the single or archive view, not allowed on calendar view.
                'count' => $settings["chronosly_events_x_page"], //number of results
                'category' => 0,//filter for concret categories,coma separated
                'organizer' => 0,//filter for concret organizers, coma separated
                'place' => 0,//filter for concret places,coma separated
                'navigation' => 0, //show navigation on top
                'pagination' => 0, //show pagination on bottom
                'small' => 0,//set if the container whith is small than 400px to adjust style
                'featured' => 0, //set yes or no to switch between featured obects
                'before_events' => 0,
                'after_events' => 0,
                'show_past' => 0,
                'template' => '', //use another template on shortcode
                'base' => '' //is a the base shortcode
            );
            $args = apply_filters("chronosly-shortcode-extra-params", $args);
            $code = shortcode_atts( $args , $atts );

            $code = apply_filters("chronosly-shortcode-extra-params-run", $code);
              // print_r($code);
            //print_r($code);
            $_REQUEST["small"] = $code["small"];
            $_REQUEST["navigation"] = $code["navigation"];
            if(stripos($type, "format") === FALSE) Chronosly_Templates::set_shortcode_template_css();

            if( $code["template"])  {
                $_REQUEST["force_template"] = $code["template"];
                Chronosly_Templates::set_shortcode_template_css($code["template"]);
            }
            if( $code["year"]){
                $_REQUEST["chronosly_event_list_format"] = "year";
                $_REQUEST["chronosly_event_list_time"] = $code["year"];
                $_REQUEST["y"] = $code["year"];
            }
            if($code["month"]){
                $_REQUEST["mo"] =  $code["month"];
                $_REQUEST["chronosly_event_list_time"] =  $code["month"];
                $_REQUEST["chronosly_event_list_format"] = "month";

            }
            if($code["week"]){

                $_REQUEST["week"] = $code["week"];
                $_REQUEST["chronosly_event_list_time"] =  $code["week"];
                $_REQUEST["chronosly_event_list_format"] = "week";

            }
            if($code["day"]){
                //show event list for day
                // if($code["type"] != "category") $code["type"] = "event";
                $_REQUEST["chronosly_event_list_format"] = "day";
                $_REQUEST["chronosly_event_list_time"] =  $code["day"];

            }
            if($code["upcoming"]){
                //show event list for day
                //$code["type"] = "event";
                $_REQUEST["chronosly_event_list_format"] = "upcoming";
                $_REQUEST["chronosly_event_list_time"] =  $code["upcoming"];

            }
            if(isset($code["show_past"]) or class_exists("Chronosly_Tickets_and_Repeats_Extended") ){
                //show event list for day
                //$code["type"] = "event";
                if($code["show_past"]) $_REQUEST["ch_show_past"] = 1;
                else if (class_exists("Chronosly_Tickets_and_Repeats_Extended") ){
                    $_REQUEST["ch_show_past"] = 1;
                    $_REQUEST["repeats_past_hide"] = 1;
                } 
                else $_REQUEST["ch_show_past"] = 0;

            }

            // $_REQUEST["ch_code"] = "[chronosly";
            // if(is_array($atts)){
            //     foreach($atts as $k=>$v){
            //         $_REQUEST["ch_code"] .= " $k='$v'";
            //     }
            // }
            // $_REQUEST["ch_code"] .= "]";
            // $_REQUEST["ch_code"] = str_replace(array("'", '"', "\\\\"), array("\'", "\"", "\\"), $_REQUEST["ch_code"]);
             $_REQUEST["ch_code"] = json_encode($atts);

           // echo $_REQUEST["ch_code"];
            ob_start();

            $this->run_templates($code["type"], $code);
            $content =  ob_get_clean();
            unset($_REQUEST["chronosly_event_list_format"]);
            unset($_REQUEST["chronosly_event_list_time"]);
            unset($_REQUEST["y"]);
            unset($_REQUEST["mo"]);
            unset($_REQUEST["week"]);
            unset($_REQUEST["count"]);
            unset($_REQUEST["category"]);
            unset($_REQUEST["organizer"]);
            unset($_REQUEST["place"]);
            unset($_REQUEST["small"]);
            unset($_REQUEST["pagination"]);
            unset($_REQUEST["ch_code"]);
            unset($_REQUEST["force_template"]);
            unset($_REQUEST["ch_show_past"]);
            unset($_REQUEST["repeats_past_hide"]);
            unset($_REQUEST["navigation"]);
            unset($_REQUEST["before_events"]);
            unset($_REQUEST["after_events"]);
            unset($_REQUEST["featured"]);
            unset($_REQUEST["ch-price-min"]);
            unset($_REQUEST["ch-price-max"]);
            unset($_REQUEST["ch_exclude"]);
            return $content;


        }



        //function for rendering templates via wp_query
        public function run_templates($type, $args){
            global $wp_query, $Post_Type_Chronosly, $pastformat;
            $pastformat = 0;//para enseñar en formato eventos pasados
            $wp_queryold = $wp_query;
            if(isset($_REQUEST["chronosly_event_list_time"]) and $_REQUEST["chronosly_event_list_time"] == "past"){
               $pastformat = 1;
               $_REQUEST["ch_show_past"] = 1;
            }
            $_REQUEST["shortcode"] = 1;
            $q = array();
            if($args["id"]) {
                 $q["post__in"] = explode(",", $args["id"]);

            }
            if($type == "place" and $args["place"]){
                $ids = explode(",", $args["place"]);
                if($q["post__in"]) $q["post__in"]= array_merge($q["post__in"],$ids);
                else $q["post__in"]= $ids;
            }
            if($type == "organizer" and $args["organizer"]){
                $ids = explode(",", $args["organizer"]);
                if($q["post__in"]) $q["post__in"]= array_merge($q["post__in"],$ids);
                else $q["post__in"]= $ids;
            }
            if($args["author"]) {
                $q["author"] = $args["author"];
            }
            if($args["exclude"]) {
                $_REQUEST["ch_exclude"] = $q["post__not_in"] = explode(",",$args["exclude"]);
            }
            if($args["category"]) {
                $_REQUEST["category"] = $args["category"];
            }
            if($args["organizer"]) {
                $_REQUEST["organizer"] = $args["organizer"];
            }
            if($args["place"]) {
                $_REQUEST["place"] = $args["place"];
            }
            if($args["count"]) {
                $q['posts_per_page']=$args["count"];
                 $q['numberposts']=$args["count"];
                  $_REQUEST["count"] = $args["count"];
            }
            if($args["pagination"]) {
                //$q['posts_per_page']=$args["count"];
                $_REQUEST['pagination']= 1;
            }

            if($_REQUEST["page"]) {
                //$q['posts_per_page']=$args["count"];
                $q['paged'] =$_REQUEST['page'];
            }
            if($args["before_events"]) {
                $_REQUEST["before_events"] = 1;
            }
            if($args["after_events"]) {
                $_REQUEST["after_events"] = 1;
            }
            if($args["featured"]) {
                if($args["featured"] == "yes" || $args["featured"] == "1") $feat = 1;
                else $feat = "";
                $q['post_meta_like']["featured"] = $feat;
                $_REQUEST["featured"] = $args["featured"];
            }
            if(isset($args["price_min"])){
                $q['post_meta_price_min'] = $args["price_min"];
                $_REQUEST["ch-price-min"] = $args["price_min"];
            }
            if(isset($args["price_max"])){
                $q['post_meta_price_max'] = $args["price_max"];
                $_REQUEST["ch-price-max"] = $args["price_max"];
            }

            if ( is_user_logged_in() )$q["post_status"] = array('publish', 'private');
            switch($type){
                case "calendar":
                     if(!$args["base"] && (stripos("http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI], Post_Type_Chronosly_Calendar::get_permalink()) !== FALSE || 
                        stripos("https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI], Post_Type_Chronosly_Calendar::get_permalink()) !== FALSE)) return;

                    if ( is_user_logged_in() ) new WP_Query("post_type=chronosly_calendar&post_status=publish,private");
                    else $wp_query = new WP_Query("post_type=chronosly_calendar");

                    include(Post_Type_Chronosly::chronosly_templates("shortcode_calendar"));
                 break;
                case "event":
                    if($args["single"]) {

                        if ( is_user_logged_in() ) query_posts("post_type=chronosly&p=".$args["id"]."&post_status=publish,private");
                        else  query_posts("post_type=chronosly&p=".$args["id"]);

                        include(Post_Type_Chronosly::chronosly_templates("shortcode_event"));

                    }
                    else {

                        add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                        $q["post_type"]="chronosly";
                        $q["posts_per_page"]=-1;
                        $wp_query = new WP_Query($q);
                        // echo "<pre>";print_r($wp_query);
                        include(Post_Type_Chronosly::chronosly_templates("shortcode_events"));

                    }
                 break;
                 case "json_format":


                    add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                    $q["post_type"]="chronosly";
                    $q["posts_per_page"]=-1;
                    $wp_query = new WP_Query($q);
                    $posts = $wp_query->posts;
                    echo "<div class='json_format' style='display:none'>".json_encode($posts).'</div>';
                    // $postr = array();
                    // foreach($posts as $post){
                    //     $postr[]= get_object_vars($post);
                    // }
                    // print_r($postr);


                 break;
                 case "map_format":
                    // print_r($args);
                    add_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
                    $q["post_type"]="chronosly";
                    $q["posts_per_page"]=-1;
                    $wp_query = new WP_Query($q);
                    // echo "<pre>";print_r($wp_query);
                    $repeated = Post_Type_Chronosly::get_events_repeated_by_date(-1, 1, $extra);
                    $elementos = Post_Type_Chronosly::get_days_by_date($wp_query, $repeated, -1, 1);
                    // print_r($repeated);
                    $elements = $elementos[0];
                    // echo "<pre>"; print_r($repeated);
                    // $posts = $wp_query->posts;
                    // echo "<div class='json_format' style='display:none'>".json_encode($posts).'</div>';
                    $postr = array();

                         $repeats = array();
// $tiempo_inicio = microtime(true);


                    foreach($elements as $el){
                        $xid = 0;
                        if(count($postr) > 700) break; 
                        ob_start();

                        if(is_array($el)){
                            $xid = $ide =  $el["id"];
                            if(isset($repeats[$xid])) $ide .= "_".$repeats[$xid];
                            // $Post_Type_Chronosly->template->print_template($el["id"], "dad1", "", "", "front", array("id" => $ide, "start" => $el["start"], "end" => $el["end"]));
                            $Post_Type_Chronosly->template->print_template($el["id"], "dad1", "", "", "front", array("id" => $ide, "start" => $el["start"], "end" => $el["end"]));
                        }
                        else {
                            $xid = $el;
                            // $Post_Type_Chronosly->template->print_template($el, "dad1", "", "", "front", array());
                            $Post_Type_Chronosly->template->print_template($el, "dad1", "", "", "front", array());
                        }
                        if(isset($repeats[$xid])) ++$repeats[$xid];
                        else $repeats[$xid] = 1;
                        $content = ob_get_clean();
                        $metas = $Post_Type_Chronosly->template->vars->metas;

                        $address = "";
                        if($metas["places_vars"][0]["metas"]["latlong"][0]){
                            $address = $metas["places_vars"][0]["metas"]["latlong"][0];
                        } else {
                            $v1 = $metas["places_vars"][0]["metas"]["evp_dir"][0];
                            if(stripos($v1, "class='lorem'") === FALSE) $address .= "$v1, ";
                             $v1 = $metas["places_vars"][0]["metas"]["evp_city"][0];
                            if(stripos($v1, "class='lorem'") === FALSE) $address .= "$v1, ";
                             $v1 = $metas["places_vars"][0]["metas"]["evp_country"][0];
                            if(stripos($v1, "class='lorem'") === FALSE) $address .= "$v1, ";
                            $v1 = $metas["places_vars"][0]["metas"]["evp_state"][0];
                            if(stripos($v1, "class='lorem'") === FALSE) $address .= "$v1, ";
                            $v1 = $metas["places_vars"][0]["metas"]["evp_pc"][0];
                            if(stripos($v1, "class='lorem'") === FALSE) $address .= "$v1";
                        }
                        if(!$address) continue;
                        if ($metas['repeat']) $repe = "&repeat=" . $metas['repeat'];
                        $id = explode("_", $Post_Type_Chronosly->template->vars->pid);
                        $id = $id[0];


                         $link = "ev_popup('".$id.$repe."&post_type=chronosly')";
                        
                        if(strlen(json_encode($content)) > 5){
                             $postr[]= array(
                                //"vars" => get_object_vars($post),
                                // "metas" => $metas,
                                "content" => $content,
                                "address" => $address,
                                "click" => $link,

                            );
                        }
                  

                        
                    }
                   echo json_encode($postr);

                 break;
                case "organizer":
                    if($args["single"]) {

                        if ( is_user_logged_in() ) query_posts("post_type=chronosly_organizer&p=".$args["id"]."&post_status=publish,private");
                        else query_posts("post_type=chronosly_organizer&p=".$args["id"]);
                        include(Post_Type_Chronosly::chronosly_templates("shortcode_organizer"));

                    }
                    else {

                        if(!has_action( 'posts_orderby', array("Post_Type_Chronosly_Organizer",'add_custom_organizers_orderby'))) add_action( 'posts_orderby', array("Post_Type_Chronosly_Organizer",'add_custom_organizers_orderby') );
                        $q["post_type"]="chronosly_organizer";
                        $wp_query = new WP_Query($q);
                        if(has_action( 'posts_orderby', array("Post_Type_Chronosly_Organizer",'add_custom_organizers_orderby'))) remove_action( 'posts_orderby', array("Post_Type_Chronosly_Organizer",'add_custom_organizers_orderby') );

                        include(Post_Type_Chronosly::chronosly_templates("shortcode_organizers"));

                    }
                 break;
                case "place":
                    if($args["single"]) {
                        if ( is_user_logged_in() ) query_posts("post_type=chronosly_places&p=".$args["id"]."&post_status=publish,private");
                        else query_posts("post_type=chronosly_places&p=".$args["id"]);
                        include(Post_Type_Chronosly::chronosly_templates("shortcode_place"));

                    }
                    else {
                        if(!has_action( 'posts_orderby', array("Post_Type_Chronosly_Places",'add_custom_places_orderby') )) add_action( 'posts_orderby', array("Post_Type_Chronosly_Places",'add_custom_places_orderby') );
                        $q["post_type"]="chronosly_places";
                        $wp_query = new WP_Query($q);
                        if(has_action( 'posts_orderby', array("Post_Type_Chronosly_Places",'add_custom_places_orderby') )) remove_action( 'posts_orderby', array("Post_Type_Chronosly_Places",'add_custom_places_orderby') );

                        include(Post_Type_Chronosly::chronosly_templates("shortcode_places"));

                    }
                 break;
                case "category":
                    if($args["single"] || ($args["id"] && !stripos($args["id"], ",") !== FALSE)) {
                        $cat = get_term($args["id"], "chronosly_category");

                        $wp_query->set("chronosly_category", $cat->slug);
                        include(Post_Type_Chronosly::chronosly_templates("shortcode_category"));

                    }
                    else {
                        if($args["id"]){
                            $ids = array();
                            if(is_array($args["id"])){

                                foreach ($args["id"] as $id) {
                                    $cat = get_term($id, "chronosly_category");
                                    $ids[]= $cat->slug;
                                }
                            }
                            else $ids = $args["id"];
                            $q["chronosly_category"] = $ids;
                        }
                        $wp_query = new WP_Query($q);
                        include(Post_Type_Chronosly::chronosly_templates("shortcode_categories"));

                    }
                 break;


            }
            wp_reset_query();//reset the query status
            $wp_query = $wp_queryold;
        }



      function  js_ch_run_shortcode(){
        $code = json_decode( str_replace('\"' , '"', urldecode($_REQUEST["ch_code"])));

        // print_r($code);

        $decode = "[chronosly";
        if($code){
            foreach($code as $k=>$v){
                $decode .= " $k='$v'";
            }
        }
        $decode .= "]";
        //if($_REQUEST['paged']) $code = str_replace();
        if(strpos($decode, "]") !== false ) echo do_shortcode($decode);
          die();
      }

        function title_like_where( $where, &$wp_query ) {
            global $wpdb;
            if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $post_title_like ) . '%\'';

            }
            return $where;
        }

        function meta_like_where( $where, &$wp_query ) {
            global $wpdb;
            if ( $meta_like = $wp_query->get( 'post_meta_like' ) ) {
                if(count($meta_like) > 1){
                    foreach ($meta_like as $key=>$meta){
                        $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "'.$key.'" AND ' . $wpdb->postmeta . '.meta_value LIKE \'%' . esc_sql( $meta ) . '%\')';

                    }
                } else $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "'.key($meta_like).'" AND ' . $wpdb->postmeta . '.meta_value LIKE \'%' . esc_sql( $meta_like[key($meta_like)] ) . '%\')';


            }
            return $where;
        }

        function meta_price_where( $where, &$wp_query ) {
            global $wpdb;
            if ( $meta_like = $wp_query->get( 'post_meta_price_min' ) ) {
                $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "tickets"  AND ' . $wpdb->postmeta . '.meta_value like \'%{"name":"price","value":"%\' and  SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  >= '.$meta_like.')';

            } else if($meta_like === "0") {
                $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "tickets"  AND ((' . $wpdb->postmeta . '.meta_value like \'%{"name":"price","value":"%\' and  SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  >= 0) OR ' . $wpdb->postmeta . '.meta_value = ""))';
            }
            if ( $meta_like2 = $wp_query->get( 'post_meta_price_max' ) and $meta_like !== "0") {
                $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "tickets" AND ' . $wpdb->postmeta . '.meta_value like \'%{"name":"price","value":"%\' and  SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  <= '.$meta_like2.')';

            } else if($meta_like2 === "0") $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "tickets"  AND ((' . $wpdb->postmeta . '.meta_value like \'%{"name":"price","value":"%\' and  SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  <= 0) OR ' . $wpdb->postmeta . '.meta_value = ""))';
             else if($meta_like === "0") $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "tickets"  AND ((' . $wpdb->postmeta . '.meta_value like \'%{"name":"price","value":"%\' and  (SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  <= 0 OR SUBSTRING_INDEX( SUBSTRING_INDEX( meta_value,  \'{"name":"price","value":"\', -1 ) ,  \'"}\', 1 )  <= '.$meta_like2.')) OR ' . $wpdb->postmeta . '.meta_value = ""))';
            return $where;
        }

        function meta_near_where( $where, &$wp_query ) {
            global $wpdb;
            if ( $box = $wp_query->get( 'post_meta_location' ) ) {
                $where .= ' AND  exists(select post_id FROM  '.$wpdb->postmeta.' where '. $wpdb->posts .'.ID = ' . $wpdb->postmeta . '.post_id AND  '.$wpdb->postmeta . '.meta_key = "latlong"
                    AND (SUBSTRING_INDEX(REPLACE('.$wpdb->postmeta . '.meta_value, "(", ""), ",", 1) BETWEEN ' . $box['min_lat']. ' AND ' . $box['max_lat'] . ')
                    AND (SUBSTRING_INDEX(REPLACE('.$wpdb->postmeta . '.meta_value, ")", ""), ",", -1) BETWEEN ' . $box['min_lng']. ' AND ' . $box['max_lng']. '))';
            }

            return $where;
        }


        // END public function plugin_settings_page()



    } // END
} // END

