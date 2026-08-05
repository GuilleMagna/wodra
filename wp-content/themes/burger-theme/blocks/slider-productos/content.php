<?php
// Block Name: Slider productos

$content_fields = [ 'titulo_productos', 'subtitulo_productos', 'encabezado', 'productos', 'mostrar_mas_productos', 'titulo_mas_productos', 'boton_mas_productos' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

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

<section id="slider-productos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="text-start border-start border-primary border-4 mb-5 ps-3">

			<<?= $encabezado ?> class="titulo text-big-titulos regular mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <span><?php echo $titulo_productos ?></span>
            </<?= $encabezado ?>>

			<p class="text-big-titulos color-secundario semi-bold pb-2">
                <?php echo $subtitulo_productos ?>
            </p>

		</div>
        
	</div>

	<div class="container mt-5">

		<div class="owl-carousel owl-productos owl-theme">

            <?php if(!empty($productos) and count($productos) > 0 ) foreach( $productos as $post ): ?>

                <?php 
                $post->post_thumbnail = '';
                if( has_post_thumbnail( $post->ID ) ){
                    $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
                }

                $post->post_permalink = get_permalink( $post->ID );

                $post->atributos = get_field( 'atributos', $post->ID );
                ?>

                <div class="item">

                    <div class="row bg-white border-dark">

                        <div class="col-12 col-md-8 d-flex justify-content-md-end align-items-center px-0" style="background-image: url('<?php echo $post->post_thumbnail ?>'); background-size: cover; background-position: center;">
                            
                            <div class="col-12 col-md-6 h-100 d-flex justify-content-start align-items-center" style="background-color: #ffb70091;">

                                <div class="card bg-transparent border-0 rounded-0 my-auto px-5">

                                    <div class="card-header d-flex flex-column bg-transparent border-0 rounded-0">

                                        <h3 class="text-normal-dos regular mb-5 text-white">
                                            <?php echo $post->post_title ?>
                                        </h3>

                                        <?php if( !empty($post->atributos) and count($post->atributos) > 0 ) foreach( $post->atributos as $item ): ?>

                                            <div class="d-flex justify-content-start mb-4 text-white">

                                                <div style="width: 35px;" class="me-2">
                                                    <img loading="lazy" decoding="async" src="<?php echo $item['icono'] ?>" style="width: 15px;">
                                                </div>

                                                <div style="width: 100%;">
                                                    <b><?php echo $item['nombre'] ?></b> <?php echo $item['valor'] ?>
                                                </div>

                                            </div>
                                            
                                        <?php endforeach ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-12 col-md-4 bg-white border border-light ps-0">

                            <div class="card bg-white border-0 px-5 py-4">

                                <div class="card-body bg-white border-0">

                                    <?php echo $post->post_excerpt ?>

                                    <?php
                                    echo burger_render_button(
                                        [
                                            'url'    => ($post->post_permalink),
                                            'title'  => 'VER PROYECTO',
                                            'target' => '_self',
                                        ],
                                        'btn btn-outline-primary',
                                        [
                                            'span_class'  => 'text-btn-01',
                                        ]
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

			<?php endforeach ?>

		</div>

	</div>

    <?php if( $mostrar_mas_productos ): ?>

        <div class="mt-4 d-flex justify-content-center">
            <button class="btn btn-lg btn-primary text-uppercase light">
                Ver todos los productos
            </button>
        </div>

    <?php endif ?>

</section>
