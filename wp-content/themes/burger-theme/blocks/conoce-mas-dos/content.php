<?php 
//Block Name: Conoce más dos

$content_fields = [ 'titulo_conoce_mas',  'subtitulo_conoce_mas', 'encabezado', 'lineas', 'height', 'items', 'items_mobile', 'items_desktop', 'margin', 'autoplay', 'nav', 'dots', 'loop', 'overflow' ];
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
    .<?= $block_id ?> #owl-slider {
        overflow: <?php echo $overflow ?>;
    }
    .<?= $block_id ?> #owl-slider #owl-slider-<?php echo $block['id'] ?> {
        overflow: <?php echo $overflow ?>;
    }
    /* .owl-stage-outer es la ventana que recorta el carrusel: si queda en
       overflow visible, la tira completa de slides sobresale del contenedor y
       genera scroll horizontal en toda la página. */
    .<?= $block_id ?> #owl-slider #owl-slider-<?php echo $block['id'] ?> .owl-stage-outer {
        overflow: hidden;
    }
</style>

<section id="conoce-mas-dos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container">

        <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>"> 

            <<?= $encabezado ?> class="titulo fw-bold mb-5 pb-4" data-aos="fade-up">
                <?= $titulo_conoce_mas ?><span><?= $subtitulo_conoce_mas ?></span>
            </<?= $encabezado ?>>
            
        </div>

    </div>

    <?php if( !empty($lineas) && count($lineas) > 0 ) foreach( $lineas as $item ): extract($item) ?>

        <div class="container" style="background-image: url('<?= $fondo ?>'); background-size: cover">

            <div class="row mb-4">

                <div class="col-12 col-md-9">

                    <div class="owl-carousel owl-clientes owl-theme py-5">

                        <?php if( !empty($galeria) && count($galeria) > 0 ) foreach( $galeria as $img ): ?>

                            <div class="item item-clientes">  
                                <div class="d-flex flex-column">
                                    <img loading="lazy" decoding="async" src="<?php echo $img ?>" class="img-fluid" style="max-height: 250px; width: auto" alt="<?= esc_html( $titulo_conoce_mas . ' ' . $subtitulo_conoce_mas ) ?>" title="<?= esc_html( $titulo_conoce_mas . ' ' . $subtitulo_conoce_mas ) ?>">
                                </div>
                            </div>

                        <?php endforeach ?>

                    </div>
                
                </div>

                <div class="col-12 col-md-3">

                    <div class="w-75 mx-auto">

                        <img loading="lazy" decoding="async" src="<?= $logo ?>" class="img-fluid w-100 pt-5 pb-2" alt="<?= esc_html( $titulo_conoce_mas . ' ' . $subtitulo_conoce_mas ) ?>">

                        <?php if( !empty($boton) && !empty($boton['url']) ): ?>

                            <?php
                            echo burger_render_button(
                                [
                                    'url'    => ($boton['url']),
                                    'title'  => ($boton['title']),
                                    'target' => '_self',
                                ],
                                'btn ' . ($estilo_del_boton),
                                [
                                    'span_class'  => 'text-btn-01',
                                ]
                            );
                            ?>

                        <?php endif ?>

                    </div>

                </div>

            </div>

        </div>

    <?php endforeach ?>

</section>