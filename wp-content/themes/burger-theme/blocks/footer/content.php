<?php
//Block Name: Footer

$copyright = BURGER_OPTIONS['copyright'] ?: '&copy; Copyright 2025 Milicic';
$menu_footer = BURGER_OPTIONS['menu_footer'] ?: [];
$modales_menu_footer = BURGER_OPTIONS['modales_menu_footer'] ?: [];
$texto_agencia = BURGER_OPTIONS['texto_agencia'] ?? 'Powered by';
$logo_agencia = BURGER_OPTIONS['logo_agencia'] ?: BURGER_THEME_URL . '/themes/images/wodra.png';
$enlace_agencia = BURGER_OPTIONS['enlace_agencia'] ?: [];

$logo_footer = BURGER_OPTIONS['logo_footer'] ?: BURGER_THEME_URL . '/themes/images/wodra.png';
$color_texto_footer = BURGER_OPTIONS['color_texto_footer'] ?: '#ffffff';
$color_fondo_footer = BURGER_OPTIONS['color_fondo_footer'] ?: 'var(--primary)';
?>

<div id="footer" class="py-3 <?php echo @get_block_classes() ?>" style="background-color: <?= $color_fondo_footer ?>">

    <div class="container">

        <div class="row py-2">

            <div class="col-md-3">
                <p class="mb-2 mb-md-0 text-center text-lg-start" style="color: <?= $color_texto_footer ?>">
                    <?php echo $copyright ?>
                </p>
            </div>

            <div class="col-md-6 text-center" style="color: <?= $color_texto_footer ?>">

                <p class="mb-2 mb-md-0">

                    <?php foreach ($menu_footer as $key => $menu): ?>

                        <?php if (count($menu['item']) == 0)
                            continue ?>

                            <a <?php if ($menu['modal'])
                            echo 'href="#" data-bs-toggle="modal" data-bs-target="' . $menu['item']['url'] . '"';
                        else
                            echo 'href="' . $menu['item']['url'] . '"' ?>
                                title="<?php echo $menu['item']['title'] ?>" target="<?php echo $menu['item']['target'] ?>">
                            <?php echo $menu['item']['title'] ?>
                        </a>

                        <?php if ($key < count($menu_footer) - 1)
                            echo '&nbsp;&nbsp;|&nbsp;&nbsp;' ?>

                    <?php endforeach ?>

                </p>

            </div>

            <div class="col-md-3">

                <p class="mb-2 mb-md-0 text-center text-lg-end" style="color: <?= $color_texto_footer ?>">
                    <?php if (!empty($enlace_agencia) and count($enlace_agencia) > 0): ?><a
                            href="<?php echo $enlace_agencia['url'] ?>" target="_blank"><?php endif ?>
                        <?php echo $texto_agencia ?>&nbsp;<img loading="lazy" decoding="async" src="<?php echo $logo_agencia ?>" style="height:12px;vertical-align:center;"
                            alt="<?php echo $texto_agencia ?>" title="<?php echo $texto_agencia ?>">
                        <?php if (!empty($enlace_agencia) and count($enlace_agencia) > 0): ?></a><?php endif ?>
                </p>

            </div>

        </div>

    </div>

    </footer>

    <?php if (BURGER_OPTIONS['mostrar_whatsapp']): ?>

        <div class="whatsapp-button">
            <a href="https://wa.me/<?php echo BURGER_OPTIONS['whatsapp'] ?>?text=<?php echo urlencode(BURGER_OPTIONS['texto_whatsapp']) ?>"
                class="text-decoration-none" target="_blank">
                <img loading="lazy" decoding="async" src="<?php echo BURGER_OPTIONS['logo_whatsapp'] ?>" style="width:120px" alt="logo wapp">
            </a>
        </div>

    <?php endif ?>

    <?php if (!empty($modales_menu_footer) and count($modales_menu_footer) > 0)
        foreach ($modales_menu_footer as $modal): ?>

            <div class="modal modal-lg fade" id="<?php echo $modal['id_modal'] ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title" id="staticBackdropLabel">
                                <?php echo $modal['titulo'] ?>
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        </div>

                        <div class="modal-body">
                            <?php echo $modal['contenido'] ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach ?>