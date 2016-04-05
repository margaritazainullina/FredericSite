var bubble_create = set_js_code_to_array(translated.bubble_create_js_code);
var bubble_modify = set_js_code_to_array(translated.bubble_modify_js_code);
var field_create = set_js_code_to_array(translated.field_create_js_code);
var field_modify = set_js_code_to_array(translated.field_modify_js_code);

var style_fields = JSON.parse(translated.style_fields);

function set_js_code_to_array(el){
    el = el.split("chseparator2");
    var ret = {};
    jQuery.each(el, function(k, v){
        var s = v.split("chseparator1");
       // console.log(s);
        if(s.length == 2) {
            ret[s[0].replace(/"/g, '' )] = s[1].replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\r/g, "").replace(/\\n/g, "").replace(/\\t/g, "");
        }
    });
    //console.log(ret);
    return ret;
}
	//modificamos el contenido si cambian las vars
	function set_vars(el){
        var type = el.children(".ev-hidden").find(".vars input").first().attr("name");
        var val = el.children(".ev-hidden").find(".vars input").first().val();
        if(!type) {
            type = el.children(".ev-hidden").find(".vars textarea").first().attr("name");
            val = el.children(".ev-hidden").find(".vars textarea").first().val();

        }
        if(!type) return;
        if(jQuery.isEmptyObject(bubble_modify)) throw "Error with the bubbles javascript functions";
        if( jQuery.isEmptyObject(field_modify)) throw "Error with the fields javascript functions";

        if(bubble_modify[type]) eval(bubble_modify[type]);
        var element = el;
        //cargamos las acciones de las fields
        el.children(".ev-hidden").find(".vars").children("input, textarea, select").slice(1).each(function(){
            type = jQuery(this).attr("name");
            if(jQuery(this).attr("type") == "checkbox") val = jQuery(this).is(":checked");
            else if(jQuery(this).prop("tagName") == "SELECT") val = jQuery(this).find("option:selected").val();
            else  val = jQuery(this).val();
            var item = jQuery(this);
            if(field_modify[type] ) eval(field_modify[type]);
        });


	}

	//Creamos los divs y los pintamos como en el front
    function create_element(el){
        if(!el.hasClass("draggable")) return;//prevent twice ins
	var type = el.children(".ev-hidden").find(".vars input").first().attr("name");
	var val = el.children(".ev-hidden").find(".vars input").first().val();
	if(!type) {
		type = el.children(".ev-hidden").find(".vars textarea").first().attr("name");
		val = el.children(".ev-hidden").find(".vars textarea").first().val();

	}
     if(!type) return;
        if( jQuery.isEmptyObject(bubble_create)) throw "Error with the bubbles javascript functions";
        if(jQuery.isEmptyObject(field_create)) throw "Error with the fields javascript functions";
        //creamos el elemento a partir del code js de las bubles i los filtros
        var content ="";

        if(bubble_create[type]) eval(bubble_create[type]);
        var t = type;
        if(type != "cont_box") el.replaceWith("<div class='ev-item new "+type+"'><div  class='ev-data "+type+"'>"+content+"</div><div class='ev-hidden'>"+el.children(".ev-hidden").html()+"</div></div>");
        else el.replaceWith("<div class='ev-item new cont_box'><div class='ev-hidden'>"+el.children(".ev-hidden").html()+"</div></div>");
//cargamos las acciones de las fields

        el.children(".ev-hidden").find(".vars").children("input, textarea, select").slice(1).each(function(){
            type = jQuery(this).attr("name");
            if(jQuery(this).attr("type") == "checkbox") val = jQuery(this).is(":checked");
            else if(jQuery(this).prop("tagName") == "SELECT") val = jQuery(this).find("option:selected").val();
            else  val = jQuery(this).val();
            var item = jQuery(this);
            var element = jQuery(".ev-item.new."+t);
            if(field_create[type]) eval(field_create[type]);
        });




}


    //creamos el mini paint
    function rellenar_estilo(el){
        // console.log(el.html());
        var prev = "";
        el.children(".ev-hidden").find("div").each(function(){
        if(prev == "custom") {
            jQuery(this).parents(".ev-selected").toggleClass("ev-selected");
            return false;
        }
            prev = jQuery(this).attr("class");
            //console.log(jQuery(this).attr("class")+" "+ jQuery(this).html());
            jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden").css("width", "").css("margin-left", "");
            jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden .vars").html(jQuery(this).html());
            jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden .vars input[name*='color']").each(function(){

                color_picker(jQuery(this));

            });
            /*jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden .vars select").each(function(){
                if(!jQuery(this).parents(".box.vars").length) {
                    switch (jQuery(this).attr('name')){
                        default:
                            /*jQuery(this).selectize({
                                create: true,
                                maxItems: 1,
                                sortField: {
                                    field: 'text',
                                    direction: 'asc'
                                }


                            });

                            jQuery(this).next("input.hide").select2({
                                createSearchChoice:function(term, data) {
                                    if ($(data).filter(function() {
                                        return this.text.localeCompare(term)===0;
                                    }).length===0) {
                                        return {id:term, text:term};
                                    },
                                    multiple: false,
                                    data
                                }
                            });
                            break;
                    }
                }

            });*/
            jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden .vars textarea").each(function(){

                if(!jQuery(this).parents(".ev-hidden").hasClass("big")){
                    jQuery(this).parents(".ev-hidden").addClass("big");
                }
             });

            jQuery("div.ev-styling div.box."+jQuery(this).attr("class")+" .ev-hidden .vars textarea.textarea").each(function(){
                if(!jQuery(this).parents(".ev-hidden").hasClass("bigger")){
                    jQuery(this).parents(".ev-hidden").addClass("bigger");
                }

                var cledit = jQuery(this).cleditor({
                    width:        550, // width not including margins, borders or padding
                    height:       250, // height not including margins, borders or padding
                    controls:     // controls to add to the toolbar
                        "bold italic underline strikethrough subscript superscript | font size " +
                            "style | color highlight removeformat | bullets numbering |  " +
                            " alignleft center alignright justify | undo redo | " +
                            "link unlink",
                    colors:       // colors in the color popup
                        "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                            "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                            "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                            "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                            "666 900 C60 C93 990 090 399 33F 60C 939 " +
                            "333 600 930 963 660 060 366 009 339 636 " +
                            "000 300 630 633 330 030 033 006 309 303",
                    fonts:        // font names in the font popup
                        "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                            "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
                    sizes:        // sizes in the font size popup
                        "1,2,3,4,5,6,7",
                    styles:       // styles in the style popup
                        [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                            ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
                            ["Header 6","<h6>"]],
                    useCSS:       false, // use CSS to style HTML when possible (not supported in ie)
                    docType:      // Document type contained within the editor
                        '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
                    docCSSFile:   // CSS file used to style the document contained within the editor
                        "",
                    bodyStyle:    // style to assign to document body contained within the editor
                        "margin:4px; font:10pt Arial,Verdana; cursor:text"
                })[0];
                jQuery(cledit.$frame[0]).attr("id","cleditCool");

                var cleditFrame;
                if(!document.frames)
                {
                    cleditFrame = jQuery("#cleditCool")[0].contentWindow.document;
                }
                else
                {
                    cleditFrame = document.frames["cleditCool"].document;
                }

                jQuery( cleditFrame ).bind('keyup', function(){
                    var v = jQuery(this).find("body").html();
                    jQuery('.ev-selected .ev-hidden .vars textarea').html(v);
                    set_vars(el);

                });

                jQuery("div.cleditorToolbar, .cleditorPopup div").bind("click",function(){
                    var v = jQuery( cleditFrame ).find("body").html();
                    jQuery('.ev-selected .ev-hidden .vars textarea').html(v);
                    set_vars(el);
                });
            });
            //ponemos el float;
            if( el.css("float") == "left") jQuery("div.ev-styling .float option[value='1']").attr("selected", "selected");
            else if( el.css("float") == "right") jQuery("div.ev-styling .float option[value='2']").attr("selected", "selected");
            else if( el.css("float") == "none") jQuery("div.ev-styling .float option[value='3']").attr("selected", "selected");

        });
        //image
        jQuery('div.ev-styling div.box  .upload_image_button').unbind("click").click(function(e) {
            var container =  jQuery(this).parent().find('.upload_image');
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                container.val(attachment.url);
                container.attr("value", container.val());
                container.keyup();
            });

            //Open the uploader dialog
            custom_uploader.open();

        });

        //gallery

        jQuery('div.ev-styling div.box .upload_gallery_button').unbind("click").click(function(e) {
            var container =  jQuery(this).parent().find('.upload_gallery');
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (custom_gallery_uploader) {
                custom_gallery_uploader = undefined;
            }

            //Extend the wp.media object
            var selection = get_shorcode_gallery(container);
            custom_gallery_uploader = wp.media.frames.file_frame = wp.media({
                frame:      'post',
                state:      'gallery-edit',
                title:      wp.media.view.l10n.editGalleryTitle,
                editing:    true,
                multiple:   true,
                button: {
                    text: 'Choose Image'
                },
                selection:  selection
            });




            custom_gallery_uploader.on( 'update',
                function() {
                    var controller = custom_gallery_uploader.states.get('gallery-edit');
                    var library = controller.get('library');
                    // Need to get all the attachment ids for gallery
                    var ids = library.pluck('id');
                    var ides = ids.join(",");
                    container.val("[gallery ids='"+ides+"']");
                    container.attr("value", container.val());
                    container.keyup();

                });

            //Open the uploader dialog
            custom_gallery_uploader.open();

        });
        jQuery("div.ev-styling").fadeOut(300).delay(150).fadeIn(300);
        setTimeout( function() { style_left = jQuery(".ev-styling").offset().left; }, 1000);

        input_onchange(jQuery(".ev-selected"));

        //botones de estilo
        //if(!el.hasClass("ev-item"))


        if(jQuery(".ev-selected").hasClass("ev-box")){
                jQuery("div.ev-styling  span.adjust").hide();
            jQuery("div.ev-styling span.b-up").show();
            jQuery("div.ev-styling span.b-down").show();
        } else {
            jQuery("div.ev-styling  span.adjust").show();
            jQuery("div.ev-styling span.b-up").hide();
            jQuery("div.ev-styling span.b-down").hide();
        }
        jQuery("div.ev-styling  span.delete").unbind("click").click(function(){
            jQuery(".ev-selected").hide('slow', function(){ jQuery(this).remove(); })
        });
        jQuery("div.ev-styling  span.default").unbind("click").click(function(){jQuery(".ev-selected").css("height", "auto").css("width", "").find(".ev-hidden .spacing select[name='padding'] option[value='1']").select();});
        jQuery("div.ev-styling  span.adjust").unbind("click").click(function(){jQuery(".ev-selected").css("height", "auto").css("width", "auto").find(".ev-hidden .spacing select[name='padding'] option[value='0']").select();});
        jQuery("div.ev-styling span.b-up").unbind("click").click(function(){
            if(jQuery(".ev-selected").index() > 0){
                jQuery(".ev-selected").insertBefore(jQuery(".ev-selected").prev());
            }
        });
        jQuery("div.ev-styling span.b-down").unbind("click").click(function(){

            if(!jQuery(".ev-selected").is(':last-child')){
                jQuery(".ev-selected").insertAfter(jQuery(".ev-selected").next());
            }
        });
        jQuery("div.ev-styling  span.duplicate").unbind("click").click(function(){
            var cl = jQuery(".ev-selected").clone();
            cl.insertAfter(jQuery(".ev-selected"));
            jQuery(".ev-selected").toggleClass("ev-selected");
            cl.focus();
            if(cl.hasClass("ev-box")) {
                cl.addClass("dupl");
                init_templates_metas_individual();
            }
            on_click_event();
            //add_buttons_metabox();
        });
        jQuery("div.ev-styling  span.copy-style").unbind("click").click(function(){
            //si es una box copiamos las vars tambien, con el type y el class
            if(jQuery(".ev-selected").hasClass("ev-box")) estilo = {vars: jQuery(".ev-selected .vars").html(),text: jQuery(".ev-selected .text").html(),background: jQuery(".ev-selected .background").html(), border: jQuery(".ev-selected .border").html(), spacing: jQuery(".ev-selected .spacing").html(), custom: jQuery(".ev-selected .custom").html() };
            else estilo = {text: jQuery(".ev-selected .text").html(),background: jQuery(".ev-selected .background").html(), border: jQuery(".ev-selected .border").html(), spacing: jQuery(".ev-selected .spacing").html(), custom: jQuery(".ev-selected .custom").html() };
        });
        jQuery("div.ev-styling  span.paste-style").unbind("click").click(function(){
            if(estilo){
                if(jQuery(".ev-selected").hasClass("ev-box") && estilo.vars)  jQuery(".ev-selected .vars").html(estilo.vars);
                jQuery(".ev-selected .text").html(estilo.text);
                jQuery(".ev-selected .background").html(estilo.background);
                jQuery(".ev-selected .border").html(estilo.border);
                jQuery(".ev-selected .sapcing").html(estilo.spacing);
                jQuery(".ev-selected .custom").html(estilo.custom);

                rellenar_estilo(jQuery(".ev-selected"));
                set_style(jQuery(".ev-selected"));

            }
        });
        jQuery("div.ev-styling  select.float").unbind("change").change(function(){
            var val = jQuery(this).find("option:selected").val();
            if(val == 1) jQuery(".ev-selected").css("float", "left").css("clear", "none");
            else if(val == 2) jQuery(".ev-selected").css("float", "right").css("clear", "none");
            else if(val == 3) jQuery(".ev-selected").css("float", "none").css("clear", "both");
        });
        //añadimos el separador para el align
        if(!jQuery("div.ev-styling  select.float").next("span.separator").length)jQuery("<span class='separator'></span>").insertAfter(jQuery("div.ev-styling  select.float"));
        jQuery( "div.ev-styling div.box").unbind("click").click(function(e){
            var parent = jQuery(e.target).parents(".box");
            if(!parent.length){
                jQuery( "div.ev-styling div.box .ev-hidden").hide();
                jQuery(this).children(".ev-hidden").show("slow");
            }
        });
        jQuery( "div.ev-styling div.box .ev-hidden .close").unbind("click").click(function(e){
            jQuery( this).parent().hide("slow");
        });

        jQuery("div.ev-styling div.box select.readmore_a").change(function(){
            var val = jQuery(this).find("option:selected").val();
            if(val == 4) jQuery(this).parent().find(".var-hidden").show("slow");
            else jQuery(this).parent().find(".var-hidden").hide("slow");

        });
        jQuery("div.ev-styling div.box select.readmore_a").change();

    }

    //añadimos els estilo al template
    function set_style(el){
        if(jQuery.isEmptyObject(style_fields)) throw "Error with the style fields javascript functions";

        // var out = "";
        //reset default aspect para evitar que se generen cosas como siempre bold aun despues de descheckear
        var wdef =  el.outerWidth()/el.parent().width()*100;
       // var h = el.css("height");
        var c = el.css("clear");
        var f = el.css("float");
        var w = "";
        //determine the css real with if auto or 100% or none;
        if(el.widthIsAuto()) w = "auto";
        else w = wdef+"%";
        el.attr("css", "");
        el.css("width", w);
        //el.css("height", h);
        el.css("clear", c);
        el.css("float", f);
        el.children(".ev-hidden ").find("select,input").each(function(){
            var name = jQuery(this).attr("name");
            var check = 0;
            if(jQuery(this).attr("type") == "checkbox"){
                var value = jQuery(this).is(":checked");
                check = 1;
            }
            else if(jQuery(this).prop("tagName") == "SELECT") var value = jQuery(this).find("option:selected").val();
            else var value = jQuery(this).val();

                if(style_fields[name]) {
                   // console.log(style_fields[name]);
                    jQuery.each(style_fields[name]['fields'], function(k,v){
                        if(value || value === 0){

                            var val = style_fields[name]['values'][k];
                            //console.log(v+" "+value)
                            //console.log(v+' '+eval(val));
                            if(check){
                                if(value) el.css(v, eval(val));
                                else el.css(v, "");
                            } else {
                                 el.css(v, eval(val));
                            }
                        } else el.css(v, "");

                    });
                }



        });
        el.children(".ev-hidden").find("textarea").each(function(){
            var value = jQuery(this).val();
            if(value){
                switch(jQuery(this).attr("name")){

                    case "css":
                        value = value.split(";");
                        jQuery.each(value, function(k, v){
                            var val = v.split(":");
                            if(val) el.css(val[0], val[1]);
                        });
                        //el.attr("style",value);
                        break;
                }
            }
        });
        if(!el.hasClass("ev-data")){
            if(!el.hasClass("ev-box") && !el.hasClass("cont_box")) el = el.find(".ev-data");
        }
        //var w = el.outerWidth()/el.parent().width()*100;
        /*var w = el.css("width");
        el.attr("style", out);
          el.css("width", w);*/
    }



jQuery.fn.widthIsAuto=function() {
    if(this[0]) {
        var ele=jQuery(this[0]);
        if(this[0].style.width=='auto' || ele.outerWidth()==ele.parent().width()) {
            return true;
        } else {
            return false;
        }
    }
    return undefined;
};
