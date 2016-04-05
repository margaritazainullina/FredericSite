<input type="checkbox" <?php if(isset($vars['featured'][0]) and $vars['featured'][0]) echo "checked='checked'";?> name="featured" value="1" /><?php _e("Featured", "chronosly"); ?><br/>
<?php _e("Order", "chronosly");?><input type="text" value="<?php echo (isset($vars['order'][0])?$vars['order'][0]:"");?>" name="order" />
					