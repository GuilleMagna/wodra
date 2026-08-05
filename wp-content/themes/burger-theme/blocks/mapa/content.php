<?php
//Block Name: Mapa

$content_fields = ['titulo_mapa', 'subtitulo_mapa', 'encabezado', 'logo_mapa', 'trama_mapa', 'imagen_mapa', 'contenido_mapa', 'mostrar_boton_mapa', 'botones_mapa'];
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
        background-image: url('<?= $imagen_fondo ?>');
        background-repeat: no-repeat;
        background-size: cover;
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

<section id="mapa" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="row d-flex justify-content-start justify-content-md-center">

            <div class="col-12 col-md-12 col-lg-6 ms-md-auto position-relative">
                <img src="<?php echo $imagen_mapa ?>" class="img-fluid w-100" alt="imagen_mapa" title="mapa"
                    data-aos="fade-right">
            </div>

            <div class="col-12 col-md-12 col-lg-6 my-auto p-mapa pt-5 pt-md-0" data-sal="fade" data-sal-duration="500">

                <div class="d-flex flex-column py-0 py-md-5">

                    <img class="mb-4 w-50 h-auto" src="<?php echo $logo_mapa ?>" alt="" width="300" height="60">

                    <<?= $encabezado ?> class="titulo fs-1 fw-bold mb-4 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-up">
                        <span><?php echo $titulo_mapa ?></span><br>
                        <?php echo $subtitulo_mapa ?>
                    </<?= $encabezado ?>>

                    <div class="color-primario text-normal regular mb-2" data-aos="fade-up">
                        <?php echo $contenido_mapa ?>
                    </div>

                    <div class="d-flex flex-wrap justify-content-start">

                        <?php if ($mostrar_boton_mapa and !empty($botones_mapa) && count($botones_mapa) > 0): ?>

                            <? foreach ($botones_mapa as $item): extract($item); ?>

                                <?php if (!$boton) continue ?>

                                <div class="me-3 mb-3" data-aos="fade-in">
                                    <?php echo get_burger_button( $boton, $estilo) ?>
                                </div>

                            <? endforeach ?>

                        <?php endif ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>