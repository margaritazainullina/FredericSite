var address = {};
var map = {};
var geocoder = {};
var Environment = {
    //mobile or desktop compatible event name, to be used with '.on' function
    TOUCH_DOWN_EVENT_NAME: 'mousedown touchstart',
    TOUCH_UP_EVENT_NAME: 'mouseup touchend',
    TOUCH_MOVE_EVENT_NAME: 'mousemove touchmove',
    TOUCH_DOUBLE_TAB_EVENT_NAME: 'dblclick dbltap',

    isAndroid: function() {
        return navigator.userAgent.match(/Android/i);
    },
    isBlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    isIOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    isOpera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    isWindows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    isMobile: function() {
        return (Environment.isAndroid() || Environment.isBlackBerry() || Environment.isIOS() || Environment.isOpera() || Environment.isWindows());
    }
};



function gmap_initialize(id, adress, zoomvar) {
    // console.log(id);
    var scroll = true;
    var drag = true;
    if(jQuery("#"+id).parents(".back_img").length) {
        scroll = false;
        drag = false;
    }
    var mapOptions1 = {
        zoom: zoomvar,
        draggable: drag,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        zoomControl: true,
        mapTypeControl: false,
        streetViewControl: true,
        overviewMapControl: true,
        scrollwheel: scroll,
    };
   
    
         // console.log(id);
    if(document.getElementById(id) != null){
        // console.log(document.getElementById(id));
        map[id] = new google.maps.Map(document.getElementById(id), mapOptions1);
        geocoder[id] = new google.maps.Geocoder();
        codeAddress1(adress, id);
    }
}
function codeAddress1(adress, id) {
    address[id] = adress;
    var search = { 'address': address[id]};
    if(address[id].indexOf("latlong") >= 0) {
        //{lat: -34.397, lng: 150.644}
        var l = address[id].replace("latlong", "").replace("(", "").replace(")", "").split(", ");
        var latlng = new google.maps.LatLng(parseFloat(l[0]), parseFloat(l[1]));
        address[id] = "loc:"+parseFloat(l[0])+"+"+ parseFloat(l[1]);
        search = {"latLng": latlng};

    }
    geocoder[id].geocode( search, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map[id].setCenter(results[0].geometry.location);
            var marker1 = new google.maps.Marker({
                map: map[id],
                position: results[0].geometry.location,
            });
        } else {
            //alert('Geocode was not successful for the following reason: ' + status);
            //retry
            setTimeout(function(){
                codeAddress1(adress, id);
            }, 500);
        }
    });

}


function ev_popup(post){
    url = "http://flamantia.org"; //translated1.weburl;
    url += "/index.php?js_render=1&shortcode=1&p="+post;
    // url += "/index.php?shortcode=1&p="+post;
    jQuery.colorbox({
        href: url, 
        width: "80%", 
        maxWidth: "900px", 
        onComplete: function(){
            jQuery("#cboxLoadedContent script").each(function(){
                //solo ejecuta el maps..mirar otros posibles js
                var code = jQuery(this).html().replace("jQuery(window).load(function(){", "").replace(";});", ";");
                eval(code);
                 if(jQuery(".chronosly.ch-dad10 .ev-item.events_list").length ){
                        jQuery(".chronosly.ch-dad10 .ev-item.events_list .ev-data.events_list").mCustomScrollbar(
                            {
                                theme:"light-2",
                                scrollButtons:{
                                    enable:true
                                }
                            }
                        );
                    }
                    jQuery(".ev-data a").each(function(){

                        if( jQuery(this).attr("href") && jQuery(this).attr("href").indexOf("#gmap_link") >= 0 ){

                            var pid = jQuery(this).parents(".chronosly").find(".ev-data.place_gmap").attr("id");
                            jQuery(this).attr("href", "https://www.google.com/maps/dir//"+address[pid]);
                        }
                    });
            });
        }
    });
    // setTimeout(function(){
    //     jQuery("#cboxLoadedContent script").each(function(){
    //         //solo ejecuta el maps..mirar otros posibles js
    //         var code = jQuery(this).html().replace("jQuery(window).load(function(){", "").replace(";});", ";");
    //         eval(code);
    //     });
    // }, 1200);
}

function ev_slide(post, el){
    var parent= jQuery(el).parents("div.chronosly");
    if(parent.length) parent = jQuery(parent[0]);
    //jQuery(".ev-"+post+":not(.small) .ev-box.ch-hidden").slideToggle("slow");
    //jQuery(".ev-"+post+":not(.small) .ev-box.normal").slideToggle("slow");
    //jQuery(".ev-"+post).toggleClass("slided", 1000);
    if(!parent.hasClass("small")){
        parent.find(".ev-box.ch-hidden").slideToggle("slow");
        parent.find(".ev-box.normal").slideToggle("slow");
        parent.toggleClass("slided", 1000);
    }

     var mapaid = "null";
    if(parent.find(".ev-data.place_gmap").length) mapaid = parent.find(".ev-data.place_gmap").attr("id");
    setTimeout(function(){

        if(typeof(map) != "undefined" && typeof(map[ mapaid ]) != "undefined"){
            var center = map[ mapaid ].getCenter();
            google.maps.event.trigger(map[ mapaid ], "resize");
            map[ mapaid ].setCenter(center);
        }
        if(!parent.hasClass("small")){
            if(translated1.scrollOnOpen) scrollToAnchor(parent,-20)
        }
    }, 800);

}

function scrollToAnchor(el, less){
    var aTag =  el;
    if(less) var les = -30+less;
    if(el.length)jQuery('html,body').animate({scrollTop: aTag.offset().top +les},'slow');
}

jQuery(document).ready(function($){
    onready_calendar();
    $(".ev-box.hidden").addClass("ch-hidden");
});
jQuery(window).load(function(){
    setTimeout(function () {
        jQuery(".ev-data a").each(function(){

            if( jQuery(this).attr("href") && jQuery(this).attr("href").indexOf("#gmap_link") >= 0 ){
                // console.log("entra");
                var pid = jQuery(this).parents(".chronosly").find(".ev-data.place_gmap").attr("id");
                jQuery(this).attr("href", "https://www.google.com/maps/dir//"+address[pid]);
            }
        });
    }, 1000);

    if(jQuery(".chronosly.ch-dad7 .ev-item.events_list").length ){
        jQuery(".chronosly.ch-dad7 .ev-item.events_list .ev-data.events_list").mCustomScrollbar(
            {
                theme:"light-2",
                scrollButtons:{
                    enable:true
                }
            }
        );
    }
    if(jQuery(".chronosly.ch-dad9 .ev-item.events_list").length ){
        jQuery(".chronosly.ch-dad9 .ev-item.events_list .ev-data.events_list").mCustomScrollbar(
            {
                theme:"light-2",
                scrollButtons:{
                    enable:true
                }
            }
        );
    }
   onload_calendar();
});

function ch_load_calendar(url, id){
      jQuery(".ch_js_loader.id"+id+" .chronosly-content-block").html( "<div class='ch-spinner'></div>" );
    jQuery(".ch_js_loader.id"+id).html("<div class='ch-spinner'></div>" );
    if(!url) url = translated1.calendarurl;
    if(url.indexOf("?") > 1) url =  url+"&js_render=1&calendarid="+id;
    else url += "?js_render=1&calendarid="+id;
    jQuery.get(url.replace(/&#038/g, "&"),function( data ) {
        //jQuery(".ch_js_loader").hide().html( data ).slideDown(3000);
        var content = "";
       if(jQuery(".ch_js_loader.id"+id).length) {
            if(jQuery(data).find(".chronosly-content-block").length) content = jQuery(data).find(".chronosly-content-block");
            else content = jQuery(data);
             if(jQuery(".ch_js_loader.id"+id+" .chronosly-content-block").lenght) jQuery(".ch_js_loader.id"+id+" .chronosly-content-block").html(content);
            else jQuery(".ch_js_loader.id"+id).html(content);
            if(jQuery(".ch_js_loader.id"+id+" .ch-fas-form").length){
                jQuery(".ch_js_loader.id"+id+" input[name='from']").val(content.find(".ch_from").html());
                jQuery(".ch_js_loader.id"+id+" input[name='to']").val(content.find(".ch_to").html());
                jQuery(".ch_js_loader.id"+id+" input[name='y']").val(content.find("input[name='y']").val());
                jQuery(".ch_js_loader.id"+id+" input[name='mo']").val(content.find("input[name='mo']").val());
                jQuery(".ch_js_loader.id"+id+" input[name='week']").val(content.find("input[name='w']").val());
            }
        }
        else {

            if(jQuery(data).find(".chronosly-calendar-block").length) content = jQuery(data).find(".chronosly-calendar-block");
            else content = jQuery(data);
            jQuery(".ch_js_loader.id"+id).html(content);
        }
        onready_calendar();
        onload_calendar();

    });
}

function onready_calendar(){
    jQuery(".ch-fas-form").submit(function(e){
        e.preventDefault();
        ch_filter(jQuery(this));
        return false;
    });
    jQuery(".post-type-archive-chronosly_calendar #page").css("min-height", jQuery(".post-type-archive-chronosly_calendar #page .ch_js_loader").height()+160);
    define_size();
    //hide on load
    //jQuery(".ev-box.ch-hidden").slideToggle("fast");

    //calendar
    jQuery(".chronosly-cal.year .m_tit a").unbind("click").click(function(){
        if(!jQuery(this).prev(".back").is(":visible")) jQuery(this).attr("href",jQuery(this).attr('link'));

    });
    jQuery(".chronosly-cal.year .m_tit").unbind("click").click(function(){
        jQuery(this).next(".m_names").show();
        jQuery(this).find(".back").hide();
        jQuery(this).find(".mday").hide();
        jQuery(this).parent().find(".ch-content").hide();
    });
    jQuery(".chronosly-cal.year .ch-foot").unbind("click").click(function(){
        jQuery(this).parents(".ch-month").find(".m_names").hide();
        jQuery(this).parents(".ch-month").find(".back").show();
        jQuery(this).parents(".ch-month").find(".m_tit a").attr("link",jQuery(this).parents(".ch-month").find(".m_tit a").attr('href' )).attr('href', "javascript:void(0)");
        jQuery(this).parents(".ch-month").find(".mday").html(jQuery(this).find(".cont2").html()).show();
        var margin = jQuery(this).parents(".m_grid").offset().top-jQuery(this).offset().top;
        jQuery(this).prev(".ch-content").css("height", jQuery(this).parents(".m_grid").outerHeight()).css("margin-top",margin).show();
    });

    jQuery(".chronosly-cal.ch-month .ch-foot").unbind("click").click(function(){
        if(!jQuery(this).find(".cont11").is(":visible")){

            //close events
            // jQuery(".chronosly-cal.month .content").addClass("hidde");
            if( jQuery(".chronosly-cal.ch-month").outerWidth() > 600) jQuery(".ch-content.ch-open .cont1").show();
            jQuery(".ch-content.ch-open").removeClass("ch-open");

            //open new
            var h = jQuery(this).parents(".ch-content").outerHeight();
            var h2 = jQuery(this).parents(".ch-content .ch-foot").outerHeight();
            jQuery(".chronosly-cal.ch-month .ch-content").addClass("hidde");
            jQuery(this).parents(".ch-content").removeClass("hidde").addClass("ch-open").css("height", h);
            jQuery(this).css("height", h2);
            jQuery(this).find(".cont1").hide();
        } else{

            jQuery(this).parents(".ch-content").removeClass("ch-open");
            jQuery(".chronosly-cal.ch-month .ch-content").removeClass("hidde");
            if(translated1.scrollOnOpen) jQuery('html,body').animate({scrollTop: jQuery(this).parents(".ch-content").offset().top -60},'slow');
            if( jQuery(".chronosly-cal.ch-month").outerWidth() > 600) jQuery(this).find(".cont1").show();
        }

    });
    jQuery(".chronosly-cal.ch-month .ch-foot .cont11").unbind("click").click(function(){

    });
    if( jQuery(".chronosly-cal.ch-month").length && jQuery(".chronosly-cal.ch-month").outerWidth() > 600){
        jQuery(".chronosly-cal.ch-month .ch-content").each(function(){
            if(jQuery(this).outerHeight() < jQuery(this).children(".cont").outerHeight()) jQuery(this).find(".ch-foot .cont1").show();

        })
    }

    if( jQuery(".post-type-archive-chronosly_calendar .today").length){
        var less = -70;
        if( jQuery(".chronosly-cal.year").length){
            if(jQuery(window).height() > 600)less = -400;
            else if(jQuery(window).height() > 400)less = -320;
            else if(jQuery(window).height() > 300)less = -250;
            else if(jQuery(window).height() > 200)less = -150;
        }
        if(translated1.scrollOnOpen) scrollToAnchor(jQuery(".today"), less);
    }

    //filter js
    if(typeof ch_filter_init == 'function') ch_filter_init();

}

function onload_calendar(){
    if(jQuery(".chronosly-cal.year.ch-default .ch-content.withevents").length ){
        jQuery(".chronosly-cal.year.ch-default .ch-content.withevents").mCustomScrollbar(
            {
                theme:"light-2",
                scrollButtons:{
                    enable:true
                }
            }
        );
    }
}

function ch_prev_page(limit, pag, code, element){
         element = jQuery(element).parents(".chronosly-content-block");

    element.html( "<div class='ch-spinner'></div>" );
    var url = translated1.ajaxurl;
    var args = {
        action: "ch_run_shortcode",
        ch_code: code,
        page: pag-1
    };
    jQuery.post(url,args, function( data ) {
        var content = "";
        if(jQuery(data).find(".chronosly-content-block").length) content = jQuery(data).find(".chronosly-content-block");
        else content = jQuery(data);
        element.html(content);
        js_post_pagination();
    });
}

function ch_next_page(limit, pag, code, element){
     element = jQuery(element).parents(".chronosly-content-block");
    element.html( "<div class='ch-spinner'></div>" );
    var url = translated1.ajaxurl;
    var args = {
        action: "ch_run_shortcode",
        ch_code: code,
        page: pag+1
    };
    jQuery.post(url,args, function( data ) {
        var content = "";
        if(jQuery(data).find(".chronosly-content-block").length) content = jQuery(data).find(".chronosly-content-block");
        else content = jQuery(data);
        element.html(content);
        js_post_pagination();
    });
}

function seasons_paginate(dir, element){
    element = jQuery(element).parents(".ch-pag");
    element.toggleClass("show");
    if(dir == "next") element.next().toggleClass("show");
    else element.prev().toggleClass("show");
    scrollToAnchor(jQuery(".ev-data.seasons_list"), -10);
}


function js_post_pagination(){

    jQuery(".chronosly-content-block .chronosly script").each(function(){
        //solo ejecuta el maps..mirar otros posibles js
        var code = jQuery(this).html().replace("jQuery(window).load(function(){", "").replace(";});", ";");
        eval(code);
    });
    define_size();
}

function define_size(){
    if(!Environment.isMobile()){
        jQuery("div.chronosly").each(function(){
            if(jQuery(this).width() < 500 && !jQuery(this).hasClass("small")) jQuery(this).addClass("medium");
        });
        jQuery("div.chronosly-cal").each(function(){
            if(jQuery(this).width() < 600 && !jQuery(this).hasClass("small")) jQuery(this).addClass("medium");
        });
    }
}
