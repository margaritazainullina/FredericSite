<div class="wrapch addons">

    <?php
    global $Post_Type_Chronosly;

    if(isset($_POST['chronosly_nonce'])) {
        //uploading file
        if( !current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_addons_upload" )){
            if(!empty($_FILES['chronosly_addon']['name'])) {
                Chronosly_Cache::clear_cache();

                // Setup the array of supported file types. In this case, it's just PDF.
                $supported_types = array('application/zip');

                // Get the file type of the upload
                $arr_file_type = wp_check_filetype(basename($_FILES['chronosly_addon']['name']));
                $uploaded_type = $arr_file_type['type'];

                // Check if the type is supported. If not, throw an error.
                if(in_array($uploaded_type, $supported_types)) {

                    // Use the WordPress API to upload the file
                    $upload = wp_upload_bits($_FILES['chronosly_addon']['name'], null, file_get_contents($_FILES['chronosly_addon']['tmp_name']));

                    if(isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {
                        WP_Filesystem();
                        $destination_path = CHRONOSLY_ADDONS_PATH;
                        // if(stripos($upload['file'], "organizers_and_places") !== FALSE or stripos($upload['file'], "extended_marketplace") !== FALSE) $destination_path = CHRONOSLY_PATH;
                        $upload['file'] = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $upload['file']);
                        $unzipfile = unzip_file( $upload['file'], $destination_path);

                        if ( $unzipfile === true ) {

                             if(stripos($upload['file'], "organizers_and_places") !== FALSE) {
                                $utils = new Chronosly_Utils();
                                $files = scandir ( $destination_path.DIRECTORY_SEPARATOR."organizers_and_places");
                                foreach ( $files as $file ) {
                                    if ($file != "." && $file != ".."){
                                        $utils->rcopy ( $destination_path.DIRECTORY_SEPARATOR."organizers_and_places/$file", CHRONOSLY_PATH."/$file" );
                                    }
                                }
                                // $utils->rrmdir( $destination_path.DIRECTORY_SEPARATOR."organizers_and_places");
                                $settings = unserialize(get_option("chronosly-settings"));
                                $settings["chronosly_organizers_addon"] = 1;
                                $settings["chronosly_places_addon"] = 1;
                                update_option('chronosly-settings', serialize($settings));
                            } else if(stripos($upload['file'], "extended_marketplace") !== FALSE) {
                                $utils = new Chronosly_Utils();
                                $files = scandir ( $destination_path.DIRECTORY_SEPARATOR."extended_marketplace");
                                foreach ( $files as $file ) {
                                    if ($file != "." && $file != ".."){
                                        $utils->rcopy ( $destination_path.DIRECTORY_SEPARATOR."extended_marketplace/$file",  CHRONOSLY_PATH."/$file" );
                                    }
                                }
                                // $utils->rrmdir( $destination_path.DIRECTORY_SEPARATOR."extended_marketplace");
                            }
                            unlink( $upload['file'] );
                            $names = explode(DIRECTORY_SEPARATOR,  $upload['file']);
                            $name = str_replace(".zip", "",$names[count($names)-1]);
                             if(stripos($upload['file'], "organizers_and_places") !== FALSE) $name = "organizers_and_places";
                            // wp_redirect("admin.php?page=chronosly_addons_configs&installed=$name");
                            echo "<script type=\"text/javascript\"> document.location.href= '".$_SERVER["SCRIPT_URI"]."?page=chronosly_addons_configs&installed=$name'; </script>";
                            die();

                        } else {
                            echo '<div class="bubbleerror">There was an error installing this addon.</div>';
                        }
                    }

                } else {
                    wp_die("The file type that you've uploaded is not a Chronosly Addons ZIP.");
                } // end if/else

            } // end if


        }
    } else if(isset($_REQUEST["update_addon"]) and $_REQUEST["update_addon"] and isset($_REQUEST['addon'])){
        if( !current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $ext = new Chronosly_Extend;
        echo $ext->update_addons($_REQUEST['addon']);
    } else if(isset($_REQUEST["delete"])){
        if( !current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if(has_filter("chronosly_update_template_".$_REQUEST["delete"])) $Post_Type_Chronosly->template->full_update_templates_by_addon($_REQUEST["delete"],array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), "remove");
        if(has_action("chronosly_remove_".$_REQUEST["delete"])) do_action("chronosly_remove_".$_REQUEST["delete"]);
        //remove files and reload page
         $utils = new Chronosly_Utils();
        $utils->rrmdir(CHRONOSLY_ADDONS_PATH.DIRECTORY_SEPARATOR.$_REQUEST["delete"]);
        // wp_redirect("admin.php?page=chronosly_addons_configs&deleted=1");
        echo "<script type=\"text/javascript\"> document.location.href= '".$_SERVER["SCRIPT_URI"]."?page=chronosly_addons_configs&deleted=1'; </script>";
        die();

    }
    //
    ?>


    <?php if(isset($_REQUEST['installed'])){
        echo "<div class='bubblegreen'>".__('Addon successfully installed!', "chronosly")."</div>";
        $Post_Type_Chronosly->template->full_update_templates_by_addon($_REQUEST['installed'],array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12), "update");

    }
    else if(isset($_REQUEST['deleted']))  echo "<div class='bubblegreen'>".__('Addon successfully removed!', "chronosly")."</div>"; ?>
    <h3><?php _e("Upload addon", "chronosly");?></h3>
    <form id="uploads" method="post" action="" enctype="multipart/form-data">

        <input type="file" id="wp_custom_attachment" name="chronosly_addon" value="" size="25" />

        <?php  wp_nonce_field( "chronosly_addons_upload", 'chronosly_nonce' ); ?>
        <input type="submit" value="<?php _e("Upload", "chronosly");?>"/>
    </form>
    <?php          $this->print_addons_main_page();        ?>
</div>


