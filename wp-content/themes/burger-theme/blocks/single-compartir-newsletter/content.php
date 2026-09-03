<?php 
//Block Name: Single Compartir Newsletter
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'imagen_formulario_newsletter', 'titulo_compartir', 'subtitulo_compartir', 'titulo_formulario_newsletter', 'subtitulo_formulario_newsletter', 'encabezado', 'redes_novedades', 'shortcode_formulario_newsletter' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

global $wp_query;
$post = $wp_query->queried_object; 

$post_id            = $post->ID ?? '';
$post_title         = $post->post_title ?? '';
$post_content       = $post->post_content ?? '';
$post_permalink     = get_permalink( $post_id );
$post_categorias    = get_the_terms( $post_id, 'category' );

$categorias_links = [];
if( !empty( $post_categorias ) and count($post_categorias) > 0 ){
    foreach( $post_categorias as $term ){
        if ($term->term_id != 11) {
            $categorias_names[] = $term->name;
        }
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

<section id="single-compartir-newsletter" <?= get_block_wrapper_attributes( [ 'class' => $block_id. ' ' .$class_container ] ) ?>>

	<div class="container text-secondary">

		<div class="row align-items-start">

			<div class="col-12 col-md-7 col-xl-8">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <h1 class="titulo fs-1 fw-bold mb-4">
                        <?= $post_title ?>
                    </h1>

                    <?php if ( ! empty( $categorias_links ) ) : ?>

                        <h6 class="fw-bold">
                            <?= implode( ', ', $categorias_links ); ?>
                        </h6>

                    <?php endif ?>

                    <?= apply_filters( 'the_content', $post_content ) ?>
                        
                    <div id="compartir-ahora" class="mt-5">
            
                        <h4 class="fs-3 color-secundario text-center text-md-start mb-4">
                            <?= $titulo_compartir ?> <?= $subtitulo_compartir ?>
                        </h4>
            
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-4">
            
                            <a href="https://api.whatsapp.com/send?text=<?php echo $post_title . ' ' . $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                                <?php echo get_burger_icon( 'icon-whatsapp' ) ?>
                            </a>
            
                            <!--a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_permalink; ?>&title=<?php echo $post_title; ?>" target="_blank" class="compartir-link me-2">

                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none" class="compartir-icon">
                                    <g clip-path="url(#clip0_2009_8271)" class="compartir-icon-fondo">
                                        <path d="M15 0C6.717 0 0 6.717 0 15C0 23.283 6.717 30 15 30C23.283 30 30 23.283 30 15C30 6.717 23.283 0 15 0ZM10.6412 22.6758H6.98799V11.6851H10.6412V22.6758ZM8.8147 10.1843H8.79089C7.565 10.1843 6.77216 9.34044 6.77216 8.28575C6.77216 7.20726 7.58926 6.38672 8.83896 6.38672C10.0887 6.38672 10.8577 7.20726 10.8815 8.28575C10.8815 9.34044 10.0887 10.1843 8.8147 10.1843ZM23.8138 22.6758H20.1611V16.796C20.1611 15.3184 19.6321 14.3106 18.3103 14.3106C17.3012 14.3106 16.7001 14.9904 16.436 15.6466C16.3394 15.8814 16.3158 16.2096 16.3158 16.5381V22.6758H12.6629C12.6629 22.6758 12.7107 12.7162 12.6629 11.6851H16.3158V13.2413C16.8013 12.4924 17.6699 11.4272 19.6081 11.4272C22.0116 11.4272 23.8138 12.998 23.8138 16.3737V22.6758Z" fill=""/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2009_8271">
                                        <rect width="30" height="30" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>

                            </a-->
            
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                                <?php echo get_burger_icon( 'icon-facebook' ) ?>
                            </a>

                            <a href="https://twitter.com/intent/tweet?text=<?php echo $post_title; ?>&url=<?php echo $post_permalink; ?>" target="_blank" class="compartir-link me-2">
                                <?php echo get_burger_icon( 'icon-twitter' ) ?>
                            </a>
            
                        </div>

                    </div>

                </div>

			</div>

			<div class="col-12 col-md-5 col-xl-4" style="background-color: <?php echo $color_secundario ?>; border-radius: 0 0 300px 300px;">

                <div class="section" id="form-newsletter-single">

                    <div class="col-12 col-md-10 col-lg-9 mx-auto" data-aos="fade-left">

                        <<?= $encabezado ?> class="titulo fs-3 fw-bold text-big-titulos light mb-3" style="text-transform: none; letter-spacing: 0px;">
                            <span class="text-white"><?= $titulo_formulario_newsletter ?></span> <span><?= $subtitulo_formulario_newsletter ?></span>
                        </<?= $encabezado ?>>

                        <div class="d-flex justify-conten-start align-items-center mb-5">

                            <?php if( !empty( $redes_novedades['linkedin'] ) ): ?>

                                <a href="<?= $redes_novedades['linkedin']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-linkedin' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if( !empty( $redes_novedades['instagram'] ) ): ?>

                                <a href="<?= $redes_novedades['instagram']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-instagram' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if( !empty( $redes_novedades['facebook'] ) ): ?>

                                <a href="<?= $redes_novedades['facebook']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-facebook' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if( !empty( $redes_novedades['youtube'] ) ): ?>

                                <a href="<?= $redes_novedades['youtube']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-youtube' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if( !empty( $redes_novedades['twitter'] ) ): ?>

                                <a href="<?= $redes_novedades['twitter']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-twitter' ) ?>
                                </a>

                            <?php endif ?>

                        </div>

                        <div class="pb-5 mb-5">
                            <?= do_shortcode( $shortcode_formulario_newsletter ) ?>
                        </div>

                    </div>

                </div>

			</div>

		</div>

	</div>

</section>

