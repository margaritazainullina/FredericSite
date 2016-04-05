/**
 * Admin JS functions
 */
(function ($) {

    $("#ecwd_category_color").ecolorpicker();

    $('#ecwd_event_repeat_dont_repeat_radio').click(function () {
        $("#ecwd_repeat_until").hide();
    });


    $("#ecwd_category_color, .ecwd_colour").ecolorpicker({
        displayIndicator: false,
        displayPointer: false,
        transparentColor: true
    });

    $("#ecwd_category_color, .ecwd_colour").on("change.color", function (event, color) {
        $(this).css('background-color', color);
    });

    //events custom fields js		
    var allHiddens = $('#ecwd_event_repeats_div .hidden'),
        radios = $('.ecwd_event_repeat_event_radio, .ecwd_event_repeat_list_radio');
    var checked_el = $('.ecwd_event_repeat_event_radio:checked,  .ecwd_event_repeat_list_radio:checked');
    show_fields(checked_el);

    $('.ecwd_event_repeat_event_radio').click(function (e) {
        show_fields($(this));
    });


    function show_fields(el) {
        allHiddens.attr('class', 'hidden');
        if (el.attr('id') != 'ecwd_event_repeat_dont_repeat_radio') {
            $("#ecwd_repeat_until").show();
        }
        else {
            $("#ecwd_repeat_until").hide();
        }
        $('#ecwd_' + el.val()).removeClass('hidden');
        if ($('#ecwd_event_repeat_how_label_' + el.val()).length > 0) {
            $('#ecwd_daily').removeClass('hidden');
            $('#ecwd_event_repeat_how_label_' + el.val()).removeClass('hidden');
        }
        $('#ecwd_repeat_until').removeClass('hidden');
    }

    if ($(".ecwd_event_repeat_choose").prop('checked')) {
        $(".select_to_enable_disable").attr('disabled', true);
    } else {
        $(".ecwd_event_repeat_on_the").attr('disabled', true);
    }

    $("#ecwd_event_repeat_dont_repeat_radio").click(function () {
        $("#ecwd_repeat_until").attr('class', 'hidden');
    });

    $(".ecwd_event_repeat_choose").click(function () {
        $(".ecwd_event_repeat_on_the").attr('disabled', false);
        $(".select_to_enable_disable").attr('disabled', true);
    });

    if($(".ecwd_event_repeat_list_radio").prop('checked')){

        $(".ecwd_event_repeat_on_the").attr('disabled', true);
        $(".select_to_enable_disable").attr('disabled', false);
    }
    if($("#ecwd_event_repeat_year_on_days_2").prop('checked')){
        $(".ecwd_event_repeat_on_the").attr('disabled', true);
        $(".select_to_enable_disable").attr('disabled', false);
        $(".#ecwd_event_year_month").attr('disabled', true);
    }
    $(".ecwd_event_repeat_list_radio").click(function () {
        $(".ecwd_event_repeat_on_the").attr('disabled', true);
        $(".select_to_enable_disable").attr('disabled', false);
    });

    //	on adding event
    $("#ecwd_repeat_event_monthly").click(function () {
        $(".ecwd_event_repeat_choose").prop('checked', true);
        $(".ecwd_event_repeat_on_the").attr('disabled', false);
        $(".select_to_enable_disable").attr('disabled', true);
    });

    //event validations
    if ($("#ecwd_event_meta").length > 0) {
        $("#post").submit(function (e) {
            var dateTo = Date.parse($("#ecwd_event_date_to").val()),
                dateFrom = Date.parse($("#ecwd_event_date_from").val());
            if (dateFrom == '' || isNaN(dateFrom) || isNaN(dateTo) || dateTo == '') {
                alert('Please set the event dates');
                e.preventDefault();
            }
            if (dateFrom && !dateTo) {
                alert('Please set the end date');
                e.preventDefault();
            }
            if (dateTo < dateFrom) {
                alert('Date to must be greater or equal to Date from');
                e.preventDefault();
            }
            if($('input[name="ecwd_event_repeat_event"]').length>0) {
                var repeat = $('input[name="ecwd_event_repeat_event"]:checked').val();
                var until = Date.parse($('#ecwd_event_repeat_until_input').val());
                if (repeat !== 'no_repeat') {
                    if (until == '' || isNaN(until)) {
                        alert('Please set the repeat until date');
                        e.preventDefault();
                    }
                    if (!isNaN(dateFrom) && !isNaN(until) && until <= dateFrom) {
                        alert('Repeat until date must be greater than Date from');
                        e.preventDefault();
                    }
                }
            }
        });
    }

    //calendar validations, etc
    if ($("#publish").attr('value') == 'Publish') {
        $("#ecwd_calendar_12_hour_time_format_NO").prop('checked', true);
    }

    if ($('#map-canvas').length > 0) {
        loadScript();
    }


    var wordpress_ver = params.version, upload_button;

    $(".ecwd_upload_image_button").click(function (event) {
        upload_button = $(this);
        var frame;
        if (wordpress_ver >= "3.5") {
            event.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media();
            frame.on("select", function () {
                // Grab the selected attachment.
                var attachment = frame.state().get("selection").first();
                frame.close();
                if (upload_button.parent().prev().children().hasClass("tax_list")) {
                    upload_button.parent().prev().children().val(attachment.attributes.url);
                    upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
                }
                else
                    $("#ecwd_taxonomy_image").val(attachment.attributes.url);
            });
            frame.open();
        }
        else {
            tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
            return false;
        }
    });

    $(".ecwd_remove_image_button").click(function () {
        $("#ecwd_taxonomy_image").val("");
        $(this).parent().siblings(".title").children("img").attr("src", "' . Z_IMAGE_PLACEHOLDER . '");
        $(".inline-edit-col :input[name=\'ecwd_taxonomy_image\']").val("");
        return false;
    });

    if (wordpress_ver < "3.5") {
        window.send_to_editor = function (html) {
            imgurl = $("img", html).attr("src");
            if (upload_button.parent().prev().children().hasClass("tax_list")) {
                upload_button.parent().prev().children().val(imgurl);
                upload_button.parent().prev().prev().children().attr("src", imgurl);
            }
            else
                $("#ecwd_taxonomy_image").val(imgurl);
            tb_remove();
        }
    }

    $("body").on("click", '.editinline', function () {
        var tax_id = $(this).parents("tr").attr("id").substr(4);
        var thumb = $("#tag-" + tax_id + " .thumb img").attr("src");
        if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
            $(".inline-edit-col :input[name=\'ecwd_taxonomy_image\']").val(thumb);
        } else {
            $(".inline-edit-col :input[name=\'ecwd_taxonomy_image\']").val("");
        }
        $(".inline-edit-col .title img").attr("src", thumb);
        return false;
    });
    ////////////Calendar add/remove events/////////////
    $(document).on('click', '.ecwd-events .ecwd-calendar-event-delete', function () {
        if (confirm('Sure?')) {
            var clicked_el = this;
            var element = $(this).closest('.ecwd-calendar-event');
            var event_id = $(element).find('input').val();
            var calendar_id = $('#post_ID').val();
            $.post(params.ajaxurl, {
                action: 'manage_calendar_events',
                ecwd_event_id: event_id,
                ecwd_calendar_id: calendar_id,
                ecwd_action: 'delete'
            }).done(function (data) {
                res = JSON.parse(data);
                if (res.status == 'ok') {
                    $(clicked_el).removeClass('ecwd-calendar-event-delete');
                    $(clicked_el).addClass('ecwd-calendar-event-add');
                    $(clicked_el).text('+');
                    $(element).find('.ecwd-calendar-event-edit').addClass('hidden');
                    $(element).remove().appendTo('.ecwd-excluded-events');
                    $(element).find('input').attr('name', 'ecwd-calendar-excluded-event-id[]');
                }
            });
        }
    });

    $(document).on('click', '.ecwd-excluded-events .ecwd-calendar-event-add, #ecwd_add_event_to_calendar .ecwd-calendar-event-add', function () {
        var clicked_el = this;
        var element = $(this).closest('.ecwd-calendar-event');
        var calendar_id = $('#post_ID').val();
        var event_id = $(element).find('input').val();
        $.post(params.ajaxurl, {
            action: 'manage_calendar_events',
            ecwd_event_id: event_id,
            ecwd_calendar_id: calendar_id,
            ecwd_action: 'add'

        }).done(function (data) {
            res = JSON.parse(data);
            if (res.status == 'ok') {
                $(clicked_el).addClass('ecwd-calendar-event-delete');
                $(clicked_el).removeClass('ecwd-calendar-event-add');
                $(clicked_el).text('x');
                $(element).find('input').attr('name', 'ecwd-calendar-event-id[]');
                $(element).find('.ecwd-calendar-event-edit').removeClass('hidden');
                $(element).remove().appendTo('.ecwd-events');
            }
        });

    });
    ////////////////////////////////////////////////////////

    ////////////Calendar selectable add events/////////////
    $(document).on('click', '.event_cal_add .event_cal_add_close', function (e) {
        $('.event_cal_add').hide();
    });
    $(document).on('mouseup', '.day-with-date', function (e) {
        var position = $(this).position();
        $('.event_cal_add').css({"margin":"0 auto","left":position.left});
    });

    $('body').on('mouseenter', '.ecwd_calendar_container',function(){
        $(this).selectable({
        filter: ".day-with-date",
        start: function(){
            $('.event_cal_add').hide();
            $('#add_event_to_cal').show();
            $('.ecwd_notification, .ecwd_error').empty();
            $('#ecwd_event_name').val('');
        },
        stop: function () {

            var result = $("#select-result").empty();

            var position = $('.ui-selected').last().find('.day-number').position();
            var start_day = parseInt($('.ui-selected').first().find('.day-number').text());
            var end_day = parseInt($('.ui-selected').last().find('.day-number').text());
            var start_date = $('.ui-selected').first().attr('data-date');
            var end_date = $('.ui-selected').last().attr('data-date');
            if (start_day) {
                if (start_day == end_day) {
                    $('.ecwd-dates').text(start_date);
                    $('#ecwd_event_date_from').val(start_date);
                    $('#ecwd_event_date_to').val(start_date);
                }
                if (end_day > start_day) {
                    $('.ecwd-dates').text(start_date + ' - ' + end_date);
                    $('#ecwd_event_date_from').val(start_date);
                    $('#ecwd_event_date_to').val(end_date);

                }
                if (start_day > end_day) {
                    $('.ecwd-dates').text(end_date + ' - ' + start_date);
                    $('#ecwd_event_date_from').val(end_date);
                    $('#ecwd_event_date_to').val(start_date);
                }
                $('.event_cal_add').removeClass('hidden');
                $('.event_cal_add').show();
                setTimeout(function () {
                    $('#ecwd_event_name').focus();
                }, 1);
                $('#ecwd-modal-preview').animate({
                    scrollTop: $(".event_cal_add").position().top
                }, 1000);
            }
        }
    });
    });
    $(document).on('click', '#add_event_to_cal', function () {
        var start_date = $('#ecwd_event_date_from').val();
        var end_date = $('#ecwd_event_date_to').val();
        var name = $('#ecwd_event_name').val();
        if (name.length > 0) {
            var calendar_id = $('#post_ID').val();
            $.post(params.ajaxurl, {
                action: 'add_calendar_event',
                ecwd_calendar_id: calendar_id,
                ecwd_event_name: name,
                ecwd_event_date_from: start_date,
                ecwd_event_date_to: end_date
            }).done(function (data) {
                res = JSON.parse(data);
                if (res.status == 'success') {
                    $('#add_event_to_cal').hide();
                    $('.ecwd_notification').html('Event \'' + name + '\' has been saved as draft.  <a href="?post=' + res.data.event_id + '&action=edit">Edit details</a>');
                }
            });
        } else {
            $('#ecwd_event_name').focus();
            $('.ecwd_error').html('Enter event name');
        }
    });
    //////////////////////////////////////////

    //////////////Theme tabs//////////////////
	
	if(typeof(localStorage.currentItem)!== "undefined"){
	   var current_item=localStorage.currentItem;
	   $("#ecwd-tabs > div").css("display","none");
	   $(current_item).css("display","block");
	   $("#ecwd-tabs .ecwd-tabs li").removeClass("ui-state-active");
	   $('#ecwd-tabs .ecwd-tabs li a[href="'+current_item+'"]').parent().addClass("ui-state-active");
	}else{
	   $('#general').css("display","block");
	   $('#ecwd-tabs .ecwd-tabs li:first-child').addClass("ui-state-active");
	} 		

	$(".ecwd-tabs li a").each(function (indx, element) {
		  $(element).click(function(){
			if(typeof(Storage) !== "undefined") {
				localStorage.currentItem = $(element).attr("href");
			}
			$("#ecwd-tabs > div").css("display","none");
			$(localStorage.currentItem).css("display","block");
			$('#ecwd-tabs .ecwd-tabs li').removeClass("ui-state-active");
			$(element).parent().addClass("ui-state-active");
		});
	});
	
	
    //////////////////////////////////////////
    $('#ecwd_event_venue').change( function (e) {

        if($(this).val()>0) {
            var location = $(this).find(':selected').data('location');
            var markerLatLong = $(this).find(':selected').data('marker');
            var zoom = parseInt($(this).find(':selected').data('zoom'));
            if(!zoom){
                zoom = 17;
            }
            var lat_long = markerLatLong.split(',');
            var myLatlng = new google.maps.LatLng(parseFloat(lat_long[0]), parseFloat(lat_long[1]));
            //alert(markerLatLong);

            deleteMarkers();
            addMarker(myLatlng);

            map.setCenter(myLatlng);
            map.setZoom(zoom);
            $('#ecwd_event_location').val(location);
            $('#ecwd_map_zoom').val(zoom);
        }
    });
    
    $('.ecwd_add_event_to_calendar').ecwd_popup({
                button: $('.ecwd_events_popup_button'),
                title: 'Event List',
                container_class:'ecwd_add_event_calendar'
            });
            $('#ecwd_preview_add_event_popup').ecwd_popup({
                button: $('#ecwd_preview_add_event'),
                title: 'Calendar',
                body_class: "ecwd-modal",
                container_class:'ecwd_preview_calendar'                
            });
}(jQuery));

var map;
var markers = [];
var geocoder;

function initialize() {
    geocoder = new google.maps.Geocoder();

    var lat_long = document.getElementById('ecwd_lat_long').value.split(',');
    var lat_long_available = false;
    if(lat_long[0]) {
        var myLatlng = new google.maps.LatLng(parseFloat(lat_long[0]), parseFloat(lat_long[1]));
        lat_long_available = true;
    }else{
        var myLatlng = new google.maps.LatLng(53.65914, 0.072050);
    }
    var ecwd_zoom = parseInt(document.getElementById('ecwd_map_zoom').value);
    var ecwd_marker = parseInt(document.getElementById('ecwd_marker').value);

    var mapOptions = {
        zoom: ecwd_zoom,
        center: myLatlng,
        scrollwheel: false
    };

    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    if (!lat_long_available && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            map.setCenter(initialLocation);
        });
    }


    var input = document.getElementById('ecwd_event_location');

    var types = document.getElementById('type-selector');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var address_marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29),
        draggable: true
    });
    markers.push(address_marker);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        infowindow.close();
        address_marker.setVisible(false);

        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(ecwd_zoom);
        }

        document.getElementById('ecwd_lat_long').value = place.geometry.location.toString().replace(')', '').replace('(', '');
        //marker.setIcon(/** @type {google.maps.Icon} */({
        //    url: place.icon,
        //    size: new google.maps.Size(71, 71),
        //    origin: new google.maps.Point(0, 0),
        //    anchor: new google.maps.Point(17, 34),
        //    scaledSize: new google.maps.Size(35, 35)
        //}));
        address_marker.setPosition(place.geometry.location);
        address_marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, address_marker);
    });

    google.maps.event.addListener(map, 'click', function(event) {
        deleteMarkers();
        geocodePosition(event.latLng);
        addMarker(event.latLng);
    });
    google.maps.event.addListener(address_marker, 'dragend', function () {
        setMarkerPosition(address_marker);
        geocodePosition(address_marker.getPosition());

    });
    google.maps.event.addListener(map, 'zoom_changed', function() {
        jQuery('#ecwd_map_zoom').val(map.getZoom());
    });

    if (ecwd_marker == 1) {
        var infowindow = new google.maps.InfoWindow();
        addMarker(myLatlng);
        var loc = document.getElementById('ecwd_event_location').value;

    }

}

// Add a marker to the map and push to the array.
function addMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true
    });
    markers.push(marker);
    setMarkerPosition(marker);
    google.maps.event.addListener(marker,'dragend',function(event) {
        setMarkerPosition(marker);
        geocodePosition(marker.getPosition());

    });
}

// Sets the map on all markers in the array.
function setAllMap(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setAllMap(null);
}

// Shows any markers currently in the array.
function showMarkers() {
    setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

function setMarkerPosition(marker) {
    document.getElementById('ecwd_lat_long').value = marker.getPosition().toUrlValue();
}

function geocodePosition(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            updateMarkerAddress(responses[0].formatted_address);
        } else {
            updateMarkerAddress('Cannot determine address at this location.');
        }
    });
}
function updateMarkerAddress(address){
    document.getElementById('ecwd_event_location').value = address;

}

function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIUXhK7om6EZ9Ca5xFcEbXMKEQZz7U2kM' +
    '&callback=initialize&libraries=places';
    document.body.appendChild(script);
}
