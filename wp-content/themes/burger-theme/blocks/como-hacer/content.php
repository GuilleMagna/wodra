<?php 
//Block Name: Como Hacer

$content_fields = [ 'titulo_como_hacer', 'subtitulo_como_hacer', 'encabezado', 'pasos', 'mostrar_solicitar_asesor', 'logo_solicitar_asesor', 'titulo_solicitar_asesor', 'subtitulo_solicitar_asesor', 'boton_solicitar_asesor', 'estilo_boton_solicitar_asesor' ];
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
        background-repeat: no-repeat;
        background-size: cover;
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

<section id="como-hacer" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container pb-5">

        <div class="row">

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                <<?= $encabezado ?> class="titulo mb-5">
                    <?php echo $titulo_como_hacer ?><br>
                    <span><?php echo $subtitulo_como_hacer ?></span>
                </<?= $encabezado ?>>
            
            </div>

        </div>

        <?php if( !empty($pasos) and count($pasos) > 0 ): ?>

            <div class="row">

                <?php foreach( $pasos as $key => $item ): ?>

                    <?php 
                    $imagen    = $item['imagen']; 
                    $titulo    = $item['titulo']; 
                    $texto     = $item['texto']; 
                    ?>

                    <div class="col-12 col-md-6 col-lg-3">

                        <div class="card card-como-comprar bg-transparent border-0">

                            <div class="card-header d-flex align-items-center bg-transparent border-0 px-4">

                                <div class="text-parallax color-secundario pe-3 fw-bolder">
                                    <?php echo $key+1 ?><span class="color-primario">.</span>
                                </div>
                                <img loading="lazy" decoding="async" src="<?php echo $imagen ?>" class="img-responsive img-fluid img-iconos" alt="<?php echo esc_html($titulo) ?>" /> 

                            </div>

                            <div class="card-body px-4">

                                <h5 class="titulo card-title color-secundario">
                                    <?php echo $titulo ?>
                                </h5>

                                <p class="card-text color-secundario">
                                    <?php echo $texto ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach ?>

            </div>

        <?php endif ?>

    </div>

    <?php if( !empty( $mostrar_solicitar_asesor ) ): ?>

        <div class="container mt-4">

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <div class="row justify-content-around align-items-center">

                        <div class="col-5 col-md-3 col-lg-3 d-flex align-items-center justify-content-center mx-auto mx-lg-0 mb-4 mb-lg-0">
                            <img loading="lazy" decoding="async" src="<?php echo $logo_solicitar_asesor ?>" alt="logo-empresa" class="img-fluid" data-aos="fade-in">
                        </div>  

                        <div class="col-12 col-md-6 col-lg-4 d-flex align-items-center justify-content-center mb-4 mb-lg-0">

                            <h5 class="mb-0 text-white" data-aos="fade-in" data-aos-delay="200"  style="text-transform: none;">
                                <?php echo $titulo_solicitar_asesor ?><br>
                                <span class="text-secondary"><?php echo $subtitulo_solicitar_asesor ?></span>
                            </h5>

                        </div> 
                        
                        <?php if( !empty( $boton_solicitar_asesor ) ): ?>

                            <div class="col-12 col-md-3 col-lg-auto d-flex align-items-center justify-content-center justify-content-lg-start">

                                <?php
                                    echo burger_render_button(
                                        [
                                            'url'    => (esc_url($boton_solicitar_asesor['url'])),
                                            'title'  => (esc_html($boton_solicitar_asesor['title'])),
                                            'target' => (esc_html($boton_solicitar_asesor['target'])),
                                        ],
                                        'btn ' . ($estilo_boton_solicitar_asesor),
                                        [
                                            'span_class'  => 'text-btn-01',
                                        ]
                                    );
                                ?>

                            </div>

                        <?php endif ?>

                    </div>

                </div>

            </div>

        </div>

    <?php endif ?>    

</section>