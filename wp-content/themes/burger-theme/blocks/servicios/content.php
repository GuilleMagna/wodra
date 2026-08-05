<?php
//Block Name: Servicios

$content_fields = ['titulo_servicios', 'subtitulo_servicios', 'encabezado', 'servicios', 'cantidad_columnas', 'mostrar_soluciones', 'titulo_soluciones', 'boton_soluciones'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

if( empty($cantidad_columnas) ) $cantidad_columnas = 'col-lg-3';

$design = get_block_design($block);
extract($design);

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
        color: <?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?php echo $color_primario ?>
    }
</style>

<section id="servicios" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <?php if (!empty($titulo_servicios)): ?>

        <div class="container py-5">

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="color-primario">
                        <?php echo $titulo_servicios ?> <span class="color-secundario"><?php echo $subtitulo_servicios ?></span>
                    </<?= $encabezado ?>>

                </div>

            </div>

        </div>

    <?php endif ?>

    <?php if( !empty($servicios) and count($servicios) > 0 ): ?>

        <div class="container-fluid px-0">

            <div class="row g-0">

                <?php foreach( $servicios as $key => $item ): extract($item) ?>

                    <div class="col-12 col-md-6 <?= $cantidad_columnas ?>" data-aos="fade-in" data-aos-delay="<?php echo $key ?>00">

                        <div class="card card-servicios border-0 rounded-0 position-relative" style="background-image: url( '<?php echo $fondo ?>'); background-position: center; background-size: cover; background-position: center;">
                            
                            <div class="overlay-servicios-inicio position-absolute h-100 w-100"></div>

                            <div class="overlay-servicios-fin position-absolute h-100 w-100"></div>

                            <div class="card-body position-relative d-flex align-items-end p-4 p-lg-5">

                                <div class="col-12 px-lg-3">

                                    <?php if( $logo ): ?>
                                        <img loading="lazy" decoding="async" class="img-fluid pb-3 py-lg-5" src="<?php echo $logo ?>" alt="<?php echo $titulo ?>">
                                    <?php endif ?>

                                    <h3 class="text-white">
                                        <?php echo $titulo ?>
                                    </h3>

                                    <?php if (!empty($texto)): ?>

                                        <div class="text-white border-bottom border-white pb-4 mb-3">
                                            <?php echo $texto ?>
                                        </div>

                                    <?php endif ?>

                                    <?php echo get_burger_button($boton, $estilo_del_boton ); ?>

                                </div>

                            </div>

                        </div>		

                    </div>

                <?php endforeach ?>

            </div>

        </div>

    <?php endif ?>  
    
    <?php if ($mostrar_soluciones): ?>

        <div class="container mt-4">

            <div class="d-md-flex justify-content-center">

                <div class="d-flex align-items-center mt-5 mt-md-0">

                    <p class="text-big text-dark mb-0 light mp-0 text-center text-md-start">
                        <?php echo $titulo_soluciones ?>
                    </p>

                </div>

                <?php if (!empty($boton_soluciones) and count($boton_soluciones) > 0): ?>

                    <div class="text-center text-md-start mt-3 mt-md-0">
                        <?php echo get_burger_button($boton_soluciones, $estilo ); ?>
                    </div>

                <?php endif ?>

            </div>

        </div>

    <?php endif ?>

</section>