<?php 
	if(isset($_POST['chronosly_nonce'])) {
		//save vars
		/*if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_save_settings" ) ){
				
				foreach($_POST as $pf=>$pv){
					 $settings[$pf] = $pv;
				}
				update_option('chronosly-settings', serialize($settings));

		} else {
			die( __( 'Action failed. Please refresh the page and retry.', 'chronosly' ) );
		}	
        */
        if ( wp_verify_nonce( $_POST['chronosly_nonce'], "chronosly_save_settings" ) ){
           if($_POST['perfil'] == 1) {
               print_perfil_basic();
           } else  if($_POST['perfil'] == 2) {
               print_perfil_pro();

           }
            $settings = unserialize(get_option("chronosly-settings"));
            $settings['chronosly_tipo_perfil'] = $_POST['perfil'];
            update_option('chronosly-settings', serialize($settings));
        }
	}
	//
 else {

?>


    <div class="wrapch">
        <h2><?php _e("Welcome to", "chronosly");?> Chronosly</h2>
        <p><?php _e("Choose the profile that better suits your skills", "chronosly");?> </p>
         <div class="container">
           <div id="pf1" class="ch-profile">
                <div class="ico"></div>
                <h4><?php _e("Basic", "chronosly");?></h4>
                <p><?php _e("Don't worry about anything, just upload your events very easily. Templates and extra addons are available in our marketplace", "chronosly");?></p>
                <span class="butt"><?php _e("select", "chronosly");?></span>
            </div>
            <div id="pf2" class="ch-profile">
                <div class="ico"></div>

                <h4><?php _e("Advanced", "chronosly");?></h4>
                <p><?php echo __("Take control of everything.", "chronosly")."<br/>".__("Make your own templates or edit the predefined ones with our editing drag &drop system", "chronosly");?></p>
                <span class="butt"><?php _e("select", "chronosly");?></span>

            </div>
             <form id="perfiles" method="post" action="" style="clear:both;">
                 <?php  wp_nonce_field( "chronosly_save_settings", 'chronosly_nonce' ); ?>
                 <input type="hidden" class="perfil" name="perfil" value="" />

             </form>
       </div>


    </div>
<?php }


function  print_perfil_basic(){
    ?>
    <div class="wrapch pf1">
            <div class="tit"><b><?php echo __("Basic", "chronosly")."</b> ". __("version", "chronosly");?><a href="post-new.php?post_type=chronosly" style="font-size:25px;" class="bubleadvice"><?php _e("Create my first event", "chronosly"); ?></a> <a href="admin.php?page=chronosly" style="font-size:25px;" class="bubleadvice"><?php _e("Settings", "chronosly"); ?></a></div>
            <ul class="lev1">
                <li>1. <?php _e("Events", "chronosly");?>
                    <ul class="lev2">
                        <li><a href="http://www.chronosly.com/faq/events/general-info/" target="_blank">1.1  <?php _e("General info", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/events/date/" target="_blank">1.2  <?php _e("Date", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/events/tickets/" target="_blank">1.3  <?php _e("Tickets", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/events/categories/" target="_blank">1.4  <?php _e("Categories", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/events/organizers/" target="_blank">1.5  <?php _e("Organizers", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/events/places/" target="_blank">1.6  <?php _e("Places", "chronosly");?></a></li>
                      </ul>
                </li>
                <li>2. <?php _e("Frontend", "chronosly");?>
                    <ul class="lev2">
                        <li><a href="http://www.chronosly.com/faq/frontend/general-info-2/" target="_blank">2.1  <?php _e("General info", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/frontend/event-views/" target="_blank">2.2  <?php _e("Event views", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/frontend/calendar-views/" target="_blank">2.3  <?php _e("Calendar views", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/frontend/categories-views/" target="_blank">2.4  <?php _e("Categories views", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/frontend/organizers-views/" target="_blank">2.5  <?php _e("Organizers views", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/frontend/places-views/" target="_blank">2.6  <?php _e("Places views", "chronosly");?></a></li>
                    </ul>
                </li>
                <li><a href="http://www.chronosly.com/faq/addons/general-info-4/" target="_blank">3. <?php _e("Addons", "chronosly");?></a></li>
                <li><a href="http://www.chronosly.com/faq/templates/general-info-5/" target="_blank">4. <?php _e("Templates", "chronosly");?></a></li>
                <li>5. <?php _e("Settings", "chronosly");?>
                    <ul class="lev2">
                        <li><a href="http://www.chronosly.com/faq/settings/general-info-6/" target="_blank">5.1  <?php _e("General info", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/change-profile-basic-advanced-pro/" target="_blank">5.2  <?php _e("Change profile Basic or Advanced", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-license-key/" target="_blank">5.3  <?php _e("Set license key", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-default-template/" target="_blank">5.4  <?php _e("Set default template", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-default-event-list-view/" target="_blank">5.5  <?php _e("Set default event list view", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-default-currency/" target="_blank">5.6  <?php _e("Set default currency", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-custom-urls/" target="_blank">5.7  <?php _e("Set custom urls", "chronosly");?></a></li>
                        <li><a href="http://www.chronosly.com/faq/settings/set-formats/" target="_blank">5.8  <?php _e("Set formats", "chronosly");?></a></li>
                    </ul>

                </li>



             </ul>
    </div>
<?php }

function  print_perfil_pro(){
    ?>
    <div class="wrapch pf2">
            <div class="tit"><b><?php echo __("Pro", "chronosly")."</b> ". __("version", "chronosly");?> <a href="post-new.php?post_type=chronosly" style="font-size:25px;" class="bubleadvice"><?php _e("Create my first event", "chronosly"); ?></a><a href="admin.php?page=chronosly" style="font-size:25px;" class="bubleadvice"><?php _e("Settings", "chronosly"); ?></a></div>
        <ul class="lev1">
            <li>1. <?php _e("Events", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/events/general-info/" target="_blank">1.1  <?php _e("General info", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/events/date/" target="_blank">1.2  <?php _e("Date", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/events/tickets/" target="_blank">1.3  <?php _e("Tickets", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/events/categories/" target="_blank">1.4  <?php _e("Categories", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/events/organizers/" target="_blank">1.5  <?php _e("Organizers", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/events/places/" target="_blank">1.6  <?php _e("Places", "chronosly");?></a></li>
                </ul>
            </li>
            <li>2. <?php _e("Frontend", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/frontend/general-info-2/" target="_blank">2.1  <?php _e("General info", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/frontend/event-views/" target="_blank">2.2  <?php _e("Event views", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/frontend/calendar-views/" target="_blank">2.3  <?php _e("Calendar views", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/frontend/categories-views/" target="_blank">2.4  <?php _e("Categories views", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/frontend/organizers-views/" target="_blank">2.5  <?php _e("Organizers views", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/frontend/places-views/" target="_blank">2.6  <?php _e("Places views", "chronosly");?></a></li>
                </ul>
            </li>
            <li>3. <?php _e("Template Editor", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/template-editor/general-info-3/" target="_blank">3.1  <?php _e("General info", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/template-editor/templates-and-views/" target="_blank">3.2  <?php _e("Templates and views", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/template-editor/drag-drop/" target="_blank">3.3  <?php _e("Drag & Drop", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/template-editor/editing-tools/" target="_blank">3.4  <?php _e("Editing tools", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/template-editor/template-status/" target="_blank">3.5  <?php _e("Template status", "chronosly");?></a></li>


                </ul>
            </li>
            <li>4. <?php _e("Addons", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/addons/general-info-4/" target="_blank">4.1  <?php _e("General info", "chronosly");?></a></li>
                    <li  class="pro"><a href="http://www.chronosly.com/faq/addons/addons-and-custom-templates-synergy/" target="_blank">4.2  <?php _e("Addons and custom templates synergy", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/addons/addons-marketplace/" target="_blank">4.3  <?php _e("Addons marketplace", "chronosly");?></a></li>
                </ul>
            </li>
            <li>5. <?php _e("Templates", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/templates/general-info-5/" target="_blank">5.1  <?php _e("General info", "chronosly");?></a></li>
                    <li  class="pro"><a href="http://www.chronosly.com/faq/templates/custom-templates/" target="_blank">5.2  <?php _e("Custom templates", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/templates/templates-marketplace/" target="_blank">5.3  <?php _e("Templates marketplace", "chronosly");?></a></li>

                </ul>
            </li>
            <li>6. <?php _e("Settings", "chronosly");?>
                <ul class="lev2">
                    <li><a href="http://www.chronosly.com/faq/settings/general-info-6/" target="_blank">6.1  <?php _e("General info", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/change-profile-basic-advanced-pro/" target="_blank">6.2  <?php _e("Change profile Basic or Advanced", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-license-key/" target="_blank">6.3  <?php _e("Set license key", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-default-template/" target="_blank">6.4  <?php _e("Set default template", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-default-event-list-view/" target="_blank">6.5  <?php _e("Set default event list view", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-default-currency/" target="_blank">6.6  <?php _e("Set default currency", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-custom-urls/" target="_blank">6.7  <?php _e("Set custom urls", "chronosly");?></a></li>
                    <li><a href="http://www.chronosly.com/faq/settings/set-formats/" target="_blank">6.8  <?php _e("Set formats", "chronosly");?></a></li>
                    <li class="pro"><a href="http://www.chronosly.com/faq/settings/set-editing-tools-config/" target="_blank">6.9  <?php _e("Set editing tools config", "chronosly");?></a></li>

                </ul>

            </li>

    </div>
<?php } ?>