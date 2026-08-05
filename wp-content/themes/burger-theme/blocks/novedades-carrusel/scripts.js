jQuery(function($) {

    $('.owl-novedades').owlCarousel({
        loop:false,
        autoplay:true,
        autoplayTimeout:4000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:false,
        autoWidth:false,
        dots:true,
        margin: 30,
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:2,
            },
            991:{
                items:3,
            }
        }
    })
    
});