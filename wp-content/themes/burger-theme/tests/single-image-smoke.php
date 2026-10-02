<?php
/** Render real con datos simulados, sin escribir entradas ni metadatos. */
if ( PHP_SAPI !== 'cli' ) exit;
require dirname( __DIR__, 4 ) . '/wp-load.php';
$sample = get_posts( [ 'post_type' => 'post', 'posts_per_page' => 1 ] )[0] ?? null;
if ( ! $sample ) throw new RuntimeException( 'Falta entrada para el contexto de lectura.' );
$GLOBALS['post'] = clone $sample;
$GLOBALS['post']->ID = 987654321;
$GLOBALS['wp_query']->queried_object = $GLOBALS['post'];
$has_image = true;
$image = 'https://example.org/novedad.jpg';
add_filter( 'get_post_metadata', static function ( $pre, $id, $key ) use ( &$has_image ) {
    if ( 987654321 === (int) $id && in_array( $key, [ 'imagen_servicio', 'boton', 'titulo_compartir', 'subtitulo_compartir' ], true ) ) return $has_image ? 'saved' : [];
    return $pre;
}, 10, 3 );
add_filter( 'acf/pre_load_value', static function ( $pre, $id, $field ) use ( &$image ) {
    if ( 987654321 === (int) $id && in_array( $field['name'], [ 'imagen_servicio', 'boton', 'titulo_compartir', 'subtitulo_compartir' ], true ) ) return 'imagen_servicio' === $field['name'] ? $image : ( $image === '' ? '' : ( 'boton' === $field['name'] ? [ 'url' => 'https://example.org/boton-novedad', 'title' => 'Botón de la Novedad', 'target' => '' ] : 'Texto propio ' . $field['name'] ) );
    return $pre;
}, 10, 3 );
add_filter( 'acf/pre_format_value', static function ( $pre, $value, $id, $field ) use ( &$image ) {
    if ( 987654321 === (int) $id && in_array( $field['name'], [ 'imagen_servicio', 'boton', 'titulo_compartir', 'subtitulo_compartir' ], true ) ) return 'imagen_servicio' === $field['name'] ? $image : ( $image === '' ? '' : ( 'boton' === $field['name'] ? [ 'url' => 'https://example.org/boton-novedad', 'title' => 'Botón de la Novedad', 'target' => '' ] : 'Texto propio ' . $field['name'] ) );
    return $pre;
}, 10, 4 );
$render = static function ( $preset ) {
    acf_get_store( 'values' )->reset();
    $block = [ 'id' => 'block_single_image_test', 'name' => 'acf/single', 'data' => [ 'preset' => $preset, 'imagen_servicio' => 'old' ], 'burger_content_fields' => [ 'imagen_servicio' => 'https://example.org/template.jpg' ] ];
    ob_start();
    include BURGER_THEME_PATH . '/blocks/single/content.php';
    return ob_get_clean();
};
foreach ( [ 0, 1 ] as $preset ) {
    $has_image = true;
    $image = 'https://example.org/novedad.jpg';
    $html = $render( $preset );
    if ( ! str_contains( $html, 'src="https://example.org/novedad.jpg"' ) || str_contains( $html, 'src="https://example.org/template.jpg"' ) ) throw new RuntimeException( 'No prevalece imagen de entrada.' );
    foreach ( [ 'https://example.org/boton-novedad', 'Texto propio titulo_compartir', 'Texto propio subtitulo_compartir' ] as $expected ) {
        if ( ! str_contains( $html, $expected ) ) throw new RuntimeException( 'No prevalece campo de entrada: ' . $expected );
    }
    $image = '';
    $empty_html = $render( $preset );
    if ( str_contains( $empty_html, 'boton-novedad' ) || str_contains( $empty_html, 'Texto propio' ) ) throw new RuntimeException( 'No respeta campos borrados.' );
    if ( str_contains( $render( $preset ), 'src="https://example.org/template.jpg"' ) ) throw new RuntimeException( 'No respeta eliminación explícita.' );
    $has_image = false;
    if ( ! str_contains( $render( $preset ), 'src="https://example.org/template.jpg"' ) ) throw new RuntimeException( 'No conserva fallback de plantilla.' );
}
echo "OK: imagen, botón y compartir de la entrada; vacíos y fallback con y sin preset.\n";

$button_rows = [
    [ 'enlace' => [ 'url' => 'https://example.org/uno', 'title' => 'Uno', 'target' => '' ], 'estilo' => 'btn-primario' ],
    [ 'enlace' => [ 'url' => 'https://example.org/dos', 'title' => 'Dos', 'target' => '' ], 'estilo' => 'btn-linea' ],
];
add_filter( 'get_post_metadata', static function ( $pre, $id, $key ) {
    return 987654321 === (int) $id && 'botones_single' === $key ? 'saved' : $pre;
}, 20, 3 );
add_filter( 'acf/pre_load_value', static function ( $pre, $id, $field ) use ( &$button_rows ) {
    return 987654321 === (int) $id && 'botones_single' === $field['name'] ? $button_rows : $pre;
}, 20, 3 );
add_filter( 'acf/pre_format_value', static function ( $pre, $value, $id, $field ) use ( &$button_rows ) {
    return 987654321 === (int) $id && 'botones_single' === $field['name'] ? $button_rows : $pre;
}, 20, 4 );
foreach ( [ 0, 1 ] as $preset ) {
    $has_image = true;
    $image = 'https://example.org/novedad.jpg';
    $html = $render( $preset );
    foreach ( [ 'https://example.org/uno', 'https://example.org/dos', 'btn-primario', 'btn-linea' ] as $expected ) {
        if ( ! str_contains( $html, $expected ) ) throw new RuntimeException( 'Falta botón o estilo: ' . $expected );
    }
    if ( str_contains( $html, 'boton-novedad' ) ) throw new RuntimeException( 'Se duplicó el botón anterior.' );
}
$button_rows = [];
if ( str_contains( $render( 1 ), 'boton-novedad' ) ) throw new RuntimeException( 'Lista vacía recupera botón anterior.' );
echo "OK: múltiples botones, estilos y eliminación explícita.\n";
