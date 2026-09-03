<?php 
//Block Name: Single
if( get_post_type() == 'page' ) return null;

global $wp_query;
$post = $wp_query->queried_object; 

$post_title     = $post->post_title ?? '';
$post_content   = $post->post_content ?? '';
$post_categories = get_the_category( $post->ID );
$post_category   = ! empty( $post_categories ) ? $post_categories[0] : null;

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

<section id="single" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>

    <div class="container">

        <div class="row justify-content-center">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?> color-secundario">

                <h1 class="titulo text-ultra-big color-primario semi-bold mb-3">
                    <?php echo $post_title ?>
                </h1>

                <?php if ( $post_category ) : ?>
                    <div class="single-category mb-5">
                        <a class="color-secundario" href="<?= esc_url( get_category_link( $post_category->term_id ) ); ?>">
                            <?= esc_html( $post_category->name ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php echo apply_filters( 'the_content', $post_content ) ?>

            </div>

        </div>

    </div>

</section>

