<?php
/** PHP CLI: no crea registros; omite CF7 incompatible con PHP CLI 8.2. */
if ( PHP_SAPI !== 'cli' ) exit;
define( 'DISABLE_WP_CRON', true );
$GLOBALS['wp_filter']['option_active_plugins'][0][] = [ 'function' => static function ( $plugins ) {
    return array_values( array_diff( $plugins, [ 'contact-form-7/wp-contact-form-7.php' ] ) );
}, 'accepted_args' => 1 ];
$GLOBALS['wp_filter']['pre_http_request'][0][] = [ 'function' => static function () { return new WP_Error( 'offline_test', 'Prueba sin red.' ); }, 'accepted_args' => 1 ];
require dirname( __DIR__, 4 ) . '/wp-load.php';
$checks = 0;
function check_perf( $condition, $message ) {
    if ( ! $condition ) throw new RuntimeException( $message );
    $GLOBALS['checks']++;
}
do_action( 'wp_enqueue_scripts' );
check_perf( wp_script_is( 'jquery', 'enqueued' ), 'Falta jQuery de WordPress.' );
check_perf( ! wp_script_is( 'burger-front-bundle', 'enqueued' ), 'Sigue cargando jQuery duplicado.' );
foreach ( burger_optional_vendors() as $handle => $vendor ) check_perf( ! wp_script_is( $handle, 'enqueued' ), 'Vendor global: ' . $handle );
$head = '<div>Sin carrusel ni animaciones</div>';
check_perf( $head === burger_prepare_front_block_html( $head, 'sin-bloque' ), 'Modifica HTML sin recursos.' );
check_perf( ! wp_script_is( 'burger-aos', 'enqueued' ), 'Carga AOS sin animaciones.' );
burger_prepare_front_block_html( '<div data-aos="fade-up"></div>', 'sin-bloque' );
check_perf( wp_script_is( 'burger-aos', 'enqueued' ) && wp_style_is( 'burger-aos', 'enqueued' ), 'No carga AOS por HTML.' );
burger_prepare_front_block_html( '<a data-fancybox="test"></a>', 'sin-bloque' );
check_perf( wp_script_is( 'burger-fancybox', 'enqueued' ), 'No carga Fancybox.' );
burger_prepare_front_block_html( '<script>jQuery(function($){$(".test").owlCarousel({});});</script>', 'sin-bloque' );
check_perf( wp_script_is( 'owl-carousel', 'enqueued' ) && wp_script_is( 'burger-carousel-navigation', 'enqueued' ), 'No carga Owl/navegación para inline cacheado.' );
burger_prepare_front_block_html( '<div></div>', 'productos' );
check_perf( wp_script_is( 'burger-swiper', 'enqueued' ), 'No carga Swiper desde scripts.js.' );
burger_prepare_front_block_html( '<div></div>', 'contador' );
check_perf( wp_script_is( 'burger-countup', 'enqueued' ), 'No carga CountUp desde scripts.js.' );
foreach ( array_keys( burger_optional_vendors() ) as $handle ) check_perf( 'defer' === wp_scripts()->get_data( $handle, 'strategy' ), 'Falta defer: ' . $handle );
$url = BURGER_THEME_URL . '/themes/images/menu-mob.svg';
$size = burger_local_image_dimensions( $url );
check_perf( ! empty( $size ), 'No resuelve dimensiones SVG local.' );
$image = burger_block_image_dimensions( '<img src="' . esc_url( $url ) . '">' );
check_perf( str_contains( $image, 'width=' ) && str_contains( $image, 'height=' ), 'No agrega dimensiones.' );
$explicit = '<img src="' . esc_url( $url ) . '" width="10" height="20">';
check_perf( $explicit === burger_block_image_dimensions( $explicit ), 'Pisa dimensiones explícitas.' );
check_perf( [] === burger_local_image_dimensions( 'https://example.invalid/image.svg' ), 'Acepta archivo remoto.' );
check_perf( [] === burger_local_image_dimensions( BURGER_THEME_URL . '/../burger-theme/../../../../wp-config.php' ), 'Permite salir del directorio.' );
$style_url = wp_styles()->registered['burger-front-bundle']->src;
$css = file_get_contents( BURGER_THEME_PATH . '/cache/' . basename( $style_url ) );
check_perf( ! str_contains( $css, 'font-display:block' ) && str_contains( $css, 'font-display:swap' ), 'Font Awesome sigue bloqueando.' );
foreach ( [ 'fancybox.css', 'swiper-bundle.min.css', 'aos.css' ] as $vendor ) check_perf( ! str_contains( $css, '/* vendors/' . $vendor ) && ! str_contains( $css, '/' . $vendor . ' */' ), 'Vendor opcional dentro del CSS global.' );
ob_start();
wp_print_scripts( [ 'jquery' ] );
$jquery = ob_get_clean();
check_perf( 1 === substr_count( $jquery, 'id="jquery-core-js"' ), 'jQuery core duplicado.' );
echo "OK: $checks comprobaciones con WordPress real; sin registros de prueba.\n";
