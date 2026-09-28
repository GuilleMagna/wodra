<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ACF ya se inicializó antes de cargar el theme en esta instalación.
// Tras importar el JSON, SCF administra la definición guardada con esta misma key.
if ( function_exists( 'acf_add_local_field_group' ) && ! acf_get_raw_field_group( 'group_burger_single' ) ) {
    $single_fields = [
        [ 'key' => 'field_burger_single_tab', 'label' => 'Configuración', 'name' => '', 'type' => 'tab', 'placement' => 'left' ],
        [ 'key' => 'field_burger_single_titulo', 'label' => 'Título', 'name' => 'titulo_single', 'type' => 'text', 'wrapper' => [ 'width' => '40' ] ],
        [ 'key' => 'field_burger_single_subtitulo', 'label' => 'Subtítulo', 'name' => 'subtitulo_single', 'type' => 'text', 'wrapper' => [ 'width' => '40' ] ],
        [ 'key' => 'field_burger_single_encabezado', 'label' => 'Encabezado', 'name' => 'encabezado_single', 'type' => 'select', 'choices' => [ 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3' ], 'default_value' => 'h1', 'return_format' => 'value', 'wrapper' => [ 'width' => '20' ] ],
        [ 'key' => 'field_burger_single_contenido', 'label' => 'Contenido', 'name' => 'contenido_single', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ],
        [ 'key' => 'field_burger_single_imagen', 'label' => 'Imagen servicio', 'name' => 'imagen_servicio', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium', 'library' => 'all', 'wrapper' => [ 'width' => '50' ] ],
        [ 'key' => 'field_burger_single_boton', 'label' => 'Botón', 'name' => 'boton', 'type' => 'link', 'return_format' => 'array', 'wrapper' => [ 'width' => '50' ] ],
        [ 'key' => 'field_burger_single_titulo_compartir', 'label' => 'Título Compartir', 'name' => 'titulo_compartir', 'type' => 'text', 'default_value' => '¡Compartí ahora esta novedad', 'wrapper' => [ 'width' => '50' ] ],
        [ 'key' => 'field_burger_single_subtitulo_compartir', 'label' => 'Subtítulo Compartir', 'name' => 'subtitulo_compartir', 'type' => 'text', 'default_value' => 'con colegas!', 'wrapper' => [ 'width' => '50' ] ],
        [ 'key' => 'field_burger_single_preset', 'label' => 'Configuración por defecto', 'name' => 'preset', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'wrapper' => [ 'width' => '30' ] ],
    ];
    foreach ( $single_fields as &$single_field ) {
        if ( in_array( $single_field['name'], [ 'imagen_servicio', 'boton', 'titulo_compartir', 'subtitulo_compartir' ], true ) ) {
            $single_field['conditional_logic'] = [ [ [ 'field' => 'field_burger_single_preset', 'operator' => '!=', 'value' => '1' ] ] ];
        }
    }
    unset( $single_field );
    $single_fields = array_merge( $single_fields, burger_agenda_media_fields( 'single', [ 'galeria' ] ) );
    acf_add_local_field_group( [
        'key' => 'group_burger_single', 'title' => 'Block Single', 'fields' => $single_fields,
        'location' => [
            [ [ 'param' => 'block', 'operator' => '==', 'value' => 'acf/single' ] ],
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ] ],
            [ [ 'param' => 'burger_preset_group', 'operator' => '==', 'value' => 'single' ] ],
        ],
    ] );
    unset( $single_fields );
}

// Un único formulario aunque PRD conserve un grupo antiguo con otra key.
add_filter( 'acf/get_field_groups', static function ( $groups ) {
    if ( ! in_array( 'group_burger_single', array_column( $groups, 'key' ), true ) ) return $groups;
    return array_values( array_filter( $groups, static function ( $group ) {
        return 'block-single' !== sanitize_title( $group['title'] ?? '' ) || 'group_burger_single' === $group['key'];
    } ) );
}, 40 );

// Precarga el texto previo sin migraciones ni escrituras. Un valor guardado vacío se respeta.
function burger_single_load_legacy_content( $value, $post_id, $field ) {
    if ( null !== $value || ! is_numeric( $post_id ) || get_post_type( (int) $post_id ) !== 'post' ) return $value;
    $property = 'titulo_single' === $field['name'] ? 'post_title' : 'post_content';
    return get_post_field( $property, (int) $post_id, 'raw' );
}
add_filter( 'acf/load_value/name=titulo_single', 'burger_single_load_legacy_content', 10, 3 );
add_filter( 'acf/load_value/name=contenido_single', 'burger_single_load_legacy_content', 10, 3 );