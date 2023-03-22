$(document).ready(function () {
    const dots = document.querySelector(".frontpage-grid .frontpage-grid-dots");
    const sections = document.querySelectorAll(".frontpage-grid .frontpage-grid-item");
    const options = {rootMargin: '-30px'};
    const nav_prev_el = document.querySelector('.frontpage-grid-dots .page-nav-link-prev');
    const nav_next_el = document.querySelector('.frontpage-grid-dots .page-nav-link-next');

    const sectionsObserver = new IntersectionObserver (function (entries, observer) {
        entries.forEach(function(entry) {
            let id = entry.target.dataset.sectionid;

            if (entry.isIntersecting) {
                let is_first = entry.target.classList.contains('first');
                let is_last = entry.target.classList.contains('last');

                document.querySelector('.frontpage-grid-item.active').classList.remove('active');
                document.querySelector('.frontpage-grid-dots .dot.active').classList.remove('active');
                document.querySelector('#item-'+id).classList.add('active');
                document.querySelector('#dot-'+id).classList.add('active');

                if(is_first === true && nav_prev_el ) {
                    nav_prev_el.classList.add('show');
                }
                if(is_last === true && nav_next_el) {
                    nav_next_el.classList.add('show');
                    nav_next_el.tooltip();
                }
            }
        });
    }, options);

    sections.forEach ( function (sections) {
        sectionsObserver.observe (sections);
    });

    // setTimeout(function() {
    //     document.querySelector('.frontpage-grid-items .btn-next-item').classList.add('show');
    // }, 3000);

    $('.frontpage-grid-items .btn-next-item').on('touchstart click',function() {
        console.log($('.frontpage-grid-dots li:nth-child(2) a'));
        $('.frontpage-grid-dots li:nth-child(2) a')[0].click();
        $(this).removeClass('show');
        return false;
    })

    $('.frontpage-grid-items').on('touchstart',function() {
        $('.btn-next-item').removeClass('show');
    })
});