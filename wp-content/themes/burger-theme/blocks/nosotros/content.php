<?php 
//Block Name: Nosotros

$content_fields = [ 'titulo_nosotros', 'subtitulo_nosotros', 'encabezado', 'contenido_nosotros', 'mostrar_boton_nosotros', 'boton_nosotros', 'galeria_nosotros' ];
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

<section id="nosotros" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container-fluid bg-light">

		<div class="row">
            
			<div class="col-12 col-md-5 d-flex align-items-center">

				<div class="col-12 col-md-8 my-4 me-5 ms-md-auto my-md-auto">

					<div class="d-flex flex-column">

						<<?= $encabezado ?> class="titulo mb-4 text-big light mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
							<?= $titulo_nosotros ?> <span class="text-ultra-big medium"><?= $subtitulo_nosotros ?></span>
                        </<?= $encabezado ?>>

						<div class="text-dark text-normal regular mb-4">
							<?= $contenido_nosotros ?>
						</div>

                        <?php if( $mostrar_boton_nosotros and !empty( $boton_nosotros ) and count( $boton_nosotros ) > 0 ): ?>
                                
                            <div>
                                <?php
                                echo burger_render_button(
                                    [
                                        'url'    => ($boton_nosotros['url']),
                                        'title'  => ($boton_nosotros['title']),
                                        'target' => ($boton_nosotros['target']),
                                    ],
                                    'btn btn-lg btn-primary text-normal regular text-white',
                                    [
                                        'span_class'  => 'text-btn-01',
                                    ]
                                );
                                ?>
                            </div>

                        <?php endif ?>

					</div>

				</div>

			</div>

            <?php if( !empty( $galeria_nosotros ) and count( $galeria_nosotros ) > 0 ): ?>

                <div class="col-12 col-md-7">

                    <div class="row px-0">
                        <?php foreach( $galeria_nosotros as $img ): ?>
                            <div class="col-6 col-lg-4 img con-nosotros" style="background-image: url( '<?php echo $img ?>');"></div>
                        <?php endforeach ?>
                    </div>

                </div>

            <?php endif ?>

		</div>

	</div>

</section>
