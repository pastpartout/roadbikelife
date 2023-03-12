$(document).ready(function () {
    initMap();
});

var markerGroups = {
    "wind": [],
    "temperature": [],
};
var infoWindow = new google.maps.InfoWindow();
var start;
let locked = false;
var $marker = $('.elevationGraphWrapper .marker');
const wheatherInterval = 100;
const rideDataInterval = 1000;
var isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);

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
    var mapWrapper = document.querySelector('.mapWrapper');
    var mapDiv = document.getElementById('googlemap');
    var screenPositionmapWrapper = mapWrapper.getBoundingClientRect();
    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener("click", () => {
        mapWrapper.classList.toggle("fullscreen" );
        body.classList.toggle("gmap-fullscreen" );

        if(!body.classList.contains('gmap-fullscreen')) {
            mapWrapper.style.height = null
            mapDiv.style.height = null
        } else {
            mapWrapper.style.height = window.innerHeight+'px';
            mapDiv.style.height = window.innerHeight+'px';
        }


    });


}


function initMap() {


    function zoomToObject(path, map) {
        var bounds = new google.maps.LatLngBounds();
        var points = path.getPath().getArray();
        for (var n = 0; n < points.length; n++) {
            bounds.extend(points[n]);
        }
        map.fitBounds(bounds);
    }

    function toggleGroup(type) {
        for (var i = 0; i < markerGroups[type].length; i++) {
            var marker = markerGroups[type][i];
            if (!marker.getVisible()) {
                marker.setVisible(true);
            } else {
                marker.setVisible(false);
            }
        }
    }




    var map = new google.maps.Map(document.getElementById('googlemap'), {
        zoom: 4,
        fullscreenControl: !isIOS,
        mapTypeId: 'terrain',
        streetViewControl: false,
        styles:
            [
                {
                    'featureType': 'all',
                    'elementType': 'labels.text.fill',
                    'stylers': [
                        {
                            'saturation': 36
                        },
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': '89'
                        }
                    ]
                },
                {
                    'featureType': 'all',
                    'elementType': 'labels.text.stroke',
                    'stylers': [
                        {
                            'visibility': 'on'
                        },
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': 16
                        }
                    ]
                },
                {
                    'featureType': 'all',
                    'elementType': 'labels.icon',
                    'stylers': [
                        {
                            'visibility': 'off'
                        }
                    ]
                },
                {
                    'featureType': 'administrative',
                    'elementType': 'geometry.fill',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': '20'
                        }
                    ]
                },
                {
                    'featureType': 'administrative',
                    'elementType': 'geometry.stroke',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': 17
                        },
                        {
                            'weight': 1.2
                        }
                    ]
                },
                {
                    'featureType': 'landscape',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'color': '#1d262d'
                        },
                        {
                            'lightness': '1'
                        },
                        {
                            'saturation': '0'
                        }
                    ]
                },
                {
                    'featureType': 'poi',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'visibility': 'off'
                        },
                        {
                            'color': '#71807c'
                        }
                    ]
                },
                {
                    'featureType': 'road.highway',
                    'elementType': 'geometry.fill',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': '27'
                        }
                    ]
                },
                {
                    'featureType': 'road.highway',
                    'elementType': 'geometry.stroke',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': 29
                        },
                        {
                            'weight': 0.2
                        }
                    ]
                },
                {
                    'featureType': 'road.arterial',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': '37'
                        }
                    ]
                },
                {
                    'featureType': 'road.local',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': '22'
                        }
                    ]
                },
                {
                    'featureType': 'transit',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'color': '#000000'
                        },
                        {
                            'lightness': 19
                        }
                    ]
                },
                {
                    'featureType': 'water',
                    'elementType': 'geometry',
                    'stylers': [
                        {
                            'color': '#558fa2'
                        },
                        {
                            'lightness': 17
                        }
                    ]
                }
            ]
    });

    if(isIOS === true) {
        const fullScreenControlDiv = document.createElement("div");
        fullScreenControlDiv.classList.add('btn-fullscreen');
        iosFullscreen(fullScreenControlDiv, map);
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(fullScreenControlDiv);
    }

    var routeLine = new google.maps.Polyline({
        path: map_data.coordinatesJson,
        geodesic: true,
        strokeColor: '#e51a51',
        strokeOpacity: 1.0,
        strokeWeight: 2,
        map: map,

    });

    window.markerPosition = new google.maps.Marker({
        map: map,
        draggable: false,
        icon: {
            url: 'https://static.pastpartout.com/images/googlemaps/map_markers_position.svg?v6',
            scaledSize: new google.maps.Size(20, 20), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(10, 10) // anchor
        }
    });

    window.markerPosition.setMap(map);


    var markerStart = new google.maps.Marker({
        position: map_data.coordinatesStart,
        map: map,
        draggable: false,
        icon: {
            url: 'https://static.pastpartout.com/images/googlemaps/map_markers_start.svg',
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(15, 15) // anchor
        }
    });
    markerStart.setMap(map);

    var markerEnd = new google.maps.Marker({
        type: "windBearing",
        position: map_data.coordinatesEnd,
        map: map,
        draggable: false,
        icon: {
            url: 'https://static.pastpartout.com/images/googlemaps/map_markers_end.svg?v6',
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0, 0), // origin
            anchor: new google.maps.Point(15, 15) // anchor
        }
    });


    map_data.wheather.forEach((item, index, arr) => {
            if (item.lat > 0 && index > 0 && index < map_data.wheather.length - 1) {

                var windSpeed = item.data.windSpeed;

                if (windSpeed < 7.5) {
                    fillColor = '#65cc86';
                }

                if (windSpeed > 7.5) {
                    fillColor = '#23a3cc';
                }

                if (windSpeed > 15) {
                    fillColor = '#ccb74d';
                }

                if (windSpeed > 22.5) {
                    fillColor = '#cc3f52'
                }


                var arrow = {
                    path: 'M2.6,15.7c-0.6-0.6-0.6-1.4,0-2L14,2.3c0.6-0.6,1.4-0.6,2,0l11.4,11.4c0.6,0.6,0.6,1.4,0,2L26.1,17c-0.6,0.6-1.5,0.5-2,0 l-6.7-7.1v16.9c0,0.8-0.6,1.4-1.4,1.4h-1.9c-0.8,0-1.4-0.6-1.4-1.4V9.9l-6.7,7.1c-0.5,0.6-1.5,0.6-2,0L2.6,15.7z',
                    fillColor: fillColor,
                    fillOpacity: 1,
                    scale: 0.7,
                    strokeWeight: 0,
                    scaledSize: new google.maps.Size(15, 15), // scaled size
                    origin: new google.maps.Point(15, 15), // origin
                    anchor: new google.maps.Point(15, 15), // anchor
                    rotation: item.data.windBearing + 180,

                };

                var position = {
                    'lat': item.lat,
                    'lng': item.lng,
                };

                createMarker(position, arrow, '', 'Wind: ' + item.data.windSpeed + ' km/h', 'wind', true, map);

                var tempColor = '';
                var temp = parseInt(item.data.temperature);

                if (temp < 10) {
                    tempColor = '#23a3cc'
                }

                if (temp > 10) {
                    tempColor = '#65cc86'
                }

                if (temp > 20) {
                    tempColor = '#ccb74d'
                }

                if (temp > 25) {
                    tempColor = '#cc3f52'
                }

                var iconTemp = {
                    path: 'M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z',
                    fillColor: tempColor,
                    fillOpacity: 0.8,
                    scale: 0.02,
                    strokeWeight: 0,

                };

                createMarkerTemp(position, iconTemp, '', item.data.temperature + ' °C', 'temperature', false, map);
            }

        }
    );


    zoomToObject(routeLine, map);

    $('[data-toggle-mapicons]').click(function () {
        toggleGroup($(this).attr('data-toggle-mapicons'));
    })

    $('.navGraphs [data-toggle="tab"]').click(function () {
        let wheatherKey = $(this).attr('data-wheather-key');
        $('.detailNumbers [data-wheather-key]').removeClass('active');
        $('.detailNumbers [data-wheather-key="'+wheatherKey+'"]').addClass('active');
    })


}

function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'click', function () {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);

    });
}

function createMarker(point, icon, name, text, type, visible, map) {
    var marker = new google.maps.Marker({
        map: map,
        position: point,
        icon: icon,
        // shadow: icon.shadow,
    });
    if (!markerGroups[type]) markerGroups[type] = [];
    markerGroups[type].push(marker);
    var html = '<div class="py-2 px-3">'+text+'</div>';


    if (text != '') {
        bindInfoWindow(marker, map, infoWindow, html);
    }

    return marker;
}

function createMarkerTemp(point, icon, name, text, type, visible, map) {
    var marker = new MarkerWithLabel({
        map: map,
        visible: visible,
        // animation: google.maps.Animation.DROP,
        position: point,
        icon: icon,
        labelContent: text,
        labelInBackground: true,
        labelClass: "badge badge-light ml-2 mt-n2 label-" + type,
        labelAnchor: point,
    });
    if (!markerGroups[type]) markerGroups[type] = [];
    markerGroups[type].push(marker);
    var html = text;


    if (text != '') {
        bindInfoWindow(marker, map, infoWindow, html);
    }

    return marker;
}




