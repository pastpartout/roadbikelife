

$(document).ready(function () {
    

    $('.moreImagesIconWrapper').click(function () {
        $('.post-img-overlay > div').removeClass('align-items-center');
        $('.post-img-overlay > div').addClass('align-items-end');
        $('.content-images').css('overflow','auto');
        $('.galleryImages li.d-none').removeClass('d-none');
        $(this).hide();
    });


});
