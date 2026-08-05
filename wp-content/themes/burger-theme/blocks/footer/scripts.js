jQuery(function($) {

    $('.carousel-empresas').owlCarousel({
        loop:true,
        autoplay:true,
        autoplayTimeout:2000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:true,
        autoWidth:false,
        navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:3,
            },
            991:{
                items:5,
            }
        }
    });

    $('.carousel-equipo').owlCarousel({
        dots:true,
        loop:true,
        autoplay:true,
        autoplayTimeout:4000,
        autoplaySpeed:300,
        responsiveClass:true,
        nav:true,
        autoWidth:false,
        navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        responsive: {
            0:{
                dots: false,
                items: 1,
                slideBy: 1,
            },
            991:{
                dotsEach: 3,
                items: 3,
                slideBy: 3,
            },
            1199:{
                dotsEach: 5,
                items: 5,
                slideBy: 5,
            }
        }

    });

    $('.carousel-imagenes').owlCarousel({
        dots:false,
        loop:true,
        autoplay:true,
        autoplayTimeout:4000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:true,
        autoWidth:false,
        navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:3,
            }
        }
    });

    $('.carousel-servicios').owlCarousel({
        dots:false,
        loop:true,
        autoplay:true,
        autoplayTimeout:40000,
        autoplaySpeed:500,
        responsiveClass:true,
        nav:true,
        autoWidth:false,
        navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:1,
            },
            991:{
                items:3,
            }
        }
    });
    
    function validarEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
  
    function checkFieldsValidity() {

        $('form.wpcf7-form.invalid').each(function(){

            var $form = $(this);

            $form.find('.wpcf7-validates-as-required').each(function(){
                
                var $field = $(this);
                var val = $field.val();

                if(!val || val.trim() === '') {
                    $field.attr('aria-invalid', 'true');
                    return; // si está vacío, no hace falta seguir validando email
                }

                // Si además tiene clase para validar email, validar email
                if($field.hasClass('wpcf7-validates-as-email')) {
                if(!validarEmail(val.trim())){
                    $field.attr('aria-invalid', 'true');
                    return;
                }
                }

                $field.removeAttr('aria-invalid');
            });

        });

    }

    checkFieldsValidity();

    $(document).on('input change', 'form.wpcf7-form.invalid .wpcf7-validates-as-required', function(){
        checkFieldsValidity();
    });

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if(mutation.attributeName === 'class'){
                checkFieldsValidity();
            }
        });
    });

    $('form.wpcf7-form').each(function(){
        observer.observe(this, { attributes: true });
    });
    
});
