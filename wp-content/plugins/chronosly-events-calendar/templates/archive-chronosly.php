<?php


$limit = (isset($_REQUEST["count"]) and $_REQUEST["count"])?$_REQUEST["count"]:$Post_Type_Chronosly->settings["chronosly_events_x_page"];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if(isset($_REQUEST["page"])) $paged = $_REQUEST["page"];
if(isset($_REQUEST["js_render"])) $_REQUEST["shortcode"] = 1;
if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"])  {
     get_header();


        $args  = array(
            'posts_per_page'   => -1,
            'numberposts'       => -1,

           // 'paged'           => $paged,
            'category'         => '',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'chronosly',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'publish'
             );
    if ( is_user_logged_in() ) $args["post_status"] = array('publish', 'private');
    $query = new WP_Query( $args );

} else {
    $query = $wp_query;
}


remove_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );

$repeated = Post_Type_Chronosly::get_events_repeated_by_date($limit, $paged);

$elementos = Post_Type_Chronosly::get_days_by_date($query, $repeated, $limit, $paged);
$elements = $elementos[0];

if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"])  {

    ?>
    <section id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

      <?php
}
echo '<div class="chronosly-closure">';

                $Post_Type_Chronosly->template->templates_tabs("dad1", 1);
                $stilo = "margin:auto;padding:30px;";
                if($Post_Type_Chronosly->settings["chronosly_template_max"]) $stilo .= "max-width:".$Post_Type_Chronosly->settings["chronosly_template_max"]."px;";
                if($Post_Type_Chronosly->settings["chronosly_template_min"]) $stilo .= "min-width:".$Post_Type_Chronosly->settings["chronosly_template_min"]."px;";

                $m = array(__("January"), __("February"),__("March"), __("April"),__("May"), __("June"),__("July"),__("August"),__("September"),__("October"),__("November"),__("December"));

                $listado = ((isset($_REQUEST["chronosly_event_list_format"]) and $_REQUEST["chronosly_event_list_format"])?$_REQUEST["chronosly_event_list_format"]:$Post_Type_Chronosly->settings["chronosly_event_list_format"]);

                $time =  ((isset($_REQUEST["chronosly_event_list_time"]) and $_REQUEST["chronosly_event_list_time"])?$_REQUEST["chronosly_event_list_time"]:$Post_Type_Chronosly->settings["chronosly_event_list_time"]);


                switch($listado){
                    case "year":
                        if(isset($_REQUEST["ch_from"]) and $_REQUEST["ch_from"]){
                            $title = date("Y", strtotime($_REQUEST["ch_from"]));
                         }
                        else if (!$time or $time == "current"){
                           $title = date("Y");
                        } else {
                            $title = $time;
                        }

                        break;
                    case "month":
                         if(isset($_REQUEST["ch_from"]) and $_REQUEST["ch_from"]){
                            $title =  __($m[date("n",strtotime($_REQUEST["ch_from"]))-1]).", ".date("Y",strtotime($_REQUEST["ch_from"]));
                         }
                        else if(!$time  or $time == "current"){
                            $title = __($m[date("n")-1]).", ".date("Y");
                        } else {
                            $y = date("Y");
                            if($_REQUEST["y"]) $y = $_REQUEST["y"];

                            $title = __($m[(int)$time-1]).", ".$y;
                        }
                        break;
                    case "week":

                         if(isset($_REQUEST["ch_from"]) and $_REQUEST["ch_from"]){
                            $w = strtotime($_REQUEST["ch_from"]);
                            $title = date("d", $w)." - ".date("d", strtotime("+6 day",$w))." ".date_i18n("F", $w).", ".date("Y", $w);
                         }
                        else if(!$time or $time == "current"){
                            $y = date("Y");
                            if(date("m") == "12" and date("W") == "1") $y++;
                            $w =strtotime($y."W".str_pad(date("W"), 2, '0', STR_PAD_LEFT));
                            if($Post_Type_Chronosly->settings["chronosly_week_start"] == 1) $w -= (60*60*24);
                            $title = date("d", $w)." - ".date("d", strtotime("+6 day",$w))." ".date_i18n("F", $w).", ".date("Y");
                        } else {
                            $y = date("Y");
                            if($_REQUEST["y"]) $y = $_REQUEST["y"];
                             $w =strtotime($y."W".str_pad($time, 2, '0', STR_PAD_LEFT));
                            if($Post_Type_Chronosly->settings["chronosly_week_start"] == 1) $w -= (60*60*24);
                            $title = date("d", $w)." - ".date("d", strtotime("+6 day",$w))." ".date_i18n("F", $w).", ".$y   ;
                        }
                        break;
                    case "day":
                        if(!$time or $time == "current"){
                            $title = date("d")." ".date_i18n("F").", ".date("Y");
                        } else {
                            $title = date("d",strtotime($time))." ".date_i18n("F", strtotime($time)).", ".date("Y", strtotime($time));
                        }
                     break;
                    case "upcoming":

                        $title = __("Upcoming Events", "chronosly");

                     break;
                }
    if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"] or (isset($_REQUEST["navigation"]) and $_REQUEST["navigation"])){
    ?>


            <div class="ch-header ch-<?php echo $Post_Type_Chronosly->settings["chronosly_titles_template_default"];?>" style="<?php echo $stilo;?>"><span class="title"><?php echo $title; ?></span>

  <?php   if($Post_Type_Chronosly->settings["chronosly-calendar-url"]){ ?>
         <a href="<?php  echo $Post_Type_Chronosly->settings["chronosly-calendar-url"]; ?>" class="icon-calendar"></a></div>

    <?php } else { ?>
            <a href="<?php  echo (get_option('permalink_structure')?get_post_type_archive_link( 'chronosly_calendar' )."/year_".date("Y")."/month_".date("n")."/":get_site_url()."/?post_type=chronosly_calendar&y=".date("Y")."&mo=".date("n")); ?>" class="icon-calendar"></a></div>
<?php    }
}
if(!isset($_REQUEST["shortcode"]) or (isset($_REQUEST["shortcode"]) and isset($_REQUEST["before_events"]))) do_action("chronosly-before-events", $stilo);
echo "<div class='chronosly-content-block' style='".$stilo.";clear:both;'>";

    if ( count($elements) ) {
                    // Start the Loop.
                    $repeats = array();
// $tiempo_inicio = microtime(true);



                    foreach($elements as $el){
                        $xid = 0;
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

                    }

// $tiempo_fin = microtime(true);

// echo "Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
                    //arreglar la paginacion con repeats
                    if(!isset($_REQUEST["shortcode"])) {

                        echo "<div class='pagination'  style='$stilo'>";
                        if(!isset($_REQUEST["ch_code"])) {
                            $_REQUEST["ch_code"] = array("type" => "event", "pagination" => 1);
                            if(isset($_REQUEST["chronosly_event_list_time"])) {
                                $listado = ((isset($_REQUEST["chronosly_event_list_format"]) and $_REQUEST["chronosly_event_list_format"])?$_REQUEST["chronosly_event_list_format"]:$Post_Type_Chronosly->settings["chronosly_event_list_format"]);
                                switch($listado){
                                    case "year":
                                        // $extra .=" year='".$_REQUEST["chronosly_event_list_time"]."'";
                                         $_REQUEST["ch_code"] = array_merge( $_REQUEST["ch_code"], array("year" => $_REQUEST["chronosly_event_list_time"]));
                                    break;
                                    case "month":
                                        // $extra .=" year='".$_REQUEST["y"]."' month='".$_REQUEST["chronosly_event_list_time"]."'";
                                        $_REQUEST["ch_code"] = array_merge( $_REQUEST["ch_code"], array("year" => $_REQUEST["y"], "month" => $_REQUEST["chronosly_event_list_time"]));

                                    break;
                                    case "week":
                                        // $extra .=" year='".$_REQUEST["y"]."' week='".$_REQUEST["chronosly_event_list_time"]."'";
                                        $_REQUEST["ch_code"] = array_merge( $_REQUEST["ch_code"], array("year" => $_REQUEST["y"], "week" => $_REQUEST["chronosly_event_list_time"]));
                                    break;
                                }
                            }
                            $_REQUEST["ch_code"] = json_encode($_REQUEST["ch_code"]);
                        }


                        if($elementos[1]) echo '<a onclick="javascript:ch_prev_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\', this)"><< '.__("Previous page").'</a> &nbsp;&nbsp;&nbsp;';
                        if($elementos[2]) echo '<a onclick="javascript:ch_next_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\', this)">'.__("Next page").' >></a>';
                        echo "</div>";
                   }
                    else if( $_REQUEST["pagination"]){
                        echo "<div class='pagination'  style='$stilo'>";

                        if($elementos[1]) echo '<a onclick="javascript:ch_prev_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\', this)"><< '.__("Previous page").'</a> &nbsp;&nbsp;&nbsp;';
                        if($elementos[2]) echo '<a onclick="javascript:ch_next_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\', this)">'.__("Next page").' >></a>';
                        echo "</div>";
                    }

    } else{
                    echo '<div class="ch-error" style="'.$stilo.'">';
                    _e("No events found");
                    echo '</div>';
                }

if(!isset($_REQUEST["shortcode"]) or ($_REQUEST["shortcode"] and $_REQUEST["after_events"])) do_action("chronosly-after-events");
echo "</div>"; //close chronosly block
echo "</div>"; //close chronosly closure

if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"])  {

            ?>
        </div><!-- #content -->
    </section><!-- #primary -->

<?php
}
wp_reset_postdata();

if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"])  {


    get_footer();
}