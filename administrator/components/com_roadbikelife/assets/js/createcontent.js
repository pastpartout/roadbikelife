$.noConflict();

jQuery(function ($) {
    $(window).on('load',function(){

        if ($('div.image-scroller').length > 0) {

            $('button[role="tab"]').on('click', function (e) {
                let $imageScroller  = $('div.image-scroller');

                if($imageScroller.find('.indicator').length == 0) {
// alert();
                    $imageScroller.imageScroller({
                        preview: '.preview', //Selector for the smaller preview image
                        featureImg: '.feature-image', //Selector for the larger image
                        indicatorText: '' //Text for the drag interface
                    });
                }

                $( ".gallery" ).sortable();

            });
        }

        $( ".btn.up" ).on('click',function(){
            var elm = $(this).closest('li');
            elm.insertBefore(elm.prev());
        });

        $( ".btn.down" ).on('click',function(){
            var elm = $(this).closest('li');
            elm.insertAfter(elm.next());
        });

    });
});
