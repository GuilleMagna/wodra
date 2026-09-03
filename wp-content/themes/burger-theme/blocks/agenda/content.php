<?php 
//Block Name: Agenda

$content_fields = [ 'titulo_informacion', 'subtitulo_informacion', 'encabezado', 'texto_informacion', 'tipo_eventos', 'cantidad_eventos', 'alineacion_eventos', 'radio_de_la_imagen', 'mostrar_boton_ver_mas', 'texto_boton_ver_mas', 'estilo_boton_ver_mas' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];

$tipo_eventos               = ( isset( $tipo_eventos ) && 'pasados' === $tipo_eventos ) ? 'pasados' : 'futuros';
$hoy                        = current_time( 'Ymd' );
$comparador                 = ( 'pasados' === $tipo_eventos ) ? '<' : '>=';
$orden                      = ( 'pasados' === $tipo_eventos ) ? 'DESC' : 'ASC';

$pagina                     = ( !empty( $_GET['pag'] ) ) ? $_GET['pag'] : 1;
$siguiente                  = $pagina+1;
$agenda_anchor              = ! empty( $block['anchor'] ) ? sanitize_title( $block['anchor'] ) : 'agenda';

$boton_ver_mas['url']       = add_query_arg( 'pag', $siguiente ) . "#{$agenda_anchor}";
$boton_ver_mas['title']     = $texto_boton_ver_mas;
$boton_ver_mas['target']    = "_self";

$cantidad_eventos = $cantidad_eventos*$pagina;

$eventos_query = new WP_Query(
    [
        'post_type'             => 'evento',
        'post_status'           => 'publish',
        'posts_per_page'        => $cantidad_eventos,
        'meta_query'            => [
            [
                'key'       => 'fecha_evento',
                'value'     => $hoy,
                'compare'   => $comparador,
                'type'      => 'NUMERIC',
            ],
        ],
    ]
);

$eventos = $eventos_query->posts;
$resultados_eventos = $eventos_query->found_posts;

if ( empty( $eventos ) ) {
    return;
}
 
usort(
    $eventos,
    static function ( $evento_a, $evento_b ) use ( $orden ) {
        $fecha_a = get_post_meta( $evento_a->ID, 'fecha_evento', true );
        $fecha_b = get_post_meta( $evento_b->ID, 'fecha_evento', true );

        $resultado = strcmp( (string) $fecha_a, (string) $fecha_b );
        return ( 'DESC' === $orden ) ? -$resultado : $resultado;
    }
);
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

<section id="agenda" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container, 'data-agenda-block' => $block_id ] ) ?>>

    <div class="container my-5">

        <div class="row">

            <?php if (!empty($titulo_informacion)): ?>

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="color-primario">
                        <?php echo $titulo_informacion ?> <span class="color-secundario"><?php echo $subtitulo_informacion ?></span>
                    </<?= $encabezado ?>>

                </div>

            <?php endif ?>

            <?php if (!empty($texto_informacion)): ?>    

                <div class="color-secundario py-5 <?= $text_align_class ?>">
                    <?php echo $texto_informacion ?>
                </div>

            <?php endif ?>
        
        </div>

    </div>

	<div class="container-fluid" data-agenda-results>

        <?php if ( ! empty( $eventos ) ) : ?>

            <?php foreach ( $eventos as $key => $evento ) :

                $fecha_guardada = get_post_meta( $evento->ID, 'fecha_evento', true );

                $fecha_objeto = DateTime::createFromFormat( '!Ymd', (string) $fecha_guardada, wp_timezone() );
                $fecha        = $fecha_objeto ? date_i18n( 'd F Y', $fecha_objeto->getTimestamp() ) : '';
                $titulo       = get_the_title( $evento );
                $contenido    = has_excerpt( $evento )
                    ? get_the_excerpt( $evento )
                    : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $evento->post_content ) ), 35 );

                $imagen       = get_the_post_thumbnail_url( $evento, 'large' );
                $multimedia_derecha  = ( 'derecha' === $alineacion_eventos );
                $boton_informacion = [
                    'enlace' => [
                        'title'  => 'Conocer más',
                        'url'    => get_permalink( $evento ),
                        'target' => '_self',
                    ],
                    'estilo' => 'btn-texto-blanco',
                ];

            ?>

                <div class="row justify-content-evenly">

                    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-start justify-content-lg-center <?php if( $multimedia_derecha ) echo 'order-1 order-lg-0'; else echo 'order-1 order-lg-1' ?>" style="border-radius: <?php echo $radio_de_la_imagen ?>">

                        <div class="col-12 col-md-10 col-lg-9 my-4 pe-lg-5">

                            <div class="d-flex flex-column mt-lg-5 my-lg-5 py-4 py-lg-5">

                                <div class="col-12 col-lg-10 mx-auto">

                                    <?php if(!empty($titulo)): ?>
    
                                        <div class="mb-3 me-5">
    
                                            <h3 class="color-primario">
                                                <?php echo $titulo ?>
                                            </h3>

                                            <div class="d-flex align-items-center justify-content-start my-3">
                                                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                                    <path d="M0 22.6562C0 23.9502 1.19978 25 2.67857 25H22.3214C23.8002 25 25 23.9502 25 22.6562V9.375H0V22.6562ZM17.8571 13.0859C17.8571 12.7637 18.1585 12.5 18.5268 12.5H20.7589C21.1272 12.5 21.4286 12.7637 21.4286 13.0859V15.0391C21.4286 15.3613 21.1272 15.625 20.7589 15.625H18.5268C18.1585 15.625 17.8571 15.3613 17.8571 15.0391V13.0859ZM17.8571 19.3359C17.8571 19.0137 18.1585 18.75 18.5268 18.75H20.7589C21.1272 18.75 21.4286 19.0137 21.4286 19.3359V21.2891C21.4286 21.6113 21.1272 21.875 20.7589 21.875H18.5268C18.1585 21.875 17.8571 21.6113 17.8571 21.2891V19.3359ZM10.7143 13.0859C10.7143 12.7637 11.0156 12.5 11.3839 12.5H13.6161C13.9844 12.5 14.2857 12.7637 14.2857 13.0859V15.0391C14.2857 15.3613 13.9844 15.625 13.6161 15.625H11.3839C11.0156 15.625 10.7143 15.3613 10.7143 15.0391V13.0859ZM10.7143 19.3359C10.7143 19.0137 11.0156 18.75 11.3839 18.75H13.6161C13.9844 18.75 14.2857 19.0137 14.2857 19.3359V21.2891C14.2857 21.6113 13.9844 21.875 13.6161 21.875H11.3839C11.0156 21.875 10.7143 21.6113 10.7143 21.2891V19.3359ZM3.57143 13.0859C3.57143 12.7637 3.87277 12.5 4.24107 12.5H6.47321C6.84152 12.5 7.14286 12.7637 7.14286 13.0859V15.0391C7.14286 15.3613 6.84152 15.625 6.47321 15.625H4.24107C3.87277 15.625 3.57143 15.3613 3.57143 15.0391V13.0859ZM3.57143 19.3359C3.57143 19.0137 3.87277 18.75 4.24107 18.75H6.47321C6.84152 18.75 7.14286 19.0137 7.14286 19.3359V21.2891C7.14286 21.6113 6.84152 21.875 6.47321 21.875H4.24107C3.87277 21.875 3.57143 21.6113 3.57143 21.2891V19.3359ZM22.3214 3.125H19.6429V0.78125C19.6429 0.351562 19.2411 0 18.75 0H16.9643C16.4732 0 16.0714 0.351562 16.0714 0.78125V3.125H8.92857V0.78125C8.92857 0.351562 8.52679 0 8.03571 0H6.25C5.75893 0 5.35714 0.351562 5.35714 0.78125V3.125H2.67857C1.19978 3.125 0 4.1748 0 5.46875V7.8125H25V5.46875C25 4.1748 23.8002 3.125 22.3214 3.125Z" fill="white"/>
                                                </svg>
                                                <span class="fs-4 color-primario mb-0 text-uppercase">
                                                    <?php echo $fecha ?>
                                                </span>
                                            </div>
    
                                        </div>
    
                                    <?php endif ?>
    
                                    <div class="mb-2 color-secundario me-5">
                                        <?php echo $contenido ?>
                                    </div>
    
                                    <div class="pb-3">
                                        <?php echo get_burger_button( $boton_informacion['enlace'], $boton_informacion['estilo']) ?>
                                    </div>
                                    
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-lg-6 p-0 img img-descripcion <?php if( $multimedia_derecha ) echo 'order-0 order-lg-1'; else echo 'order-0 order-lg-0' ?>" style="background-image: url( '<?php echo $imagen ?>'); background-size: cover; border-radius: <?php echo $radio_de_la_imagen ?>"></div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

	</div>

    <?php if( $mostrar_boton_ver_mas && $resultados_eventos > $cantidad_eventos ): ?>

        <div class="container py-5" data-agenda-pagination>

            <div class="row justify-content-center">

                <div class="col-8 col-lg-3 col-lg-4 col-xxl-auto d-flex align-items-center justify-content-center">

                    <?php echo get_burger_button( $boton_ver_mas, $estilo_boton_ver_mas ) ?>

                </div>   

            </div>

        </div>
    
    <?php endif ?>

</section>