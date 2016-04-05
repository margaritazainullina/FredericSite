var elementos;

jQuery(document).ready(function($){
    var args = {
        action : "chronosly_load_mk_templates"

    };
    //generamos el html del template
    $.getJSON( ajaxurl, args, function( data ) {


        elementos = eval(data);
        pinta_templates_featured(elementos);
        pinta_templates_listado(elementos);
        args = {
            action : "chronosly_load_mk_templates_downloaded"

        };
        $.getJSON( ajaxurl, args, function( data2 ) {
            templates_descargados(data2);

        });
    });



    $(".marketplace div.filter select").change(function(){
        marketplace_reorder($(this).val());
    });
})

function pinta_templates_featured(data){
    var caja = "";
    jQuery.each(data, function(){
        var el = jQuery(this)[0];
        if(el.featured == 1) {
             caja += pinta_templates_caja_featured(el);


        }
    });
    jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.featured").append(caja);
}

function pinta_templates_listado(data){
    var caja = "";
    jQuery.each(data, function(){
        var el = jQuery(this)[0];

            caja += pinta_templates_caja(el);


    });
    jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list").append(caja);
}

function pinta_templates_caja_featured(el){
    var feat = " featured";
    return "<div id='"+el.id+"' class='box"+feat+"' time='"+el.updated+"' d='"+el.dw+"' rate='"+el.rate+"'>" +
                "<img src='"+el.img+"' />"+
                "<h4>"+el.name+"</h4>" +
                "<p class='desc'>"+el.desc+"</p>" +
                "<div class='footer'>" +
                    "<span class='autor'><b>"+translated.author+":</b>"+el.author+"</span>"+
                    "<a href='"+el.url+"' target='_blank'  class='price'>"+el.price+"</a>"+
                "</div>" +
                "<div class='actions'>" +
                    "<a href='"+el.url+"' target='_blank' class='more'>"+translated.view+"</a>"+
                    "<span class='download'>"+translated.download+"</span>"+
                "</div>" +
            "</div>";

}

function pinta_templates_caja(el){
    var feat = "";
    var buttons = "";
    if(el.featured) feat += " featured";
    if(el.descargado) feat += " descargado";
    else {
        var buttons = "<div class='actions'>" +
            "<a href='"+el.view+"' target='_blank'  class='more'>"+translated.view+"</a>"+
            "<a href='"+el.url+"' target='_blank'  class='price'>"+el.price+" $</a>"+

            "</div>";
    }
    return "<div id='"+el.id+"' class='box"+feat+"' time='"+el.updated+"' d='"+el.dw+"' rate='"+el.rate+"'>" +
        "<img src='"+el.img+"' />"+
        "<h4>"+el.name+"</h4>" +
                "<p class='desc'>"+el.desc+"</p>" +
                "<div class='footer'>" +
                    "<span class='autor'><b>"+translated.author+":</b>"+el.author+"</span>"+
                "</div>"+
                buttons +
            "</div>";

}


function templates_descargados(data){
    jQuery.each(data, function(index, value){


        jQuery(".marketplace .list #"+index).addClass("descargado");
        jQuery(".marketplace .list #"+index+" .actions").hide();
        //actualizamos el elemento en el array
        jQuery.each(elementos, function (i, elemento){
            if(elemento.id == index) elemento.descargado = 1;
        });

    });


}

function marketplace_reorder(campo){
    switch (campo){
        case "price":
            jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list div.box").remove();
            pinta_templates_listado(elementos.sort(SortByPrice));
        break;
        case "name":
            jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list div.box").remove();
            pinta_templates_listado(elementos.sort(SortByName));
        break;
        case "rated":
            jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list div.box").remove();
            pinta_templates_listado(elementos.sort(SortByRated));
        break;
        case "popular":
            jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list div.box").remove();
            pinta_templates_listado(elementos.sort(SortByPopular));
        break;
        case "new":
            jQuery(".chronosly_page_chronosly_mk_templates .marketplace div.list div.box").remove();
            pinta_templates_listado(elementos.sort(SortByNew));
        break;

    }
}

function SortByPrice(a, b){
    var avar = a.price;
    var bvar = b.price;
    return ((avar < bvar) ? -1 : ((avar > bvar) ? 1 : 0));
}

function SortByName(a, b){
    var avar = a.name;
    var bvar = b.name;
    return ((avar < bvar) ? -1 : ((avar > bvar) ? 1 : 0));
}
function SortByRated(a, b){
    var avar = a.rate;
    var bvar = b.rate;
    return ((avar > bvar) ? -1 : ((avar < bvar) ? 1 : 0));
}

function SortByPopular(a, b){
    var avar = a.dw;
    var bvar = b.dw;
    return ((avar > bvar) ? -1 : ((avar < bvar) ? 1 : 0));
}
function SortByNew(a, b){
    var avar = a.updated;
    var bvar = b.updated;
    return ((avar > bvar) ? -1 : ((avar < bvar) ? 1 : 0));
}


