<?php 
//Block Name: Productos

$content_fields = [ 'titulo_productos',  'subtitulo_productos', 'encabezado', 'productos',  'mostrar_proyectos',  'titulo_proyectos',  'boton_proyectos' ];
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

<section id="productos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <div class="text-start border-start border-primary border-4 mb-5 ps-3">
            
            <<?= $encabezado ?> class="titulo text-big light mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <?php echo $titulo_productos ?><br>
                <span class="medium"><?php echo $subtitulo_productos ?></span>
            </<?= $encabezado ?>>

        </div>

    </div>

    <?php if( !empty($productos) and count($productos) > 0 ): ?>

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-10">

                    <div class="swiper mySwiperData">

                        <div class="swiper-wrapper container">

                            <?php foreach( $productos as $post ): ?>

                                <?
                                $post->post_thumbnail = '';
                                if( has_post_thumbnail( $post->ID ) )
                                    $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );

                                $post->post_permalink = get_permalink( $post->ID );
                                $post->atributos = get_field( 'atributos_producto', $post->ID );
                                ?>

                                <div class="swiper-slide">

                                    <div class="card card-productos">

                                        <div class="row h-100">

                                            <div class="col-12 col-md-3 img position-relative" style="background-image: url( '<?= $post->post_thumbnail ?>');">

                                                <div class="badge bg-primary text-white rounded-pill px-4 py-2 text-normal-dos light">
                                                    <?php echo $titulo_categoria ?>
                                                </div>

                                            </div>

                                            <div class="col-12 col-md-9">

                                                <div class="card-body rounded-0 border-0 d-flex flex-column h-100 p-5">

                                                    <div class="d-flex align-items-start flex-column bd-highlight h-100">

                                                        <h5 class="color-primario text-big regular mb-3 text-uppercase bd-highlight">
                                                            <?= $post->post_title ?>
                                                        </h5>

                                                        <div  class="text-dark text-normal bd-highlight">
                                                            <?= $post->post_excerpt ?>
                                                        </div>

                                                        <div class="mt-auto bd-highlight">

                                                            <?php
                                                            echo burger_render_button(
                                                                [
                                                                    'url'    => ($post->post_permalink),
                                                                    'title'  => ($post->post_title),
                                                                    'target' => '_blank',
                                                                ],
                                                                'btn btn-outline-primary',
                                                                [
                                                                    'span_class'  => 'text-btn-01',
                                                                ]
                                                            );
                                                            ?>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach ?>

                        </div>

                    </div>

                </div>

                <?php if( !empty($productos) && count($productos) > 1 ): ?>

                    <div class="col-12 col-lg-2 position-relative mySwiperNav mt-5 mt-lg-0">

                        <svg class="swiper-button-prev" style="height: 55px; width: 55px" xmlns="http://www.w3.org/2000/svg" width="87" height="87" viewBox="0 0 87 87">
                            <g id="Group_1627" data-name="Group 1627" transform="translate(-1537 -1649)">
                                <g id="Group_1626" data-name="Group 1626" transform="translate(1537 1649)">
                                <g id="Path_16747" data-name="Path 16747" fill="none">
                                    <path d="M43.5,0A43.5,43.5,0,1,0,87,43.5,43.5,43.5,0,0,0,43.5,0Z" stroke="none"/>
                                    <path d="M 43.5 2 C 37.89664840698242 2 32.46197509765625 3.096977233886719 27.34693908691406 5.260452270507812 C 22.40538787841797 7.350547790527344 17.96700286865234 10.3431396484375 14.15505981445312 14.15505981445312 C 10.3431396484375 17.96700286865234 7.350547790527344 22.40538787841797 5.260452270507812 27.34693908691406 C 3.096977233886719 32.46197509765625 2 37.89664840698242 2 43.5 C 2 49.10335159301758 3.096977233886719 54.53802490234375 5.260452270507812 59.65306091308594 C 7.350547790527344 64.59461212158203 10.3431396484375 69.03300476074219 14.15505981445312 72.84494018554688 C 17.96700286865234 76.6568603515625 22.40538787841797 79.64945220947266 27.34693908691406 81.73954772949219 C 32.46197509765625 83.90302276611328 37.89664840698242 85 43.5 85 C 49.10335159301758 85 54.53802490234375 83.90302276611328 59.65306091308594 81.73954772949219 C 64.59461212158203 79.64945220947266 69.03300476074219 76.6568603515625 72.84494018554688 72.84494018554688 C 76.6568603515625 69.03300476074219 79.64945220947266 64.59461212158203 81.73954772949219 59.65306091308594 C 83.90302276611328 54.53802490234375 85 49.10335159301758 85 43.5 C 85 37.89664840698242 83.90302276611328 32.46197509765625 81.73954772949219 27.34693908691406 C 79.64945220947266 22.40538787841797 76.6568603515625 17.96700286865234 72.84494018554688 14.15505981445312 C 69.03300476074219 10.3431396484375 64.59461212158203 7.350547790527344 59.65306091308594 5.260452270507812 C 54.53802490234375 3.096977233886719 49.10335159301758 2 43.5 2 M 43.5 0 C 67.52438354492188 0 87 19.47560882568359 87 43.5 C 87 67.52438354492188 67.52438354492188 87 43.5 87 C 19.47560882568359 87 0 67.52438354492188 0 43.5 C 0 19.47560882568359 19.47560882568359 0 43.5 0 Z" stroke="none" fill="#2d3a48"/>
                                </g>
                                <path id="Path_16744" data-name="Path 16744" d="M1768.183,82.667,1752,100.006l16.183,15.027" transform="translate(-1716.699 -55.033)" fill="none" stroke="#2d3a48" stroke-width="2"/>
                                </g>
                            </g>
                        </svg>

                        <svg  class="swiper-button-next" style="height: 55px; width: 55px" xmlns="http://www.w3.org/2000/svg" width="87" height="87" viewBox="0 0 87 87">
                            <g id="Group_1625" data-name="Group 1625" transform="translate(-1537 -1568)">
                                <g id="Path_16747" data-name="Path 16747" transform="translate(1537 1568)" fill="none">
                                    <path d="M43.5,0A43.5,43.5,0,1,1,0,43.5,43.5,43.5,0,0,1,43.5,0Z" stroke="none"/>
                                    <path d="M 43.5 2 C 37.89664840698242 2 32.46197509765625 3.096977233886719 27.34693908691406 5.260452270507812 C 22.40538787841797 7.350547790527344 17.96700286865234 10.3431396484375 14.15505981445312 14.15505981445312 C 10.3431396484375 17.96700286865234 7.350547790527344 22.40538787841797 5.260452270507812 27.34693908691406 C 3.096977233886719 32.46197509765625 2 37.89664840698242 2 43.5 C 2 49.10335159301758 3.096977233886719 54.53802490234375 5.260452270507812 59.65306091308594 C 7.350547790527344 64.59461212158203 10.3431396484375 69.03300476074219 14.15505981445312 72.84494018554688 C 17.96700286865234 76.6568603515625 22.40538787841797 79.64945220947266 27.34693908691406 81.73954772949219 C 32.46197509765625 83.90302276611328 37.89664840698242 85 43.5 85 C 49.10335159301758 85 54.53802490234375 83.90302276611328 59.65306091308594 81.73954772949219 C 64.59461212158203 79.64945220947266 69.03300476074219 76.6568603515625 72.84494018554688 72.84494018554688 C 76.6568603515625 69.03300476074219 79.64945220947266 64.59461212158203 81.73954772949219 59.65306091308594 C 83.90302276611328 54.53802490234375 85 49.10335159301758 85 43.5 C 85 37.89664840698242 83.90302276611328 32.46197509765625 81.73954772949219 27.34693908691406 C 79.64945220947266 22.40538787841797 76.6568603515625 17.96700286865234 72.84494018554688 14.15505981445312 C 69.03300476074219 10.3431396484375 64.59461212158203 7.350547790527344 59.65306091308594 5.260452270507812 C 54.53802490234375 3.096977233886719 49.10335159301758 2 43.5 2 M 43.5 0 C 67.52438354492188 0 87 19.47560882568359 87 43.5 C 87 67.52438354492188 67.52438354492188 87 43.5 87 C 19.47560882568359 87 0 67.52438354492188 0 43.5 C 0 19.47560882568359 19.47560882568359 0 43.5 0 Z" stroke="none" fill="#2d3a48"/>
                                </g>
                                <path id="Path_16744" data-name="Path 16744" d="M1752,82.667l16.183,17.339L1752,115.033" transform="translate(-179.484 1512.967)" fill="none" stroke="#2d3a48" stroke-width="2"/>
                            </g>
                        </svg>

                    </div>
                
                <?php endif ?>

            </div>

        </div>

    <?php endif ?>

    <?php if( $mostrar_proyectos ): ?>

        <div class="container pt-5">

            <div class="d-md-flex justify-content-center">

                <div class="d-flex align-items-center mt-5 mt-md-0">

                    <p class="text-big color-secundario light mb-0 mp-0 text-center text-md-start">
                        <?= $titulo_proyectos ?>
                    </p>

                </div>

                <?php if( !empty( $boton_proyectos ) and count( $boton_proyectos ) > 0 ): ?>

                    <div class="text-center text-md-start mt-3 mt-md-0">
                        <?php
echo burger_render_button(
    [
        'url'    => ($boton_proyectos['url']),
        'title'  => ($boton_proyectos['title']),
        'target' => ($boton_proyectos['target']),
    ],
    'btn btn-lg btn-primary text-normal regular text-white ms-0 ms-md-4',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>
                    </div>

                <?php endif ?>

            </div>

        </div>

    <?php endif ?>

</section>