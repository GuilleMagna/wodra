<?php 
//Block Name: Timeline

$content_fields = [ 'titulo_timeline', 'timeline' ];
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

<section id="timeline" <?= get_block_wrapper_attributes( [ 'class' => $block_id.' '.$class_container ] ) ?>>
	
	<div class="container">

		<div class="text-center text-lg-start border-start border-primary border-4 mb-5 ps-3">

			<h2 class="titulo text-big-titulos semi-bold mb-0 mx-auto <?= $col_container_class ?> <?= $col_md_container_class ?> <?= $col_lg_container_class ?> <?= $text_align_class ?>">
                <?php echo $titulo_timeline ?>
            </h2>

		</div>	
        
        <?php if( !empty( $timeline ) and count( $timeline ) > 0 ): ?>

            <section class="cd-horizontal-timeline">

                <div class="timeline">

                    <div class="events-wrapper">

                        <div class="events">

                            <ol style="padding-right: 0px; padding-left: 0px;">

                                <?php foreach( $timeline as $key => $hito ): ?>

                                    <li>
                                        <a href="#0" data-date="<?php echo $hito['fecha'] ?>" <?php if( $key==0 )echo 'class="selected"' ?>>
                                            <?php echo $hito['anio'] ?>
                                        </a>
                                    </li>

                                <?php endforeach ?>

                            </ol>

                            <span class="filling-line" aria-hidden="true"></span>

                        </div>

                    </div>
                        
                    <ul class="cd-timeline-navigation">

                        <li>
                            <a href="#0" class="prev inactive">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="36.544" viewBox="0 0 20 36.544" class="text-primary">
                                    <path id="chevron-right-solid" d="M64.657,48.354a2.7,2.7,0,0,0,0,3.694l15,15.657a2.429,2.429,0,0,0,3.538,0,2.7,2.7,0,0,0,0-3.694L69.961,50.2,83.185,36.383a2.7,2.7,0,0,0,0-3.694,2.429,2.429,0,0,0-3.538,0l-15,15.657Z" transform="translate(-63.925 -31.925)"/>
                                </svg>
                            </a>
                        </li>

                        <li>
                            <a href="#0" class="next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="36.544" viewBox="0 0 20 36.544" class="text-primary">
                                    <path id="chevron-right-solid" d="M83.193,48.354a2.7,2.7,0,0,1,0,3.694l-15,15.657a2.429,2.429,0,0,1-3.538,0,2.7,2.7,0,0,1,0-3.694L77.889,50.2,64.665,36.383a2.7,2.7,0,0,1,0-3.694,2.429,2.429,0,0,1,3.538,0l15,15.657Z" transform="translate(-63.925 -31.925)"/>
                                </svg>
                            </a>
                        </li>

                    </ul>

                </div>

                <div class="events-content mt-0">

                    <ol style="padding-right: 0px; padding-left: 0px;">
                        
                        <?php foreach( $timeline as $key => $hito ): ?>
                            
                            <li <?php if( $key==0 )echo 'class="selected"' ?> data-date="<?php echo $hito['fecha'] ?>">
                                <p class="text-normal-dos text-center">	
                                    <?php echo $hito['suceso'] ?>
                                </p>
                            </li>

                        <?php endforeach ?>

                    </ol>

                </div>

            </section>

        <?php endif ?>

	</div>

</section>
