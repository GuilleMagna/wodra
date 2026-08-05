<?php 
//Block Name: Información

$content_fields = [ 'titulo_informacion',  'subtitulo_informacion',  'encabezado',  'texto_informacion',  'multimedia_derecha',  'mostrar_galeria',  'galeria_informacion', 'mostrar_imagen',  'imagen_informacion',  'imagen_de_fondo', 'radio_de_los_bordes', 'mostrar_video', 'video', 'poster_video', 'botones_informacion' ];
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
        background-size: 100%;
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

<section id="informacion" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container-fluid">

		<div class="row justify-content-evenly">

			<div class="col-12 col-lg-6 d-flex align-items-center justify-content-start justify-content-lg-center <?php if( $multimedia_derecha ) echo 'order-1 order-lg-0'; else echo 'order-1 order-lg-1' ?>">

				<div class="col-12 col-md-10 col-lg-9 my-4 ps-4 pe-4 ps-lg-0 pe-lg-5">

					<div class="d-flex flex-column mt-lg-5 my-lg-5 py-4 py-lg-5">

                        <div class="w-75 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                            <?php if(!empty($titulo_informacion)): ?>

                                <div class="mb-3" data-aos="zoom-in">

                                    <<?= $encabezado ?> class="titulo fs-1">
                                        <?php echo $titulo_informacion ?> <span><?php echo $subtitulo_informacion ?></span>
                                    </<?= $encabezado ?>>

                                </div>

                            <?php endif ?>

                            <div class="my-2 color-secundario" data-aos="fade-in">
                                <?php echo $texto_informacion ?>
                            </div>

                            <div class="me-5" data-aos="fade-in">

                                <?php if (!empty($botones_informacion) && count($botones_informacion) > 0): ?>

                                    <? foreach ($botones_informacion as $item): extract($item); ?>
                                        <?php echo get_burger_button( $enlace, $estilo ) ?>
                                    <? endforeach ?>

                                <?php endif ?>

                            </div>

                        </div>
                          
					</div>

				</div>

			</div>

            <?php if( $mostrar_galeria && $galeria ): ?>

                <div class="col-12 col-md-12 col-lg-6 px-0 <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0'; ?>">
                        
                    <div id="carouselExampleIndicators" class="carousel h-100 slide" data-bs-ride="carousel">

                        <!-- <div class="carousel-indicators">
                            <?php foreach( $galeria_informacion as $key => $img ): ?>
                                <button type="button" data-bs-target="#carouselExampleIndicators-3" data-bs-slide-to="<?php echo $key ?>"<?php if( $key == 0 ) echo ' class="active" aria-current="true"' ?> aria-label="Slide <?php echo $key+1 ?>"></button>
                            <?php endforeach ?>
                        </div> -->

                        <div class="carousel-inner h-100">
                            <?php foreach( $galeria_informacion as $key => $img ): ?>
                                <div class="carousel-item h-100 img img-descripcion<?php if( $key == 0 ) echo ' active' ?>" style="background-image: url( '<?php echo $img ?>');"></div>
                            <?php endforeach ?>
                        </div>
                        
                        <?php if( count( $galeria_informacion ) > 1 ): ?>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.6666 14.1667L15.8333 20.0001L21.6666 25.8334" stroke="#F2F1F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 36.6666C10.7953 36.6666 3.33329 29.2046 3.33329 19.9999C3.33329 10.7952 10.7953 3.33325 20 3.33325C29.2047 3.33325 36.6666 10.7952 36.6666 19.9999C36.6666 29.2046 29.2047 36.6666 20 36.6666Z" stroke="#F2F1F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3333 14.1667L24.1666 20.0001L18.3333 25.8334" stroke="#F2F1F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 36.6666C29.2046 36.6666 36.6666 29.2046 36.6666 19.9999C36.6666 10.7952 29.2046 3.33325 20 3.33325C10.7952 3.33325 3.33331 10.7952 3.33331 19.9999C3.33331 29.2046 10.7952 36.6666 20 36.6666Z" stroke="#F2F1F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                                <span class="visually-hidden">Next</span>
                            </button>

                        <?php endif ?>

                    </div> 

                </div>

            <?php elseif( $mostrar_imagen && $imagen_informacion && $imagen_de_fondo ): ?>

                <div class="col-12 col-lg-6 p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>" style="background-image: url( '<?php echo $imagen_informacion ?>'); background-size: 100%; border-radius: <?= $radio_de_los_bordes ?>"></div>
            
            <?php elseif( $mostrar_imagen && $imagen_informacion ): ?>
			
				<div class="col-12 col-lg-6 d-flex align-items-center justify-content-center p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>">
                    <img loading="lazy" decoding="async" src="<?php echo $imagen_informacion ?>" 
                        class="img-fluid border-right-big"
                        alt="img <?php echo $titulo_informacion ?>"
                        style="border-radius: <?= $radio_de_los_bordes ?>">
                </div>

            <?php elseif ( $mostrar_video && $video ): ?>
                
                <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>">
                    <video class="info-hero-media" autoplay muted loop playsinline poster="<?= esc_url($poster_video) ?>">
                        <source src="<?= esc_url($video) ?>" type="video/mp4">
                    </video>
                </div>
	
            <?php endif ?>

		</div>

	</div>

</section>