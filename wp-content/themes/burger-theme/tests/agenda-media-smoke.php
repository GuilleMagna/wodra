<?php
/** PHP CLI: datos simulados, sin guardar ni borrar registros de WordPress. */
if ( PHP_SAPI !== 'cli' ) exit;
$GLOBALS['wp_filter']['option_active_plugins'][1][] = [
    'function' => static fn( $plugins ) => array_values( array_filter( $plugins, static fn( $plugin ) => str_contains( $plugin, 'advanced-custom-fields' ) || str_contains( $plugin, 'secure-custom-fields' ) ) ),
    'accepted_args' => 1,
];
require dirname( __DIR__, 4 ) . '/wp-load.php';

$checks = 0;
function check_agenda_media( $condition, $message ) {
    global $checks;
    if ( ! $condition ) throw new RuntimeException( $message );
    $checks++;
}
$fields = acf_get_fields( 'group_6a69018b1669c' );
$keys = array_column( $fields, 'key' );
check_agenda_media( count( $keys ) === count( array_unique( $keys ) ), 'Keys duplicadas en Single Agenda.' );
foreach ( burger_agenda_media_fields() as $field ) {
    check_agenda_media( in_array( $field['key'], $keys, true ), 'Falta campo: ' . $field['key'] );
    check_agenda_media( (bool) acf_get_field( $field['key'] ), 'Key no registrada: ' . $field['key'] );
}
ob_start();
acf_render_fields( array_values( array_filter( $fields, static fn( $f ) => str_starts_with( $f['key'], 'field_burger_agenda_' ) ) ), 'post_987654321', 'div', 'label' );
$form = ob_get_clean();
check_agenda_media( str_contains( $form, 'name="acf[field_burger_agenda_galeria_visible]"' ), 'Switch fuera del formulario ACF.' );
check_agenda_media( str_contains( $form, 'name="acf[field_burger_agenda_video_id_video_youtube]"' ), 'Video fuera del formulario ACF.' );

$saved = [];
add_filter( 'acf/pre_update_metadata', static function ( $pre, $id, $name, $value, $hidden ) use ( &$saved ) {
    $saved[ ( $hidden ? '_' : '' ) . $name ] = $value;
    return true;
}, 10, 5 );
foreach ( [ 'galeria_visible' => 1, 'galeria_galeria' => [ 12, 34 ], 'video_id_video_youtube' => 'dQw4w9WgXcQ' ] as $name => $value ) {
    update_field( 'field_burger_agenda_' . $name, $value, 987654321 );
    check_agenda_media( ( $saved[ '_agenda_' . $name ] ?? '' ) === 'field_burger_agenda_' . $name, 'Referencia de guardado incorrecta.' );
    check_agenda_media( ( $saved[ 'agenda_' . $name ] ?? null ) == $value, 'Valor de guardado incorrecto.' );
}

$values = [];
add_filter( 'acf/pre_load_value', static function ( $pre, $post_id, $field ) use ( &$values ) {
    if ( str_starts_with( $field['name'], 'agenda_' ) ) return $values[ $field['name'] ] ?? false;
    return $pre;
}, 10, 3 );
add_filter( 'acf/pre_format_value', static function ( $pre, $value, $post_id, $field ) use ( &$values ) {
    if ( str_starts_with( $field['name'], 'agenda_' ) ) return $values[ $field['name'] ] ?? false;
    return $pre;
}, 10, 4 );
$render = static function ( $id = 'block_agenda_test' ) {
    acf_get_store( 'values' )->reset();
    ob_start();
    burger_render_agenda_media( 987654321, [ 'id' => $id ] );
    return ob_get_clean();
};
check_agenda_media( '' === $render(), 'Un evento existente muestra secciones nuevas.' );
$values = [ 'agenda_galeria_visible' => 1, 'agenda_video_visible' => 1 ];
check_agenda_media( '' === $render(), 'Se renderizan secciones sin contenido.' );
$values += [
    'agenda_galeria_galeria' => [ 'https://example.org/uno.jpg', 'https://example.org/dos.jpg' ],
    'agenda_video_id_video_youtube' => 'dQw4w9WgXcQ',
];
$html = $render();
check_agenda_media( str_contains( $html, 'uno.jpg' ) && str_contains( $html, 'youtube.com/embed/dQw4w9WgXcQ' ), 'No se renderizan ambos medios.' );
check_agenda_media( str_contains( $html, 'class="block_agenda_test-agenda-galeria galeria' ) || str_contains( $html, 'galeria-block galeria' ), 'Falta clase de estilos de galería.' );
check_agenda_media( str_contains( $html, 'data-bs-target="#carousel-block_agenda_test-agenda-galeria"' ), 'Slider apunta a otra galería.' );
$values['agenda_galeria_visible'] = 0;
check_agenda_media( ! str_contains( $render(), 'uno.jpg' ), 'Galería oculta todavía visible.' );
$values['agenda_galeria_visible'] = 1;
check_agenda_media( str_contains( $render(), 'uno.jpg' ), 'Ocultar perdió imágenes.' );
$values['agenda_galeria_tipo_de_galeria'] = 'carrusel';
$values['agenda_galeria_nav'] = 0;
$values['agenda_galeria_margen'] = 0;
$html = $render();
check_agenda_media( str_contains( $html, '.owlCarousel(' ) && str_contains( $html, 'nav: false' ) && str_contains( $html, 'margin: 0' ), 'Carrusel no respeta valores cero.' );
$values['agenda_video_id_video_youtube'] = '" onload="alert(1)';
check_agenda_media( ! str_contains( $render(), '<iframe' ), 'ID de video inválido renderizado.' );
burger_agenda_media_assets();
check_agenda_media( wp_style_is( 'burger-block-galeria', 'enqueued' ) && wp_script_is( 'burger-block-galeria', 'enqueued' ), 'Faltan assets de galería.' );
echo "OK: $checks verificaciones sin escribir en la base.\n";
