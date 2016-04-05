<?php
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

            $limit = $Post_Type_Chronosly->settings["chronosly_events_x_page"];
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $tax = $wp_query->query["chronosly_tag"];
            $wp_query->query("chronosly_tag=$tax&posts_per_page=$limit&numberposts=$limit&paged=$paged");
            $stilo = "margin:auto;padding:30px;";
            if($Post_Type_Chronosly->settings["chronosly_template_max"]) $stilo .= "max-width:".$Post_Type_Chronosly->settings["chronosly_template_max"]."px;";
            if($Post_Type_Chronosly->settings["chronosly_template_min"]) $stilo .= "min-width:".$Post_Type_Chronosly->settings["chronosly_template_min"]."px;";

            ?>

        <div class="ch-header ch-<?php echo $Post_Type_Chronosly->settings["chronosly_titles_template_default"];?>" style="<?php echo $stilo;?>">
        <?php   if($Post_Type_Chronosly->settings["chronosly-calendar-url"]){ ?>
         <a href="<?php  echo $Post_Type_Chronosly->settings["chronosly-calendar-url"]; ?>" class="icon-calendar"></a></div>

    <?php } else { ?>
            <a href="<?php  echo (get_option('permalink_structure')?get_post_type_archive_link( 'chronosly_calendar' )."/year_".date("Y")."/month_".date("n")."/":get_site_url()."/?post_type=chronosly_calendar&y=".date("Y")."&mo=".date("n")); ?>" class="icon-calendar"></a></div>
<?php    }

    if(!isset($_REQUEST["shortcode"]) or !$_REQUEST["shortcode"] ) do_action("chronosly-before-events", $stilo);
    echo "<div class='chronosly-content-block' style='".$stilo.";clear:both;'>";

    // Start the Loop.
            if ( have_posts() ){



                    $tax_ob =get_term_by("slug", $tax, "chronosly_tag");
                    ob_start();
                    $Post_Type_Chronosly->template->print_template($tax_ob->term_id, "dad12", "", "", "front");

                    $content = ob_get_clean();

                    if(stripos($content, "#event_list#")){
                        //get the events for this organizer
                        $res = "";
                        while ( have_posts() ) {
                           the_post();
                            $eid = get_the_ID();
                            if(!isset($events_list[$eid])){
                                ob_start();
                                $Post_Type_Chronosly->template->print_template($eid, "dad1", "", "", "front");
                                $events_list[$eid]= ob_get_clean();
                            }
                            $is_feat = stripos($events_list[$eid], " ch-featured ");
                            if($feats and !$is_feat){
                                $res .= "<span class='feat-sep'></span>";
                                $feats = 0;
                            }
                            else if($is_feat) $feats = 1;
                            $res .= $events_list[$eid];
                        }
                        echo str_replace("#event_list#", $res, $content);
                    } else   echo $content;





                echo "<div class='pagination'  style='$stilo'>";

                if ( $paged > 1) {
                   if($paged == 2) echo '<a rel="prev" href="' . get_term_link($tax, "chronosly_tag"). '">« '.__("Previous page").'</a> ';
                    else echo '<a rel="prev" href="' . get_term_link($tax, "chronosly_tag"). 'page/'.($paged-1).'/">« '.__("Previous page").'</a> ';
                }
                if ( $wp_query->max_num_pages > $paged ) {
                    echo '<a rel="next" href="' . get_term_link($tax, "chronosly_tag"). 'page/'.($paged+1).'/">'.__("Next page").' »</a>';
                }
                echo "</div>";



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

}