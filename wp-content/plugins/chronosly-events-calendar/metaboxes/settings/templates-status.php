
<div class="wrapch">
    <h3><?php _e("Template Status", "chronosly");?></h3>
    <?php _e("This feature automatically check how different templates are used on events, organizers, places and categories.", "chronosly");?><br/><br/>
    <div class="templates-status">
        <div class="filters">
            <?php _e("Filter events", "chronosly");?>
           <select name="filter1" id="filter1">
                <option value="all"><?php _e("All", "chronosly");?></option>
                <option value="not-past"><?php _e("Not past events", "chronosly");?></option>
                <option value="file"><?php _e("Template not manual edited", "chronosly");?></option>
                <option value="chbd"><?php _e("Template manual edited", "chronosly");?></option>
           </select>

            <?php _e("Group by", "chronosly");?>
            <select name="filter2" id="filter2">
               <option value="temp"><?php _e("Template", "chronosly");?></option>
                <option value="view"><?php _e("View", "chronosly");?></option>
                <?php /*<option value="cat"><?php _e("Category", "chronosly");?></option>*/?>
           </select>
        </div>
        <div id="status" style="width:37%;margin: 0 auto;float:left;"></div>
        <h3 style="float:right;width:50%;text-align:center;"><?php _e("Chart representation of template usage","chronosly");?></h3>
        <div style="float:right;width:60%;text-align:center;">
            <div id="pieLegend" ></div>

            <canvas id="container" width="300" height="300" style=" margin: 0 auto;float:right;"></canvas>
        </div>
    </div>

</div>

