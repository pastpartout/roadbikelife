$(document).ready(function () {

    var wrapper = document.getElementById("temperature-graph");


    if (wrapper) {
        function kmFormat(v, axis) {
            v = v / 1000;
            return v.toFixed(axis.tickDecimals) + " km";
        }

        function precipFormat(v, axis) {
            return v.toFixed(0) + " %";
        }

        function tempFormat(v, axis) {
            return v.toFixed(2) + " °C";
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

        function slideMarker() {
            let counter = start || 0.1;
            counter = counter + 0.1;
            if (counter > 99.9) {
                counter = 0.1;

            }
            start = counter;
            moveMarker(wrapper, counter);
        }
        
        function getFlotDataPointValue(leftPercent, map_data, index) {
            if (map_data['flot'][index]) {
                // console.log(map_data['flot'][index][Math.floor((map_data['flot'][index] / 100) * leftPercent)]);
                // console.log();
                // debugger;
                let counterIndex = Math.floor((map_data['flot'][index].length / 100) * leftPercent);
                return Math.floor((map_data['flot'][index][counterIndex][1]));
            }
        }



        function moveMarker(el, e) {

            let $marker = $('.graphWrapper .marker');

            if (e.type) {
                var relX = getOffsetPosition(e, el);
                relX = relX.x;
                var parentWidth = $(el).width();
                var latlngCount = map_data.coordinatesJson.length;
                var leftPercent = 100 / (parentWidth / relX);
                var Markerlatlng = map_data.coordinatesJson[Math.floor((latlngCount / 100) * leftPercent)];
                start = leftPercent;
            } else {
                leftPercent = e;
                var latlngCount = map_data.coordinatesJson.length;
                var Markerlatlng = map_data.coordinatesJson[Math.floor((latlngCount / 100) * leftPercent)];
            }

            if (leftPercent > 0 && leftPercent <= 100) {
                for (var index in graph_types) {
                    if ($("#" + index + '-graph').length) {
                        $('.detailNumbers .'+index).text(getFlotDataPointValue(leftPercent, map_data,index));
                        // debugger;

                    }
                }


                $('#wheather .wind .windInfo .windSpeed').text(getWindSpeedValue(leftPercent, map_data).toString().replace(/[.]/, ","));
                $('#wheatherIcon').attr('class', 'fal py-1 ' + getWheatherIcon(leftPercent, map_data));

                let windSpeedValuePercent = getWindSpeedValuePercent(leftPercent, map_data);
                let windSpeedProgressBar = $('#wheather .wind .windInfo .progress-bar');
                let windSpeedBearing = $('#wheather .wind .windBearing');

                windSpeedProgressBar.css('height', windSpeedValuePercent + '%');
                $('#wheather .wind .windBearing i').css('transform', 'rotate(' + getWindBearingValue(leftPercent, map_data));

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
                $marker.css({'left': leftPercent + '%'});
            }
        }


        let markerInterval;

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

        startMarker();

        function getWindSpeedValue(leftPercent, map_data) {
            if (map_data.wheather) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                return Math.round(map_data.wheather[index].data.windSpeed * 10) / 10;
            }
        }

        function getWindBearingValue(leftPercent, map_data) {
            if (map_data.wheather) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                return map_data.wheather[index].data.windBearing + 180 + 'deg';
            }
        }

        function getWindSpeedValuePercent(leftPercent, map_data) {
            if (map_data.wheather) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                let windspeed = map_data.wheather[index].data.windSpeed;
                let windspeedMax = 30;
                let windspeedPercent = windspeed / windspeedMax * 100;
                return Math.floor(windspeedPercent);

            }
        }

        function getWheatherIcon(leftPercent, map_data) {
            if (map_data.wheather) {
                let index = Math.floor((map_data.wheather.length / 100) * leftPercent);
                return map_data.wheather[index].data.icon;
            }
        }

        var graph_types = map_data.graph_types;

        for (var index in graph_types) {

            let flotData = map_data['flot'][index];

            if ($("#" + index + '-graph').length) {
                let dataset = [
                    {
                        data: flotData,
                        yaxis: 1,
                        shadowSize: 0,
                        color: '#e51a51',
                        lines: {
                            show: true,
                            fill: false,
                            lineWidth: 3,
                            fillColor: '#fff'
                        },
                        curvedLines: {
                            apply: true,
                            monotonicFit: false
                        }
                    }
                ];


                let options = {

                    xaxis: {
                        show: false,

                    },
                    yaxes: [{
                        show: false,
                    }],

                    grid: {
                        show: false,
                        borderWidth: 1,
                        mouseActiveRadius: 0,
                        axisMargin: 0,
                        margin: 0,
                        color: "rgba(0,0,0,0.3)",

                    },
                    series: {
                        curvedLines: {
                            active: true,
                        }
                    }
                };

                $.plot($("#" + index + '-graph'), dataset, options);

                let wrapper = document.getElementById(index + '-graph');
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
            ;
        }

        if ($("#temperature-rain-graph").length) {
            let dataset = [
                {
                    data: map_data.flot.precipProbability,
                    yaxis: 1,
                    shadowSize: 0,
                    color: '#e51a51',
                    bars: {
                        show: true,
                        fill: true,
                        line:false,
                        lineWidth: 0,
                        fillColor: 'rgba(50,188,245,0.55)',
                        fillOpacity:  0.3,
                    },

                }
            ];


            let options2 = {

                xaxis: {
                    show: true,
                    position: "bottom",
                    tickFormatter: kmFormat,
                    axisLabelUseCanvas: false,
                    axisLabelPadding: 0,
                    color: "rgba(0,0,0,0.3)",
                    font:{
                        size:11,
                    }

                },
                yaxes: [

                    {
                        show: true,
                        position: "right",
                        color: "rgba(0,0,0,0.3)",
                        axisLabelUseCanvas: false,
                        axisLabelPadding: 3,
                        max: 100,
                        min:0,
                        autoScale: false,
                        tickFormatter: precipFormat,
                        font:{
                            size:11,
                        }

                    },

                ],

                grid: {
                    show: true,
                    borderWidth: 1,
                    mouseActiveRadius: 20,
                    axisMargin: 0,
                    margin: 0,
                    color: "rgba(0,0,0,0.3)",

                },
                series: {
                    curvedLines: {
                        active: true,
                    }
                }
            };

            $.plot($("#temperature-rain-graph"), dataset, options2);

        }
        ;


    }
});

