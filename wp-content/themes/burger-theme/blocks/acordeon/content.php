<?php
//Block Name: Acordeon

$content_fields = ['titulo_acordeon', 'subtitulo_acordeon', 'encabezado', 'texto_acordeon', 'acordeon'];
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

<section id="acordeon" <?= get_block_wrapper_attributes(['class' => $block_id .' '. $class_container]) ?>>

    <div class="container">

        <h2 class="titulo mb-4 fw-bold fs-2 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>" data-aos="fade-up">
            <?= $titulo_acordeon ?> <span><?= $subtitulo_acordeon ?></span>
        </h2>

        <div class="col-12 col-md-8 col-lg-7 col-xl-6 mb-5">
            <?= $texto_acordeon ?>
        </div>

        <div class="accordion" id="accordionExample-trabajo">

            <?php foreach ($acordeon as $key => $item): ?>

                <div class="accordion-item border-0 mt-3" data-aos="fade-up">

                    <h5 class="accordion-header" id="heading<?= $key ?>-trabajo">

                        <button class="accordion-button border-0 border-bottom border-2 px-3 py-4 <? if ($key != 0)
                            echo 'collapsed' ?>" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse<?= $key ?>-trabajo" <? if ($key == 0)
                                      echo 'aria-expanded="true"';
                                  else
                                      echo 'aria-expanded="false"'; ?> aria-controls="collapse<?php echo $key ?>-trabajo">
                            <?= $item['titulo_acordeon'] ?>
                        </button>

                    </h5>

                    <div id="collapse<?= $key ?>-trabajo"
                        class="accordion-collapse collapse<?php if ($key == 0)
                            echo ' show'; ?>"
                        aria-labelledby="heading<?= $key ?>-trabajo" data-bs-parent="#accordionExample-trabajo">

                        <div class="accordion-body text-secondary px-4 py-4">

                            <?= $item['texto_acordeon'] ?>

                            <?php if ($item['mostrar_subacordeon']): ?>

                                <div class="accordion mt-4" id="accordion<?= $key ?>-trabajo-<?= $sub_key ?>">

                                    <?php foreach ($item['subacordeon'] as $sub_key => $sub_item): ?>

                                        <div class="accordion-item border-0">

                                            <h2 class="accordion-header border-bottom border-2 border-white"
                                                id="heading<?php echo $key ?>-trabajo-<?= $sub_key ?>">
                                                <button
                                                    class="accordion-button border-0 bg-light text-dark text-normal medium<?php if ($sub_key != 0)
                                                        echo ' collapsed'; ?>"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse<?php echo $key ?>-trabajo-<?php echo $sub_key ?>"
                                                    aria-expanded="true"
                                                    aria-controls="collapse<?php echo $key ?>-trabajo-<?php echo $sub_key ?>">
                                                    <?= $sub_item['titulo_subacordeon'] ?>
                                                </button>
                                            </h2>

                                            <div id="collapse<?= $key ?>-trabajo-<?= $sub_key ?>" 
                                                class="accordion-collapse collapse<?php if ($sub_key == 0) echo ' show'; ?>"
                                                aria-labelledby="heading<?= $key ?>-trabajo-<?= $sub_key ?>"
                                                data-bs-parent="#accordion<?php echo $key ?>-trabajo-<?php echo $sub_key ?>">

                                                <div class="accordion-body text-secondary px-4 py-4">

                                                    <?= $sub_item['texto_subacordeon'] ?>

                                                    <?php if ($sub_item['mostrar_boton_subacordeon']): ?>

                                                        <?php
                                                        echo burger_render_button(
                                                            [
                                                                'url'    => ($sub_item['boton_subacordeon']['url']),
                                                                'title'  => ($sub_item['boton_subacordeon']['title']),
                                                                'target' => ($sub_item['boton_subacordeon']['target']),
                                                            ],
                                                            'btn btn-lg btn-primary text-normal regular text-white mt-4',
                                                            [
                                                                'span_class'  => 'text-btn-01',
                                                            ]
                                                        );
                                                        ?>

                                                    <?php endif ?>

                                                </div>

                                            </div>

                                        </div>

                                    <?php endforeach ?>

                                </div>

                            <?php endif ?>

                            <?php if ($item['mostrar_boton_acordeon']): ?>

                                <?php
echo burger_render_button(
    [
        'url'    => ($item['boton_acordeon']['url']),
        'title'  => ($item['boton_acordeon']['title']),
        'target' => '_self',
    ],
    'btn btn-white',
    [
        'span_class'  => 'text-btn-01',
    ]
);
?>

                            <?php endif ?>

                        </div>

                    </div>

                </div>

            <?php endforeach ?>

        </div>

    </div>

</section>