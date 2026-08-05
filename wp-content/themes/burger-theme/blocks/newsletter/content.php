<?php 
//Block Name: Newsletter

$content_fields = [ 'titulo_newsletter',  'subtitulo_newsletter', 'encabezado', 'texto_newsletter', 'imagen_newsletter', 'titulo_formulario', 'shortcode_formulario' ];
$fields = get_block_content_fields( $block, $content_fields );
extract( $fields );

$design = get_block_design( $block );
extract( $design );

$block_id = $block['id'];

$redes = BURGER_OPTIONS['redes'] ?? [];
?>

<style>
    .<?= $block_id ?>::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: <?= $border_radius ?> !important;
        background-image: url('<?= $imagen_fondo ?>');
        background-size: cover;
        background-repeat: no-repeat;
        z-index: -1;
    }
    .<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        background-color: <?= $color_fondo ?>;
        position: relative;
        isolation: isolate;
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

<section id="newsletter" <?= get_block_wrapper_attributes( [ 'class' => $block_id .' '. $class_container ] ) ?>>
    
    <!--div class="overlay-image">
        <img src="<?= BURGER_URL ?>/wp-content/uploads/2025/06/Scroll_Group_3.png" alt="Scroll_Group_3" class="h-100">
    </div-->

	<div class="container my-5 contenido-news">

		<div class="row">

			<div class="col-12 col-lg-5 me-lg-auto  <?= $text_align_class ?> me-xl-3">

                <<?= $encabezado ?> class="color-primario mb-2" style="text-transform: none; letter-spacing: 0px;" data-aos="fade-up">
                    <?= $titulo_newsletter ?>  <span><?= $subtitulo_newsletter ?></span>
                </<?= $encabezado ?>>

				<div class="text-white mb-4" data-aos="fade-in">
                    <?= $texto_newsletter ?>
                </div>
				
                <div class="iconos-redes mt-3 d-none d-md-block">

                    <?php if( !empty($redes['linkedin']) ): ?>

                        <a href="<?php echo $redes[ 'linkedin' ] ?>" title="Linkedin" target="_blank" class="text-decoration-none me-2" data-aos="fade-in">
                            <?php echo get_burger_icon( 'icon-linkedin' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty($redes['instagram']) ): ?>

                        <a href="<?php echo $redes[ 'instagram' ] ?>" title="Instagram" target="_blank" class="text-decoration-none me-2">
                            <?php echo get_burger_icon( 'icon-instagram' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty($redes['facebook']) ): ?>

                        <a href="<?php echo $redes[ 'facebook' ] ?>" title="Facebook" target="_blank" class="text-decoration-none me-2">
                            <?php echo get_burger_icon( 'icon-facebook' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty($redes['youtube']) ): ?>

                        <a href="<?php echo $redes[ 'youtube' ] ?>" title="Youtube" target="_blank" class="text-decoration-none me-2">
                            <?php echo get_burger_icon( 'icon-youtube' ) ?>
                        </a>

                    <?php endif ?>

                    <?php if( !empty($redes['twitter']) ): ?>

                        <a href="<?php echo $redes[ 'twitter' ] ?>" title="X" target="_blank" class="text-decoration-none me-2">
                            <?php echo get_burger_icon( 'icon-twitter' ) ?>
                        </a>

                    <?php endif ?>

				</div>

			</div>

			<div class="col-12 col-md-6">

				<div>

					<div class="d-flex align-items-center justify-content-start mb-5">

                        <svg class="me-2" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.6667 14.9999L20.0001 20.8333L28.3334 14.9999" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.33325 29.6666V10.3333C3.33325 9.22868 4.22868 8.33325 5.33325 8.33325H34.6666C35.7712 8.33325 36.6666 9.22868 36.6666 10.3333V29.6666C36.6666 30.7712 35.7712 31.6666 34.6666 31.6666H5.33325C4.22868 31.6666 3.33325 30.7712 3.33325 29.6666Z" stroke="white" stroke-width="2"/>
                        </svg>

						<h2 class="color-primario pt-2" data-aos="fade-right">
                            <?php echo $titulo_formulario ?>
                        </h2>

					</div>

                    <div class="color-secundario row g-3 text-white" data-aos="fade-in">
                        <?php echo $shortcode_formulario ?>
                    </div>

				</div>

			</div>

		</div>

	</div>

</section>