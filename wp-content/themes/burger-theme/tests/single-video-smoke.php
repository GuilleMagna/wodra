<?php
/** Contexto simulado: no modifica entradas ni metadatos existentes. */
if ( PHP_SAPI !== 'cli' ) exit;
require dirname( __DIR__, 4 ) . '/wp-load.php';
function verify_single_video( $condition, $message ) {
    if ( ! $condition ) throw new RuntimeException( $message );
    echo "OK: $message\n";
}
$fields = acf_get_fields( 'group_burger_single' );
$keys = array_column( $fields, 'key' );
verify_single_video( count( $keys ) === count( array_unique( $keys ) ), 'Campos sin duplicados' );
foreach ( burger_agenda_media_fields( 'single', [ 'video' ] ) as $field ) {
    verify_single_video( in_array( $field['key'], $keys, true ) && acf_get_field( $field['key'] ), 'Campo disponible: ' . $field['key'] );
}
ob_start();
acf_render_fields( array_values( array_filter( $fields, static fn( $f ) => str_starts_with( $f['key'], 'field_burger_single_video_' ) ) ), 'post_987654321', 'div', 'label' );
$html = ob_get_clean();
verify_single_video( str_contains( $html, 'name="acf[field_burger_single_video_id_video_youtube]"' ), 'Input incluido en el formulario ACF' );
$saved = [];
add_filter( 'acf/pre_update_metadata', static function ( $pre, $id, $name, $value, $hidden ) use ( &$saved ) {
    $saved[ ( $hidden ? '_' : '' ) . $name ] = $value;
    return true;
}, 10, 5 );
update_field( 'field_burger_single_video_id_video_youtube', 'dQw4w9WgXcQ', 987654321 );
verify_single_video( ( $saved['single_video_id_video_youtube'] ?? '' ) === 'dQw4w9WgXcQ' && ( $saved['_single_video_id_video_youtube'] ?? '' ) === 'field_burger_single_video_id_video_youtube', 'Guardado simulado conserva valor y referencia' );
$values = [];
add_filter( 'acf/pre_load_value', static function ( $pre, $id, $field ) use ( &$values ) {
    return str_starts_with( $field['name'], 'single_video_' ) ? ( $values[ $field['name'] ] ?? false ) : $pre;
}, 10, 3 );
$render = static function () {
    acf_get_store( 'values' )->reset();
    ob_start();
    burger_render_agenda_media( 987654321, [ 'id' => 'block_single_test' ], 'single', [ 'video' ] );
    return ob_get_clean();
};
verify_single_video( '' === $render(), 'Entradas anteriores mantienen video oculto' );
$values = [ 'single_video_visible' => 1, 'single_video_id_video_youtube' => 'dQw4w9WgXcQ' ];
verify_single_video( str_contains( $render(), 'youtube.com/embed/dQw4w9WgXcQ' ), 'Video válido renderizado' );
$values['single_video_id_video_youtube'] = 'invalid';
verify_single_video( '' === $render(), 'ID inválido no genera video' );
$values['single_video_id_video_youtube'] = 'dQw4w9WgXcQ';
$values['single_video_visible'] = 0;
verify_single_video( '' === $render(), 'Switch apagado oculta video' );
burger_agenda_media_assets( [ 'galeria', 'video' ] );
verify_single_video( wp_style_is( 'burger-block-video', 'enqueued' ), 'Estilos de video encolados' );
