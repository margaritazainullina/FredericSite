<?php
global $Post_Type_Chronosly, $wp_query,$wp_the_query;


$limit = (isset($_REQUEST["count"]) and $_REQUEST["count"])?$_REQUEST["count"]:$Post_Type_Chronosly->settings["chronosly_events_x_page"];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"])  {

    get_header();

    ?>
    <section id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

                <?php
}
echo '<div class="chronosly-closure">';

                $Post_Type_Chronosly->template->templates_tabs("dad1", 1);
                ?>


            <?php


            $tax = $wp_query->query["chronosly_category"];
            if(!$tax) $tax = $wp_query->query_vars["chronosly_category"];
            if(!$tax) $tax = $wp_query->queried_object->chronosly_category;
            if(!$tax) $tax = $wp_the_query->queried_object->chronosly_category;
            $wp_query->query("chronosly_category=$tax&posts_per_page=$limit&numberposts=$limit&paged=$paged");
            remove_action( 'pre_get_posts', array("Post_Type_Chronosly",'add_custom_post_vars')  );
            $extra = array("chronosly_category" => $tax);
            $repeated = Post_Type_Chronosly::get_events_repeated_by_date($limit, $paged, $extra);
            $elementos = Post_Type_Chronosly::get_days_by_date($wp_query, $repeated, $limit, $paged);
            $elements = $elementos[0];
            $cat_link = "../";

            $stilo = "margin:auto;padding:30px;";
            if($Post_Type_Chronosly->settings["chronosly_template_max"]) $stilo .= "max-width:".$Post_Type_Chronosly->settings["chronosly_template_max"]."px;";
            if($Post_Type_Chronosly->settings["chronosly_template_min"]) $stilo .= "min-width:".$Post_Type_Chronosly->settings["chronosly_template_min"]."px;";

if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"] or (isset($_REQUEST["navigation"]) and $_REQUEST["navigation"])){
    ?>

        <div class="ch-header ch-<?php echo $Post_Type_Chronosly->settings["chronosly_titles_template_default"];?>" style="<?php echo $stilo;?>"><a href="<?php  echo (get_option('permalink_structure')?$cat_link:get_site_url()."/index.php?post_type=chronosly_category") ;?>" class="back"><i class="fa fa-chevron-left"></i> <?php _e("Category list","chronosly") ?></a><?php   if($Post_Type_Chronosly->settings["chronosly-calendar-url"]){ ?>
         <a href="<?php  echo $Post_Type_Chronosly->settings["chronosly-calendar-url"]; ?>" class="icon-calendar"></a></div>

    <?php } else { ?>
            <a href="<?php  echo (get_option('permalink_structure')?get_post_type_archive_link( 'chronosly_calendar' )."/year_".date("Y")."/month_".date("n")."/":get_site_url()."/?post_type=chronosly_calendar&y=".date("Y")."&mo=".date("n")); ?>" class="icon-calendar"></a></div>
<?php    }
}
$tax_ob =get_term_by("slug", $tax, "chronosly_category");

if(!isset($_REQUEST["shortcode"]) or ($_REQUEST["shortcode"] and $_REQUEST["before_events"])) do_action("chronosly-before-events", $stilo);
echo "<div class='chronosly-content-block' style='".$stilo.";clear:both;'>";

// Start the Loop.
            if ( count($elements) ) {



                    ob_start();
                    $Post_Type_Chronosly->template->print_template($tax_ob->term_id, "dad12", "", "", "front");

                    $content = ob_get_clean();

                    if(stripos($content, "#event_list#")){
                        //get the events for this organizer
                        $res = "";

                        $repeats = array();

                        if(count($elements)){
                            foreach($elements as $el){
                                $xid = $ide = 0;
                                if(is_array($el)){
                                    $xid = $ide =  $el["id"];
                                    if(isset($repeats[$xid])) $ide .= "_".$repeats[$xid];
                                    ob_start();
                                    $Post_Type_Chronosly->template->print_template($el["id"], "dad4", "", "", "front", array("id" => $ide, "start" => $el["start"], "end" => $el["end"]));
                                    $events_list[$ide]= ob_get_clean();
                                }
                                else {
                                    $xid = $ide = $el;
                                    ob_start();
                                    $Post_Type_Chronosly->template->print_template($el, "dad4", "", "", "front");
                                    $events_list[$ide]= ob_get_clean();
                                }
                                if(isset($repeats[$xid])) ++$repeats[$xid];
                                else $repeats[$xid] = 1;




                                $is_feat = stripos($events_list[$ide], " ch-featured ");
                                if($feats and !$is_feat){
                                    $res .= "<span class='feat-sep'></span>";
                                    $feats = 0;
                                }
                                else if($is_feat) $feats = 1;
                                $res .= $events_list[$ide];
                            }
                        }
                        echo str_replace("#event_list#", $res, $content);
                    } else   echo $content;


                if(!isset($_REQUEST["shortcode"])) {

                    echo "<div class='pagination'  style='$stilo'>";
                    if(!isset($_REQUEST["ch_code"])) $_REQUEST["ch_code"] = json_encode(array("type"=>'category', "category"=> $tax_ob->term_id, "pagination"=>1));
                    if($elementos[1]) echo '<a href="javascript:ch_prev_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]).'\')"><< '.__("Previous page").'</a> &nbsp;&nbsp;&nbsp;';
                    if($elementos[2]) echo '<a href="javascript:ch_next_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]).'\')">'.__("Next page").' >></a>';
                    echo "</div>";
                }
                else if( $_REQUEST["pagination"]){
                    echo "<div class='pagination'  style='$stilo'>";

                    if($elementos[1]) echo '<a href="javascript:ch_prev_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\')"><< '.__("Previous page").'</a> &nbsp;&nbsp;&nbsp;';
                    if($elementos[2]) echo '<a href="javascript:ch_next_page('.$limit.','.$paged.', \''. urlencode($_REQUEST["ch_code"]) .'\')">'.__("Next page").' >></a>';
                    echo "</div>";
                }




            } else {
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
    wp_reset_postdata();


    get_footer();
} else{

        global $post, $wp_query;
        $post = "";
        $wp_query->post = $post;
        $wp_query->posts = $post;
        $wp_query->post_count = 0;
}
