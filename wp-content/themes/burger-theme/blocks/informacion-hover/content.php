<?php 
//Block Name: Información Hover

$content_fields = [ 'titulo_informacion', 'subtitulo_informacion', 'encabezado', 'texto_informacion', 'boton_informacion', 'multimedia_derecha', 'imagen_informacion' ];
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

<section id="informacion-hover" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container-fluid">

		<div class="row informacion-largo img" style="background-image: url( '<?= $imagen_informacion ?>');">
            
            <?php if( $multimedia_derecha ): ?>

                <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">

                    <<?= $encabezado ?> class="titulo my-5 py-4 text-uppercase light text-normal-dos text-center mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                        <?= $titulo_informacion ?> <span><?= $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>

                </div>

            <?php endif ?>

			<div class="col-12 col-md-6 informacion-columna">

				<div class="col-12 col-md-7 my-4 py-5 mx-md-auto my-md-auto">

					<div class="d-flex flex-column my-5 py-4">

						<div class="text-normal text-white regular mb-4">
                            <p>
                                <?= $texto_informacion ?>
                            </p>
						</div>

                        <?php if( !empty( $boton_informacion ) and count($boton_informacion) > 0 ): ?>

                            <div>
                                <?php
echo burger_render_button(
    [
        'url'    => ($boton_informacion['url']),
        'title'  => ($boton_informacion['title']),
        'target' => ($boton_informacion['target']),
    ],
    'btn btn-lg-02 btn-outline-primary text-normal regular',
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

            <?php if( !$multimedia_derecha ): ?>

                <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">

                    <<?= $encabezado ?> class="titulo my-5 py-4 text-uppercase light text-normal-dos text-white text-center">
                        <?= $titulo_informacion ?> <span><?= $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>

                </div>

            <?php endif ?>

		</div>

	</div>

</section>