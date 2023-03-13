$(document).ready(function () {
    $('.frontpage-grid article').click(function() {
        location.href = $(this).closest('article').attr('data-href');
    });


    const dots = document.querySelector(".frontpage-grid .frontpage-grid-dots");
    const jadeScale = document.querySelectorAll(".frontpage-grid .frontpage-grid-item");
    const options = {rootMargin: '-30px'};

    const jadeScaleObserver = new IntersectionObserver (function (entries, observer) {

        entries.forEach(function(entry) {
            let id = entry.target.dataset.sectionid;

            if (entry.isIntersecting) {
                document.querySelector('.frontpage-grid-item.active').classList.remove('active');
                document.querySelector('.frontpage-grid-dots .dot.active').classList.remove('active');
                document.querySelector('#item-'+id).classList.add('active');
                document.querySelector('#dot-'+id).classList.add('active');
                // let scrollOffset =  document.querySelector('#dot-'+id).offsetTop - 60;
                // if(scrollOffset <= 0) scrollOffset = 0;
                // document.querySelector('.frontpage-grid-dots').scrollTop = scrollOffset
            } else {

            }
        });
    }, options);

    jadeScale.forEach ( function (jadeScale) {
        jadeScaleObserver.observe (jadeScale);
    });
});