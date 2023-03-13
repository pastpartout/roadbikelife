
$(document).ready(function () {
    var analyticsId = $('html').attr('data-gaid');

    // function loadGAonConsent() {
    //     window.ga = window.ga || function () {
    //         (ga.q = ga.q || []).push(arguments)
    //     };
    //     ga.l = +new Date;
    //     ga('create', analyticsId, 'auto');
    //     ga('set', 'anonymizeIp', true);
    //     ga('send', 'pageview');
    //     var gascript = document.createElement("script");
    //     gascript.async = true;
    //     gascript.src = "https://www.google-analytics.com/analytics.js";
    //     document.getElementsByTagName("head")[0].appendChild(gascript, document.getElementsByTagName("head")[0]);
    // }

    // gdprCookieNotice({
    //     locale: $('html').attr('lang').substr(0, 2), //This is the default value
    //     timeout: 0, //Time until the cookie bar appears
    //     expiration: 360, //This is the default value, in days
    //     implicit: false, //Accept cookies on page scroll automatically
    //     statement: $('html').attr('data-baseurl') + 'datenschutzerklaerung', //Link to your cookie statement page
    //     performance: ['JSESSIONID'], //Cookies in the performance category.
    //     analytics: ['ga', '_gat'], //Cookies in the analytics category.
    //     marketing: ['SSID'], //Cookies in the marketing category.
    // });

    // document.addEventListener('gdprCookiesEnabled', function (e) {
    //     if (e.detail.analytics) { //checks if marketing cookies are enabled
    //         loadGAonConsent();
    //     }
    // });

    // setTimeout(function () {
    //     $('html').removeClass('gdpr-cookie-notice-loaded');
    // }, 10000);
    //
    // $(document).on('click', '.gdpr-cookie-notice .close', function () {
    //     $('html').removeClass('gdpr-cookie-notice-loaded');
    // });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    var back_to_top_button = ['<a href="#top" class="btn btn-lg btn-outline-primary back-to-top">' +
    '<i class="fa fa-angle-up"></i><span class="sr-only">Nach oben</span> </a>'].join("");
    $("body").append(back_to_top_button)

    // Der Button wird ausgeblendet
    $(".back-to-top").addClass('fade out');

    // Funktion für das Scroll-Verhalten
    $(function () {

        if(screen.width > 480 ) {
            $('.mobileBottomBar').addClass('in');
        } else {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) { // Wenn 100 Pixel gescrolled wurde
                    $('.back-to-top,.mobileBottomBar').removeClass('out');
                    $('.back-to-top,.mobileBottomBar').addClass('in');

                } else {
                    $('.back-to-top,.mobileBottomBar').removeClass('in');
                    $('.back-to-top,.mobileBottomBar').addClass('out')
                }
            });
        }


        $('.back-to-top').click(function () { // Klick auf den Button
            $('body,html').scrollTop(0);
            return false;
        });

        $('.btnToBlog').click(function () { // Klick auf den Button
            $('body,html').animate({
                scrollTop: $('.blog').offset().top
            }, 500);
            return false;
        });

    });

    $('.analytics-opt-out').click(function () { // Klick auf den Button
        window['ga-disable-'+analyticsId] = true;
    });

    $('.mobileBottomBarToggle').on('click touchstart',function() { // Klick auf den Button
        $('.mobileBottomBar').removeClass('expand');
        // $('a[data-toggle="tab"]').
        $('.nav-item.active').removeClass('active');

    });

    $('.mobileBottomBar a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $('.mobileBottomBar').addClass('expand');
    })



    // var tabEl = document.querySelector('button[data-bs-toggle="tab"]')
    // tabEl.addEventListener('shown.bs.tab', function (event) {
    //   event.target // newly activated tab
    //   event.relatedTarget // previous active tab
    // })

    Modernizr.Detectizr.detect({detectDevice: true});
    var device = Modernizr.Detectizr.device.type;
    var browser = Modernizr.Detectizr.device.browser;

    if (device != "desktop") {
        $('html').addClass('mobile');
        $('#mainnav li a').click(function () {
            document.location.href = $(this).attr('href');
            return false;
        });
    };

    if (device === "tablet") {
        $('html').addClass('tablet');
    };



    $('.messageWrapper .close').click(function() {
        $('.messageWrapper').remove();
    })



    // Youtube Data Privacy
    $('body').on('click', '.avPlayerWrapper ', function () {
        $(this).find('.data-privacy-info--text').removeClass('out').addClass('in');
    })

    $('body').on('click', '.btn-play-yt-video', function () {
        $closesContainer = $(this).closest('.avPlayerContainer');
        if ($closesContainer.find('iframe').length < 1) {

            let iframe = $('<iframe/>')
                .attr('src', $(this).attr('data-href'))
                .attr('frameborder', '0')
                .attr('allowscriptaccess', 'always')
                .attr('allowfullscreen', 'true')
                .attr('enablejsapi', '1')
                .attr('allow', 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture');
            $closesContainer.append(iframe);
        }
    });
});