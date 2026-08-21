<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function burger_fixed_structure_choices() {
    return [
        'header'           => 'Header',
        'footer-contenido' => 'Contenido del footer',
        'footer'           => 'Footer',
    ];
}

function burger_default_fixed_structure() {
    return [ [ 'block' => 'header' ], [ 'block' => 'footer-contenido' ], [ 'block' => 'footer' ] ];
}

function burger_get_fixed_structure_rows( $option_name ) {
    $rows = function_exists( 'get_field' ) ? get_field( $option_name, 'option' ) : null;
    // ACF no aplica default_value retroactivamente a opciones ya existentes.
    if ( ! is_array( $rows ) ) $rows = burger_default_fixed_structure();

    $allowed = array_keys( burger_fixed_structure_choices() );
    $result  = [];
    foreach ( $rows as $row ) {
        $slug = sanitize_key( is_array( $row ) ? ( $row['block'] ?? '' ) : $row );
        if ( in_array( $slug, $allowed, true ) && ! in_array( $slug, $result, true ) ) $result[] = $slug;
    }
    return $result;
}

function burger_get_fixed_structure( $post_type, $post_id = 0 ) {
    if ( 'page' === $post_type && $post_id && function_exists( 'get_field' ) ) {
        $overrides = get_field( 'burger_page_structure_overrides', 'option' );

        foreach ( (array) $overrides as $override ) {
            $page = $override['page'] ?? 0;
            $page_id = is_object( $page ) ? (int) $page->ID : (int) $page;
            if ( $page_id !== (int) $post_id ) continue;

            $allowed = array_keys( burger_fixed_structure_choices() );
            $result = [];
            foreach ( (array) ( $override['blocks'] ?? [] ) as $row ) {
                $slug = sanitize_key( is_array( $row ) ? ( $row['block'] ?? '' ) : $row );
                if ( in_array( $slug, $allowed, true ) && ! in_array( $slug, $result, true ) ) $result[] = $slug;
            }
            return $result;
        }
    }
    return burger_get_fixed_structure_rows(
        'post' === $post_type ? 'burger_post_fixed_structure' : 'burger_page_fixed_structure'
    );
}

function burger_fixed_structure_template( $post_type ) {
    $template = [];
    foreach ( burger_get_fixed_structure( $post_type ) as $slug ) {
        $template[] = [ 'acf/' . $slug, [
            'mode' => 'preview',
            'lock' => [ 'move' => true, 'remove' => true ],
        ] ];
    }
    return $template;
}

// Inserta la estructura bloqueada en páginas y entradas nuevas.
add_action( 'init', function () {
    foreach ( [ 'page', 'post' ] as $post_type ) {
        $object = get_post_type_object( $post_type );
        if ( ! $object ) continue;
        $object->template = burger_fixed_structure_template( $post_type );
        $object->template_lock = false;
    }
}, 100 );

// Los bloques estructurales sólo se administran desde Configuración General.
add_action( 'init', function () {
    $registry = WP_Block_Type_Registry::get_instance();
    foreach ( array_keys( burger_fixed_structure_choices() ) as $slug ) {
        $block = $registry->get_registered( 'acf/' . $slug );
        if ( $block ) {
            $block->supports['inserter'] = false;
            $block->supports['lock'] = true;
        }
    }
}, 110 );

function burger_lock_fixed_structure_blocks( $blocks ) {
    $fixed = array_map( static fn( $slug ) => 'acf/' . $slug, array_keys( burger_fixed_structure_choices() ) );

    foreach ( $blocks as &$block ) {
        if ( in_array( $block['blockName'] ?? '', $fixed, true ) ) {
            $block['attrs'] = (array) ( $block['attrs'] ?? [] );
            $block['attrs']['lock'] = [ 'move' => true, 'remove' => true ];
        }
        if ( ! empty( $block['innerBlocks'] ) ) {
            $block['innerBlocks'] = burger_lock_fixed_structure_blocks( $block['innerBlocks'] );
        }
    }
    unset( $block );

    return $blocks;
}

function burger_lock_fixed_structure_content( $content ) {
    if ( ! is_string( $content ) || '' === trim( $content ) ) return $content;
    return serialize_blocks( burger_lock_fixed_structure_blocks( parse_blocks( $content ) ) );
}

/**
 * Gutenberg puede cargar correctamente bloques ACF heredados y, al publicar,
 * serializarlos sin `name`, `data` ni `mode`. Si el bloque ya existía y el
 * request no trae `data`, conserva sus atributos guardados. Un `data` presente
 * (incluso con valores vacíos) siempre se respeta para permitir ediciones.
 */
function burger_preserve_missing_acf_block_data( $incoming, $stored ) {
    foreach ( $incoming as $index => &$block ) {
        $previous = $stored[ $index ] ?? null;
        if ( ! is_array( $previous ) || ( $block['blockName'] ?? '' ) !== ( $previous['blockName'] ?? '' ) ) continue;

        if ( str_starts_with( $block['blockName'] ?? '', 'acf/' ) ) {
            $attrs          = (array) ( $block['attrs'] ?? [] );
            $previous_attrs = (array) ( $previous['attrs'] ?? [] );

            if ( ! array_key_exists( 'data', $attrs ) && array_key_exists( 'data', $previous_attrs ) ) {
                foreach ( [ 'name', 'data', 'mode' ] as $key ) {
                    if ( ! array_key_exists( $key, $attrs ) && array_key_exists( $key, $previous_attrs ) ) {
                        $attrs[ $key ] = $previous_attrs[ $key ];
                    }
                }
                $block['attrs'] = $attrs;
            }
        }

        if ( ! empty( $block['innerBlocks'] ) && ! empty( $previous['innerBlocks'] ) ) {
            $block['innerBlocks'] = burger_preserve_missing_acf_block_data( $block['innerBlocks'], $previous['innerBlocks'] );
        }
    }
    unset( $block );
    return $incoming;
}

function burger_preserve_missing_acf_content( $content, $post_id ) {
    if ( ! is_string( $content ) || '' === trim( $content ) || ! $post_id ) return $content;
    $stored = get_post_field( 'post_content', $post_id, 'raw' );
    if ( ! is_string( $stored ) || '' === trim( $stored ) ) return $content;

    return serialize_blocks( burger_preserve_missing_acf_block_data( parse_blocks( $content ), parse_blocks( $stored ) ) );
}

// WordPress 7.1 siempre aísla el editor en un iframe. ACF 6.2 no puede
// ejecutar formularios v2 dentro de ese iframe, pero sí puede mostrarlos en
// la barra lateral cuando el bloque permanece en modo preview.
function burger_force_acf_preview_blocks( $blocks ) {
    foreach ( $blocks as &$block ) {
        if ( str_starts_with( $block['blockName'] ?? '', 'acf/' ) ) {
            $block['attrs'] = (array) ( $block['attrs'] ?? [] );
            $block['attrs']['mode'] = 'preview';
        }
        if ( ! empty( $block['innerBlocks'] ) ) {
            $block['innerBlocks'] = burger_force_acf_preview_blocks( $block['innerBlocks'] );
        }
    }
    unset( $block );
    return $blocks;
}

function burger_prepare_iframed_editor_content( $content ) {
    if ( ! is_string( $content ) || '' === trim( $content ) ) return $content;
    $blocks = burger_lock_fixed_structure_blocks( parse_blocks( $content ) );
    return serialize_blocks( burger_force_acf_preview_blocks( $blocks ) );
}
// La carga inicial de Gutenberg no siempre pasa por REST. Estos filtros
// entregan el contenido bloqueado sin reescribir la base automáticamente.
function burger_lock_fixed_structure_editor_content( $content, $post_id = 0 ) {
    $post_type = $post_id ? get_post_type( $post_id ) : '';
    if ( ! in_array( $post_type, [ 'page', 'post', 'template' ], true ) ) return $content;
    return burger_prepare_iframed_editor_content( $content );
}
add_filter( 'edit_post_content', 'burger_lock_fixed_structure_editor_content', 20, 2 );
add_filter( 'content_edit_pre', 'burger_lock_fixed_structure_editor_content', 20, 2 );

// Gutenberg recibe bloqueadas también las instancias guardadas antes de esta función.
function burger_lock_fixed_structure_rest_response( $response, $post, $request ) {
    if ( is_wp_error( $response ) || 'edit' !== $request->get_param( 'context' ) ) return $response;

    $data = $response->get_data();
    if ( isset( $data['content']['raw'] ) ) {
        $data['content']['raw'] = burger_prepare_iframed_editor_content( $data['content']['raw'] );
        $response->set_data( $data );
    }
    return $response;
}
add_filter( 'rest_prepare_page', 'burger_lock_fixed_structure_rest_response', 20, 3 );
add_filter( 'rest_prepare_post', 'burger_lock_fixed_structure_rest_response', 20, 3 );

// Al guardar desde Gutenberg, el bloqueo queda persistido en post_content.
add_filter( 'wp_insert_post_data', function ( $data, $postarr ) {
    if ( ! in_array( $data['post_type'] ?? '', [ 'page', 'post' ], true ) ) return $data;
    if ( ! isset( $data['post_content'] ) ) return $data;

    // Duplicate Page entrega post_content sin wp_slash() cuando está configurado
    // para el editor clásico. Recuperamos la fuente para no perder escapes JSON.
    $action = sanitize_key( $_REQUEST['action'] ?? '' );
    if ( 'dt_duplicate_post_as_draft' === $action ) {
        $source_id = absint( $_REQUEST['post'] ?? 0 );
        $source = $source_id ? get_post( $source_id ) : null;
        if ( $source && $source->post_type === ( $data['post_type'] ?? '' ) ) {
            $data['post_content'] = burger_lock_fixed_structure_content( $source->post_content );
        }
        return $data;
    }

    // Gutenberg guarda mediante REST. Otros procesos (por ejemplo Duplicate Page)
    // pueden insertar el contenido sin el slash adicional que espera wp_insert_post().
    // Parsearlo en ese punto elimina los atributos JSON de los bloques ACF duplicados.
    if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) return $data;

    $data['post_content'] = burger_preserve_missing_acf_content( $data['post_content'], absint( $postarr['ID'] ?? 0 ) );
    $data['post_content'] = burger_lock_fixed_structure_content( $data['post_content'] );
    return $data;
}, 20, 2 );

function burger_strip_fixed_structure_blocks( $blocks ) {
    $fixed = array_map( static fn( $slug ) => 'acf/' . $slug, array_keys( burger_fixed_structure_choices() ) );
    $result = [];
    foreach ( $blocks as $block ) {
        if ( in_array( $block['blockName'] ?? '', $fixed, true ) ) continue;
        if ( ! empty( $block['innerBlocks'] ) ) {
            $block['innerBlocks'] = burger_strip_fixed_structure_blocks( $block['innerBlocks'] );
        }
        $result[] = $block;
    }
    return $result;
}

function burger_render_fixed_structure_block( $slug ) {
    $markup = sprintf(
        '<!-- wp:acf/%1$s {"name":"acf/%1$s","data":{"preset":"0"},"mode":"preview","lock":{"move":true,"remove":true}} /-->',
        sanitize_key( $slug )
    );
    return do_blocks( $markup );
}

/*
 * También aplica la estructura a contenido existente sin migrar la base.
 * single.php renderiza una página plantilla, por eso se usa el objeto
 * consultado para distinguir entre la estructura de páginas y entradas.
 */
add_filter( 'the_content', function ( $content ) {
    if ( is_admin() || ! is_main_query() || ! is_singular() ) return $content;

    $queried_id   = get_queried_object_id();
    $queried_type = $queried_id ? get_post_type( $queried_id ) : get_post_type();
    if ( ! in_array( $queried_type, [ 'page', 'post' ], true ) ) return $content;

    // single.php procesa una página-plantilla y adentro vuelve a filtrar la novedad.
    // La estructura se agrega una sola vez, en el nivel exterior.
    static $post_structure_applied = false;
    if ( 'post' === $queried_type ) {
        if ( $post_structure_applied ) return $content;
        $post_structure_applied = true;
    }

    $content = serialize_blocks( burger_strip_fixed_structure_blocks( parse_blocks( $content ) ) );
    $before = '';
    $after  = '';
    foreach ( burger_get_fixed_structure( $queried_type, $queried_id ) as $slug ) {
        if ( 'header' === $slug ) $before .= burger_render_fixed_structure_block( $slug );
        else $after .= burger_render_fixed_structure_block( $slug );
    }
    return $before . $content . $after;
}, 8 );
// Hace efectivo el bloqueo también sobre bloques que ya estaban guardados.
add_filter( 'block_editor_settings_all', function ( $settings, $context ) {
    if ( ! empty( $context->post ) && in_array( $context->post->post_type, [ 'page', 'post' ], true ) ) $settings['canLockBlocks'] = false;
    return $settings;
}, 10, 2 );

add_action( 'enqueue_block_editor_assets', function () {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || ! in_array( $screen->post_type, [ 'page', 'post' ], true ) ) return;
    $script = <<<'JS'
(function (wp) {
    if (!wp.data) return;
    var fixed = ['acf/header', 'acf/footer-contenido', 'acf/footer'], done = false;
    var unsubscribe = wp.data.subscribe(function () {
        if (done) return;
        var select = wp.data.select('core/block-editor'), dispatch = wp.data.dispatch('core/block-editor');
        if (!select || !dispatch) return;
        var blocks = select.getBlocks();
        if (!blocks.length) return;
        done = true;
        var lock = function (items) { items.forEach(function (block) {
            if (fixed.indexOf(block.name) !== -1) dispatch.updateBlockAttributes(block.clientId, { lock: { move: true, remove: true } });
            if (block.innerBlocks && block.innerBlocks.length) lock(block.innerBlocks);
        }); };
        lock(blocks);
        unsubscribe();
    });
})(window.wp);

// SCF v3 puede actualizar la preview sin consolidar attributes.data antes
// de publicar. Sincroniza explícitamente cada cambio del formulario ACF.
(function (wp, $) {
    var bound = false, timers = {};
    function bind() {
        if (bound) return;
        if (!wp || !wp.data || !$ || !window.acf || !window.acf.serialize) {
            window.setTimeout(bind, 100);
            return;
        }
        bound = true;
        $(document).on('change input', '.acf-block-fields [name^="acf-block_"]', function () {
            var form = this.closest('.acf-block-fields');
            if (!form) return;
            var match = (this.name || '').match(/^acf-block_([^\[]+)/);
            var clientId = match ? match[1] : form.getAttribute('data-block-id');
            if (!clientId) return;
            window.clearTimeout(timers[clientId]);
            timers[clientId] = window.setTimeout(function () {
                var block = wp.data.select('core/block-editor').getBlock(clientId);
                if (!block) return;
                var data = window.acf.serialize($(form), 'acf-block_' + clientId);
                if (!data || JSON.stringify(data) === JSON.stringify(block.attributes.data || {})) return;
                wp.data.dispatch('core/block-editor').updateBlockAttributes(clientId, { data: data });
            }, 50);
        });
    }
    bind();
})(window.wp, window.jQuery);
JS;
    wp_add_inline_script( 'wp-blocks', $script, 'after' );
} );

// Graba los defaults una sola vez para que el tab no aparezca vacío.
add_action( 'admin_init', function () {
    if ( ! current_user_can( 'manage_options' ) || ! function_exists( 'acf_get_metadata' ) || ! function_exists( 'update_field' ) ) return;
    if ( null === acf_get_metadata( 'options', 'burger_page_fixed_structure' ) ) update_field( 'field_burger_page_fixed_structure', burger_default_fixed_structure(), 'option' );
    if ( null === acf_get_metadata( 'options', 'burger_post_fixed_structure' ) ) update_field( 'field_burger_post_fixed_structure', burger_default_fixed_structure(), 'option' );
}, 80 );
// Define los campos que se anexan al grupo existente sin reemplazarlo.
function burger_fixed_structure_fields() {

    $parent = 'group_66ec98973649f'; // > Configuración General.
    $block_select = static function ( $key ) {
        return [
            'key' => $key, 'label' => 'Bloque', 'name' => 'block', 'type' => 'select',
            'choices' => burger_fixed_structure_choices(), 'ui' => 1, 'return_format' => 'value',
        ];
    };

    $fields = [
        [
            'key' => 'field_burger_fixed_structure_tab', 'label' => 'Estructura fija',
            'name' => '', 'type' => 'tab', 'placement' => 'top', 'endpoint' => 0,
        ],
        [
            'key' => 'field_burger_page_fixed_structure', 'label' => 'Estructura de páginas',
            'name' => 'burger_page_fixed_structure', 'type' => 'repeater', 'layout' => 'table',
            'instructions' => 'Se aplica a todas las páginas salvo las que tengan una excepción individual.',
            'button_label' => 'Agregar bloque',
            'sub_fields' => [ $block_select( 'field_burger_page_fixed_structure_block' ) ],
        ],
        [
            'key' => 'field_burger_post_fixed_structure', 'label' => 'Estructura de entradas',
            'name' => 'burger_post_fixed_structure', 'type' => 'repeater', 'layout' => 'table',
            'instructions' => 'Se aplica a todas las novedades/entradas y a su plantilla de single.',
            'button_label' => 'Agregar bloque',
            'sub_fields' => [ $block_select( 'field_burger_post_fixed_structure_block' ) ],
        ],
        [
            'key' => 'field_burger_page_structure_overrides', 'label' => 'Excepciones por página',
            'name' => 'burger_page_structure_overrides', 'type' => 'repeater', 'layout' => 'block',
            'instructions' => 'Elegí una página y definí su estructura particular. Si no figura aquí, usa la estructura general de páginas.',
            'button_label' => 'Agregar excepción',
            'sub_fields' => [
                [
                    'key' => 'field_burger_page_structure_override_page', 'label' => 'Página',
                    'name' => 'page', 'type' => 'post_object', 'post_type' => [ 'page' ],
                    'return_format' => 'id', 'ui' => 1, 'required' => 1,
                ],
                [
                    'key' => 'field_burger_page_structure_override_blocks', 'label' => 'Bloques estructurales',
                    'name' => 'blocks', 'type' => 'repeater', 'layout' => 'table',
                    'instructions' => 'Ordenalos o eliminá una fila para ocultar ese bloque en la página elegida.',
                    'button_label' => 'Agregar bloque',
                    'sub_fields' => [ $block_select( 'field_burger_page_structure_override_block' ) ],
                ],
            ],
        ],
    ];

    foreach ( $fields as &$field ) $field['parent'] = $parent;
    unset( $field );
    return $fields;
}

// Registrar las keys bajo un padre virtual permite que ACF las resuelva al
// guardar, sin hacer que sustituyan los campos reales de Configuración General.
if ( function_exists( 'acf_add_local_fields' ) ) {
    $burger_virtual_fields = burger_fixed_structure_fields();
    foreach ( $burger_virtual_fields as &$burger_virtual_field ) {
        $burger_virtual_field['parent'] = 'group_burger_fixed_structure_virtual';
    }
    unset( $burger_virtual_field );
    acf_add_local_fields( $burger_virtual_fields );
    unset( $burger_virtual_fields );
}

function burger_validate_fixed_structure_field( $field, $parent ) {
    $field['parent'] = $parent;

    if ( ! empty( $field['sub_fields'] ) ) {
        foreach ( $field['sub_fields'] as &$sub_field ) {
            $sub_field = burger_validate_fixed_structure_field( $sub_field, $field['key'] );
        }
        unset( $sub_field );
    }

    return acf_validate_field( $field );
}

add_filter( 'acf/load_fields', function ( $fields, $parent ) {
    if ( ( $parent['key'] ?? '' ) !== 'group_66ec98973649f' ) return $fields;

    $existing = array_column( (array) $fields, 'key' );
    foreach ( burger_fixed_structure_fields() as $field ) {
        if ( ! in_array( $field['key'], $existing, true ) ) {
            $fields[] = burger_validate_fixed_structure_field( $field, $parent['key'] );
        }
    }
    return $fields;
}, 20, 2 );
