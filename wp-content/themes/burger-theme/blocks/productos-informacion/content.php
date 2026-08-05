<?php 
//Block Name: Productos Información

$content_fields = [ 'titulo_producto', 'subtitulo_producto', 'encabezado', 'contenido', 'titulo_atributos', 'subtitulo_atributos', 'atributos_producto' ];
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

<section id="productos-informacion" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="row justify-content-between">
            
			<div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-in">

				<<?= $encabezado ?> class="titulo mb-5">
                    <?php echo $titulo_producto ?> <span><?php echo $subtitulo_producto ?></span>
                </<?= $encabezado ?>>

				<?= $contenido ?>

			</div>

			<div class="col-12 col-md-5" data-sal="fade" data-sal-duration="500">

				<h3 class="mb-5">
                    <?php echo $titulo_atributos ?>&nbsp;<span class="text-primary"><?php echo $subtitulo_atributos ?></span>
                </h3>

				<ul class="list-group list-group-flush">

                    <?php if( !empty($atributos_producto) and count($atributos_producto) > 0 ) foreach( $atributos_producto as $key => $value ): ?>

                        <li class="list-group-item<?php if( $key == 0 ) echo ' pt-0' ?> pb-3 border-0 ps-0">
                            <span class="text-primary"><?php echo $value['nombre'] ?></span>&nbsp;<?php echo $value['valor'] ?>
                        </li>

                    <?php endforeach ?> 

				</ul>

			</div>

		</div>

	</div>

</section>