
jQuery(document).ready(function($) {
    $('.owl-sucursales').owlCarousel({
      margin: 10,
      responsiveClass: true,
      nav: true,
      dots: false,
      loop: true,
      navText: ["<svg aria-hidden=\"true\" width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"> <g opacity=\"0.5\"> <path d=\"M21.6666 14.1666L15.8333 20L21.6666 25.8333\" stroke=\"#504D50\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/> <path d=\"M20 36.6667C10.7953 36.6667 3.33329 29.2047 3.33329 20C3.33329 10.7953 10.7953 3.33337 20 3.33337C29.2047 3.33337 36.6666 10.7953 36.6666 20C36.6666 29.2047 29.2047 36.6667 20 36.6667Z\" stroke=\"#504D50\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/> </g> </svg> ","<svg aria-hidden=\"true\" width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"> <g opacity=\"0.5\"> <path d=\"M18.3333 14.1666L24.1667 20L18.3333 25.8333\" stroke=\"#504D50\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/> <path d=\"M20 36.6667C29.2047 36.6667 36.6667 29.2047 36.6667 20C36.6667 10.7953 29.2047 3.33337 20 3.33337C10.7952 3.33337 3.33333 10.7953 3.33333 20C3.33333 29.2047 10.7952 36.6667 20 36.6667Z\" stroke=\"#504D50\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/> </g> </svg> "],
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