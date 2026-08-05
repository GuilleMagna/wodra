<?php 
//Block Name: Relacionados dos

$content_fields = [ 'titulo_servicios_otros', 'subtitulo_servicios_otros', 'encabezado', 'servicios_otros' ];
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

<section id="relacionados-dos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container mb-5">

        <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

            <<?= $encabezado ?> class="titulo mb-0" data-aos="fade-up">
                <?php echo $titulo_servicios ?> <span><?php echo $subtitulo_servicios ?></span>
            </<?= $encabezado ?>>

        </div>
        
    </div>

    <? if( !empty($servicios_otros) && count($servicios_otros) > 0 ): ?>

        <div class="container-fluid px-0">

            <div class="row g-0">

                <?php foreach( $servicios_otros as $key => $post ): ?>

                    <?php
                    $post->post_thumbnail = '';
                    if( has_post_thumbnail( $post->ID ) ){
                        $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
                    }
                
                    $post->post_permalink = get_permalink( $post->ID );
                    $post->post_excerpt = get_field( 'descripcion_para_home', $post->ID );
                    ?>

                    <div class="col-12 col-lg-4" data-aos="fade-in" data-aos-delay="<?php echo $key ?>00">

                        <div class="card card-servicios border-0 rounded-0 position-relative" style="background-image: url( '<?php echo $post->post_thumbnail ?>'); background-position: center; background-size: cover; background-position: center;">
                            
                            <div class="overlay-servicios-inicio position-absolute h-100 w-100"></div>
                            <!-- <div class="overlay-servicios-fin position-absolute h-100 w-100"></div> -->

                            <div class="card-body position-relative d-flex align-items-end p-4 p-lg-5">

                                <div class="col-12 px-lg-3">

                                    <h3 class="text-white">
                                        <?= $post->post_title ?>                                    
                                    </h3>

                                    <div class="text-white pb-4">
                                        <?= $post->post_excerpt ?>
                                    </div>

                                    <?php
echo burger_render_button(
    [
        'url'    => ($post->post_permalink),
        'title'  => 'CONOCER MÁS',
        'target' => '_self',
    ],
    'btn btn-outline-conocer',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>

                                </div>

                            </div>

                        </div>		

                    </div>

                <?php endforeach ?>

            </div>

        </div>

    <? endif ?>

</section>