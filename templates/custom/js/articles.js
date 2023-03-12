

$(document).ready(function () {
    

    $('.moreImagesIconWrapper').click(function () {
        $('.postImageOverlayContent > div').removeClass('align-items-center');
        $('.postImageOverlayContent > div').addClass('align-items-end');
        $('.content-images').css('overflow','auto');
        $('.galleryImages li.d-none').removeClass('d-none');
        $(this).hide();
    });


});
