<?php
//Block Name: Zocalo Titulos

$content_fields = ['titulo_zocalo', 'subtitulo_zocalo', 'encabezado'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

$design = get_block_design($block);
extract($design);

$block_id = 'zocalo-titulos-' . $block['id'];
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

<section id="zocalo-titulo" <?= get_block_wrapper_attributes(['class' => $block_id.' '.$class_container]) ?>>

    <?php if (!empty($titulo_zocalo)): ?>

        <div class="container mb-5">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                <<?= $encabezado ?> class="titulo text-big-titulos h1 fw-bold mb-0">
                    <span><?php echo $titulo_zocalo ?></span> <?php echo $subtitulo_zocalo ?>
                </<?= $encabezado ?>>

            </div>

        </div>

    <?php endif ?>

</section>