<?php
/** PHP CLI: pruebas aisladas, sólo archivos temporales; no carga WordPress ni toca la BD. */
if ( PHP_SAPI !== 'cli' ) exit;
define( 'ABSPATH', __DIR__ );
error_reporting( E_ALL );
set_error_handler( static function ( $severity, $message, $file, $line ) {
    throw new ErrorException( $message, 0, $severity, $file, $line );
} );
$hooks = [];
$enabled = false;
$admin = true;
$upload = true;
$checks = 0;
function add_filter( $name, $callback, $priority = 10, $args = 1 ) { $GLOBALS['hooks'][ $name ][] = $callback; }
function run_svg_filter( $name, ...$args ) {
    foreach ( $GLOBALS['hooks'][ $name ] ?? [] as $callback ) $args[0] = $callback( ...$args );
    return $args[0];
}
function burger_site_setting( $name, $default = '' ) { return $GLOBALS['enabled']; }
function current_user_can( $cap ) { return 'manage_options' === $cap ? $GLOBALS['admin'] : $GLOBALS['upload']; }
function wp_check_filetype( $filename, $mimes = null ) {
    $mimes = $mimes ?? run_svg_filter( 'upload_mimes', [] );
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    return [ 'ext' => isset( $mimes[ $ext ] ) ? $ext : false, 'type' => $mimes[ $ext ] ?? false ];
}
function get_post_mime_type( $id ) { return 'image/svg+xml'; }
function get_attached_file( $id ) { return $GLOBALS['svg_file']; }
function wp_get_attachment_url( $id ) { return 'https://example.test/image.svg'; }
function _wp_relative_upload_path( $file ) { return basename( $file ); }
function image_constrain_size_for_editor( $width, $height, $size ) {
    $ratio = min( 1, $size[0] / $width, $size[1] / $height );
    return [ (int) round( $width * $ratio ), (int) round( $height * $ratio ) ];
}
function check_svg( $condition, $message ) {
    if ( ! $condition ) throw new RuntimeException( $message );
    $GLOBALS['checks']++;
}
require dirname( __DIR__ ) . '/inc/site-svg.php';
$svg_file = tempnam( sys_get_temp_dir(), 'wodra-svg-' );
$empty_type = [ 'ext' => false, 'type' => false, 'proper_filename' => false ];
$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100"><defs><linearGradient id="g"><stop offset="0" stop-color="red"/></linearGradient></defs><path fill="url(#g)" d="M0 0h100v50z"/></svg>';
$upload_file = static function ( $xml, $name = 'image.svg', $hook = 'wp_handle_upload_prefilter' ) use ( $svg_file ) {
    file_put_contents( $svg_file, $xml );
    return run_svg_filter( $hook, [ 'name' => $name, 'tmp_name' => $svg_file, 'error' => 0, 'size' => strlen( $xml ), 'type' => 'application/xml' ] );
};
try {
    check_svg( ! isset( run_svg_filter( 'upload_mimes', [] )['svg'] ), 'Apagado permite SVG.' );
    check_svg( empty( $upload_file( $svg )['error'] ) && file_get_contents( $svg_file ) === $svg, 'Apagado modifica archivos.' );
    $enabled = true;
    foreach ( [ [ false, true ], [ true, false ] ] as [ $admin, $upload ] ) {
        check_svg( ! isset( run_svg_filter( 'upload_mimes', [] )['svg'] ), 'Permisos insuficientes permiten MIME.' );
        check_svg( ! empty( $upload_file( $svg )['error'] ), 'Permisos insuficientes permiten carga.' );
    }
    $admin = $upload = true;
    check_svg( 'image/svg+xml' === run_svg_filter( 'upload_mimes', [] )['svg'], 'Admin no puede subir.' );
    foreach ( [ 'wp_handle_upload_prefilter', 'wp_handle_sideload_prefilter' ] as $hook ) {
        $result = $upload_file( $svg, 'image.SVG', $hook );
        check_svg( empty( $result['error'] ), 'SVG válido rechazado: ' . $hook );
        $clean = file_get_contents( $svg_file );
        check_svg( false !== strpos( $clean, 'linearGradient' ) && false !== strpos( $clean, 'url(#g)' ), 'Perdió gradientes o referencias internas.' );
        $type = run_svg_filter( 'wp_check_filetype_and_ext', $empty_type, $svg_file, 'image.SVG', null );
        check_svg( 'image/svg+xml' === $type['type'], 'MIME sanitizado no aceptado.' );
        check_svg( false === run_svg_filter( 'wp_check_filetype_and_ext', $empty_type, $svg_file, 'image.svg', [] )['type'], 'Ignora restricción MIME del llamador.' );
        check_svg( [ 200, 100 ] === burger_svg_dimensions( $svg_file ), 'Dimensiones viewBox incorrectas.' );
        file_put_contents( $svg_file, $clean . '<script>alert(1)</script>' );
        check_svg( false === run_svg_filter( 'wp_check_filetype_and_ext', $empty_type, $svg_file, 'image.svg', null )['type'], 'Acepta archivo alterado tras sanitizar.' );
    }
    $malicious = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(1)</script><foreignObject><div>HTML</div></foreignObject><image href="https://evil.test/track.png"/><a href="javascript:alert(1)"><path d="M0 0"/></a><style>.x{fill:url(https://evil.test/x)}</style></svg>';
    check_svg( empty( $upload_file( $malicious )['error'] ), 'No puede limpiar SVG malicioso.' );
    $clean = file_get_contents( $svg_file );
    foreach ( [ '<script', 'onload', 'foreignObject', 'javascript:', 'evil.test' ] as $bad ) check_svg( false === stripos( $clean, $bad ), 'Conserva contenido inseguro: ' . $bad );
    foreach ( [ '', '<svg><path></svg>', '<html/>', '<?php echo 1; ?>', str_repeat( 'x', 2 * 1024 * 1024 + 1 ) ] as $invalid ) {
        check_svg( ! empty( $upload_file( $invalid )['error'] ), 'Acepta archivo inválido o demasiado grande.' );
    }
    check_svg( ! empty( $upload_file( $svg, 'image.svgz' )['error'] ), 'Acepta SVG comprimido.' );
    file_put_contents( $svg_file, $svg );
    unset( $GLOBALS['burger_sanitized_svg'] );
    check_svg( false === run_svg_filter( 'wp_check_filetype_and_ext', $empty_type, $svg_file, 'image.svg', null )['type'], 'Acepta un flujo que saltea la sanitización.' );
    $entity = '<!DOCTYPE svg [<!ENTITY secret SYSTEM "file:///etc/passwd">]><svg xmlns="http://www.w3.org/2000/svg"><text>&secret;</text></svg>';
    $result = $upload_file( $entity );
    check_svg( ! empty( $result['error'] ) || false === strpos( file_get_contents( $svg_file ), 'root:' ), 'Expande entidades externas.' );
    check_svg( [ 0, 0 ] === burger_svg_dimensions( $svg_file ), 'Preview acepta DTD o XML inválido.' );
    $upload_file( $svg );
    $metadata = run_svg_filter( 'wp_generate_attachment_metadata', [], 123 );
    check_svg( 200 === $metadata['width'] && 100 === $metadata['height'], 'Metadatos incorrectos.' );
    $enabled = false;
    $response = run_svg_filter( 'wp_prepare_attachment_for_js', [ 'url' => wp_get_attachment_url( 123 ) ], (object) [ 'ID' => 123, 'post_mime_type' => 'image/svg+xml' ] );
    check_svg( 200 === $response['sizes']['full']['width'], 'Sin preview de SVG existente con switch apagado.' );
    check_svg( [ wp_get_attachment_url( 123 ), 100, 50, false ] === run_svg_filter( 'image_downsize', false, 123, [ 100, 100 ] ), 'No respeta tamaño solicitado.' );
    echo "OK: $checks comprobaciones SVG, sin WordPress ni escrituras en la BD.\n";
} finally {
    unlink( $svg_file );
}
