jQuery(function($) {

	$('.owl-servicios').on('changed.owl.carousel initialized.owl.carousel', function(event) {
    	$(event.target)
      		.find('.owl-item').addClass('border-end').addClass('border-primary')
      		.eq(event.item.index + event.page.size - 1).removeClass('border-end').removeClass('border-primary');
  		}).owlCarousel({
	    loop:true,
	    autoplay:true,
	    autoplayTimeout:4000,
	    autoplaySpeed:500,
	    responsiveClass:true,
	    nav:false,
	    autoWidth:false,
	    dots:true,
	    margin: 20,
	    navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
	    responsive:{
	        0:{
	            items:1,
	        },
	        768:{
	            items:2,
	        },
	        991:{
	            items:4,
	        }
	    }
	})
    
});