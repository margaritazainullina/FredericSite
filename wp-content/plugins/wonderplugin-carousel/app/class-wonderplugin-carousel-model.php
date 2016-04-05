<?php 

require_once 'wonderplugin-carousel-functions.php';

class WonderPlugin_Carousel_Model {

	private $controller;
	
	function __construct($controller) {
		
		$this->controller = $controller;
	}
	
	function get_upload_path() {
		
		$uploads = wp_upload_dir();
		return $uploads['basedir'] . '/wonderplugin-carousel/';
	}
	
	function get_upload_url() {
	
		$uploads = wp_upload_dir();
		return $uploads['baseurl'] . '/wonderplugin-carousel/';
	}
	
	function generate_body_code($id, $has_wrapper) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		if ( !$this->is_db_table_exists() )
		{
			return '<p>The specified carousel does not exist.</p>';
		}
		
		$ret = "";
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$data = str_replace('\\\"', '"', $item_row->data);
			$data = str_replace("\\\'", "'", $data);
			$data = str_replace("\\\\", "\\", $data);
			
			$data = json_decode(trim($data));
			
			foreach($data as &$value)
			{
				if ( is_string($value) )
					$value = wp_kses_post($value);
			}
			
			if (isset($data->customcss) && strlen($data->customcss) > 0)
			{
				$customcss = str_replace("\r", " ", $data->customcss);
				$customcss = str_replace("\n", " ", $customcss);
				$customcss = str_replace("CAROUSELID", $id, $customcss);
				$ret .= '<style type="text/css">' . $customcss . '</style>';
			}
			
			if (isset($data->skincss) && strlen($data->skincss) > 0)
			{
				$skincss = str_replace("\r", " ", $data->skincss);
				$skincss = str_replace("\n", " ", $skincss);
				$skincss = str_replace('#amazingcarousel-CAROUSELID',  '#wonderplugincarousel-' . $id, $skincss);
				$ret .= '<style type="text/css">' . $skincss . '</style>';
			}
			
			if ($has_wrapper)
				$ret .= '<div class="wonderplugincarousel-container" id="wonderplugincarousel-container-' . $id . '" style="max-width:' . $data->width * $data->visibleitems . 'px;margin:0 auto;padding:0 60px;">';
			else
				$ret .= '<div class="wonderplugincarousel-container" id="wonderplugincarousel-container-' . $id . '">';
			
			// div data tag
			$ret .= '<div class="wonderplugincarousel" id="wonderplugincarousel-' . $id . '" data-carouselid="' . $id . '" data-width="' . $data->width . '" data-height="' . $data->height . '" data-skin="' . $data->skin . '"';
			
			if (isset($data->dataoptions) && strlen($data->dataoptions) > 0)
			{
				$ret .= ' ' . stripslashes($data->dataoptions);
			}
			
			$boolOptions = array('sameheight', 'sameheightresponsive', 'centerimage', 'fitimage', 'fitcenterimage', 'fixaspectratio', 'autoplay', 'random', 'autoplayvideo', 'circular', 'pauseonmouseover', 'continuous', 'responsive', 'showhoveroverlay', 'showhoveroverlayalways', 'hidehoveroverlayontouch', 'lightboxresponsive', 'lightboxshownavigation', 'lightboxnogroup', 'lightboxshowtitle', 'lightboxshowdescription', 'usescreenquery', 'donotinit', 'addinitscript', 'doshortcodeontext',
					'showplayvideo', 'triggerresize', 'lightboxfullscreenmode', 'lightboxcloseonoverlay', 'lightboxvideohidecontrols', 'lightboxautoslide', 'lightboxshowtimer', 'lightboxshowplaybutton', 'lightboxalwaysshownavarrows', 'lightboxshowtitleprefix');
			foreach ( $boolOptions as $key )
			{
				if (isset($data->{$key}) )
					$ret .= ' data-' . $key . '="' . ((strtolower($data->{$key}) === 'true') ? 'true': 'false') .'"';
			}
			
			$valOptions = array('spacing', 'rownumber', 'visibleitems', 'arrowstyle', 'arrowimage', 'arrowwidth', 'arrowheight', 'navstyle', 'navimage', 'navwidth', 'navheight', 'navspacing', 'hoveroverlayimage', 'lightboxthumbwidth', 'lightboxthumbheight', 'lightboxthumbtopmargin', 'lightboxthumbbottommargin', 'lightboxbarheight', 'lightboxtitlebottomcss', 'lightboxdescriptionbottomcss', 'continuousduration', 
					'scrollmode', 'interval', 'transitionduration', 'lightboxtitlestyle', 'lightboximagepercentage', 'lightboxdefaultvideovolume', 'lightboxoverlaybgcolor', 'lightboxoverlayopacity', 'lightboxbgcolor', 'lightboxtitleprefix', 'lightboxtitleinsidecss', 'lightboxdescriptioninsidecss',
					'playvideoimage', 'playvideoimagepos',
					'sameheightmediumscreen', 'sameheightmediumheight', 'sameheightsmallscreen', 'sameheightsmallheight', 'triggerresizedelay', 'lightboxslideinterval', 'lightboxtimerposition', 'lightboxtimerheight:', 'lightboxtimercolor', 'lightboxtimeropacity', 'lightboxbordersize', 'lightboxborderradius');
			foreach ( $valOptions as $key )
			{
				if (isset($data->{$key}) )
					$ret .= ' data-' . $key . '="' . $data->{$key} . '"';
			}
				
			// screen query
			if (isset($data->screenquery))
				$ret .= " data-screenquery='" . preg_replace('/\s+/', ' ', trim($data->screenquery))   . "'";
			else
				$ret .= " data-screenquery='{ \"mobile\": { \"screenwidth\": 600, \"visibleitems\": 1 } }'";
			
			$ret .= ' data-jsfolder="' . WONDERPLUGIN_CAROUSEL_URL . 'engine/"'; 
			
			if ($data->direction == 'vertical')
				$totalwidth = $data->width;
			else
				$totalwidth = $data->width * $data->visibleitems;
				
			if (strtolower($data->responsive) === 'true')
				$ret .= ' style="display:none;position:relative;margin:0 auto;width:100%;max-width:' . $totalwidth . 'px;"';
			else 
				$ret .= ' style="display:none;position:relative;margin:0 auto;width:' . $totalwidth . 'px;"';
			
			$ret .= ' >';
			
			if ( !isset($data->rownumber) || !is_int($data->rownumber) || $data->rownumber < 1)
				$data->rownumber = 1;
			
			if (isset($data->slides) && count($data->slides) > 0)
			{
				$ret .= '<div class="amazingcarousel-list-container" style="overflow:hidden;">';
				$ret .= '<ul class="amazingcarousel-list">';
				
				$count = 0;
				
				foreach ($data->slides as $index => $slide)
				{		
					foreach($slide as &$value)
					{
						if ( is_string($value) )
							$value = wp_kses_post($value);
					}
					
					if ( isset($data->doshortcodeontext) && (strtolower($data->doshortcodeontext) === 'true') )
					{
						if ($slide->title && strlen($slide->title) > 0)
							$slide->title = do_shortcode($slide->title);
						
						if ($slide->description && strlen($slide->description) > 0)
							$slide->description = do_shortcode($slide->description);
					}
					
					$boolOptions = array('lightbox', 'displaythumbnail', 'lightboxsize', 'weblinklightbox');
					foreach ( $boolOptions as $key )
					{
						if (isset($slide->{$key}) )
							$slide->{$key} = ((strtolower($slide->{$key}) === 'true') ? true: false);
					}
					
					if ($slide->type == 6)
					{
						$items = $this->get_post_items($slide);
						$ret .= $this->create_post_code($items, $data, $id);
					}
					else
					{
						if ($count == 0)
							$ret .= '<li class="amazingcarousel-item">';
						else if ($count % $data->rownumber == 0)
							$ret .= '</li><li class="amazingcarousel-item">';					
						
						$count++;
						
						$ret .= '<div class="amazingcarousel-item-container">';
						
						$image_code = '';
						if ( isset($slide->lightbox) && $slide->lightbox )
						{
							$image_code .= '<a href="';
							if ($slide->type == 0)
							{
								$image_code .= $slide->image;
							}
							else if ($slide->type == 1)
							{
								$image_code .= $slide->mp4;
								if ($slide->webm)
									$image_code .= '" data-webm="' . $slide->webm;
							}
							else if ($slide->type == 2 || $slide->type == 3)
							{
								$image_code .= $slide->video;
							}
						
							if ($slide->title && strlen($slide->title) > 0)
								$image_code .= '" title="' . str_replace("\"", "&quot;", $slide->title);
						
							if ($slide->description && strlen($slide->description) > 0)
								$image_code .= '" data-description="' . str_replace("\"", "&quot;", $slide->description); 
							
							if ($slide->lightboxsize)
								$image_code .= '" data-width="' .  $slide->lightboxwidth . '" data-height="' .  $slide->lightboxheight;
							
							$image_code .= '" data-thumbnail="' . $slide->thumbnail;
							
							$image_code .= '" class="wondercarousellightbox wondercarousellightbox-' . $id . '"';
							if ( !isset($data->lightboxnogroup) || strtolower($data->lightboxnogroup) !== 'true' )
								$image_code .= ' data-group="wondercarousellightbox-' . $id . '"';
							$image_code .= '>';
						}
						else if ($slide->weblink && strlen($slide->weblink) > 0)
						{
							$image_code .= '<a href="' . $slide->weblink . '"';
							if ($slide->linktarget && strlen($slide->linktarget) > 0)
								$image_code .= ' target="' . $slide->linktarget . '"';
							if ( isset($slide->weblinklightbox) && $slide->weblinklightbox )
							{
								$image_code .= '" class="wondercarousellightbox wondercarousellightbox-' . $id . '"';
								if ( !isset($data->lightboxnogroup) || strtolower($data->lightboxnogroup) !== 'true' )
									$image_code .= ' data-group="wondercarousellightbox-' . $id . '"';
								if ($slide->lightboxsize)
									$image_code .= ' data-width="' .  $slide->lightboxwidth . '" data-height="' .  $slide->lightboxheight . '"';
							}
							$image_code .= '>';
						}
							
						if ( isset($slide->displaythumbnail) && $slide->displaythumbnail )
							$image_code .= '<img src="' . $slide->thumbnail . '"';
						else
							$image_code .= '<img src="' . $slide->image . '"';
						$image_code .= ' alt="' . str_replace("\"", "&quot;", $slide->title) . '"';
						$image_code .= ' data-description="' . str_replace("\"", "&quot;", $slide->description) . '"';
						if (!$slide->lightbox)
						{
							if ($slide->type == 1)
							{
								$image_code .= ' data-video="' . $slide->mp4 . '"';
								if ($slide->webm)
									$image_code .= ' data-videowebm="' . $slide->webm . '"';
							}
							else if ($slide->type == 2 || $slide->type == 3)
							{
								$image_code .= ' data-video="' . $slide->video . '"';
							}
						}
						$image_code .= ' />';
						
						if ($slide->lightbox || (!$slide->lightbox && $slide->weblink && strlen($slide->weblink) > 0))
						{
							$image_code .= '</a>';
						}
						
						$title_code = '';
						if ($slide->title && strlen($slide->title) > 0)
							$title_code = $slide->title;
						 
						$description_code = '';
						if ($slide->description && strlen($slide->description) > 0)
							$description_code = $slide->description;
						
						$skin_template = str_replace('&amp;',  '&', $data->skintemplate);
						$skin_template = str_replace('&lt;',  '<', $skin_template);
						$skin_template = str_replace('&gt;',  '>', $skin_template);
						
						$skin_template = str_replace('__IMAGE__',  $image_code, $skin_template);
						$skin_template = str_replace('__TITLE__',  $title_code, $skin_template);
						$skin_template = str_replace('__DESCRIPTION__',  $description_code, $skin_template);
						
						$skin_template = str_replace('__HREF__',  empty($slide->weblink) ? '' : $slide->weblink, $skin_template);
						$skin_template = str_replace('__TARGET__',  empty($slide->linktarget) ? '' : $slide->linktarget, $skin_template);
						
						$ret .= $skin_template;	
					
						$ret .= '</div>';
					}
				}
				
				if ($count > 0)
					$ret .= '</li>';
				
				$ret .= '</ul>';
				$ret .= '<div class="amazingcarousel-prev"></div><div class="amazingcarousel-next"></div>';
				$ret .= '</div>';
				$ret .= '<div class="amazingcarousel-nav"></div>';
				
			}
			if ('F' == 'F')
				$ret .= '<div class="wonderplugin-engine"><a href="http://www.wonderplugin.com/wordpress-carousel/" title="'. get_option('wonderplugin-carousel-engine')  .'">' . get_option('wonderplugin-carousel-engine') . '</a></div>';
			$ret .= '</div>';
			
			$ret .= '</div>';
			
			if (isset($data->addinitscript) && strtolower($data->addinitscript) === 'true')
			{
				$ret .= '<script>jQuery(document).ready(function(){jQuery(".wonderplugin-engine").css({display:"none"});jQuery(".wonderplugincarousel").wonderplugincarouselslider({forceinit:true});});</script>';
			}
			
			if (isset($data->triggerresize) && strtolower($data->triggerresize) === 'true')
			{
				$ret .= '<script>jQuery(document).ready(function(){';
				if ($data->triggerresizedelay > 0)
					$ret .= 'setTimeout(function(){jQuery(window).trigger("resize");},' . $data->triggerresizedelay . ');';
				else
					$ret .= 'jQuery(window).trigger("resize");'; 
				$ret .= '});</script>';
			}
		}
		else
		{
			$ret = '<p>The specified carousel id does not exist.</p>';
		}
		return $ret;
	}
	
	function get_post_items($options) {

		$posts = array();
		
		if ($options->postcategory == -1)
		{
			$posts = wp_get_recent_posts(array(
						'numberposts' 	=> $options->postnumber,
						'post_status' 	=> 'publish'
					));			
		}
		else
		{
			$posts = get_posts(array(
						'numberposts' 	=> $options->postnumber,
						'post_status' 	=> 'publish',
						'category'		=> $options->postcategory
					));
		}
		
		$items = array();	
		
		foreach($posts as $post)
		{
			if (is_object($post))
				$post = get_object_vars($post);
			
			$thumbnail = '';
			$image = '';
			if ( has_post_thumbnail($post['ID']) )
			{
				$featured_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post['ID']), $options->featuredimagesize);
				$thumbnail = $featured_thumb[0];
			
				$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post['ID']), 'full');
				$image = $featured_image[0];
			}
						
			$excerpt = $post['post_excerpt'];
			if (empty($excerpt))
			{
				$excerpts = explode( '<!--more-->', $post['post_content'] );
				$excerpt = $excerpts[0];
				$excerpt = strip_tags( str_replace(']]>', ']]&gt;', strip_shortcodes($excerpt)) );	
			}
			$excerpt = wonderplugin_carousel_wp_trim_words($excerpt, $options->excerptlength);
		
			$post_item = array(
					'type'			=> 0,
					'image'			=> $image,
					'thumbnail'		=> $thumbnail,
					'title'			=> $post['post_title'],
					'posttitle'		=> $post['post_title'],
					'description'	=> $excerpt,
					'weblink'		=> get_permalink($post['ID']),
					'linktarget'	=> $options->postlinktarget
			);
			
			if (isset($options->postlightbox))
			{
				$post_item['lightbox'] = $options->postlightbox;
				$post_item['lightboxsize'] = $options->postlightboxsize;
				$post_item['lightboxwidth'] = $options->postlightboxwidth;
				$post_item['lightboxheight'] = $options->postlightboxheight;
				
				if (isset($options->posttitlelink) && strtolower($options->posttitlelink) === 'true')
				{
					$post_item['posttitle'] = '<a class="amazingcarousel-posttitle-link" href="' . $post_item['weblink'] . '"';
					if (isset($post_item['linktarget']) && strlen($post_item['linktarget']) > 0)
						$post_item['posttitle'] .= ' target="' . $post_item['linktarget'] . '"';
					$post_item['posttitle'] .= '>' . $post['post_title'] . '</a>';
				}
			}
							
			$items[] = (object) $post_item;
		}
		return $items;
	}
	
	function create_post_code($slides, $data, $id) {
		
		$ret = '';
		
		$count = 0;
		
		foreach($slides as $slide)
		{
			if ($count == 0)
				$ret .= '<li class="amazingcarousel-item">';
			else if ($count % $data->rownumber == 0)
				$ret .= '</li><li class="amazingcarousel-item">';
			
			$count++;
			
			$ret .= '<div class="amazingcarousel-item-container">';
			
			$skin_template = str_replace('&amp;',  '&', $data->skintemplate);
			$skin_template = str_replace('&lt;',  '<', $skin_template);
			$skin_template = str_replace('&gt;',  '>', $skin_template);
			
			$image_code = '';
			
			if ($slide->thumbnail && strlen($slide->thumbnail) > 0)
			{
				if ( isset($slide->lightbox) && strtolower($slide->lightbox) === 'true' )
				{
					$image_code .= '<a href="';
					$image_code .= $slide->image;
					
					if ($slide->title && strlen($slide->title) > 0)
						$image_code .= '" title="' . str_replace("\"", "&quot;", $slide->title);
				
					if ($slide->description && strlen($slide->description) > 0)
						$image_code .= '" data-description="' . str_replace("\"", "&quot;", $slide->description);

					if ( isset($slide->lightboxsize) && strtolower($slide->lightboxsize) === 'true' )
						$image_code .= '" data-width="' .  $slide->lightboxwidth . '" data-height="' .  $slide->lightboxheight;
						
					$image_code .= '" data-thumbnail="' . $slide->thumbnail;
						
					$image_code .= '" class="wondercarousellightbox wondercarousellightbox-' . $id . '"';
					if ( !isset($data->lightboxnogroup) || strtolower($data->lightboxnogroup) !== 'true' )
						$image_code .= ' data-group="wondercarousellightbox-' . $id . '"';
					
					$image_code .= '>';
				}
				else if ($slide->weblink && strlen($slide->weblink) > 0)
				{
					$image_code .= '<a href="' . $slide->weblink . '"';
					if ($slide->linktarget && strlen($slide->linktarget) > 0)
						$image_code .= ' target="' . $slide->linktarget . '"';
					$image_code .= '>';
				}
				
				$image_code .= '<img src="' . $slide->thumbnail . '"';
				$image_code .= ' alt="' . str_replace("\"", "&quot;", $slide->title) . '"';
				$image_code .= ' data-description="' . str_replace("\"", "&quot;", $slide->description) . '"';
				$image_code .= ' />';
				
				if ((isset($slide->lightbox) && strtolower($slide->lightbox) === 'true') || ($slide->weblink && strlen($slide->weblink) > 0))
				{
					$image_code .= '</a>';
				}
			}
			
			$skin_template = str_replace('__IMAGE__',  $image_code, $skin_template);
			$skin_template = str_replace('__TITLE__',  $slide->posttitle, $skin_template);
			$skin_template = str_replace('__DESCRIPTION__',  $slide->description, $skin_template);
			$skin_template = str_replace('__HREF__',  $slide->weblink, $skin_template);
			$skin_template = str_replace('__TARGET__',  $slide->linktarget, $skin_template);
						
			$ret .= $skin_template;
			$ret .= '</div>';
		}
		
		if ($count > 0)
			$ret .= '</li>';
		
		return $ret;
	}
	
	function delete_item($id) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		$ret = $wpdb->query( $wpdb->prepare(
				"
				DELETE FROM $table_name WHERE id=%s
				",
				$id
		) );
		
		return $ret;
	}
	
	function trash_item($id) {
	
		return $this->set_item_status($id, 0);
	}
	
	function restore_item($id) {
	
		return $this->set_item_status($id, 1);
	}
	
	function set_item_status($id, $status) {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
	
		$ret = false;
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$data = json_decode($item_row->data, true);
			$data['publish_status'] = $status;
			$data = json_encode($data);
	
			$update_ret = $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET data=%s WHERE id=%d", $data, $id ) );
			if ( $update_ret )
				$ret = true;
		}
	
		return $ret;
	}
	
	function clone_item($id) {
	
		global $wpdb, $user_ID;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		$cloned_id = -1;
		
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$time = current_time('mysql');
			$authorid = $user_ID;
			
			$ret = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$item_row->name . " Copy",
					$item_row->data,
					$time,
					$authorid
			) );
				
			if ($ret)
				$cloned_id = $wpdb->insert_id;
		}
	
		return $cloned_id;
	}
	
	function is_db_table_exists() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
	
		return ( strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name) );
	}
	
	function is_id_exist($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
	
		$carousel_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		return ($carousel_row != null);
	}
	
	function create_db_table() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE $wpdb->collate";
	
		$sql = "CREATE TABLE $table_name (
		id INT(11) NOT NULL AUTO_INCREMENT,
		name tinytext DEFAULT '' NOT NULL,
		data MEDIUMTEXT DEFAULT '' NOT NULL,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		authorid tinytext NOT NULL,
		PRIMARY KEY  (id)
		) $charset;";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	function save_item($item) {
		
		global $wpdb, $user_ID;
		
		if ( !$this->is_db_table_exists() )
		{
			$this->create_db_table();
				
			$create_error = "CREATE DB TABLE - ". $wpdb->last_error;
			if ( !$this->is_db_table_exists() )
			{				
				return array(
						"success" => false,
						"id" => -1,
						"message" => $create_error
				);
			}
		}	
		
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		$id = $item["id"];
		$name = $item["name"];
		
		unset($item["id"]);
		$data = json_encode($item);
		
		if ( empty($data) )
		{
			$json_error = "json_encode error";
			if ( function_exists('json_last_error_msg') )
				$json_error .= ' - ' . json_last_error_msg();
			else if ( function_exists('json_last_error') )
				$json_error .= 'code - ' . json_last_error();
		
			return array(
					"success" => false,
					"id" => -1,
					"message" => $json_error
			);
		}
		
		$time = current_time('mysql');
		$authorid = $user_ID;
		
		if ( ($id > 0) && $this->is_id_exist($id) )
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					UPDATE $table_name
					SET name=%s, data=%s, time=%s, authorid=%s
					WHERE id=%d
					",
					$name,
					$data,
					$time,
					$authorid,
					$id
			) );
			
			if (!$ret)
			{
				return array(
						"success" => false,
						"id" => $id, 
						"message" => "UPDATE - ". $wpdb->last_error
					);
			}
		}
		else
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO $table_name (name, data, time, authorid)
					VALUES (%s, %s, %s, %s)
					",
					$name,
					$data,
					$time,
					$authorid
			) );
			
			if (!$ret)
			{
				return array(
						"success" => false,
						"id" => -1,
						"message" => "INSERT - " . $wpdb->last_error
				);
			}
			
			$id = $wpdb->insert_id;
		}
		
		return array(
				"success" => true,
				"id" => intval($id),
				"message" => "Carousel published!"
		);
	}
	
	function get_list_data() {
		
		if ( !$this->is_db_table_exists() )
			$this->create_db_table();
		
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		
		$rows = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);
		
		$ret = array();
		
		if ( $rows )
		{
			foreach ( $rows as $row )
			{
				$ret[] = array(
							"id" => $row['id'],
							'name' => $row['name'],
							'data' => $row['data'],
							'time' => $row['time'],
							'author' => $row['authorid']
						);
			}
		}
	
		return $ret;
	}
	
	function get_item_data($id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
	
		$ret = "";
		$item_row = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id) );
		if ($item_row != null)
		{
			$ret = $item_row->data;
		}

		return $ret;
	}
	
	
	function get_settings() {
	
		$userrole = get_option( 'wonderplugin_carousel_userrole' );
		if ( $userrole == false )
		{
			update_option( 'wonderplugin_carousel_userrole', 'manage_options' );
			$userrole = 'manage_options';
		}
		
		$thumbnailsize = get_option( 'wonderplugin_carousel_thumbnailsize' );
		if ( $thumbnailsize == false )
		{
			update_option( 'wonderplugin_carousel_thumbnailsize', 'medium' );
			$thumbnailsize = 'medium';
		}
		
		$keepdata = get_option( 'wonderplugin_carousel_keepdata', 1 );
		
		$disableupdate = get_option( 'wonderplugin_carousel_disableupdate', 0 );
		
		$supportwidget = get_option( 'wonderplugin_carousel_supportwidget', 1 );
		
		$addjstofooter = get_option( 'wonderplugin_carousel_addjstofooter', 0 );
		
		$jsonstripcslash = get_option( 'wonderplugin_carousel_jsonstripcslash', 1 );
		
		$settings = array(
			"userrole" => $userrole,
			"thumbnailsize" => $thumbnailsize,
			"keepdata" => $keepdata,
			"disableupdate" => $disableupdate,
			"supportwidget" => $supportwidget,
			"addjstofooter" => $addjstofooter,
			"jsonstripcslash" => $jsonstripcslash
		);
		
		return $settings;
		
	}
	
	function save_settings($options) {
	
		if (!isset($options) || !isset($options['userrole']))
			$userrole = 'manage_options';
		else if ( $options['userrole'] == "Editor")
			$userrole = 'moderate_comments';
		else if ( $options['userrole'] == "Author")
			$userrole = 'upload_files';
		else
			$userrole = 'manage_options';
		update_option( 'wonderplugin_carousel_userrole', $userrole );
		
		if (isset($options) && isset($options['thumbnailsize']))
			$thumbnailsize = $options['thumbnailsize'];
		else
			$thumbnailsize = 'medium';
		update_option( 'wonderplugin_carousel_thumbnailsize', $thumbnailsize );
		
		if (!isset($options) || !isset($options['keepdata']))
			$keepdata = 0;
		else
			$keepdata = 1;
		update_option( 'wonderplugin_carousel_keepdata', $keepdata );
		
		if (!isset($options) || !isset($options['disableupdate']))
			$disableupdate = 0;
		else
			$disableupdate = 1;
		update_option( 'wonderplugin_carousel_disableupdate', $disableupdate );
		
		if (!isset($options) || !isset($options['supportwidget']))
			$supportwidget = 0;
		else
			$supportwidget = 1;
		update_option( 'wonderplugin_carousel_supportwidget', $supportwidget );
		
		if (!isset($options) || !isset($options['addjstofooter']))
			$addjstofooter = 0;
		else
			$addjstofooter = 1;
		update_option( 'wonderplugin_carousel_addjstofooter', $addjstofooter );
		
		if (!isset($options) || !isset($options['jsonstripcslash']))
			$jsonstripcslash = 0;
		else
			$jsonstripcslash = 1;
		update_option( 'wonderplugin_carousel_jsonstripcslash', $jsonstripcslash );
	}
	
	function get_plugin_info() {
	
		$info = get_option('wonderplugin_carousel_information');
		if ($info === false)
			return false;
	
		return unserialize($info);
	}
	
	function save_plugin_info($info) {
	
		update_option( 'wonderplugin_carousel_information', serialize($info) );
	}
	
	function check_license($options) {
	
		$ret = array(
				"status" => "empty"
		);
	
		if ( !isset($options) || empty($options['wonderplugin-carousel-key']) )
		{
			return $ret;
		}
	
		$key = sanitize_text_field( $options['wonderplugin-carousel-key'] );
		if ( empty($key) )
			return $ret;
	
		$update_data = $this->controller->get_update_data('register', $key);
		if( $update_data === false )
		{
			$ret['status'] = 'timeout';
			return $ret;
		}
	
		if ( isset($update_data->key_status) )
			$ret['status'] = $update_data->key_status;
	
		return $ret;
	}
	
	function deregister_license($options) {
	
		$ret = array(
				"status" => "empty"
		);
	
		if ( !isset($options) || empty($options['wonderplugin-carousel-key']) )
			return $ret;
	
		$key = sanitize_text_field( $options['wonderplugin-carousel-key'] );
		if ( empty($key) )
			return $ret;
	
		$info = $this->get_plugin_info();
		$info->key = '';
		$info->key_status = 'empty';
		$info->key_expire = 0;
		$this->save_plugin_info($info);
	
		$update_data = $this->controller->get_update_data('deregister', $key);
		if ($update_data === false)
		{
			$ret['status'] = 'timeout';
			return $ret;
		}
	
		$ret['status'] = 'success';
	
		return $ret;
	}
}
