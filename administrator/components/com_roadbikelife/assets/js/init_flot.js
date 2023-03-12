$(document).ready(function () {
    function timeConverter(UNIX_timestamp) {
        var a = new Date(UNIX_timestamp * 1000);
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
        return time;
    }


    if ($("#visitChart").length) {
        var dataset = [
            {
                data: chartsJson.VisitChart.data,

                yaxis: 1,
                shadowSize: 0,
                color: '#e51a51',
                bars: {
                    show: true,

                },
            }
        ];


        var options = {

            xaxis: {
                mode: "time",
                show: true,
                position: "bottom",
                color: "black",
                axisLabel: "Zeit",
                ticksize: [1, 'hours'],
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 10,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 0
            },
            yaxes: [{
                show: true,
                axisLabel: "Besucher",
                min: 0,
                tickSize: 1,
                tickDecimals: 0,
            }],

            grid: {
                show: true,
                borderWidth: 1,
                mouseActiveRadius: 20,
                axisMargin: 20,
                margin: 20,
                hoverable: true,
                clickable: true
            },
            series: {
                curvedLines: {
                    active: true,
                }
            }
        };


        var plot = $.plot("#visitChart", dataset, options);

        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("body");

        $("#visitChart").bind("plothover", function (event, pos, item) {

            if (item) {

                var date = new Date(item.datapoint[0] * 1000);
                var hours = date.getHours();
                var minutes = "0" + date.getMinutes();
                var seconds = "0" + date.getSeconds();

                var formattedTime = timeConverter(item.datapoint[0]);
                var
                    y = item.datapoint[1].toFixed(0)

                $("#tooltip").html(y + " Besucher am " + formattedTime)
                    .css({top: item.pageY + 5, left: item.pageX + 5})
                    .fadeIn(200);
            } else {
                $("#tooltip").hide();
            }
        });

        $("#visitChart").bind("plothovercleanup", function (event, pos, item) {
            $("#tooltip").hide();
        });


    }
    ;

    $.plot('#browserChart', chartsJson.BrowserChart.data, {
        series: {
            pie: {
                show: true
            }
        }
    });

    $.plot('#deviceChart', chartsJson.DeviceChart.data, {
        series: {
            pie: {
                show: true
            }
        }
    });

    $.plot('#countryChart', chartsJson.CountryChart.data, {
        series: {
            pie: {
                show: true
            }
        }
    });

    $.plot('#cityChart', chartsJson.CityChart.data, {
        series: {
            pie: {
                show: true
            }
        }
    });


});

