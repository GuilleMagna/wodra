<?php 
//Block Name: Sucursales

$content_fields = [ 'titulo_sucursales',  'subtitulo_sucursales', 'encabezado', 'texto_sucursales', 'logo_sucursales', 'mostrar_galeria_sucursales',  'galeria_sucursales',  'sucursales' ];
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
        background-size: cover;
        background-repeat: no-repeat;
        background-image: url('<?= $imagen_fondo ?>');
        
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

<section id="sucursales" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>

    <div class="container pt-5">

		<div class="row justify-content-between">

			<div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

				<<?= $encabezado ?> class="titulo h1 fw-bold mb-5 pb-3">
                    <?php echo $titulo_sucursales ?> <span><?php echo $subtitulo_sucursales ?></span>
                </<?= $encabezado ?>>

                <div class="color-secundario mb-5" data-aos="fade-right">
                    <?= $texto_sucursales ?>
                </div>

                <div class="row mb-4">

                    <?php if( !empty( $sucursales ) && count( $sucursales ) > 0 ) foreach( $sucursales as $key => $value ): extract( $value ) ?>

                        <div class="col-12 col-md-8 mb-3">

                            <div class="d-flex justify-content-start color-primario">

                                <div data-aos="fade-in" data-aos-delay="<?= $key ?>00">

                                    <h6 class="fs-4 fw-bold mb-4">
                                        <?= $nombre ?>
                                    </h6>

                                    <p class="fs-5 mb-2">
                                        <?php if( !empty( $icono )): ?><img loading="lazy" decoding="async" src="<?= $icono ?>" class="mb-2" title="<?= $nombre ?>"><?php endif ?> <?= $ubicacion ?>
                                    </p>

                                    <p class="mb-3" style="font-size:0.8rem">
                                        <a href="<?= $link ?>" target="_blank">
                                            ¿CÓMO LLEGO? <img loading="lazy" decoding="async" src="<?= BURGER_THEME_URL ?>/themes/images/page-right.png" class="mb-2">
                                        </a>
                                    </p>

                                    <p class="mb-5">
                                        <a href="<?= $link_telefono ?>" target="_blank">
                                            <img loading="lazy" decoding="async" src="<?= BURGER_THEME_URL ?>/themes/images/Vector-2.png"><?= $telefono ?>
                                        </a>
                                    </p>

                                </div>

                            </div>

                        </div>

                    <?php endforeach ?>

                </div>

			</div>

			<div class="col-12 col-lg-3 d-flex align-items-center">
				<img loading="lazy" decoding="async" src="<?php echo $logo_sucursales ?>" alt="logo" class="img-fluid w-100">
			</div>

		</div>

	</div>
    
</section>