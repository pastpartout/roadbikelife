$(document).ready(function () {
    $('.frontpage-grid article').click(function() {
        location.href = $(this).closest('article').attr('data-href');
    })
});