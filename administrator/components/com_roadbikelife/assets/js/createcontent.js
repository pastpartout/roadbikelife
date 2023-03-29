$.noConflict();

jQuery(function ($) {
    $(window).on('load',function(){

   
        $( ".gallery" ).sortable();

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
