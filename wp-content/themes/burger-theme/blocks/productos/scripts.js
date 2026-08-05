jQuery(function($) {

    var swiper = new Swiper(".mySwiperData", {
        effect: "cards",
        grabCursor: true,
        //slidesPerView: "auto",
        //spaceBetween: 20,
        //slidesPerView: 1,
        //loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            prevEl: ".swiper-button-prev",
            nextEl: ".swiper-button-next",
        },
        // breakpoints: {
        //     400: {
        //         slidesPerView: 1,
        //     },
        //     768: {
        //         slidesPerView: 1,
        //     },
        //     992: {
        //         slidesPerView: 1,
        //     }
        // }
    });

});