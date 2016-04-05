function HWT_Utilise(targetSelector){
   var preff = '';

   if(targetSelector){
    if(targetSelector != '') preff = targetSelector + ' ';
   }
  
  jQuery(preff + '.numeric').each(function(ind){
     jQuery(this).keydown(function(evt){
	   var theEvent = evt || window.event;
       var key = theEvent.keyCode || theEvent.which;
	   if (((key < 48 || key > 57)&&(key < 96 || key > 105)) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){
         theEvent.returnValue = false;
         if (theEvent.preventDefault) theEvent.preventDefault();
       }
   });
   });
   jQuery(preff + '.decimal').each(function(ind){
     jQuery(this).keydown(function(evt){
	   var theEvent = evt || window.event;
       var key = theEvent.keyCode || theEvent.which;
       if(key != 190 && key != 110)
  	   if (((key < 48 || key > 57)&&(key < 96 || key > 105)) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){
         theEvent.returnValue = false;
         if (theEvent.preventDefault) theEvent.preventDefault();
       }
   });
   });
}


if(jQuery)
{
 jQuery(document).ready(function(){
   HWT_Utilise('');
 });
}