<?php 
//Block Name: Single acordeon
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'titulo_acordeon', 'subtitulo_acordeon', 'encabezado', 'acordeon' ];
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

<section id="single-acordeon" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container">

        <div class="text-start border-start border-primary border-4 mb-5 ps-3">

            <<?= $encabezado ?> class="titulo text-big-titulos text-dark light mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" style="text-transform: none; letter-spacing: 0px;">
                <?php echo $titulo_acordeon ?><br>
                <span class="text-big-titulos medium"><?php echo $subtitulo_acordeon ?></span>
            </<?= $encabezado ?>>

        </div>

    </div>

    <div class="container">

        <div class="accordion bg-transparent" id="accordionExample">

            <?php if( !empty( $acordeon ) and count( $acordeon ) > 0 ) foreach( $acordeon as $key => $value ): ?>

                <?php 
                $titulo             = $value['titulo'] ?? ''; 
                $mostrar_imagen     = $value['mostrar_imagen'] ?? ''; 
                $imagen             = $value['imagen'] ?? BURGER_THEME_URL .'/themes/images/proyecto-empleado.png';
                $contenido          = $value['contenido'] ?? '';  
                $mostrar_boton      = $value['mostrar_boton'] ?? ''; 
                $titulo_boton       = $value['titulo_boton'] ?? ''; 
                $boton              = $value['boton'] ?? []; 
                ?>

                <div class="accordion-item border-0">

                    <h2 class="accordion-header" id="heading<?php echo $key ?>">

                        <button class="accordion-button bg-transparent text-normal-dos text-dark border-0 border-bottom" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key ?>" <?php if( $key==0 ) echo 'aria-expanded="true"'; else echo 'aria-expanded="false"' ?> aria-controls="collapse<?php echo $key ?>">
                            <?php echo $titulo; ?>
                        </button>

                    </h2>

                    <div id="collapse<?php echo $key ?>" class="accordion-collapse collapse <?php if( $key==0 ) echo 'show' ?>" aria-labelledby="heading<?php echo $key ?>" data-bs-parent="#accordionExample">

                        <div class="accordion-body">

                            <div class="card bg-transparent border-0">

                                <div class="card-body">

                                    <div class="row">

                                        <?php if( $mostrar_imagen ): ?>

                                            <div class="col-12 col-md-5 img" style="background-image: url( '<?php echo $imagen ?>');"></div>

                                            <div class="col-12 col-md-7 text-dark text-normal py-5 border-0">

                                                <div class="card border-0">

                                                    <div class="card-body  border-0 text-dark text-normal regular">

                                                        <div style="line-height: 25px;" class="medium">
                                                            <?php echo $contenido ?>
                                                        </div>

                                                        <?php if( $mostrar_boton ): ?>

                                                            <div class="d-md-flex justify-content-center mt-4">

                                                                <div class="d-flex align-items-center mt-5 mt-md-0 col">

                                                                    <div class="text-big text-dark light text-center text-md-start">
                                                                        <span class="medium"><?php echo $titulo_boton ?></span>
                                                                    </div>	

                                                                </div>
                                                            
                                                                <?php if( !empty( $boton ) and count( $boton ) > 0 ): ?>

                                                                    <div class="text-center text-md-start mt-3 mt-md-0 col-auto">

                                                                        <?php
                                                                        echo burger_render_button(
                                                                            [
                                                                                'url'    => ($boton['url']),
                                                                                'title'  => ($boton['title']),
                                                                                'target' => ($boton['target']),
                                                                            ],
                                                                            'btn btn-lg btn-outline-primary text-normal regular ms-0 ms-md-4',
                                                                            [
                                                                                'span_class'  => 'text-btn-01',
                                                                            ]
                                                                        );
                                                                        ?>

                                                                    </div>

                                                                <?php endif ?>

                                                            </div>

                                                        <?php endif ?>

                                                    </div>

                                                </div>

                                            </div>

                                        <?php else: ?>

                                            <?php echo $contenido ?>

                                        <?php endif ?>

                                    </div>

                                </div>

                            </div>

                        </div>
                        
                    </div>

                </div>

            <?php endforeach ?>

        </div>

    </div>

</section>