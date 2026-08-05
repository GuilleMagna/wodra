<?php 
//Block Name: Enviar CV

$content_fields = [ 'titulo_enviar_cv',  'subtitulo_enviar_cv',  'encabezado',  'texto_enviar_cv',  'imagen_enviar_cv',  'titulo_boton_enviar_cv',  'boton_enviar_cv' ];
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

<section id="enviar-cv" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="row">

			<div class="col-12 col-md-8 col-lg-7 mx-auto text-start py-5">

				<div class="text-start mb-5 ps-3">

					<<?= $encabezado ?> class="titulo text-big-titulos fw-bold mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                        <?php echo $titulo_enviar_cv ?> <span><?php echo $subtitulo_enviar_cv ?></span>
			        </<?= $encabezado ?>>

				</div>

				<p class="text-dark text-normal regular text-center text-md-start mb-4">
                    <?php echo $texto_enviar_cv ?>
                </p>

				<div class="d-md-flex justify-content-start">

					<div class="d-flex align-items-center mt-5 mt-md-0">

						<p class="text-big text-dark light mb-0 thin mp-0 text-center text-md-start" style="text-transform: none;">
                            <?php echo $titulo_boton_enviar_cv ?>
                        </p>	

					</div>

                    <?php if( !empty( $boton_enviar_cv ) and count( $boton_enviar_cv ) > 0 ): ?>

                        <div class="text-center text-md-start mt-3 mt-md-0">

                            <?php
                            echo burger_render_button(
                                [
                                    'url'    => ($boton_enviar_cv['url']),
                                    'title'  => ($boton_enviar_cv['title']),
                                    'target' => ($boton_enviar_cv['target']),
                                ],
                                'btn btn-lg btn-primary text-normal regular text-white ms-0 ms-md-4',
                                [
                                    'span_class'  => 'text-btn-01',
                                ]
                            );
                            ?>

                        </div>

                    <?php endif ?>

				</div>

			</div>
                                    
            <?php if( !empty( $imagen_enviar_cv ) ): ?>

                <div class="col-12 col-md-4 col-lg-5 text-center d-flex align-items-end justify-content-center">
                    <img loading="lazy" decoding="async" src="<?php echo $imagen_enviar_cv ?>" class="img-fluid" alt="trabajador">
                </div>

            <?php endif ?>

		</div>

	</div>

</section>
