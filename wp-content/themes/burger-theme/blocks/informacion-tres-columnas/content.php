<?php 
//Block Name: Información dos columnas

$content_fields = [ 'titulo_informacion',  'subtitulo_informacion',  'encabezado',  'texto_informacion',  'columnas' ];
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

<section id="informacion-tres-columnas" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <?php if( !empty($titulo_informacion) OR !empty($subtitulo_informacion) ): ?>

            <div class="mb-5">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?> mb-3">

                    <<?= $encabezado ?> class="titulo pb-2" data-aos="fade-in" data-aos-delay="000">
                        <?php echo $titulo_informacion ?> <span><?php echo $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>
                    
                    <div data-aos="fade-in" data-aos-delay="300">
                        <?php echo $texto_informacion ?>
                    </div>

                </div>

            </div>

        <?php endif ?>

		<div class="row g-0 g-lg-4">

            <?php if( !empty($columnas) && count($columnas) > 0 ) foreach( $columnas as $key => $item ): extract($item) ?>

                <div class="col-12 col-md-6 col-lg-4 mb-4">

                    <div class="card card-info border-0 rounded-0">

                        <div class="card-body p-3 py-0 py-lg-3" data-aos="fade-in" data-aos-delay="<?= $key ?>00">

                            <?php if(!empty($imagen)): ?>

                                <div class="text-start <?php if( $key%2==0 ) echo 'text-md-start'; ?> mb-5">
                                    <img loading="lazy" decoding="async" src="<?= $imagen ?>" class="img-fluid" alt="<?= $titulo ?>" style="aspect-ratio: 1/1; border-radius: <?= $radio_de_los_bordes ?>;">
                                </div>

                            <?php endif ?>

                            <h3 class="fs-4 fw-bold mb-4 color-secundario">
                                <?php echo $titulo ?>
                            </h3>

                            <? if( $subtitulo ): ?>
                                <div class="text-small mb-4 color-secundario">
                                    <?php echo $subtitulo ?>
                                </div>
                            <? endif ?>

                            <div class="color-primario">
                                <?= $contenido ?>
                            </div>

                            <div class="text-start <?php if( $key%2==0 ) echo 'text-md-start' ?>">

                                <?php if ( $mostrar_boton ): ?>
                                    <?php echo get_burger_button ( $boton, $estilo_boton ) ?>
                                <?php endif ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach ?>

		</div>

	</div>
    
</section>