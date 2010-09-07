window.addEvent("domready", function() {
    var carousel = new iCarousel("carousel_content", {
        idPrevious: "carousel_prev",
        idNext: "carousel_next",
        idToggle: "undefined",
        item: {
            klass: "carouselitem_right",
            size: 265
        },
        animation: {
            type: "scroll",
            duration: 700,
            amount: 1
        }
    });
    $$('.carousel_header a').each(function (el,index){
        el.addEvent("click", function(event){
            new Event(event).stop();
            carousel.goTo(index);
            $$('.carousel_header a').removeClass('active');
            $('carousel_link'+index).addClass('active');
        });
    });
});