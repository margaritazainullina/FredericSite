<?php
if(!class_exists('Post_Type_Chronosly_Calendar'))
{
    /**
     * A PostTypeTemplate class that provides 3 additional meta fields
     */
    class Post_Type_Chronosly_Calendar
    {
        const POST_TYPE = "chronosly_calendar";
        private $_meta  = array(
            'meta_a',
            'meta_b',
            'meta_c',
        );

        /**
         * The Constructor
         */
        public function __construct()
        {
            // register actions
            add_action('init', array(&$this, 'init'));
            //add_action('admin_init', array(&$this, 'admin_init'));
        } // END public function __construct()

        /**
         * hook into WP's init action hook
         */
        public function init()
        {
            // Initialize Post Type
            $this->create_post_type();
            //creamos el calendario base

            $args  = array(
                'post_type' => "chronosly_calendar"
            );
            $query = new WP_Query( $args );
            if(!$query->have_posts()){
                $args = array(
                    "post_title" => "calendar",
                    'post_status'      => 'publish',
                    "post_type" => self::POST_TYPE
                );
                $id = wp_insert_post($args);
            }
            else if(count($query->posts) > 1){
                $mycustomposts = get_pages( array( 'post_type' => 'chronosly_calendar',  'number' => 1000) );
                foreach( $mycustomposts as $mypost ) {
                    // Delete's each post.
                    wp_delete_post( $mypost->ID, true);
                    // Set to False if you want to send them to Trash.
                }
            }
            add_action('save_post', array(&$this, 'save_post'));
        } // END public function init()

        /**
         * Create the post type
         */
        public function create_post_type()
        {
            global $Post_Type_Chronosly;
            $slug = "chronosly-calendar";
           if($Post_Type_Chronosly->settings['chronosly-calendar-slug']) $slug = $Post_Type_Chronosly->settings['chronosly-calendar-slug'];
            add_rewrite_rule($slug.'/year_([0-9]+)/?$','index.php?post_type=chronosly_calendar&y=$matches[1]','top');
            add_rewrite_rule($slug.'/year_([0-9]+)/month_([0-9]+)/?$','index.php?post_type=chronosly_calendar&y=$matches[1]&mo=$matches[2]','top');
            add_rewrite_rule($slug.'/year_([0-9]+)/week_([0-9]+)/?$','index.php?post_type=chronosly_calendar&y=$matches[1]&week=$matches[2]','top');
            add_filter('query_vars',  array("Post_Type_Chronosly_Calendar",'add_query_vars'));

            register_post_type(self::POST_TYPE,
                array(
                    'labels' => array(
                        'name' => __("Calendars", "chronosly"),
                        'singular_name' => __("Calendar", "chronosly"),
                        'add_new' =>  __("Add new calendar", "chronosly"),
                        'add_new_item' =>  __("Add new calendar", "chronosly"),
                        'view_item' =>  __("View calendar", "chronosly"),
                        'search_items' =>  __("Search calendar", "chronosly"),


                    ),
                    'rewrite' => array('slug' => $slug, 'with_front' => false, 'feeds' => true),
                    'public' => true,
                    'show_ui' => true,
                    'map_meta_cap'  => true,
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
                    'show_in_menu'  => false,
                    'capability' => 'chronosly_author',
                    'has_archive' => true,
                    'description' => __("Calendar type for create event calendar", "chronosly"),
                    'supports' => array(
                    )
                )
            );


            if($Post_Type_Chronosly->settings['chronosly-allow-flush']and !$Post_Type_Chronosly->settings['chronosly-calendar-flushed']) {
                flush_rewrite_rules();
                $Post_Type_Chronosly->settings['chronosly-calendar-flushed'] = 1;
                update_option('chronosly-settings', serialize($Post_Type_Chronosly->settings));
            
            }
            //add_filter( 'map_meta_cap', array("Post_Type_Chronosly",'chronosly_map_meta_cap'), 10, 4 );
            add_filter( 'template_include', array("Post_Type_Chronosly",'chronosly_templates') );
            add_filter('template_redirect',  array(&$this,'calendar_override_404'));
        }

        //adding custom vars to query for url firendly
        public static function add_query_vars($aVars) {
            $aVars[] = "y"; // represents of the year
            $aVars[] = "mo"; // represents of the month
            $aVars[] = "week"; // represents of the week
            return $aVars;
        }

// hook add_query_vars function into query_vars

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

        public static function get_days_by_date($year, $month, $week, $query,$repeated){
            if(!$year) $year = date("Y");
            if(!$month and !$week){
                $days =  Post_Type_Chronosly_Calendar::yearArray($year);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_query($year,0,0, $query);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_repeated($year,0,0, $repeated, $elements);
            } else if($week){
                $days =  Post_Type_Chronosly_Calendar::weekArray($week, $year);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_query($year,0,$week, $query);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_repeated($year,0,$week, $repeated, $elements);
            }
            else if($month){
                $days =  Post_Type_Chronosly_Calendar::monthArray($month, $year);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_query($year,$month,0, $query);
                $elements =  Post_Type_Chronosly_Calendar::get_array_days_by_repeated($year,$month,0, $repeated, $elements);
            }
            return array_merge($days,$elements["days"]);


        }

        public static function get_event_position_by_hour($meta, $id){

            //se podria añadir el order por order en vez de por time
            $settings =  unserialize(get_option("chronosly-settings"));
            $pos = $id;

            if(isset($meta["ev-from-h"][0]) and $meta["ev-from-h"][0] != "" and $meta["ev-from-h"][0] != "00") $pos += $meta["ev-from-h"][0]*60*10000;
            else $pos += 10000000;
            if(isset($meta["ev-from-m"][0]) and $meta["ev-from-m"][0] != "00") $pos += $meta["ev-from-m"][0]*10000;
            if($settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos += 100000000;
            return $pos;
        }

        public static function get_array_days_by_query($year, $month, $week, $query){
            $elements = array("ids"=>array(), "days"=>array());
            $settings =  unserialize(get_option("chronosly-settings"));
            if(!$month and !$week){
                while ( $query->have_posts() ){
                    $query->the_post();
                    if(!Post_Type_Chronosly::filter(get_the_ID())) continue;

                    $elements["ids"][] = get_the_ID();
                    $meta = get_post_meta(get_the_ID());
                    if(isset($meta["events"][0]) and $meta["events"][0]) {
                        $eventos = json_decode($meta["events"][0]);
                        $tickets = array();
                        if( isset($meta["tickets"][0])) $tickets = json_decode($meta["tickets"][0]);
                        $elements = Post_Type_Chronosly_Calendar::seasons($eventos, $year, $month, $week, $start_ini, $end, get_the_ID(), $elements, $tickets);
                    }
                    else if(isset($meta["ev-from"][0])){
                        


                        //si empieza en el mismo año que el calendario debemos empezar en el dia especifico
                        if(date("Y", strtotime($meta["ev-from"][0])) == $year){
                           $start =  strtotime($meta["ev-from"][0]);
                        }//si no empezamos en el primer dia del año
                        else $start = strtotime("01-01-".$year);
                        if(isset($meta["ev-to"][0]) and date("Y", strtotime($meta["ev-to"][0])) == $year){
                            $end =  strtotime($meta["ev-to"][0]);
                        }//si no empezamos en el primer dia del año
                        else $end = strtotime("31-12-".$year);
                        $end_top = strtotime("31-12-".$year);
                        if($settings["chronosly_week_start"] == 1) {
                            //$start -= (60*60*24);
                            //$end -= (60*60*24);
                        }

                        $start_ini = $start;
                        $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                        $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, get_the_ID());
                        do{
                            if($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d"))) {$elements["days"][date('Y-m-d',$start)][$pos]= get_the_ID();}
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)));

                        //repeats
                       $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start_ini, $end, get_the_ID(), $elements);


                    }

                }

            } else if($week){
                while ( $query->have_posts() ){
                    $query->the_post();
                    if(!Post_Type_Chronosly::filter(get_the_ID())) continue;

                    $elements["ids"][] = get_the_ID();

                    $meta = get_post_meta(get_the_ID());

                    if(isset($meta["events"][0]) and $meta["events"][0]) {
                        $eventos = json_decode($meta["events"][0]);
                        $tickets = array();
                        if( isset($meta["tickets"][0])) $tickets = json_decode($meta["tickets"][0]);
                        $elements = Post_Type_Chronosly_Calendar::seasons($eventos, $year, $month, $week, $start_ini, $end, get_the_ID(), $elements,$tickets);
                    }
                    else if(isset($meta["ev-from"][0])){
                        //si empieza en el mismo año y week que el calendario debemos empezar en el dia especifico
                         if($settings["chronosly_week_start"] == 1) {
                            $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT))-(60*60*24);
                            $top = strtotime("+7 days", $botom);
                         } else {
                             $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT));
                             $top = strtotime("+7 days", $botom);
                         }

                        if(strtotime($meta["ev-from"][0]) >=  $botom
                            and  strtotime($meta["ev-from"][0]) <= $top){
                            $start =  strtotime($meta["ev-from"][0]);
                        }//si no empezamos en el primer dia de semana
                        else {
                            $start = $botom;
                        }

                        if(isset($meta["ev-to"][0]) and strtotime($meta["ev-to"][0]) <= $top){
                            $end =  strtotime($meta["ev-to"][0]);
                        }//si no acabamos en el ultimo dia del mes
                        else $end = $top;
                        $end_top = $top;

                        if($settings["chronosly_week_start"] == 1) {
                                // $start -= (60*60*24);
                                // $end -= (60*60*24);
                        }

                        $start_ini = $start;
                        $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, get_the_ID());
                        $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                        do{
                            $elements["days"][date('Y-m-d',$start)][$pos]= get_the_ID();
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours))  );

                        //repeats
                       $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start_ini, $end, get_the_ID(), $elements);
                    }

                }
            }
            else if($month){
                while ( $query->have_posts() ){
                        $query->the_post();
                    if(!Post_Type_Chronosly::filter(get_the_ID())) continue;

                    $elements["ids"][] = get_the_ID();

                    $meta = get_post_meta(get_the_ID());
                       

                    if(isset($meta["events"][0]) and $meta["events"][0]) {
                        $eventos = json_decode($meta["events"][0]);
                        $tickets = array();
                        if( isset($meta["tickets"][0])) $tickets = json_decode($meta["tickets"][0]);
                        $elements = Post_Type_Chronosly_Calendar::seasons($eventos, $year, $month, $week, $start_ini, $end, get_the_ID(), $elements,$tickets);
                    }
                    else if(isset($meta["ev-from"][0])){
                        //si empieza en el mismo año y mes que el calendario debemos empezar en el dia especifico
                        if(date("Y-m", strtotime($meta["ev-from"][0])) == date("Y-m", strtotime($year."-".$month))){
                            $start =  strtotime($meta["ev-from"][0]);
                        }//si no empezamos en el primer dia del mes
                        else $start = strtotime("01-$month-$year");
                        //si acaba en este mes acabamos en el dia
                        if(isset($meta["ev-to"][0]) and date("Y-m", strtotime($meta["ev-to"][0])) ==  date("Y-m", strtotime($year."-".$month))){
                            $end =  strtotime($meta["ev-to"][0]);
                        }//si no acabamos en el ultimo dia del mes
                        else $end = strtotime(date("Y-m-t", strtotime($year."-".$month)));
                        $end_top = strtotime(date("Y-m-t", strtotime($year."-".$month)));

                        if($settings["chronosly_week_start"] == 1) {
                           // $start -= (60*60*24);
                           // $end -= (60*60*24);


                        }

                        $start_ini = $start;
                        $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, get_the_ID());
                        $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                        do{
                            $elements["days"][date('Y-m-d',$start)][$pos]= get_the_ID();
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)));

                        //repeats
                       $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start_ini, $end, get_the_ID(), $elements);

                    }

                }
            }
            return $elements;

        }

        public static function get_array_days_by_repeated($year, $month, $week, $query, $elements){
            $settings =  unserialize(get_option("chronosly-settings"));
            if(!$month and !$week){
                while ( $query->have_posts() ){
                    $query->the_post();
                    // if(is_array($elements["ids"]) and in_array(get_the_ID(), $elements["ids"])) continue;//if the day is already done.
                    $meta = get_post_meta(get_the_ID());
                    if(isset($meta["ev-from"][0])){
                        //Miramos cuando empieza y acaba y generamos sus repeticiones para ver si hay que mostrarlo
                       $start =  strtotime($meta["ev-from"][0]);
                        $end =  strtotime($meta["ev-to"][0]);
                        if($settings["chronosly_week_start"] == 1) {
                            //$start -= (60*60*24);
                            //$end -= (60*60*24);
                        }


                        //do repeats
                        if(Post_Type_Chronosly::filter(get_the_ID())) $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start, $end, get_the_ID(), $elements, 1);
                    }

                }

            } else if($week){
                while ( $query->have_posts() ){
                    $query->the_post();
                    // if(is_array($elements["ids"]) and in_array(get_the_ID(), $elements["ids"])) continue;//if the day is already done.
                    $meta = get_post_meta(get_the_ID());

                    if(isset($meta["ev-from"][0])){

                            $start =  strtotime($meta["ev-from"][0]);

                            $end =  strtotime($meta["ev-to"][0]);


                        if($settings["chronosly_week_start"] == 1) {
                           //  $start -= (60*60*24);
                           // $end -= (60*60*24);
                        }
                        // echo get_the_ID()." ".date("d-m-Y ", $start)."<br/>";

                        //do repeats
                        if(Post_Type_Chronosly::filter(get_the_ID())) $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start, $end, get_the_ID(), $elements, 1);
                    }

                }
            }
            else if($month){
                while ( $query->have_posts() ){
                    $query->the_post();
                    // if(is_array($elements["ids"]) and in_array(get_the_ID(), $elements["ids"])) continue;//if the day is already done.

                    $meta = get_post_meta(get_the_ID());

                    if(isset($meta["ev-from"][0])){

                            $start =  strtotime($meta["ev-from"][0]);

                            $end =  strtotime($meta["ev-to"][0]);

                        if($settings["chronosly_week_start"] == 1) {
                            //$start -= (60*60*24);
                            //$end -= (60*60*24);
                        }
                        if(Post_Type_Chronosly::filter(get_the_ID())) $elements =  Post_Type_Chronosly_Calendar::repeats($meta, $year, $month, $week,$start, $end, get_the_ID(), $elements, 1);

                    }

                }
            }
            return $elements;

        }


        public static function repeats($meta, $year, $month, $week, $start, $end, $id, $elements, $repeated=0){
                                        // echo $id." ".date('Y-m-d H:i',$start)." ".date('Y-m-d H:i',$end)." ".date('Y-m-d H:i',$end_top)."<br/>";

            $settings =  unserialize(get_option("chronosly-settings"));
            $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, $id);
            if(isset($meta["ev-repeat"][0]) and $meta["ev-repeat"][0] != "" and isset($meta["ev-repeat-every"][0]) and $meta["ev-repeat-every"][0] > 0){
                if(!$month and !$week){
                    if($repeated)  $start_min = strtotime("01-01-".$year);//start building array
                    $end_top = strtotime("31-12-".$year);//limit of repeats per year
                    if(isset($meta["ev-repeat-option"][0]) and $meta["ev-repeat-option"][0] == "until" and
                        isset($meta["ev-until"][0]) and strtotime($meta["ev-until"][0]) < $end_top){
                        $end_top = strtotime($meta["ev-until"][0]);
                    }
                } else if($week){

                    $st1 =  strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT));
                    if($repeated)  $start_min = $st1;//start building array
                    $end_top = strtotime("+7 days", $st1);//limit of repeats per week


                    if(isset($meta["ev-repeat-option"][0]) and $meta["ev-repeat-option"][0] == "until" and
                        isset($meta["ev-until"][0]) and strtotime($meta["ev-until"][0]) < $end_top){
                        $end_top = strtotime($meta["ev-until"][0]);
                    }
                    // echo $end_top;
                    // if($settings["chronosly_week_start"] == 1) {
                    //     // $start_min -= (60*60*24);
                    //     // $end_top -= (60*60*24);
                    // }
                } else if($month){
                    if($repeated)  $start_min = strtotime("01-$month-$year");//start building array
                    $end_top = strtotime(date("Y-m-t", strtotime($year."-".$month)));//limit of repeats per month
                    if(isset($meta["ev-repeat-option"][0]) and $meta["ev-repeat-option"][0] == "until" and
                        isset($meta["ev-until"][0]) and strtotime($meta["ev-until"][0]) < $end_top){
                        $end_top = strtotime($meta["ev-until"][0]);
                    }
                }
                if($repeated and $start_min > $end_top) return $elements;
                else if(!$repeated and $start > $end_top) return $elements;
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
                            $start1 = $start;
                            $end = $start+$event_days;//recalculamos el final
                             $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                            --$count;
                            while($start <= $end and $start <= $end_top){//rellenamos los dias de este tramo
                                if($start >= $start_min and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)) and ($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d")))) $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id, "start" => $start1, "end" => $end );
                                $start = strtotime("+ 1 day",$start);
                                //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            }
                            $start = strtotime("- 1 day",$start);//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }

                        break;
                    case "week":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            // echo $id." ".date('Y-m-d H:i',$start)." ".date('Y-m-d H:i',$endHours)." ".date('Y-m-d H:i',$end_top)."<br/>";
                            // echo $event_days;
                            $start += ($distance*7*60*60*24)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo
                            $start1 = $start;

                            $end = $start+$event_days;//recalculamos el final
                            $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                            --$count;
                            //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            while($start <= $end and $start <= $end_top){//rellenamos los dias de este tramo
                                if((!$repeated or $start >= $start_min) and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)) and ($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d")))) $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id, "start" => $start1, "end" => $end );
                               // echo $id." ".date('Y-m-d H:i',$start)." ".date('Y-m-d H:i',$endHours)." ".date('Y-m-d H:i',$end_top)."<br/>";

                                $start = strtotime("+ 1 day",$start);
                                //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            }
                            $start = strtotime("- 1 day",$start);//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                    case "month":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start = strtotime("+$distance month", $start)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo
                            $start1 = $start;
                            // echo $event_days;
                            $end = $start+$event_days;//recalculamos el final
                            $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);

                            --$count;
                            //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            while($start <= $end and $start <= $end_top){//rellenamos los dias de este tramo
                                if((!$repeated or $start >= $start_min) and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)) and ($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d")))) $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id, "start" => $start1, "end" => $end );
                                // echo $id." ".date('Y-m-d H:i',$start)." ".date('Y-m-d H:i',$endHours)." ".date('Y-m-d H:i',$end_top)."<br/>";
                                $start = strtotime("+ 1 day",$start);
                            }
                            $start = strtotime("- 1 day",$start);//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                    case "year":
                        $start = $end;

                        while($start < $end_top and $count){//mientras no estemos en el tope superior
                            $start = strtotime("+$distance year", $start)-$event_days;//añadimos la distancia de semanas, restando los dias que dura el evento...si el evento dura mas de una semana es un total absurdo usarlo
                            $start1 = $start;

                            $end = $start+$event_days;//recalculamos el final
                             $endHours = $end + ($meta["ev-to-h"][0]*60*60) + ($meta["ev-to-m"][0]*60);
                            --$count;
                            //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            while($start <= $end and $start <= $end_top ){//rellenamos los dias de este tramo
                                if((!$repeated or $start >= $start_min) and ($endHours < $end_top or date('Y-m-d',$start) != date('Y-m-d',$endHours)) and ($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d")))) $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id, "start" => $start1, "end" => $end );
                                $start = strtotime("+ 1 day",$start);
                                //echo date('Y-m-d',$start)." ".date('Y-m-d',$end)."<br/>";
                            }
                            $start = strtotime("- 1 day",$start);//restamos un dia porque si no el ultimo dia no se cuenta al salir del bucle
                        }
                        break;
                }

            }
            return $elements;
        }
            
        public static function seasons($eventos, $year, $month, $week, $start, $end, $id, $elements, $tickets){
            $settings =  unserialize(get_option("chronosly-settings"));
            
                // print_r($tickets);
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

                $pos = $id;
                
                if( date("H", $from) != "00") $pos += date("H", $from)*60*10000;
                else $pos += 10000000;
                if(date("i", $from) != "00") $pos += date("i", $from)*10000;
                if($settings["chronosly_featured_first"] and (!isset($meta["featured"][0]) or $meta["featured"][0] != 1)) $pos += 100000000;
                // echo date('Y-m-d',$from)."<br>";
                // echo date('Y-m-d',$to)."<br>";
                //repeats
                if(!$month and !$week){
                    if(date("Y", $from) == $year){
                       $start =  $from;
                    }//si no empezamos en el primer dia del año
                    else $start = strtotime("01-01-".$year);
                    if($to >= $start and $from <= strtotime("31-12-".$year." 23:59")){
                        if(date("Y", $to) == $year){
                            $end =  $to;
                        }//si no empezamos en el primer dia del año
                        else $end = strtotime("31-12-".$year);
                       
                        $start_ini = $start;

                        // $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, get_the_ID());
                        do{
                            if($settings["hide_past_on_calendar"] != 1 or $start>= strtotime(date("Y-m-d"))) {
                                $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id,"start" => $start_ini, "end" => $end , "h" => date("H", $from), "m"=> date("i", $from), "eh" => date("H", $to), "em"=> date("i", $to));}
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end );
                    }

                } else if($week){
                   
                    //si empieza en el mismo año y week que el calendario debemos empezar en el dia especifico
                     // if($settings["chronosly_week_start"] == 1) {
                     //    $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT))-(60*60*24);
                     //    $top = strtotime($year."W".str_pad($week+1, 2, '0', STR_PAD_LEFT))-(1*60*60*24);
                     // } else {
                     //     $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT));
                     //    $top = strtotime($year."W".str_pad($week+1, 2, '0', STR_PAD_LEFT));

                     // }
                      if($settings["chronosly_week_start"] == 1) {
                            $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT))-(60*60*24);
                            $top = strtotime("+7 days", $botom);
                         } else {
                             $botom = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT));
                             $top = strtotime("+7 days", $botom);
                         }
                    // echo "$botom $top<br/>";
                    if($from >=  $botom
                        and  $from <= $top){
                        $start =  $from;
                    }//si no empezamos en el primer dia de semana
                    else {
                        $start = $botom;
                    }
                    $start_ini = $start;
                    // echo "$from >=  $botom";
                    // echo "<br>".date("Y-m-d", $from);
                    if($from < $top && $to >= $start){
                        if($to < $top){
                            $end =  $to;
                        }//si no acabamos en el ultimo dia del mes
                        else $end = $top;

                     
                        // $pos = Post_Type_Chronosly_Calendar::get_event_position_by_hour($meta, get_the_ID());

                        do{
                            // echo date('Y-m-d',$start)." ";
                            $elements["days"][date('Y-m-d',$start)][$pos]= array("id" => $id,"start" => $start_ini, "end" => $end , "h" => date("H", $from), "m"=> date("i", $from), "eh" => date("H", $to), "em"=> date("i", $to));
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end );
                    }

                    
                }
                else if($month){
                   
                    //si empieza en el mismo año y mes que el calendario debemos empezar en el dia especifico
                    if(date("Y-m", $from) == date("Y-m", strtotime($year."-".$month))){
                        $start =  $from;
                    }//si no empezamos en el primer dia del mes
                    else $start = strtotime("01-$month-$year");

                 if($to >= $start and $from <= strtotime( date("Y-m-t", strtotime($year."-".$month))." 23:59")){
                   
                        // echo "<br>".date("Y-m-d", $from);
                        //si acaba en este mes acabamos en el dia
                        if(date("Y-m", $to) ==  date("Y-m", strtotime($year."-".$month))){
                            $end =  $to;
                        }//si no acabamos en el ultimo dia del mes
                        else $end = strtotime(date("Y-m-t", strtotime($year."-".$month)));

                        $start_ini = $start;
                        // echo date("Y-m", $from);
                        // echo date("Y-m", strtotime($year."-".$month));

                        do{
                            $elements["days"][date('Y-m-d',$start)][$pos]=array("id" => $id,"start" => $start_ini, "end" => $end , "h" => date("H", $from), "m"=> date("i", $from), "eh" => date("H", $to), "em"=> date("i", $to));
                            $start = strtotime("+ 1 day",$start);
                        }while ( $start<=$end );
                    }

                }
            }
            return $elements;
           
        }

        public static function yearArray($year){
            $range = array();
            $settings =  unserialize(get_option("chronosly-settings"));
            $date = new DateTime;
            $date->setISODate($year-1, 53);
            $add_week = ($date->format("W") !== "53" ? "" : " -1 week");
            $start = strtotime($year . 'W' . str_pad(1, 2, '0', STR_PAD_LEFT).$add_week);
            $date = new DateTime;
            $date->setISODate($year, 53);
           // echo $date->format("W");
            //$add_week = ($date->format("W") === "53" ? "" : " +1 week");
            $add_week = " +1 week";
            $end =  strtotime($year . 'W' . str_pad(53, 2, '0', STR_PAD_LEFT).$add_week);
            if($settings["chronosly_week_start"] == 1) {
                $start -= (60*60*24);
                $start2 = $start+(7*60*60*24);
                if(date("d",$start2) == "01") $start = $start2;
                if(date("d",$end) != "01") $end -= (60*60*24);
                else $end += (6*60*60*24);
            }
            do{
                $range[date('Y-m-d',$start)] = 0;
                $start = strtotime("+ 1 day",$start);
            }while ( $start<$end );

            return $range;
        }

        public static function monthArray($month, $year){
            $range = array();
            $settings =  unserialize(get_option("chronosly-settings"));

            $start1 = strtotime($year . '-'.$month."-01");
            $dw =  date( "w", $start1);
            if($dw) $less = $dw-1;
            else $less = 6;
            if($less)$start = strtotime("-$less day", $start1);
            else $start = $start1;
            $end =  strtotime(date("Y-m-t", $start1)." +1 day");
            $d = date( "w", $end);
            if($d != 1){
              if($d == 0) $end = strtotime("+1 day", $end);
              else {
                  $n = 8-$d;
                  $end = strtotime("+$n day", $end);
              }
            }
            if($settings["chronosly_week_start"] == 1) {
                $start -= (60*60*24);
                $start2 = $start+(7*60*60*24);
                if(date("d",$start2) == "01") $start = $start2;
                if(date("d",$end) != "01") $end -= (60*60*24);
                else $end += (6*60*60*24);
            }


            do{
                $range[date('Y-m-d',$start)] = 0;
                $start = strtotime("+ 1 day",$start);
            }while ( $start<$end );

            return $range;
        }

        public static function weekArray($week, $year){
            $range = array();
            $settings =  unserialize(get_option("chronosly-settings"));
            $start = strtotime($year."W".str_pad($week, 2, '0', STR_PAD_LEFT));
            $end = strtotime("+1 week", $start);
            if($settings["chronosly_week_start"] == 1) {
                $start -= (60*60*24);
                $end -= (60*60*24);
            }
            do{
                $range[date('Y-m-d',$start)] = 0;
                $start = strtotime("+ 1 day",$start);
            }while ( $start<$end );

            return $range;
        }


        public static function get_events_by_date($year, $month, $week){
            $settings =  unserialize(get_option("chronosly-settings"));
            if(!$year) $year = date("Y");
            $search2 = "$year-01-01";
            $search1 = "$year-12-31";

            if($month){
                if($month < 10) $month = "0$month";
                $search2= "$year-$month-01";
                $search1= date("Y-m-t", strtotime($search2));

            }


            if($week){
                $d = strtotime($year . 'W' . str_pad($week, 2, '0', STR_PAD_LEFT));
                if($settings["chronosly_week_start"] == 1) {
                    $d -= (60*60*24);
                }
                $search2=  date('Y-m-d', $d);
                $search1=  date('Y-m-d', strtotime("+6 day", $d));


                //echo "from <= $search1 and to >= $search2";


            }

            if($settings["hide_past_on_calendar"] == 1 and time() > strtotime($search2)) $search2 =  date('Y-m-d');
                $metaquery = array(
                    'relation' => "AND",
                    array(
                        'key' => 'ev-from',
                        'value' => $search1,
                        'compare' => '<='
                    ),
                    array(
                        'key' => 'ev-to',
                        'value' => $search2,
                        'compare' => '>='
                    )
                );

            $args  = array(
                'posts_per_page'   => -1,
                'numberposts'       => -1,

                'offset'           => 0,
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

            if( $_REQUEST["event_name"]) {
                 $args['post_title_like'] =$_REQUEST["event_name"];
            } 
            if( $_REQUEST["ch_exclude"]) {
                 $args['post__not_in'] =$_REQUEST["ch_exclude"];
            }

            if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');

            return  new WP_Query( $args );
        }

        public static function get_events_repeated_by_date($year, $month, $week){
            $settings =  unserialize(get_option("chronosly-settings"));
            if(!$year) $year = date("Y");
            $search2 = "$year-01-01";
            $search1 = "$year-12-31";

            if($month){
                if($month < 10) $month = "0$month";
                $search2= "$year-$month-01";
                $search1= date("Y-m-t", strtotime($search2));

            }


            if($week){
                $d = strtotime($year . 'W' . str_pad($week, 2, '0', STR_PAD_LEFT));
                if($settings["chronosly_week_start"] == 1) {
                    $d -= (60*60*24);
                }
                $search2=  date('Y-m-d', $d);
                $search1=  date('Y-m-d', strtotime("+6 day", $d));


                //echo "from <= $search1 and to >= $search2";


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
                        'value' => $search1,
                        'compare' => '<='
                    )
                );

            $args  = array(
                'posts_per_page'   => -1,
                'numberposts'       => -1,

                'offset'           => 0,
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

            return  new WP_Query( $args );
        }

        public function calendar_override_404() {
            global $wp_query, $post;
            if ( $wp_query->post_count == 0 and $wp_query->query_vars["post_type"] == "chronosly_calendar" )
            {
                $week = get_query_var( 'week' );
                $month = get_query_var( 'mo' );
                $year = get_query_var( 'y' );

                if( $year )
                {
                    $wp_query->set("y", $year);
                    $_REQUEST["y"] = $year;
                }

                if( $month )
                {
                    $wp_query->set("mo", $month);
                    $_REQUEST["mo"] = $month;

                }

                if ( $week )
                {

                    $wp_query->set("week", $week);
                    $_REQUEST["week"] = $week;

                }




                if ( $week || $month || $year )
                {
                    status_header( 200 );

                    $id= 202; // need an id
                    $post  = get_post($id);

                    $wp_query->queried_object=$post;
                    $wp_query->post=$post;
                    $wp_query->found_posts = 1;
                    $wp_query->post_count = 1;
                    $wp_query->is_404 = false;
                    $wp_query->posts = array($post);
                    $wp_query->is_archive = 1;

                }
            }
            //print_r($wp_query);
        }

       public function get_permalink(){
            $url = get_post_type_archive_link( 'chronosly_calendar' );
            if(stripos($url, "?") === false and substr($url, -1) != "/") $url .= "/";

           return $url;

       }


    } // END class Post_Type_Template
} // END if(!class_exists('Post_Type_Template'))
