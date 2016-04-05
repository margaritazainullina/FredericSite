<?php ?>
<div class="wrapch addon" id="<?php echo (isset($_REQUEST['addon']))?$_REQUEST["addon"]:"";?>">
    <div class="save_info"></div>

    <?php
         if(isset($_REQUEST['installed'])){

             echo "<div class='bubleadvice'>".__('Template successfully installed!', "chronosly")."<br/>";
             echo __("Check", "chronosly")." <a href='admin.php?page=chronosly_addons_configs'>".__("Addons Configs", "chronosly")."</a> ".__("to ensure that all addons are ready for the template.", "chronosly")."</div>";
         } ?>
        <div class="ch-box3" >
            <h3><?php _e("Upload template", "chronosly");?></h3>
            <form id="templates" method="post" action="" enctype="multipart/form-data">

                <input type="file" id="wp_custom_attachment" name="chronosly_addon" value="" size="25" />

                <?php  wp_nonce_field( "chronosly_addons_upload", 'chronosly_nonce' ); ?>
                <input class="button" type="submit" value="<?php _e("Upload", "chronosly");?>"/>
            </form>
        </div>
       
