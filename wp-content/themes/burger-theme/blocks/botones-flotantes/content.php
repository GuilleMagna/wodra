<?php 
//Block Name: Botones Flotantes

$content_fields = [ 'botones_flotantes', 'bottom_posicion' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$bottom_posicion = trim( (string) $bottom_posicion ) ?: '51px';

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];
?>

<style>
    .<?= $block_id ?> {
        bottom: <?= esc_attr( $bottom_posicion ) ?> !important;
        margin: <?= $section_margin ?> !important;
        padding: 0 !important;
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

<section id="botones-flotantes" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="d-flex flex-column flex-sm-row align-items-md-center justify-content-md-center px-4 px-lg-0">

        <?php if( !empty( $botones_flotantes ) AND count( $botones_flotantes ) > 0 ): ?>

            <div class="text-start text-md-center">

                <?php foreach( $botones_flotantes as $boton ): ?>
                    <?php echo get_burger_button( $boton['enlace'], $boton['estilo'] ) ?>
                <?php endforeach ?>

            </div>

        <?php endif ?>
        

	</div>

</section>
