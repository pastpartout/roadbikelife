function isOnScreen(elem) {
    // if the element doesn't exist, abort
    if( elem.length == 0 ) {
        return;
    }
    var $window = jQuery(window)
    var viewport_top = $window.scrollTop()
    var viewport_height = $window.height()
    var viewport_bottom = viewport_top + viewport_height
    var $elem = jQuery(elem)
    var top = $elem.offset().top
    var height = $elem.height()
    var bottom = top + height

    return (top >= viewport_top && top < viewport_bottom) ||
        (bottom > viewport_top && bottom <= viewport_bottom) ||
        (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
}

function zoomToObject(path, map) {
    var bounds = new google.maps.LatLngBounds();
    var points = path.getPath().getArray();
    for (var n = 0; n < points.length; n++) {
        bounds.extend(points[n]);
    }
    map.fitBounds(bounds);
}


function iosFullscreen(controlDiv, map) {
// Set CSS for the control border.
    const controlUI = document.createElement("div");
    controlUI.style.backgroundColor = "#fff";
    controlUI.style.border = "2px solid #fff";
    controlUI.style.borderRadius = "3px";
    controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
    controlUI.style.marginRight = "10px";
    controlUI.style.marginTop = "10px";
    controlUI.style.lineHeight = "1";
    controlUI.style.height = "40px";
    controlUI.style.padding = "8px 8px 7px";
    controlUI.style.cursor = "pointer";
    controlUI.style.fontSize = "22px";
    controlUI.style.color = "#666";
    controlUI.style.textAlign = "center";

    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    const controlText = document.createElement("div");
    controlUI.innerHTML = "<i class='fal fa-expand'></i>";

    controlUI.appendChild(controlText);

    var body = document.querySelector('body');
    var mapWrapper = document.getElementById('map-wrapper');
    var mapDiv = document.getElementById('googlemap');
    var screenPositionmapWrapper = mapWrapper.getBoundingClientRect();
    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener("click", () => {
        mapWrapper.classList.toggle("fullscreen");
        body.classList.toggle("gmap-fullscreen");

        if (!body.classList.contains('gmap-fullscreen')) {
            mapWrapper.style.height = null
            mapDiv.style.height = null
        } else {
            mapWrapper.style.height = window.innerHeight + 'px';
            mapDiv.style.height = window.innerHeight + 'px';
        }


    });


}

function initMap(data) {

    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
    }
    var infoWindow = new google.maps.InfoWindow();
    var markersOnMap = [];
    var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    var map = new google.maps.Map(document.getElementById('googlemap'), {
        zoom: 4,
        streetViewControl: false,
        fullscreenControl: !isIOS,
        styles: Joomla.gmap_styles_rbl_article
    });


    var routeLine = new google.maps.Polyline({
        path: google.maps.geometry.encoding.decodePath(data.map_polyline),
        geodesic: true,
        strokeColor: '#981539',
        strokeOpacity: 1.0,
        strokeWeight: 2,
        map: map,

    });
    var activeInfoWindow = false;
    var infowindows = {};
    var markerCoords = [];

    if (isIOS === true) {
        const fullScreenControlDiv = document.createElement("div");
        fullScreenControlDiv.classList.add('btn-fullscreen');
        iosFullscreen(fullScreenControlDiv, map);
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(fullScreenControlDiv);
    }

    window.markerPosition = new google.maps.Marker({
        map: map,
        draggable: false,
        icon: {
            url: 'https://www.roadbikelife.de/images/map_markers/map_markers_position.svg?v6',
            scaledSize: new google.maps.Size(20, 20), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(10, 10) // anchor
        }
    });

    let markerHtml = '<span class="stravaMapValueWrapper">Keep rollin\', rollin\', rollin\'</span>';
    bindInfoWindow(window.markerPosition, map, infoWindow, markerHtml);

    // start marker
    var markerStart = new google.maps.Marker({
        position: data.coordinates[0],
        map: map,
        draggable: false,
        icon: {
            url: 'https://www.roadbikelife.de/images/map_markers/map_markers_start.svg',
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(15, 15) // anchor
        }
    });

    var markerEnd = new google.maps.Marker({
        position: data.coordinates[data.coordinates.length - 1],
        map: map,
        draggable: false,
        icon: {
            url: 'https://www.roadbikelife.de/images/map_markers/map_markers_end.svg?v6',
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(15, 15) // anchor
        }
    });

    let controlUIWrapper = document.createElement("div");
    controlUIWrapper.classList.add('btn-group');
    controlUIWrapper.classList.add('btn-group-marker-btns');
    controlUIWrapper.style.marginBottom = "25px";

    let controlUI = document.createElement("div");
    controlUI.classList.add('btn');
    controlUI.classList.add('btn-marker');
    controlUI.classList.add('btn-marker-zoom-out');
    controlUI.style.cursor = "pointer";


    // Set CSS for the control interior.
    let controlText = document.createElement("div");
    controlUI.innerHTML = "<i class='fa fa-search-minus'></i>";
    controlUI.appendChild(controlText);
    controlUIWrapper.appendChild(controlUI);

    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener("click", () => {
        zoomToObject(routeLine, map);
    });


    let i = 0;
    for (let marker_name in data.markers) {
        let marker = data.markers[marker_name];

        if (typeof marker.coordinates !== 'undefined') {
            // move marker one point ahead if its on same position
            if (markerCoords.indexOf(marker.coordinates.lat + ',' + marker.coordinates.lng) >= 0) {

                for (let index in data.coordinates) {
                    let point = data.coordinates[index];
                    // debugger;
                    if (point.lat == marker.coordinates.lat && point.lng == marker.coordinates.lng) {
                        let newPointIndex = parseInt(index) + 1;
                        var newCoords = data.coordinates[newPointIndex];
                    }
                }

                if (typeof (newCoords) !== 'undefined') {
                    marker.coordinates = newCoords;
                }

            } else {
                markerCoords.push(marker.coordinates.lat + ',' + marker.coordinates.lng);
            }

            let markerOnMap = new google.maps.Marker({
                position: marker.coordinates,
                map: map,
                draggable: false,
                icon: {
                    url: 'https://www.roadbikelife.de/images/map_markers/map_markers_' + marker_name + '.svg?v6',
                    scaledSize: new google.maps.Size(30, 30), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(15, 15) // anchor
                }
            });
            bindInfoWindow(markerOnMap, map, infoWindow, marker.text);

            let controlUI = document.createElement("div");
            controlUI.classList.add('btn');
            controlUI.classList.add('btn-marker');
            controlUI.classList.add('btn-marker-' + marker_name);


            // Set CSS for the control interior.
            let controlText = document.createElement("div");
            controlUI.innerHTML = "<img src='https://www.roadbikelife.de/images/map_markers/map_markers_" + marker_name + ".svg?v6'/>";
            controlUI.appendChild(controlText);
            controlUIWrapper.appendChild(controlUI);



            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", function() {
                map.setZoom(13);
                map.panTo(marker.coordinates);
                infoWindow.setContent(marker.text);
                infoWindow.open(map,markerOnMap);
                infoWindow.setPosition(marker.coordinates);
            });
            i++;
        }
    }

    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(controlUIWrapper);


    data.photos.forEach(photo => {
            if (photo.location) {
                var photoMarker = new google.maps.Marker({
                    position: {
                        'lat': photo.location[0],
                        'lng': photo.location[1],
                    },
                    map: map,
                    draggable: false,
                    icon: {
                        url: 'https://www.roadbikelife.de/images/map_markers/map_markers_photo.svg?v3',
                        scaledSize: new google.maps.Size(30, 30), // scaled size
                        origin: new google.maps.Point(0, 0), // origin
                        anchor: new google.maps.Point(15, 15) // anchor
                    }
                })

                let photoMarkerHtml = '<a href="' + photo.urls[1000] + '" data-fancybox="gmapsAcitivityImages"><img src="' + photo.urls[250] + '" class="acitvityMapPhoto"></a>';
                bindInfoWindow(photoMarker, map, infoWindow, photoMarkerHtml);

            }

        }
    );

    if (data.segment_efforts) {

        data.segment_efforts.forEach(segment_effort => {
                if (segment_effort.achievements[0] !== undefined) {

                    let rank = segment_effort.achievements[0].rank;
                    if (rank === 1) {
                        var rankIcon = 'map_markers_rank_' + rank + '.svg';
                    } else {
                        var rankIcon = 'map_markers_rank_x.svg';
                    }

                    var marker = new google.maps.Marker({
                        position: {
                            'lat': segment_effort.segment.end_latlng[0],
                            'lng': segment_effort.segment.end_latlng[1],
                        },
                        map: map,
                        draggable: false,
                        icon: {
                            url: 'https://www.roadbikelife.de/images/map_markers/' + rankIcon,
                            scaledSize: new google.maps.Size(30, 30), // scaled size
                            origin: new google.maps.Point(0, 0), // origin
                            anchor: new google.maps.Point(15, 15) // anchor
                        }
                    })

                    let segmentLine = new google.maps.Polyline({
                        path: google.maps.geometry.encoding.decodePath(segment_effort.segment.map.polyline),
                        geodesic: true,
                        strokeColor: '#f5cd5e',
                        strokeOpacity: 1.0,
                        strokeWeight: 4.2,
                        map: map,
                        position:
                            {
                                'lat': segment_effort.segment.end_latlng[0],
                                'lng': segment_effort.segment.end_latlng[1],
                            }
                    });

                    let markerHtml = '<span class="stravaMapValueWrapper">' + segment_effort.tooltip_content + '</span>';
                    bindInfoWindow(marker, map, infoWindow, markerHtml);
                    bindInfoWindow(segmentLine, map, infoWindow, markerHtml);

                }

            }
        );
    }

    zoomToObject(routeLine, map);

}


