function initMap() {
    var map_center = new google.maps.LatLng(50.2, 87.5);
    if ($.cookie("map_center") != undefined) {
        cookie_map_center = new google.maps.LatLng(JSON.parse($.cookie("map_center")));
        if (!isNaN(cookie_map_center.lat()) && !isNaN(cookie_map_center.lng()))
            map_center = cookie_map_center;
    }

    var zoom = 8;
    if ($.cookie("map_zoom") && !isNaN(Number($.cookie("map_zoom")))) zoom = Number($.cookie("map_zoom"));

    var type_id = "hybrid";
    if ($.cookie("map_type") && ($.cookie("map_type") == 'hybrid' || $.cookie("map_type") == 'satellite' || $.cookie("map_type") == 'terrain'))
        type_id = $.cookie("map_type");

    map = new google.maps.Map(document.getElementById('map_canvas'), {
        center: map_center,
        zoom: zoom,
        mapTypeId: type_id

    });

    imageMapType = new google.maps.ImageMapType(
        {
            getTileUrl: function (coord, zoom) {
                // return '/assets/google_tiles/' + zoom + '/' + coord.x + '/' + coord.y + '.png';
            },
            tileSize: new google.maps.Size(256, 256),
            isPng: true,
            alt: "Aero",
            name: "Aero",
            maxZoom: 23
        });
    map.overlayMapTypes.insertAt(0, imageMapType);

    map.addListener('center_changed', function () {
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        var expires = ";expires=" + d.toUTCString();

        document.cookie = "map_center=" + JSON.stringify(map.getCenter().toJSON()) + expires + ";path=/";
        //console.log(document.cookie);
    });
    map.addListener('zoom_changed', function () {
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        var expires = ";expires=" + d.toUTCString();

        document.cookie = "map_zoom=" + map.zoom + expires + ";path=/";
        //console.log(document.cookie);
    });
    map.addListener('maptypeid_changed', function () {
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        var expires = ";expires=" + d.toUTCString();

        document.cookie = "map_type=" + map.getMapTypeId() + expires + ";path=/";
    });

    initialize_markers(arr);
}

function initialize_markers(arr) {
    markers = [];

    for (var i = 0; i < arr.length; i++) {

        var marker = addMarker(arr[i]);

        var infowindow = new google.maps.InfoWindow();
        //открывает infowindows при наведении курсора мыши
        marker.addListener('mouseover', (function (marker, infowindow, info) {
            return function () {
                var img_str = info["image"] != null ? '<div class="div-infowindow"><img class="img-infowindow" src="' + info["image"] + '"></div>' : ""
                infowindow.setContent('<p>' + info["name"] + '</p>' + img_str);
                infowindow.open(map, marker);
            }
        })(marker, infowindow, arr[i]));
        marker.addListener('mousedown', (function (marker, infowindow, info) {
            return function () {
                var img_str = info["image"] != null ? '<div class="div-infowindow"><img class="img-infowindow" src="' + info["image"] + '"></div>' : ""
                infowindow.setContent('<p>' + info["name"] + '</p>' + img_str);
                infowindow.open(map, marker);
            }
        })(marker, infowindow, arr[i]));
        //закрывает infowindows при отведении курсора мыши
        marker.addListener('mouseout', (function (marker, infowindow) {
            return function () {
                infowindow.close(map, marker);
            }
        })(marker, infowindow));
        marker.addListener('click', (function (marker, infowindow, info) {
            return function () {
                window.location.href = "petroglyph/" + info['id'];
            }
        })(marker, infowindow, arr[i]));
    }

    var markerClusterer = new MarkerClusterer(map, markers,
        {
            imagePath: '/assets/js/markerclusterer/images/m',
            maxZoom: 17,
            gridSize: 20,
            styles: null
        }
    );
}

function addMarker(location) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(location["lat"], location["lng"]),
        map: map,
        title: location["name"]
    });

    markers.push(marker);

    return marker;
}