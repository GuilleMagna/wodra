<?php 
//Block Name: Single Carrusel
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'imagen_servicio', 'titulo_compartir', 'subtitulo_compartir', 'boton', 'servicios' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

global $wp_query;
$post = $wp_query->queried_object; 

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

<section id="single-carrusel" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

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
        
                        <a href="https://api.whatsapp.com/send?text=<?php echo $post_title . ' ' . $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-whatsapp-primario' ) ?>
                        </a>
        
                        <!--a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_permalink; ?>&title=<?php echo $post_title; ?>" target="_blank" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-linkedin-primario' ) ?>
                        </a-->
        
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-facebook-primario' ) ?>
                        </a>

                        <a href="https://twitter.com/intent/tweet?text=<?php echo $post_title; ?>&url=<?php echo $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                            <?php echo get_burger_icon( 'icon-twitter-primario' ) ?>
                        </a>
        
                    </div>

                </div>
                
                <?php if( !empty( $boton ) && count( $boton ) > 0 ): ?>

                    <div class="text-center text-md-start mt-3 mt-md-0">
                        <?php echo get_burger_button( $boton, 'btn-linea' ) ?>                        
                    </div>

                <?php endif ?>

			</div>

			<div class="col-12 col-md-6 text-center d-flex align-items-start justify-content-center">
				<img loading="lazy" decoding="async" src="<?php echo $imagen_servicio ?>" class="py-4 w-75" alt="<?php echo strip_tags( $post->post_title ) ?>" title="<?php echo strip_tags( $post->post_title ) ?>">
			</div>

		</div>

	</div>

    <?php if( !empty( $servicios ) && count( $servicios ) > 0 ): ?>

        <div class="container mt-5">

            <div class="owl-carousel owl-servicios owl-theme">

                <?php foreach( $servicios as $value ): ?>

                    <?php $icono = $value['icono'] ?? BURGER_THEME_URL . '/themes/images/iconos/evaluacion.png' ?>

                    <div class="item">

                        <div class="card bg-transparent border-0 rounded-0">

                            <div class="row mx-auto d-flex justify-content-center">

                                <div class="col-auto">
                                    <img loading="lazy" decoding="async" src="<?php echo $icono ?>" style="width: 40px;" alt="<?php echo $value['titulo'] ?>">
                                </div>

                                <div class="col d-flex align-items-center text-normal">
                                    <?php echo $value['titulo'] ?>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach ?>
                
            </div>

        </div>

    <?php endif ?>

</section>
