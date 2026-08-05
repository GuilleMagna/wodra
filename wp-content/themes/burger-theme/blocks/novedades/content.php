<?php 
//Block Name: Novedades

$content_fields = [ 'titulo_novedades',  'subtitulo_novedades',  'seleccionar_novedades',  'novedades',  'categoria_novedades',  'cantidad_novedades', 'estilo_boton' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

if( !$seleccionar_novedades ):
	$novedades = get_posts( [ 'posts_per_page' => $cantidad_novedades, 'cat' => $categoria_novedades ] );
endif;	

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

<section id="novedades" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="text-start mb-5">

            <h2 class="titulo mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <?php echo $titulo_novedades ?><br>
                <span><?php echo $subtitulo_novedades ?></span>
            </h2>

		</div>

	</div>

	<div class="container">

		<div class="row justify-content-center px-1">

            <?php foreach( $novedades as $post ): ?>

                <?php 
                $post->post_thumbnail = '';
                $post->post_category = '';
                if( has_post_thumbnail( $post->ID ) ) {
                    $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
                    $post->post_category = get_the_category( $post->ID );
                }

                $boton['title']     = 'Leer Más';
                $boton['url']       = get_permalink( $post->ID );
                $boton['target']    = '_self';
                $estilo             = $estilo_boton;
                
                ?>
                
                <div class="col-lg-4">

                    <div class="card card-novedades bg-transparent border-0 rounded-0 mb-5" data-aos="fade-up">
                        
                        <div class="card-header border-0 rounded-4 img novedades-img position-relative" style="background-image: url( '<?= $post->post_thumbnail ?>');">
                            <a href="<?= $boton['url'] ?>" class="position-absolute w-100 h-100"></a>
                        </div>

                        <div class="card-body px-0 border-0 d-flex flex-column">

                            <h2 class="color-secundario mb-3">
                                <?= cortar_texto( $post->post_title ) ?>
                            </h2>

                            <p class="color-secundario mb-3">
                                <?= $post->post_category[0]->name; ?>
                            </p>

                            <div class="color-primario mb-3">
                                <?= $post->post_excerpt ?>
                            </div>

                            <?php echo get_burger_button ( $boton, $estilo ) ?>

                        </div>

                    </div>

                </div>
                
            <?php endforeach; ?>

		</div>

	</div>

</section>