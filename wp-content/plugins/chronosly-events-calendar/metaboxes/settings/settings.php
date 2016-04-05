<?php //print_r($vars); ?>
<div id="settings-tabs" class="wrapch">
    <ul>
        <li><a href="#tabs-1"><?php _e("General", "chronosly");?></a></li>
        <li><a href="#tabs-2"><?php _e("Incompatibilities", "chronosly");?></a></li>
        <li><a href="#tabs-3"><?php _e("Template Editor", "chronosly");?></a></li>
        <li><a href="#tabs-4"><?php _e("Url", "chronosly");?></a></li>
        <li><a href="#tabs-5"><?php _e("Status", "chronosly");?></a></li>


    </ul>
    <div class="container">
    <form id="settings" method="post" action="">
		<?php  wp_nonce_field( "chronosly_save_settings", 'chronosly_nonce' ); ?>

      <div id="tabs-1">
        <h2><?php _e("General settings", "chronosly");?></h2>
       
       <label> <?php _e("Enable Tickets", "chronosly")?></label> <input type="checkbox" name="chronosly_tickets" value="1" <?php if(isset($vars['chronosly_tickets'])  and $vars['chronosly_tickets']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("In the Event editor the options for Tickets are shown by default. Disable this option if you don't want the options for Tickets to be shown.", "chronosly");?><br/>           </div><br/>
         
          <input type="hidden" name="chronosly-events-flushed" value="0" />
          <input type="hidden" name="chronosly-organizers-flushed" value="0" />
          <input type="hidden" name="chronosly-places-flushed" value="0" />
          <input type="hidden" name="chronosly-calendar-flushed" value="0" />
          <input type="hidden" name="chronosly-cats-flushed" value="0" />
          <input type="hidden" name="chronosly-tags-flushed" value="0" />

          <input type="hidden" name="chronosly_organizers_addon" value="<?php echo $vars["chronosly_organizers_addon"]?>" />
          <input type="hidden" name="chronosly_places_addon" value="<?php echo $vars["chronosly_places_addon"]?>" />
      <?php if($vars["chronosly_organizers_addon"]) { ?>
          <label> <?php _e("Enable Organizers", "chronosly")?></label> <input type="checkbox" name="chronosly_organizers" value="1" <?php if(isset($vars['chronosly_organizers'])  and $vars['chronosly_organizers']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("In the Event editor the options for Organizers are shown by default. Disable this option if you don't want the options for Organizers to be shown", "chronosly");?><br/>        </div><br/>
      <?php } if($vars["chronosly_places_addon"]) { ?>
 
       <label> <?php _e("Enable Places", "chronosly")?></label> <input type="checkbox" name="chronosly_places" value="1" <?php if(isset($vars['chronosly_places']) and $vars['chronosly_places']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("In the Event editor the options for Places are shown by default. Disable this option if you don't want the options for Places to be shown", "chronosly");?><br/>         </div><br/>
       <?php } ?>
       <label> <?php _e("Enable template editor", "chronosly")?></label> <input type="checkbox" name="chronosly_template_editor" value="1" <?php if(isset($vars['chronosly_template_editor']) and $vars['chronosly_template_editor']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("In the event, organizers and places editors the options for Templates customization are shown by default. Disable this option if you don't waht the options for Templates customization to be shown", "chronosly");?><br/>         </div><br/>
          <label> <?php _e("Currency", "chronosly")?></label>
          <select name="chronosly_currency" id="chronosly_currency">
              <?php echo $currency; ?>
          </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Set currency for you tickets info and tickets selling.", "chronosly");?><br/>        </div><br/>



         
        <h5><?php _e("Events display", "chronosly");?></h5>
          <label> <?php _e("Week starts on", "chronosly")?></label>
          <select name="chronosly_week_start" id="chronosly_week_start">
              <option <?php if($vars['chronosly_week_start'] == "1") echo "selected='selected'"?> value="1"><?php _e("Sunday")?></option>
              <option <?php if($vars['chronosly_week_start'] == "2") echo "selected='selected'"?> value="2"><?php _e("Monday")?></option>
          </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Calendars with weekly view start on Mondays by default. You can set the weeks to start on Sunday or Monday.", "chronosly");?><br/>        </div><br/>

          <label> <?php _e("Show past events", "chronosly")?></label> <input type="checkbox" name="chronosly_show_past_events" value="1" <?php if(isset($vars['chronosly_show_past_events']) and $vars['chronosly_show_past_events'] ) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("All past events are not shown by default, except on calendar. Enable this option if you want past events to be shown", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Hide past events on calendar", "chronosly")?></label> <input type="checkbox" name="hide_past_on_calendar" value="1" <?php if(isset($vars['hide_past_on_calendar']) and $vars['hide_past_on_calendar'] ) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("Hide past events on calendar", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Disable scroll when event is opened", "chronosly")?></label> <input type="checkbox" name="disable_slide_on_show" value="1" <?php if(isset($vars['disable_slide_on_show']) and $vars['disable_slide_on_show'] ) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("Disable the auto scroll when event is opened on event lists", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Event sort", "chronosly")?></label>

        <select name="chronosly_events_order" id="chronosly_events_order">
            <option <?php if($vars['chronosly_events_order'] == "date") echo "selected='selected'"?> value="date"><?php _e("By date", "chronosly")?></option>
            <option <?php if($vars['chronosly_events_order'] == "order") echo "selected='selected'"?> value="order"><?php _e("By order", "chronosly")?></option>
           <?php /* <option <?php if($vars['chronosly_events_order'] == "price") echo "selected='selected'"?> value="price"><?php _e("By price", "chronosly")?></option>*/?>
            <?php /*   <option <?php if($vars['chronosly_events_order'] == "c-name") echo "selected='selected'"?> value="c-name"><?php _e("By category name", "chronosly")?></option>*/?>
            <option <?php if($vars['chronosly_events_order'] == "event") echo "selected='selected'"?> value="event"><?php _e("By event name", "chronosly")?></option>
             <?php /* <option <?php if($vars['chronosly_events_order'] == "o-name") echo "selected='selected'"?> value="o-name"><?php _e("By organizer name", "chronosly")?></option>*/?>
             <?php /* <option <?php if($vars['chronosly_events_order'] == "p-name") echo "selected='selected'"?> value="p-name"><?php _e("By place name", "chronosly")?></option>*/?>
        </select><span class="info"></span>
          <div class="info-hide">
              <?php _e("Sort order is by date by default. You can change the sort order into the following:", "chronosly");?><br/><br/>
              <b><?php _e("date", "chronosly");?></b><br/><br/>
              <?php echo "<b>".__("order","chronosly")."</b>: ".
                  __("it is ordered by the customized order in each type of element: events, categories, organizers, and places.", "chronosly")."<br>".
                  __("The option to set an order is in the Options right sidebar of the elements that have this kind of setting: events, categories, organizers, and places.", "chronosly")."<br>".__("For more information, check the <a target='_blank' href='http://www.chronosly.com/faq/events/others/'>Order settings help</a>", "chronosly");?><br/><br/>
              <b><?php _e("event name", "chronosly");?></b><br/>
          </div><br/>
           <label> <?php _e("Event sort direction", "chronosly")?></label>
           <select name="chronosly_events_orderdir" id="chronosly_events_orderdir">
            <option <?php if($vars['chronosly_events_orderdir'] == "ASC") echo "selected='selected'"?> value="ASC"><?php _e("Ascendent", "chronosly")?></option>
            <option <?php if($vars['chronosly_events_orderdir'] == "DESC") echo "selected='selected'"?> value="DESC"><?php _e("Descendent", "chronosly")?></option>
        </select><br/>
          <label> <?php _e("Featured on top", "chronosly")?></label> <input type="checkbox" name="chronosly_featured_first" value="1" <?php if(isset($vars['chronosly_featured_first'])  and $vars['chronosly_featured_first']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("Featured events are shown on top of the lists or views by default. Disable this option to have them shown by Default sort, from the previous point in General Settings", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Events per page", "chronosly")?></label> <input type="text" name="chronosly_events_x_page" value="<?php echo $vars['chronosly_events_x_page']?>" /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set the number of events to be displayed per page", "chronosly");?><br/>        </div><br/>
          <?php if($vars["chronosly_organizers_addon"]) {?>
         <label> <?php _e("Organizers per page", "chronosly")?></label> <input type="text" name="chronosly_organizers_x_page" value="<?php echo $vars['chronosly_organizers_x_page']?>" /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set the number of rganizers to be displayed per page", "chronosly");?><br/>        </div><br/>
         <?php  }
if($vars["chronosly_places_addon"]){ ?>
          <label> <?php _e("Places per page", "chronosly")?></label> <input type="text" name="chronosly_places_x_page" value="<?php echo $vars['chronosly_places_x_page']?>" /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set the number of places to be displayed per page", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Show event repeats on organizers", "chronosly")?></label> <input type="checkbox" name="chronosly-show-repeats-organizer" value="1" <?php if(isset($vars['chronosly-show-repeats-organizer'])  and $vars['chronosly_featured_first']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("Events repetition will be shown on organizers event list", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Show event repeats on places", "chronosly")?></label> <input type="checkbox" name="chronosly-show-repeats-place" value="1" <?php if(isset($vars['chronosly-show-repeats-place'])  and $vars['chronosly_featured_first']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("Events repetition will be shown on places event list", "chronosly");?><br/>        </div><br/>
         <?php } ?>

          <label> <?php _e("Main event list display", "chronosly")?></label>

          <select name="chronosly_event_list_format" id="chronosly_event_list_format">
              <option <?php if($vars['chronosly_event_list_format'] == "year") echo "selected='selected'"?> value="year"><?php _e("Year", "chronosly")?></option>
              <option <?php if($vars['chronosly_event_list_format'] == "month") echo "selected='selected'"?> value="month"><?php _e("Month", "chronosly")?></option>
              <option <?php if($vars['chronosly_event_list_format'] == "week") echo "selected='selected'"?> value="week"><?php _e("Week", "chronosly")?></option>
              <option <?php if($vars['chronosly_event_list_format'] == "day") echo "selected='selected'"?> value="day"><?php _e("Day", "chronosly")?></option>
              <option <?php if($vars['chronosly_event_list_format'] == "upcoming") echo "selected='selected'"?> value="upcoming"><?php _e("Upcoming", "chronosly")?></option>
          </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Event list are shown by current month by default. Change this option if you want them to be shown weekly, annually or upcoming for a day range. Whatever kind of chosen option, the moment will be always shown (current year, month, day, or next upcoming events)", "chronosly");?><br/>        </div><br/>

          <label> <?php _e("Force main event list date", "chronosly")?></label> <input type="text" name="chronosly_event_list_time" value="<?php echo $vars['chronosly_event_list_time']?>" /><span class="info"></span>           <div class="info-hide">
              <?php _e("This option is closely related to 'Main event list display'. It allows to show another year, month, or days other than the current ones. That is to say, you can choose which events, past or future, will be shown on the events list", "chronosly");?><br/><br/>
              <?php _e("Year: set the year to be shown. e.g. 2015", "chronosly");?><br/>
              <?php _e("Month: set the month to be shown. e.g. if you want to show August, set 8 or 08", "chronosly");?><br/>
              <?php _e("Day: set the day to be shown in format yyyy-mm-dd. e.g. 25th October would be 2014-10-25", "chronosly");?><br/>
              <?php _e("Upcoming: set the day range for upcoming events. e.g. 100", "chronosly");?><br/>
          </div><br/>


      </div>

      <div id="tabs-2">
          <h2><?php _e("Themes and Plugins compatibility", "chronosly");?></h2>
          <div class="theme_comp">
              <div class="frase"><?php _e("Create a page with Chronosly base shortcode, used when you have style incompatibilities with your theme", "chronosly");?></div>
              <?php if(!$vars["chronosly-base-templates-id"]) {?> <a href="admin.php?page=chronosly&create_base=1" class="but"><?php _e("Create Base", "chronosly");?></a>
              <?php } else {?> <a href="admin.php?page=chronosly&delete_base=1" class="but"><?php _e("Delete Base", "chronosly");?></a>
              <?php }?>
              <span class="info"></span>
              <div class="info-hide">
                  <?php echo __("This option creates a page called “chronosly base” and adds the shortcode to load your theme structure.", "chronosly")."<br/><br/>
             ".__("If you want more flexibility with different Chronosly pages (i.g. event list, single event, organizer), to add extra html like different headers, sidebars... you could create your own Chronosly custom template files inside your active theme.", "chronosly");
                  echo "<br/>".__("More info about <a href='http://www.chronosly.com/faq/frontend/theme-style-incompatibilities/' target='_blank'>Chronosly theme style incompatibilities</a>", "chronosly");?><br/>
              </div><br/>
              <input type="hidden" name="chronosly-base-templates-id" value="<?php echo $vars['chronosly-base-templates-id'] ?>" />
              <br/> <br/>
              <label> <?php _e("Solve memory limit on admin", "chronosly")?></label> <input type="checkbox" name="chronosly_admin_memory_limit" value="1" <?php if(isset($vars['chronosly_admin_memory_limit']) and $vars['chronosly_admin_memory_limit']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide"><?php _e("Some sites with low memory servers will need to active this to reduce the memory consumption on admin panels. If your admin panels throws an Allowed memory size exhausted you should active it.", "chronosly");?></div><br/>
              <label> <?php _e("Disable jquery admin", "chronosly")?></label> <input type="checkbox" name="jquery-admin-disable" value="1" <?php if(isset($vars['jquery-admin-disable']) and $vars['jquery-admin-disable']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide"><?php _e("Disable jquery script if another plugins adds jquery source(this will prevent incompatibilities and source code duplication)", "chronosly");?></div><br/>
              <label> <?php _e("Disable gmap script", "chronosly")?></label> <input type="checkbox" name="chronosly-disable-gmap-js" value="1" <?php if(isset($vars['chronosly-disable-gmap-js']) and $vars['chronosly-disable-gmap-js']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide"><?php _e("Disable Google maps script if you already have Gmap on your theme (this will prevent incompatibilities and source code duplication)", "chronosly");?></div><br/>
              <label> <?php _e("Disable chronosly cache", "chronosly")?></label> <input type="checkbox" name="disable_cache" value="1" <?php if(isset($vars['disable_cache']) and $vars['disable_cache']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide"><?php _e("Disable Chronosly internal events cache", "chronosly");?></div><br/>
              <a id="clearcache" class="ch-button warning" style="color:white;" href="javascript:clear_cache()"><?php _e("Clear Cache", "chronosly");?></a>
          </div>

         <?php /* <label style="color:black;"><?php _e("Custom base pages", "chronosly");?></label> <span class="info"></span>
          <div class="info-hide">
              <?php _e("You can use custom pages to become the base for Chronosly, instead of only 1 base.","chronosly"); ?>
              </br> </br><?php _e("Useful for individual customizing the base pages with sidebars, templates, metas,...","chronosly"); ?>
              </br></br><?php _e("The page must have this shortcode ","chronosly"); ?><b>[chronoslybase]</b>
          </div>
          <br/>
          <label> <?php _e("Events base id", "chronosly")?></label> <input type="text" name="chronosly-events-base-templates-id" id="chronosly-events-base-templates-id" value='<?php echo $vars['chronosly-events-base-templates-id']?>' />
          <br/><label> <?php _e("Single Event base id", "chronosly")?></label> <input type="text" name="chronosly-events-single-base-templates-id" id="chronosly-events-single-base-templates-id" value='<?php echo $vars['chronosly-events-single-base-templates-id']?>' />
          <br/><label> <?php _e("Organizers base id", "chronosly")?></label> <input type="text" name="chronosly-organizers-base-templates-id" id="chronosly-organizers-base-templates-id" value='<?php echo $vars['chronosly-organizers-base-templates-id']?>' />
          <br/><label> <?php _e("Single Organizer base id", "chronosly")?></label> <input type="text" name="chronosly-organizers-single-base-templates-id" id="chronosly-organizers-single-base-templates-id" value='<?php echo $vars['chronosly-organizers-single-base-templates-id']?>' />
          <br/><label> <?php _e("Places base id", "chronosly")?></label> <input type="text" name="chronosly-places-base-templates-id" id="chronosly-places-base-templates-id" value='<?php echo $vars['chronosly-places-base-templates-id']?>' />
          <br/><label> <?php _e("Single Place base id", "chronosly")?></label> <input type="text" name="chronosly-places-single-base-templates-id" id="chronosly-places-single-base-templates-id" value='<?php echo $vars['chronosly-places-single-base-templates-id']?>' />
          <br/> <label> <?php _e("Category base id", "chronosly")?></label> <input type="text" name="chronosly-category-base-templates-id" id="chronosly-category-base-templates-id" value='<?php echo $vars['chronosly-category-base-templates-id']?>' />
          <br/> <label> <?php _e("Single Category base id", "chronosly")?></label> <input type="text" name="chronosly-category-single-base-templates-id" id="chronosly-category-single-base-templates-id" value='<?php echo $vars['chronosly-category-single-base-templates-id']?>' />
          <br/><label> <?php _e("Calendar base id", "chronosly")?></label> <input type="text" name="chronosly-calendar-base-templates-id" id="chronosly-calendar-base-templates-id" value='<?php echo $vars['chronosly-calendar-base-templates-id']?>' />
          <br/> */ ?>
      </div>

      <div id="tabs-3">

        <h2><?php _e("Template Editor", "chronosly");?></h2>
       <label> <?php _e("Default template", "chronosly")?></label>
        <select name="chronosly_template_default" id="chronosly_template_default">
            <?php echo $templates_options; ?>
        </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Choose the Template what will be used in the Template Editor by default. You can also change the template once your are into the Template Editor", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Force default template", "chronosly")?></label> <input type="checkbox" name="chronosly_template_default_active" value="1" <?php if(isset($vars['chronosly_template_default_active']) and $vars['chronosly_template_default_active']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide"><?php _e("This option allows only the default template to be shown in all the views, even if they have been created by using other templates with different views", "chronosly");?></div><br/>
          <label> <?php _e("Calendar template", "chronosly")?></label>
          <select name="chronosly_calendar_template_default" id="chronosly_calendar_template_default">
              <?php echo $templates_calendar_options; ?>
          </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Calendar Views can not be edited from the Template Editor. You can choose which will be the default template for the three Calendar Views of year, month and week.", "chronosly");?><br/>        </div><br/>
          <label> <?php _e("Navigation template", "chronosly")?></label>
          <select name="chronosly_titles_template_default" id="chronosly_titles_template_default">
              <?php echo $templates_titles_options; ?>
          </select><span class="info"></span>           <div class="info-hide">                           <?php _e("Calendar Views can not be edited from the Template Editor. You can choose which will be the default template for the three Calendar Views of year, month and week.", "chronosly");?><br/>        </div><br/>

          <label> <?php _e("Min. event width", "chronosly")?></label> <input type="text" name="chronosly_template_min" id="chronosly_template_min" value='<?php echo $vars['chronosly_template_min']?>' />px<span class="info"></span>
          <div class="info-hide">
              <?php _e("Set the minimum event width of your events and the rest of Chronosly views.", "chronosly");?><br/>
              <?php _e("Note that if you set a minimum px which is too low you may have problens with the Responsive versions (only if you have chosen a template optimized for Mobile devices)", "chronosly");?><br/>
              <?php _e("The minimum set is not fixed, and it depends on the applied template.", "chronosly");?><br/>
              <?php _e("We recommend to check, that once the minimum is modified, the different views of the template you are using are correctly shown", "chronosly");?><br/>
          </div><br/>
       <label> <?php _e("Max. event width", "chronosly")?></label> <input type="text" name="chronosly_template_max" id="chronosly_template_max" value='<?php echo $vars['chronosly_template_max']?>' />px<span class="info"></span>
          <div class="info-hide">
              <?php _e("Set the maximum event width of your events and the rest of Chronosly views.", "chronosly");?><br/>
              <?php _e("Note that if you modify this options you may have problens with the Responsive versions (only if you have chosen a template optimized for Mobile devices)", "chronosly");?><br/>
              <?php _e("The maximum set is not fixed, and it depends on the applied template.", "chronosly");?><br/>
              <?php _e("We recommend to check, that once the minimum is modified, the different views of the template you are using are correctly shown", "chronosly");?><br/>
          </div><br/>


       <label> <?php _e("Default category color", "chronosly")?></label> <input type="text" class="color" name="chronosly_category_color" id="chronosly_category_color" value='<?php echo $vars['chronosly_category_color']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("This is the default color for all categories, you can individually set category color inside the category editor", "chronosly");?><br/>        </div><br/>
      <label> <?php _e("Ticket deal color", "chronosly")?></label> <input type="text" class="color" name="chronosly_sale_color" id="chronosly_sale_color" value='<?php echo $vars['chronosly_sale_color']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Color for ticket deals when sale is enabled. Set best color to incerease visibility for your ticket sales", "chronosly");?><br/>        </div><br/>
     <label> <?php _e("Show load template", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_show_load_template" value="1" <?php if(isset($vars['chronosly_dad_show_load_template']) and $vars['chronosly_dad_show_load_template']) echo "checked" ?> /><span class="info"></span>
          <div class="info-hide">
              <?php _e("The option of load template is enabled by default, so different templates can be chosen and loaded in the Template editor", "chronosly");?><br/>
              <?php _e("This option is especially useful to prevent other users (team members or clients) from changing or modifying how the events are shown in the site", "chronosly");?><br/>
          </div><br/>
          <?php /* <label> <?php _e("Show save template", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_show_save_template" value="1" <?php if($vars['chronosly_dad_show_save_template']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>*/ ?>
          <label> <?php _e("Show load view", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_show_load_view" value="1" <?php if(isset($vars['chronosly_dad_show_load_view']) and $vars['chronosly_dad_show_load_view']) echo "checked" ?> /><span class="info"></span>
          <div class="info-hide">
              <?php _e("The option of select view is enabled by default, so different templates views can be chosen in the Template editor", "chronosly");?><br/>
              <?php _e("This option is especially useful to prevent other users (team members or clients) from changing or modifying how the events are shown in the site", "chronosly");?><br/>
          </div><br/>

          <h5><?php _e("Formats ", "chronosly")?></h5>

              <label> <?php _e("Default Time format", "chronosly")?></label> <input type="text" name="chronosly_format_time" id="chronosly_format_time" value='<?php echo $vars['chronosly_format_time']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set display time format for tempalte editor. This option does not modify the format set in your template", "chronosly");?><br/>        </div><br/>
        <label> <?php _e("Default Date format", "chronosly")?></label> <input type="text" name="chronosly_format_date" id="chronosly_format_date" value='<?php echo $vars['chronosly_format_date']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set display date format for template editor. This option does not modify the format set in your template", "chronosly");?><br/>        </div><br/>
        <label> <?php _e("Default DateTime format", "chronosly")?></label> <input type="text" name="chronosly_format_date_time" id="chronosly_format_date_time" value='<?php echo $vars['chronosly_format_date_time']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Set display time and date format for template editor. This option does not modify the format set in your template", "chronosly");?><br/>        </div><br/>
        <label> <?php _e("Full DateTime separator", "chronosly")?></label> <input type="text" name="chronosly_full_datetime_separator" id="chronosly_full_datetime_separator" value='<?php echo $vars['chronosly_full_datetime_separator']?>' /><span class="info"></span>
        <div class="info-hide">
            <?php _e("By default 'to' is set as in 11:00 to 19:00. Set custom date-time separator with dashes '-' '/' or pipes '|' or your custom html code", "chronosly");?><br/>
        </div><br/>

      <?php /* <label> <?php _e("Link event title", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_event_title_link" value="1" <?php if($vars['chronosly_dad_event_title_link']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Link organizer title", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_organizer_title_link" value="1" <?php if($vars['chronosly_dad_organizer_title_link']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Link place title", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_place_title_link" value="1" <?php if($vars['chronosly_dad_place_title_link']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Link category name", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_category_title_link" value="1" <?php if($vars['chronosly_dad_category_title_link']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Link tag name", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_tag_title_link" value="1" <?php if($vars['chronosly_dad_tag_title_link']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Default link action", "chronosly")?></label>
        <select name="chronosly_dad_link_action" id="chronosly_dad_link_action">
            <option <?php if($vars['chronosly_dad_link_action'] == "1") echo "selected='selected'"?> value="1"><?php _e("Open chronosly page", "chronosly")?></option>
            <option <?php if($vars['chronosly_dad_link_action'] == "4") echo "selected='selected'"?> value="4"><?php _e("Open external page", "chronosly")?></option>
        </select><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Open link in new page?", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_link_new_window" value="1" <?php if($vars['chronosly_dad_link_new_window']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Link NoFollow", "chronosly")?></label> <input type="checkbox" name="chronosly_dad_link_nofollow" value="1" <?php if($vars['chronosly_dad_link_nofollow']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Default readmore text", "chronosly")?></label> <input type="text" name="chronosly_dad_readmore" id="chronosly_dad_readmore" value='<?php echo $vars['chronosly_dad_readmore']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Default Ticket buy link text", "chronosly")?></label> <input type="text" name="chronosly_dad_buy_tiket" id="chronosly_dad_buy_tiket" value='<?php echo $vars['chronosly_dad_buy_tiket']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
    */?>
          <label> <?php _e("Categories separator", "chronosly")?></label> <input type="text" name="chronosly_dad_cat_separator" id="chronosly_dad_cat_separator" value='<?php echo $vars['chronosly_dad_cat_separator']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Default Categories separator is ,(comma). Set up how categories should be shown between them.", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Tags separator", "chronosly")?></label> <input type="text" name="chronosly_dad_tag_separator" id="chronosly_dad_tag_separator" value='<?php echo $vars['chronosly_dad_tag_separator']?>' /><span class="info"></span>           <div class="info-hide">                           <?php _e("Default Tags separator is ,(comma). Set up how categories should be shown between them.", "chronosly");?><br/>        </div><br/>
     <label> <?php _e("Default gmap zoom", "chronosly")?></label> <input type="text" name="chronosly_dad_gmap_zoom" id="chronosly_dad_gmap_zoom" value='<?php echo $vars['chronosly_dad_gmap_zoom']?>' /><span class="info"></span>
          <div class="info-hide">
              <?php _e("Default Google maps zoom is 16. Zoom levels between 0 (the lowest zoom level, in which the entire world can be seen on one map) and 21+ (down to streets and individual buildings).", "chronosly");?><br/>
              <?php _e("You can find more information in <a href='https://developers.google.com/maps/documentation/staticmaps/?hl=en#Zoomlevels' target='_blank'>Google Maps help site</a>", "chronosly");?><br/>
          </div><br/>

      <?php /* <label> <?php _e("Show vars box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_vars" value="1" <?php if($vars['chronosly_paint_show_vars']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show text box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_text" value="1" <?php if($vars['chronosly_paint_show_text']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show background box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_back" value="1" <?php if($vars['chronosly_paint_show_back']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show border box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_border" value="1" <?php if($vars['chronosly_paint_show_border']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show spacing box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_space" value="1" <?php if($vars['chronosly_paint_show_space']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show custom box on template editor bar", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_custom" value="1" <?php if($vars['chronosly_paint_show_custom']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
       <label> <?php _e("Show custom css textarea on template editor", "chronosly")?></label> <input type="checkbox" name="chronosly_paint_show_custom_all" value="1" <?php if($vars['chronosly_paint_show_custom_all']) echo "checked" ?> /><span class="info"></span>           <div class="info-hide">                           <?php _e("", "chronosly");?><br/>        </div><br/>
  */ ?>
       </div>
        <div id="tabs-4">
            <h2><?php _e("Url", "chronosly");?></h2>
           <label> <?php _e("Event", "chronosly")?> Slug</label> <input type="text" name="chronosly-slug" id="chronosly-slug" value='<?php echo $vars['chronosly-slug']?>' />
            <input type="hidden" name="chronosly-history-slug" id="chronosly-history-slug" value='<?php echo $vars['chronosly-history-slug']?>' /><span class="info"></span>
            <div class="info-hide">
                <?php _e("Slugs are meant to be used with permalinks as they help describe what the content at the URL is.", "chronosly");?><br/>
                <?php _e("Slugs are a few words that describe an event or a page and can be anything you like.", "chronosly");?><br/>
               <a href="http://codex.wordpress.org/Glossary#Slug" target="_blank" ><?php _e("More info about slugs", "chronosly");?></a><br/>
                <a href="http://codex.wordpress.org/Glossary#Permalink" target="_blank" ><?php _e("More info about permalinks", "chronosly");?></a><br/>
            </div><br/>
            <?php if($vars["chronosly_organizers_addon"]) { ?>
            <label> <?php _e("Organizer", "chronosly")?> Slug</label> <input type="text" name="chronosly-organizer-slug" id="chronosly-organizer-slug" value='<?php echo $vars['chronosly-organizer-slug']?>' />
            <input type="hidden" name="chronosly-organizer-history-slug" id="chronosly-organizer-history-slug" value='<?php echo $vars['chronosly-organizer-history-slug']?>' />
           <br/><label> <?php _e("Place", "chronosly")?> Slug</label> <input type="text" name="chronosly-place-slug" id="chronosly-place-slug" value='<?php echo $vars['chronosly-place-slug']?>' />
            <input type="hidden" name="chronosly-place-history-slug" id="chronosly-place-history-slug" value='<?php echo $vars['chronosly-place-history-slug']?>' />
            <?php } ?> 
            <br/><label> <?php _e("Calendar", "chronosly")?> Slug</label> <input type="text" name="chronosly-calendar-slug" id="chronosly-calendar-slug" value='<?php echo $vars['chronosly-calendar-slug']?>' />
            <input type="hidden" name="chronosly-calendar-history-slug" id="chronosly-calendar-history-slug" value='<?php echo $vars['chronosly-calendar-history-slug']?>' />
            <br/> <label> <?php _e("Category", "chronosly")?> Slug</label> <input type="text" name="chronosly-category-slug" id="chronosly-category-slug" value='<?php echo $vars['chronosly-category-slug']?>' />
            <input type="hidden" name="chronosly-category-history-slug" id="chronosly-category-history-slug" value='<?php echo $vars['chronosly-category-history-slug']?>' />
            <br/><label> <?php _e("Tag", "chronosly")?> Slug</label> <input type="text" name="chronosly-tag-slug" id="chronosly-tag-slug" value='<?php echo $vars['chronosly-tag-slug']?>' />
            <input type="hidden" name="chronosly-tag-history-slug" id="chronosly-tag-history-slug" value='<?php echo $vars['chronosly-tag-history-slug']?>' />

            <h5><?php _e("Url for lists", "chronosly"); ?></h5>
            <label> <?php _e("Main event list", "chronosly")?> url</label> <input type="text" name="chronosly-event-list-url" id="chronosly-event-list-url" value='<?php echo $vars['chronosly-event-list-url']?>' />
            <span class="info"></span>
            <div class="info-hide">
                <?php _e("When you are inside an event this will be te url to go back to event list", "chronosly");?><br/>
            </div><br/>
            <label> <?php _e("Main event list title", "chronosly")?></label> <input type="text" name="chronosly-event-list-title" id="chronosly-event-list-title" value='<?php echo $vars['chronosly-event-list-title']?>' />
             <span class="info"></span>
            <div class="info-hide">
                <?php _e("Title for url to go back to event list", "chronosly");?><br/>
            </div><br/>

            <?php if($vars["chronosly_organizers_addon"]) { ?>
               <label> <?php _e("Organizers list", "chronosly")?> url</label> <input type="text" name="chronosly-organazires-list-url" id="chronosly-organazires-list-url" value='<?php echo $vars['chronosly-organazires-list-url']?>' />
              <span class="info"></span>
              <div class="info-hide">
                  <?php _e("When you are inside an organizer this will be te url to go back to organizers list", "chronosly");?><br/>
              </div><br/>
            <label> <?php _e("Organizers list title", "chronosly")?></label> <input type="text" name="chronosly-organizers-list-title" id="chronosly-organizers-list-title" value='<?php echo $vars['chronosly-organizers-list-title']?>' />
                        <span class="info"></span>
            <div class="info-hide">
                <?php _e("Title for url to go back to organizers list", "chronosly");?><br/>
            </div><br/>
              <label> <?php _e("Places list", "chronosly")?> url</label> <input type="text" name="chronosly-places-list-url" id="chronosly-places-list-url" value='<?php echo $vars['chronosly-places-list-url']?>' />
              <span class="info"></span>
              <div class="info-hide">
                  <?php _e("When you are inside a place this will be te url to go back to places list", "chronosly");?><br/>
              </div><br/>
                          <label> <?php _e("Places list title", "chronosly")?></label> <input type="text" name="chronosly-places-list-title" id="chronosly-places-list-title" value='<?php echo $vars['chronosly-places-list-title']?>' />
          <span class="info"></span>
            <div class="info-hide">
                <?php _e("Title for url to go back to places list", "chronosly");?><br/>
            </div><br/>

                 <?php } ?> 
              <label> <?php _e("Calendar", "chronosly")?> url</label> <input type="text" name="chronosly-calendar-url" id="chronosly-calendar-url" value='<?php echo $vars['chronosly-calendar-url']?>' />
              <span class="info"></span>
              <div class="info-hide">
                  <?php _e("This will be the url for the calendar icon, usefull for navigate to calendar", "chronosly");?><br/>
              </div><br/>
                 
           <?php /* <br/><label> <?php _e("Allow flush rewrite", "chronosly")?></label> <input type="checkbox" name="chronosly-allow-flush" value="1" <?php if(isset($vars['chronosly-allow-flush']) and $vars['chronosly-allow-flush']) echo "checked" ?> /><span class="info"></span>
            <div class="info-hide">
                <?php // _e("Some other plugins using WordPress rewrite urls will not be compatible with Chronosly when do flush_rewrite_urls.", "chronosly");?><br/><br/>
                <?php // _e("If you deactivate it that plugins will work together with Chronosly but you lose the ability to automatically change the slugs above defined.", "chronosly");?><br/><br/>
                <?php // _e("Every time you change any slug you have to go to WP Settings > Permalinks and click save to soft flush the urls and regenerate it.", "chronosly");?><br/><br/>
            </div><br/> */ ?>

        </div>
        <div id="tabs-5">
            <h2><?php echo __("Chronosly status", "chronosly");?></h2>
            <div class="main-status">
                
              
                <h5><?php _e("Templates status", "chronosly")?></h5>
                <label><?php _e("Automatic templates updates", "chronosly")?></label> <input type="checkbox" name="chronosly_templates_autoupdate" value="1" <?php if(isset($vars['chronosly_templates_autoupdate']) and $vars['chronosly_templates_autoupdate']) echo "checked" ?> /><span class="info"></span>
                <div class="info-hide">
                    <?php _e("By default this option is disabled. Mark it if you want Chronosly templates to be automatically updated when a new version is released.", "chronosly");?><br/>
                </div><br/>
                <?php echo $templates_licenses; ?>

            </div>
            
        </div>
        <div class="bottom"><input class="submit" type="submit" value="<?php _e("Save", "chronosly")?>"></div>
</div>

     
    </form>
</div>