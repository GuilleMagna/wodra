jQuery(function($) {

    $('.owl-conoce-mas').owlCarousel({
        loop:true,
        autoplay:true,
        autoplayTimeout:4000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:false,
        autoWidth:false,
        dots:true,
        margin: 30,
        navText : ['<div class="d-none"><img src="/wp-content/themes/burger-theme/themes/images/arrow-start.svg"></div>','<div class="d-none"><img src="/wp-content/themes/burger-theme/themes/images/arrow-end.svg"></div>'],
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:2,
            },
            991:{
                items:3,
            },
            1200:{
                items:4,
            },
            1400:{
                items:5,
            }
        }
    })

});