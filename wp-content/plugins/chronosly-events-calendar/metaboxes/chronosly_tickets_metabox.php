<div id="chronosly_tickets_form">
    <li> <div class='butons'>
    <div class="solded"><input type="checkbox" value="1" name="soldout" class="soldout"/> <?php _e("Sold Out", "chronosly");?></div>
            <div class="solded"><input type="checkbox" value="1" name="sale" class="soldout"/> <?php _e("Sale", "chronosly");?></div><br/>
            <span class='edit'><?php _e("edit", "chronosly");?></span><span class='delete'><?php _e("delete", "chronosly");?></span>
            
        </div><input type='text'  class='title'  name='title' value='' /><input    type='text' class='price' name='price' value='' /><input type="text" class="price" name="sales-price" value="" /><input   type='text' class='currency' name='currency' value='<?php echo $currency; ?>' /><input   type='text' class='capacity' name='capacity' value='' /><input type="text" class="link" name="link" value="" /><br/><textarea name='notes' class='notes'></textarea>
       </li>
</div>
<ul id="chronosly_tickets_list">

    <?php

    // echo "<li class='ticket-head'><span class='title'>".__("Title","chronosly")."</span><span class='price'>".__("Price","chronosly")."</span><span class='capacity'>".__("Capacity","chronosly")."</span><span class='min-user'>".__("Min.","chronosly")."</span><span class='max-user'>".__("Max.","chronosly")."</span><span class='start-time'>".__("From","chronosly")."</span><span class='end-time'>".__("To","chronosly")."</span><span class='link'>".__("Link","chronosly")."</span></li>";
    echo "<li class='ticket-head'><span class='title'>".__("Title","chronosly")."</span><span class='price'>".__("Price","chronosly")."</span><span class='price'>".__("Sale Price","chronosly")."</span><span class='currency'>".__("Currency","chronosly")."</span><span class='capacity'>".__("Capacity","chronosly")."</span><span class='link'>".__("Link","chronosly")."</span></li>";

    if(isset($vars->tickets)){
        for($i = 1; $i < count($vars->tickets);++$i){
            $soldout = $sale = "";
            $tickets = $vars->tickets[$i];
            $ticket = array();
            foreach($tickets as $t) $ticket[$t->name] = $t->value;

            if($ticket["soldout"]) $soldout = "checked='checked'";
            if($ticket["sale"]) $sale = "checked='checked'";
            echo "<li><div class='butons'>"
            ."<div class='solded'><input name='soldout' type='checkbox' value='1' class='soldout' $soldout/> ".__("Sold Out", "chronosly")."</div>"
            ."<div class='solded'><input name='sale' type='checkbox' value='1' class='soldout' $sale/> ".__("Sale", "chronosly")."</div><br/>"
            ."<span class='edit'>".__("edit","chronosly")."</span><span class='delete'>".__("delete","chronosly")."</span></div>"
            ."<input type='text' readonly class='title'  name='title' value='".$ticket["title"]."' />"
            ."<input  readonly  type='text' class='price' name='price' value='".$ticket["price"]."' />"
            ."<input  readonly type='text' class='price' name='sales-price' value='".$ticket["sales-price"]."' />"
            ."<input readonly  type='text' class='currency' name='currency' value='".$ticket["currency"]."' />"
            ."<input readonly  type='text' class='capacity' name='capacity' value='".$ticket["capacity"]."' />"
            // ."<input  readonly type='text' class='max-user' name='max-user' value='".$ticket["max-user"]."' />"
            // ."<input  readonly type='text' class='sales-start-time' name='start-time' value='".$ticket["start-time"]."' />"
            // ."<input  readonly type='text' class='sales-end-time' name='end-time' value='".$ticket["end-time"]."' />"
            ."<input readonly type='text' class='link' name='link' value='".$ticket["link"]."' /><br/>"
            ."<textarea readonly name='notes' readonly class='notes'>".$ticket["notes"]."</textarea>";
            }
    }
    ?>
</ul>
<input type="button" id="add_ticket" value="<?php _e("Add ticket", "chronosly");?>" /><span class="info"></span>
<div class="info-hide">
    <?php _e("Manage all your event tickets and registrations. Show all important details about your event tickets, such as number of tickets available, price, capacity and much more.", "chronosly");?><br/><br/>
    <?php _e("TITLE: Create the name of your ticket type. Give a descriptive name to ensure you can distinguish between them when you define capacity and availability.", "chronosly");?><br/><br/>
    <?php _e("PRICE: Show ticket prices, also change currency and availability", "chronosly");?><br/><br/>
    <?php _e("SALE PRICE: Set discounted price for the ticket, enable checking on sale.", "chronosly");?><br/><br/>
    <?php _e("CURRENCY: Set different currencies for tickets", "chronosly");?><br/><br/>
    <?php _e("SALE: Active sale price", "chronosly");?><br/><br/>
    <?php _e("SOLD OUT: Set ticket sold out", "chronosly");?><br/><br/>
    <?php _e("CAPACITY: Provide detailed information about the maximum number of tickets that can be sold at your event. This set is important to avoid overbooking your event.", "chronosly");?><br/><br/>
   <!--  <?php
     _e("MIN.: Provide detailed information about the minimum number of tickets that can be can be purchased by each user. This set-up is used to ensure your events are accessible to as many users as possible, also to avoid the resale of ticket", "chronosly");?><br/><br/>
    <?php _e("MAX.: Provide detailed information about the maximum number of tickets that can be can be purchased by each user. This set-up is used to ensure your events are accessible to as many users as possible, also to avoid the resale of tickets.", "chronosly");?><br/><br/>
    <?php _e("FROM: Set ticket sales start date.", "chronosly");?><br/><br/>
    <?php _e("TO: Set ticket sales end date.", "chronosly");?><br/><br/> -->
    <?php _e("LINK: Add an external link to your selling tickets website or platform.", "chronosly");?><br/><br/>

</div>
<input id="tickets" type="hidden" name="tickets" value="" />
                    