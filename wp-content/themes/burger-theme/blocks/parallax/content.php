<?php 
//Block Name: Parallax

$content_fields = [ 'imagen_parallax', 'mostrar_texto_parallax', 'texto_parallax', 'mostrar_logo_parallax', 'logo_parallax', 'botones_parallax', 'alineacion_contenido_parallax', 'mostrar_overlay_parallax', 'inicio_overlay_parallax', 'final_overlay_parallax', 'invertir_columnas' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$content_align = $text_align = '';
if( $alineacion_contenido_parallax == 'center' ){
    $content_align = 'justify-content-center';
    $text_align = 'text-center';
}elseif( $alineacion_contenido_parallax == 'right' ){
    $content_align = 'justify-content-end';
    $text_align = 'text-center text-lg-end';
}else {
    $content_align = 'justify-content-between';
}

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

<section id="parallax" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>
    
    <div class="parallax position-relative" style="<?php if( $mostrar_overlay_parallax ): ?> background: -webkit-linear-gradient(90deg, <?php echo $inicio_overlay_parallax ?> 0%, <?php echo $final_overlay_parallax ?> 100%), url('<?php echo $imagen_parallax ?>');<?php else: ?>background-image: url('<?php echo $imagen_parallax ?>');<?php endif ?>">

        <div class="overlay-image"></div> 
            
        <div class="section container contenido overflow-hidden">
    
            <div class="row py-5 my-5 <?php echo $content_align ?>">

                <?php if( $mostrar_texto_parallax || $mostrar_boton_parallax ): ?>

                    <div class="col-12 col-md-6 mb-4 mb-md-0 d-flex align-items-center <?=($invertir_columnas===true) ? 'order-1' : 'order-0'; ?> <?php echo $content_align ?> <?php echo $text_align ?>" data-sal="fade-down" data-sal-duration="500">

                        <div data-aos="fade-right">
                            
                            <div class="text-parallax text-white mx-auto p-5 p-md-0 <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
            
                                <?php if( $mostrar_texto_parallax ): ?>
                                    <?php echo $texto_parallax ?>
                                <?php endif ?>
            
                            </div>

                            <?php if( !empty( $botones_parallax ) AND count( $botones_parallax ) > 0 ): ?>

                                <div class="text-start">

                                    <?php foreach( $botones_parallax as $boton ): ?>
                                        <?php echo get_burger_button( $boton['enlace'], $boton['estilo']) ?>
                                    <?php endforeach ?>

                                </div>

                            <?php endif ?>

                        </div>

                    </div>

                <?php endif ?>
                
                <?php if( $mostrar_logo_parallax ): ?>

                    <div class="col-12 col-md-5 col-lg-4 d-flex align-items-center <?=($invertir_columnas===true) ? 'order-0' : 'order-1'; ?>" data-sal="fade-in" data-sal-duration="500">
                        <img loading="lazy" decoding="async" src="<?php echo $logo_parallax ?>" class="img-fluid p-5 p-md-0" alt="imagen parallax" data-aos="fade-up">
                    </div>
                    
                <?php endif ?>

            </div>
    
        </div>

    </div>

</section>