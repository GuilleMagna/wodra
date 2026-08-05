<?php
//Block Name: Relacionados

$content_fields = ['titulo_otros', 'subtitulo_otros', 'encabezado', 'texto_otros', 'otros', 'owl', 'items', 'items_mobile', 'items_tablet', 'items_desktop', 'margin', 'autoplay', 'nav', 'dots', 'loop', 'overflow'];
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
        background-image: url('<?= $imagen_fondo ?>');
        background-size: 100%;
        background-repeat: no-repeat;
    }
    .<?= $block_id ?> .titulo,
    .<?= $block_id ?> .color-secundario {
        color: <?php echo $color_secundario ?>
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
    .<?= $block_id ?> #owl-slider #owl-slider-<?= $block_id ?> .owl-stage-outer {
        overflow: <?php echo $overflow ?>;
    }
</style>

<section id="relacionados" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <div class="text-start mb-3">

            <<?= $encabezado ?> class="titulo fs-1 fw-bold text-white light mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <?php echo $titulo_otros ?> <span class="text-big-titulos medium"><?php echo $subtitulo_otros ?></span>
            </<?= $encabezado ?>>

        </div>

        <div class="text-start text-white mb-5 col-12 col-md-6 col-lg-4">
            <p>
                <?= $texto_otros ?>
            </p>
        </div>

    </div>

    <div class="container ps-5 pt-5">

        <?php if (!empty($otros) and count($otros) > 0): ?>

            <div id="owl-slider-<?= $block_id ?>" class="<?php if ($owl)
                  echo 'owl-carousel owl-relacionados owl-theme';
              else
                  echo 'row g-0' ?>">

                <?php foreach ($otros as $item):
                  extract($item) ?>

                    <?php
                    if (empty($post))
                        continue;

                    $post->post_thumbnail = $imagen;
                    if (empty($imagen) && has_post_thumbnail($post->ID)) {
                        $post->post_thumbnail = get_the_post_thumbnail_url($post->ID, 'large');
                    }

                    $post->post_permalink = get_permalink($post->ID);
                    ?>

                    <div class="<?php if ($owl)
                        echo 'item';
                    else
                        echo 'col-12 col-md-6 col-lg-4' ?>">


                            <div class="card border-0 rounded-0 mb-5 text-start" style="background:transparent">

                                <div class="img productos-otros mb-2 w-75 mx-auto">
                                    <img src="<?php echo $post->post_thumbnail ?>" class="img-fluid w-100" alt="imagen slider">
                            </div>

                            <div class="border-0 d-flex flex-column w-75 mx-auto">

                                <h3 class="fw-bold mb-2 regular color-secundario">
                                    <?php echo $titulo ?>
                                </h3>

                                <h5 class="mb-3 text-normal color-primario">
                                    <?php echo $texto ?>
                                </h5>

                                <div>

                                    <?php
                                    echo burger_render_button(
                                        [
                                            'url'    => ($post->post_permalink),
                                            'title'  => ($texto_del_boton ?? $post->post_title),
                                            'target' => '_self',
                                        ],
                                        'btn ' . ($estilo_del_boton) . ' text-uppercase',
                                        [
                                            'span_class'  => 'text-btn-01',
                                        ]
                                    );
                                    ?>

                                </div>


                                <? if (!empty($link_mercadolibre)): ?>

                                    <div>

                                        <?php
echo burger_render_button(
    [
        'url'    => ($link_mercadolibre),
        'title'  => 'MERCADO LIBRE',
        'target' => '_blank',
    ],
    'btn ' . ($estilo_del_boton_meli) . ' text-uppercase',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>

                                    </div>

                                <? endif ?>

                            </div>

                        </div>


                    </div>

                <?php endforeach ?>

            </div>

            <?php if ($owl): ?>

                <script>
                    jQuery(document).ready(function ($) {
                        $('#owl-slider-<?= $block_id ?>').owlCarousel({
                            center: <?php (!empty($loop)) ? print 'true' : print 'false'; ?>,
                            loop: <?php (!empty($loop)) ? print 'true' : print 'false'; ?>,
                            items: <?php echo $items ?>,
                            margin: <?php echo $margin ?>,
                            autoplay: <?php (!empty($autoplay)) ? print 'true' : print 'false'; ?>,
                            nav: <?php (!empty($nav)) ? print 'true' : print 'false'; ?>,
                            dots: <?php (!empty($dots)) ? print 'true' : print 'false'; ?>,
                            navText: ['<span class="carousel-control-prev-icon" aria-hidden="true"></span>', '<span class="carousel-control-next-icon" aria-hidden="true"></span>'],
                            responsive: {
                                0: {
                                    items: <?php echo $items_mobile ?>
                                },
                                600: {
                                    items: <?php echo $items_tablet ?>
                                },
                                1000: {
                                    items: <?php echo $items_desktop ?>
                                }
                            }
                        });
                    });
                </script>

            <?php endif ?>

        <?php endif ?>

    </div>

</section>