jQuery(function($) {

    $('.owl-team').owlCarousel({
      margin: 20,
      responsiveClass: true,
      nav: true,
      dots: true,
      loop: true,
      navText : ['<img src="https://gruposit.wodra.ar/wp-content/themes/burger-theme/themes/images/arrow-start.svg">','<img src="https://gruposit.wodra.ar/wp-content/themes/burger-theme/themes/images/arrow-end.svg">'],
      responsive: {
        0: {
          items: 2,
          margin: 10,
        },
        600: {
          items: 3,
        },
        800: {
          items: 3,
        },
        1000: {
          items: 4,
          margin: 20,
        },
        1400: {
          items: 6,
          margin: 20,
        }
      }
    });
    
  })