<?php
/*
Plugin Name: Random Image
Plugin URI: http://mervin.info/wordpress-random-image
Description: Displays a Random Image from the Post Attachment Gallery/ Page Attachement Gallery / Custom Media Library Items. Easy Implementing Shortcode is available. 
Version: 2.0
Author: mervinpraison
Author URI: http://mervin.info
License: GPL2
*/

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

// some definition we will use

if ( function_exists('add_action') ) {
    //WordPress definitions
    if ( !defined('WP_CONTENT_URL') )
        define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content/uploads/photo-gallery');
    if ( !defined('WP_CONTENT_DIR') )
        define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
    if ( !defined('WP_PLUGIN_URL') )
        define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
    if ( !defined('WP_PLUGIN_DIR') )
        define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
    if ( !defined('PLUGINDIR') )
        define( 'PLUGINDIR', 'wp-content/plugins' ); // Relative to ABSPATH.  For back compat.
        
}

/*  Including the Random Image Class */
include( WP_PLUGIN_DIR . '/wordpress-random-image/random-image-class.php');
include( WP_PLUGIN_DIR . '/wordpress-random-image/random-image-class-id.php');


/*  The Function to get the instance */

function randomimageinstance($arging){

$randmimage = new randomimageclass();
return $randmimage -> mpri_gallery_random($arging);

}
add_shortcode( 'random-image' , 'randomimageinstance' );

function get_random_image($ats) {

    
}


/* Filters for Media / Attachements Column ID in admin area */

add_filter( 'manage_media_columns', 'mediaColumnsHeader' );
add_filter( 'manage_media_custom_column', 'mediaColumnsRow', 10, 2 );



?>