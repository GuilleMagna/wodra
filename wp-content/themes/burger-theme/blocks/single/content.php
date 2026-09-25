<?php
//Block Name: Single
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'imagen_servicio', 'titulo_compartir', 'subtitulo_compartir', 'boton' ];
$fields = get_block_content_fields( $block, $content_fields );


global $wp_query;
$post = $wp_query->queried_object;

// Las plantillas leen los campos de la entrada; un bloque o preset explícito prevalece.
if ( empty( $block['data']['preset'] ) ) {
    foreach ( $content_fields as $field_name ) {
        if ( ! array_key_exists( $field_name, $block['data'] ?? [] ) ) {
            $fields[ $field_name ] = get_field( $field_name, $post->ID ) ?? '';
            // Los campos nuevos no reciben defaults retroactivos en entradas guardadas.
            if ( ! metadata_exists( 'post', $post->ID, $field_name ) ) {
                if ( 'titulo_compartir' === $field_name ) $fields[ $field_name ] = '¡Compartí ahora esta novedad';
                if ( 'subtitulo_compartir' === $field_name ) $fields[ $field_name ] = 'con colegas!';
            }
        }
    }
}
extract( $fields );
$post_id            = $post->ID ?? '';
$post_title         = $post->post_title ?? '';
$post_content       = $post->post_content ?? '';
$post_permalink     = get_permalink( $post_id );
$post_categories    = get_the_category( $post_id );
$post_category      = ! empty( $post_categories ) ? $post_categories[0] : null;

foreach ( $post_categories as $category ) {
    if ( 'todas' !== $category->slug ) {
        $post_category = $category;
        break;
    }
}

$design = get_block_design( $block );
extract( $design );

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
        color:<?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color:<?php echo $color_primario ?>
    }
</style>

<section id="single" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container">

        <div class="row">

            <div class="col-12 col-md-6 mx-auto text-start">

                <div class="text-start mb-5">

                    <h1 class="color-primario regular mb-3 <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                        <?php echo $post_title ?>
                    </h1>

                    <?php if ( $post_category ) : ?>
                        <a class="color-secundario" href="<?= esc_url( get_category_link( $post_category->term_id ) ); ?>">
                            <?= esc_html( $post_category->name ); ?>
                        </a>
                    <?php endif; ?>

                </div>

                <div class="color-secundario">
                    <?= apply_filters( 'the_content', $post_content ) ?>
                </div>

                <div id="compartir-ahora" class="mt-5">

                    <h4 class="fs-3 titulo text-center text-md-start mb-4">
                        <?= $titulo_compartir ?> <span><?= $subtitulo_compartir ?></span>
                    </h4>

                    <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-4">

                        <a href="https://api.whatsapp.com/send?text=<?= rawurlencode( $post_title . ' ' . $post_permalink ) ?>" target="_blank" rel="noopener noreferrer" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-whatsapp-primario' ) ?>
                        </a>

                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode( $post_permalink ) ?>" target="_blank" rel="noopener noreferrer" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-facebook-primario' ) ?>
                        </a>

                        <a href="https://twitter.com/intent/tweet?text=<?= rawurlencode( $post_title ) ?>&url=<?= rawurlencode( $post_permalink ) ?>" target="_blank" rel="noopener noreferrer" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-twitter-primario' ) ?>
                        </a>

                    </div>

                </div>

                <?php if( is_array( $boton ) && !empty( $boton ) ): ?>

                    <div class="text-center text-md-start mt-3 mt-md-0">
                        <?php echo get_burger_button( $boton, 'btn-linea' ) ?>
                    </div>

                <?php endif ?>

            </div>

            <div class="col-12 col-md-6 text-center d-flex align-items-start justify-content-center">
                <?php if ( $imagen_servicio ) : ?><img loading="lazy" decoding="async" src="<?= esc_url( $imagen_servicio ) ?>" class="py-4 w-75" alt="<?= esc_attr( wp_strip_all_tags( $post_title ) ) ?>" title="<?= esc_attr( wp_strip_all_tags( $post_title ) ) ?>"><?php endif; ?>
            </div>

        </div>

    </div>

</section>
