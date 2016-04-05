var items = [];
var conf = {"filter":"all", "group":"temp"};
var piecolors = ["#fecb37","#ffa00c","#55abbe","#baf4f4","#82d8c8","#a8ac1a","#80b04c", "#611934"];
var piehiglights = ["#ffda6e","#feaf33","#72c0d1","#d0f9f9","#9ee8da","#b8bc2c","#98c765", "#6f2440"];

if(typeof translated != "undefined" && translated.templates_items){
    items = eval(translated.templates_items);
    

}

jQuery(document).ready(function($){



    $("span.info").click(function(){$(this).next(".info-hide").slideToggle()});

    color_picker($("input.color"));


    $(".ch-profile").click(function(){
        $("#perfiles input.perfil").val($(this).attr("id").replace("pf", ""));
        $("#perfiles").submit();
    });


    if( $( "#settings-tabs").length){
        $( "#settings-tabs" ).tabs({

             activate: function( event, ui ) {
                // console.log(ui.newPanel.selector);
                $("form#settings").attr("action", ui.newPanel.selector);
             }
            
        }
        ).addClass( "ui-tabs-vertical ui-helper-clearfix" );
        $( "#settings-tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );

    }

    if(typeof translated != "undefined" && translated.templates_items){

        var views_name = "";
    
        $.each(items, function(){
            print_template_item_status($(this));


        });
        clickable_headers();

        //filtros
        $("#filter1").change(function(){
            var val = jQuery(this).find("option:selected").val();
            conf.filter = val;
            $("#status").html("");
            $.each(items, function(){
                print_template_item_status($(this));
            });
            clickable_headers();
            print_chart_status();

        });
        //vinculamos el selector a el pintar el status
        $("#filter2").change(function(){
            var val = jQuery(this).find("option:selected").val();
            conf.group = val;
            $("#status").html("");
            $.each(items, function(){
                print_template_item_status($(this));
            });
            clickable_headers();

        });

        //pintamos un grafico ;)
        print_chart_status();
    }
});

function clickable_headers(){
    jQuery(".group").hide();
    jQuery(".group2").hide();

    jQuery("#status h3").click(function(){
        jQuery(this).next(".group").slideToggle();
        jQuery(this).toggleClass("open");
        if(conf.group == "view") {

            print_chart_status(jQuery(this).next(".group").attr("class").replace("group ", ""));

        }
    })
    jQuery("#status h4").click(function(){
        jQuery(this).next(".group2").slideToggle();
    })

};

function print_template_item_status(el){
    el = el[0];
    var g_name = "";
    var g2_name = "";
    var g3_name = "";
    var g_type = "";
    var g2_type = "";
    var g3_type = "";
    var id = "";
    var ev_name = "";
    if(conf.filter == "not-past" && !not_past(el)) return;//eventos pasados no se printan
    else if(conf.filter == "file" && el.templ == "chbd") return;//eventos que no usan template no se printan
    else if(conf.filter == "chbd"&& el.templ != "chbd") return;//eventos que usan template no se printan
    switch(conf.group ){
        case "temp":
            id = el.id;
            name = el["name"];

            g_name = el.templ;
            g_type = g_name.replace(" ", "_");
            if(g_name =="chbd") g_name = translated.bd_template;
            else g_name =  translated.template+" "+g_name;
            g2_type = el.view;
            g2_name = get_translated(g2_type);
            g3_name = el["cats-n"].replace(/\|/g, ", ");
            g3_type = translated.category;
            if(el.view == "dad7" ||el.view == "dad8")ev_name = translated.organizer;
            else if(el.view == "dad9" ||el.view == "dad10")ev_name = translated.place;
            else if(el.view == "dad11" ||el.view == "dad12")ev_name = translated.category;
            else if(el.view == "dad13" ||el.view == "dad14"||el.view == "dad15")ev_name = translated.calendar;
            else ev_name = translated.event;
            build_template_status_item(g_name, g2_name, g3_name, g_type, g2_type, g3_type, ev_name ,id, name);
        break;
        case "view":
            id = el.id;
            name = el["name"];
            g2_name = el.templ;
            g2_type = g2_name.replace(" ", "_");
            if(g2_name =="chbd") g2_name = translated.bd_template;
            else g2_name =  translated.template+" "+g2_name;


            g_type = el.view;
            g_name = get_translated(g_type);
            g3_name = el["cats-n"].replace(/\|/g, ", ");
            g3_type = translated.category;
            if(el.view == "dad7" ||el.view == "dad8")ev_name = translated.organizer;
            else if(el.view == "dad9" ||el.view == "dad10")ev_name = translated.place;
            else if(el.view == "dad11" ||el.view == "dad12")ev_name = translated.category;
            else if(el.view == "dad13" ||el.view == "dad14"||el.view == "dad15")ev_name = translated.calendar;
            else ev_name = translated.event;
            build_template_status_item(g_name, g2_name, g3_name, g_type, g2_type, g3_type, ev_name ,id, name);
        break;
       /* case "cat":
            id = el.id;
            g3_name = el.cats;
            g3_type = translated.template;
            if(g3_name =="chbd") g3_name = translated.bd_template;


            g2_type = el.view;
            g2_name = get_translated(g2_type);
            g_name = el.cats;
            if(g_name){
                g_type = g_name.replace(" ","_");
                g_name = translated.category+" "+g_name;
            }
            else {
                g_type = "without-cat";
                g_name = translated.without+" "+translated.category;
            }

            if(el.view == "dad7" ||el.view == "dad8")ev_name = translated.organizer;
            else if(el.view == "dad9" ||el.view == "dad10")ev_name = translated.place;
            else if(el.view == "dad11" ||el.view == "dad12")ev_name = translated.category;
            else if(el.view == "dad13" ||el.view == "dad14"||el.view == "dad15")ev_name = translated.calendar;
            else ev_name = translated.event;
            build_template_status_item(g_name, g2_name, g3_name, g_type, g2_type, g3_type, ev_name ,id);
        break;*/

    }
}

//printamos la estructura
function build_template_status_item(g_name, g2_name, g3_name, g_type, g2_type, g3_type, ev_name ,id, name){
    //console.log(g_name+" "+g2_name+" "+g3_name+" "+g_type+" "+g2_type+" "+g3_type+" "+ev_name+" "+id)
    var cont ="";
    if(name == "null") name = id.toString();
    if(!jQuery(".group."+g_type).length) jQuery("#status").append( "<h3>"+g_name+"</h3><div class='group "+g_type+"'></div>");
    if(!jQuery(".group."+g_type+" .group2."+g2_type).length) jQuery(".group."+g_type).append( "<h4>"+g2_name+"(<span class='counter "+g2_type+"'>0</span>)</h4><div class='group2 "+g2_type+"'></div>");
    if(g3_name) cont = "<div class='item'><a target='_blank' href='post.php?post="+id+"&action=edit'>"+ev_name+" "+name.substr(0, 100)+"</a> <span class='g3'>"+g3_type+" "+g3_name+"</span></div>";
    else {
        if(id != "undefined" && id.toString().indexOf("c") >= 0) cont = "<div class='item'><a target='_blank' href='edit-tags.php?action=edit&taxonomy=chronosly_category&tag_ID="+id.replace("c", "")+"&post_type=chronosly'>"+ev_name+" "+name.substr(0, 100)+"</a></div>";
        else cont = "<div class='item'><a target='_blank' href='post.php?post="+id+"&action=edit'>"+ev_name+" "+name.substr(0, 100)+"</a></div>";
    }
    jQuery(".group."+g_type+" .group2."+g2_type).append(cont);
    jQuery(".group."+g_type+" .counter."+g2_type).html(parseInt(jQuery(".group."+g_type+" .counter."+g2_type).html())+1)//update counter
}

function get_translated(vars){
    switch(vars){
        case "dad1":return translated.dad1;
        case "dad2":return translated.dad2;
        case "dad3":return translated.dad3;
        case "dad4":return translated.dad4;
        case "dad5":return translated.dad5;
        case "dad6":return translated.dad6;
        case "dad7":return translated.dad7;
        case "dad8":return translated.dad8;
        case "dad9":return translated.dad9;
        case "dad10":return translated.dad10;
        case "dad11":return translated.dad11;
        case "dad12":return translated.dad12;
       /* case "dad13":return translated.dad13;
        case "dad14":return translated.dad14;
        case "dad15":return translated.dad15;*/
    }
}

function print_chart_status(vista){
    //preparamos los datos en formato  ['Firefox',   45.0],
    var templates = {};
    jQuery.each(items, function(){
        var el = jQuery(this)[0];
        var x = el.templ.replace(" ", "_");
        if(vista && el.view != vista){
            return "next";
        }
        if(conf.filter == "all"){
            if(templates[x]) templates[x] = templates[x]+1;
            else templates[x] = 1;
        } else if(conf.filter == "not-past"){
            if(not_past(el)){
                if(templates[x]) templates[x] = templates[x]+1;
                else templates[x] = 1;
            }
        } else if(conf.filter == "file"){
            if(el.templ != "chbd"){
                if(templates[x]) templates[x] = templates[x]+1;
                else templates[x] = 1;
            }
        }
        else if(conf.filter == "chbd"){
            if(el.templ == "chbd"){
                if(templates[x]) templates[x] = templates[x]+1;
                else templates[x] = 1;
            }

        }
    });
    var elements = [];
    var i = -1;
    jQuery.each(templates, function(key, val){

        if(key == "chbd"){
            key = translated.bd_template;
           // elements.push({name: key, y: value, color:'#BF0B23'})
            elements.push({value: val, label: key, color:"#e75e38", highlight: "#f57450"})

        } //else elements.push({name: key, y: value});
        else {
          if(piecolors.length > i+2)  ++i;
          else i = 0;
          elements.push({value: val, label: key, color:piecolors[i], highlight: piehiglights[i]});
        }
    });

    build_chart_status(elements);
}



function build_chart_status(elements){
    jQuery('#container').html("");
    var ctx = jQuery("#container").get(0).getContext("2d");

    var options = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke : false,

        //String - The colour of each segment stroke
        segmentStrokeColor : "#fff",

        //Number - The width of each segment stroke
        segmentStrokeWidth : 1,

        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout : 0, // This is 0 for Pie charts

        //Number - Amount of animation steps
        animationSteps : 100,

        //String - Animation easing effect
        animationEasing : "easeOutBounce",

        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate : true,

        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale : false,

        //String - A legend template
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

    };
    var myNewChart = new Chart(ctx).Pie(elements,options);

    legend("#pieLegend", elements);


}

function not_past(el){
    if(!el.data) return 0;
    date = parseDate(el.data);
    if(date < new Date()) return 0;
    return 1;
}

function parseDate(input) {
    var parts = input.split('-');
    // new Date(year, month [, day [, hours[, minutes[, seconds[, ms]]]]])
    return new Date(parts[0], parts[1]-1, parts[2]); // Note: months are 0-based
}

function clear_cache(){
    var args = {
        action : "chronosly_clear_cache"

    };
    //generamos el html del template
    jQuery.post( ajaxurl, args, function( data ) {
        jQuery("#clearcache").text(data).removeClass("warning").addClass("yell");
    });
}

function color_picker(el){
    el.spectrum({
        //flat: true,
        showInput: true,
        allowEmpty: true,
        showInitial: true,
        showPalette: true,
        showSelectionPalette: true,
        maxPaletteSize: 20,
        preferredFormat: "hex",
        localStorageKey: "spectrum.demo",

        /*move: function (color) {

         },
         show: function () {

         },
         beforeShow: function () {

         },
         hide: function () {

         },
         change: function() {

         },*/
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