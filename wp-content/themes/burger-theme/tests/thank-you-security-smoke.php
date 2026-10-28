<?php
/** Render aislado: sin base de datos, correos ni cambios de contenido. */
if ( PHP_SAPI !== 'cli' ) exit;
function get_block_content_fields( $block, $fields ) {
    return [ 'titulo_empresa' => 'Gracias [nombre]', 'encabezado' => 'h1' ] + array_fill_keys( $fields, '' );
}
function get_block_design( $block ) {
    return array_fill_keys( [ 'section_margin', 'section_padding', 'border_radius', 'color_fondo', 'imagen_fondo', 'color_secundario', 'color_primario', 'class_container', 'col_container_class', 'col_md_container_class', 'col_lg_container_class', 'text_align_class' ], '' );
}
function get_block_wrapper_attributes( $attributes ) { return 'class="test"'; }
foreach ( [ '<script>ATTACK</script>', [ 'ATTACK' ] ] as $attack ) {
    $_GET['nombre'] = $attack;
    $block = [ 'id' => 'test-block' ];
    ob_start();
    include dirname( __DIR__ ) . '/blocks/descripcion-empresa/content.php';
    $html = ob_get_clean();
    if ( str_contains( $html, 'ATTACK' ) || str_contains( $html, 'Cliente' ) || ! str_contains( $html, 'data-gracias-nombre' ) ) {
        throw new RuntimeException( 'El título usa datos de la URL o perdió el marcador' );
    }
}
echo "OK: nombre de la URL ignorado, incluso con parámetros de tipo array.\n";
