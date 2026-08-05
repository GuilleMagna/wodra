<?php

/**
 * Junta varios archivos CSS/JS del theme en un solo archivo cacheado en
 * disco, para bajar la cantidad de requests del front (hoy header.php/
 * footer.php cargan ~9 CSS y ~6 JS por separado).
 *
 * El nombre del bundle incluye un hash de los mtimes de los archivos
 * fuente, así que si se actualiza cualquier vendor el bundle se
 * regenera solo (y el navegador lo vuelve a descargar por el cambio de
 * URL, sin depender de cabeceras de caché).
 */
function burger_get_asset_bundle_url( $type, array $files ) {

    $theme_path = BURGER_THEME_PATH;
    $theme_url  = BURGER_THEME_URL;
    $cache_dir  = $theme_path . '/cache';

    if ( ! file_exists( $cache_dir ) ) {
        wp_mkdir_p( $cache_dir );
    }

    $sig = [];

    foreach ( $files as $file ) {
        $path = $theme_path . '/' . ltrim( $file, '/' );
        if ( file_exists( $path ) ) {
            $sig[] = $file . ':' . filemtime( $path );
        }
    }

    $hash     = substr( md5( implode( '|', $sig ) ), 0, 10 );
    $filename = "front-{$type}-{$hash}.{$type}";
    $bundle_path = $cache_dir . '/' . $filename;

    if ( ! file_exists( $bundle_path ) ) {
        burger_write_asset_bundle( $bundle_path, $type, $files, $theme_path, $theme_url );

        // Limpia bundles viejos del mismo tipo para no acumular basura.
        foreach ( glob( $cache_dir . "/front-{$type}-*.{$type}" ) as $old ) {
            if ( $old !== $bundle_path ) {
                @unlink( $old );
            }
        }
    }

    return $theme_url . '/cache/' . $filename;
}

function burger_write_asset_bundle( $bundle_path, $type, array $files, $theme_path, $theme_url ) {

    $output = '';

    foreach ( $files as $file ) {

        $path = $theme_path . '/' . ltrim( $file, '/' );

        if ( ! file_exists( $path ) ) {
            continue;
        }

        $content = file_get_contents( $path );

        if ( $type === 'css' ) {
            // Los archivos quedan todos en /cache/, así que hay que
            // volver absolutas las url() relativas de cada uno (fuentes,
            // íconos, sprites) usando la carpeta original de cada archivo.
            // Se arma protocol-relative (//dominio/...) para no depender
            // de que is_ssl() detecte bien el HTTPS en el momento exacto
            // en que se genera el bundle (típico atrás de un proxy/CDN).
            $dir_url = rtrim( preg_replace( '#^https?:#i', '', $theme_url ) . '/' . dirname( $file ), '/' );

            $content = preg_replace_callback(
                '/url\(\s*([\'"]?)(?!data:|https?:|\/\/|\/)([^\'")]+)\1\s*\)/i',
                function ( $m ) use ( $dir_url ) {
                    return 'url(' . $m[1] . $dir_url . '/' . $m[2] . $m[1] . ')';
                },
                $content
            );
        }

        $output .= "\n/* {$file} */\n" . $content . "\n";
    }

    file_put_contents( $bundle_path, $output );
}

add_action( 'wp_enqueue_scripts', function () {

    if ( is_admin() ) {
        return;
    }

    $css_files = [
        'vendors/bootstrap5.2/css/bootstrap.min.css',
        'vendors/font-awesome6/css/all.min.css',
        'vendors/fancybox/fancybox.css',
        'vendors/swiperjs/swiper-bundle.min.css',
        'vendors/aos/aos.css',
        'themes/css/fonts.css',
        'themes/css/colors.css',
        'themes/css/styler.css',
        'themes/css/buttons.css',
    ];

    $js_files = [
        'vendors/jquery-3.6.0.min.js',
        'vendors/countUp.min.js',
        'vendors/bootstrap5.2/js/bootstrap.bundle.min.js',
        'vendors/fancybox/fancybox.umd.js',
        'vendors/swiperjs/swiper-bundle.min.js',
        'vendors/aos/aos.js',
    ];

    wp_enqueue_style(
        'burger-front-bundle',
        burger_get_asset_bundle_url( 'css', $css_files ),
        [],
        null
    );

    wp_enqueue_script(
        'burger-front-bundle',
        burger_get_asset_bundle_url( 'js', $js_files ),
        [],
        null,
        false // en el <head>, mismo orden/momento que antes (evita romper scripts inline que ya asumen jQuery cargado ahí)
    );
}, 5 );
