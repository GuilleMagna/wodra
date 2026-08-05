<?php 
//Block Name: Clientes

$content_fields = [ 'titulo_clientes', 'subtitulo_clientes', 'encabezado', 'dos_por_fila', 'clientes', 'items', 'items_desktop', 'items_tablet', 'items_mobile', 'autoplay', 'nav', 'dots', 'loop', 'margen', 'overflow' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

// En instancias del bloque guardadas antes de que existieran estos campos
// (config del carrusel), ACF no tiene cómo resolver su default_value
// dentro del contexto del bloque, así que hace falta el fallback acá.
$items         = $items !== '' ? (int) $items : 4;
$items_desktop = $items_desktop !== '' ? (int) $items_desktop : 3;
$items_tablet  = $items_tablet !== '' ? (int) $items_tablet : 2;
$items_mobile  = $items_mobile !== '' ? (int) $items_mobile : 4;
$margen        = $margen !== '' ? (int) $margen : 10;
$overflow      = $overflow !== '' ? $overflow : 'visible';
$nav           = $nav !== '' ? (bool) $nav : true;
$dots          = $dots !== '' ? (bool) $dots : true;
$loop          = (bool) $loop;
$autoplay      = (bool) $autoplay;

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];
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
    .<?= $block_id ?> .owl-clientes,
    .<?= $block_id ?> .owl-clientes .owl-stage-outer {
        overflow: <?php echo $overflow ?>;
    }
</style>

<section id="clientes" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

      <?php if(!empty($titulo_clientes)): ?>

          <div class="container py-5">

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="color-primario" data-aos="fade-up">
                        <?php echo $titulo_clientes ?> <span class="color-secundario"><?php echo $subtitulo_clientes ?></span>
                    </<?= $encabezado ?>>

                </div>

            </div>

        </div>

      <?php endif ?>

      <div class="container">

          <div class="row">

              <div class="col-12 mx-auto text-center" data-aos="fade-in">

                  <?php if( $dos_por_fila ): ?>

                        <? if( $dos_por_fila ): 
                            $clientes_dupla = [];
                            foreach ( array_chunk($clientes, 2) as $index => $dupla ) {
                                $item = [];
                                foreach ($dupla as $key => $img ) {
                                    $item[ "imagen_" . $key ] = $img;
                                }
                                $clientes_dupla[] = $item;
                            }
                        endif ?>
                      
                        <div class="owl-carousel owl-clientes owl-theme">

                          <?php foreach( $clientes_dupla as $item ): ?>
                              <div class="item item-clientes">
                                  <div class="d-flex flex-column">
                                      <img src="<?php echo $item['imagen_0'] ?>" class="img-fluid" alt="<?php echo $titulo_clientes . ' ' . $subtitulo_clientes ?>" title="<?= $titulo_clientes . ' ' . $subtitulo_clientes ?>">
                                      <img src="<?php echo $item['imagen_1'] ?>" class="img-fluid" alt="<?php echo $titulo_clientes . ' ' . $subtitulo_clientes ?>" title="<?= $titulo_clientes . ' ' . $subtitulo_clientes ?>">
                                  </div>
                              </div>
                          <?php endforeach ?>

                        </div>

                  <?php else: ?>
                      
                        <div class="owl-carousel owl-clientes owl-theme">

                          <?php foreach( $clientes as $img ): ?>
                              <div class="item item-clientes">  
                                  <div class="d-flex align-items-center">
                                      <img src="<?php echo $img ?>" class="img-fluid" alt="<?= $titulo_clientes . ' ' . $subtitulo_clientes ?>" title="<?= $titulo_clientes . ' ' . $subtitulo_clientes ?>">
                                  </div>
                              </div>
                          <?php endforeach ?>

                        </div>

                  <?php endif ?>

              </div>

          </div>

      </div>

</section>

<script>
    jQuery(function ($) {
        var $gal = $('.<?= $block_id ?> .owl-clientes');
        var totalSlides = $gal.children().length;

        // Mismos breakpoints que se le pasan a owlCarousel más abajo (una
        // sola fuente de verdad), ordenados de mayor a menor para el lookup.
        var breakpoints = [
            { width: 1400, items: <?php echo (int) $items ?> },
            { width: 1000, items: <?php echo (int) $items_desktop ?> },
            { width: 600,  items: <?php echo (int) $items_tablet ?> },
            { width: 0,    items: <?php echo (int) $items_mobile ?> },
        ];

        function itemsForWidth(width) {
            for (var i = 0; i < breakpoints.length; i++) {
                if (width >= breakpoints[i].width) {
                    return breakpoints[i].items;
                }
            }
            return 1;
        }

        // Si hay menos items que el "items" del breakpoint actual, sin loop
        // no hay nada para navegar y Owl los deja pegados a la izquierda.
        // En ese caso los centramos. No usamos $gal.data('owl.carousel')
        // porque Owl recién lo setea DESPUÉS de que termina de construirse,
        // y los eventos de abajo se disparan desde adentro del
        // constructor: en ese momento el data todavía no existe.
        function centerIfNotEnough() {
            var currentItems = itemsForWidth($gal.width());
            $gal.toggleClass('owl-not-enough', totalSlides <= currentItems);
        }

        $gal.on('initialized.owl.carousel changed.owl.carousel resized.owl.carousel refreshed.owl.carousel', centerIfNotEnough);
        $(window).on('resize', centerIfNotEnough);

        $gal.owlCarousel({
            margin: <?php echo (int) $margen ?>,
            responsiveClass: true,
            nav: <?php echo $nav ? 'true' : 'false' ?>,
            dots: <?php echo $dots ? 'true' : 'false' ?>,
            loop: <?php echo $loop ? 'true' : 'false' ?>,
            autoplay: <?php echo $autoplay ? 'true' : 'false' ?>,
            navText : [
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.6667 14.167L15.8334 20.0003L21.6667 25.8337" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.0001 36.6663C10.7954 36.6663 3.33342 29.2043 3.33342 19.9997C3.33342 10.7949 10.7954 3.33301 20.0001 3.33301C29.2048 3.33301 36.6667 10.7949 36.6667 19.9997C36.6667 29.2043 29.2048 36.6663 20.0001 36.6663Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3333 14.167L24.1666 20.0003L18.3333 25.8337" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.9999 36.6663C29.2046 36.6663 36.6666 29.2043 36.6666 19.9997C36.6666 10.7949 29.2046 3.33301 19.9999 3.33301C10.7952 3.33301 3.33325 10.7949 3.33325 19.9997C3.33325 29.2043 10.7952 36.6663 19.9999 36.6663Z" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
            responsive: {
                0: {
                    items: <?php echo (int) $items_mobile ?>
                },
                600: {
                    items: <?php echo (int) $items_tablet ?>
                },
                1000: {
                    items: <?php echo (int) $items_desktop ?>
                },
                1400: {
                    items: <?php echo (int) $items ?>
                }
            }
        });

        centerIfNotEnough();
    });
</script>