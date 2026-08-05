<?php 
//Block Name: Información en carrusel

$content_fields = [ 'titulo_informacion',  'subtitulo_informacion',  'encabezado',  'galeria' ];
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

<section id="informacion-en-carrusel" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="text-start border-start border-primary border-4 mb-5 ps-3">

			<<?= $encabezado ?> class="titulo text-big-titulos text-dark light mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" style="text-transform: none; letter-spacing: 0px;">
                <?php echo $titulo_informacion ?><br>
	            <span><?php echo $subtitulo_informacion ?></span>
	        </<?= $encabezado ?>>

		</div>

	</div>

    <?php if( !empty( $galeria ) and count( $galeria ) > 0 ): ?>

        <div class="container">

            <div id="carousel-informacion-en-carrusel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <?php foreach( $galeria as $key => $img ): ?>
                        <button type="button" data-bs-target="#carousel-informacion-en-carrusel" data-bs-slide-to="<?= $key ?>"  <?php if( $key==0 ) echo 'class="active" aria-current="true"'; ?> aria-label="Slide <?= $key ?>"></button>
                    <?php endforeach ?>
                </div>

                <div class="carousel-inner">
                    <?php foreach( $galeria as $key => $img ): ?>
                        <div class="carousel-item img <?php if( $key==0 ) echo 'active'; ?>" style="background-image: url( '<?= $img ?>');"></div>
                    <?php endforeach ?>
                </div>
                    
                <?php if( count( $galeria ) > 1 ): ?>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-informacion-en-carrusel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-informacion-en-carrusel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>

                <?php endif ?>

            </div>

        </div>

    <?php endif ?>

</section>