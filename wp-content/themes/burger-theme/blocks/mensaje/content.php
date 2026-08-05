<?php 
//Block Name: Mensaje

$content_fields = [ 'titulo_mensaje', 'subtitulo_mensaje', 'encabezado', 'texto_mensaje' ];
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

<section id="mensaje" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <div class="row">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                <<?= $encabezado ?> class="titulo fw-bold text-big-titulos">
                    <?php echo $titulo_mensaje ?> <span><?php echo $subtitulo_mensaje ?></span>
                </<?= $encabezado ?>>

            </div>

            <p class="color-secundario text-normal regular text-center text-md-start mb-0">
                <?php echo $texto_mensaje ?>
            </p>

        </div>

	</div>

</section>