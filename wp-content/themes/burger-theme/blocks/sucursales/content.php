<?php 
//Block Name: Sucursales

$content_fields = [ 'titulo_sucursales',  'subtitulo_sucursales', 'encabezado', 'texto_sucursales',  'mostrar_galeria_sucursales',  'galeria_sucursales',  'sucursales' ];
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

<section id="sucursales" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="mb-5">

			<<?= $encabezado ?> class="titulo fs-1 fw-bold pb-3 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-up">
				<span><?= $titulo_sucursales ?></span> <?= $subtitulo_sucursales ?>
			</<?= $encabezado ?>>
	
			<div class="col-12 col-md-10 col-lg-8 color-secundario" data-aos="fade-right">
				<?= $texto_sucursales ?>
			</div>

		</div>

		<div class="row mb-4">

			<?php if( !empty( $sucursales ) && count( $sucursales ) > 0 ) foreach( $sucursales as $key => $value ): extract( $value ) ?>

				<div class="col-12 col-md-7 col-lg-auto mb-3">

					<div class="card bg-transparent border-0" data-aos="fade-in" data-aos-delay="<?= $key ?>00">

						<div class="card-body px-0 py-0 py-lg-3">

							<div class="d-flex flex-column">

								<div class="d-flex align-items-center justify-content-start">

									<?php if( !empty( $icono )):  ?>

										<div class="d-flex align-items-center">
											<img loading="lazy" decoding="async" src="<?= $icono ?>" class="me-3 mb-2" title="<?= $nombre ?> <?= $ciudad ?>">
										</div>

									<?php endif ?>

									<h6 class="text-primary text-uppercase bold mb-0">
                                        <?php if( $link ): ?><a href="<?= $link ?>" target="_blank"><?php endif ?>
										    <?= $nombre ?> <?= $ciudad ?>
                                        <?php if( $link ): ?></a><?php endif ?>
									</h6>

								</div>

                                <?php if( $ubicacion ): ?>
                                    <p class="text-small mb-2 text-dark">
                                        <?= $ubicacion ?>
                                    </p>
                                <?php endif ?>

                                <?php if( $descripcion ): ?>
                                    <div class="text-normal text-dark" style="line-height: 24px;">
                                        <?= $descripcion ?>
                                    </div>
                                <?php endif ?>

							</div>

						</div>

					</div>

				</div>

			<?php endforeach ?>

		</div>
		
        <div class="owl-carousel owl-sucursales owl-theme">
            
            <?php if( !empty( $galeria_sucursales ) && count( $galeria_sucursales ) > 0 ) foreach( $galeria_sucursales as $img ): ?>
            
                <div class="item mx-3">
    
                    <div class="img galeria-owl img-cont mx-auto position-relative" style="background-image: url('<?= $img ?>')">
                        <a href="<?= $img ?>" class="position-absolute h-100 w-100" data-fancybox="galeria-scurusales"></a>
                    </div>
    
                </div>
            
            <?php endforeach ?>
            
        </div>
	        
	</div>
    
</section>