<?php 
//Block Name: Productos dos
if( get_post_type() == 'page' ) return null;

$content_fields = [ 'titulo_producto', 'subtitulo_producto', 'encabezado', 'area', 'contenido', 'boton', 'titulo_atributos', 'atributos_producto', 'servicios' ];
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

<section id="productos-dos" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

	<div class="container">

		<div class="row">
            
			<div class="col-12 col-md-7 mx-auto text-start" data-sal="fade" data-sal-duration="500">

				<div class="text-start border-start border-primary border-4 ps-3">

					<<?= $encabezado ?> class="titulo text-big-titulos text-primary regular mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" style="text-transform: none;">
                        <?php echo $titulo_producto ?><br>
                        <span><?php echo $subtitulo_producto ?></span>
                    </<?= $encabezado ?>>

				</div>

                <?php if( !empty( $area ) ): ?>

                    <p class="mb-0">
                        <span class="badge bg-white border border-dark text-dark px-3 py-2 my-4"><?php echo $area ?></span>
                    </p>

                <?php endif ?>

				<div class="text-dark text-normal regular text-center text-md-start">
                    <?php echo $contenido ?>
                </div>
				
                <?php if( !empty( $boton ) and count( $boton ) > 0 ): ?>
                        
                    <div class="text-center text-md-start mt-3 mt-md-0 mb-5 mb-md-0">

                        <div>
                            <?php
                            echo burger_render_button(
                                [
                                    'url'    => ($boton['url']),
                                    'title'  => ($boton['title']),
                                    'target' => ($boton['target']),
                                ],
                                'btn btn-lg btn-outline-primary text-uppercase text-normal regular',
                                [
                                    'span_class'  => 'text-btn-01',
                                ]
                            );
                            ?>
                        </div>

                    </div>

                <?php endif ?>

			</div>

			<div class="col-12 col-md-5" data-sal="fade" data-sal-duration="500">

				<p class="text-big text-dark medium mb-4" style="text-transform: none;">
                    <?php echo $titulo_atributos ?>
                </p>

				<ul class="list-group list-group-flush">

                    <?php if( !empty($atributos_producto) and count($atributos_producto) > 0 ) foreach( $atributos_producto as $value ): ?>

                        <?php $icono = $value['icono'] ?? BURGER_THEME_URL . '/themes/images/iconos/evaluacion.png' ?>

                        <li class="list-group-item d-flex justify-content-start">

                            <div style="width: 35px;" class="me-2">
                                <img loading="lazy" decoding="async" src="<?php echo $icono ?>" title="<?php echo $value['titulo'] ?>" alt="<?php echo $value['titulo'] ?>">
                            </div>

                            <div style="width: 100%;">
                                <b><?php echo $value['nombre'] ?></b>&nbsp;<?php echo $value['valor'] ?>
                            </div>

                        </li>

                    <?php endforeach ?>     

				</ul>

			</div>

		</div>

	</div>

    <?php if( !empty( $servicios ) and count( $servicios ) > 0 ): ?>

        <div class="container mt-5">

            <div class="owl-carousel owl-interna owl-theme">

                <?php foreach( $servicios as $value ): ?>

                    <?php $icono = $value['icono'] ?? BURGER_THEME_URL . '/themes/images/iconos/evaluacion.png' ?>

                    <div class="item">

                        <div class="card bg-transparent border-0 rounded-0">

                            <div class="row mx-auto d-flex justify-content-center">

                                <div class="col-auto">
                                    <img loading="lazy" decoding="async" src="<?php echo $icono ?>" style="width: 40px;" title="<?php echo $value['titulo'] ?>" alt="<?php echo $value['titulo'] ?>">
                                </div>

                                <div class="col d-flex align-items-center text-normal">
                                    <?php echo $value['titulo'] ?>
                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach ?>    

                <!--div class="item">
                    <div class="card bg-transparent border-0 rounded-0">
                        <div class="row mx-auto d-flex justify-content-center">
                            <div class="col-auto">
                                <img loading="lazy" decoding="async" src="<?php echo BURGER_THEME_URL ?>/themes/images/iconos/equipo.png" style="width: 40px;">
                            </div>
                            <div class="col d-flex align-items-center text-normal">
                                600 Colaboradores Participantes
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="card bg-transparent border-0 rounded-0">
                        <div class="row mx-auto d-flex justify-content-center">
                            <div class="col-auto">
                                <img loading="lazy" decoding="async" src="<?php echo BURGER_THEME_URL ?>/themes/images/iconos/camion-volquete.png" style="width: 40px;">
                            </div>
                            <div class="col d-flex align-items-center text-normal">
                                6.000 toneladas de piedra transportadas
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="card bg-transparent border-0 rounded-0">
                        <div class="row mx-auto d-flex justify-content-center">
                            <div class="col-auto">
                                <img loading="lazy" decoding="async" src="<?php echo BURGER_THEME_URL ?>/themes/images/iconos/casco.png" style="width: 40px;">
                            </div>
                            <div class="col d-flex align-items-center text-normal">
                                3.000 m2 construidos
                            </div>
                        </div>
                    </div>
                </div-->

            </div>

        </div>
    
    <?php endif ?>

</section>