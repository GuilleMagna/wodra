<?php 
//Block Name: Under Construction

$content_fields = [ 'logo_under', 'titulo_under', 'subtitulo_under', 'encabezado', 'texto_under', 'redes_under' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];
?>

<style>
    #<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?>; 
        background-image: url('<?= $imagen_fondo ?>');
        background-size: cover;
        background-position:center:
    }
    #<?= $block_id ?> .titulo,
    #<?= $block_id ?> .color-secundario {
        color:<?php echo $color_secundario ?>
    }
    #<?= $block_id ?> .titulo span,
    #<?= $block_id ?> .color-primario {
        color:<?php echo $color_primario ?>
    }
</style>

<section id="<?= $block_id ?>" class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

	<div class="container">

        <div class="col-12 col-md-9 col-lg-7 col-xxl-6 mx-auto">

            <div class="text-center">

                <img loading="lazy" decoding="async" src="<?php echo $logo_under ?>" alt="<?= $titulo_under ?> <?= $subtitulo_under ?>" class="mx-auto mb-5" style="max-height: 130px;">

                <h1 class="color-primario mb-4 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                    <?= $titulo_under ?> <span><?= $subtitulo_under ?></span>
                </h1>

                <h2 class="color-secundario mb-5">
                    <?= $texto_under ?>
                </h2>

                <div class="color-secundario d-flex align-items-center justify-content-center">

                    <?php if( !empty( BURGER_OPTIONS['whatsapp'] ) ): ?>

                        <a href="https://wa.me/<?php echo BURGER_OPTIONS['whatsapp'] ?>" target="_blank" class="color-secundario mx-2">
                            <?php echo get_burger_icon( 'icon-whatsapp' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty( BURGER_OPTIONS['redes']['instagram'] ) ): ?>

                        <a href="<?php echo BURGER_OPTIONS['redes']['instagram'] ?>" target="_blank" class="color-secundario mx-2">
                            <?php echo get_burger_icon( 'icon-instagram' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty( BURGER_OPTIONS['redes']['facebook'] ) ): ?>

                        <a href="<?php echo BURGER_OPTIONS['redes']['facebook'] ?>" target="_blank" class="color-secundario mx-2">
                            <?php echo get_burger_icon( 'icon-facebook' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty( BURGER_OPTIONS['redes']['youtube'] ) ): ?>

                        <a href="<?php echo BURGER_OPTIONS['redes']['youtube'] ?>" target="_blank" class="color-secundario mx-2">
                            <?php echo get_burger_icon( 'icon-youtube' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty( BURGER_OPTIONS['redes']['linkedin'] ) ): ?>

                        <a href="<?php echo BURGER_OPTIONS['redes']['linkedin'] ?>" target="_blank" class="color-secundario mx-2">
                            <?php echo get_burger_icon( 'icon-linkedin' ) ?>
                        </a>

                    <?php endif ?>

                </div>

            </div>            

        </div>

	</div>

</section>