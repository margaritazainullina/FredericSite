
var dbclick = 0;
var lorem = 0;
var custom_uploader;
var custom_gallery_uploader;
var guardamos = translated1.guardamos;//variabe que define el id del template que guardamos o chbd si se trata de bd
var tempbase = "";
var style_left;
var featured = 0;
var feat_prev = 0;


jQuery(document).ready(function($){
    $("span.info").click(function(){$(this).next(".info-hide").slideToggle()});
    $("span.infot").tooltip({
        show: {
            delay: 2000
        },
        content: function () {
            return $(this).prop('title');
        },
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        }
    });
    $("a.warning").click(function(){return confirm($(this).next(".warning-hide").html().trim());});//custom warnings messages
    $("#chronosly_organizer-add-submit").click(function(){
        var args = {
            action : "chronosly_add_organizer",
            name: jQuery("#chronosly_organizer-add #name").val(),
            phone: jQuery("#chronosly_organizer-add #phone").val(),
            web: jQuery("#chronosly_organizer-add #web").val(),
            mail: jQuery("#chronosly_organizer-add #mail").val()

        };
        //generamos el html del template
        $.post( ajaxurl, args, function( data ) {
            //  console.log(data.template);

            $( ".organizer-box" ).append( data );
            $("#chronosly_organizer-add-toggle").click();
        });

    });
    $("#chronosly_place-add-submit").click(function(){
        var args = {
            action : "chronosly_add_place",
            name: jQuery("#chronosly_place-add #name").val(),
            phone: jQuery("#chronosly_place-add #phone").val(),
            web: jQuery("#chronosly_place-add #web").val(),
            mail: jQuery("#chronosly_place-add #mail").val(),
            dir: jQuery("#chronosly_place-add #dir").val(),
            city: jQuery("#chronosly_place-add #city").val(),
            state: jQuery("#chronosly_place-add #state").val(),
            country: jQuery("#chronosly_place-add #country").val(),
            pc: jQuery("#chronosly_place-add #pc").val(),

        };
        //generamos el html del template
        $.post( ajaxurl, args, function( data ) {
            //  console.log(data.template);

            $( ".place-box" ).append( data );
            $("#chronosly_place-add-toggle").click();
        });

    });
    if($("#place-gmap").length) {
        set_place_map("place-gmap", 1);
        $("#chronosly_chronosly_places_vars_section input").change(function(){
            set_place_map("place-gmap", 0);
        });
    }

	var dbclick = 0;

	var estilo ="";
	//$(".ev-box.ch-hidden").slideToggle("fast");
	//custom template
	//create_sortables();
	on_click_event();

    definir_guardamos();//definimos la var guardamos

	$("textarea.extra-custom-css").change(function(){
		$("style#style").html($(this).val().replace(/;/g, "!important;").replace(/[event\-class]/g, ".ev-"+jQuery('#post_ID').val()));
	});


	var ide = "dad1";
	
	$("#add_ticket").click(function(){
		$("#chronosly_tickets_list").append($("#chronosly_tickets_form").html());
		ticket_actions();
	});
    if($("#chronosly_tickets_list li").length < 2) $("#add_ticket").click();//init first ticket


    ticket_actions();


	$("#ev-from").datepicker({ dateFormat: 'yy-mm-dd' });//todo: formato desde la config
	$("#ev-to").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#rrule_until").datepicker({ dateFormat: 'yy-mm-dd' });
    $("#ev-from").change(function(){
        if(!$("#ev-to").val()) $("#ev-to").val($("#ev-from").val())
    });

	//$(".field-hide").hide();
	$("#repeat").change(function(){
		var val = $(this).find("option:selected").val();
		if(val == "") {
			$(".field-hide.field1").hide();
			$(".end-repeat-section").hide();
		}
		else {
			$(".end-repeat-section").show();
			$(".field-hide.field1").show();
			if(val == "day") $("#ev-repat-name").html(translated1.days);
			else if(val == "week") $("#ev-repat-name").html(translated1.weeks);
			else if(val == "month") $("#ev-repat-name").html(translated1.months);
			else if(val == "year") $("#ev-repat-name").html(translated1.years);
		}
	});


	$("#repeat_end_type").change(function(){
		var val = $(this).find("option:selected").val();
		if(val != "never") {
			if(val == "until") {
				$(".repeat_type_until").show();
				$(".repeat_type_count").hide();
			}
			else if(val == "count") {
				$(".repeat_type_until").hide();
				$(".repeat_type_count").show();
			}
		} else {
			$(".repeat_type_until").hide();
			$(".repeat_type_count").hide();
		}
	});



    //cancelar el envio del formpara debugar
 /*  $("form#post").submit(function(event){
        event.preventDefault();
    });*/

	//save dad template dependiendo de el tipo
	$(" .post-type-chronosly #post").on('submit', function(e){
		tickets_save();
		//var templates = ["#tdad1_box","#tdad2_box","#tdad3_box","#tdad4_box","#tdad5_box","#tdad6_box"];
        save_template("");
        if($("#ch-seasons").length) {
            $("#ch-seasons").val(JSON.stringify(seasons));
            recalc_events(1,1);
            $("#ch-events").val(JSON.stringify(eventos));

        }

        return true;
	});

	$(".post-type-chronosly_organizer #post").on('submit', function(e){
		//var templates = ["#tdad7_box","#tdad8_box"];
		//template_encode_all(templates);
        save_template("");
        return true;


    });
	$(".post-type-chronosly_places #post").on('submit', function(e){
		//var templates = ["#tdad9_box","#tdad10_box"];
		//template_encode_all(templates);
        save_template("");
        return true;

    });
    $(".taxonomy-chronosly_category #edittag").on('submit', function(e){
		//var templates = ["#tdad9_box","#tdad10_box"];
		//template_encode_all(templates);
        save_template("");
        return true;

    });


    /*
   - EL edit de user normal noo guarda datos en la bd, por lo tanto siempre debemos guardar en la bd el id del template cargado.
    - Si somos pro y picamos en el styler, definimos edited = true y asi guardamos codificado en la bd.
     - Siempre que carguemos una vista debemos mirar si es edited o template id, y asi cargar una cosa u otra

     */

	//select view
	$('.dad_select').change(function(e){
        //Todo popup preguntando si quiere guardar el template de la vista anteiror
        var ide = jQuery('#post_ID').val();
        var vista = $(this).val();
        /*if($("body[class*='page_chronosly_edit_templates']").length){

            if(vista == "dad1" || vista == "dad2"|| vista == "dad3"|| vista == "dad4"|| vista == "dad5"|| vista == "dad6"){
                $("ul.ui-tabs-nav li").hide();
               $("ul.ui-tabs-nav li").eq(0).show();
               $("ul.ui-tabs-nav li").eq(1).show();
               $("ul.ui-tabs-nav li").eq(2).show();
               $("ul.ui-tabs-nav li").eq(3).show();
               $("ul.ui-tabs-nav li").eq(4).show();
               $("ul.ui-tabs-nav li").eq(5).show();
               $("ul.ui-tabs-nav li").eq(6).show();
               $("ul.ui-tabs-nav li").eq(7).show();
            }
            else if(vista == "dad7" || vista == "dad8"){
                $("ul.ui-tabs-nav li").hide();
                $("ul.ui-tabs-nav li").eq(1).show();
                $("ul.ui-tabs-nav li").eq(2).show();
                $("ul.ui-tabs-nav li").eq(7).show();
            }
            else if(vista == "dad9" || vista == "dad10"){
                $("ul.ui-tabs-nav li").hide();
                $("ul.ui-tabs-nav li").eq(1).show();
                $("ul.ui-tabs-nav li").eq(2).show();
                $("ul.ui-tabs-nav li").eq(6).show();
            } else if(vista == "dad11" || vista == "dad12"){
                $("ul.ui-tabs-nav li").hide();
                $("ul.ui-tabs-nav li").eq(1).show();
                $("ul.ui-tabs-nav li").eq(2).show();
                $("ul.ui-tabs-nav li").eq(8).show();

            }//falta el calendar


        }*/
        if(vista == "dad11" ||  vista == "dad12") ide = jQuery('input[name="tag_ID"]').val();
        var profile = 1;
        if($(".ev-styling").length) profile = 2;
        var st = "back-js";
        if(jQuery(".wrap.addon").length) st = "back-addon-js|"+jQuery(".wrap.addon").attr("id");
        //var templ = jQuery(" .tdad_select :selected").val();
        jQuery("#spin").show("fast");

        var args = {
            action : "chronosly_render_template",
            id: ide,
            view: vista,
            perfil: profile,
//            template: templ,
            style: st

        };
        //generamos el html del template
		$.post( ajaxurl, args, function( data ) {
          //  console.log(data.template);
          $(".main_box").attr("id", vista);//cambiamos el id del template
            $( "#tdad_box" ).html( data.template );
            $(".extra-custom-css").val(data.css);
            var temp = $(".tdad_select option:selected").val()
            $(".tdad_select option").remove();
            $(".tdad_select").html(data.select);
            if($("body[class*='page_chronosly_edit_templates']").length)  $(".tdad_select option[value='"+temp+"']").attr('selected', 'selected');
            init_templates_metas();
            on_click_event();
            guardamos = data.template_name; //definimos que template tenemos para guardar
            tempbase = ""; //definimos que template tenemos para guardar
            jQuery("#spin").hide("fast");
            featured = 0;
            feat_prev = 0
            if(jQuery("#chronosly_chronosly_vars_section input[name='featured']:checked").length ||
               jQuery("#chronosly_organizer_chronosly_organizer_vars_section input[name='featured']:checked").length ||
               jQuery("#chronosly_place_chronosly_places_vars_section input[name='featured']:checked").length)  jQuery(".show_featured").click();


            //init_box_controls();
		}, "json");


	});

	//select templates
	$('.tdad_select').live('change',function(e){
		load_template();
	});



	init_templates_metas();
	//init_box_controls();

   
	//add organizer
    $("#chronosly_organizer-add").hide();
    $("#chronosly_organizer-add div.more").hide();

    $("#chronosly_organizer-add-toggle").click(function(){

        $("#chronosly_organizer-add").slideToggle("slow");
    });
    $("#chronosly_organizer-add-more").click(function(){
        $(this).slideToggle("slow");
        $("#chronosly_organizer-add div.more").slideToggle("slow");
    })
    //todo guardar organizer comprobando que title no se repite
    // add place
    $("#chronosly_place-add").hide();
    $("#chronosly_place-add div.more").hide();

    $("#chronosly_place-add-toggle").click(function(){

        $("#chronosly_place-add").slideToggle("slow");
    })
    $("#chronosly_place-add-more").click(function(){
        $(this).slideToggle("slow");
        $("#chronosly_place-add div.more").slideToggle("slow");
    })
    //todo guardar place comprobando que title no se repite
    //todo arreglar css de los inputs


    //chronosly category color
    if(jQuery("input.cat-color").length) color_picker(jQuery("input.cat-color"));

   /* if($("body[class*='page_chronosly_edit_templates']").length){
        $("ul.ui-tabs-nav li").eq(8).hide();
    }*/
    if(jQuery("#chronosly_chronosly_vars_section input[name='featured']:checked").length ||
        jQuery("#chronosly_organizer_chronosly_organizer_vars_section input[name='featured']:checked").length ||
        jQuery("#chronosly_place_chronosly_places_vars_section input[name='featured']:checked").length)  jQuery(".show_featured").click();

    $("input[name='featured']").change(function(){ jQuery(".show_featured").click();});

});







function ev_slide(featured){
    if(featured){
        if(!feat_prev){
            //primer click a featured
            jQuery(".ev-box:not(.ch-featured)").hide("slow");
            jQuery(".ev-box.ch-featured").show("slow");
            jQuery(".ev-box.ch-hidden.ch-featured").slideToggle("fast");
            jQuery("span.show_hidden").removeClass("clicked");
        }
        else {
            jQuery(".ev-box.ch-hidden.ch-featured").slideToggle("slow");
            jQuery(".ev-box.normal.ch-featured").slideToggle("slow");
            jQuery(".main_box .chronosly").toggleClass("slided", 1000);

        }
        feat_prev = 1;
    } else {
        if(feat_prev){

            //primer click a featured
            jQuery(".ev-box.ch-featured").hide("slow");
            jQuery(".ev-box:not(.ch-featured)").show("slow");
            jQuery(".ev-box.ch-hidden:not(.ch-featured)").slideToggle("fast");
            jQuery("span.show_hidden").removeClass("clicked");



        }
        else {
            jQuery(".ev-box.ch-hidden:not(.ch-featured)").slideToggle("slow");
            jQuery(".ev-box.normal:not(.ch-featured)").slideToggle("slow");
            jQuery(".main_box .chronosly").toggleClass("slided", 1000);
        }
        feat_prev = 0;

    }

}


//crea el resize y los onclick de los elementos
function add_buttons_metabox(){
    jQuery(".ev-box .sortable li.draggable").each(function(){
        jQuery(this).next().addClass("ev-new-post");
        create_element(jQuery(this));
        on_click_event();

        jQuery(".ev-item.new").resizable({
            handles: 'e, w',
            start: function(event, ui) {
                ui.element.css({
                    position: "relative !important",
                    top: "",
                    left: ""
                });
            },
            resize: function( e, ui ) {
                ui.element.css({
                    position : "relative !important",
                    top: "",
                    left: ""
                });
            },
            stop: function(e, ui) {
                var parent = ui.element.parent();
                ui.element.css({
                    width: ui.element.outerWidth()/parent.width()*100+"%",
                    //height: ui.element.height()/parent.height()*100+"%",
                    position: "",
                    top: "",
                    left: ""
                });
                ui.element.click();//para hacer un ev-selected y cargar las vars


            }
        });
        jQuery( ".ev-item.new.cont_box").each(function(){
            jQuery(this).append("<div class='sortable'></div>");
            jQuery( this).find(".sortable").sortable({
                connectWith:".sortable",
                revert: true, cursor: 'move', tolerance: "intersect",
                cursorAt: { top:20, left: 50 }
            });
        });
        jQuery(".ev-new-post").before(jQuery(".ev-item.new"));
        jQuery(".ev-item.new").removeClass("new").click();
        setTimeout(function(){jQuery(".ev-new-post").removeClass("ev-new-post")},2000);
        //jQuery( ".ev-item.cont_box .ev-item.cont_box").remove();
    });



}

//añade las capacidades de ordenar, redimensionar y llama a las funciones para poner los botones en el mini paint
function init_templates_metas(){
    // jQuery( ".ev-box .sortable" ).sortable({
    //     connectWith:".sortable",
    //     revert: true, cursor: 'move', tolerance: "intersect",
    //     cursorAt: { top:20, left: 50 }
    // });

    jQuery(".ev-box.ch-featured").hide("fast");


    // /* jQuery( ".ev-box" ).sortable({
    //      connectWith:".ev-box",
    //      revert: true
    //  });*/
    // jQuery( ".ev-box ").resizable({
    //     handles: 'e, w',
    //     start: function(event, ui) {
    //         ui.element.css({
    //             position: "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     resize: function( e, ui ) {
    //         ui.element.css({
    //             position : "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     stop: function(e, ui) {
    //         var parent = ui.element.parent();
    //         ui.element.css({
    //             width: ui.element.outerWidth()/parent.width()*100+"%",
    //             //height: ui.element.height()/parent.height()*100+"%",
    //             position: "",
    //             top: "",
    //             left: ""
    //         });
    //         ui.element.click();//para hacer un ev-selected y cargar las vars

    //     }

    // });
    // jQuery( ".ev-box .ev-item").resizable({
    //     handles: 'e, w',

    //     start: function(event, ui) {
    //         ui.element.css({
    //             position: "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     resize: function( e, ui ) {
    //         ui.element.css({
    //             position : "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     stop: function(e, ui) {
    //         var parent = ui.element.parent();
    //         ui.element.css({
    //             width: ui.element.outerWidth()/parent.width()*100+"%",
    //             //height: ui.element.height()/parent.height()*100+"%",
    //             position: "",
    //             top: "",
    //             left: ""
    //         });
    //         ui.element.click();//para hacer un ev-selected y cargar las vars


    //     }
    // });
    // jQuery( ".ev-box div.cont_box").resizable({
    //     handles: 'e, w',

    //     start: function(event, ui) {
    //         ui.element.css({
    //             position: "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     resize: function( e, ui ) {
    //         ui.element.css({
    //             position : "relative !important",
    //             top: "",
    //             left: ""
    //         });
    //     },
    //     stop: function(e, ui) {
    //         var parent = ui.element.parent();
    //         ui.element.css({
    //             width: ui.element.outerWidth()/parent.width()*100+"%",
    //             //height: ui.element.height()/parent.height()*100+"%",
    //             position: "",
    //             top: "",
    //             left: ""
    //         });
    //         ui.element.click();//para hacer un ev-selected y cargar las vars


    //     }
    // });
    // //add_buttons_metabox();


    // jQuery( ".dad_controls .draggable" ).draggable({
    //     connectToSortable: ".sortable",
    //     helper: "clone",
    //     revert: "invalid",

    //     stop: function( event, ui ) {

    //         //console.log(jQuery(this));
    //         add_buttons_metabox();
    //         resize_boxes();


    //     }


    // });
    // jQuery( ".dad_controls .draggable.info" ).tooltip({
    //     show: {
    //         delay: 2000
    //     },
    //     content: function () {
    //         return jQuery(this).prop('title');
    //     },
    //     position: {
    //         my: "center bottom-20",
    //         at: "center top",
    //         using: function( position, feedback ) {
    //             jQuery( this ).css( position );
    //             jQuery( "<div>" )
    //                 .addClass( "arrow" )
    //                 .addClass( feedback.vertical )
    //                 .addClass( feedback.horizontal )
    //                 .appendTo( this );
    //         }
    //     }
    // });
    // jQuery( ".ev-box  div" ).disableSelection();

    // jQuery( ".dad_controls #tabs" ).tabs();

    jQuery(".field-hide").hide();
    jQuery(".ev-box.ch-hidden").hide("fast");//escondemos los hidden
    jQuery(".show_hidden").click(function(){ev_slide(featured);jQuery("span.show_hidden").toggleClass("clicked");});
    jQuery(".show_featured").click(function(){
        if(!featured) featured=1;
        else featured = 0;
        jQuery("span.show_featured").toggleClass("clicked");
        if(jQuery("#tdad_box .ev-box.ch-featured").length)ev_slide(featured);
    });
    jQuery(".clear_content").click(function(){jQuery(".chronosly").html("")});
    jQuery(".clear_lorem").click(function(){
        jQuery("span.clear_lorem").toggleClass("clicked");
        jQuery(".ev-item").each(function(){
            if(!jQuery(this).hasClass("cont_box") && jQuery(this).find(".lorem").length) {
                if(!lorem) jQuery(this).hide();
                else jQuery(this).show();

            }
        })
        if(!lorem) lorem = 1;
        else lorem = 0;
    });


}

//añade las capacidades de ordenar, redimensionar y llama a las funciones para poner los botones en el mini paint pero solo para un elemento en concreto
function init_templates_metas_individual(){
   //  jQuery( ".ev-box.dupl").removeClass("ui-resizable");
   //  jQuery( ".ev-box.dupl").removeClass("ui-sortable");
   //  jQuery(".ev-box.dupl .ui-resizable-handle").remove();
   //  jQuery( ".ev-box.dupl .ui-resizable").removeClass("ui-resizable");//eliminamos el resizable antiguo
   //  jQuery( ".ev-box.dupl .ui-sortable").removeClass("ui-sortable");//eliminamos el resizable antiguo

   //  jQuery( ".ev-box.dupl .sortable" ).sortable({
   //      connectWith:".sortable",
   //      revert: true, cursor: 'move', tolerance: "intersect",
   //      cursorAt: { top:20, left: 50 }
   //  });

   // /* jQuery( ".ev-box.dupl " ).sortable({
   //      connectWith:".ev-box",
   //      revert: true
   //  });*/
   //  jQuery( ".ev-box.dupl ").resizable({
   //      handles: 'e, w',
   //      start: function(event, ui) {
   //          ui.element.css({
   //              position: "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      resize: function( e, ui ) {
   //          ui.element.css({
   //              position : "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      stop: function(e, ui) {
   //          var parent = ui.element.parent();
   //          ui.element.css({
   //              width: ui.element.outerWidth()/parent.width()*100+"%",
   //              //height: ui.element.height()/parent.height()*100+"%",
   //              position: "",
   //              top: "",
   //              left: ""
   //          });
   //          ui.element.click();//para hacer un ev-selected y cargar las vars


   //      }

   //  });
   // jQuery( ".ev-box.dupl  .ev-item").resizable({
   //      handles: 'e, w',

   //      start: function(event, ui) {
   //          ui.element.css({
   //              position: "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      resize: function( e, ui ) {
   //          ui.element.css({
   //              position : "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      stop: function(e, ui) {
   //          var parent = ui.element.parent();
   //          ui.element.css({
   //              width: ui.element.outerWidth()/parent.width()*100+"%",
   //              //height: ui.element.height()/parent.height()*100+"%",
   //              position: "",
   //              top: "",
   //              left: ""
   //          });

   //          ui.element.click();//para hacer un ev-selected y cargar las vars

   //      }
   //  });
   //  jQuery( ".ev-box.dupl  div.cont_box").resizable({
   //      handles: 'e, w',

   //      start: function(event, ui) {
   //          ui.element.css({
   //              position: "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      resize: function( e, ui ) {
   //          ui.element.css({
   //              position : "relative !important",
   //              top: "",
   //              left: ""
   //          });
   //      },
   //      stop: function(e, ui) {
   //          var parent = ui.element.parent();
   //          ui.element.css({
   //              width: ui.element.outerWidth()/parent.width()*100+"%",
   //              //height: ui.element.height()/parent.height()*100+"%",
   //              position: "",
   //              top: "",
   //              left: ""
   //          });
   //          ui.element.click();//para hacer un ev-selected y cargar las vars


   //      }
   //  });
   //  //add_buttons_metabox();


   // jQuery(".ev-box.dupl ").removeClass("dupl");


}

	function resize_boxes(){
		jQuery(".ev-box .sortable").each(function(){
			jQuery(this).find("div.ch-clear").remove();
			jQuery(this).append("<div class='ch-clear'></div>");
		});
	}


//añade los controles del mini paint
	function init_box_controls(){
		var box_w = jQuery(".ev-box").width();
		jQuery(".box .controls1 a.show").click(function(){
			jQuery(this).parent().find("span").slideToggle("slow");
		});
		//controls buttons
		jQuery(".controls .edit").click(function(){jQuery(this).parent().find("div.style").slideToggle("slow")});
		jQuery(".controls .default").click(function(){jQuery(this).parent().parent().css("height", "auto").css("width", "")});
		jQuery(".controls .adjust").click(function(){jQuery(this).parent().parent().css("height", "auto").css("width", "auto").addClass("adjust")});
		jQuery(".controls .fit").click(function(){
			if(!jQuery(this).parent().parent().prev()) alert("first");
			var w = jQuery(this).parent().parent().outerWidth();
			var w2 = jQuery(this).parent().parent().prev().outerWidth();
			var w3 = jQuery(this).parent().parent().prev().prev().outerWidth();
			var w4 = jQuery(this).parent().parent().prev().prev().prev().outerWidth();
			var w5 = jQuery(this).parent().parent().prev().prev().prev().prev().outerWidth();
			if(w+w2 > box_w)  w = (1055);
			else if(w+w2+w3 > box_w) w = (box_w-w2)
			else if(w+w2+w3+w4 > box_w)   w = (box_w-w2-w3);
			else if(w+w2+w3+w4+w5 > box_w)   w = (box_w-w2-w3-w4);
			else  w = (box_w-w2-w3-w4-w5);
			w = w-(0.02*box_w)-4;//sin padding ni border
			w = (w/box_w)*100;
			if(w > 95) w = 100;
			jQuery(this).parent().parent().width(w+"%");
		});
		jQuery(".controls .b-up").click(function(){
            var find = ".ch-featured";
            if(!featured) find = ":not(.ch-featured)";
			if(jQuery(this).parent().parent().index(find) > 0){
				jQuery(this).parent().parent().insertBefore(jQuery(this).parent().parent().prev(find));
			}
		});
		jQuery(".controls .b-down").click(function(){
            var find = ".ch-featured";
            if(!featured) find = ":not(.ch-featured)";
			if(!jQuery(this).is(':last-child')){
				jQuery(this).parent().parent().insertAfter(jQuery(this).parent().parent().next(find));
			}
		});
		jQuery(".controls .delete").click(function(){


				jQuery(this).parent().parent().hide('slow', function(){ jQuery(this).remove(); });

		});
	}

    //añadimos una box
    function add_box(id){
		ide = id;
		id = "#"+id;
		jQuery("div.ev-selected").toggleClass("ev-selected");
		if(featured) {
            if(jQuery("#tdad_box .chronosly .ev-box.ch-featured:last").length) {
                jQuery("<div class='ev-box new featured'>"+jQuery("#box-hide").html()+"</div>").insertAfter(jQuery("#tdad_box .chronosly .ev-box.ch-featured:last"));
            } else jQuery("#tdad_box .chronosly").append("<div class='ev-box new ch-featured'>"+jQuery("#box-hide").html()+"</div>");
            jQuery(".ev-box.new.ch-featured .ev-hidden input[name='featured']").attr("checked", "checked");
        }
        else {
            if(jQuery("#tdad_box .chronosly .ev-box:last").length) {
                jQuery("<div class='ev-box new'>"+jQuery("#box-hide").html()+"</div>").insertAfter(jQuery("#tdad_box .chronosly .ev-box:not(.ch-featured):last"));
            }
            else jQuery("#tdad_box .chronosly").append("<div class='ev-box new'>"+jQuery("#box-hide").html()+"</div>");
        }
		jQuery("#tdad_box .ev-box.new").resizable({
			handles: 'e, w',
			start: function(event, ui) {
				ui.element.css({
					position: "relative !important",
					top: "",
					left: ""
				});
			},
			resize: function( e, ui ) {
				ui.element.css({

					 position : "relative !important",
					 top: "",
					left: ""
				});
			},
			stop: function(e, ui) {
				var parent = ui.element.parent();
				ui.element.css({
					width: ui.element.outerWidth()/parent.width()*100+"%",
					//height: ui.element.height()/parent.height()*100+"%",
					position: "",
					top: "",
					left: ""
				});
                ui.element.click();//para hacer un ev-selected y cargar las vars


            }
		});
		jQuery("#tdad_box .ev-box.new").sortable({
		connectWith:".ev-box",
		  revert: true, cursor: 'move', tolerance: "intersect",
            cursorAt: { top:20, left: 50 }
		});
		jQuery("#tdad_box .ev-box.new").find(".sortable").sortable({
			connectWith:".sortable",
			revert: true, cursor: 'move', tolerance: "intersect",
            cursorAt: { top:20, left: 50 }
		  });
		//resize_boxes();
		//init_box_controls();
		//create_sortables();
		on_click_event();
        jQuery("#tdad_box .ev-box.new").toggleClass("ev-selected").removeClass("new").click();

	 }

//define la variable guardamos si es template o bd
function definir_guardamos(){
    var args = {
        action: "chronosly_get_tipo_template",
        id: jQuery("input#post_ID").val(),
        view: jQuery(".main_box").attr("id")
    }
    jQuery.post(ajaxurl, args, function(data){
        if(data){
            guardamos = data.replace("\n", "");
            tempbase = ""
        }
    });
}

	//click save button when checks something
    function save_event(){
		jQuery("#publish").click();
	}

    function delete_template(){
        var ov = jQuery("#dad_save .update_template option:selected").val();
        if(jQuery("body[class*='page_chronosly_edit_templates']").length) ov =  jQuery(".tdad_select option:selected").val();
        var args = {
            action: "chronosly_delete_template",
            name: ov

        };
        jQuery("#spin").show("fast");
        jQuery.post(ajaxurl,args,function(data){
            jQuery(".save_info").html(data.message);
            var delay = 8000;
            if(data.error) jQuery(".save_info").addClass("red");
            else  {
                jQuery(".save_info").removeClass("red");
                delay = 3000;
            }
            jQuery(".save_info").show("slow");
            jQuery("#spin").hide("fast");

            setTimeout(function (){
                jQuery(".save_info").hide("slow");
                if(!data.error)document.location.href =document.location.href;
            }, delay);

        },'json');
    }
if(document.location.href.indexOf("#ch-loaded") < 1){
    setTimeout(function(){
        document.location.href =document.location.href+"#ch-loaded";
    }, 5000);
}

    function save_template(type){
	var ide = jQuery(".main_box").attr("id");

	var html= template_encode("");
    jQuery("#spin").show("fast");
    if(type == "template_file"){
        var name = jQuery("#dad_save .save_template").val();

        var ov = jQuery("#dad_save .update_template option:selected").val();
        if(jQuery("body[class*='page_chronosly_edit_templates']").length) ov =  jQuery(".tdad_select option:selected").val();

        if(ov != ""){
               //update template file
            var args = {
                action: "chronosly_save_template",
                task: 'update',
                id: jQuery(".main_box").attr("id"),
                name: ov,
                html: html

            };
            if(jQuery(".wrap.addon").length) args.addon = jQuery(".wrap.addon").attr("id");
            jQuery.post(ajaxurl,args,function(data){
                jQuery(".save_info").html(data.message);
                var delay = 8000;
                if(data.error) jQuery(".save_info").addClass("red");
                else  {
                    jQuery(".save_info").removeClass("red");
                    delay = 4000;
                }
                jQuery(".save_info").show("slow");
                jQuery("#spin").hide("fast");

                setTimeout(function (){
                    jQuery(".save_info").hide("slow");

                }, delay);

            },'json');
        } else {
            jQuery(".save_info").html("");
            //save template file
            //todo arreglar el tema del naming
            if(name == ""){
                jQuery(".save_info").html(translated1.specify_name);
            }
            else if(/^[a-zA-Z0-9-_ ]*$/.test(name) == false){
                jQuery(".save_info").html(translated1.wrong_name);

            } else {
                var args = {
                    action: "chronosly_save_template",
                    task: 'save',
                    id: ide,
                    name: name.replace(" ", "_"),
                    html: html

                };
                jQuery.post(ajaxurl,args,function(data){
                    jQuery(".save_info").html(data.message);
                    if(data.error) jQuery(".save_info").addClass("red");
                    else jQuery(".save_info").removeClass("red");
                },'json');

            }
            setTimeout(function (){
                jQuery(".save_info").show("slow");
                jQuery("#spin").hide("fast");

            }, 1500);
            setTimeout(function (){
                jQuery(".save_info").hide("slow");

            }, 6000);
        }
    }
    else if(type == "template_file_new"){
        //nuevo template
        var name = jQuery("input.save_template").val();



            //save template file
            //todo arreglar el tema del naming
        jQuery(".save_info").addClass("red");
            if(name == ""){
                jQuery(".save_info").html(translated1.specify_name);
            }
            else if(/^[a-zA-Z0-9-_ ]*$/.test(name) == false){
                jQuery(".save_info").html(translated1.wrong_name);

            } else {
                jQuery(".save_info").html("").removeClass("red");
                var args = {
                    action: "chronosly_save_template",
                    task: 'save',
                    id: "dad1",
                    name: name.replace(/ /g, "_"),
                    html: " "

                };
                jQuery.post(ajaxurl,args,function(data){
                    jQuery(".save_info").html(translated1.succes);
                    document.location.href =document.location.href;

                },'json');

            }
        setTimeout(function (){
            jQuery(".save_info").show("slow");
            jQuery("#spin").hide("fast");

        }, 1500);

        setTimeout(function (){
            jQuery(".save_info").hide("slow");

        }, 5000);

    } else if(type == "template_file_duplicate"){
        var name = jQuery("input.duplicate_template").val();
        var duplicate = jQuery("select.duplicate_from option:selected").val();



            //duplicamos template
            //todo arreglar el tema del naming
        jQuery(".save_info").addClass("red");
            if(name == ""){
                jQuery(".save_info").html(translated1.specify_name);
            }
            else if(/^[a-zA-Z0-9-_ ]*$/.test(name) == false){
                jQuery(".save_info").html(translated1.wrong_name);

            } else {
                jQuery(".save_info").html("").removeClass("red");
                var args = {
                    action: "chronosly_save_template",
                    task: 'duplicate',
                    id: "dad1",
                    name: name.replace(/ /g, "_"),
                    html: duplicate

                };
                jQuery.post(ajaxurl,args,function(data){
                    if(data != 0) {
                        jQuery(".save_info").html(data);
                    }
                    else {
                        jQuery(".save_info").html(translated1.succes);
                        document.location.href =document.location.href;
                    }
                },'json');

            }
        setTimeout(function (){
            jQuery(".save_info").show("slow");
            jQuery("#spin").hide("fast");

        }, 1500);

        setTimeout(function (){
            jQuery(".save_info").hide("slow");

        }, 5000);

    }
    else {
        //save in bd
        var tid = jQuery('#post_ID').val();
        if(ide == "dad11" ||  ide == "dad12") tid = jQuery('input[name="tag_ID"]').val();
        var args = {
            action: "chronosly_save_template",
            task: 'save-bd',
            template: ide,
            id: tid,
            html: html

        };
        jQuery.post(ajaxurl,args,function(data){
        },'json');
        jQuery(".dad_box").remove();//to not exceed the input vars sended
        jQuery(".dad_controls").remove();//to not exceed the input vars sended
    }
}

function ticket_actions(){
    jQuery("#chronosly_tickets_list span.delete").click(function(){
        jQuery(this).parent().parent().remove();
    });
    jQuery("#chronosly_tickets_list span.edit").click(function(){
        jQuery(this).parent().parent().find("input").attr('readonly', false);
        jQuery(this).parent().parent().find("textarea").attr('readonly', false);
    });
    jQuery("#chronosly_tickets_list li input.start-time").datepicker({ dateFormat: 'yy-mm-dd' });
    jQuery("#chronosly_tickets_list li input.end-time").datepicker({ dateFormat: 'yy-mm-dd' });
}

function tickets_save(){
	var save = {tickets:[]};
	var count = 0;

    jQuery("#chronosly_tickets_list li input.title").each(function(){

        if(!jQuery(this).val()) {
            jQuery(this).parent().find(".delete").click();
        }
    });

    jQuery("#chronosly_tickets_list li").each(function(i){

		if(jQuery(this).attr("class") != "ticket-head") {
			count++;
			save.tickets[i] = [];
			var x = 0;
			jQuery(this).find("input").each(function(j){
				 var v = jQuery(this).attr('name');
				 var res = jQuery(this).attr('value');
                 if((v == "soldout" || v == "sale") && !jQuery(this).is(":checked")) res = 0;
				 save.tickets[i][j] = {name: v, value : res};
				 x = j;
			});
			x++;
			jQuery(this).find("textarea").each(function(j){
				 var v = jQuery(this).attr('name');
				 var res = jQuery(this).val();
				 save.tickets[i][x+j] = {name: v, value : res};

			});

        }
	});
	if(count) jQuery("input#tickets").val(JSON.stringify(save));
    return save;
}



function load_template(){
    var ide = jQuery('#post_ID').val();
    var vista = jQuery(".main_box").attr("id");
    if(vista == "dad11" ||  vista == "dad12") ide = jQuery('input[name="tag_ID"]').val();
    var templ = jQuery(" .tdad_select :selected").val();
    var profile = 1;
    if(jQuery(".ev-styling").length) profile = 2;
    var st = "back-js";
    if(jQuery(".wrap.addon").length) st = "back-addon-js|"+jQuery(".wrap.addon").attr("id");
    jQuery("#spin").show("fast");
    var args = {
        action : "chronosly_render_template",
        id: ide,
        view: vista,
        template: templ,
        perfil: profile,
        style: st

    };
    jQuery.post( ajaxurl, args, function( data ) {

        jQuery( "#tdad_box" ).html( data.template );
        jQuery(".extra-custom-css").val(data.css);
        //$(".tdad_select option").remove().html(data.select);
        init_templates_metas();
        on_click_event();
        guardamos = templ; //definimos que template tenemos para guardar
        tempbase = data.template_base; //definimos la base
        jQuery("#spin").hide("fast");
        featured = 0;
        feat_prev = 0
        if(jQuery("#chronosly_chronosly_vars_section input[name='featured']:checked").length ||
           jQuery("#chronosly_organizer_chronosly_organizer_vars_section input[name='featured']:checked").length ||
           jQuery("#chronosly_place_chronosly_places_vars_section input[name='featured']:checked").length)  jQuery(".show_featured").click();
        //init_box_controls();
    }, "json");

}

//encode html to json
function template_encode(ide){
    if(!tempbase || typeof (tempbase)=="undefined") tempbase = jQuery(".dad_box .chronosly").attr("base");
    if(guardamos != "chbd" && !jQuery("body[class*='page_chronosly_edit_templates']").length) return "template-"+guardamos;
    //demomento seleccionamos el id, no guradamos todos.
    ide = ".dad_box";
    var debug = 0;
    if(jQuery("body[class*='page_chronosly_edit_templates']").length) var template = {boxes:[]};
    else var template = {boxes:[] , base:tempbase};
    //recorremos las boxes
    jQuery(ide+" div.ev-box").each(function(i){
        var b_attr = [];
        var b_type =  jQuery(this).children(".ev-hidden").find(".vars select[name='type'] option:selected").val();
        var b_class =  jQuery(this).children(".ev-hidden").find(".vars input[name='class']").val();
        var b_feat =  jQuery(this).children(".ev-hidden").find(".vars input[name='featured']").is(":checked");

        jQuery(this).children(".ev-hidden").find("div:not(.vars)").children().each(function(k){
            var res ="";

            if(jQuery(this).attr("type") == "checkbox") res = jQuery(this).is(":checked");
            else if(jQuery(this).prop("tagName") == "SELECT") res = jQuery(this).find("option:selected").val();
            else if(jQuery(this).prop("tagName") == "INPUT" || jQuery(this).prop("tagName") == "TEXTAREA") res = jQuery(this).val();
            else return;
            var v = jQuery(this).attr('name');
            //var t = jQuery(this).attr('etype');
            //var tipo = jQuery(this).attr('type');
            var l = jQuery(this).prev("label").html();
            if(debug) console.log(v+" "+res);

            if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
            // elemento = { name : v, value: res, type: t, label: l, vars:[{ name : v, value: res, label: l, type: tipo}]};
            k = b_attr.length;
            b_attr[k] = { name : v, value: res, label: l};
        });
        //miramos los atributos del paint de la box
        /*  jQuery(this).children(".ev-hidden").find("option:selected").each(function(x){
         var v = jQuery(this).parent().attr('name');
         var res = jQuery(this).val();
         var ord = jQuery(this).parent().attr('order');
         var l = jQuery(this).parent().prev().html();
         if(debug) console.log(v+" "+res);
         b_attr[ord] = {name: v, value: res, label: l};
         if(v == "type") b_type = res;
         });
         jQuery(this).children(".ev-hidden").find("input").each(function(x){
         if(jQuery(this).attr('type') == "checkbox"){
         if(jQuery(this).is(":checked")) var res = 1;
         else var res = 0
         var v = jQuery(this).attr('name');
         var ord = jQuery(this).attr('order');
         var l = jQuery(this).prev().html();
         if(debug) console.log(v+" "+res);

         b_attr[ord] = {name: v, value: res, label: l};


         }
         else {
         var v = jQuery(this).attr('name');
         var res = jQuery(this).attr('value');
         var t = jQuery(this).attr('etype');
         if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
         var ord = jQuery(this).attr('order');
         var l = jQuery(this).prev().html();
         if(debug) console.log(v+" "+res);

         b_attr[ord] = {name: v, value: res, label: l};

         }
         });
         jQuery(this).children(".ev-hidden").find("textarea").each(function(x){
         var v = jQuery(this).attr('name');
         var res = jQuery(this).val();
         if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
         var ord = jQuery(this).attr('order');
         var l = jQuery(this).prev().html();
         if(debug) console.log(v+" "+res);

         b_attr[ord] = {name: v, value: res, label: l};
         }); */

        template.boxes[i] = {type: b_type, clase: b_class,featured: b_feat, style:jQuery(this).attr("style"),items:[], attr: b_attr};
        b_attr = "";

        //miramos los elementos interiores, elemntos simples
        jQuery(this).children(".sortable").children(".ev-item").each(function(j){
            if(jQuery(this).hasClass("cont_box")){
                //se trata de un elemento con subelementos, generamos el item que contiene los subelementos y llamamos a la funcion recursiva
                template.boxes[i].items[j] = {"style":jQuery(this).attr("style"), attr:[], items:[]};
                recursive_cont_box(jQuery(this), template.boxes[i].items[j]);
            } else template.boxes[i].items[j] = {"style":jQuery(this).attr("style"), attr:[]};
            //entramos en el paint y cojemos los elementos del var, que determinan el elemento

            //modo simple 1 solo elemento, el mismo content que el recursive
            var elemento = "";

            var x = 0;
            jQuery(this).children(".ev-hidden").find(".vars").children().each(function(k){
                var res ="";
                if(debug) console.log(jQuery(this).prop("tagName"));
                if(jQuery(this).attr("type") == "checkbox")  res = jQuery(this).is(":checked");
                else if(jQuery(this).prop("tagName") == "SELECT") res = jQuery(this).find("option:selected").val();
                else if(jQuery(this).prop("tagName") == "INPUT" || jQuery(this).prop("tagName") == "TEXTAREA") res = jQuery(this).val();
                else return;

                if(elemento == ""){
                    var v = jQuery(this).attr('name');
                    var t = jQuery(this).attr('etype');
                    //var tipo = jQuery(this).attr('type');
                    var l = jQuery(this).prev("label").html();
                    if(debug) console.log(v+" "+res);

                    if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
                    // elemento = { name : v, value: res, type: t, label: l, vars:[{ name : v, value: res, label: l, type: tipo}]};
                    elemento = { name : v, value: res, label: l, vars:[{ name : v, value: res, label: l}]};
                }
                else {
                    x =  elemento.vars.length;

                    var v = jQuery(this).attr('name');
                    var l = jQuery(this).prev('label').html();
                    if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
                    var t = jQuery(this).attr('extra');

                    //var ord = jQuery(this).attr('order');

                    /* if( jQuery(this).parents(".var-hidden").length) var hidden = 1;
                     else var hidden = 0;
                     var tipo = jQuery(this).attr('type');*/
                    if(debug) console.log(v+" "+res);
                    // var t = jQuery(this).attr('etype');


                    // if(ord == 0) elemento = { name : v, value: res, label: l, vars:[{ name : v, value: res, label: l}]};
                    if(t) elemento.vars[x] = { name : v, value: res, label: l,extra: t};
                    else elemento.vars[x] = { name : v, value: res, label: l};
                }
            });

            //guardamos el elemento
            template.boxes[i].items[j].attr[0] = elemento;
            //seleccionamos los estilos
            var x = 0;
            jQuery(this).children(".ev-hidden").find("div:not(.vars)").children().each(function(k){
                var res ="";
                if(jQuery(this).attr("type") == "checkbox") res = jQuery(this).is(":checked");
                else if(jQuery(this).prop("tagName") == "SELECT") res = jQuery(this).find("option:selected").val();
                else if(jQuery(this).prop("tagName") == "INPUT" || jQuery(this).prop("tagName") == "TEXTAREA") res = jQuery(this).val();
                else return;

                var v = jQuery(this).attr('name');
                var t = jQuery(this).attr('etype');
                //var tipo = jQuery(this).attr('type');
                var l = jQuery(this).prev("label").html();
                if(debug) console.log(v+" "+res);

                if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
                x =  template.boxes[i].items[j].attr.length;
                // elemento = { name : v, value: res, type: t, label: l, vars:[{ name : v, value: res, label: l, type: tipo}]};
                template.boxes[i].items[j].attr[x] = { name : v, value: res, label: l};
            });
        });
    });
    //metemos el extra custom css
    jQuery(ide).parent().find("textarea.extra-custom-css").each(function(i){
        if(jQuery(this).val() || jQuery(this).val() == 0) template.style = encodeURI(jQuery(this).val().replace(/"/g, "'").replace(/\+/g, "#plus#"));
        else template.style ="";
    });
    return  JSON.stringify(template);


};



//codifica recursivamente en caso de haber cont boxes
function recursive_cont_box(el, obj){
    var debug = 0;
    //metemos las vars de la inside box
    el.children(".sortable").children(".ev-item").each(function(extra){
        if(jQuery(this).hasClass("cont_box")){
            //se trata de un elemento con subelementos, generamos el item que contiene los subelementos y llamamos a la funcion recursiva
            obj.items[extra] = {"style":jQuery(this).attr("style"), attr:[], items:[]};
            recursive_cont_box(jQuery(this), obj.items[extra]);
        }
        else obj.items[extra] = {"style":jQuery(this).attr("style"), attr:[]};
        var elemento = "";
        var x = -1;
        jQuery(this).children(".ev-hidden").find(".vars").children().each(function(k){
            var res = "";
            if(jQuery(this).attr("type") == "checkbox") res = jQuery(this).is(":checked");
            else if(jQuery(this).prop("tagName") == "SELECT") res = jQuery(this).find("option:selected").val();
            else if(jQuery(this).prop("tagName") == "INPUT" || jQuery(this).prop("tagName") == "TEXTAREA") res = jQuery(this).val();
            else return;
            if(elemento == ""){
                var v = jQuery(this).attr('name');
                var t = jQuery(this).attr('etype');
                //var tipo = jQuery(this).attr('type');
                var l = jQuery(this).prev("label").html();
                if(debug) console.log(v+" "+res);

                if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
                // elemento = { name : v, value: res, type: t, label: l, vars:[{ name : v, value: res, label: l, type: tipo}]};
                elemento = { name : v, value: res, label: l, vars:[{ name : v, value: res, label: l}]};
            }
            else {

                var v = jQuery(this).attr('name');
                var l = jQuery(this).prev('label').html();
                if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
                var t = jQuery(this).attr('extra');
                //  var ord = jQuery(this).attr('order');
                /* if( jQuery(this).parents(".var-hidden").length) var hidden = 1;
                 else var hidden = 0;
                 var tipo = jQuery(this).attr('type');*/
                if(debug) console.log(v+" "+res);
                // var t = jQuery(this).attr('etype');
                x =  elemento.vars.length;

                //porque se añaden cosas extrañas aseguramos que el order define el elemento
                //if(ord == 0) elemento =  { name : v, value: res, label: l, vars:[{ name : v, value: res, label: l}]};
                if(t) elemento.vars[x] = { name : v, value: res, label: l,extra: t};
                else elemento.vars[x] = { name : v, value: res, label: l};

            }
        });

        //guardamos el elemento
        if(debug) console.log("aqui");
        obj.items[extra].attr[0] = elemento;
        if(debug) console.log("bien");
        //seleccionamos los estilos
        var x = 0;
        jQuery(this).children(".ev-hidden").find(" div:not(.vars)").children().each(function(k){
            var res ="";
            if(jQuery(this).attr("type") == "checkbox") res = jQuery(this).is(":checked");
            else if(jQuery(this).prop("tagName") == "SELECT") res = jQuery(this).find("option:selected").val();
            else if(jQuery(this).prop("tagName") == "INPUT" || jQuery(this).prop("tagName") == "TEXTAREA") res = jQuery(this).val();
            else return;
            x = obj.items[extra].attr.length;
            var v = jQuery(this).attr('name');
            var t = jQuery(this).attr('etype');
            //var tipo = jQuery(this).attr('type');
            var l = jQuery(this).prev("label").html();
            if(debug) console.log(v+" "+res);

            if(res && typeof(res) == "string") res = encodeURI(res.replace(/"/g, "'").replace(/\+/g, "#plus#"));
            // elemento = { name : v, value: res, type: t, label: l, vars:[{ name : v, value: res, label: l, type: tipo}]};
            obj.items[extra].attr[x] = { name : v, value: res, label: l};
        });
    });

}




function get_shorcode_gallery(container) {
				var shortcode = wp.shortcode.next( 'gallery', container.val() ),
					defaultPostId = wp.media.gallery.defaults.id,
					attachments, selection;

				// Bail if we didn't match the shortcode or all of the content.
				if ( ! shortcode )
					return;

				// Ignore the rest of the match object.
				shortcode = shortcode.shortcode;

				if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
					shortcode.set( 'id', defaultPostId );

				attachments = wp.media.gallery.attachments( shortcode );
				selection = new wp.media.model.Selection( attachments.models, {
					props:    attachments.props.toJSON(),
					multiple: true
				});

				selection.gallery = attachments.gallery;

				// Fetch the query's attachments, and then break ties from the
				// query to allow for sorting.
				selection.more().done( function() {
					// Break ties with the query.
					selection.props.set({ query: false });
					selection.unmirror();
					selection.props.unset('orderby');
				});

				return selection;
			};

    /*function create_sortables(){

		jQuery( ".ev-box .cont_box").each(function(){
            var el = jQuery(this).children(".ev-hidden");
			jQuery(this).html("<div class='sortable'>"+jQuery(this).html()+"</div>").append(el);
            jQuery(this).children(".sortable").children(".ev-hidden").remove();

		})


	}*/

	//clicamos en un elemento del template y llamamos al creador del mini paint
	function on_click_event(){
        jQuery(".ev-box a").each(function(){
            //if(jQuery(this).attr("href").indexOf('ev_slide') < 0)
            if(jQuery(this).attr("href") /*&& jQuery(this).attr("href").indexOf("javascript") < 0*/ ) jQuery(this).attr("href", "javascript:void(0)");
            if(jQuery(this).attr("onclick") /*&& jQuery(this).attr("onclick").indexOf("javascript") < 0 */) jQuery(this).attr("onclick", "javascript:void(0)");
        });
		jQuery( ".ev-box").unbind("click").click(function(e){
            var elemento = jQuery(this);
		setTimeout(function (){
			if(!dbclick){
				jQuery("div.ev-selected").toggleClass("ev-selected");
					var parent = jQuery(e.target).parents(".ev-item");
					if(parent.length){
                        //element
						parent.toggleClass("ev-selected");
					}
					else {
						 parent = jQuery(e.target).parents("div.sortable");
						 //console.log(parent.children(".cont_box"));

						 if(parent.length && parent.children(".cont_box").length) {
                               //cont_box
							parent = parent.children(".cont_box");
							parent.toggleClass("ev-selected");

						}
						 else {
                             //box
							parent = elemento;
							parent.toggleClass("ev-selected");
						}
					}

					rellenar_estilo(parent);
			}
         }, 400);




		});
		jQuery( ".ev-box").unbind("dblclick").dblclick(function(e){
			dbclick=1;
			jQuery("div.ev-selected").toggleClass("ev-selected");
			var parent = jQuery(e.target).parents(".ev-item");
			if(parent.length){
				parent.toggleClass("ev-selected");
			}
			else {
				 parent = jQuery(e.target).parents("div.sortable");
				// console.log(parent.children(".cont_box"));

				 if(parent.length && parent.children(".cont_box").length) {
					parent = parent.children(".cont_box");
					parent.toggleClass("ev-selected");

				}
				 else {
					parent =jQuery(this);
					parent.toggleClass("ev-selected");
				}
			}
			rellenar_estilo(parent);
			jQuery(".ev-styling .box.vars .ev-hidden").show();
			setTimeout(function (){
				jQuery(".ev-styling .box.vars .ev-hidden .vars input:text").first().focus();

				dbclick = 0;
			}, 500);

		});







    }


//al modificar los inputs del mini paint, modificamos los inputs hidden de cada elemento y llamamos al creador de estilo de template front
	function input_onchange(el){
		//eventos de los inputs para cambiar los valores del template
		//jQuery("div.ev-styling div.box .ev-hidden .vars input").unbind("keyup");

		jQuery("div.ev-styling div.box .ev-hidden .vars input").unbind("keyup").keyup(function(){

            if( !jQuery(this).hasClass("item")){
                // store current positions in variables
                    var start = this.selectionStart,
                    end = this.selectionEnd;

                    if(jQuery(this).attr('type') == "checkbox"){
                        if(jQuery(this).is(":checked")) el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").attr("checked", "checked");
                        else  el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").removeAttr("checked");
                    }
                    else el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").attr("value",jQuery(this).val());
                   // el.children(".ev-hidden").find(".sp-container").remove();//delete colorpicker

                    set_style(el);
                    set_vars(el);
                    // restore from variables...
                    this.setSelectionRange(start, end);

                }
			});

			//jQuery("div.ev-styling div.box .ev-hidden .vars input").unbind("change");

			jQuery("div.ev-styling div.box .ev-hidden .vars input").unbind("change").change(function(){
                if( !jQuery(this).hasClass("item")){
                    if(jQuery(this).attr('type') == "checkbox"){
                        if(jQuery(this).is(":checked")) el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").attr("checked", "checked");
                        else  el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").removeAttr("checked");
                    }
                    else el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" input[name='"+jQuery(this).attr("name")+"']").attr("value",jQuery(this).val());
                //    el.children(".ev-hidden").find(".sp-container").remove();//delete colorpicker
                //    el.children(".ev-hidden").find(".sp-replacer").remove();//delete colorpicker

                    set_style(el);
                    set_vars(el);
                }

			});

        jQuery("div.ev-styling div.box .ev-hidden .vars textarea").unbind("change").change(function(){
            if( !jQuery(this).hasClass("item")){
                 el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" textarea[name='"+jQuery(this).attr("name")+"']").html(jQuery(this).val());

                set_style(el);
                set_vars(el);
            }

        });

        // jQuery("div.ev-styling div.box .ev-hidden .vars select").unbind("change");
        jQuery("div.ev-styling div.box .ev-hidden .vars select").unbind("change").change(function(){

            if( !jQuery(this).hasClass("selectized")){

                var sel = jQuery(this).find("option:selected");
                jQuery(this).find("option").removeAttr("selected");
                sel.attr("selected", "selected");

                if(sel.val() == 4) jQuery(this).parent().find(".var-hidden").show("slow");
                else jQuery(this).parent().find(".var-hidden").hide("slow");
                el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" select[name='"+jQuery(this).attr("name")+"']").html(jQuery(this).html());
            } else {
                var x = el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" select[name='"+jQuery(this).attr("name")+"']");
                if(!x.find("option[value='"+jQuery(this).val()+"']").length) x.append("<option value='"+jQuery(this).val()+"'>"+jQuery(this).val()+"</option>");
                x.find("option").removeAttr("selected");
                x.find("option[value='"+jQuery(this).val()+"']").attr("selected", "selected");

            }
		//jQuery("div.ev-styling div.box .ev-hidden .vars textarea").unbind("keyup");
		jQuery("div.ev-styling div.box .ev-hidden .vars textarea").keyup(function(){
			 // store current positions in variables
                if(jQuery(this).hasClass("css")) return;
				var start = this.selectionStart,
				end = this.selectionEnd;

				el.children(".ev-hidden").find("div."+jQuery(this).parents("div.box").attr("class").replace("box ", "")+" textarea[name='"+jQuery(this).attr("name")+"']").html(jQuery(this).val());


				set_style(el);
				// restore from variables...
				this.setSelectionRange(start, end);
			});

                   //  el.children(".ev-hidden").find(".selectize-control").remove();//delete selectizer

                set_style(el);


                });

	}

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
            cancelText: translated1.color_cancel,
            chooseText: translated1.color_choose,
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




function set_place_map(id, first){
    if(!first) jQuery("#latlong").val("");//limpiamos el lat long si editamos
    var adress =  jQuery("#evp_dir").val()+" ";
    adress += jQuery("#evp_city").val()+" ";
    adress += jQuery("#evp_pc").val()+" ";
    adress += jQuery("#evp_state").val()+" ";
    adress += jQuery("#evp_country").val();
    gmap_initialize2(id, adress);
}

var map1;
var geocoder1;
function gmap_initialize2(id, adress) {
    var mapOptions1 = {
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        zoomControl: true,
        mapTypeControl: false,
        streetViewControl: true,
        overviewMapControl: true,
        scrollwheel: false,

    };
    map1 = new google.maps.Map(document.getElementById(id), mapOptions1);
    geocoder1 = new google.maps.Geocoder();
    codeAddress2(adress);
}
function codeAddress2(adress) {

    var address1 = adress;
    var search = { 'address': address1};
    if(jQuery("#latlong").val()) {
        //{lat: -34.397, lng: 150.644}
        var l = jQuery("#latlong").val().replace("(", "").replace(")", "").split(", ");
        var latlng = new google.maps.LatLng(parseFloat(l[0]), parseFloat(l[1]));
        search = {"latLng": latlng};

    }
   // console.log(search);
    geocoder1.geocode( search, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            jQuery("#latlong").val(results[0].geometry.location);
            map1.setCenter(results[0].geometry.location);
            var marker1 = new google.maps.Marker({
                map: map1,
                position: results[0].geometry.location,
                draggable:true,
            });
            google.maps.event.addListener(marker1, 'dragend', function()
            {
                jQuery("#latlong").val(marker1.getPosition());
            });

        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });




}

jQuery(window).load(function(){
    var rsel = jQuery("#repeat option:selected").val();
    if(rsel!= ""){
        jQuery("#repeat").change();
        jQuery("#repeat_end_type").change();
    }
});

//sttime jquery
Date.ext={};Date.ext.util={};Date.ext.util.xPad=function(x,pad,r){if(typeof (r)=="undefined"){r=10}for(;parseInt(x,10)<r&&r>1;r/=10){x=pad.toString()+x}return x.toString()};Date.prototype.locale="en-GB";if(document.getElementsByTagName("html")&&document.getElementsByTagName("html")[0].lang){Date.prototype.locale=document.getElementsByTagName("html")[0].lang}Date.ext.locales={};Date.ext.locales.en={a:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],A:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],b:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],B:["January","February","March","April","May","June","July","August","September","October","November","December"],c:"%a %d %b %Y %T %Z",p:["AM","PM"],P:["am","pm"],x:"%d/%m/%y",X:"%T"};Date.ext.locales["en-US"]=Date.ext.locales.en;Date.ext.locales["en-US"].c="%a %d %b %Y %r %Z";Date.ext.locales["en-US"].x="%D";Date.ext.locales["en-US"].X="%r";Date.ext.locales["en-GB"]=Date.ext.locales.en;Date.ext.locales["en-AU"]=Date.ext.locales["en-GB"];Date.ext.formats={a:function(d){return Date.ext.locales[d.locale].a[d.getDay()]},A:function(d){return Date.ext.locales[d.locale].A[d.getDay()]},b:function(d){return Date.ext.locales[d.locale].b[d.getMonth()]},B:function(d){return Date.ext.locales[d.locale].B[d.getMonth()]},c:"toLocaleString",C:function(d){return Date.ext.util.xPad(parseInt(d.getFullYear()/100,10),0)},d:["getDate","0"],e:["getDate"," "],g:function(d){return Date.ext.util.xPad(parseInt(Date.ext.util.G(d)/100,10),0)},G:function(d){var y=d.getFullYear();var V=parseInt(Date.ext.formats.V(d),10);var W=parseInt(Date.ext.formats.W(d),10);if(W>V){y++}else{if(W===0&&V>=52){y--}}return y},H:["getHours","0"],I:function(d){var I=d.getHours()%12;return Date.ext.util.xPad(I===0?12:I,0)},j:function(d){var ms=d-new Date(""+d.getFullYear()+"/1/1 GMT");ms+=d.getTimezoneOffset()*60000;var doy=parseInt(ms/60000/60/24,10)+1;return Date.ext.util.xPad(doy,0,100)},m:function(d){return Date.ext.util.xPad(d.getMonth()+1,0)},M:["getMinutes","0"],p:function(d){return Date.ext.locales[d.locale].p[d.getHours()>=12?1:0]},P:function(d){return Date.ext.locales[d.locale].P[d.getHours()>=12?1:0]},S:["getSeconds","0"],u:function(d){var dow=d.getDay();return dow===0?7:dow},U:function(d){var doy=parseInt(Date.ext.formats.j(d),10);var rdow=6-d.getDay();var woy=parseInt((doy+rdow)/7,10);return Date.ext.util.xPad(woy,0)},V:function(d){var woy=parseInt(Date.ext.formats.W(d),10);var dow1_1=(new Date(""+d.getFullYear()+"/1/1")).getDay();var idow=woy+(dow1_1>4||dow1_1<=1?0:1);if(idow==53&&(new Date(""+d.getFullYear()+"/12/31")).getDay()<4){idow=1}else{if(idow===0){idow=Date.ext.formats.V(new Date(""+(d.getFullYear()-1)+"/12/31"))}}return Date.ext.util.xPad(idow,0)},w:"getDay",W:function(d){var doy=parseInt(Date.ext.formats.j(d),10);var rdow=7-Date.ext.formats.u(d);var woy=parseInt((doy+rdow)/7,10);return Date.ext.util.xPad(woy,0,10)},y:function(d){return Date.ext.util.xPad(d.getFullYear()%100,0)},Y:"getFullYear",z:function(d){var o=d.getTimezoneOffset();var H=Date.ext.util.xPad(parseInt(Math.abs(o/60),10),0);var M=Date.ext.util.xPad(o%60,0);return(o>0?"-":"+")+H+M},Z:function(d){return d.toString().replace(/^.*\(([^)]+)\)$/,"$1")},"%":function(d){return"%"}};Date.ext.aggregates={c:"locale",D:"%m/%d/%y",h:"%b",n:"\n",r:"%I:%M:%S %p",R:"%H:%M",t:"\t",T:"%H:%M:%S",x:"locale",X:"locale"};Date.ext.aggregates.z=Date.ext.formats.z(new Date());Date.ext.aggregates.Z=Date.ext.formats.Z(new Date());Date.ext.unsupported={};Date.prototype.strftime=function(fmt){if(!(this.locale in Date.ext.locales)){if(this.locale.replace(/-[a-zA-Z]+$/,"") in Date.ext.locales){this.locale=this.locale.replace(/-[a-zA-Z]+$/,"")}else{this.locale="en-GB"}}var d=this;while(fmt.match(/%[cDhnrRtTxXzZ]/)){fmt=fmt.replace(/%([cDhnrRtTxXzZ])/g,function(m0,m1){var f=Date.ext.aggregates[m1];return(f=="locale"?Date.ext.locales[d.locale][m1]:f)})}var str=fmt.replace(/%([aAbBCdegGHIjmMpPSuUVwWyY%])/g,function(m0,m1){var f=Date.ext.formats[m1];if(typeof (f)=="string"){return d[f]()}else{if(typeof (f)=="function"){return f.call(d,d)}else{if(typeof (f)=="object"&&typeof (f[0])=="string"){return Date.ext.util.xPad(d[f[0]](),f[1])}else{return m1}}}});d=null;return str};
