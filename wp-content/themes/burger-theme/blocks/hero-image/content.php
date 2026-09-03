<?php
//Block Name: Hero Image

$content_fields = ['imagen', 'configuracion_individual'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

$design = get_block_design($block);
extract($design);

if ( $configuracion_individual && has_post_thumbnail(get_queried_object_id()) )
    $imagen = get_the_post_thumbnail_url(get_queried_object_id(), 'full');

$block_id = $block['id'];
?>

<style>
    
    .hero-image {
        margin: 105px auto 0 !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?>;
        background-image: url('<?= $imagen_fondo ?>');
        background-repeat: no-repeat;
        background-size: contain;
    }

    .hero-image .imagen-interna{
        height: 80vh;
        width: 100%;
        background-image:url('<?= esc_url($imagen) ?>');
        background-position:center center;
        background-repeat:no-repeat;
        background-size:cover;
        overflow:hidden;
    }
    
    @media (max-width: 991px){

        .hero-image {
            margin: 65px auto 0 !important;
        }

    }

    @media (max-width: 768px){

        .hero-image .imagen-interna {
            height: 30vh;
        }

    }

    .hero-image .titulo,
    .hero-image .color-secundario {
        color: <?php echo $color_secundario ?>
    }

    .hero-image .titulo span,
    .hero-image .color-primario {
        color: <?php echo $color_primario ?>
    }

</style>

<section id="hero-image" <?= get_block_wrapper_attributes(['class' => $block_id . ' section']) ?>>
    <div class="imagen-interna parallax" style="background-image: url('<?php echo esc_url($imagen) ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;"></div>
</section>