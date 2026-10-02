<?php
/** Render simulado de eventos sin escribir registros en la base. */
if ( PHP_SAPI !== 'cli' ) exit;
require dirname( __DIR__, 4 ) . '/wp-load.php';
$GLOBALS['wp_query']->queried_object = new WP_Post( (object) [ 'ID' => 987654321, 'post_type' => 'evento', 'post_title' => 'Evento de prueba', 'post_content' => '', 'post_status' => 'publish' ] );
$GLOBALS['post'] = $GLOBALS['wp_query']->queried_object;
$values = [];
add_filter( 'acf/pre_load_value', static function ( $pre, $id, $field ) use ( &$values ) {
    if ( 987654321 === (int) $id ) return $values[ $field['name'] ] ?? false;
    if ( 'modo_de_edicion_evento' === $field['name'] ) return 'bloque-fijo';
    return $pre;
}, 10, 3 );
add_filter( 'acf/pre_format_value', static function ( $pre, $value, $id, $field ) use ( &$values ) {
    if ( 987654321 === (int) $id ) return $values[ $field['name'] ] ?? false;
    return $pre;
}, 10, 4 );
$render = static function ( $preset ) {
    acf_get_store( 'values' )->reset();
    $block = [ 'id' => 'block_event_source_test', 'name' => 'acf/single-agenda', 'data' => [ 'preset' => $preset, 'logo_evento' => 'https://example.org/plantilla.jpg', 'texto_evento' => 'TEXTO_PLANTILLA' ] ];
    ob_start();
    include BURGER_THEME_PATH . '/blocks/single-agenda/content.php';
    return ob_get_clean();
};
foreach ( [ 0, 1 ] as $preset ) {
    $values = [ 'logo_evento' => 'https://example.org/evento.jpg', 'texto_evento' => 'TEXTO_EVENTO', 'botones_evento' => [ [ 'enlace' => [ 'url' => 'https://example.org/evento-boton', 'title' => 'BOTON_EVENTO', 'target' => '' ], 'estilo' => 'btn-linea' ] ] ];
    $html = $render( $preset );
    foreach ( [ 'https://example.org/evento.jpg', 'TEXTO_EVENTO', 'https://example.org/evento-boton' ] as $expected ) {
        if ( ! str_contains( $html, $expected ) ) throw new RuntimeException( 'Falta valor del evento: ' . $expected );
    }
    if ( str_contains( $html, 'plantilla.jpg' ) || str_contains( $html, 'TEXTO_PLANTILLA' ) ) throw new RuntimeException( 'Prevalece plantilla.' );
    $values = [ 'logo_evento' => '', 'texto_evento' => '', 'botones_evento' => [] ];
    $html = $render( $preset );
    foreach ( [ 'evento.jpg', 'evento-boton', 'TEXTO_EVENTO', 'plantilla.jpg', 'TEXTO_PLANTILLA' ] as $unexpected ) {
        if ( str_contains( $html, $unexpected ) ) throw new RuntimeException( 'No respeta campo borrado: ' . $unexpected );
    }
}
echo "OK: imagen, botones y texto toman valores del evento y respetan borrado, con y sin preset.\n";
