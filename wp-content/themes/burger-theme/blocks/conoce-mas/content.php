<?php
//Block Name: Conoce más

$content_fields = ['titulo_conoce_mas', 'subtitulo_conoce_mas', 'encabezado', 'carga_automatica', 'tipo_publicacion', 'cantidad_publicaciones', 'servicios', 'items', 'items_mobile', 'items_tablet', 'items_desktop', 'margin', 'autoplay', 'nav', 'dots', 'loop', 'overflow', 'mostrar_boton_final', 'boton_final', 'estilo_del_boton_final', 'tipo_de_imagen' ];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);
if ( ! empty( $carga_automatica ) ) {
    $tipo_publicacion = in_array( $tipo_publicacion, [ 'post', 'evento' ], true ) ? $tipo_publicacion : 'post';
    $cantidad_publicaciones = min( 24, max( 1, absint( $cantidad_publicaciones ?: 6 ) ) );
    $query_args = [
        'post_type'        => $tipo_publicacion,
        'post_status'      => 'publish',
        'posts_per_page'   => $cantidad_publicaciones,
        'suppress_filters' => false,
        'no_found_rows'    => true,
    ];

    if ( 'evento' === $tipo_publicacion ) {
        $query_args['meta_key'] = 'fecha_evento';
        $query_args['meta_query'] = [ [
            'key' => 'fecha_evento',
            'value' => current_time( 'Ymd' ),
            'compare' => '>=',
            'type' => 'NUMERIC',
        ] ];
        $query_args['orderby'] = 'meta_value_num';
        $query_args['order'] = 'ASC';
    } else {
        $query_args['orderby'] = 'date';
        $query_args['order'] = 'DESC';
    }

    $servicios = [];
    foreach ( get_posts( $query_args ) as $publicacion ) {
        $texto = has_excerpt( $publicacion )
            ? get_the_excerpt( $publicacion )
            : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $publicacion->post_content ) ), 24 );

        if ( 'evento' === $tipo_publicacion ) {
            $fecha_guardada = get_post_meta( $publicacion->ID, 'fecha_evento', true );
            $fecha_objeto = DateTime::createFromFormat( '!Ymd', (string) $fecha_guardada, wp_timezone() );
            if ( $fecha_objeto ) {
                $fecha = date_i18n( 'd F Y', $fecha_objeto->getTimestamp() );
                $texto = '<time datetime="' . esc_attr( $fecha_objeto->format( 'Y-m-d' ) ) . '"><strong>' . esc_html( $fecha ) . '</strong></time>'
                    . ( $texto ? '<br>' . $texto : '' );
            }
        }

        $servicios[] = [
            'imagen' => get_the_post_thumbnail_url( $publicacion, 'large' ) ?: '',
            'titulo' => get_the_title( $publicacion ),
            'texto'  => $texto,
            'boton'  => [
                'title' => 'Conocer más',
                'url' => get_permalink( $publicacion ),
                'target' => '_self',
            ],
            'estilo_del_boton' => 'btn-texto-blanco',
        ];
    }
}

$design = get_block_design($block);
extract($design);

$block_id = $block['id'];
?>

<style>
    .<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?>;
        background-image: url('<?= $imagen_fondo ?>');
        background-size: cover;
        background-repeat: no-repeat;
        --carousel-dot-color: <?= $color_primario ?: 'var(--primary)' ?>;
    }
    .<?= $block_id ?> .titulo,
    .<?= $block_id ?> .color-secundario {
        color: <?php echo $color_secundario ?>;
        margin-inline: auto;
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?php echo $color_primario ?>
    }
    .<?= $block_id ?> #owl-slider {
        overflow: <?php echo $overflow ?>;
    }
    .<?= $block_id ?> #owl-slider #owl-slider-<?= $block_id ?> {
        overflow: <?php echo $overflow ?>;
    }
    /* .owl-stage-outer es la ventana que recorta el carrusel: si queda en
       overflow visible, la tira completa de slides sobresale del contenedor y
       genera scroll horizontal en toda la página. */
    .<?= $block_id ?> #owl-slider #owl-slider-<?= $block_id ?> .owl-stage-outer {
        overflow: hidden;
    }
    .<?= $block_id ?> .owl-conoce-mas {
        position: relative;
    }

    #<?= $block_id ?> .owl-theme .owl-dots {
        text-align: center;
        -webkit-tap-highlight-color: transparent;
        margin-top: 30px;
    }

    #<?= $block_id ?> .owl-theme .owl-dots .owl-dot span {
        width: 8px;
        height: 8px;
        margin: 7px 10px;
        background: #ccc;
        display: block;
        opacity: 0.5;
        transition: opacity 200ms ease;
        border-radius: 30px;
    }

    #<?= $block_id ?> .owl-theme .owl-dots .owl-dot span {
        background: var(--primary);
        border: 1px var(--primary) solid !important;
    }

    #<?= $block_id ?> .owl-theme .owl-dots .owl-dot:hover span {
        background: var(--primary) !important;
        transition: all 200ms ease;
        opacity: 1;
    }

    #<?= $block_id ?> .owl-theme .owl-dots .owl-dot.active span {
        background: var(--primary) !important;
        opacity: 1;
        transform: scale(2);
        transition: all 200ms ease;
    }

</style>

<section id="conoce-mas" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

            <<?= $encabezado ?> class="titulo mb-5 pb-4" data-aos="fade-up">
                <?= $titulo_conoce_mas ?><span><?= $subtitulo_conoce_mas ?></span>
            </<?= $encabezado ?>>

        </div>

        <div id="owl-slider-<?php echo $block['id'] ?>" class="owl-carousel owl-conoce-mas owl-theme">

            <?php if (!empty($servicios) && count($servicios) > 0): ?>

                <? foreach ($servicios as $key => $item): extract($item); ?>

                    <?php
                    $tiene_enlace = false;
                    if (!empty($boton) and count($boton) > 0)
                        $tiene_enlace = true;
                    ?>

                    <div class="item">

                        <div class="card-conoce-mas text-center">

                            <?php if($tipo_de_imagen === true): ?>

                                <div class="card-conoce-img mb-3">
                                    <img loading="lazy" decoding="async" class="mx-auto" style="width: auto;" src="<?php echo esc_url($imagen) ?>" alt="<?php echo esc_attr($titulo) ?>">
                                </div>

                            <?php else:?>

                                <div class="card-conoce-img rounded-4 mb-3" alt="<?php echo esc_attr($titulo) ?>" style="aspect-ratio: 1/1; background-image: url('<?php echo esc_url($imagen) ?>'); background-position: center; background-size: cover;"></div>

                            <?php endif;?>

                            <h3 class="color-primario card-conoce-fecha mb-3">
                                <?php echo $titulo ?>
                            </h3>

                            <?php if ( !empty($texto) ): ?>
                                <div class="color-secundario card-conoce-texto mb-4">
                                    <?php echo $texto ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($tiene_enlace): ?>
                                <?php echo get_burger_button($boton, $estilo_del_boton ); ?>
                            <?php endif ?>

                        </div>

                    </div>

                <?php endforeach ?>

            <? endif ?>

        </div>

        <? if( $mostrar_boton_final && $boton_final ): ?>

            <div class="text-center mt-5">
                <?php echo get_burger_button($boton_final, $estilo_del_boton_final ); ?>
            </div>

        <? endif ?>

    </div>

</section>

<script>
    jQuery(document).ready(function ($) {
        $('#owl-slider-<?php echo $block['id'] ?>').owlCarousel({
            center: false,
            loop: <?php echo $loop ?>,
            items: <?php echo $items ?>,
            margin: <?php echo $margin ?>,
            autoplay: <?php echo $autoplay ?>,
            nav: <?php echo $nav ?>,
            dots: <?php echo $dots ?>,
            navText: [
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.6666 14.167L15.8333 20.0003L21.6666 25.8337" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 36.6663C10.7953 36.6663 3.33329 29.2043 3.33329 19.9997C3.33329 10.7949 10.7953 3.33301 20 3.33301C29.2047 3.33301 36.6666 10.7949 36.6666 19.9997C36.6666 29.2043 29.2047 36.6663 20 36.6663Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3333 14.167L24.1666 20.0003L18.3333 25.8337" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.9999 36.6663C29.2046 36.6663 36.6666 29.2043 36.6666 19.9997C36.6666 10.7949 29.2046 3.33301 19.9999 3.33301C10.7952 3.33301 3.33325 10.7949 3.33325 19.9997C3.33325 29.2043 10.7952 36.6663 19.9999 36.6663Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
            responsive: {
                0: {
                    items: <?php echo $items_mobile ?>
                },
                600: {
                    items: <?php echo $items_tablet ?>
                },
                1000: {
                    items: <?php echo $items_desktop ?>
                },
                1400: {
                    items: <?php echo $items ?>
                }
            }
        });
    });
</script>
