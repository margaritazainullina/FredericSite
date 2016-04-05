<?php //echo "<pre>";print_r($temp);
global $pastformat, $tabs;
$tabs = "   ";
$perfil = $this->settings["chronosly_tipo_perfil"];
if($st != "front"){
    if($st == "back" or $st == "back-addon"){
       //if($st == "back"){
            ?>
            <div id="spin"></div>

            <div style="display:none;" class="dad_controls">


            <div id="tabs">
                  <?php

                  // if($perfil == 2 or stripos($_SERVER['REQUEST_URI'], "chronosly_edit_templates") !== FALSE) $this->templates_tabs($vista);
                   if($perfil == 2) $this->templates_tabs($vista);
                   else  $this->templates_tabs($vista, 1);

                  ?>
                <div style="clear:both;"></div>
            </div>
            </div>
        <?php // } ?>

        <div style="display:none;" id="tdad_box" class="dad_box">
    <?php
    }

    ?>

  <?php if($perfil == 2) { ?>
      <span class="butt add_box infot" onclick="javascript:add_box()" title="<?php _e("Add box to template.\nA box let you group elements to style, like background of specific block or other effects like box hide/show","chronosly");?>"><?php _e("Add box", "chronosly"); ?></span>
   <?php } ?>
    <span class="butt show_hidden infot" title="<?php _e("Show/hide all hidden boxes, like if one user clicks on read more with 'show hidden boxes' action","chronosly");?>"><?php _e("Hidden boxes", "chronosly"); ?></span>
    <span class="butt show_featured infot" title="<?php _e("Show/hide featured boxes to style the featured template","chronosly");?>"><?php _e("Featured style", "chronosly"); ?></span>
    <span class="butt clear_lorem infot" title="<?php _e("Hidde demo content, lorem ipsum, to show the real content in frontend","chronosly");?>"><?php _e("Remove lorem ipsum", "chronosly"); ?></span>
 <?php if($perfil == 2) {?>
     <span class="butt clear_content infot" title="<?php _e("Remove all content of this template, to start a blank new page","chronosly");?>"><?php _e("Remove all content", "chronosly"); ?></span>
 <?php } ?>

	<div id="box-hide" >
		<div class="ev-hidden">


			<?php


			$out2 ="";
            $order = 0;
            $out2 .= "<div class='vars'>
                        <label>". __('Box type','chronosly')."</label> <select class='style' order='$order' name='type' ><option value='1'>".__("Normal, hide when click on readmore with slide action", "chronosly")."</option><option $b value='2'>".__("Hidden, show when click on readmore with slide action", "chronosly")."</option><option selected='selected' value='3'>".__("Show always", "chronosly")."</option></select><br/>
                        <label>". __('Box class','chronosly')."</label> <input type='text'  name='class' value=''/><br/>
                         <label>". __('Featured box','chronosly')."</label> <input type='checkbox' name='featured' value='1'/><br/>
                        </div>";
            $out2 .= $this->print_box("hide", $temp, $st);
            echo $out2;

			?>
		</div>
		<div class="sortable">
		</div>
	</div>
	<?php
}//end if style back
else {
    $this->templates_tabs($vista, 1);

}

	if(isset($temp->style)){
        $color = $temp->metas["cats_vars"][0]->metas["cat-color"];
        if(!$color)  $color = $this->settings["chronosly_category_color"];
		if($html) echo "<style id='style'>".str_replace(array("#plus#", ";", "[event-class]"), array("+","!important;", ".ev-{{id}}", $color), urldecode($temp->style))."\n</style>";
        else echo "<style id='style'>".str_replace(array("#plus#", ";", "[event-class]", "#cat-color"), array("+","!important;", ".ev-".$temp->post->ID, $color), urldecode($temp->style))."\n</style>";
	}

    $sinmin = array("dad3","dad5","dad6");
     $stilo = "";
    if(!$html) {
        if($this->settings["chronosly_template_max"]) $stilo .= "max-width:".$this->settings["chronosly_template_max"]."px;";
        if($this->settings["chronosly_template_min"] and !in_array($vista, $sinmin)) $stilo .= "min-width:".$this->settings["chronosly_template_min"]."px;";
    ?>
<div <?php if($stilo) echo "style='$stilo'";?> base="<?php echo $template;?>" class="chronosly perfil<?php echo $perfil;?> ch-<?php echo $template;?>  ch-<?php echo $st;?> ch-<?php echo $vista;?> ev-<?php echo $temp->post->ID; if(isset($_REQUEST['small']) and $_REQUEST["small"]) echo " small";if($temp->metas["featured"][0]) echo " box-featured";?>">
<?php } else { ?>

<div <?php if($stilo) echo "style='$stilo;{{max-width}}{{min-width}}'";?> base="<?php echo $template;?>" class="chronosly perfil<?php echo $perfil;?> ch-<?php echo $template;?>  ch-<?php echo $st;?> ch-<?php echo $vista;if($html == 2) echo " box-featured";?> ev-{{id}} {{size}} ">
<?php
        } //skeleton for front dad templates
        if(isset($temp->boxes)){ //if box
            $exist_featured = 0;
             foreach($temp->boxes as $box){

                 $exist_featured = $box->featured;
                 if($exist_featured) break;

             }
            foreach($temp->boxes as $box){ //foreach boxes
                $out3 ="";
                $b_es = $this->parse_style($box->style);

                $style = $this->needed_style($b_es);
                $style = $this->parse_box_style($style, $box, $html);
                $type = $box->type;
                $clase = $box->clase;
                $featured = $box->featured;
                if($st == "front" and $exist_featured){
                    if($html == 2) $temp->metas["featured"][0] = 1;
                    if((isset($temp->metas["featured"][0]) and $temp->metas["featured"][0] ) and !$featured) continue;
                    else if((!isset($temp->metas["featured"][0]) or !$temp->metas["featured"][0]) and $featured) continue;
                }
                if($type == 1) $class= "normal";
                else if($type == 2) $class= "ch-hidden";
                else if($type == 3) $class= "both";
                $a = $b = $c = "";
                if($type == 1) $a = "selected='selected'";
                else if($type == 2) $b = "selected='selected'";
                else if($type == 3) $c = "selected='selected'";

                $out3 .="<div class='vars'>
                    <label>".__('Box type','chronosly')."</label> <select order='0' class='style' name='type' ><option $a  value='1'>".__("Normal, hide when click on readmore with slide action", "chronosly")."</option><option $b  value='2'>".__("Hidden, show when click on readmore with slide action", "chronosly")."</option><option  value='3' $c >".__("Show always", "chronosly")."</option></select><br/>
                    <label>". __('Box class','chronosly')."</label> <input type='text' name='class' value='$clase'/><br/>
                    <label>". __('Featured box','chronosly')."</label> <input type='checkbox' name='featured' value='1'";
             if($featured) $out3 .=" checked='checked' ";
             $out3.="/><br/>
                    </div>";
                $out3 .= $this->print_box($box, $temp, $st);
        if($featured) $clase .=" ch-featured ";

			?>

	<div class="ev-box <?php echo $class." ".$clase;?>" style="<?php echo $style;?>">
<?php
        $tabs .= "      ";
			if($st == "back" or $st == "back-js" or $st == "back-addon") echo "<div class='ev-hidden'>$out3</div><div class='sortable'>";

			foreach($box->items as $item){

                $this->print_item_box($item, $temp, $st, $html);

			} //end items foreach
		?>

		<div class="ch-clear"></div>
<?php if($st == "back" or $st == "back-js" or $st == "back-addon")	echo "</div>";  //end sortable div ?>
	</div><?php //end ev-box ?>

			<?php
            }//end foreach boxes
		}//end if of boxes
		?>

    <div style="clear:both;"></div>
</div>
<?php if($st == "back" or $st == "back-addon" ){ ?>

</div>

    <?php

    if($perfil == 2){ ?>
<div class="ev-styling">
    <select class='float' name='float'>
        <option selected  value='1'><?php _e("Align left", "chronosly"); ?></option><option   value='2'><?php _e("Aling right", "chronosly"); ?></option><option   value='3'><?php _e("New line", "chronosly"); ?></option>
    </select>
    <span class='b-up infot' title="<?php _e("Move up this box one position", "chronosly")?>"><?php _e("Box up", "chronosly"); ?></span>
    <span class='b-down infot' title="<?php _e("Move down this box one position", "chronosly")?>"><?php _e("Box down", "chronosly"); ?></span>
    <span class='default infot' title="<?php _e("Reset the width of item to default, useful when we have edited the size of the item and want to return back","chronosly");?>"><?php _e("Default", "chronosly"); ?></span>
    <span class='adjust infot' title="<?php _e("Automatically adjusts an item width according to its content so that it fits with the rest of items in the Template","chronosly");?>"><?php _e("Adjust", "chronosly"); ?></span>
    <span class='duplicate infot' title="<?php _e("Create a copy of selected item with the same values and settings as the original","chronosly");?>"><?php _e("Duplicate", "chronosly"); ?></span>
    <span class='copy-style infot' title="<?php _e("Copy style of a selected item","chronosly");?>"><?php _e("Copy style", "chronosly"); ?></span>
    <span class='paste-style infot' title="<?php _e("Paste style on a selected item.<br/>Before pastying the style, it should be copied from another item (Copy Style)","chronosly");?>"><?php _e("Paste style", "chronosly"); ?></span>
    <span class='delete infot' title="<?php _e("Delete selected item","chronosly");?>" ><?php _e("Delete", "chronosly"); ?></span>

    <div class="box vars"><?php _e("Variables", "chronosly");?><div class="ev-hidden"><div class="close">X</div><div class="vars"></div></div></div>

    <?php
    $styleboxes = Chronosly_Paint::default_style_boxes();
    foreach($styleboxes as $id=>$box){
        echo  "<div class='box $id'>".__($box, "chronosly").'<div class="ev-hidden"><div class="close">X</div><div class="vars"></div></div></div>';

    }
    ?>
</div>

<div class="after-style" style="clear:both;">
    <?php _e("Custom css", "chronosly");?>
	<textarea name="custom-css" class="extra-custom-css"  rows="6" cols="100" ><?php echo str_replace("#plus#", "+", urldecode($temp->style)); ?></textarea><br/>
</div>
<?php
    }
} ?>
