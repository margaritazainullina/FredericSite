
<div id="dad1" class="main_box">
<?php
if($this->settings["chronosly_dad_show_load_template"] and !$this->settings["chronosly_template_default_active"]){ ?>
<label class="top"><?php _e("Load template", "chronosly");?></label>

<select class="tdad_select">
<?php echo $select_options; ?>
</select><br/>
    <?php }

if($this->settings["chronosly_dad_show_load_view"]){
?>

    <label class="top"><?php _e("Select view", "chronosly");?></label>
    <select class="dad_select">
        <?php foreach($vistas as $k=>$c) { ?>
            <option value="<?php echo $k?>"><?php echo $c?></option>
        <?php } ?>
    </select><span class="info"></span>
    <div class="info-hide">
        <?php
        _e("A view is how your event is shown in each website navigation section.", "chronosly");
        _e("It allows you to thoroughly customize how each event behaves in each section.", "chronosly");
        echo "<br/>";
        echo "<br/>";

        _e("In your website, events have 6 different views, even if the 2 first ones are the most important (General View, and Inner page View)", "chronosly");
        echo "<br/>";
        _e("1- General View: it is the main view where all the events are displayed", "chronosly");
        echo "<br/>";
        _e("2- Inner page view: it is the inner view of your event, a single page on the event, where all the information is shown", "chronosly");
        echo "<br/>";
        _e("3- Calendar view: customize how you want to show your event in the calendar page", "chronosly");
        echo "<br/>";
        _e("4- Category view: each category page has a list with all the events of that category. You can customize your event and define its list view", "chronosly");
        echo "<br/>";
        _e("5- Organizer view: each organizer page has a list with the organizer's events. You can customize your event and define its list view", "chronosly");
        echo "<br/>";
        _e("6- Place view: each place page has a list with all the events that take place in that place. you can customize your event and define its list view", "chronosly");
        echo "<br/>";
        ?>
    </div>

<?php } ?>

