<?php 
//Block Name: Galeria

$content_fields = [ 'titulo_galeria', 'subtitulo_galeria', 'encabezado', 'tipo_de_galeria', 'galeria', 'items', 'items_desktop', 'items_tablet', 'items_mobile', 'autoplay', 'nav', 'dots', 'loop', 'margen', 'overflow' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

// En instancias del bloque guardadas antes de que existieran estos campos
// (config del carrusel), ACF no tiene cómo resolver su default_value
// dentro del contexto del bloque, así que hace falta el fallback acá.
$items         = $items !== '' ? (int) $items : 4;
$items_desktop = $items_desktop !== '' ? (int) $items_desktop : 3;
$items_tablet  = $items_tablet !== '' ? (int) $items_tablet : 2;
$items_mobile  = $items_mobile !== '' ? (int) $items_mobile : 1;
$margen        = $margen !== '' ? (int) $margen : 10;
$overflow      = $overflow !== '' ? $overflow : 'visible';
$nav           = $nav !== '' ? (bool) $nav : true;
$dots          = $dots !== '' ? (bool) $dots : false;
$loop          = $loop !== '' ? (bool) $loop : true;
$autoplay      = (bool) $autoplay;

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];

$thumbs_col_class = is_single() ? 'col-lg-2' : 'col-lg-1';
$main_col_class   = is_single() ? 'col-lg-10' : 'col-lg-11';
?>

<style>
    .<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?>; 
        background-image: url('<?= $imagen_fondo ?>')
    }
    .<?= $block_id ?> .titulo,
    .<?= $block_id ?> .color-secundario {
        color:<?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color:<?php echo $color_primario ?>
    }
    .<?= $block_id ?> .owl-single-gal {
        overflow: <?php echo $overflow ?>;
    }
    /* .owl-stage-outer es la ventana que recorta el carrusel: si queda en
       overflow visible, la tira completa de slides sobresale del contenedor y
       genera scroll horizontal en toda la página. */
    .<?= $block_id ?> .owl-single-gal .owl-stage-outer {
        overflow: hidden;
    }
</style>

<section id="galeria" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container .' galeria-block' ] ) ?>>

    <div class="container">

        <<?= $encabezado ?> class="mb-5 mx-auto color-primario <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
            <?php echo $titulo_galeria ?>&nbsp;<span><?php echo $subtitulo_galeria ?></span>
        </<?= $encabezado ?>>

        <?php if( $tipo_de_galeria == 'slider' ): ?>

            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

                <div class="row">

                    <div class="col-12 <?= $thumbs_col_class ?> order-1 order-lg-0 thumbs-col">

                        <button type="button" class="btn btn-secundario rounded-3 w-100 mt-0 d-none d-lg-block thumb-nav thumb-nav-up" <?php if(is_single()): ?> style="padding: 7px !important;"<?php endif ?>>
                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 6.58301L6.58333 0.749675L12.4167 6.58301" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div class="thumbs-container">

                            <div class="carousel-indicators flex-lg-column">

                                <?php if( !empty( $galeria ) and count( $galeria ) > 0 ) foreach( $galeria as $key => $img ): ?>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" class="img img-indicators rounded rounded-4 <?php if( $key==0 ) echo 'active' ?>" data-bs-slide-to="<?php echo $key ?>" <?php if( $key==0 ) echo 'aria-current="true"' ?> aria-label="Slide <?php echo $key+1 ?>" style="background-image: url('<?php echo $img ?>');"></button>
                                <?php endforeach ?>

                            </div>

                        </div>

                        <button type="button" class="btn btn-secundario rounded-3 w-100 mt-0 d-none d-lg-block thumb-nav thumb-nav-down" <?php if(is_single()): ?> style="padding: 7px !important;"<?php endif ?>>
                            <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.4166 0.75L6.58329 6.58333L0.749959 0.749998" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                    </div>

                    <div class="col-12 <?= $main_col_class ?> order-0 order-lg-1 position-relative main-col">

                        <div class="carousel-inner">
                            <?php  if( !empty( $galeria ) and count( $galeria ) > 0 ) foreach( $galeria as $key => $img ): ?>
                                <div class="rounded-4 carousel-item img img-carousel<?php if( $key==0 ) echo ' img active' ?>" style="background-image: url('<?php echo $img ?>');"></div>
                            <?php endforeach ?>
                        </div>
                                
                        <?php if( !empty( $galeria ) and count( $galeria ) > 1 ): ?>

                            <button class="carousel-control-prev h-100" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next h-100" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>

                        <?php endif ?>

                    </div>

                </div>
                
            </div>

        <?php else: ?>

            <div class="owl-carousel owl-single-gal owl-theme">

                <?php if( !empty( $galeria ) and count( $galeria ) > 0 ) foreach( $galeria as $img ): ?>

                    <div class="item">

                        <div class="img galeria-owl rounded-4 mx-auto w-75" style="background-image: url('<?php echo $img ?>')">
                            <a href="<?php echo $img ?>" class="position-absolute h-100 w-100" data-fancybox="galeria"></a>
                        </div>

                    </div>

                <?php endforeach ?>

            </div>

            <script>
                jQuery(function ($) {
                    var $gal = $('.<?= $block_id ?> .owl-single-gal');
                    var totalSlides = $gal.children().length;

                    // Mismos breakpoints que se le pasan a owlCarousel más abajo
                    // (una sola fuente de verdad), ordenados de mayor a menor.
                    var breakpoints = [
                        { width: 1400, items: <?php echo $items ?> },
                        { width: 1000, items: <?php echo $items_desktop ?> },
                        { width: 600,  items: <?php echo $items_tablet ?> },
                        { width: 0,    items: <?php echo $items_mobile ?> },
                    ];

                    function itemsForWidth(width) {
                        for (var i = 0; i < breakpoints.length; i++) {
                            if (width >= breakpoints[i].width) {
                                return breakpoints[i].items;
                            }
                        }
                        return 1;
                    }

                    // Si hay menos imágenes que el "items" del breakpoint actual,
                    // Owl las deja pegadas a la izquierda (no hay nada que
                    // loopear/navegar). En ese caso las centramos y ocultamos las
                    // flechas. No usamos $gal.data('owl.carousel') acá porque Owl
                    // recién lo setea DESPUÉS de que termina de construirse, y
                    // los eventos de abajo se disparan desde adentro del
                    // constructor: en ese momento el data todavía no existe.
                    function centerIfNotEnough() {
                        var currentItems = itemsForWidth($gal.width());
                        $gal.toggleClass('owl-not-enough', totalSlides <= currentItems);
                    }

                    $gal.on('initialized.owl.carousel changed.owl.carousel resized.owl.carousel refreshed.owl.carousel', centerIfNotEnough);
                    $(window).on('resize', centerIfNotEnough);

                    $gal.owlCarousel({
                        margin: <?php echo $margen ?>,
                        responsiveClass: true,
                        nav: <?php echo $nav ? 'true' : 'false' ?>,
                        dots: <?php echo $dots ? 'true' : 'false' ?>,
                        loop: <?php echo $loop ? 'true' : 'false' ?>,
                        autoplay: <?php echo $autoplay ? 'true' : 'false' ?>,
                        navText : ['<img class="d-block" src="<?php echo BURGER_THEME_URL ?>/themes/images/arrow-start.svg">','<img class="d-block" src="<?php echo BURGER_THEME_URL ?>/themes/images/arrow-end.svg">'],
                        responsive: {
                            0: {
                                items: <?php echo $items_mobile ?>,
                                margin: 0,
                            },
                            600: {
                                items: <?php echo $items_tablet ?>,
                            },
                            1000: {
                                items: <?php echo $items_desktop ?>,
                                margin: <?php echo $margen ?>,
                            },
                            1400: {
                                items: <?php echo $items ?>,
                                margin: <?php echo $margen ?>,
                            },
                        }
                    });

                    centerIfNotEnough();
                });
            </script>

        <?php endif ?>

    </div>

</section>