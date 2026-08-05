<?php 
//Block Name: Información dos columnas

$content_fields = [ 'titulo_informacion',  'subtitulo_informacion',  'encabezado',  'columnas' ];
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

<section id="informacion-dos-columnas" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <?php if(!empty($titulo_informacion)): ?>

            <div class="mb-5">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="titulo text-big-titulos light mb-0">
                        <?php echo $titulo_informacion ?> <span><?php echo $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>

                </div>

            </div>

        <?php endif ?>

        <div class="row g-5 justify-content-center">

            <?php if( !empty($columnas) && count($columnas) > 0 ) foreach( $columnas as $key => $item ): extract($item) ?>

                <div class="col-12 col-lg-5">

                    <div class="info-columna text-center" data-aos="fade-up">

                        <?php if ( !empty($imagen) ): ?>

                            <div class="mb-4">

                                <img src="<?= esc_url($imagen) ?>" class="img-fluid" alt="<?= esc_attr($titulo) ?>">

                                <?php if ( !empty($icono) ): ?>
                                    <div class="info-columna-icono">
                                        <img src="<?= esc_url($icono) ?>" alt="">
                                    </div>
                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                        <?php if ( !empty($titulo) ): ?>

                            <h3 class="titulo info-columna-titulo mb-3">
                                <?= $titulo ?>
                            </h3>

                        <?php endif; ?>

                        <?php if ( !empty($contenido) ): ?>

                            <div class="info-columna-texto color-secundario mb-4">
                                <?= $contenido ?>
                            </div>

                        <?php endif; ?>

                        <?php if ( !empty($mostrar_boton) && !empty($botones) ): ?>

                            <div>

                                <?php if (!empty($botones) && count($botones) > 0): ?>

                                    <? foreach ($botones as $item): extract($item); ?>

                                        <?php if (!$boton) continue ?>

                                        <div class="me-3" data-aos="fade-in">
                                            <?php echo get_burger_button( $boton, $estilo ) ?>
                                        </div>

                                    <? endforeach ?>

                                <?php endif ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach ?>

        </div>

		<!--div class="row g-4">

            <?php if( !empty($columnas) && count($columnas) > 0 ) foreach( $columnas as $key => $item ): ?>

                <div class="col-12 col-lg-6 mb-4">

                    <div class="card bg-light card-info border-0 rounded-0" data-aos="fade-up">

                        <div class="card-body p-3">

                            <div class="text-start <?php if( $key%2==0 ) echo 'text-md-start'; ?> mb-5">
                                <img src="<?= $item['imagen'] ?>" class="img-fluid" alt="<?= $item['titulo'] ?>">
                            </div>

                            <h3 class="mb-4"><?= $item['titulo'] ?></h3>

                            <?= $item['contenido'] ?>

                            <div class="text-start <?php if( $key%2==0 ) echo 'text-md-start' ?>">

                                <?php if ( $item['mostrar_boton'] ): ?>
                                    <?php echo get_burger_button ( $item['boton'], $item['estilo'] ) ?>
                                <?php endif ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach ?>

		</div-->

	</div>
    
</section>