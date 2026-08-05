<?php 
//Block Name: Contador

$content_fields = [ 'titulo_contador', 'subtitulo_contador', 'encabezado', 'texto_contador', 'texto_contador_dos', 'contadores' ];
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

<section id="contador" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container">
        
        <?php if(!empty($titulo_contador)): ?>
    
            <<?= $encabezado ?> class="titulo mb-3 mb-lg-5 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="zoom-in">
                <span><?php echo $titulo_contador ?></span> <?php echo $subtitulo_contador ?>
            </<?= $encabezado ?>>
    
        <?php endif; ?>
    
        <?php if(!empty($texto_contador)): ?>
    
            <div class="row mb-5" data-aos="zoom-in">
    
                <div class="col-12 col-md-6 d-flex align-items-start">
    
                    <div class="text-normal-dos medium text-center text-md-end color-primario">
                        <?php echo $texto_contador ?>
                    </div>
    
                </div>
    
                <div class="col-12 col-md-6 d-flex align-items-center">
    
                    <div class="text-normal light text-center text-md-start color-primario">
                        <?php echo $texto_contador_dos ?>
                    </div>
    
                </div>
    
            </div>
    
        <?php endif; ?>
    
        <div class="row d-flex  justify-content-around">
    
            <?php foreach( $contadores as $key => $item ): ?>
    
                <div class="col-6 col-md-4 col-lg-3 text mb-3" data-aos="fade-up">
    
                    <div class="card border-0 bg-transparent">
    
                        <div class="card-body px-0">
    
                            <div class="counter d-flex align-items-center">
    
                                <div class="text-contador me-2 color-primario">
                                    <?php echo $item['icono'] ?>
                                </div>
    
                                <div class="text-contador mb-2 count color-primario">
                                    <?php echo $item['numero'] ?>
                                </div>
    
                            </div>
                            
                            <svg width="64" height="4" viewBox="0 0 64 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="64" height="4" fill="<?= $color_primario ?>"/>
                            </svg>
    
                            <div class="d-flex align-items-end">
    
                                <div class="fs-4 mt-3 color-secundario">
                                    <?php echo $item['titulo'] ?>
                                </div>
    
                            </div>
    
                        </div>
    
                    </div>
    
                </div>
    
            <?php endforeach ?>
    
        </div>

    </div>

</section>