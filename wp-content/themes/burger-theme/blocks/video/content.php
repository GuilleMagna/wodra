<?php 
//Block Name: Video

$content_fields = [ 'titulo_video', 'subtitulo_video', 'encabezado', 'id_video_youtube', 'imagen_video_youtube' ];
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

<section id="video" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>

    <div class="container">

        <<?= $encabezado ?> class="titulo fs-2 fw-bold mb-5 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
            <span><?= $titulo_video ?></span> <?= $subtitulo_video ?>
        </<?= $encabezado ?>>

        <? if( $id_video_youtube ): ?>

            <div class="embed-responsive embed-responsive-16by9 rounded-4">
                <iframe class="embed-responsive-item w-100 rounded-4" src="https://www.youtube.com/embed/<?= $id_video_youtube ?>" style="aspect-ratio: 2 / 1;"></iframe>
            </div>
            
        <? endif ?>

    </div>

</section>