<?php 
//Block Name: Testimonios

$content_fields = [ 'titulo_testimonios', 'subtitulo_testimonios', 'encabezado', 'testimonios' ];
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

<section id="testimonios" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>

    <div class="container pt-5">
        
        <?php if( !empty($titulo_testimonios) OR !empty($subtitulo_testimonios) ): ?>

            <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?> mb-2">

                <<?= $encabezado ?> class="titulo fw-bold fs-2" data-aos="fade-in">
                    <span><?= $titulo_testimonios ?></span> <?= $subtitulo_testimonios ?>
                </<?= $encabezado ?>>

            </div>

        <?php endif ?>

        <div class="row justify-content-center">

            <div class="col-12 col-md-10" data-aos="fade-in">

                <div class="card rounded-5 border-0 position-relative card-bg">

                    <div class="card-body p-2 p-md-3 py-md-5">

                        <div class="owl-carousel owl-comentarios owl-theme bg-light" style="border-radius:0 6rem 0 0">

                            <?php if( !empty( $testimonios ) and count( $testimonios ) > 0 ) foreach( $testimonios as $item ): ?>

                                <div class="item">  

                                    <div class="row align-items-center">

                                        <div class="col-12 col-md-3 col-lg-4 mx-2 mx-lg-auto d-flex align-items-center justify-content-center">
                                            <img src="<?php echo $item['imagen'] ?>" alt="logo <?php echo $item['empresa'] ?>" class="w-75 img-fluid py-5 px-5 px-md-0">
                                        </div>

                                        <div class="col-12 col-md-8 col-lg-7 me-5">

                                            <div class="text-center text-md-start px-4 px-lg-0">

                                                <div class="comentario mb-4">
                                                    <?php echo $item['texto'] ?>
                                                </div>

                                                <div>
                                                    <p class="mb-1 text-secondary">
                                                        <?php echo $item['autor'] ?>
                                                    </p>
                                                    <p class="text-primary mb-0">
                                                        <?php echo $item['cargo'] ?>
                                                    </p>
                                                    <p class="text-primary mb-0">
                                                        <?php echo $item['empresa'] ?>
                                                    </p>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach ?>

                        </div>
                        
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>