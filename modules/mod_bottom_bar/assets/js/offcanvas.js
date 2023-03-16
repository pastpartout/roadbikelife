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


    $('.mobileBottomBarToggle,.mobileBottomBarBackdrop').on('click touchstart',function() { // Klick auf den Button
        $('.mobileBottomBarBackdrop').removeClass('show');
        $('.mobileBottomBar').removeClass('expand');
        $('.nav-item.active').removeClass('active');

    });

    $('.mobileBottomBar a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $('.mobileBottomBar').addClass('expand');
        $('.mobileBottomBarBackdrop').addClass('show');

    })

});