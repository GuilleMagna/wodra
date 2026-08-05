<?php 
//Block Name: Hero Slider

$content_fields = [ 'slide', 'height', 'items', 'items_mobile', 'items_tablet', 'items_desktop', 'margin', 'autoplay', 'nav', 'dots', 'loop', 'overflow' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

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
</style>

<section id="hero-slider" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' section' ] ) ?>>
      
    <div id="carouselPrincipal" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">

        <div class="carousel-inner">

            <?php if( !empty($slide) && count($slide) > 0 ) foreach( $slide as $key => $item ): extract( $item ) ?>
                
                <div class="carousel-item<?php if( $key == 0 ) echo ' active'; ?>" style="background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(79, 72, 66, 0.5) 100%), url('<?php echo $imagen_slider ?>');">
                    
                    <div class="container vh-100">

                        <div class="row vh-100">

                            <div class="d-flex align-items-center text-start my-4 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                                <div data-aos="fade-in">
                                        
                                    <?php if( $logo_slider ): ?>
                                        <img src="<?php echo $logo_slider ?>" class="logo-slider" alt="<?php echo $texto_slider ?>">
                                    <?php endif ?>
                            
                                    <?php if( $texto_slider ): ?>
                                        
                                        <div class="slide color-primario">
                                            <?php echo $texto_slider; ?>
                                        </div>

                                    <?php endif ?>

                                    <div class="me-5" data-aos="fade-in">

                                        <?php if (!empty($botones) && count($botones) > 0): ?>

                                            <? foreach ($botones as $item): extract($item); ?>

                                                <?php echo get_burger_button( $boton, $estilo ) ?> 

                                            <? endforeach ?>

                                        <?php endif ?>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>   

        </div>
        
        <!-- <div class="scroll-icon-content">
            <span class="scroll-icon">
                <span class="scroll-icon__dot"></span>
            </span>
        </div> -->
        
        <?php if( count($slide) > 1 ): ?>
                
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carouselPrincipal" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <div class="carousel-indicators">

                <?php foreach( $slide as $key => $item ): ?>
                    <button type="button" data-bs-target="#carouselPrincipal" data-bs-slide-to="<?php echo $key; ?>"<?php if( $key==0) echo ' class="active" aria-current="true"'; ?> aria-label="Slide <?php echo $key+1; ?>"></button>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

    <script>
        jQuery(document).ready(function($) {
            
            $('#owl-slider-<?php echo $block['id'] ?>').owlCarousel({
                center: true,
                loop: <?php echo $loop ?>, 
                items: <?php echo $items ?>, 
                margin: <?php echo $margin ?>, 
                autoplay: <?php echo $autoplay ?>,
                nav: <?php echo $nav ?>,
                dots: <?php echo $dots ?>,
                navText: ['<span class="carousel-control-prev-icon" aria-hidden="true"></span>','<span class="carousel-control-next-icon" aria-hidden="true"></span>'],
                responsive: {
                    0: {
                        items: <?php echo $items_mobile ?>
                    },
                    600: {
                        items: <?php echo $items_tablet ?>
                    },
                    1000: {
                        items: <?php echo $items_desktop ?>
                    }
                }
            });
            
        });
    </script>

</section>