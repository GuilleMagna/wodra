jQuery(function($) {

  $('.owl-logos-footer').owlCarousel({
    margin: 10,
    responsiveClass: true,
    nav: true,
    dots: false,
    loop: false,
    navText: ['<svg width="9" height="19" viewBox="0 0 9 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.24997 17.42L1.72997 10.9C0.959966 10.13 0.959966 8.87002 1.72997 8.10002L8.24997 1.58002" stroke="var(--primary)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>', '<svg width="10" height="19" viewBox="0 0 10 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.65991 17.42L8.17991 10.9C8.94991 10.13 8.94991 8.87002 8.17991 8.10002L1.65991 1.58002" stroke="var(--primary)" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
    responsive: {
      0: {
        items: 2,
        margin: 0,
        dots: true,
        nav: false,
      },
      600: {
        items: 2,
        dots: true,
        nav: false,
      },
      1000: {
        items: 3,
        margin: 20,
      },
      1400: {
        items: 3,
        margin: 20,
      },
    }
  });

})