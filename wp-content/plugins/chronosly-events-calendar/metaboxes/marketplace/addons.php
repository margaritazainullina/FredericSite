
<div class="wrapch">
    <div class="marketplace">
        <?php /*<div class="featured"><h3>Elementos destacados</h3></div>*/ ?>
        <div class="list">
            <h3><?php _e("Addons - Chronosly extended features", "chronosly");?></h3>
            <div style="width:80%;margin-bottom:20px;"><?php _e("Chronosly addons allow to extend the basic funcionalities for the WordPress event calendar plugin, like social media sharing, rating and reviews, Evebtbrite ticketing, importing from Google Calendar or Facebook and much more.", "chronosly");?></div>
            <div style="clear:both;width:100%;display:none;" class="filter">
                <?php /* <h3><?php _e("Filters", "chronosly"); ?></h3>
            <a class="downloaded"><?php _e("Downloaded", "chronosly"); ?></a>
            <a class="new"><?php _e("New", "chronosly"); ?></a>
            <a class="featured"><?php _e("Featured", "chronosly"); ?></a>*/?>
                <label><?php _e("Order", "chronosly"); ?>:</label>
                <select name="order">
                    <option value="default">-</option>
                    <option value="price"><?php _e("Price", "chronosly"); ?></option>
                    <option value="popular"><?php _e("Popular", "chronosly"); ?></option>
                    <option value="rated"><?php _e("Best rated", "chronosly"); ?></option>
                    <option value="new"><?php _e("Newer", "chronosly"); ?></option>
                    <option value="name"><?php _e("Name", "chronosly"); ?></option>
                </select>
            </div>
        </div>

<?php /*  <div class="cross_selling"><h3>Venta cruzada hacia templates</h3></div>

        <div class="footer"><ul><li>Mas info y links de interes</li></ul></div>*/?>

    </div>
</div>