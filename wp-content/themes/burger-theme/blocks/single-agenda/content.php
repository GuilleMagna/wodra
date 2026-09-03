<?php
//Block Name: Single agenda

global $wp_query;
$post = $wp_query->queried_object;

$post_title        = $post->post_title;
$post_content      = $post->post_content ?? '';
$texto_evento      = get_field( 'texto_evento', $post->ID );
$botones_evento    = get_field( 'botones_evento', $post->ID ) ?? [];
$informacion       = get_field( 'informacion', $post->ID ) ?? [];
$logo_evento       = get_field( 'logo_evento', $post->ID );
$modo_contenido    = function_exists( 'burger_editor_mode' ) && 'contenido' === burger_editor_mode( 'evento' );
$contenido_bloques = '';

if ( $modo_contenido ) {
    $contenido_bloques = apply_filters( 'the_content', $post_content );
}
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

<section id="single-agenda" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="row g-0">

            <div
                class="col-12 <?php if (!empty($logo_evento)): ?> col-md-7 col-lg-6 <?php else: ?> col-md-10 col-lg-8 <?php endif ?> d-flex align-items-center">

                <div data-aos="fade-left">

                    <div class="<?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                        <h1 class="titulo text-ultra-big color-primario semi-bold mb-5">
                            <?php echo $post_title ?>
                        </h1>

                    </div>

                    <?php if ( $modo_contenido && '' !== trim( $contenido_bloques ) ) : ?>
                        <div class="single-agenda-content">
                            <?php echo $contenido_bloques; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! $modo_contenido && '' !== trim( (string) $texto_evento ) ) : ?>
                        <div class="color-secundario">
                            <?php echo $texto_evento; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($botones_evento) AND count($botones_evento) > 0): ?>

                        <?php foreach ($botones_evento as $boton): ?>
                            <?php echo get_burger_button( $boton['enlace'], $boton['estilo'] ) ?>
                        <?php endforeach ?>

                    <?php endif ?>

                </div>

            </div>

            <?php if ( !empty($informacion) && count($informacion) > 0 ): ?>

                <div class="col-12 col-md-4 col-lg-3 d-flex align-items-center mx-auto">
                    <ul class="list-group list-group-flush">
                        <?php foreach( $informacion as $item ): extract( $item ) ?>
                            <li class="list-group-item border-0">
                                <div class="d-flex align-items-center justify-content-start">
                                    <img src="<?php echo $icono ?>" title="<?php echo $texto ?>" alt="<?php echo $texto ?>" class="me-2">
                                    <div><?php echo $texto ?></div>
                                </div>

                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>

            <?php endif ?>

        </div>

    </div>

</section>
