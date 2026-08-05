<?php
//Block Name: Compartir

global $wp_query;
$post = $wp_query->queried_object;
$post_id = $post->ID ?? '';

$content_fields = ['titulo_compartir', 'subtitulo_compartir', 'encabezado', 'logo_compartir'];
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
        background-color: <?= $color_fondo ?>;
        background-image: url('<?= $imagen_fondo ?>');
        background-size: cover;
        background-repeat: no-repeat;
        position: relative;
        isolation: isolate;
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

<section id="compartir" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container py-xxl-5">

        <div class="row justify-content-between">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                <<?= $encabezado ?> class="titulo h1 fw-bold mb-4">
                    <?php echo $titulo_compartir ?> <span><?php echo $subtitulo_compartir ?></span>
                </<?= $encabezado ?>>

                <div class="d-flex align-items-center justify-content-center justify-content-lg-start" style="gap: 12px;">

                    <a href="https://api.whatsapp.com/send?text=<?php echo $post->post_title . ' ' . $post->post_permalink; ?>" target="_blank">
                        <?php echo get_burger_icon( 'icon-whatsapp' ) ?>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post->post_permalink; ?>" target="_blank" class="text-decoration-none">
                        <?php echo get_burger_icon( 'icon-facebook' ) ?>
                    </a>

                    <!--a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post->post_permalink; ?>&title=<?php echo $post->post_title; ?>" target="_blank" class="text-decoration-none">
                        <img src="<?= BURGER_THEME_URL ?>/themes/images/iconos/icono-linkedin.png" alt="compartir linkedin">
                    </a-->

                    <a href="https://twitter.com/intent/tweet?text=<?php echo $post->post_title; ?>&url=<?php echo $post->post_permalink; ?>" target="_blank" class="text-decoration-none">
                        <?php echo get_burger_icon( 'icon-twitter' ) ?>
                    </a>

                </div>

            </div>

            <div class="col-6 mx-auto mt-4 mt-md-0 col-lg-3 d-flex align-items-center">
                <img src="<?php echo $logo_compartir ?>" alt="logo" class="img-fluid w-100">
            </div>

        </div>

    </div>

</section>