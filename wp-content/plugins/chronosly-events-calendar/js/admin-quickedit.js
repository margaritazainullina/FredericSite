/* TODO pasarlo todo a Angularjs */



jQuery(document).ready(function($){


        $("body").bind("ajaxComplete", function(e, xhr, settings){
            //Complete
       $(".editinline").click(function(){
           if($("body.taxonomy-chronosly_category").length){
               $("input.cat-color").val( $(this).parents("tr").find(".cat-color").css("background-color"));
               color_picker(jQuery("input.cat-color"));


               setInterval( function(){

                   if(jQuery("input.sp-input").length){
                       jQuery(".sp-preview-inner").css("background-color", jQuery("input.sp-input").val());
                       $("input.cat-color").val( jQuery("input.sp-input").val());
                   }
               }, 1000);
           }
           else {
               var vars = $.parseJSON($(this).parents("tr").find(".chronosly-qe-vars").html());

                var id = $(this).parents("tr").attr("id").replace("post", "edit");

                setTimeout( function(){jQuery.each(vars, function(i,val){
                    if(i != "organizer" && i != "places" && i != "tickets") {
                        if(jQuery(".chronosly-fields").find("input[name='"+i+"']").length || jQuery(".chronosly-fields").find("select[name='"+i+"']").length){
                            // console.log(i+" "+val);
                            if(jQuery(".chronosly-fields").find("input[name='"+i+"']").attr("type") == "checkbox"){
                                if(val) jQuery(".chronosly-fields").find("input[name='"+i+"']").attr("checked", "checked");
                                else jQuery(".chronosly-fields").find("input[name='"+i+"']").removeAttr("checked");
                            }
                            else if(jQuery(".chronosly-fields").find("select[name='"+i+"']").length) jQuery(".chronosly-fields").find("select[name='"+i+"'] option[value='"+val+"']").attr("selected", "selected");
                            else if(jQuery(".chronosly-fields").find("input[name='"+i+"']").length) jQuery(".chronosly-fields").find("input[name='"+i+"']").val(val);

                        }
                    }
                    else if(i == "organizer" && val){
                        jQuery.each(val, function(j,v){
                            jQuery("#in-chronosly_organizer-"+v).attr("checked", "checked");
                        });
                    }
                    else if(i == "places" && val){
                        jQuery.each(val, function(j,v){
                            jQuery("#in-chronosly_places-"+v).attr("checked", "checked");
                        });
                    }
                   /* else if(i == "tickets" && val){
                        //[null,[{"name":"soldout","value":"1"},{"name":"title","value":"nuevo"},{"name":"price","value":"20"},{"name":"capacity","value":""},{"name":"min-user","value":""},{"name":"max-user","value":""},{"name":"start-time","value":""},{"name":"end-time","value":""},{"name":"link","value":""},{"name":"notes","value":""}]]
                        jQuery.each(val, function(j,v1){
                           if(j > 0){
                               console.log(v1);
                               var tick = "";
                               jQuery.each(v1, function(i,v){tick[v.name] = v.value;});
                               var check = "";
                               if(tick["soldout"]) check = "checked";
                               jQuery("ul#tickets").append("<li><label>"+title+"</label>: Price <input name='tickets["+j+"][\"price\"]' type='text' value='"+price+"'/>  Soldout <input name='tickets["+j+"][\"soldout\"]' type='checkbox' value='1' "+check+"/></li>");
                           }
                        });
                    }*/

                });
                    jQuery("#ev-from").datepicker({ dateFormat: 'yy-mm-dd' });//todo: formato desde la config
                    jQuery("#ev-to").datepicker({ dateFormat: 'yy-mm-dd' });
                    jQuery("#rrule_until").datepicker({ dateFormat: 'yy-mm-dd' });
                    jQuery("#ev-from").change(function(){
                        if(!jQuery("#ev-to").val()) jQuery("#ev-to").val(jQuery("#ev-from").val())
                    });

                    //jQuery(".field-hide").hide();
                    jQuery("#repeat").change(function(){
                        var val = jQuery(this).find("option:selected").val();
                        if(val == "") {
                            jQuery(".field-hide.field1").hide();
                            jQuery(".end-repeat-section").hide();
                        }
                        else {
                            jQuery(".end-repeat-section").show();
                            jQuery(".field-hide.field1").show();

                        }
                    }).change();


                    jQuery("#repeat_end_type").change(function(){
                        var val = jQuery(this).find("option:selected").val();
                        if(val != "never") {
                            if(val == "until") {
                                jQuery(".repeat_type_until").show();
                                jQuery(".repeat_type_count").hide();
                            }
                            else if(val == "count") {
                                jQuery(".repeat_type_until").hide();
                                jQuery(".repeat_type_count").show();
                            }
                        } else {
                            jQuery(".repeat_type_until").hide();
                            jQuery(".repeat_type_count").hide();
                        }
                    }).change();
                    if( jQuery("#repeat_end_type option:selected").val() == "until") jQuery(".repeat_type_count").hide();
                    else jQuery(".repeat_type_until").hide();},600);
           }
        });

     });

    //duplicado porque no funcionaba bien el datepicker
    $(".editinline").click(function(){
        if($("body.taxonomy-chronosly_category").length){

            $("input.cat-color").val( $(this).parents("tr").find(".cat-color").css("background-color"));
            color_picker(jQuery("input.cat-color"));


                setInterval( function(){

                        if(jQuery("input.sp-input").length){
                            jQuery(".sp-preview-inner").css("background-color", jQuery("input.sp-input").val());
                             $("input.cat-color").val( jQuery("input.sp-input").val());
                    }
                }, 1000);

        }
        else {
            var vars = $.parseJSON($(this).parents("tr").find(".chronosly-qe-vars").html());
            var id = $(this).parents("tr").attr("id").replace("post", "edit");

            setTimeout( function(){jQuery.each(vars, function(i,val){
                if(i != "organizer" && i != "places" && i != "tickets") {
                    if(jQuery(".chronosly-fields").find("input[name='"+i+"']").length || jQuery(".chronosly-fields").find("select[name='"+i+"']").length){
                        // console.log(i+" "+val);
                        if(jQuery(".chronosly-fields").find("input[name='"+i+"']").attr("type") == "checkbox"){
                            if(val) jQuery(".chronosly-fields").find("input[name='"+i+"']").attr("checked", "checked");
                            else jQuery(".chronosly-fields").find("input[name='"+i+"']").removeAttr("checked");
                        }
                        else if(jQuery(".chronosly-fields").find("select[name='"+i+"']").length) jQuery(".chronosly-fields").find("select[name='"+i+"'] option[value='"+val+"']").attr("selected", "selected");
                        else if(jQuery(".chronosly-fields").find("input[name='"+i+"']").length) jQuery(".chronosly-fields").find("input[name='"+i+"']").val(val);

                    }
                }
                else if(i == "organizer" && val){
                    jQuery.each(val, function(j,v){
                        jQuery("#in-chronosly_organizer-"+v).attr("checked", "checked");
                    });
                }
                else if(i == "places" && val){
                    jQuery.each(val, function(j,v){
                        jQuery("#in-chronosly_places-"+v).attr("checked", "checked");
                    });
                }
                /*else if(i == "tickets" && val){
                    //[null,[{"name":"soldout","value":"1"},{"name":"title","value":"nuevo"},{"name":"price","value":"20"},{"name":"capacity","value":""},{"name":"min-user","value":""},{"name":"max-user","value":""},{"name":"start-time","value":""},{"name":"end-time","value":""},{"name":"link","value":""},{"name":"notes","value":""}]]
                    jQuery.each(val, function(j,v1){
                        if(j > 0){
                            var title = "";
                            var price = "";
                            var soldout = "";
                            jQuery.each(v1, function(i,v){ var name = v.name;if(name == "soldout") soldout = v.value;if(name == "title") title = v.value;if(name == "price") price = v.value;});
                            var check = "";
                            if(soldout) check = "checked";
                            jQuery("ul#tickets").append("<li><label>"+title+"</label>: Price <input name='tickets["+j+"][\"price\"]' type='text' value='"+price+"'/>  Soldout <input name='tickets["+j+"][\"soldout\"]' type='checkbox' value='1' "+check+"/></li>");
                        }
                    });
                }*/
            });
                jQuery("#ev-from").datepicker({ dateFormat: 'yy-mm-dd' });//todo: formato desde la config
                jQuery("#ev-to").datepicker({ dateFormat: 'yy-mm-dd' });
                jQuery("#rrule_until").datepicker({ dateFormat: 'yy-mm-dd' });
                jQuery("#ev-from").change(function(){
                    if(!jQuery("#ev-to").val()) jQuery("#ev-to").val(jQuery("#ev-from").val())
                });

                //jQuery(".field-hide").hide();
                jQuery("#repeat").change(function(){
                    var val = jQuery(this).find("option:selected").val();
                    if(val == "") {
                        jQuery(".field-hide.field1").hide();
                        jQuery(".end-repeat-section").hide();
                    }
                    else {
                        jQuery(".end-repeat-section").show();
                        jQuery(".field-hide.field1").show();

                    }
                }).change();


                jQuery("#repeat_end_type").change(function(){
                    var val = jQuery(this).find("option:selected").val();
                    if(val != "never") {
                        if(val == "until") {
                            jQuery(".repeat_type_until").show();
                            jQuery(".repeat_type_count").hide();
                        }
                        else if(val == "count") {
                            jQuery(".repeat_type_until").hide();
                            jQuery(".repeat_type_count").show();
                        }
                    } else {
                        jQuery(".repeat_type_until").hide();
                        jQuery(".repeat_type_count").hide();
                    }
                }).change();
                if( jQuery("#repeat_end_type option:selected").val() == "until") jQuery(".repeat_type_count").hide();
                else jQuery(".repeat_type_until").hide();},600);
        }
     });


    
});



function color_picker(el){
    el.spectrum({
        showInput: true,
        allowEmpty: true,
        showInitial: true,
        showPalette: true,
        showSelectionPalette: true,
        maxPaletteSize: 20,
        preferredFormat: "hex",
        localStorageKey: "spectrum.save",
        cancelText: "cancel",
        chooseText: "select",
        palette: [
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
        ]
    });
}
