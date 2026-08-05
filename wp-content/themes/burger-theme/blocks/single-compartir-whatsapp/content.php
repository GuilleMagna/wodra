<?php 
//Block Name: Single Compartir Whatsapp
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'titulo_compartir' ];
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

<section id="single-compartir-whatsapp" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>

    <div class="row">

        <div class="col-12 text-center">

            <nav>
                <ul class="d-flex justify-content-between align-items-center">
                    
                    <?php if ( $prev_post = get_previous_post_by_id( $post ) ) : ?>

                        <li class="page-item d-flex align-items-start">

                            <a class="page-numbers text-uppercase medium d-flex justify-content-start align-items-center pt-0" href="<?php echo get_permalink( $prev_post->ID ) ?>">
                                <i class="fas fa-chevron-left me-3" style="padding: 10px 13px; border: 1px #cfcfd0 solid"></i><?php echo $prev_post->post_title ?>
                            </a>

                        </li>

                    <?php endif; ?>

                    <div class="d-flex align-items-center">

                        <p class="text-big text-dark ligth mb-0 d-none d-md-block me-3">
                            <?php echo $titulo_compartir; ?>
                        </p>

                        <div>
                            <?php echo get_burger_button ( 
                                [
                                    'url'    => 'https://api.whatsapp.com/send?text=' . ( $post->post_title . ' ' . $post->post_permalink ),
                                    'title'  => 'COMPARTIR',
                                    'target' => '_self',
                                ],
                                'btn-primario'
                            ) ?>
                        </div>

                    </div>

                    <?php if ( $next_post = get_next_post_by_id( $post ) ) : ?>
                                    
                        <li class="page-item d-flex align-items-start">

                            <a class="page-numbers text-uppercase medium d-flex justify-content-end align-items-center pt-0" href="<?php echo get_permalink( $next_post->ID ) ?>">
                                <?php echo $next_post->post_title ?><i class="fas fa-chevron-right ms-3" style="padding: 10px 13px; border: 1px #cfcfd0 solid"></i>
                            </a>

                        </li>
                        
                    <?php endif; ?>


                </ul>

            </nav>
            
        </div>

        <div class="d-flex justify-content-center align-items-start">
            
            <div class="d-block d-md-none mt-5">

                <p class="text-big text-dark ligth">
                    <span class="medium">Compartí este proyecto</span> ahora!
                </p>

                <?php echo get_burger_button ( 
                    [
                        'url'    => 'https://api.whatsapp.com/send?text=' . ( $post->post_title . ' ' . $post->post_permalink ),
                        'title'  => 'COMPARTIR',
                        'target' => '_self',
                    ],
                    'btn-primario'
                ) ?>

            </div>

        </div>

    </div>

</section>