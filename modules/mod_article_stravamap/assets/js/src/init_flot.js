
    var start;
    let locked = false;
    let $flotmarker = $('.graphWrapper .marker');
    let wrapper = document.getElementById("distance-graph");
    const wheatherInterval = 100;
    const rideDataInterval = 1000;
    const flotOptions = {

        xaxis: {
            show: false,
            position: "bottom",
            color: "black",
            axisLabel: "Distanz",
            tickFormatter: kmFormat,
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 10,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 0,
        },
        yaxes: [{
            show: false,
        }],

        grid: {
            show: false,
            borderWidth: 0,
            mouseActiveRadius: 20,
            axisMargin: 0,
            margin: 0
        },
        series: {
            curvedLines: {
                active: true,
            }
        }
    };
    let markerInterval;

    if (wrapper) {
        function getHighestValue(dataSet) {
            if (dataSet) {
                var sortable = [];

                Object.keys(dataSet).forEach(function (key) {
                    sortable.push(parseInt(dataSet[key][1]))
                });
                sortable.sort(function (a, b) {
                    return b - a
                });
                return sortable[0] * 1.1;
            }

        }

        function kmFormat(v, axis) {
            v = v / 1000;
            return v.toFixed(axis.tickDecimals) + " km";
        }

        function kmhFormat(v, axis) {
            v = v * 3.6;
            return v.toFixed(axis.tickDecimals) + " km/h";
        }

        function mFormat(v, axis) {
            v = v;
            return v.toFixed(axis.tickDecimals) + " m";
        }

        function createGraph(selector, data, flotOptions) {
            // if ($(selector).length) {
            if (!data) {
                $("a[data-graph-name='" + selector + "']").addClass('d-none');
            } else {
                $("a[data-graph-name='" + selector + "']").removeClass('d-none');
                var dataset = [
                    {
                        data: data,
                        points: {symbol: "circle", fillColor: "#e51a51", show: false},
                        yaxis: 1,
                        shadowSize: 0,
                        color: '#e51a51',
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 2,
                            fillColor: '#ced4da'
                        },
                        curvedLines: {
                            apply: true,
                            monotonicFit: false
                        }
                    }
                ];

                var flot = $.plot(selector, dataset, flotOptions);
                flot.getAxes().yaxis.options.max = getHighestValue(data);

                // make scroll/touch listeners
                let wrapper = document.getElementById(selector.replace('#', ''));
                wrapper.addEventListener("mousemove", function (e) {
                    if (locked === false) {
                        moveMarker(wrapper, e)
                    }
                });

                wrapper.addEventListener("touchmove", function (e) {
                    moveMarker(wrapper, e)
                });

                wrapper.addEventListener("touchstart", function (e) {
                    moveMarker(wrapper, e)
                });

                wrapper.addEventListener("mousedown", function (e) {
                    if (locked === true) {
                        moveMarker(wrapper, e)
                    }
                    locked = !locked;
                });
            }


        }

        function slideMarker() {
            let counter = start || 0.1;
            let wrapper = document.getElementById("altitude-graph");
            counter = counter + 0.1;
            if (counter > 99.9) {
                counter = 0.1;

            }
            start = counter;
            moveMarker(wrapper, counter);
        }

        function getOffsetPosition(evt, parent) {
            var position = {
                x: (evt.targetTouches) ? evt.targetTouches[0].pageX : evt.clientX,
                y: (evt.targetTouches) ? evt.targetTouches[0].pageY : evt.clientY
            };

            while (parent.offsetParent) {
                position.x -= parent.offsetLeft - parent.scrollLeft;
                position.y -= parent.offsetTop - parent.scrollTop;

                parent = parent.offsetParent;
            }

            return position;
        }


        function moveMarker(el, e) {

            let $flotmarker = $('.graphWrapper .marker');

            if (e.type) {
                var relX = getOffsetPosition(e, el);
                relX = relX.x;
                var parentWidth = $(el).width();
                var latlngCount = map_data.coordinates.length;
                var leftPercent = 100 / (parentWidth / relX);
                var Markerlatlng = map_data.coordinates[Math.floor((latlngCount / 100) * leftPercent)];
                start = leftPercent;
            } else {
                leftPercent = e;
                var latlngCount = map_data.coordinates.length;
                var Markerlatlng = map_data.coordinates[Math.floor((latlngCount / 100) * leftPercent)];
            }

            if (leftPercent > 0 && leftPercent <= 100) {
                $('.detailNumbers .distance').text(getSpeedDataPointValue(leftPercent));
                $('.detailNumbers .watts').text(getWattsDataPointValue(leftPercent));
                $('.detailNumbers .heartrate').text(getHeartrateDataPointValue(leftPercent));
                $('.detailNumbers .altitude').text(getElevationDataPointValue(leftPercent));
                $('.windInfo .temp > span ').text(getTempValue(leftPercent));
                if (map_data.wheather) {
                    $('#wheather .wind .windInfo .windSpeed').text(getWindSpeedValue(leftPercent).toString().replace(/[.]/, ","));
                    $('#wheatherIcon').attr('class', 'fal py-1 ' + getWheatherIcon(leftPercent));
                }

                let windSpeedValuePercent = getWindSpeedValuePercent(leftPercent);
                let windSpeedProgressBar = $('#wheather .wind .windInfo .progress-bar');
                let windSpeedBearing = $('#wheather .wind .windBearing');

                windSpeedProgressBar.css('height', windSpeedValuePercent + '%');
                $('#wheather .wind .windBearing i').css('transform', 'rotate(' + getWindBearingValue(leftPercent));

                if (windSpeedValuePercent > 0) {
                    windSpeedBearing.attr('class', 'windBearing text-success');
                    windSpeedProgressBar.attr('class', 'progress-bar bg-success');
                }
                if (windSpeedValuePercent > 25) {
                    windSpeedBearing.attr('class', 'windBearing text-info');
                    windSpeedProgressBar.attr('class', 'progress-bar bg-info');
                }
                if (windSpeedValuePercent > 50) {
                    windSpeedBearing.attr('class', 'windBearing text-warning');
                    windSpeedProgressBar.attr('class', 'progress-bar bg-warning');
                }
                if (windSpeedValuePercent > 75) {
                    windSpeedBearing.attr('class', 'windBearing text-danger');
                    windSpeedProgressBar.attr('class', 'progress-bar bg-danger');
                }


                window.markerPosition.setPosition(Markerlatlng);
                localStorage.setItem('stravaMapMarkerPos', leftPercent);
                $flotmarker.css({'left': leftPercent + '%'});
            }
        }

        function startMarker() {
            markerInterval = setInterval(slideMarker, 200);
            $('a.pause').removeClass('active');
            $('a.play').addClass('active');
        }

        function stopMarker() {
            clearInterval(markerInterval);
            $('a.play').removeClass('active');
            $('a.pause').addClass('active');
        }

        function getSpeedDataPointValue(leftPercent) {
            let data = map_data.graphs.distance
            if (data.length > 0) {
                return Math.floor((data[Math.floor((data.length / 100) * leftPercent)][1]) * 3.6);
            }
        }

        function getHeartrateDataPointValue(leftPercent) {
            let data = map_data.graphs.heartrate;
            if (data.length > 0) {
                return Math.floor((data[Math.floor((data.length / 100) * leftPercent)][1]));
            }
        }

        function getWattsDataPointValue(leftPercent) {
            let data = map_data.graphs.watts;
            if (data.length > 0) {
                return Math.floor((data[Math.floor((data.length / 100) * leftPercent)][1]));
            }
        }

        function getElevationDataPointValue(leftPercent) {
            let data = map_data.graphs.altitude;
            if (data) {
                return Math.floor((data[Math.floor((data.length / 100) * leftPercent)][1]));
            }
        }


        function getWindSpeedValue(leftPercent) {
            if (map_data.wheather.length > 0) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                return Math.round(map_data.wheather[index].windSpeed * 10) / 10;
            }
        }

        function getTempValue(leftPercent) {
            if (map_data.temp.length > 0) {
                let index = Math.floor((map_data.temp.length / 100) * leftPercent);
                return map_data.temp[index];
            }
        }


        function getWindBearingValue(leftPercent) {
            if (map_data.wheather.length > 0) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                return map_data.wheather[index].windBearing + 180 + 'deg';
            }
        }

        function getWindSpeedValuePercent(leftPercent) {
            if (map_data.wheather.length > 0) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                let windspeed = map_data.wheather[index].windSpeed;
                let windspeedMax = 30;
                let windspeedPercent = windspeed / windspeedMax * 100;
                return Math.floor(windspeedPercent);
            }
        }

        function getWheatherIcon(leftPercent) {
            if (map_data.wheather.length > 0) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                let icon = map_data.wheather[index].icon;
                let iconsMap = {
                    'clear-day': 'fa-sun',
                    'clear-night': 'fa-moon',
                    'rain': 'fa-cloud-rain',
                    'snow': 'fa-cloud-snow',
                    'sleet': 'fa-cloud-sleet',
                    'wind': 'fa-wind',
                    'fog': 'fa-fog',
                    'cloudy': 'fa-clouds',
                    'partly-cloudy-day': 'fa-clouds-sun',
                    'partly-cloudy-night': 'fa-clouds-moon',
                    '01d': 'fa-sun',
                    '01n': 'fa-sun',
                    '02d': 'fa-clouds-sun',
                    '02n': 'fa-clouds-sun',
                    '03d': 'fa-cloud',
                    '03n': 'fa-cloud',
                    '04d': 'fa-clouds',
                    '04n': 'fa-clouds',
                    '09d': 'fa-cloud-showers',
                    '09n': 'fa-cloud-showers',
                    '10d': 'fa-cloud-rain',
                    '10n': 'fa-cloud-rain',
                    '11d': 'fa-thunderstorm',
                    '11n': 'fa-thunderstorm',
                    '13d': 'fa-fog',
                    '13': 'fa-fog'
                };

                return iconsMap[icon];

            }
        }

        // create graphs
        for (let graph_name in map_data.graphs) {
            let graph = map_data.graphs[graph_name];
            createGraph('#' + graph_name + '-graph', graph, flotOptions);
        }


        $('.stravaGraph').on('touchstart mouseenter', function () {
            stopMarker();
        });

        $('a.play').on('click', function () {
            locked = false;
            if (!$(this).hasClass('active')) {
                startMarker();
            }
        });

        $('a.pause').on('click', function () {
            stopMarker();
        });

    }

    // if($(window).width() > 480) {
    //     initMap(map_data);
    //     startMarker();
    //
    // } else {

    // }
    // Change Action

    $('body').on('click', 'a.changeMap:not(.disabled)', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-href');
        var stravaId = $(this).attr('data-strava-id');

        $('.activities a.disabled').removeClass('disabled');
        $('aside').addClass('loading');
        $(this).addClass('disabled');
        $(".postInfos-strava-inner[data-strava-id='" + stravaId + "']").addClass('d-flex');

        var request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.onload = function () {
            if (request.status >= 200 && request.status < 400) {
                // Success!
                map_data = JSON.parse(request.responseText);
                initMap(map_data);

                for (let graph_name in map_data.graphs) {
                    let graph = map_data.graphs[graph_name];
                    $('#' + graph_name + '-graph').empty();
                    createGraph('#' + graph_name + '-graph', graph, flotOptions);
                }

                $(".postInfos-strava-inner[data-strava-id='" + stravaId + "']").removeClass('d-none');
                $(".postInfos-strava-inner[data-strava-id='" + stravaId + "']").addClass('d-flex');

                $(".postInfos-strava-inner[data-strava-id!='" + stravaId + "']").addClass('d-none');
                $(".postInfos-strava-inner[data-strava-id!='" + stravaId + "']").removeClass('d-flex');

                $('#btn-gpxdownload').attr('href', window.location.origin + '/component/roadbikelife/gpxdownload/' + stravaId);
                $('.strava-link').attr('href', 'http://www.strava.com/activities/' + stravaId);


                $('aside').removeClass('loading');
            } else {
                console.log('error loading data');
                $('aside').removeClass('loading');

            }

            map_wrapper = $('#map-wrapper');
            map_wrapper_offset = map_wrapper.offset();
            scroll_offset = map_wrapper_offset.top - (($(window).innerHeight() - map_wrapper.height()) / 2);
            var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);

            if ($(window).width() < 480) {
                if (iOS) {
                    $('body,html').animate({
                        scrollTop: scroll_offset
                    });
                } else {
                    window.scroll({
                        top: scroll_offset,
                        left: 0,
                        behavior: 'smooth'
                    });
                }
            }
        };

        request.onerror = function () {
            // There was a connection error of some sort
        };
        request.send();
    })



