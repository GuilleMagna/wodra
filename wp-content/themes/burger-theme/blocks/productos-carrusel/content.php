<?php 
//Block Name: Productos Carrusel

$content_fields = [ 'titulo_productos',  'subtitulo_productos', 'encabezado', 'productos',  'mostrar_boton_proyectos',  'boton_proyectos' ];
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

<section id="productos-carrusel" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>"> 

            <<?= $encabezado ?> class="titulo mb-5">
                <?php echo $titulo_productos ?> <span><?php echo $subtitulo_productos ?></span>
            </<?= $encabezado ?>>

        </div>

        <?php if( !empty( $productos ) && count( $productos ) > 0 ): ?>

            <div class="owl-carousel owl-proyectos-destacados owl-theme mb-4">

                <?php foreach( $productos as $key => $post ): ?>
                                    
                    <?php 
                    $post->post_thumbnail = '';
                    if( has_post_thumbnail( $post->ID ) )
                        $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );

                    $post->post_permalink = get_permalink( $post->ID );
                    $post->atributos = get_field( 'atributos_producto', $post->ID );
                    ?>

                    <div class="item">

                        <div class="card border-light rounded-5  rounded-0 card-productos mb-3" style="overflow: hidden;" data-aos="fade-in" data-aos-delay="<?php echo $key*200 ?>">

                            <a href="<?php echo $post->post_permalink ?>" style="text-decoration: none;" title="<?php echo $post->post_title ?>">
                                <div class="card-header img img-productos border-0 rounded-0 mb-2" style="background-image: url( <?php echo $post->post_thumbnail ?> );"></div>
                            </a>

                            <div class="card-body d-flex flex-column px-4">

                                <h5 class="mb-4">
                                    <?php echo $post->post_title ?>
                                </h5>

                                <div>

                                    <p class="mb-1">
                                        <?php if( !empty($post->atributos) and count($post->atributos) > 0 ) foreach( $post->atributos as $value ): ?>
                                            <b><?php echo $value['nombre'] ?>:</b>&nbsp;<?php echo $value['valor'] ?><br>
                                        <?php endforeach ?>     
                                    </p>

                                </div>

                                <div>

                                    <?php
                                    echo burger_render_button(
                                        [
                                            'url'    => ($post->post_permalink),
                                            'title'  => 'Conocer más',
                                            'target' => '_self',
                                        ],
                                        'btn bg-transparent px-0',
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

        <?php endif ?>

        <?php if( $mostrar_boton_proyectos ): ?>

            <div class="d-flex justify-content-center">

                <?php if( !empty( $boton_proyectos ) and count( $boton_proyectos ) > 0 ): ?>
                    
                    <?php
echo burger_render_button(
    [
        'url'    => ($boton_proyectos['url']),
        'title'  => ($boton_proyectos['title']),
        'target' => ($boton_proyectos['target']),
    ],
    'btn bg-transparent px-0',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>

                <?php endif ?>

            </div>

        <?php endif ?>

	</div>
    
</section>
