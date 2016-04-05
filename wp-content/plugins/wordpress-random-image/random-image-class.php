<?php


/*  Copyright 2014  Mervin Praison

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?><?php

// Class

class randomimageclass {

public function mpri_gallery_random($atts){

	  extract(shortcode_atts(array(
	'postid' => false,
	'attachmentids' => false
	), $atts));
	

	if ( $attachmentids == false ){
				if ( $id == false ){
						global $post;
						$ids = $post->ID;

					}
				else
						$ids = $postid;

	}
	else {
		$attachids = $attachmentids;
	}

	
/* Random Image from attachment ID's */

if ($attachids){
	$galleryids= explode(",",$attachids);	
	foreach ($galleryids as $galleryid) {
		$link = wp_get_attachment_url( $galleryid, false );		
		$galleryurls[] = $link;
	}
}


/* Random Image from Gallery of Each Page */
if ( $ids ) {

$args = array( 'post_type' => 'attachment', 'numberposts' => 0, 'post_status' => null, 'post_parent' => $post->ID ); 
$attachments = get_posts($args);



		if ( $attachments ) {
			foreach ( $attachments as $attachment) {				
				$link = wp_get_attachment_url( $attachment->ID, false );
				$galleryurls [] = $link;
			}
			
		} 
		
}

/* Getting Attachment ID from SRC */

function get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;

	}

/* Random Image Processing */

$total = count($galleryurls); 
$random = (mt_rand()%$total); 
$file = $galleryurls[$random];
$theID = get_attachment_id_from_src ($file);
$alt = get_post_meta($theID, '_wp_attachment_image_alt', true);


		if ( $total > 0 ) 
		{
		    if($alt!=NULL)
		    {
		        $finalgallery = "<img src='".$file."' alt ='".$alt."'/>";
		    }
		    else
		    {
		        $finalgallery = "<img src=".$file." />";
            }
            
        }
		else 
		{
			$finalgallery = NULL;
		}

return $finalgallery;

}

}


?>