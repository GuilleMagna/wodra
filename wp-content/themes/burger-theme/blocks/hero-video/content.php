<?php
//Block Name: Hero Video

$content_fields = ['titulo_hero', 'subtitulo_hero', 'encabezado', 'poster', 'video', 'video_mobile', 'boton'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

$video_src = wp_is_mobile() && !empty($video_mobile)
    ? $video_mobile
    : $video;

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

<section id="hero-video" <?= get_block_wrapper_attributes(['class' => $block_id .' section']) ?>>

    <div class="overlay-video"></div>

    <img src="<?php echo BURGER_THEME_URL ?>/themes/images/trama-hero-video.png" alt="trama del video" class="trama-hero-video">

    <div class="h-100 position-relative">

        <video class="w-auto h-auto px-0 position-absolute video-home" style="z-index: 0;" autoplay muted loop
            playsinline poster="<?php echo $poster ?>" width="100%" height="900px">
            <source src="<?= $video_src ?>" type="video/mp4">
            Tu navegador no soporta el elemento de video.
        </video>

        <div class="h-100 d-flex align-items-end position-relative" style="z-index: 8;">

            <div class="container overflow-hidden">

                <div class="row vh-100 align-items-center justify-content-start">

                    <div class="col-12 col-md-6 col-lg-6 col-xxl-6 d-flex align-items-center text-start">

                        <<?= $encabezado ?> class="titulo text-slider mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                            <?= $titulo_hero ?> <span><?= $subtitulo_hero ?></span>
                        </<?= $encabezado ?>>

                    </div>

                    <?php if ( $boton ) : ?>

                        <div class="col-12 position-absolute flechita-abajo">

                            <div class="d-flex align-items-center justify-content-center">
                                <?= burger_render_button(
                                    $boton,
                                    'hero-video-scroll-button',
                                    [
                                        'icon_only'  => true,
                                        'icon_type'  => 'btn-primary',
                                        'aria_label' => $boton['title'] ?? 'Scroll',
                                    ]
                                ); ?>
                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</section>