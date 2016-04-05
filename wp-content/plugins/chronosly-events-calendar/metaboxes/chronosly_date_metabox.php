<div id="ev-date-box">
	<label for="from"><?php echo __("From", "chronosly"); ?></label>
	<input type="text" id="ev-from" name="ev-from" value="<?php echo (isset($vars['ev-from'][0])?$vars['ev-from'][0]:"");?>" />
	<label for="from-h"><?php echo __("Hour", "chronosly"); ?></label>

	<input type="text" id="ev-from-h" name="ev-from-h"  value="<?php echo  (isset($vars['ev-from-h'][0])?$vars['ev-from-h'][0]:"");?>"  />:
	<input type="text" id="ev-from-m" name="ev-from-m"  value="<?php echo  (isset($vars['ev-from-m'][0])?$vars['ev-from-m'][0]:"")?>" />
	<br/>
	<label for="to"><?php echo __("To", "chronosly"); ?></label>
	<input type="text" id="ev-to" name="ev-to"  value="<?php echo  (isset($vars['ev-to'][0])?$vars['ev-to'][0]:"");?>"  />
	<label for="from-h"><?php echo __("Hour", "chronosly"); ?></label>

	<input type="text" id="ev-to-h" name="ev-to-h"  value="<?php echo (isset($vars['ev-to-h'][0])?$vars['ev-to-h'][0]:"");?>" />:
	<input type="text" id="ev-to-m" name="ev-to-m"  value="<?php echo (isset($vars['ev-to-m'][0])?$vars['ev-to-m'][0]:"");?>" />
	 <div >
		<label for="repeat" ><?php echo __("Repeat", "chronosly");?></label>
		<select id='repeat' name="ev-repeat">
			<option <?php if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "") echo "selected";?> value=""><?php _e("Never (Not a recurring event)", "chronosly")?></option>
			<option <?php if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "day") echo "selected";?> value="day"><?php _e("Every day", "chronosly")?></option>
			<option <?php if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "week") echo "selected";?> value="week"><?php _e("Every week", "chronosly")?></option>
			<option <?php if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "month") echo "selected";?> value="month"><?php _e("Every month", "chronosly")?></option>
			<option <?php if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "year") echo "selected";?> value="year"><?php _e("Every year", "chronosly")?></option>
		</select>
	</div>
		

	<div class="end-repeat-section" >
		<div class="field-hide field1 <?php if(!isset($vars['ev-repeat'][0]) or $vars['ev-repeat'][0] == "") echo "hide";?>"  >

			<label><?php _e("Repeat every","chronosly");?></label>
			<input type="text" id="ev-repeat-every" name="ev-repeat-every"  value="<?php echo (isset($vars['ev-repeat-every'][0])?$vars['ev-repeat-every'][0]:"");?>" /><span id="ev-repat-name"><?php

				if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "day") echo __("days", "chronosly");
				else if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "week") echo   __("weeks", "chronosly");
				else if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "month") echo  __("months", "chronosly");
				else if(isset($vars['ev-repeat'][0]) and $vars['ev-repeat'][0] == "year") echo __("years", "chronosly");
			?></span>



			<label><?php _e("End of repeat","chronosly");?></label>
			<select id="repeat_end_type" name="ev-repeat-option" >
				<option <?php if(isset($vars['ev-repeat-option'][0]) and $vars['ev-repeat-option'][0] == "never") echo "selected";?> value="never"><?php _e("never", "chronosly");?></option>
				<option <?php if(isset($vars['ev-repeat-option'][0]) and $vars['ev-repeat-option'][0] == "until") echo "selected";?> value="until"><?php _e("by date", "chronosly");?></option>
				<option <?php if(isset($vars['ev-repeat-option'][0]) and $vars['ev-repeat-option'][0] == "count") echo "selected";?> value="count"><?php _e("by number", "chronosly");?></option>
			</select>
			<div class="ch-clear"></div>
		</div>

		<div class="field-hide repeat_type_until <?php if(isset($vars['ev-repeat-option'][0]) and $vars['ev-repeat-option'][0] != "until") echo "hide";?> ">

			<label><?php _e("End date","chronosly");?></label>
				<input id="rrule_until" type="text" name="ev-until"  value="<?php echo (isset($vars['ev-until'][0])?$vars['ev-until'][0]:"");?>" />
		</div>

		<div class="field-hide repeat_type_count <?php if(isset($vars['ev-repeat-option'][0]) and $vars['ev-repeat-option'][0] != "count") echo "hide";?> ">

			<label ><?php _e("Number of repeats","chronosly");?></label>
				<input id="fc_end_count" type="text" name="ev-end_count"  value="<?php echo (isset($vars['ev-end_count'][0])?$vars['ev-end_count'][0]:"");?>"  />
		</div>
	</div>
	<span class="info"></span>
	<div class="info-hide">
	    <?php _e("If you choose to repeat an event, more options will appear such as repeating the event over a period time, either in days, weeks or years. These new options will allow you to set the end of the repetitions, by setting an exact end date o a definite number of repetitions, for examples the event to be repeated twice.", "chronosly");?><br/><br/>
	    <?php _e("Note: Chronosly does not allow certain repetitions to be set. If the number of days with events is over a week o a month, the system does not allow the event to be published. E.g. if you create 8-days events, these can not be repeated weekly. 32-days events can not be set with weekly or monthly repetition.", "chronosly");?><br/><br/>
	    <?php _e("It is possible to set 2-days events with daily repetitions. In this case the event would be repeated every 2 days.", "chronosly");?><br/><br/>



	</div>
</div>
					