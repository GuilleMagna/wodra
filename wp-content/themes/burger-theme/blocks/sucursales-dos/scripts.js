
jQuery(document).ready(function($) {
    $('.owl-sucursales').owlCarousel({
      margin: 10,
      responsiveClass: true,
      nav: true,
      dots: false,
      loop: true,
      navText : ['<img src="https://gruposit.wodra.ar/wp-content/themes/burger-theme/themes/images/arrow-start.svg">','<img src="https://gruposit.wodra.ar/wp-content/themes/burger-theme/themes/images/arrow-end.svg">'],
      responsive: {
        0: {
          items: 1,
          margin: 0,
          nav:true,
        },
        600: {
          items: 2,
          nav:true,
        },
        1000: {
          items: 3,
          margin: 20,
        },
        1400: {
          items: 4,
          margin: 20,
        },
      }
    });
  })