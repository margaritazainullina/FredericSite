
<?php /*//drag and drop save template

_e("If you want you can save this template to use on others events", "chronosly");


?>

<div id="dad_save">
    <?php

    _e("Save template as ", "chronosly");
    ?>
    <input type='text' value=""  class="save_template" name="save_template" />
    <?php
    _e("Or update template", "chronosly");
    ?>
    <select class="update_template">
        <option selected="selected" value=""></option>
        <?php foreach($custom_templates as $c) { ?>
            <option  value="<?php echo $c?>"><?php echo $c?></option>
        <?php } ?>
    </select>
    <span class="button" onclick="javascript:save_template('template_file')"><?php _e("Save", "chronosly")?></span>
    <span class="info"></span>
    <div class="info-hide">
        <?php _e("Guided process do step by step letting you to confirm or update all templates and views", "chronosly");?><br/>
        <?php _e("Auto Update do the job for you, but this is not full suported on custom edited templates that could need your work", "chronosly");?><br/>
        <?php _e("For the single edited templates (previously edited directly inside the events page)  you can also update template using the new drag and drop 'Share' bubble", "chronosly");?><br/><br/>
    </div>
    <div class="save_info"></div>
</div>*/ ?>

<div style="clear:both"></div>
</div>