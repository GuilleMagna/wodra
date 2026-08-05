<?php 
//Block Name: Equipo

$content_fields = [ 'titulo_equipo',  'subtitulo_equipo',  'encabezado',  'texto_equipo',  'equipo' ];
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

<section id="equipo" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container mb-5">

        <div class="mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">

            <<?= $encabezado ?> class="titulo text-black mb-4 me-5 pe-5">
                <?php echo $titulo_equipo ?> <span><?php echo $subtitulo_equipo ?>
            </<?= $encabezado ?>>

            <p class="text black mb-4">
                <?php echo $texto_equipo ?>
            </p>

        </div>

    </div>

    <?php if( !empty( $equipo ) && count( $equipo ) > 0 ): ?>

        <div class="container">

            <div class="row">

                <div class="col-12 mx-auto text-center">

                    <div class="owl-carousel owl-team owl-theme">

                        <?php foreach( $equipo as $item ): ?>

                            <?php $imagen = $item['imagen'] ?? '' ?>

                            <div class="item">

                                <div class="card rounded-5 h-100">

                                    <div class="card-header p-0 border-0 bg-white">
                                        <div style="background-image: url('<?= $imagen ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; aspect-ratio: 3/4;"></div>
                                    </div>

                                    <div class="card-body border-0">
                                        <h5><?= $item['nombre'] ?></h5>
                                        <p class="mb-0"><?= $item['cargo'] ?></p>
                                    </div>

                                </div>

                            </div>

                        <?php endforeach ?>

                    </div>

                </div>

            </div>

        </div>

    <?php endif ?>
    
</section>