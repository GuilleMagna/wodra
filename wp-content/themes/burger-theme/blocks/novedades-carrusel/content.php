<?php 
//Block Name: Novedades Carrusel

$content_fields = [ 'titulo_novedades', 'subtitulo_novedades', 'encabezado', 'seleccionar_novedades', 'novedades', 'categoria_novedades', 'cantidad_novedades' ];
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

<section id="novedades-carrusel" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <<?= $encabezado ?> class="titulo mb-5 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-down">
            <?php echo $titulo_novedades ?>
            <span class="medium"><?php echo $subtitulo_novedades ?></span>
        </<?= $encabezado ?>>

		<div class="owl-carousel owl-theme owl-novedades mx-0">

            <?php foreach( $novedades as $post ): ?>

                <?php 
                if( $post->ID == $post_id ) continue;

                $post->post_thumbnail = '';
                $post->post_category = '';
                if( has_post_thumbnail( $post->ID ) ) {
                    $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
                    $post->post_category = get_the_category( $post->ID );
                }

                $post->post_permalink = get_permalink( $post->ID );
                ?>

                <div class="item">

                    <div class="card card-novedades border-0 bg-transparent rounded-0 mb-5" data-aos="fade-up">
                                        
                        <div class="card-header border-0 rounded-4 mb-2 img novedades-img position-relative" style="background-image: url( '<?= $post->post_thumbnail ?>');">
                            <a href="<?php echo $post->post_thumbnail ?>" class="position-absolute w-100 h-100"></a>
                            
                        </div>

                        <div class="card-body px-0 border-0 d-flex flex-column">

                            
                            <h3 class="novedades-titulo text-primary mb-2">
                                <?= $post->post_title ?>
                            </h3>
                            
                            <p class="text-primary mb-1"><?= $post->post_category[0]->name; ?></p>

                            <div class="novedades-descripcion mb-3">
                                <?= $post->post_excerpt ?>
                            </div>

                            <?php
                            echo burger_render_button(
                                [
                                    'url'    => ($post->post_permalink),
                                    'title'  => 'Leer Más',
                                    'target' => '_self',
                                ],
                                'btn-text text-uppercase',
                                [
                                    'span_class'  => 'text-btn-01',
                                ]
                            );
                            ?>   

                        </div>

                    </div>

                </div>

			<?php endforeach; ?>

		</div>

	</div>
    
</section>
