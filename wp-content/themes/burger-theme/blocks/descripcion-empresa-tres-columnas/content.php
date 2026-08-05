<?php
//Block Name: Descripción empresa tres columnas

$content_fields = ['logo_empresa', 'titulo_empresa', 'subtitulo_empresa', 'encabezado', 'texto_empresa', 'botones_empresa'];
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

<section id="descripcion-empresa-tres-columnas" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="row g-4">

            <div class="col-12 col-md-3 d-flex align-items-center">

                <?php if (!empty($titulo_empresa)): ?>

                    <div>

                        <<?= $encabezado ?> class="h5 <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?> color-primario" data-aos="fade-in" data-aos-delay="000">
                            <?php echo $titulo_empresa ?> <span class="color-secundario"><?php echo $subtitulo_empresa ?></span>
                        </<?= $encabezado ?>>

                    </div>

                <?php endif; ?>

            </div>

            <div class="col-12 col-md-5 fs-6 d-flex align-items-center color-primario mb-0" data-aos="fade-in" data-aos-delay="200">
                <?php echo $texto_empresa ?>
            </div>

            <?php if (!empty($logo_empresa)): ?>

                <div class="col-12 col-md-4 d-flex align-items-center justify-content-center">
                    <img src="<?php echo $logo_empresa ?>" class="img-fluid logo-descripcion m-lg-5 w-50" alt="logo-empresa" data-aos="fade-in" data-aos-delay="400">
                </div>

            <?php endif ?>

        </div>

    </div>

</section>