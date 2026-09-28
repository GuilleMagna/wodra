<?php
/** Regresión SCF: comprueba el metabox real sin crear ni modificar entradas. */
if ( PHP_SAPI !== 'cli' ) exit;
define( 'WP_ADMIN', true );
$GLOBALS['wp_filter']['option_active_plugins'][1][] = [ 'function' => static fn( $plugins ) => array_values( array_filter( $plugins, static fn( $plugin ) => str_contains( $plugin, 'advanced-custom-fields' ) || str_contains( $plugin, 'secure-custom-fields' ) ) ), 'accepted_args' => 1 ];
require dirname( __DIR__, 4 ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';
require_once ABSPATH . 'wp-admin/includes/template.php';
$mode = 'bloque-fijo';
add_filter( 'acf/pre_load_value', static function ( $pre, $id, $field ) use ( &$mode ) {
    if ( 'modo_de_edicion_post' === $field['name'] ) return $mode;
    if ( 'campos_disponibles_post' === $field['name'] ) return [ [ 'title' => 'single' ] ];
    return $pre;
}, 10, 3 );
add_filter( 'acf/pre_format_value', static function ( $pre, $value, $id, $field ) use ( &$mode ) {
    if ( 'modo_de_edicion_post' === $field['name'] ) return $mode;
    if ( 'campos_disponibles_post' === $field['name'] ) return [ [ 'title' => 'single' ] ];
    return $pre;
}, 10, 4 );
function verify_rigid_single( $ok, $label ) {
    if ( ! $ok ) throw new RuntimeException( $label );
    echo "OK: $label\n";
}
$post = get_posts( [ 'post_type' => 'post', 'posts_per_page' => 1 ] )[0] ?? null;
verify_rigid_single( (bool) $post, 'Entrada existente disponible para lectura' );
set_current_screen( 'page' );
acf_get_store( 'values' )->reset();
$original = acf_get_field_group( 'group_burger_single' );
$form = acf_get_instance( 'acf_form_post' );
verify_rigid_single( $form->is_block_field_group( $original ), 'Reproduce la clasificación original que ocultaba Single' );
set_current_screen( 'post' );
$groups = acf_get_field_groups( [ 'post_type' => 'post', 'post_id' => $post->ID ] );
$single = array_values( array_filter( $groups, static fn( $g ) => 'group_burger_single' === $g['key'] ) );
verify_rigid_single( count( $single ) === 1 && ! $form->is_block_field_group( $single[0] ), 'Single disponible como metabox fijo' );
$form->add_meta_boxes( 'post', $post );
verify_rigid_single( isset( $GLOBALS['wp_meta_boxes']['post']['normal']['high']['acf-group_burger_single'] ), 'SCF registra el metabox real de Novedades' );
$names = array_column( acf_get_fields( $single[0] ), 'name' );
verify_rigid_single( in_array( 'imagen_servicio', $names, true ) && in_array( 'single_galeria_visible', $names, true ), 'Formulario incluye configuración y galería' );
$mode = 'contenido';
acf_get_store( 'values' )->reset();
$groups = burger_rigid_editor_field_groups( [ $original ] );
verify_rigid_single( $form->is_block_field_group( $groups[0] ), 'Modo contenido conserva ubicación de bloque' );
$mode = 'bloque-fijo';
acf_get_store( 'values' )->reset();
set_current_screen( 'page' );
$groups = burger_rigid_editor_field_groups( [ $original ] );
verify_rigid_single( $form->is_block_field_group( $groups[0] ), 'Editor de páginas conserva ubicación de bloque' );
acf_get_store( 'field-groups' )->reset();
verify_rigid_single( $form->is_block_field_group( acf_get_field_group( 'group_burger_single' ) ), 'Registro original intacto' );
