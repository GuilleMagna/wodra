jQuery(function($) {

    $('.owl-team').owlCarousel({
      margin: 20,
      responsiveClass: true,
      nav: true,
      dots: true,
      loop: true,
      navText : [
        '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.6667 14.167L15.8334 20.0003L21.6667 25.8337" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.0001 36.6663C10.7954 36.6663 3.33342 29.2043 3.33342 19.9997C3.33342 10.7949 10.7954 3.33301 20.0001 3.33301C29.2048 3.33301 36.6667 10.7949 36.6667 19.9997C36.6667 29.2043 29.2048 36.6663 20.0001 36.6663Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3333 14.167L24.1666 20.0003L18.3333 25.8337" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.9999 36.6663C29.2046 36.6663 36.6666 29.2043 36.6666 19.9997C36.6666 10.7949 29.2046 3.33301 19.9999 3.33301C10.7952 3.33301 3.33325 10.7949 3.33325 19.9997C3.33325 29.2043 10.7952 36.6663 19.9999 36.6663Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
      ],
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