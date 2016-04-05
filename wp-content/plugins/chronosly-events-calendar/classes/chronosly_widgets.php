<?php
/**
 * Base del widget
 */
if(!class_exists('Chronosly_Widget')){
    class Chronosly_Widget extends WP_Widget {

        function Chronosly_Widget() {
            // Instantiate the parent object
            parent::__construct( "chronosly_widget", 'Chronosly Widget',array( 'description' => __( 'Chronosly widget for Events,Calendar, Organizers and Places', 'chronosly' ) ));
        }

        function widget( $args, $instance ) {
            // Widget output
            $title = apply_filters( 'widget_title', $instance['title'] );
            // before and after widget arguments are defined by themes

            echo $args['before_widget'];
            if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
            if($instance['type'] == "calendar" and  !$instance['year'] and  !$instance['month'] and  !$instance['week']){
                $instance['month'] = date("m");
            }
            foreach($instance as $k=>$v){
                if($v and $k != "title"){
                   $params.= $k.'="'.$v.'" ';
                }
            }
            // This is where you run the code and display the output            
            // echo '[chronosly '.$params.']';

            echo do_shortcode('[chronosly '.$params.']');
            echo $args['after_widget'];
        }

        function update( $new_instance, $old_instance ) {
            // Save widget options
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['type'] = $new_instance['type'];
            $instance['single'] = $new_instance['single'];
            $instance['upcoming'] = strip_tags($new_instance['upcoming']);
            $instance['year'] = strip_tags($new_instance['year']);
            $instance['month'] = strip_tags($new_instance['month']);
            $instance['week'] = strip_tags($new_instance['week']);
            $instance['day'] = strip_tags($new_instance['day']);
            $instance['category'] = strip_tags($new_instance['category']);
            $instance['organizer'] = strip_tags($new_instance['organizer']);
            $instance['place'] = strip_tags($new_instance['place']);
            $instance['id'] = strip_tags($new_instance['id']);
            $instance['exclude'] = strip_tags($new_instance['exclude']);
            $instance['count'] = strip_tags($new_instance['count']);
            $instance['pagination'] = $new_instance['pagination'];
            $instance['small'] = $new_instance['small'];


            return $instance;
        }

        function form( $instance ) {
            // Output admin widget options form
            if (!isset( $instance[ 'type' ] ) ) {
              $instance[ 'type' ] = "event";
            }


            // Widget admin form
            if($instance["type"] == "event" || $instance["type"] == "calendar" ){
                if(!$instance["single"]){
                    $year_style=1;
                    if($instance["type"] == "event") $upcoming_style = 1;
                    $month_style=1;
                    $week_style=1;
                    if($instance["type"] == "event") $day_style=1;
                    $category_style=1;
                    $place_style=1;
                    $organizer_style=1;
                }


            }
            if($instance["type"] != "calendar"){
              //  $single_style=1;
                $id_style=1;

                if(!$instance["single"]){
                    $exclude_style=1;
                    $count_style=1;
                }

            }
            ?>
            
            <script>
                var ch_lock = 0;
                var type_ant = {};

                function ch_hide_or_show(id){

                    jQuery("#"+id+" .ch-widget-y").hide();
                    jQuery("#"+id+" .ch-widget-m").hide();
                    jQuery("#"+id+" .ch-widget-w").hide();
                    jQuery("#"+id+" .ch-widget-d").hide();
                    jQuery("#"+id+" .ch-widget-single").hide();
                    jQuery("#"+id+" .ch-widget-id").hide();
                    jQuery("#"+id+" .ch-widget-exclude").hide();
                    jQuery("#"+id+" .ch-widget-category").hide();
                    jQuery("#"+id+" .ch-widget-place").hide();
                    jQuery("#"+id+" .ch-widget-organizer").hide();
                    jQuery("#"+id+" .ch-widget-count").hide();
                    jQuery("#"+id+" .ch-widget-upcoming").hide();
                    type = ch_lock;
                    if(type == "event" || type == "calendar" ){
                        jQuery("#"+id+" .ch-widget-y").show();
                        jQuery("#"+id+" .ch-widget-m").show();
                        jQuery("#"+id+" .ch-widget-w").show();
                        if(type == "event") jQuery("#"+id+" .ch-widget-d").show();
                        if(type == "event") jQuery("#"+id+" .ch-widget-upcoming").show();
                        jQuery("#"+id+" .ch-widget-category").show();
                        jQuery("#"+id+" .ch-widget-place").show();
                        jQuery("#"+id+" .ch-widget-organizer").show();

                    }
                    if(type != "calendar" ){
                       // jQuery("#"+id+" .ch-widget-single").show();
                        jQuery("#"+id+" .ch-widget-id").show();
                        jQuery("#"+id+" .ch-widget-exclude").show();
                        jQuery("#"+id+" .ch-widget-count").show();
                        jQuery("#"+id+" .ch-widget-count").show();

                    }
                }



                jQuery(document).ready(function(){


                    jQuery(".ch-widget-type").change(function(){
                        var type = jQuery(this).find("option:selected").val();

                       if(! ch_lock){
                           ch_lock = type;
                           var id = jQuery(this).parents(".widget").attr("id");
                           type_ant[id] = type;
                           ch_hide_or_show(id);
                           jQuery("#"+id+" .ch-widget-single input").change();
                           setTimeout(function (){
                               ch_lock=0;

                           }, "1000");
                       }

                    });
                    jQuery(".ch-widget-single input").change(function(){
                        var id = jQuery(this).parents(".widget").attr("id");
                        if(jQuery(this).is(":checked")){
                            jQuery("#"+id+" .ch-widget-y").hide();
                            jQuery("#"+id+" .ch-widget-m").hide();
                            jQuery("#"+id+" .ch-widget-w").hide();
                            jQuery("#"+id+" .ch-widget-d").hide();
                           // jQuery("#"+id+" .ch-widget-single").show();
                            jQuery("#"+id+" .ch-widget-id").show();
                            jQuery("#"+id+" .ch-widget-exclude").hide();
                            jQuery("#"+id+" .ch-widget-category").hide();
                            jQuery("#"+id+" .ch-widget-place").hide();
                            jQuery("#"+id+" .ch-widget-organizer").hide();
                            jQuery("#"+id+" .ch-widget-count").hide();
                            jQuery("#"+id+" .ch-widget-upcoming").hide();
                        } else {
                           if(!ch_lock) {
                               var type = type_ant[id];
                               ch_lock = type;
                               ch_hide_or_show(id);
                               setTimeout(function (){
                                   ch_lock=0;

                               }, "1000");
                           }

                        }


                    });


                });
            </script>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:' ); ?></label>
                <select class="ch-widget-type"  id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
                    <option value="event" <?php if($instance["type"] == "event") echo "selected" ?>><?php _e("Events", "chronosly");?></option>
                    <option value="calendar" <?php if($instance["type"] == "calendar") echo "selected" ?>><?php _e("Calendar", "chronosly");?></option>
                    <option value="category" <?php if($instance["type"] == "category") echo "selected" ?>><?php _e("Categories", "chronosly");?></option>
                    <option value="organizer" <?php if($instance["type"] == "organizer") echo "selected" ?>><?php _e("Organizers", "chronosly");?></option>
                    <option value="place" <?php if($instance["type"] == "place") echo "selected" ?>><?php _e("Places", "chronosly");?></option>
                </select>
                <p class="ch-widget-single" style="<?php echo ($single_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'single' ); ?>"><?php _e( 'Single view:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'single' ); ?>" name="<?php echo $this->get_field_name( 'single' ); ?>" type="checkbox" value="1" <?php if( $instance[ 'single' ] ) echo "checked='checked'"; ?> />
                </p>
                   <p class="ch-widget-id"  style="<?php echo ($id_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Ids:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'id' ] ); ?>" />
                </p>
                <p class="ch-widget-exclude"  style="<?php echo ($exclude_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e( 'Exclude:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'exclude' ] ); ?>" />
                </p>
                 <p class="ch-widget-category"  style="<?php echo ($category_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'category' ] ); ?>" />
                </p>
                <?php if($this->settings["chronosly_organizers"] and $this->settings["chronosly_organizers_addon"]) { ?>
                   <p class="ch-widget-organizer"  style="<?php echo ($organizer_style?"":"display:none;");?>">
                        <label for="<?php echo $this->get_field_id( 'place' ); ?>"><?php _e( 'Organizer:',"chronosly" ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'organizer' ); ?>" name="<?php echo $this->get_field_name( 'organizer' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'organizer' ] ); ?>" />
                    </p>
                <?php }
                    if($this->settings["chronosly_places"] and $this->settings["chronosly_places_addon"]) {
                ?>
                     <p class="ch-widget-place"  style="<?php echo ($place_style?"":"display:none;");?>">
                        <label for="<?php echo $this->get_field_id( 'place' ); ?>"><?php _e( 'Place:' ,"chronosly"); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'place' ); ?>" name="<?php echo $this->get_field_name( 'place' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'place' ] ); ?>" />
                    </p>
                <?php } ?>
                 <p class="ch-widget-upcoming"  style="<?php echo ($upcoming_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'upcoming' ); ?>"><?php _e( 'Upcoming:',"chronosly" ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'upcoming' ); ?>" name="<?php echo $this->get_field_name( 'upcoming' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'upcoming' ] ); ?>" />
                </p>
                <p class="ch-widget-y"  style="<?php echo ($year_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'year' ); ?>"><?php _e( 'Year:',"chronosly" ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'year' ); ?>" name="<?php echo $this->get_field_name( 'year' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'year' ] ); ?>" />
                </p>
                 <p class="ch-widget-m"  style="<?php echo ($month_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'month' ); ?>"><?php _e( 'Month:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'month' ); ?>" name="<?php echo $this->get_field_name( 'month' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'month' ] ); ?>" />
                </p>
                 <p class="ch-widget-w"  style="<?php echo ($week_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'week' ); ?>"><?php _e( 'Week:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'week' ); ?>" name="<?php echo $this->get_field_name( 'week' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'week' ] ); ?>" />
                </p>
                <p class="ch-widget-d"  style="<?php echo ($day_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'day' ); ?>"><?php _e( 'Day (Y-m-d):' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'day' ); ?>" name="<?php echo $this->get_field_name( 'day' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'day' ] ); ?>" />
                </p>
                <p class="ch-widget-count"  style="<?php echo ($count_style?"":"display:none;");?>">
                    <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Limit:' ,"chronosly"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'count' ] ); ?>" />
                </p>
            <p >
                <label for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e( 'Pagination' ,"chronosly"); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'pagination' ); ?>" name="<?php echo $this->get_field_name( 'pagination' ); ?>" type="checkbox" value="1" <?php if($instance['pagination']) echo "checked='checked'"; ?> />
            </p>
               <p >
                    <label for="<?php echo $this->get_field_id( 'small' ); ?>"><?php _e( 'Small container (like sidebar):' ,"chronosly"); ?></label>
                   <input class="widefat" id="<?php echo $this->get_field_id( 'small' ); ?>" name="<?php echo $this->get_field_name( 'small' ); ?>" type="checkbox" value="1" <?php if($instance['small']) echo "checked='checked'"; ?> />
                </p>

            </p>
        <?php
        }
    }



}
