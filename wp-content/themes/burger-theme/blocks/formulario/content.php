<?php 
//Block Name: Formulario

$content_fields = [ 'titulo_formulario',  'subtitulo_formulario',  'encabezado',  'texto_formulario',  'shortcode_formulario', 'botones_formulario' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];
$wrapper_style = sprintf(
    'margin:%s !important;padding:%s !important;border-radius:%s !important;background-color:%s !important;background-image:url("%s");background-size:cover;background-repeat:no-repeat;',
    $section_margin,
    $section_padding,
    $border_radius,
    $color_fondo,
    esc_url($imagen_fondo)
);
?>

<style>
    .<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?> !important;
        background-image: url('<?= $imagen_fondo ?>');
        background-size: cover;
        background-repeat: no-repeat;
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

<section id="formulario" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container, 'style' => $wrapper_style, 'data-burger-bg-color' => $color_fondo ] ) ?>>
    
	<div class="container">

		<?php if( !empty( $titulo_formulario ) || !empty( $texto_formulario ) ): ?>

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="titulo fs-1 mb-4">
                        <?php echo $titulo_formulario ?> <span><?php echo $subtitulo_formulario ?></span>
                    </<?= $encabezado ?>>
                
                    <div class="color-secundario">
                        <?php echo $texto_formulario ?>
                    </div>

                </div>

            </div>

		<?php endif ?>

	</div>

	<div class="container pt-5">

		<div class="mx-auto col-12 col-md-8 color-secundario">
			<?php echo do_shortcode( $shortcode_formulario ) ?>
		</div>

        <div class="pt-5 d-flex flex-column flex-sm-row align-items-md-center justify-content-md-center">

            <?php if (!empty($botones_formulario) && count($botones_formulario) > 0): ?>

                <? foreach ($botones_formulario as $item): extract($item); ?>

                    <?php if (!$enlace) continue ?>

                    <div class="me-3" data-aos="fade-in">
                        <?php echo get_burger_button( $enlace, $estilo ) ?>
                    </div>

                <? endforeach ?>

            <?php endif ?>

        </div>        
		
	</div>

</section>