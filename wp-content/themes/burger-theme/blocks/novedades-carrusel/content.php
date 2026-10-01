<?php
//Block Name: Novedades Carrusel

$content_fields = [ 'titulo_novedades',  'subtitulo_novedades',  'seleccionar_novedades',  'novedades',  'categoria_novedades',  'cantidad_novedades', 'estilo_boton', 'encabezado' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$cantidad_novedades = max( 1, absint( $cantidad_novedades ?: 6 ) );
$estilo_boton = $estilo_boton ?: 'btn-texto';
$encabezado = in_array( $encabezado, [ 'h1', 'h2', 'h3' ], true ) ? $encabezado : 'h2';
$current_post_id = is_singular( 'post' ) ? get_queried_object_id() : 0;
if ( ! $seleccionar_novedades ) {
    $novedades = get_posts( [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $cantidad_novedades,
        'category__in' => array_filter( array_map( static function ( $category ) {
            return absint( is_object( $category ) ? $category->term_id : $category );
        }, (array) $categoria_novedades ) ),
        'post__not_in' => $current_post_id ? [ $current_post_id ] : [],
    ] );
}
$novedades = array_values( array_filter( array_map( 'get_post', (array) $novedades ), static function ( $news ) use ( $current_post_id ) {
    return $news instanceof WP_Post && 'publish' === $news->post_status && $news->ID !== $current_post_id;
} ) );
if ( ! $novedades ) return;

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
        color: <?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?php echo $color_primario ?>
    }
</style>

<section id="novedades-carrusel" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="text-start mb-5">

            <<?= $encabezado ?> class="titulo mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <?php echo $titulo_novedades ?><br>
                <span><?php echo $subtitulo_novedades ?></span>
            </<?= $encabezado ?>>

		</div>

	</div>

	<div class="container">

		<div id="owl-novedades-<?= esc_attr( $block_id ) ?>" class="owl-carousel owl-theme owl-novedades">

            <?php foreach( $novedades as $news ): ?>

                <?php
                $thumbnail = get_the_post_thumbnail_url( $news->ID, 'large' ) ?: '';
                $categories = get_the_category( $news->ID );
                $category_name = $categories ? $categories[0]->name : '';
                $boton['title']     = 'Leer Más';
                $boton['url']       = get_permalink( $news->ID );
                $boton['target']    = '_self';
                $estilo             = $estilo_boton;

                ?>

                <div class="item">

                    <div class="card card-novedades bg-transparent border-0 rounded-0 mb-5">

                        <div class="card-header border-0 rounded-4 img novedades-img position-relative" style="background-image: url( '<?= esc_url( $thumbnail ) ?>');">
                            <a href="<?= $boton['url'] ?>" class="position-absolute w-100 h-100"></a>
                        </div>

                        <div class="card-body px-0 border-0 d-flex flex-column">

                            <h3 class="color-secundario mb-3">
                                <?= cortar_texto( $news->post_title ) ?>
                            </h3>

                            <p class="color-secundario mb-3">
                                <?= esc_html( $category_name ); ?>
                            </p>

                            <div class="color-primario mb-3">
                                <?= $news->post_excerpt ?>
                            </div>

                            <?php echo get_burger_button ( $boton, $estilo ) ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

		</div>

	</div>

</section>
<script>
jQuery(function ($) {
    const $carousel = $(document.getElementById(<?= wp_json_encode( 'owl-novedades-' . $block_id ) ?>));
    if (!$carousel.length || !$.fn.owlCarousel || $carousel.hasClass('owl-loaded')) return;
    $carousel.owlCarousel({
        loop: false,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplaySpeed: 500,
        nav: true,
        navText: [
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.6666 14.167L15.8333 20.0003L21.6666 25.8337" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 36.6663C10.7953 36.6663 3.33329 29.2043 3.33329 19.9997C3.33329 10.7949 10.7953 3.33301 20 3.33301C29.2047 3.33301 36.6666 10.7949 36.6666 19.9997C36.6666 29.2043 29.2047 36.6663 20 36.6663Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3333 14.167L24.1666 20.0003L18.3333 25.8337" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.9999 36.6663C29.2046 36.6663 36.6666 29.2043 36.6666 19.9997C36.6666 10.7949 29.2046 3.33301 19.9999 3.33301C10.7952 3.33301 3.33325 10.7949 3.33325 19.9997C3.33325 29.2043 10.7952 36.6663 19.9999 36.6663Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
        dots: true,
        margin: 24,
        responsive: { 0: { items: 1 }, 768: { items: 2 }, 992: { items: 3 } }
    });
});
</script>