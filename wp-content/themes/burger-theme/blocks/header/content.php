<?php
//Block Name: Header

$logo = BURGER_OPTIONS['logo'] ?? '';
$menu_header = BURGER_OPTIONS['menu'] ?? [];
$mostrar_redes = BURGER_OPTIONS['mostrar_redes'] ?? false;
$redes_menu = BURGER_OPTIONS['redes_menu'] ?? [];
$menu_top = BURGER_OPTIONS['menu_top'] ?? [];
$mostrar_idiomas = BURGER_OPTIONS['mostrar_idiomas'] ?? false;
$idiomas = BURGER_OPTIONS['idiomas'] ?? [];
$color_header = BURGER_OPTIONS['color_header'] ?? 'var(--secondary)';
?>

<style>
    .dropdown-menu {
        background-color: <?php echo $color_header ?>;
    }
</style>

<header id="header" class="<?php echo @get_block_classes() ?>">

    <div class="fixed-top<?php if (get_queried_object_id() != get_option('page_on_front')) echo ' is-page' ?>" id="fixedNav">

        <nav class="navbar navbar-expand-mb d-none d-md-block py-0" id="preNav">

            <div class="container">

                <div class="row d-flex justify-content-end ms-auto">

                    <div class="col-auto">

                        <nav id="preNav" class="navbar navbar-expand-lg">

                            <div id="preNavResponsive" class="collapse navbar-collapse">

                                <ul class="navbar-nav ms-auto">

                                    <?php foreach ($menu_top as $key => $menu): ?>

                                        <?php
                                        if (empty($menu['item']) OR empty($menu['item']['url'])) continue;
                                        $icono = $menu['icono'] ?? '';
                                        ?>

                                        <li class="nav-item<?php if ($key == 0) echo ' first-item' ?> px-2 my-2 d-flex align-items-center">

                                            <?php if ($icono): ?>
                                                <img src="<?php echo $icono ?>" alt="icon" style="width:auto;height:15px;">
                                            <?php endif; ?>

                                            <a href="<?php echo $menu['item']['url'] ?>" class="nav-link py-1"
                                                title="<?php echo $menu['item']['title'] ?>"
                                                target="<?php echo $menu['item']['target'] ?>"
                                                <?php if ($menu['color']): ?>style="color:<?= $menu['color'] ?>"<? endif ?>>
                                                <?php echo $menu['item']['title'] ?>
                                            </a>

                                        </li>

                                    <?php endforeach ?>

                                    <?php if ($mostrar_idiomas): ?>

                                        <li class="nav-item my-2 d-flex align-items-center">

                                            <?php if (!empty($idiomas) && count($idiomas) > 0)
                                                foreach ($idiomas as $menu): ?>
                                                    <?php echo do_shortcode('[gt-link lang="' . $menu['codigo'] . '" label="' . $menu['label'] . '" widget_look="flags"]') ?>
                                                <?php endforeach ?>

                                        </li>

                                    <?php endif ?>

                                </ul>

                            </div>

                        </nav>

                    </div>

                </div>

            </div>

        </nav>

        <nav class="navbar navbar-expand-lg navbar-dark py-0" role="navigation" id="mainNav">

            <div class="container">

                <a href="<?php echo BURGER_URL ?>" class="navbar-brand logo-head">
                    <img src="<?php echo $logo ?>">
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <img src="<?php echo BURGER_THEME_URL ?>/themes/images/menu-mob.svg" alt="menu de mobile">
                </button>

                <div class="offcanvas offcanvas-end bg-transparent" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel" aria-hidden="true">

                    <div class="offcanvas-header justify-content-between bg-secondary pt-3">

                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                            <a href="<?php echo BURGER_URL ?>" style="text-decoration: none;" class="logo-head">
                                <img src="<?php echo $logo ?>" class="ms-3" alt="Logo">
                            </a>
                        </h5>

                        <button type="button" class="btn  border-0 text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close">
                            <img src="<?php echo BURGER_THEME_URL ?>/themes/images/x.svg"
                                alt="cierre de menu de mobile">
                        </button>

                    </div>

                    <div class="offcanvas-body">

                        <ul class="navbar-nav justify-content-lg-end flex-grow-1 pt-2 pb-3">

                            <?php foreach ($menu_header as $menu): ?>

                                <?php
                                if (empty($menu['item']) OR empty($menu['item']['url'])) continue;
                                $icono = $menu['icono'] ?? '';
                                ?>

                                <?php if ($menu['tiene_submenu']): ?>

                                    <li class="nav-item dropdown">

                                        <div class="btn-group d-flex align-items-center">

                                            <a class="nav-link text-nav" href="<?php echo $menu['item']['url'] ?>" title="<?php echo $menu['item']['title'] ?>">
                                                <?php if ($icono): ?><img src="<?php echo $icono ?>" class="me-1 pb-1" alt="icon" style="width:auto;height:15px;"><?php endif; ?>
                                                <?php echo $menu['item']['title'] ?>
                                            </a>

                                            <button class="dropdown-toggle dropdown-toggle-split nav-link px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>

                                            <ul class="dropdown-menu py-0 py-lg-3 px-0 px-lg-4 border-0 rounded-0">

                                                <?php foreach ($menu['submenu'] as $submenu): ?>

                                                    <li>
                                                        <a class="dropdown-item nav-link text-nav py-lg-2"
                                                            href="<?php echo $submenu['item']['url'] ?>"
                                                            title="<?php echo $submenu['item']['title'] ?>"
                                                            target="<?php echo $submenu['item']['target'] ?>">
                                                            <?php echo $submenu['item']['title'] ?>
                                                        </a>
                                                    </li>

                                                <?php endforeach ?>

                                            </ul>

                                        </div>

                                    </li>
                                <?php else: ?>

                                    <li class="nav-item">
                                        <a href="<?php echo $menu['item']['url'] ?>" class="nav-link text-nav" title="<?php echo $menu['item']['title'] ?>" target="<?php echo $menu['item']['target'] ?>">
                                            <?php if ($icono): ?><img src="<?php echo $icono ?>" class="me-1 pb-1" alt="icon" style="width:auto;height:15px;"><?php endif; ?>
                                            <?php echo $menu['item']['title'] ?>
                                        </a>
                                    </li>

                                <?php endif ?>

                            <?php endforeach ?>

                            <?php foreach ($menu_top as $key => $menu): ?>

                                <?php
                                if (empty($menu['item']) OR empty($menu['item']['url']))
                                    continue;
                                $icono = $menu['icono'] ?? '';
                                ?>

                                <li class="nav-item d-lg-none">
                                    <a href="<?php echo $menu['item']['url'] ?>" class="nav-link text-nav"
                                        title="<?php echo $menu['item']['title'] ?>"
                                        target="<?php echo $menu['item']['target'] ?>"
                                        <?php if ($menu['color']): ?>style="color:<?= $menu['color'] ?>"<? endif ?>>
                                        <?php if ($icono): ?><img src="<?php echo $icono ?>" class="pb-1" alt="icon" style="width:auto;height:15px;"><?php endif; ?>
                                        <?php echo $menu['item']['title'] ?>
                                    </a>
                                </li>

                            <?php endforeach ?>

                            <?php if ($mostrar_idiomas): ?>

                                <li class="nav-item my-2 d-flex align-items-center d-md-none">

                                    <div class="btn-group flags">

                                        <?php if ($mostrar_idiomas): ?>

                                            <?php if (!empty($idiomas) && count($idiomas) > 0) foreach ($idiomas as $menu): ?>
                                                <?php echo do_shortcode('[gt-link lang="' . $menu['codigo'] . '" label="' . $menu['label'] . '" widget_look="flags"]') ?>
                                            <?php endforeach ?>

                                        <?php endif ?>

                                    </div>

                                </li>

                            <?php endif ?>

                            <?php if ($mostrar_redes): ?>

                                <li class="nav-item">

                                    <?php if (!empty($redes_menu) && count($redes_menu) > 0) foreach ($redes_menu as $red): ?>

                                        <a href="<?php echo $red['url'] ?>" class="nav-link text-nav icon-swap" title="<?php echo $red['nombre'] ?>" target="_blank">
                                            <img src="<?php echo $red['icono'] ?>" class="icon-default me-1 pb-1" alt="icono <?php echo $red['nombre'] ?>" style="width:auto;height:20px;">
                                            <img src="<?php echo $red['icono_hover'] ?>" class="icon-hover me-1 pb-1" alt="icono <?php echo $red['nombre'] ?>" style="width:auto;height:20px;">
                                        </a>

                                    <?php endforeach ?>

                                </li>

                            <?php endif ?>

                        </ul>

                    </div>

                </div>

            </div>

        </nav>

    </div>

</header>


<?php if (get_queried_object_id() != get_option('page_on_front')): ?>

<script>
    jQuery(document).ready(function ($) {

        $(window).scroll(function () {

            if ($(window).scrollTop() > $(window).height() / 4) {
                $("#fixedNav").css({
                    "background-color": "<?php echo $color_header ?>",
                });
                //$("#logo-dark").addClass('d-none');
                //$("#logo-light").removeClass('d-none');
            } else {
                $("#fixedNav").css({
                    "background-color": "<?php echo $color_header ?>",
                });
                //$("#logo-dark").removeClass('d-none');
                //$("#logo-light").addClass('d-none');
            }

        });

    });
</script>

<?php else: ?>

<script>
    jQuery(document).ready(function ($) {

        $(window).scroll(function () {

            if ($(window).scrollTop() > $(window).height() / 4) {
                $("#fixedNav").css({
                    "background-color": "<?php echo $color_header ?>",
                });
                //$("#logo-dark").addClass('d-none');
                //$("#logo-light").removeClass('d-none');
            } else {
                $("#fixedNav").css({
                    "background-color": "transparent",
                });
                //$("#logo-dark").removeClass('d-none');
                //$("#logo-light").addClass('d-none');
            }

        });

    });
</script>

<?php endif; ?>