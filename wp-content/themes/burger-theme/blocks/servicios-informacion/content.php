<?php 
//Block Name: Servicios información

$content_fields = [ 'titulo_servicios', 'subtitulo_servicios', 'encabezado', 'mostrar_servicios', 'servicios_informacion', 'columnas' ];
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
        color: <?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?php echo $color_primario ?>
    }
</style>

<section id="servicios-informacion" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container mb-5">

		<div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

			<<?= $encabezado ?> class="titulo">
				<?php echo $titulo_servicios ?> <span><?php echo $subtitulo_servicios ?></span>
			</<?= $encabezado ?>>

		</div>

	</div>

	<div class="container-fluid">

        <?php if( $mostrar_servicios && !empty($servicios_informacion) and count($servicios_informacion) > 0 ): ?>
                
            <?php foreach( $servicios_informacion as $key => $post_id ): ?>

                <div class="row">

                    <?php
                    $post = get_post( $post_id );
                    $post->post_thumbnail = '';
                    if( has_post_thumbnail( $post_id ) ){
                        $post->post_thumbnail = get_the_post_thumbnail_url( $post_id, 'large' );
                    }
                
                    $post->post_permalink = get_permalink( $post_id );
                    ?>

                    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center <?php if( $key%2 == 0 ) echo 'order-1 order-lg-0'; else echo 'order-1 order-lg-1' ?>">

                        <div class="col-12 col-md-8 col-lg-7 my-4 pe-5">

                            <div class="d-flex flex-column">

                                <div class="mb-3" data-aos="zoom-in">

                                    <<?= $encabezado ?> class="titulo">
                                        <span><?php echo $post->post_title ?></span>
                                    </<?= $encabezado ?>>

                                </div>

                                <div class="mb-2" data-aos="fade-in">
                                    <?php echo $post->post_excerpt ?>
                                </div>
                                    
                            </div>
                                
                            <?php
                            echo burger_render_button(
                                [
                                    'url'    => ($post->post_permalink),
                                    'title'  => 'CONOCER MÁS',
                                    'target' => '_blank',
                                ],
                                'btn btn-primary',
                                [
                                    'span_class'  => 'text-btn-01',
                                ]
                            );
                            ?>

                        </div>

                    </div>

                    <div class="col-12 col-lg-6 img img-descripcion <?php if( $key%2 == 0 ) echo 'border-right-big'; else echo 'border-left-big' ?> <?php if( $key%2 == 0 ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>" style="background-image: url( '<?php echo $post->post_thumbnail ?>');"></div>

                </div>

            <?php endforeach ?>

        <?php else: ?>

            <?php if( !empty($columnas) and count($columnas) > 0 ) foreach( $columnas as $key => $item ): ?>

                <div class="row">

                    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center <?php if( $key%2 != 0 ) echo 'order-1 order-lg-0'; else echo 'order-1 order-lg-1' ?>">

                        <div class="col-12 col-md-8 col-lg-7 my-4 pe-5">

                            <div class="d-flex flex-column">

                                <div class="mb-3" data-aos="zoom-in">

                                    <h2 class="color-primario">
                                        <?php echo $item['titulo'] ?>
                                    </h2>

                                </div>

                                <div class="color-secundario mb-2" data-aos="fade-in">
                                    <?php echo $item['contenido'] ?>
                                </div>
                                    
                            </div>
                                
                            <?php if ( $item['mostrar_boton'] ): ?>

                                <?php
echo burger_render_button(
    [
        'url'    => ($item['boton']['url']),
        'title'  => ($item['boton']['title'] ?? ''),
        'target' => ($item['boton']['target'] ?? '_self'),
    ],
    'btn btn-primary',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>

                            <?php endif ?>

                        </div>

                    </div>

                    <div class="col-12 col-lg-6 img img-descripcion <?php if( $key%2 == 0 ) echo 'border-right-big'; else echo 'border-left-big' ?> <?php if( $key%2 != 0 ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>" style="background-image: url( '<?php echo $item['imagen'] ?>');"></div>

                </div>

            <?php endforeach ?>

        <?php endif ?>

	</div>

</section>