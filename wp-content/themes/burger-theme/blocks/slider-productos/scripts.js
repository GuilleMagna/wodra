jQuery(window).on('resize', function($) {
    $('.productos').css('height', '320');
});

jQuery(function($) {

    $(window).trigger('resize');

    $('.owl-productos').owlCarousel({
        loop:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:true,
        autoWidth:false,
        dots:true,
        margin: 20,
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:1,
            },
            991:{
                items:1,
            }
        }
    })
    
});