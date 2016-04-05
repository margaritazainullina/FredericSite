<?php
/*
Plugin Name: Media Library Plus Reset
Plugin URI: http://maxgalleria.com
Description: Plugin for reseting Media Library Plus
Author: Max Foundry
Author URI: http://maxfoundry.com
Version: 2.34
Copyright 2015 Max Foundry, LLC (http://maxfoundry.com)

*/


function mlp_reset_menu() {
  add_menu_page('Media Library Plus Reset', 'Media Library Plus Reset', 'manage_options', 'mlp-reset', 'mlp_reset' );
  add_submenu_page('mlp-reset', 'Reset Database', 'Reset Database', 'manage_options', 'clean_database', 'clean_database');
}
add_action('admin_menu', 'mlp_reset_menu');

function mlp_reset() {

	echo "<h3>Media Library Plus Reset Instructions</h3>";
  echo "<h4>If you need to rescan your database, please deactivate the Media Library Plus plugin and then click Media Library Plus Reset->Reset Database to erase the folder data. Then deactivate Media Library Plus Reset and reactivate Media Library Plus which will perform a fresh scan of your database.</h4>";
  
}

function clean_database() {  
    global $wpdb;
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_name'";
    $wpdb->query($sql);
    
    $sql = "delete from $wpdb->prefix" . "options where option_name = 'mgmlp_upload_folder_id'";
    $wpdb->query($sql);
        
    echo "Deleteing mgmlp_folders<br>";
    
    $sql = "TRUNCATE TABLE $wpdb->prefix" . "mgmlp_folders";
    $wpdb->query($sql);
    
    $sql = "DROP TABLE $wpdb->prefix" . "mgmlp_folders";    
    $wpdb->query($sql);
    
    echo "Removing mgmlp_media_folder posts<br>";
    $sql = "delete from $wpdb->prefix" . "posts where post_type = 'mgmlp_media_folder'";
    $wpdb->query($sql);
    
    echo "Done. You can now reactivate Media Library Plus.<br>";

  
}
