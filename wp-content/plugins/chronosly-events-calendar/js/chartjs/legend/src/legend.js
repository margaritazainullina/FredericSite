function legend(parent, data) {
    parent.className = 'legend';
    var datas = data.hasOwnProperty('datasets') ? data.datasets : data;

    // remove possible children of the parent

    jQuery(parent).html("");
    datas.forEach(function(d) {
		jQuery(parent).append("<span class='leg' style='background-color:"+d.color+"'></span> <span class='legtit'>"+d.label+"</span><br/>");
      
    });
}
