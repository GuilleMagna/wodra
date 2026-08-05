<?php
//Block Name: Descripción empresa

$content_fields = ['logo_empresa', 'titulo_empresa', 'subtitulo_empresa', 'encabezado', 'texto_empresa', 'botones_empresa'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

$design = get_block_design($block);
extract($design);

if (isset($_GET['nombre']) and !empty($_GET['nombre']))
    $nombre = $_GET['nombre'];
else
    $nombre = 'Cliente';

$titulo_empresa = str_replace('[nombre]', "<span class='text-primary'>{$nombre}</span>", $titulo_empresa);

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

<section id="descripcion-empresa" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="row g-0">

            <div
                class="col-12 <?php if (!empty($logo_empresa)): ?> col-md-7 col-lg-6 <?php else: ?> col-md-10 col-lg-8 <?php endif ?> d-flex align-items-center">

                <div data-aos="fade-left">

                    <?php if (!empty($titulo_empresa)): ?>

                        <div class="titulo <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                            <<?= $encabezado ?> class="mb-4" data-aos="zoom-in">
                                <?php echo $titulo_empresa ?> <span><?php echo $subtitulo_empresa ?></span>
                            </<?= $encabezado ?>>

                        </div>

                    <?php endif; ?>

                    <div class="color-secundario">
                        <?php echo $texto_empresa ?>
                    </div>

                    <?php if (!empty($botones_empresa) AND count($botones_empresa) > 0): ?>

                        <?php foreach ($botones_empresa as $boton):
                            echo get_burger_button( $boton['enlace'], $boton['estilo'] );
                        endforeach ?>

                    <?php endif ?>

                </div>

            </div>

            <?php if (!empty($logo_empresa)): ?>

                <div class="col-12 col-md-5 col-lg-4 d-flex align-items-center mx-auto">
                    <img src="<?php echo $logo_empresa ?>"
                        class="img-fluid w-75 img-descripcion pt-3 pt-md-0 mx-auto mx-lg-0" alt="logo-empresa"
                        data-aos="fade-in" data-aos-delay="300">
                </div>

            <?php endif ?>

        </div>

    </div>

</section>