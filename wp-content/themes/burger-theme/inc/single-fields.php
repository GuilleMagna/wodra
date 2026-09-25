<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ACF ya se inicializó antes de cargar el theme en esta instalación.
if ( function_exists( 'acf_add_local_field_group' ) ) {
    $single_fields = [
        [ 'key' => 'field_burger_single_tab', 'label' => 'Configuración', 'name' => '', 'type' => 'tab', 'placement' => 'left' ],
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
