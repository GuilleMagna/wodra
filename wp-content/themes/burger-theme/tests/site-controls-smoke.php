<?php
/** Ejecutar con PHP CLI. No escribe opciones, comentarios ni usuarios en la BD. */
if ( PHP_SAPI !== 'cli' ) exit;
$GLOBALS['wp_filter']['option_active_plugins'][1][] = [ 'function' => static function ( $plugins ) {
    return array_values( array_diff( $plugins, [ 'disable-comments/disable-comments.php', 'custom-login/custom-login.php' ] ) );
}, 'accepted_args' => 1 ];
require dirname( __DIR__, 4 ) . '/wp-load.php';

$checks = 0;
function check_control( $condition, $message ) {
    global $checks;
    if ( ! $condition ) throw new RuntimeException( $message );
    $checks++;
}
$settings = [];
add_filter( 'pre_option', static function ( $pre, $option ) use ( &$settings ) {
    return array_key_exists( $option, $settings ) ? $settings[ $option ] : $pre;
}, 10, 2 );
$set = static function ( $name, $value ) use ( &$settings ) { $settings[ 'options_burger_' . $name ] = $value; };
foreach ( [ 'comments_disabled', 'login_enabled', 'soon_enabled' ] as $name ) $set( $name, '0' );
check_control( ! burger_comments_disabled() && ! burger_soon_blocks_request(), 'Los switches apagados deben preservar el sitio.' );

$fields = acf_get_fields( 'group_66ec98973649f' );
$tabs = array_column( array_filter( $fields, static fn( $f ) => 'tab' === $f['type'] ), 'label' );
foreach ( [ 'Header', 'Estructura fija', 'Comentarios', 'Custom Login', 'Próximamente' ] as $label ) check_control( in_array( $label, $tabs, true ), 'Falta tab: ' . $label );
$keys = array_column( $fields, 'key' );
check_control( count( $keys ) === count( array_unique( $keys ) ), 'Hay keys repetidas.' );

// Intercepta el guardado de ACF antes de cualquier escritura; verifica valor y referencia.
$saved = [];
add_filter( 'acf/pre_update_metadata', static function ( $pre, $id, $name, $value, $hidden ) use ( &$saved ) {
    $saved[ ( $hidden ? '_' : '' ) . $name ] = $value;
    return true;
}, 10, 5 );
foreach ( [ 'comments_disabled' => 1, 'login_logo' => 123, 'soon_title' => 'Próximamente: comunidad', 'soon_allowed_roles' => [ 'editor' ] ] as $name => $value ) {
    $key = 'field_burger_' . $name;
    check_control( (bool) acf_get_field( $key ), 'ACF no resuelve ' . $key );
    update_field( $key, $value, 'option' );
    check_control( ( $saved[ '_burger_' . $name ] ?? '' ) === $key, 'ACF no guarda referencia de ' . $name );
    check_control( ( $saved[ 'burger_' . $name ] ?? null ) == $value, 'ACF no guarda el valor de ' . $name );
}

// Regresión del formulario real: update_field() directo no detecta inputs sin acf[].
$control_keys = array_column( burger_site_control_fields(), 'key' );
$render_fields = array_values( array_filter( $fields, static fn( $f ) => in_array( $f['key'], $control_keys, true ) ) );
ob_start();
acf_render_fields( $render_fields, 'options' );
$form_html = ob_get_clean();
$dom = new DOMDocument();
@$dom->loadHTML( '<?xml encoding="UTF-8">' . $form_html );
$xpath = new DOMXPath( $dom );
foreach ( $xpath->query( '//input[@name]|//textarea[@name]|//select[@name]' ) as $input ) {
    $name = $input->getAttribute( 'name' );
    check_control( str_starts_with( $name, 'acf[' ), 'Input fuera del payload ACF: ' . $name );
}
foreach ( [ 'comments_disabled', 'login_enabled', 'soon_enabled' ] as $name ) {
    $input = $xpath->query( '//input[@type="checkbox" and @name="acf[field_burger_' . $name . ']"]' )->item( 0 );
    check_control( (bool) $input, 'Falta el interruptor en el formulario: ' . $name );
    foreach ( [ '1', '0' ] as $value ) {
        // Reproduce el envío normal del navegador y el mismo guardado usado por ACF.
        parse_str( urlencode( $input->getAttribute( 'name' ) ) . '=' . $value, $posted );
        acf_update_values( $posted['acf'] ?? [], 'options' );
        check_control( (string) ( $saved[ 'burger_' . $name ] ?? '' ) === $value, 'El formulario no guarda ' . $name . '=' . $value );
        check_control( ( $saved[ '_burger_' . $name ] ?? '' ) === 'field_burger_' . $name, 'El formulario pierde la referencia de ' . $name );
    }
}
check_control( apply_filters( 'comments_open', true, 0 ), 'Apagado debe preservar comentarios abiertos.' );
$set( 'comments_disabled', '1' );
check_control( false === apply_filters( 'comments_open', true, 0 ), 'Comentarios siguen abiertos.' );
check_control( false === apply_filters( 'pings_open', true, 0 ), 'Pingbacks siguen abiertos.' );
check_control( [] === apply_filters( 'comments_array', [ (object) [ 'comment_ID' => 1 ] ], 0 ), 'Comentarios visibles.' );
check_control( 0 === get_comments( [ 'count' => true ] ), 'Conteo visible.' );
check_control( [] === get_comments(), 'Consulta de comentarios visible.' );
check_control( 0 === wp_count_comments()->approved, 'Conteo del dashboard visible.' );
$methods = apply_filters( 'xmlrpc_methods', [ 'pingback.ping' => 'x', 'wp.newComment' => 'x', 'wp.getComments' => 'x', 'wp.getPosts' => 'x' ] );
check_control( ! isset( $methods['pingback.ping'], $methods['wp.newComment'], $methods['wp.getComments'] ) && isset( $methods['wp.getPosts'] ), 'XML-RPC incorrecto.' );
$request = new WP_REST_Request( 'POST', '/wp/v2/comments' );
$result = apply_filters( 'rest_pre_dispatch', null, rest_get_server(), $request );
check_control( is_wp_error( $result ) && 403 === $result->get_error_data()['status'], 'REST permite comentarios.' );
$set( 'comments_disabled', '0' );
check_control( apply_filters( 'comments_open', true, 0 ), 'No se restauran comentarios al apagar.' );

wp_set_current_user( 0 );
$set( 'soon_enabled', '1' );
check_control( burger_soon_blocks_request(), 'Visitante puede saltear Próximamente.' );
$_GET['burger_soon_preview'] = '1';
check_control( ! burger_soon_is_preview() && burger_soon_blocks_request(), 'Preview permite bypass anónimo.' );
$result = apply_filters( 'rest_pre_dispatch', null, rest_get_server(), new WP_REST_Request( 'GET', '/wp/v2/posts' ) );
check_control( is_wp_error( $result ) && 503 === $result->get_error_data()['status'], 'REST expone el sitio cerrado.' );
$fake = new WP_User();
$fake->ID = PHP_INT_MAX;
$fake->roles = [ 'administrator' ];
$fake->allcaps = [ 'manage_options' => true, 'read' => true ];
$GLOBALS['current_user'] = $fake;
check_control( burger_soon_can_bypass() && ! burger_soon_blocks_request() && burger_soon_is_preview(), 'Administrador sin acceso.' );
$fake->roles = [ 'editor' ];
$fake->allcaps = [ 'read' => true ];
$set( 'soon_allowed_roles', [] );
check_control( burger_soon_blocks_request(), 'Rol no permitido tiene acceso.' );
$set( 'soon_allowed_roles', [ 'editor' ] );
check_control( burger_soon_can_bypass() && ! burger_soon_is_preview(), 'Rol permitido o preview incorrecto.' );
$set( 'soon_enabled', '0' );
unset( $_GET['burger_soon_preview'] );

$set( 'login_enabled', '1' );
$set( 'login_message', '<script>alert(1)</script>' );
$set( 'login_button_color', 'red;}body{display:none' );
check_control( '#4b8dff' === burger_site_color( 'login_button_color', '#4b8dff' ), 'CSS sin validar.' );
check_control( ! str_contains( burger_site_css_url( 'https://example.org/a</style>.png' ), '</style>' ), 'URL puede cerrar style.' );
check_control( str_contains( apply_filters( 'login_message', 'Original' ), '&lt;script&gt;' ), 'Mensaje de login no escapado.' );
do_action( 'login_enqueue_scripts' );
check_control( wp_style_is( 'burger-custom-login', 'enqueued' ), 'CSS login no encolado.' );
check_control( ! empty( wp_styles()->get_data( 'burger-custom-login', 'after' ) ), 'Falta CSS inline del login.' );
$set( 'soon_title', '<script>bad</script>' );
$set( 'soon_button_url', 'javascript:alert(1)' );
$set( 'soon_button_label', 'Botón' );
ob_start();
$preview = false;
require dirname( __DIR__ ) . '/inc/site-coming-soon-template.php';
$html = ob_get_clean();
check_control( str_contains( $html, '&lt;script&gt;bad&lt;/script&gt;' ) && ! str_contains( $html, 'javascript:' ), 'Próximamente sin escape.' );
echo "OK: {$checks} comprobaciones; sin escrituras de prueba en la base.\n";
