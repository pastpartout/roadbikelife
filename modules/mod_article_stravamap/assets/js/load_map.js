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
})
;
