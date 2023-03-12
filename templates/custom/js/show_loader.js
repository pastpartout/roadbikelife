if($('section.product-listing').size() == 0) {
    $('.load1').fadeOut(50, function() {
        $(this).remove();
        $('.show-loader').removeClass('show-loader');
    });
} else {
    $('body').on('table-ready', function(e) {
        $('.load1').fadeOut(50, function() {
            $(this).remove();
            $('.show-loader').removeClass('show-loader');
        });
    });
}




