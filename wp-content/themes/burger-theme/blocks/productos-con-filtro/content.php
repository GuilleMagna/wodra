<?php
//Block Name: Productos con filtro

$content_fields = ['titulo_productos', 'subtitulo_productos', 'encabezado', 'productos', 'categorias', 'productos', 'mostrar_proyectos', 'imagen_proyectos', 'titulo_proyectos', 'subtitulo_proyectos', 'boton_proyectos'];
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
        background-repeat: no-repeat;
        background-size: 100%;
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

<section id="productos-con-filtro" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <?php if ($titulo_productos OR $subtitulo_productos): ?>

            <div class="row">

                <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

                    <<?= $encabezado ?> class="titulo fs-5 fw-bold" data-aos="fade-up">
                        <?= $titulo_productos ?> <span><?= $subtitulo_productos ?></span>
                    </<?= $encabezado ?>>

                </div>
            
            </div>

        <?php endif ?>

        <ul class="<?= $text_align_class ?> nav nav-pills pt-2 pb-4" id="pills-tab" role="tablist">

            <?php foreach ($categorias as $key => $item): extract($item); ?>

                <li class="nav-item <?php if ($key != 0) echo ' ps-3' ?>" role="presentation" data-aos="fade-in" data-aos-delay="<?= $key ?>00">

                    <button class="nav-link first-item <?php if ($key == 0) echo 'btn-outline-secondary active'; else echo 'btn-outline-primary' ?>" id="pills-<?= sanitize_title($titulo_categoria) ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?= sanitize_title($titulo_categoria) ?>" type="button" role="tab" aria-controls="pills-<?= sanitize_title($titulo_categoria) ?>" <? if ($key == 0) echo 'aria-selected="true"' ?>>
                        <?php echo $titulo_categoria ?>
                    </button>

                </li>

            <?php endforeach ?>

        </ul>

        <div class="tab-content pt-4" id="pills-tabContent">

            <?php foreach ($categorias as $key => $item):
                extract($item); ?>

                <div class="tab-pane fade<? if ($key == 0) echo ' show active'; ?>" id="pills-<?= sanitize_title($titulo_categoria) ?>" role="tabpanel" aria-labelledby="pills-<?= sanitize_title($titulo_categoria) ?>-tab">

                    <div class="row px-1">

                        <?php if (!empty($productos) AND count($productos) > 0): ?>

                            <div class="owl-carousel owl-proyectos-<?= sanitize_title($titulo_categoria) ?> owl-theme mb-4">

                                <?php foreach ($productos as $key => $item):
                                    extract($item); ?>

                                    <?php
                                    $_categorias = array_column($categorias, 'categoria');
                                    if (!in_array($titulo_categoria, $_categorias))
                                        continue;

                                    $permalink = get_permalink($producto->ID);
                                    $permalink_pago = get_field('link_mercadolibre', $producto->ID) ?? '';
                                    ?>

                                    <div class="item">

                                        <div class="card border-0 rounded-0 mb-5" style="background:transparent" data-aos="fade-in" data-aos-delay="<?php echo $key * 200 ?>">

                                            <div class="img productos-otros mb-4">
                                                <img loading="lazy" decoding="async" src="<?= $imagen ?>" class="img-fluid mx-auto" style="max-height: 400px; width: auto; border-radius: <?= $radio_de_los_bordes ?>">
                                            </div>

                                            <div class="card-body d-flex flex-column">

                                                <div class="title-prod">

                                                    <h5 class="fs-3 text-secondary mb-3">
                                                        <?= $titulo ?>
                                                    </h5>

                                                    <? if( !empty($subtitulo) ): ?>

                                                        <div class="fs-4 text-primary text-normal bd-highlight mb-3">
                                                            <?= $subtitulo ?>
                                                        </div>

                                                    <? endif ?>

                                                </div>

                                                <div class="mb-3" style="min-height: 155px;">

                                                    <ul class="list-group list-group-flush">

                                                        <?php if( !empty($atributos) and count($atributos) > 0 ) foreach( $atributos as $value ): ?>
                                                            <li class="list-group-item px-0">
                                                                <b><?php echo $value['atributo'] ?>:</b>&nbsp;<?php echo $value['valor'] ?>
                                                            </li>
                                                        <?php endforeach ?>

                                                    </ul>

                                                </div>

                                                <? if( !empty($permalink) ){ ?>

                                                    <div>

                                                        <?php
                                                        echo burger_render_button(
                                                            [
                                                                'url'    => ($permalink),
                                                                'title'  => 'Conocer más',
                                                                'target' => '_self',
                                                            ],
                                                            'btn ' . ($estilo_del_boton),
                                                            [
                                                                'span_class'  => 'text-btn-01',
                                                            ]
                                                        );
                                                        ?>

                                                    </div>

                                                <?php } ?>

                                                <? if ( !empty($permalink_pago) ): ?>

                                                    <div>

                                                        <?php
                                                        echo burger_render_button(
                                                            [
                                                                'url'    => ($permalink_pago),
                                                                'title'  => 'Mercado libre',
                                                                'target' => '_self',
                                                            ],
                                                            'btn ' . ($estilo_del_boton_mercadolibre),
                                                            [
                                                                'span_class'  => 'text-btn-01',
                                                            ]
                                                        );
                                                        ?>

                                                    </div>

                                                <? endif; ?>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif ?>

                    </div>

                </div>

                <script>
                    jQuery(document).ready(function ($) {

                        $('.owl-proyectos-<?= sanitize_title($titulo_categoria) ?>').owlCarousel({
                            margin: 10,
                            responsiveClass: true,
                            nav: false,
                            dots: true,
                            loop: false,
                            navText: ['<svg width="9" height="19" viewBox="0 0 9 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.24997 17.42L1.72997 10.9C0.959966 10.13 0.959966 8.87002 1.72997 8.10002L8.24997 1.58002" stroke="#796E65" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>', '<svg width="10" height="19" viewBox="0 0 10 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.65991 17.42L8.17991 10.9C8.94991 10.13 8.94991 8.87002 8.17991 8.10002L1.65991 1.58002" stroke="#796E65" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>'],
                            responsive: {
                                0: { items: 1, margin: 0 },
                                600: { items: 2 },
                                1000: { items: 2, margin: 20 },
                                1400: { items: 3, margin: 20 },
                            }
                        });

                    });
                </script>

            <?php endforeach ?>

        </div>

    </div>

    <?php if ( !empty($mostrar_proyectos) ): ?>

        <div class="container pt-5">

            <div class="d-md-flex justify-content-center">

                <?php if (!empty($boton_proyectos) and count($boton_proyectos) > 0): ?>

                    <div class="text-center text-md-start mt-3 mt-md-0">

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

                    </div>

                <?php endif ?>

            </div>

        </div>

    <?php endif ?>

</section>