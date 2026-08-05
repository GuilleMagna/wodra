<?php

//Block Name: Zocalo Mensajes

$content_fields = ['imagen_zocalo_mensaje', 'titulo_zocalo_mensajes', 'subtitulo_zocalo_mensajes', 'encabezado', 'boton_zocalo_mensajes', 'estilo_boton_zocalo_mensajes'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

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

<section id="zocalo-mensajes" <?= get_block_wrapper_attributes(['class' => $block_id.' '.$class_container]) ?>>

    <div class="container">

        <div class="row">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                <div class="row justify-content-center">

                    <?php if (!empty($imagen_zocalo_mensaje)): ?>

                        <div class="col-5 col-lg-3 col-xxl-3 mx-auto mx-lg-0 mb-2 mb-md-0 d-flex align-items-center justify-content-center">
                            <img class="img-fluid mb-2 w-75" src="<?php echo $imagen_zocalo_mensaje ?>" alt="<?= esc_html($titulo_zocalo_mensajes . ' ' . $subtitulo_zocalo_mensajes) ?>" data-aos="fade-in">
                        </div>

                    <?php endif ?>

                    <?php if (!empty($titulo_zocalo_mensajes)): ?>

                        <div class="col-12 col-lg-5 col-lg-5 col-xxl-3 mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-lg-start">

                            <div>

                                <<?= $encabezado ?> class="titulo fs-4 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-in" data-aos-delay="200">
                                    <?php echo $titulo_zocalo_mensajes ?> <span><?php echo $subtitulo_zocalo_mensajes ?></span>
                                </<?= $encabezado ?>>

                            </div>

                        </div>

                    <?php endif ?>

                    <?php if (!empty($boton_zocalo_mensajes)): ?>

                        <div class="col-8 col-lg-3 col-lg-4 col-xxl-auto d-flex align-items-center justify-content-center">
                            <?php echo get_burger_button( $boton_zocalo_mensajes, $estilo_boton_zocalo_mensajes ) ?>
                        </div>

                    <?php endif ?>

                </div>

            </div>

        </div>

    </div>

</section>