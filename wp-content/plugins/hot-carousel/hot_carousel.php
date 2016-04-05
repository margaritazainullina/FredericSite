<?php
/**
 * Plugin Name: Hot Carousel
 * Plugin URI: http://hot-themes.com/wordpress/plugins/carousel/
 * Description: Hot Carousel by HotThemes is a fully configurable, simple carousel widget, based on jQuery. Hot Carousel is a simple WordPress widget that will help you to show your photos in a carousel style.
 * Version: 1.1
 * Author: HotThemes
 * Author URI: http://hot-themes.com/
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'hot_carousel_load_widgets' );
add_action('admin_init', 'hot_carousel_textdomain');
/**
 * Register our widget.
 * 'HotEffectsRotator' is the widget class used below.
 *
 * @since 0.1
 */
function hot_carousel_load_widgets() {
	register_widget( 'HotCarousel' );
}

function hot_carousel_textdomain() {
	load_plugin_textdomain('hot_carousel', false, dirname(plugin_basename(__FILE__) ) . '/languages');
}
	
/**
 * HotCarousel Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
 
class HotCarousel extends WP_Widget {
     
	/**
	 * Widget setup.
	 */
	 
	function HotCarousel() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Hot_carousel', 'description' => __('Hot Carousel', 'hot_carousel') );

		/* Widget control settings. */
		$control_ops = array(  'id_base' => 'hot-carousel' );

		/* Create the widget. */
		parent::__construct( 'hot-carousel', __('Hot Carousel', 'hot_carousel'), $widget_ops, $control_ops );
		
    	add_action('wp_print_styles', array( $this, 'HotCarousel_styles'),12);
		add_action('wp_head', array( $this, 'HotCarousel_inline_scripts_and_styles'),13);
	    add_action('admin_init', array( $this,'admin_utils'));
    }

	function admin_utils(){
			wp_enqueue_script( 'jquery-colorpicker', plugins_url('/js/jscolor.js', __FILE__),array('jquery'),'1.0.0');
			wp_enqueue_script( 'jquery-hotutil', plugins_url('/js/hotutil.js', __FILE__),array('jquery'),'1.0.0');
	}
	
	function HotCarousel_styles(){
	   wp_enqueue_style( 'hot-carousel-style', plugins_url('/style.css', __FILE__));
	   $all_options = parent::get_settings();
	   
	   foreach ($all_options as $key => $value){
	    $options = $all_options[$key];
		if($options['LoadjQuery'] == '1'){
		  wp_enqueue_script( 'jquery', plugins_url('/js/jquery.min.js', __FILE__),false,'1.5.2');
		}
	   }
	   wp_enqueue_script( 'jquery-hot-carousel', plugins_url('/js/jquery.carousel.js', __FILE__),array('jquery'),'1.0');
	}
	
	function HotCarousel_inline_scripts_and_styles(){
	   // MULTIPLE WIDGETS ON PAGE ARE SUPPORTED !!!
	   $all_options = parent::get_settings();
	   echo '<style type="text/css">';
	   foreach ($all_options as $key => $value){
	    $options = $all_options[$key];
	
		if(!$options['ImageFolder'])continue;
		$defaults = $this->GetDefaults();
 	    $options = wp_parse_args( (array) $options, $defaults );
		$hotCarouselWidth = $options['OverallWidth'];
		echo '
		
            .hotcarousel-'.$key.' img {
				border:'.$options['BorderWidth'].'px solid '.$options['BorderColor'].';
				margin:0 '.$options['ImagesMargin'].'px;
				padding:'.$options['ImageToBorderPadding'].'px;
            }

            .hotcarousel-'.$key.' .js {
				overflow:hidden;
				width:'.$options['OverallWidth'].'px;
				height:'.$options['OverallHeight'].'px;
            }

            .hotcarousel-'.$key.' .carousel-next {
				background: url("'.plugins_url('/images/circleright32.png', __FILE__).'") 0 '.$options['NavArrowsVerticalOffset'].'px no-repeat;float:left;
            }

            .hotcarousel-'.$key.' .carousel-previous {
				background: url("'.plugins_url('/images/circleleft32.png', __FILE__).'") 0 '.$options['NavArrowsVerticalOffset'].'px no-repeat;float:left;
            }

			.hotcarousel-'.$key.' .carousel-control{height:'.$options['OverallHeight'].'px;}
			
			.carousel-wrap {width:'.$hotCarouselWidth.'px;}
			
	   ';
	   
	   }
	   echo '</style>';
	}
	
	
	function GetDefaults()
	{
	  return array(   
		         'WidgetClassSuffix' => '' 
				,'LoadjQuery' => 0 
				,'ImageFolder' => plugins_url('/images/demo', __FILE__) 
				,'OverallWidth' => 650 
				,'OverallHeight' => 136 
				,'NumberOfImagesPerSlide' => 3 
				,'WidthOfImages' => 165 
				,'HeightOfImages' => 128
				,'ImagesMargin' => 21 
				,'ImageToBorderPadding' => 2 
				,'BorderWidth' => 2 
				,'BorderColor' => '000000'
				,'ShowPagination' => false 
				,'Autoslide' => false
				,'AutoslideInterval' => 3000 
				,'TransitionEffect' => 'slide' // slide|fade
				,'AnimationSpeed' => 'normal' //slow|normal|fast
				,'DirectionOfImages' => 'horizontal' // horizontal|vertical
				,'TransitionLoop' => false
                ,'NavArrowsVerticalOffset' => 52				
			 );
	}
	
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
	   extract( $args );
       echo $before_widget;
       //--------------------------------------------------------------------------------------------------------------------------------------------
	   //--------------------------------------------------------------------------------------------------------------------------------------------
	 
        $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults );  ?>
	   
	   <script type="text/javascript">
		    jQuery(function(){
		        jQuery(".hotcarousel-<?php echo $this->number; ?> div.foo").carousel({
					direction: "<?php echo $instance['DirectionOfImages']; ?>",
					loop: <?php echo $instance['TransitionLoop']?'true':'false'; ?>,
					dispItems: <?php echo $instance['NumberOfImagesPerSlide']; ?>,
					pagination: <?php echo $instance['ShowPagination']?'true':'false'; ?>,
					paginationPosition: "inside",
					autoSlide: <?php echo $instance['Autoslide']?'true':'false'; ?>,
					autoSlideInterval: <?php echo $instance['AutoslideInterval']; ?>,
					delayAutoSlide: false,
					combinedClasses: false,
					effect: "<?php echo $instance['TransitionEffect']; ?>",
					slideEasing: "swing",
					animSpeed: "<?php echo $instance['AnimationSpeed']; ?>",
					equalWidths: "true"
				});
		    });
        </script>
		    
        <div class="hotcarousel hotcarousel-<?php echo $this->number; ?> hotcarousel<?php echo $instance['WidgetClassSuffix'];?>">
		    <div class="foo">
		         <ul style="margin:0; padding:0">
		    
		            <?php  
					
					$imageFolder = $instance['ImageFolder'].'/';
					$imageFolder = str_ireplace("\\", "/", $imageFolder);
					$imageFolder = str_ireplace("//", "/", $imageFolder);
					$imageFolder = str_ireplace("//", "/", $imageFolder);
				    $imageFolder = str_ireplace("http:/", "http://", $imageFolder);
					
					$full = stristr($instance['ImageFolder'], 'http');
		        	$path_r = $instance['ImageFolder'];
					if($full){
				      $path_r = str_ireplace(get_bloginfo('wpurl'),"",$path_r);
				    }
                    $infinite_list = "";					
					$site_abs = str_ireplace("index.php", "", $_SERVER["SCRIPT_FILENAME"]);;
			
					$image_abs = $site_abs.$path_r;
					$image_abs = str_ireplace('/',DIRECTORY_SEPARATOR,$image_abs);
					$image_abs = str_ireplace('\\',DIRECTORY_SEPARATOR,$image_abs);
					$image_abs = str_ireplace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR,$image_abs);
					$image_abs = str_ireplace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR,$image_abs);
					
					
					if ($handle = opendir($image_abs)) {
						while (false !== ($file = readdir($handle))) {
							if ($file != "." && $file != "..") {
								$infinite_list = $infinite_list."$file"."||";
							}
						}
						closedir($handle);
						$infinite_pic = explode("||", $infinite_list);
						$infinite_pics_number = count($infinite_pic) - 2;
						
						for ($loop = 0; $loop <= $infinite_pics_number; $loop += 1) {
							$pic_type = explode(".", $infinite_pic[$loop]);
							if ((($pic_type[1]=="jpg")or($pic_type[1]=="gif"))or(($pic_type[1]=="jpeg")or($pic_type[1]=="png"))) {
								echo '<li><img src="'.$imageFolder.$infinite_pic[$loop].'" alt="" width="'.$instance['WidthOfImages'].'" height="'.$instance['HeightOfImages'].'" /></li>';
								echo "\n";
							} elseif ((($pic_type[1]=="JPG")or($pic_type[1]=="GIF"))or(($pic_type[1]=="JPEG")or($pic_type[1]=="PNG"))) {
								echo '<li><img src="'.$imageFolder.$infinite_pic[$loop].'" alt="" width="'.$instance['WidthOfImages'].'" height="'.$instance['HeightOfImages'].'" /></li>';
								echo "\n";						
							}
						}
					}
		            ?>
		    
		        </ul>
		    </div>
        </div>
	   
	   <?php
       //--------------------------------------------------------------------------------------------------------------------------------------------
	   //--------------------------------------------------------------------------------------------------------------------------------------------
	   echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	
		foreach($new_instance as $key => $option)
		{
		  $instance[$key]     = $new_instance[$key];
		} 
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
	    $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults );  
?>

<table style="width:692px;margin-left:-466px;background:white;border:Solid 1px Gray;border-top:Solid 4px Gray;" class="hot_carousel_property_table_<?php echo $this->number ;?>" cellpadding="3">

<tr>
<th colspan="2" >
<?php _e('Hot Carousel Widget Options','hot_carousel'); ?>
</th>
</tr>

<tr>
<td colspan="2">
<hr/>
</td>
</tr>

<tr>
<td style="width:49%;">
<table >

<tr><td><?php _e('Widget Class Suffix','hot_carousel'); ?> 
</td><td> <input type="text" name="<?php echo $this->get_field_name( 'WidgetClassSuffix' ); ?>"
                             id="<?php echo $this->get_field_id( 'WidgetClassSuffix' ); ?>" 
							 value="<?php echo $instance['WidgetClassSuffix']; ?>" /> 
</td></tr> 

<tr><td><?php _e('Overall Width Of Widget','hot_carousel'); ?> 
</td><td> <input type="text" name="<?php echo $this->get_field_name( 'OverallWidth' ); ?>"
                             id="<?php echo $this->get_field_id( 'OverallWidth' ); ?>" 
							 value="<?php echo $instance['OverallWidth']; ?>" 
							 class="numeric" />
 </td></tr>	
 
 <tr><td><?php _e('Overall Height Of Widget','hot_carousel'); ?> 
</td><td> <input type="text" name="<?php echo $this->get_field_name( 'OverallHeight' ); ?>"
                             id="<?php echo $this->get_field_id( 'OverallHeight' ); ?>" 
							 value="<?php echo $instance['OverallHeight']; ?>"
                             class="numeric" />
 </td></tr>	
 
<tr><td><?php _e('Number Of Images Per Slide','hot_carousel'); ?> 
</td><td> 
<input type="text" name="<?php echo $this->get_field_name( 'NumberOfImagesPerSlide' ); ?>"
                             id="<?php echo $this->get_field_id( 'NumberOfImagesPerSlide' ); ?>" 
							 value="<?php echo $instance['NumberOfImagesPerSlide']; ?>"
                             class="numeric" />
</td></tr>	
<tr><td><?php _e('Width Of Images','hot_carousel'); ?> 
</td><td>
<input type="text" name="<?php echo $this->get_field_name( 'WidthOfImages' ); ?>"
                             id="<?php echo $this->get_field_id( 'WidthOfImages' ); ?>" 
							 value="<?php echo $instance['WidthOfImages']; ?>"
                             class="numeric" />

</td></tr>	

<tr><td><?php _e('Height Of Images','hot_carousel'); ?> 
</td><td>
<input type="text" name="<?php echo $this->get_field_name( 'HeightOfImages' ); ?>"
                             id="<?php echo $this->get_field_id( 'HeightOfImages' ); ?>" 
							 value="<?php echo $instance['HeightOfImages']; ?>"
                             class="numeric"
							 />
 </td></tr>	

<tr><td><?php _e('Margin between images','hot_carousel'); ?> 
</td><td>
<input type="text" name="<?php echo $this->get_field_name( 'ImagesMargin' ); ?>"
                             id="<?php echo $this->get_field_id( 'ImagesMargin' ); ?>" 
							 value="<?php echo $instance['ImagesMargin']; ?>"
                             class="numeric"
							 />
</td></tr>	
 
<tr><td><?php _e('Padding between images and border','hot_carousel'); ?> 
</td><td>
<input type="text" name="<?php echo $this->get_field_name( 'ImageToBorderPadding' ); ?>"
                             id="<?php echo $this->get_field_id( 'ImageToBorderPadding' ); ?>" 
							 value="<?php echo $instance['ImageToBorderPadding']; ?>"
                             class="numeric"
							 />
</td></tr>	

<tr><td><?php _e('Images Border Width','hot_carousel'); ?> 
</td><td>
<input type="text" name="<?php echo $this->get_field_name( 'BorderWidth' ); ?>"
                             id="<?php echo $this->get_field_id( 'BorderWidth' ); ?>" 
							 value="<?php echo $instance['BorderWidth']; ?>"
                             class="numeric"
							 />
</td></tr>	


</table>
</td>
<td style="width:49%;">
<table>

<tr><td><?php _e('Load jQuery','hot_carousel'); ?> 
</td><td> 
<select class="select"  id="<?php echo $this->get_field_id( 'LoadjQuery' ); ?>" name="<?php echo $this->get_field_name( 'LoadjQuery' ); ?>" >
                <option value="1" <?php if($instance['LoadjQuery'] == "1") echo 'selected="selected"'; ?> ><?php _e('Yes', 'hot_carousel'); ?></option>
                <option value="0" <?php if($instance['LoadjQuery'] == "0") echo 'selected="selected"'; ?> ><?php _e('No', 'hot_carousel'); ?></option>				
</select>
</td></tr>


<tr><td><?php _e('Images Border Color','hot_carousel'); ?> 
</td><td> 
<input type="text" name="<?php echo $this->get_field_name( 'BorderColor' ); ?>"
                             id="<?php echo $this->get_field_id( 'BorderColor' ); ?>" 
							 value="<?php echo $instance['BorderColor']; ?>"
                             class="color"
							 />
</td></tr>	 
 
<tr><td><?php _e('Show Pagination','hot_carousel'); ?> 
</td><td>
<select class="select"  id="<?php echo $this->get_field_id( 'ShowPagination' ); ?>" name="<?php echo $this->get_field_name( 'ShowPagination' ); ?>" >
                <option value="true" <?php if($instance['ShowPagination'] === 'true' ) echo 'selected="selected"'; ?> ><?php _e('Yes', 'hot_carousel'); ?></option>
                <option value="false" <?php if($instance['ShowPagination'] === 'false') echo 'selected="selected"'; ?> ><?php _e('No', 'hot_carousel'); ?></option>				
</select>
 </td></tr>
 
<tr><td><?php _e('Autoslide','hot_carousel'); ?> 
</td><td> 
<select class="select"  id="<?php echo $this->get_field_id( 'Autoslide' ); ?>" name="<?php echo $this->get_field_name( 'Autoslide' ); ?>" >
                <option value="true" <?php if($instance['Autoslide'] === 'true' ) echo 'selected="selected"'; ?> ><?php _e('Yes', 'hot_carousel'); ?></option>
                <option value="false" <?php if($instance['Autoslide'] === 'false') echo 'selected="selected"'; ?> ><?php _e('No', 'hot_carousel'); ?></option>				
</select>
 </td></tr> 

<tr><td><?php _e('Autoslide Interval','hot_carousel'); ?> 
</td></tr><tr><td>
<input type="text" name="<?php echo $this->get_field_name( 'AutoslideInterval' ); ?>"
                             id="<?php echo $this->get_field_id( 'AutoslideInterval' ); ?>" 
							 value="<?php echo $instance['AutoslideInterval']; ?>"
                             class="numeric"
							 />
</td></tr> 

<tr><td><?php _e('Transition Effect','hot_carousel'); ?> 
</td><td>
<select class="select"  id="<?php echo $this->get_field_id( 'TransitionEffect' ); ?>" name="<?php echo $this->get_field_name( 'TransitionEffect' ); ?>" >
                <option value="fade" <?php if($instance['TransitionEffect'] === "fade" ) echo 'selected="selected"'; ?> ><?php _e('Slide', 'hot_carousel'); ?></option>
                <option value="slide" <?php if($instance['TransitionEffect'] === "slide") echo 'selected="selected"'; ?> ><?php _e('Fade', 'hot_carousel'); ?></option>				
</select>
 </td></tr> 

<tr><td><?php _e('Animation Speed','hot_carousel'); ?> 
</td><td>
<select class="select"  id="<?php echo $this->get_field_id( 'AnimationSpeed' ); ?>" name="<?php echo $this->get_field_name( 'AnimationSpeed' ); ?>" >
                <option value="slow" <?php if($instance['AnimationSpeed'] === "slow" ) echo 'selected="selected"'; ?> ><?php _e('Slow', 'hot_carousel'); ?></option>
                <option value="normal" <?php if($instance['AnimationSpeed'] === "normal") echo 'selected="selected"'; ?> ><?php _e('Normal', 'hot_carousel'); ?></option>
                <option value="fast" <?php if($instance['AnimationSpeed'] === "fast") echo 'selected="selected"'; ?> ><?php _e('Fast', 'hot_carousel'); ?></option>				
</select>
</td></tr> 

<tr><td><?php _e('Direction Of Images','hot_carousel'); ?> 
</td><td>
<select class="select"  id="<?php echo $this->get_field_id( 'DirectionOfImages' ); ?>" name="<?php echo $this->get_field_name( 'DirectionOfImages' ); ?>" >
                <option value="horizontal" <?php if($instance['DirectionOfImages'] === "horizontal" ) echo 'selected="selected"'; ?> ><?php _e('Horizontal', 'hot_carousel'); ?></option>
                <option value="vertical" <?php if($instance['DirectionOfImages'] === "vertical") echo 'selected="selected"'; ?> ><?php _e('Vertical', 'hot_carousel'); ?></option>			
</select>
</td></tr> 

<tr><td><?php _e('Transition Loop','hot_carousel'); ?> 
</td><td> 
<select class="select"  id="<?php echo $this->get_field_id( 'TransitionLoop' ); ?>" name="<?php echo $this->get_field_name( 'TransitionLoop' ); ?>" >
                <option value="true" <?php if($instance['TransitionLoop'] === 'true' ) echo 'selected="selected"'; ?> ><?php _e('Yes', 'hot_carousel'); ?></option>
                <option value="false" <?php if($instance['TransitionLoop'] === 'false') echo 'selected="selected"'; ?> ><?php _e('No', 'hot_carousel'); ?></option>				
</select>
 </td></tr>  
 



</table>
</td>
</tr>

<tr>
<td colspan="2">
<hr/>
</td>
</tr>

<tr>
<td>
<?php _e('Navigation Arrows Vertical Offset','hot_carousel'); ?> 
</td>
<td>
<input type="text" name="<?php echo $this->get_field_name( 'NavArrowsVerticalOffset' ); ?>"
                             id="<?php echo $this->get_field_id( 'NavArrowsVerticalOffset' ); ?>" 
							 value="<?php echo $instance['NavArrowsVerticalOffset']; ?>"
                             class="numeric"
							 />
</td>
</tr> 

<tr>
<td>
<?php _e('Folder where your images are stored','hot_carousel'); ?> 
</td>
<td> <input type="text" name="<?php echo $this->get_field_name( 'ImageFolder' ); ?>"
                             id="<?php echo $this->get_field_id( 'ImageFolder' ); ?>" 
							 value="<?php echo $instance['ImageFolder']; ?>"
                             style="width:90%;"
							 />
</td></tr>



 
</table>

<script type="text/javascript" >
   try{ 
    jQuery('.widgets-holder-wrap .widget').css({
		'overflow':'visible'
    });
	
   }catch(exc){}
</script>

<script type="text/javascript" >
   try{ 
    jscolor.init();
   }catch(exc){}
   
   try{ 
    HWT_Utilise('.hot_carousel_property_table_<?php echo $this->number ;?>');
   }catch(exc){}
</script>

	<?php  
	}
}

?>