<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function burger_editor_type_settings() {
    return [
        'post' => [
            'mode_field'   => 'modo_de_edicion_post',
            'mode_key'     => 'field_6a85bada309b9',
            'blocks_field' => 'campos_disponibles_post',
            'blocks_key'   => 'field_69b5b34bb03ed',
            'default_mode' => 'contenido',
        ],
        'evento' => [
            'mode_field'   => 'modo_de_edicion_evento',
            'mode_key'     => 'field_6a85bb32309ba',
            'blocks_field' => 'campos_disponibles_evento',
            'blocks_key'   => 'field_6a6b662e87856',
            'default_mode' => 'bloque-fijo',
        ],
    ];
}

function burger_editor_mode( $post_type ) {
    $settings = burger_editor_type_settings()[ $post_type ] ?? null;
    if ( ! $settings || ! function_exists( 'get_field' ) ) return 'contenido';
    $mode = get_field( $settings['mode_field'], 'option' );
    return in_array( $mode, [ 'bloque-fijo', 'contenido' ], true ) ? $mode : $settings['default_mode'];
}

function burger_editor_configured_slugs( $post_type ) {
    $settings = burger_editor_type_settings()[ $post_type ] ?? null;
    if ( ! $settings || ! function_exists( 'get_field' ) ) return [];

    $rows = get_field( $settings['blocks_field'], 'option' );
    $slugs = [];
    foreach ( (array) $rows as $row ) {
        $slug = sanitize_key( is_array( $row ) ? ( $row['title'] ?? '' ) : $row );
        if ( $slug && ! in_array( $slug, $slugs, true ) ) $slugs[] = $slug;
    }
    return $slugs;
}

function burger_rigid_post_types() {
    $rigid = [];
    foreach ( burger_editor_type_settings() as $post_type => $settings ) {
        if ( 'bloque-fijo' !== burger_editor_mode( $post_type ) ) continue;
        $slugs = burger_editor_configured_slugs( $post_type );
        if ( ! empty( $slugs[0] ) ) $rigid[ $post_type ] = $slugs[0];
    }
    return $rigid;
}

// Un tipo rígido muestra el grupo ACF del bloque elegido, no Gutenberg.
add_action( 'init', function () {
    foreach ( array_keys( burger_rigid_post_types() ) as $post_type ) {
        if ( post_type_exists( $post_type ) ) remove_post_type_support( $post_type, 'editor' );
    }
}, 100 );

// En modo contenido, el insertador ofrece bloques editoriales básicos y los ACF configurados.
add_filter( 'allowed_block_types_all', function ( $allowed, $editor_context ) {
    $post = $editor_context->post ?? null;
    if ( ! $post || ! isset( burger_editor_type_settings()[ $post->post_type ] ) ) return $allowed;
    if ( 'contenido' !== burger_editor_mode( $post->post_type ) ) return [];

    $editorial = [
        'core/paragraph', 'core/heading', 'core/list', 'core/list-item',
        'core/image', 'core/quote', 'core/buttons', 'core/button',
        'core/separator', 'core/spacer', 'core/embed',
    ];
    $acf_blocks = array_map(
        static fn( $slug ) => 'acf/' . $slug,
        burger_editor_configured_slugs( $post->post_type )
    );
    return array_values( array_unique( array_merge( $editorial, $acf_blocks ) ) );
}, 20, 2 );

// El grupo del bloque fijo se comporta como grupo de campos del post type rígido.
add_filter( 'acf/load_field_group', function ( $group ) {
    $title_slug = sanitize_title( preg_replace( '/^Block\s+/i', '', $group['title'] ?? '' ) );
    if ( ! $title_slug ) return $group;

    $field_group_locations = burger_rigid_post_types();

    if ( 'contenido' === burger_editor_mode( 'evento' ) ) {
        $field_group_locations['evento'] = 'single-agenda';
    }

    foreach ( $field_group_locations as $post_type => $block_slug ) {
        if ( $title_slug !== sanitize_title( $block_slug ) ) continue;
        $has_location = false;
        foreach ( (array) ( $group['location'] ?? [] ) as $rules ) {
            foreach ( (array) $rules as $rule ) {
                if ( ( $rule['param'] ?? '' ) === 'post_type' && ( $rule['operator'] ?? '' ) === '==' && ( $rule['value'] ?? '' ) === $post_type ) {
                    $has_location = true;
                }
            }
        }
        if ( ! $has_location ) {
            $group['location'][] = [ [
                'param' => 'post_type', 'operator' => '==', 'value' => $post_type,
            ] ];
        }
    }
    return $group;
} );

function burger_available_editor_block_choices() {
    $choices = [];
    $excluded = function_exists( 'burger_fixed_structure_choices' )
        ? array_keys( burger_fixed_structure_choices() )
        : [ 'header', 'footer-contenido', 'footer' ];

    foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $block ) {
        if ( ! str_starts_with( $name, 'acf/' ) ) continue;
        $slug = substr( $name, 4 );
        if ( in_array( $slug, $excluded, true ) ) continue;
        $label = ! empty( $block->title ) ? $block->title : ucwords( str_replace( '-', ' ', $slug ) );
        $choices[ $slug ] = $label;
    }
    natcasesort( $choices );
    return $choices;
}

function burger_prepare_available_block_select( $field ) {
    $field['label'] = 'Bloque';
    $field['type'] = 'select';
    $field['choices'] = burger_available_editor_block_choices();
    $field['ui'] = 1;
    $field['ajax'] = 0;
    $field['allow_null'] = 0;
    $field['multiple'] = 0;
    $field['required'] = 1;
    $field['return_format'] = 'value';
    return $field;
}
add_filter( 'acf/load_field/key=field_69b5b35ab03ee', 'burger_prepare_available_block_select' );
add_filter( 'acf/load_field/key=field_6a6b662e87857', 'burger_prepare_available_block_select' );
add_filter( 'acf/prepare_field/key=field_69b5b35ab03ee', 'burger_prepare_available_block_select' );
add_filter( 'acf/prepare_field/key=field_6a6b662e87857', 'burger_prepare_available_block_select' );

function burger_configured_blocks_field( $field ) {
    foreach ( burger_editor_type_settings() as $post_type => $settings ) {
        if ( ( $field['key'] ?? '' ) !== $settings['blocks_key'] ) continue;
        $fixed = 'bloque-fijo' === burger_editor_mode( $post_type );
        $field['min'] = $fixed ? 1 : 0;
        $field['max'] = $fixed ? 1 : 0;
        $field['instructions'] = $fixed
            ? 'Elegí exactamente un bloque. Sus campos serán el único editor del tipo de contenido.'
            : 'Estos bloques estarán disponibles en el insertador, junto con los bloques editoriales básicos.';
    }
    return $field;
}
add_filter( 'acf/load_field/key=field_69b5b34bb03ed', 'burger_configured_blocks_field' );
add_filter( 'acf/load_field/key=field_6a6b662e87856', 'burger_configured_blocks_field' );
add_filter( 'acf/prepare_field/key=field_69b5b34bb03ed', 'burger_configured_blocks_field' );
add_filter( 'acf/prepare_field/key=field_6a6b662e87856', 'burger_configured_blocks_field' );

function burger_validate_configured_blocks( $valid, $value, $field, $input ) {
    if ( true !== $valid ) return $valid;

    foreach ( burger_editor_type_settings() as $post_type => $settings ) {
        if ( ( $field['key'] ?? '' ) !== $settings['blocks_key'] ) continue;
        $posted_mode = $_POST['acf'][ $settings['mode_key'] ] ?? burger_editor_mode( $post_type );
        if ( 'bloque-fijo' === $posted_mode && 1 !== count( (array) $value ) ) {
            return 'En modo Bloque fijo tenés que elegir exactamente un bloque.';
        }
    }
    return $valid;
}
add_filter( 'acf/validate_value/key=field_69b5b34bb03ed', 'burger_validate_configured_blocks', 20, 4 );
add_filter( 'acf/validate_value/key=field_6a6b662e87856', 'burger_validate_configured_blocks', 20, 4 );

function burger_hide_legacy_event_text_field( $field ) {
    if ( 'contenido' !== burger_editor_mode( 'evento' ) ) return $field;

    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( $screen && 'evento' === $screen->post_type ) return false;

    return $field;
}
add_filter( 'acf/prepare_field/name=texto_evento', 'burger_hide_legacy_event_text_field' );
