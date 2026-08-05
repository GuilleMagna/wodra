<?php 
//Block Name: Menú secundario

$content_fields = [ 'menu_secundario', 'titulo_volver', 'enlace_volver' ];
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

<nav id="secondNav" class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-lg-3 shadow <?= $block_id ?> <?php echo @get_block_classes() ?>">

  	<div class="container px-lg-0">

        <div class="nav-item px-lg-2 my-auto me-auto first-item d-lg-none">
            <a class="nav-link py-2 py-lg-1" href="<?php echo $enlace_volver ?>">
                <?php echo $titulo_volver ?>
            </a>
        </div>

    	<button class="navbar-toggler border-0 icon-mobile collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      		<svg xmlns="http://www.w3.org/2000/svg" width="21.121" height="11.81" viewBox="0 0 21.121 11.81">
                <path id="Path_60" data-name="Path 60" d="M20,1,10.5,11,1,1" transform="translate(21.061 11.75) rotate(180)" fill="none" stroke="#796e65" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
            </svg>
    	</button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav w-100 text-uppercase py-4 py-lg-0">

                <li class="nav-item px-lg-2 my-auto first-item d-none d-lg-block">
                    <a class="nav-link py-2 py-lg-1" href="<?php echo $enlace_volver ?>">
                        <?php echo $titulo_volver ?>
                    </a>
                </li>

                <?php if( !empty( $menu_secundario ) and count( $menu_secundario ) > 0 ) foreach( $menu_secundario as $key => $menu ): if( empty( $menu['item']['url'] ) ) continue ?>
                    
                    <li class="nav-item px-lg-2 my-auto">
                        <a class="nav-link py-2 py-lg-1" href="<?php echo $menu['item']['url'] ?>" title="<?php echo $menu['item']['title'] ?>" target="<?php echo $menu['item']['target'] ?>">
                            <?php echo $menu['item']['title'] ?>
                        </a>
                    </li>

                <?php endforeach ?>

            </ul>

        </div>

  	</div>

</nav>