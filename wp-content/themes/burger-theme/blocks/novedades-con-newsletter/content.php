<?php
//Block Name: Novedades con Newsletter

$content_fields = ['titulo_novedades_newsletter', 'subtitulo_novedades_newsletter', 'encabezado', 'novedades', 'ver_boton_mas_novedades', 'boton_mas_novedades', 'estilo_boton_mas_novedades', 'imagen_novedades_newsletter', 'titulo_formulario_novedades', 'redes_novedades', 'shortcode_formulario_novedades'];
$fields = get_block_content_fields($block, $content_fields);
extract($fields);

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

<section id="novedades-con-newsletter" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="row align-items-start g-5">

            <div class="col-12 col-md-7 col-lg-7 ps-md-0">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?> pb-5">

                    <<?= $encabezado ?> class="titulo text-big-titulos fw-bold light mb-5 w-50 me-5 pe-3">
                        <span><?php echo $titulo_novedades_newsletter ?></span>
                        <?php echo $subtitulo_novedades_newsletter ?>
                    </<?= $encabezado ?>>

                    <div class="row g-4">

                        <?php foreach ($novedades as $item): extract($item) ?>

                            <div class="col-12">

                                <div class="card card-novedades border-0" data-aos="fade-in">

                                    <div class="row g-4">

                                        <div class="col-12 col-lg-4">

                                            <div class="img img-novedades">
                                                <? if( !empty($boton) && count($boton)> 0 ): ?><a href="<?php echo $boton['url'] ?>" title="<?= $titulo ?>"><? endif ?>
                                                    <img loading="lazy" decoding="async" src="<?= $imagen ?>" class="img-fluid w-100" style="aspect-ratio: 3/2; border-radius: <?= $border_radio ?>" alt="<?= esc_html($titulo) ?>">
                                                <? if( !empty($boton) && count($boton)> 0 ): ?></a><? endif ?>
                                            </div>

                                        </div>

                                        <div class="col-12 col-lg-7 d-flex align-items-start">

                                            <div>

                                                <h4 class="color-secundario mb-2 mb-lg-3">
                                                    <?= $titulo ?>
                                                </h4>

                                                <p class="color-secundario">
                                                    <?= $texto ?>
                                                </p>

                                                <?php
                                                if( !empty($boton) && count($boton) > 0 ):
                                                    echo get_burger_button( $boton, $estilo );
                                                endif ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach ?>

                    </div>

                    <?php if ($ver_boton_mas_novedades && !empty($boton_mas_novedades) && count($boton_mas_novedades) > 0): ?>

                        <div class="w-100 d-flex justify-content-center justify-content-md-start">
                            <?php echo get_burger_button( $boton_mas_novedades, $estilo_boton_mas_novedades ); ?>
                        </div>

                    <?php endif ?>

                </div>

            </div>

            <div class="col-12 col-md-5 col-lg-5 mb-5 mb-md-0" style="background-color: <?php echo $color_secundario ?>; background-image:url( <?= $imagen_novedades_newsletter ?>); border-radius: 30px;">

                <div class="section">

                    <div class="col-12 col-md-10 col-lg-9 col-xl-8 mx-auto" data-aos="fade-left">

                        <h3 class="color-primario fw-bold mb-3">
                            <?php echo $titulo_formulario_novedades ?>
                        </h3>

                        <div class="d-flex justify-conten-start align-items-center mb-5">

                            <?php if (!empty($redes_novedades['linkedin'])): ?>

                                <a href="<?php echo $redes_novedades['linkedin']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-linkedin' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if (!empty($redes_novedades['facebook'])): ?>

                                <a href="<?= $redes_novedades['facebook']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-facebook' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if (!empty($redes_novedades['instagram'])): ?>

                                <a href="<?= $redes_novedades['instagram']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-instagram' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if (!empty($redes_novedades['youtube'])): ?>

                                <a href="<?= $redes_novedades['youtube']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-youtube' ) ?>
                                </a>

                            <?php endif ?>

                            <?php if (!empty($redes_novedades['x'])): ?>

                                <a href="<?= $redes_novedades['x']; ?>" class="text-decoration-none me-3" target="_blank">
                                    <?php echo get_burger_icon( 'icon-twitter' ) ?>
                                </a>

                            <?php endif ?>

                        </div>

                        <div class="pb-5 mb-5">
                            <?php echo do_shortcode($shortcode_formulario_novedades) ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>