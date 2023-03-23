$(document).ready(function () {
    $('.btn-Likes').each(function (){
        $btnLike = $(this);
        var contentId = $btnLike.attr('data-content-id');
        var type = $btnLike.attr('data-type');

        $btnLike.click(function () {
            $btnLikeClicked = $(this);
            $btnLikeClicked.addClass('liked');
            var number =   $btnLikeClicked.find('.number').text();
            $btnLikeClicked.find('.content').text(parseInt(number) + 1);

            $.ajax({
                url: window.baseUrl + "component/roadbikelife/like/" + type + "/" + contentId,
                method: "GET",
                success: function (data) {
                }
            });
        });
    });


});
