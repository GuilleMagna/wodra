<?php 
//Block Name: Novedades con filtro

if( !empty( $_GET['pag'] ) ) $actual_page = $_GET['pag'];
else $actual_page = 0;

$content_fields = [ 'titulo_novedades',  'subtitulo_novedades', 'encabezado', 'titulo_categorias',  'categorias',  'cantidad_categoria', 'estilo_boton', 'mostrar_paginador', 'cantidad_por_categoria_total', 'titulo_sin_resultados', 'texto_sin_resultados', 'imagen_sin_resultados', 'color_flechas_paginador' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$design = get_block_design( $block );
extract( $design );

if( is_numeric($cantidad_por_categoria_total) AND is_numeric($cantidad_categoria) )
    $total_pages = ( $cantidad_por_categoria_total / $cantidad_categoria - 1 );
else
    $total_pages = 1;

$mostrar_ante = false;
if( $actual_page > 0 OR ( !empty( $_GET['pag'] ) and $_GET['pag'] > 0 ) ) $mostrar_ante = true;

$mostrar_sgte = false;
if( ($actual_page+1) < $total_pages ) $mostrar_sgte = true;

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
        color: <?= $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?= $color_primario ?>
    }

    #novedades-con-filtro .nav-pills .nav-link {
        background: <?= $color_fondo ?>;
        color: <?= $color_primario ?>;
        border-left: 0px solid <?= $color_primario ?> !important;
        text-decoration: underline;
    }

    #novedades-con-filtro .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: <?= $color_fondo ?>;
        color: <?= $color_secundario ?>;
        border-left: 1px solid <?= $color_primario ?> !important;
        font-weight: 800;
    }

    #novedades-con-filtro .nav-pills .show>.nav-link.first-item {
        background-color: <?= $color_fondo ?>;
        color: <?= $color_secundario ?>;
        border-left: 0px solid <?= $color_primario ?> !important;
    }

    #novedades-con-filtro .nav-pills .nav-link.active.first-item {
        background-color: <?= $color_fondo ?>;
        color: <?= $color_secundario ?>;
        border-left: 0px solid <?= $color_secundario ?> !important;
        text-decoration: none !important;
    }

    

</style>

<section id="novedades-con-filtro" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

        <?php if( $titulo_novedades ): ?>
            
            <div class="text-start mb-4 d-flex">
                
                <h2 class="mb-0 <?= $col_container_class ?> <?= $col_lg_container_class ?> <?= $col_md_container_class ?> <?= $text_align_class ?>" data-aos="fade-up">
                    <span class="text-primary"><?= $titulo_novedades ?></span>&nbsp;<?= $subtitulo_novedades ?>
                </h2>
                
            </div>
            
        <?php endif ?>

        <h3 class="mb-4" data-aos="fade-up">
            <?= $titulo_categorias ?>
        </h3>

        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">

            <?php foreach( $categorias as $key => $term ): ?>

                <li class="nav-item <?php if( $key != 0 ): ?>border-start<?php endif; ?>" role="presentation" data-aos="fade-in" data-aos-delay="<?= $key ?>00">
                    <button class="nav-link text-btn rounded-0 py-0 first-item<?php if( $key == 0 ) echo ' active ps-0' ?>" id="pills-<?= $term->slug ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?= $term->slug ?>" type="button" role="tab" aria-controls="pills-<?= $term->slug ?>" <?php if( $key == 0 ) echo 'aria-selected="true"' ?>>
                        <?= $term->name ?>
                    </button>
                </li>
            
            <?php endforeach ?>

        </ul>

		<div class="tab-content" id="pills-tabContent">
                
            <?php foreach( $categorias as $key => $term ): ?>

                <div class="tab-pane fade<?php if( $key == 0 ) echo ' show active' ?>" id="pills-<?= $term->slug ?>" role="tabpanel" aria-labelledby="pills-<?= $term->slug ?>-tab">

                    <div class="row px-1">

                        <?php 

                        $offset = 0;
                        $actual_page = 0;
                        if( !empty( $_GET['tab'] ) && $term->slug == $_GET['tab'] ){
                            $offset = $cantidad_categoria*$actual_page;
                            $actual_page = $_GET['pag'];
                        }  

                        $args = array(  
                                        'posts_per_page'    => $cantidad_categoria, 
                                        'offset'            => $offset, 
                                        'cat'               => $term->term_id 
                        );

                        $novedades = get_posts( $args );
                        
                        if( count( $novedades ) > 0 ): ?>
                        
                            <?php foreach( $novedades as $post ): ?>
                            
                                <?php 
                                $post->post_thumbnail = '';
                                $post->post_category = '';
                                if( has_post_thumbnail( $post->ID ) ) {
                                    $post->post_thumbnail = get_the_post_thumbnail_url( $post->ID, 'large' );
                                    $post->post_category = get_the_category( $post->ID );
                                }

                                $boton['title']     = 'Leer Más';
                                $boton['url']       = get_permalink( $post->ID );
                                $boton['target']    = '_self';
                                $estilo             = $estilo_boton;
                                
                                ?>
                                
                                <div class="col-lg-4">

                                    <div class="card card-novedades bg-transparent border-0 rounded-0 mb-5" data-aos="fade-up">
                                        
                                        <div class="card-header border-0 rounded-4 img novedades-img position-relative" style="background-image: url( '<?= $post->post_thumbnail ?>');">
                                            <a href="<?= $boton['url'] ?>" class="position-absolute w-100 h-100"></a>
                                        </div>

                                        <div class="card-body px-0 border-0 d-flex flex-column">

                                            <h2 class="color-secundario mb-3">
                                                <?= cortar_texto( $post->post_title ) ?>
                                            </h2>

                                            <p class="color-secundario mb-3">
                                                <?= $post->post_category[0]->name; ?>
                                            </p>

                                            <div class="color-primario mb-3">
                                                <?= $post->post_excerpt ?>
                                            </div>

                                            <?php echo get_burger_button ( $boton, $estilo ) ?>

                                        </div>

                                    </div>

                                </div>

                            <? endforeach ?>

                        <?php else: ?>

                            <div class="col-12 col-lg-8 mx-auto">

                                <div class="row">

                                    <div class="col-12 col-md-8 col-xl-9 d-flex align-items-center">

                                        <div>

                                            <h2 class="text-primary mb-2">
                                                <?= $titulo_sin_resultados ?>
                                            </h2>
    
                                            <h4>
                                                <?= $texto_sin_resultados ?>
                                            </h4>

                                        </div>

                                    </div>

                                    <?php if( $imagen_sin_resultados ): ?>
                                        <div class="col-12 col-md-4 col-xl-3">
                                            <img src="<?= $imagen_sin_resultados ?>" class="img-fluid" alt="<?= $titulo_sin_resultados ?>" />
                                        </div>
                                    <? endif ?>

                                </div>

                            </div>

                        <?php endif ?>                        

                    </div>
                    
                    <?php if( $mostrar_paginador  ): ?>

                        <section id="paginador" class="pt-5">

                            <div class="row">

                                <div class="col-12 text-center">

                                    <div class="d-flex align-items-center justify-content-between">
                                        
                                        <? if ( $mostrar_ante ){ ?>

                                            <?php echo get_burger_button( 
                                                [
                                                    'url'    => '?tab=' . ($term->slug) . '&pag=' . ($actual_page-1) . '#novedades-con-filtro',
                                                    'title'  => BURGER_OPTIONS['anterior_paginador'],
                                                    'target' => '_self',
                                                ],
                                                BURGER_OPTIONS['estilo_boton']
                                            ) ?>

                                        <? } else { ?>    

                                            <div class="d-flex align-items-start justify-content-start">
                                                <svg width="20" height="20" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-3 pt-1" style="transform: rotate(-180deg);">
                                                    <g clip-path="url(#clip0_4365_1021)">
                                                        <path d="M6.875 5.3125L9.0625 7.5L6.875 9.6875" stroke="<?= $color_flechas_paginador ?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.5 13.75C10.9517 13.75 13.75 10.9517 13.75 7.5C13.75 4.04822 10.9517 1.25 7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 10.9517 4.04822 13.75 7.5 13.75Z" stroke="<?= $color_flechas_paginador?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_4365_1021">
                                                            <rect width="15" height="15" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <span class="color-secundario text-btn d-none d-md-block"><?php echo BURGER_OPTIONS['anterior_paginador'] ?></span>
                                            </div>
                                        
                                        <? } ?>    

                                        <? if( BURGER_OPTIONS['logo_paginador'] ): ?>

                                            <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
                                                <img src="<?= BURGER_OPTIONS['logo_paginador'] ?>" alt="logo Grupo Sit" class="img-fluid">
                                            </div>

                                        <? endif ?>
                                        
                                        <? if ( $mostrar_sgte ){ ?>  
                                                
                                             <?php echo get_burger_button( 
                                                [
                                                    'url'    => '?tab=' . ($term->slug) . '&pag=' . ($actual_page+1) . '#novedades-con-filtro',
                                                    'title'  => BURGER_OPTIONS['posterior_paginador'],
                                                    'target' => '_self',
                                                ],
                                                BURGER_OPTIONS['estilo_boton']
                                            ) ?>
                                            
                                            <? } else { ?>    
                                                
                                                <div class="d-flex align-items-start justify-content-start">
                                                    <span class="color-secundario text-btn d-none d-md-block"><?php echo BURGER_OPTIONS['posterior_paginador'] ?></span>
                                                    <svg width="20" height="20" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-3 pb-1">
                                                        <g clip-path="url(#clip0_4365_1021)">
                                                            <path d="M6.875 5.3125L9.0625 7.5L6.875 9.6875" stroke="<?= $color_flechas_paginador?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M7.5 13.75C10.9517 13.75 13.75 10.9517 13.75 7.5C13.75 4.04822 10.9517 1.25 7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 10.9517 4.04822 13.75 7.5 13.75Z" stroke="<?= $color_flechas_paginador?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_4365_1021">
                                                                <rect width="15" height="15" fill="white"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                             </div>
                                        
                                        <? } ?>     

                                    </div>                                          

                                </div>

                            </div>

                        </section>

                    <? endif ?>

                </div>

            <? endforeach ?>
            
		</div>

	</div>

</section>

<script>
    <? if( isset( $_GET['pag'] ) && isset( $_GET['tab'] ) ): ?>
        $(document).ready(function() {
            $('#pills-<?= $_GET['tab'] ?>-tab').trigger('click');
        });
    <? endif ?>
</script>
