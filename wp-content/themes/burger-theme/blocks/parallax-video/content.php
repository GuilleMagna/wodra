<?php
//Block Name: Parallax video

$content_fields = ['video_parallax', 'logo_parallax', 'logo_parallax_2', 'titulo_parallax', 'subtitulo_parallax', 'texto_parallax_video', 'botones_parallax', 'invertir_contenido'];
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

<section id="parallax-video" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop" class="position-absolute p-video"
        style="z-index: 0;">
        <source src="<?php echo $video_parallax ?>" type="video/mp4">
    </video>

    <div class="position-absolute h-100 w-100" style="background-color:rgba(0, 0, 0, 0.5); top: 0px; left: 0px;"></div>

    <div class="h-100 d-flex align-items-center contenido-video">

        <div class="container">

            <div class="row justify-content-start justify-content-lg-between align-items-start">

                <? if( !$invertir_contenido ): ?>

                    <? if (!empty($logo_parallax)): ?>

                        <div class="col-8 col-md-6 col-lg-5">
                            <img loading="lazy" decoding="async" src="<?php echo $logo_parallax ?>" alt="logo parallax" class="img-fluid img-video-home">
                        </div>

                    <? endif ?>

                <? endif ?>

                <div class="col-12 col-md-6 col-lg-6 col-xxl-6 pt-5 pt-md-0">

                    <div class="d-flex flex-column">

                        <?php if(!empty($titulo_parallax)): ?>

                            <div class="text-parallax color-primario pb-3 pt-lg-5 pt-lg-0 mt-2 mt-lg-0 pe-lg-5">
                                <?php echo $titulo_parallax ?> <span><?php echo $subtitulo_parallax ?></span>
                            </div>

                        <? endif; ?>

                        <?php if(!empty($texto_parallax_video)): ?>

                            <div class="text-parallax mb-4 me-lg-5 color-secundario">
                                <?php echo $texto_parallax_video ?>
                            </div>

                        <? endif; ?>

                        <div class="d-flex flex-column align-items-start mb-2">

                            <?php if ( !empty($botones_parallax) && is_array($botones_parallax) ): ?>

                                <div class="text-start">

                                    <?php foreach ($botones_parallax as $boton): ?>
                                        <?php echo get_burger_button ( $boton['enlace'], $boton['estilo']) ?>
                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

                <? if( $invertir_contenido ): ?>

                    <? if (!empty($logo_parallax)): ?>

                        <div class="col-8 col-md-6 col-lg-5">
                            <img loading="lazy" decoding="async" src="<?php echo $logo_parallax ?>" alt="logo parallax" class="img-fluid img-video-home">
                        </div>

                    <? endif ?>

                <? endif ?>

                <? if (!empty($logo_parallax_2)): ?>

                    <div class="col-8 col-md-6 col-lg-5">
                        <img loading="lazy" decoding="async" src="<?php echo $logo_parallax_2 ?>" alt="logo parallax" class="img-fluid img-video-home">
                    </div>

                <? endif ?>

            </div>

        </div>

    </div>

</section>