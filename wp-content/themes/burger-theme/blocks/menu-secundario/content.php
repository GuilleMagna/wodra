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

            <button class="second-nav-arrow second-nav-arrow-prev" type="button" aria-label="Desplazar menú hacia la izquierda">
                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M12.5 4.5 7 10l5.5 5.5"/></svg>
            </button>

            <div class="second-nav-scroll">

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

            <button class="second-nav-arrow second-nav-arrow-next" type="button" aria-label="Desplazar menú hacia la derecha">
                <svg viewBox="0 0 20 20" aria-hidden="true"><path d="m7.5 4.5 5.5 5.5-5.5 5.5"/></svg>
            </button>

        </div>

  	</div>

</nav>

<script>
(function () {
    const root = document.querySelector('.<?= esc_js( $block_id ) ?>');
    if (!root) return;

    const scroller = root.querySelector('.second-nav-scroll');
    const previous = root.querySelector('.second-nav-arrow-prev');
    const next = root.querySelector('.second-nav-arrow-next');
    if (!scroller || !previous || !next) return;

    const update = function () {
        const overflow = scroller.scrollWidth > scroller.clientWidth + 2;
        const maxScroll = Math.max(0, scroller.scrollWidth - scroller.clientWidth);
        previous.classList.toggle('is-visible', overflow);
        next.classList.toggle('is-visible', overflow);
        previous.disabled = !overflow || scroller.scrollLeft <= 2;
        next.disabled = !overflow || scroller.scrollLeft >= maxScroll - 2;

        const link = scroller.querySelector('.nav-link');
        if (link) root.style.setProperty('--second-nav-arrow-color', getComputedStyle(link).color);
    };

    const move = function (direction) {
        scroller.scrollBy({
            left: direction * Math.max(180, scroller.clientWidth * .65),
            behavior: 'smooth'
        });
    };

    previous.addEventListener('click', function () { move(-1); });
    next.addEventListener('click', function () { move(1); });
    scroller.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });

    if ('ResizeObserver' in window) {
        new ResizeObserver(update).observe(scroller);
    }

    requestAnimationFrame(update);
})();
</script>