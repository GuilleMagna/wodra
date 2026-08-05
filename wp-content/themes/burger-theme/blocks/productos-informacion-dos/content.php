<?php 
//Block Name: Productos Informacion dos

$content_fields = [ 'titulo_producto_informacion', 'subtitulo_producto_informacion', 'encabezado', 'productos_informacion' ];
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

<section id="productos-informacion.dos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container mb-5">

		<div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

			<<?= $encabezado ?> class="titulo text-big-titulos text-dark light mb-0" style="text-transform: none; letter-spacing: 0px;">
				<?php echo $titulo_producto ?> <span><?php echo $subtitulo_producto ?></span>
			</<?= $encabezado ?>>

		</div>

	</div>

	<div class="container-fluid">

        <?php foreach( $productos_informacion as $key => $post ): ?>

            <?php
            $post->post_thumbnail = '';
            if( has_post_thumbnail( $post->ID ) ){
                $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
            }
        
            $post->post_permalink = get_permalink( $post->ID );
            ?>

            <div class="row">

                <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center order-1 <? if( $key%2 != 0 ) echo 'order-md-1'; else echo 'order-md-2'; ?>">

                    <div class="col-12 col-md-8 col-lg-7 pe-5">

                        <div class="d-flex flex-column py-5">

                            <div class="mb-4" data-aos="zoom-in">

                                <h2 class="h3 text-white fw-bold">
                                    <?php echo $post->post_title ?>
                                </h2>

                            </div>

                            <div class="mb-5 text-white" data-aos="fade-in">
                                <?php echo $post->post_excerpt ?>
                            </div>

                            <div>

                                <?php
                                echo burger_render_button(
                                    [
                                        'url'    => ($post->post_permalink),
                                        'title'  => 'Conocer más',
                                        'target' => '_self',
                                    ],
                                    'btn btn-primary',
                                    [
                                        'span_class'  => 'text-btn-01',
                                    ]
                                );
                                ?>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-lg-6 img img-descripcion order-2 <? if( $key%2 != 0 ) echo 'order-md-2'; else echo 'order-md-1'; ?>" rounded-end-5" style="background-image: url( '<?php echo $post->post_thumbnail ?>');"></div>

            </div>

        <?php endforeach ?>

	</div>

</section>