<?php 
//Block Name: Información Repetida

$content_fields = [ 'titulo_informacion',  'subtitulo_informacion',  'encabezado',  'texto_informacion',  'informacion' ];
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

<section id="informacion-repetida" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>

    <?php if (!empty($titulo_informacion)): ?>

        <div class="container my-5">

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="color-primario">
                        <?php echo $titulo_informacion ?> <span class="color-secundario"><?php echo $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>

                </div>
            
            </div>

        </div>

    <?php endif ?>

	<div class="container-fluid">

        <? if( !empty($informacion) && count($informacion) > 0 ): ?>

            <? foreach( $informacion as $key => $item ): extract($item) ?>

                <div class="row justify-content-evenly">

                    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-start justify-content-lg-center <?php if( $multimedia_derecha ) echo 'order-1 order-lg-0'; else echo 'order-1 order-lg-1' ?>" style="border-radius: <?= $radio_de_los_bordes ?>">

                        <div class="col-12 col-md-10 col-lg-9 my-4 pe-lg-5">

                            <div class="d-flex flex-column mt-lg-5 my-lg-5 py-4 py-lg-5">

                                <div class="col-12 col-lg-10 mx-auto">

                                    <?php if(!empty($titulo)): ?>
    
                                        <div class="mb-3 me-5">
    
                                            <h2 class="color-primario">
                                                <?php echo $titulo ?>
                                            </h2>

                                            <?php if($mostrar_fecha): ?>

                                                <div class="d-flex align-items-center justify-content-start my-3">
                                                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                                        <path d="M0 22.6562C0 23.9502 1.19978 25 2.67857 25H22.3214C23.8002 25 25 23.9502 25 22.6562V9.375H0V22.6562ZM17.8571 13.0859C17.8571 12.7637 18.1585 12.5 18.5268 12.5H20.7589C21.1272 12.5 21.4286 12.7637 21.4286 13.0859V15.0391C21.4286 15.3613 21.1272 15.625 20.7589 15.625H18.5268C18.1585 15.625 17.8571 15.3613 17.8571 15.0391V13.0859ZM17.8571 19.3359C17.8571 19.0137 18.1585 18.75 18.5268 18.75H20.7589C21.1272 18.75 21.4286 19.0137 21.4286 19.3359V21.2891C21.4286 21.6113 21.1272 21.875 20.7589 21.875H18.5268C18.1585 21.875 17.8571 21.6113 17.8571 21.2891V19.3359ZM10.7143 13.0859C10.7143 12.7637 11.0156 12.5 11.3839 12.5H13.6161C13.9844 12.5 14.2857 12.7637 14.2857 13.0859V15.0391C14.2857 15.3613 13.9844 15.625 13.6161 15.625H11.3839C11.0156 15.625 10.7143 15.3613 10.7143 15.0391V13.0859ZM10.7143 19.3359C10.7143 19.0137 11.0156 18.75 11.3839 18.75H13.6161C13.9844 18.75 14.2857 19.0137 14.2857 19.3359V21.2891C14.2857 21.6113 13.9844 21.875 13.6161 21.875H11.3839C11.0156 21.875 10.7143 21.6113 10.7143 21.2891V19.3359ZM3.57143 13.0859C3.57143 12.7637 3.87277 12.5 4.24107 12.5H6.47321C6.84152 12.5 7.14286 12.7637 7.14286 13.0859V15.0391C7.14286 15.3613 6.84152 15.625 6.47321 15.625H4.24107C3.87277 15.625 3.57143 15.3613 3.57143 15.0391V13.0859ZM3.57143 19.3359C3.57143 19.0137 3.87277 18.75 4.24107 18.75H6.47321C6.84152 18.75 7.14286 19.0137 7.14286 19.3359V21.2891C7.14286 21.6113 6.84152 21.875 6.47321 21.875H4.24107C3.87277 21.875 3.57143 21.6113 3.57143 21.2891V19.3359ZM22.3214 3.125H19.6429V0.78125C19.6429 0.351562 19.2411 0 18.75 0H16.9643C16.4732 0 16.0714 0.351562 16.0714 0.78125V3.125H8.92857V0.78125C8.92857 0.351562 8.52679 0 8.03571 0H6.25C5.75893 0 5.35714 0.351562 5.35714 0.78125V3.125H2.67857C1.19978 3.125 0 4.1748 0 5.46875V7.8125H25V5.46875C25 4.1748 23.8002 3.125 22.3214 3.125Z" fill="white"/>
                                                    </svg>
                                                    <span class="fs-4 color-primario mb-0 text-uppercase">
                                                        <?php echo $fecha ?>
                                                    </span>
                                                </div>

                                            <?php endif ?>
    
                                        </div>
    
                                    <?php endif ?>
    
                                    <div class="mb-2 me-5 color-secundario">
                                        <?php echo $contenido ?>
                                    </div>
    
                                    <div data-aos="fade-in">
    
                                        <?php if( !empty( $botones_informacion ) AND count( $botones_informacion ) > 0 ): ?>
    
                                            <?php foreach( $botones_informacion as $boton ): ?>
                                                <?php echo get_burger_button( $boton['enlace'], $boton['estilo']) ?>
                                            <?php endforeach ?>
    
                                        <?php endif ?>
    
                                    </div>
                                    
                                </div>

                            </div>

                        </div>

                    </div>

                    <?php if( $mostrar_galeria && $galeria ): ?>

                        <div class="col-12 col-md-12 col-lg-6 px-0 <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0'; ?>">
                                
                            <div id="carouselExampleIndicators" class="carousel h-100 slide" data-bs-ride="carousel">

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

                    <?php elseif( $mostrar_imagen && $imagen && $imagen_de_fondo ): ?>

                        <div class="col-12 col-lg-6 p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>" style="background-image: url( '<?php echo $imagen ?>'); background-size: 100%; border-radius: <?= $radio_de_los_bordes ?>; min-heigth: 220px;"></div>
                    
                    <?php elseif( $mostrar_imagen && $imagen ): ?>
                    
                        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>">
                            <img loading="lazy" decoding="async" src="<?php echo $imagen ?>" 
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

            <? endforeach ?>

        <? endif ?>

	</div>

</section>