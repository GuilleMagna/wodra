<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** Campos propios del evento: no dependen de insertar bloques ni de presets. */
function burger_agenda_media_schema() {
    return [
        'galeria' => [
            'titulo_galeria' => [ 'Título', 'text', '' ],
            'subtitulo_galeria' => [ 'Subtítulo', 'text', '' ],
            'encabezado' => [ 'Encabezado', 'select', 'h2', [ 'h2' => 'H2', 'h3' => 'H3' ] ],
            'galeria' => [ 'Imágenes', 'gallery', '' ],
            'tipo_de_galeria' => [ 'Tipo de galería', 'select', 'slider', [ 'slider' => 'Slider', 'carrusel' => 'Carrusel' ] ],
            'items' => [ 'Imágenes en pantallas grandes', 'number', 4 ],
            'items_desktop' => [ 'Imágenes en escritorio', 'number', 3 ],
            'items_tablet' => [ 'Imágenes en tablet', 'number', 2 ],
            'items_mobile' => [ 'Imágenes en celular', 'number', 1 ],
            'autoplay' => [ 'Reproducción automática', 'true_false', 0 ],
            'nav' => [ 'Flechas', 'true_false', 1 ],
            'dots' => [ 'Indicadores', 'true_false', 0 ],
            'loop' => [ 'Repetir carrusel', 'true_false', 1 ],
            'margen' => [ 'Separación entre imágenes (px)', 'number', 10 ],
            'overflow' => [ 'Desbordamiento del contenedor', 'select', 'visible', [ 'visible' => 'Visible', 'hidden' => 'Oculto' ] ],
        ],
        'video' => [
            'titulo_video' => [ 'Título', 'text', '' ],
            'subtitulo_video' => [ 'Subtítulo', 'text', '' ],
            'encabezado' => [ 'Encabezado', 'select', 'h2', [ 'h2' => 'H2', 'h3' => 'H3' ] ],
            'imagen_video_youtube' => [ 'Imagen Video Youtube', 'image', '' ],
            'id_video_youtube' => [ 'ID del video de YouTube', 'text', '' ],
        ],
    ];
}

function burger_agenda_media_fields( $namespace = 'agenda', $sections = [ 'galeria', 'video' ] ) {
    $fields = [];
    foreach ( array_intersect_key( burger_agenda_media_schema(), array_flip( $sections ) ) as $section => $schema ) {
        $prefix = $namespace . '_' . $section . '_';
        $toggle = 'field_burger_' . $prefix . 'visible';
        $fields[] = [ 'key' => 'field_burger_' . $prefix . 'tab', 'label' => ucfirst( $section ), 'name' => '', 'type' => 'tab' ];
        $fields[] = [
            'key' => $toggle, 'label' => 'Mostrar ' . $section, 'name' => $prefix . 'visible',
            'type' => 'true_false', 'ui' => 1, 'default_value' => 0,
            'instructions' => 'Podés ocultar esta sección sin borrar su contenido.',
        ];
        foreach ( $schema as $name => $definition ) {
            [ $label, $type, $default ] = $definition;
            $rules = [ [ 'field' => $toggle, 'operator' => '==', 'value' => '1' ] ];
            if ( 'galeria' === $section && in_array( $name, [ 'items', 'items_desktop', 'items_tablet', 'items_mobile', 'autoplay', 'nav', 'dots', 'loop', 'margen', 'overflow' ], true ) ) {
                $rules[] = [ 'field' => 'field_burger_' . $prefix . 'tipo_de_galeria', 'operator' => '==', 'value' => 'carrusel' ];
            }
            $field = [
                'key' => 'field_burger_' . $prefix . $name, 'name' => $prefix . $name,
                'label' => $label, 'type' => $type, 'default_value' => $default,
                'conditional_logic' => [ $rules ],
            ];
            if ( 'select' === $type ) $field += [ 'choices' => $definition[3], 'return_format' => 'value' ];
            if ( in_array( $type, [ 'gallery', 'image' ], true ) ) $field += [ 'return_format' => 'url', 'preview_size' => 'thumbnail', 'library' => 'all' ];
            if ( 'number' === $type ) $field += [ 'min' => 'margen' === $name ? 0 : 1, 'step' => 1 ];
            if ( 'true_false' === $type ) $field['ui'] = 1;
            if ( 'id_video_youtube' === $name ) $field['instructions'] = 'Ingresá solo el ID, por ejemplo: dQw4w9WgXcQ.';
            $fields[] = $field;
        }
    }
    return $fields;
}

// Mismo patrón que site-controls: registrar keys sin reemplazar el grupo de la BD.
if ( function_exists( 'acf_add_local_fields' ) ) {
    $agenda_fields = burger_agenda_media_fields();
    foreach ( $agenda_fields as &$agenda_field ) $agenda_field['parent'] = 'group_burger_agenda_media_virtual';
    unset( $agenda_field );
    acf_add_local_fields( $agenda_fields );
    unset( $agenda_fields );
}
add_filter( 'acf/load_fields', function ( $fields, $parent ) {
    if ( ( $parent['key'] ?? '' ) !== 'group_6a69018b1669c' ) return $fields;
    $existing = array_column( (array) $fields, 'key' );
    foreach ( burger_agenda_media_fields() as $field ) {
        if ( in_array( $field['key'], $existing, true ) ) continue;
        $field['parent'] = $parent['key'];
        $field['prefix'] = 'acf';
        $fields[] = acf_validate_field( $field );
    }
    return $fields;
}, 30, 2 );

/** También se ejecuta cuando Single Agenda sale de la caché de HTML. */
function burger_agenda_media_assets( $sections = [ 'galeria', 'video' ] ) {
    foreach ( array_intersect( [ 'galeria', 'video' ], $sections ) as $slug ) {
        foreach ( [ 'css' => 'styles.css', 'js' => 'scripts.js' ] as $type => $file ) {
            $relative = '/blocks/' . $slug . '/' . $file;
            $path = BURGER_THEME_PATH . $relative;
            if ( ! file_exists( $path ) ) continue;
            if ( 'css' === $type ) wp_enqueue_style( 'burger-block-' . $slug, BURGER_THEME_URL . $relative, [], filemtime( $path ) );
            else wp_enqueue_script( 'burger-block-' . $slug, BURGER_THEME_URL . $relative, [ 'jquery' ], filemtime( $path ), true );
        }
    }
}

function burger_render_agenda_media( $post_id, $parent_block, $namespace = 'agenda', $sections = [ 'galeria', 'video' ] ) {
    foreach ( array_intersect_key( burger_agenda_media_schema(), array_flip( $sections ) ) as $slug => $schema ) {
        $prefix = $namespace . '_' . $slug . '_';
        if ( ! get_field( $prefix . 'visible', $post_id ) ) continue;
        $values = [];
        foreach ( $schema as $name => $definition ) {
            $value = get_field( $prefix . $name, $post_id );
            $values[ $name ] = null === $value || false === $value || '' === $value ? $definition[2] : $value;
            // Un switch guardado apagado prevalece sobre su default.
            if ( 'true_false' === $definition[1] && metadata_exists( 'post', $post_id, $prefix . $name ) ) $values[ $name ] = (bool) $value;
        }
        $values['encabezado'] = in_array( $values['encabezado'], [ 'h2', 'h3' ], true ) ? $values['encabezado'] : 'h2';
        if ( 'galeria' === $slug && ( ! is_array( $values['galeria'] ) || ! $values['galeria'] ) ) continue;
        if ( 'video' === $slug ) {
            $values['id_video_youtube'] = trim( (string) $values['id_video_youtube'] );
            if ( ! preg_match( '/^[a-zA-Z0-9_-]{11}$/', $values['id_video_youtube'] ) ) continue;
        }
        $block = [
            'name' => 'acf/' . $slug,
            'id' => sanitize_html_class( $parent_block['id'] . '-' . $namespace . '-' . $slug ),
            'data' => [ 'preset' => 0 ],
            'burger_content_fields' => $values,
        ];
        // Scope propio, sin alterar el contexto ACF del bloque padre.
        ob_start();
        include BURGER_THEME_PATH . '/blocks/' . $slug . '/content.php';
        echo burger_apply_block_anchor( ob_get_clean(), $block );
    }
}
