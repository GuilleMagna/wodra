<?php

require_once __DIR__ . '/image-dimensions.php';

/**
 * Junta el CSS base del theme en un archivo cacheado en disco.
 * Los vendors opcionales se encolan por bloque y su JS se difiere al footer.
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

    $sig = [ 'bundle-v2-font-swap' ];

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
            // Font Awesome no debe ocultar los íconos mientras descarga la fuente.
            if ( false !== strpos( $file, 'font-awesome' ) ) {
                $content = preg_replace( '/font-display\s*:\s*block/', 'font-display:swap', $content );
            }
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

/**
 * WP 7.0 agregó `wp_hoist_late_printed_styles()`: intercepta los estilos que se
 * encolan después de wp_head (los "late styles"), los captura en un output
 * buffer y los reinyecta en el <head>.
 *
 * Los styles.css de cada bloque se encolan en el `enqueue_assets` de ACF, o sea
 * durante el render del contenido => son late styles. Cuando la reinyección no
 * ocurre, esos estilos quedan capturados y nunca se imprimen: la página sale sin
 * NINGÚN CSS de bloque (header, footer y todo lo demás sin estilo).
 *
 * Core deja el opt-out a mano: alcanza con desenganchar la acción. Los late
 * styles vuelven a imprimirse normalmente en el footer, como hasta WP 6.x.
 *
 * Se hace en `template_redirect` porque la acción se engancha en
 * `wp_default_styles`, que puede dispararse antes de que se cargue el theme (si
 * un plugin instancia wp_styles() temprano). `template_redirect` corre siempre
 * antes de `wp_before_include_template`, que es donde arranca el buffer.
 */
add_action( 'template_redirect', function () {
    remove_action( 'wp_template_enhancement_output_buffer_started', 'wp_hoist_late_printed_styles' );
}, 0 );

add_action( 'wp_enqueue_scripts', function () {

    if ( is_admin() ) {
        return;
    }

    $font_url = trim( BURGER_OPTIONS['url_font_family'] ?? '' );
    if ( $font_url ) {
        if ( 'fonts.googleapis.com' === wp_parse_url( $font_url, PHP_URL_HOST ) ) $font_url = add_query_arg( 'display', 'swap', $font_url );
        wp_enqueue_style( 'burger-fonts', $font_url, [], null );
    }

    $css_files = [
        'vendors/bootstrap5.2/css/bootstrap.min.css',
        'vendors/font-awesome6/css/all.min.css',
        'themes/css/fonts.css',
        'themes/css/colors.css',
        'themes/css/styler.css',
        'themes/css/buttons.css',
    ];

    wp_enqueue_style(
        'burger-front-bundle',
        burger_get_asset_bundle_url( 'css', $css_files ),
        [],
        null
    );

    // Los inline existentes registran callbacks de ready durante el parseo.
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'burger-bootstrap', BURGER_THEME_URL . '/vendors/bootstrap5.2/js/bootstrap.bundle.min.js', [], '5.2', [ 'in_footer' => true, 'strategy' => 'defer' ] );
    foreach ( burger_optional_vendors() as $handle => $vendor ) {
        // Owl ya fue registrado por setup.php: sustituir su registro global.
        wp_dequeue_script( $handle );
        wp_deregister_script( $handle );
        wp_register_script( $handle, BURGER_THEME_URL . '/' . $vendor['js'], $vendor['deps'] ?? [], filemtime( BURGER_THEME_PATH . '/' . $vendor['js'] ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
    }
}, 5 );

// setup.php también encola Owl en prioridad 10; la carga pasa a ser por bloque.
add_action( 'wp_enqueue_scripts', static function () {
    wp_dequeue_script( 'owl-carousel' );
    wp_dequeue_style( 'owl-carousel' );
}, 30 );

function burger_optional_vendors() {
    return [
        'owl-carousel' => [ 'js' => 'vendors/owlcarousel2/owl.carousel.min.js', 'css' => 'vendors/owlcarousel2/assets/owl.carousel.min.css', 'deps' => [ 'jquery' ] ],
        'burger-swiper' => [ 'js' => 'vendors/swiperjs/swiper-bundle.min.js', 'css' => 'vendors/swiperjs/swiper-bundle.min.css' ],
        'burger-fancybox' => [ 'js' => 'vendors/fancybox/fancybox.umd.js', 'css' => 'vendors/fancybox/fancybox.css' ],
        'burger-aos' => [ 'js' => 'vendors/aos/aos.js', 'css' => 'vendors/aos/aos.css' ],
        'burger-countup' => [ 'js' => 'vendors/countUp.min.js' ],
    ];
}

/** Usa el HTML real, incluso cacheado, y el script del bloque que se está mostrando. */
function burger_prepare_front_block_html( $html, $slug ) {
    if ( is_admin() ) return $html;
    $script = BURGER_THEME_PATH . '/blocks/' . $slug . '/scripts.js';
    $source = $html . ( is_file( $script ) ? file_get_contents( $script ) : '' );
    $needed = [
        'owl-carousel' => false !== strpos( $source, 'owlCarousel' ),
        'burger-swiper' => false !== strpos( $source, 'new Swiper' ),
        'burger-fancybox' => false !== strpos( $source, 'data-fancybox' ),
        'burger-aos' => false !== strpos( $html, 'data-aos=' ),
        'burger-countup' => false !== strpos( $source, 'new CountUp' ),
    ];
    foreach ( burger_optional_vendors() as $handle => $vendor ) {
        if ( ! $needed[ $handle ] ) continue;
        wp_enqueue_script( $handle );
        if ( isset( $vendor['css'] ) ) wp_enqueue_style( $handle, BURGER_THEME_URL . '/' . $vendor['css'], [], filemtime( BURGER_THEME_PATH . '/' . $vendor['css'] ) );
        if ( 'owl-carousel' === $handle ) burger_enqueue_carousel_navigation();
    }
    return burger_block_image_dimensions( $html );
}

add_action( 'wp_footer', static function () {
    $deps = [];
    foreach ( [ 'burger-aos', 'burger-fancybox' ] as $handle ) {
        if ( wp_script_is( $handle, 'enqueued' ) ) $deps[] = $handle;
    }
    if ( $deps ) wp_enqueue_script( 'burger-vendor-init', BURGER_THEME_URL . '/themes/js/vendor-init.js', $deps, filemtime( BURGER_THEME_PATH . '/themes/js/vendor-init.js' ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
}, 5 );

// Cargar después de Owl y también en previews del editor.
function burger_enqueue_carousel_navigation() {
    $dependency = is_admin() ? 'burger-editor-owl' : 'owl-carousel';
    wp_enqueue_script(
        'burger-carousel-navigation',
        BURGER_THEME_URL . '/themes/js/carousel-navigation.js',
        [ $dependency ],
        filemtime( BURGER_THEME_PATH . '/themes/js/carousel-navigation.js' ),
        ! is_admin()
    );
    if ( ! is_admin() ) wp_script_add_data( 'burger-carousel-navigation', 'strategy', 'defer' );
    wp_enqueue_style(
        'burger-carousel-navigation',
        BURGER_THEME_URL . '/themes/css/carousel-navigation.css',
        [],
        filemtime( BURGER_THEME_PATH . '/themes/css/carousel-navigation.css' )
    );
}
add_action( 'enqueue_block_editor_assets', 'burger_enqueue_carousel_navigation', 30 );

// Inicializar las pestañas cuando SCF remonta el formulario del editor extendido.
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'burger-editor-tabs',
        BURGER_THEME_URL . '/themes/js/editor-tabs.js',
        [ 'acf-input', 'acf-blocks' ],
        filemtime( BURGER_THEME_PATH . '/themes/js/editor-tabs.js' ),
        true
    );
}, 30 );

// Reemplazar la descarga global de Turnstile de CF7 por carga al interactuar.
// Conservar el handle para respetar dependencias, sin el reset global del plugin.
add_action( 'wp_enqueue_scripts', function () {
    if ( is_admin() || ! wp_script_is( 'cloudflare-turnstile', 'enqueued' ) ) {
        return;
    }
    wp_dequeue_script( 'cloudflare-turnstile' );
    wp_deregister_script( 'cloudflare-turnstile' );
    wp_enqueue_script(
        'cloudflare-turnstile',
        BURGER_THEME_URL . '/themes/js/turnstile-lazy.js',
        [],
        filemtime( BURGER_THEME_PATH . '/themes/js/turnstile-lazy.js' ),
        true
    );
}, 100 );
