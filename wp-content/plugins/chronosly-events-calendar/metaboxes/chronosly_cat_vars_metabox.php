<?php _e("Category color", "chronosly");?> <input class="cat-color" name="cat-color" type="text" value="<?php echo (isset($vars['cat-color'])?$vars['cat-color']:"");?>"><br/>
<input type="checkbox" <?php if(isset($vars['featured']) and $vars['featured']) echo "checked='checked'";?> name="featured" value="1" /> <?php _e("Featured", "chronosly"); ?><br/>
<?php _e("Order", "chronosly");?> <input type="text" value="<?php echo (isset($vars['order'][0])?$vars['order'][0]:"");?>" name="order" />
<br/>
					