$(window).on('load', function () {
    if (localStorage.getItem('strava_map_consent') == '1') {
        $('.gdpr-notice').remove();
    }
});
$(document).ready(function () {
    function load_map() {
        $.when(
            $.getScript(gmaps_js_url),
            $.getScript('/modules/mod_article_stravamap/assets/js/dist/flot_addons.js')
        ).done(function () {
            $.when(
                $.getScript('/modules/mod_article_stravamap/assets/js/dist/init.js'),
            ).done(function () {
                localStorage.setItem('strava_map_consent', '1');
                initMap(map_data);
                startMarker();
            });
        });
    }

    if (localStorage.getItem('strava_map_consent') == '1') {
        let fired = false;

        $(window).on('scroll load', function () {
            let hT = $('#googlemap').offset().top,
                hH = $('#googlemap').outerHeight() * -2,
                wH = $(window).height(),
                wS = $(this).scrollTop();
            wB = hT + hH - wH
            if (wS > wB && fired === false) {
                fired = true;
                load_map();
            }
        });
    }

    $(document).on('click', '.gdpr-notice', function (e) {
        if (!$(e.target).hasClass('prevent-event')) {
            load_map();
        }
    });

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

                if(!map_data.wheather) {
                    $('.wheatherWidget').hide();
                } else {
                    $('.wheatherWidget').show();
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
})
;
