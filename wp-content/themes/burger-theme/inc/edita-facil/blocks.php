<?php
/**
 * Edita Fácil para bloques de Wodra. UI de Nakama v1.2.0; almacenamiento del theme.
 * No instala plugins, roles ni cambia el panel de WordPress.
 */
defined( 'ABSPATH' ) || exit;

function burger_ef_devices() {
    return [ 'escritorio' => 'Escritorio', 'tablet' => 'Tablet', 'movil' => 'Celular' ];
}

function burger_ef_hide( $value ) {
    $tokens = is_string( $value ) ? preg_split( '/\s+/', $value ) : (array) $value;
    return implode( ' ', array_intersect( array_keys( burger_ef_devices() ), $tokens ) );
}

// Registrar los atributos también en Gutenberg: de otro modo los descarta al guardar.
add_filter( 'register_block_type_args', function ( $args, $name ) {
    if ( str_starts_with( $name, 'acf/' ) ) {
        $args['attributes']['efid'] = [ 'type' => 'string' ];
        $args['attributes']['efHide'] = [ 'type' => 'string' ];
    }
    return $args;
}, 20, 2 );

add_action( 'enqueue_block_editor_assets', function () {
    wp_add_inline_script( 'wp-blocks', <<<'JS'
wp.hooks.addFilter('blocks.registerBlockType', 'burger/edita-facil', function(settings, name) {
    if (name.indexOf('acf/') === 0) {
        settings.attributes = Object.assign({}, settings.attributes, {
            efid: {type: 'string'}, efHide: {type: 'string'}
        });
    }
    return settings;
});
JS
    , 'after' );
} );

/** IDs únicos dentro del documento, incluso al duplicar bloques en Gutenberg. */
function burger_ef_assign_ids( $blocks, &$seen = [] ) {
    foreach ( $blocks as &$block ) {
        if ( str_starts_with( $block['blockName'] ?? '', 'acf/' ) ) {
            $id = $block['attrs']['efid'] ?? '';
            if ( ! is_string( $id ) || ! preg_match( '/^ef_[a-f0-9]{32}$/D', $id ) || isset( $seen[ $id ] ) ) {
                $id = 'ef_' . str_replace( '-', '', wp_generate_uuid4() );
            }
            $block['attrs']['efid'] = $id;
            $seen[ $id ] = true;
        }
        if ( ! empty( $block['innerBlocks'] ) ) {
            $block['innerBlocks'] = burger_ef_assign_ids( $block['innerBlocks'], $seen );
        }
    }
    unset( $block );
    return $blocks;
}

add_filter( 'wp_insert_post_data', function ( $data ) {
    if ( 'revision' === ( $data['post_type'] ?? '' ) || empty( $data['post_content'] ) ) return $data;
    $content = wp_unslash( $data['post_content'] );
    if ( ! str_contains( $content, '<!-- wp:acf/' ) ) return $data;
    $blocks = parse_blocks( $content );
    $updated = burger_ef_assign_ids( $blocks );
    if ( $updated !== $blocks ) $data['post_content'] = wp_slash( serialize_blocks( $updated ) );
    return $data;
}, 50 );

/** Las páginas anteriores funcionan sin una migración ni escrituras al visitarlas. */
function burger_ef_locator( $block, $path ) {
    return $block['attrs']['efid'] ?? ( 'legacy_' . implode( '_', $path ) . '_' . substr( hash( 'sha256', serialize_block( $block ) ), 0, 16 ) );
}

function burger_ef_entries( $blocks, $parent = [] ) {
    $entries = [];
    foreach ( $blocks as $index => $block ) {
        $path = array_merge( $parent, [ $index ] );
        if ( str_starts_with( $block['blockName'] ?? '', 'acf/' ) ) {
            $entries[] = [ 'path' => $path, 'block' => $block, 'id' => burger_ef_locator( $block, $path ) ];
        }
        if ( ! empty( $block['innerBlocks'] ) ) {
            $entries = array_merge( $entries, burger_ef_entries( $block['innerBlocks'], $path ) );
        }
    }
    return $entries;
}

function burger_ef_find( $blocks, $id ) {
    $matches = array_values( array_filter( burger_ef_entries( $blocks ), static fn( $entry ) => $entry['id'] === $id ) );
    return count( $matches ) === 1 ? $matches[0] : null;
}

function burger_ef_replace( &$blocks, $path, $attrs ) {
    $index = array_shift( $path );
    if ( ! isset( $blocks[ $index ] ) ) return false;
    if ( $path ) return burger_ef_replace( $blocks[ $index ]['innerBlocks'], $path, $attrs );
    $blocks[ $index ]['attrs'] = $attrs;
    return true;
}

function burger_ef_asset( $name ) {
    return BURGER_THEME_URL . '/inc/edita-facil/assets/' . $name;
}

function burger_ef_version( $name ) {
    return (string) filemtime( __DIR__ . '/assets/' . $name );
}

add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_user_logged_in() || ! function_exists( 'acf_form' ) ) return;
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' )
        && ! current_user_can( 'edit_post', get_queried_object_id() ) ) return;
    wp_enqueue_style( 'burger-edita-facil', burger_ef_asset( 'edita-facil.css' ), [], burger_ef_version( 'edita-facil.css' ) );
    wp_enqueue_script( 'burger-edita-facil', burger_ef_asset( 'edita-facil.js' ), [], burger_ef_version( 'edita-facil.js' ), true );
    wp_localize_script( 'burger-edita-facil', 'editaFacil', [
        'form' => admin_url( 'admin.php?page=burger-edita-facil' ),
        'marca' => 'Edita Fácil', 'autor' => 'by Nakama',
    ] );
} );

// Solo visibilidad pública; el CSS de la interfaz se carga para usuarios editores.
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'burger-ef-visibility', burger_ef_asset( 'visibility.css' ), [], burger_ef_version( 'visibility.css' ) );
} );

/**
 * Decorar DESPUÉS del render y de la caché del theme. Los transients nunca
 * contienen permisos ni marcadores de edición. El contexto debe coincidir con
 * un bloque real del documento dueño; los bloques sintéticos quedan excluidos.
 */
add_filter( 'render_block', function ( $html, $block, $instance ) {
    if ( is_admin() || ! str_starts_with( $block['blockName'] ?? '', 'acf/' ) || ! $html ) return $html;
    $hide = burger_ef_hide( $block['attrs']['efHide'] ?? '' );
    $attrs = $hide ? [ 'data-ef-hide' => $hide ] : [];
    if ( is_user_logged_in() && function_exists( 'acf_form' ) && empty( $block['attrs']['data']['preset'] ) ) {
        $owner = absint( $instance->context['postId'] ?? get_the_ID() );
        $post = $owner ? get_post( $owner ) : null;
        if ( $post && current_user_can( 'edit_post', $owner ) ) {
            $signature = serialize_block( $block );
            $matches = array_values( array_filter( burger_ef_entries( parse_blocks( $post->post_content ) ),
                static fn( $entry ) => serialize_block( $entry['block'] ) === $signature ) );
            // Antes del primer guardado puede haber bloques idénticos sin efid.
            static $occurrences = [];
            $key = $owner . ':' . hash( 'sha256', $signature );
            $occurrence = $occurrences[ $key ] ?? 0;
            if ( $matches ) {
                $entry = $matches[ $occurrence % count( $matches ) ];
                $occurrences[ $key ] = $occurrence + 1;
                $type = WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );
                $attrs += [ 'data-ef-post' => $owner, 'data-ef-bloque' => $entry['id'],
                    'data-ef-titulo' => $type->title ?? $block['blockName'] ];
            }
        }
    }
    if ( ! $attrs ) return $html;
    $tags = new WP_HTML_Tag_Processor( $html );
    while ( $tags->next_tag() ) {
        if ( in_array( $tags->get_tag(), [ 'STYLE', 'SCRIPT', 'LINK', 'TEMPLATE' ], true ) ) continue;
        foreach ( $attrs as $name => $value ) $tags->set_attribute( $name, $value );
        return $tags->get_updated_html();
    }
    return $html;
}, 30, 3 );

function burger_ef_fields( $name ) {
    $fields = [];
    foreach ( acf_get_field_groups( [ 'block' => $name ] ) as $group ) {
        // Excluir grupos globales/opciones que no pertenecen a bloques.
        $located = false;
        foreach ( $group['location'] ?? [] as $rules ) {
            foreach ( $rules as $rule ) {
                if ( ( $rule['param'] ?? '' ) === 'block' && ( $rule['operator'] ?? '' ) === '==' && ( $rule['value'] ?? '' ) === $name ) $located = true;
            }
        }
        if ( ! $located ) continue;
        foreach ( acf_get_fields( $group ) ?: [] as $field ) $fields[ $field['key'] ] = $field;
    }
    return $fields;
}

function burger_ef_error( $message, $status = 403 ) {
    wp_die( esc_html( $message ), 'Edita Fácil', [ 'response' => $status ] );
}

/** Resolver y autorizar tanto al abrir como al guardar (incluyendo AJAX de ACF). */
function burger_ef_target( $post_id, $locator ) {
    $post = get_post( $post_id );
    if ( ! $post || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) ) {
        return new WP_Error( 'permission', 'No tenés permiso para editar esta sección.' );
    }
    if ( ! function_exists( 'wp_check_post_lock' ) ) require_once ABSPATH . 'wp-admin/includes/post.php';
    if ( wp_check_post_lock( $post_id ) ) return new WP_Error( 'locked', 'Otra persona está editando esta página. Volvé a intentar cuando termine.' );
    $blocks = parse_blocks( $post->post_content );
    $entry = burger_ef_find( $blocks, $locator );
    if ( ! $entry || ! empty( $entry['block']['attrs']['data']['preset'] ) ) {
        return new WP_Error( 'missing', 'La sección cambió o usa contenido compartido. Recargá la página; los presets se editan desde WordPress.' );
    }
    $fields = burger_ef_fields( $entry['block']['blockName'] );
    if ( ! $fields ) return new WP_Error( 'fields', 'No encontramos campos editables para esta sección.' );
    return compact( 'post', 'blocks', 'entry', 'fields' );
}

function burger_ef_virtual( $post_id, $locator ) {
    return 'efblock_' . $post_id . ':' . $locator;
}

function burger_ef_submission( $virtual ) {
    if ( ! is_string( $virtual ) || ! preg_match( '/^efblock_([1-9][0-9]*):([a-zA-Z0-9_]+)$/D', $virtual, $m ) ) {
        return new WP_Error( 'target', 'La sección no es válida.' );
    }
    $nonce = $_POST['burger_ef_nonce'] ?? '';
    if ( ! is_string( $nonce ) || ! wp_verify_nonce( $nonce, $virtual ) ) return new WP_Error( 'nonce', 'La sesión venció. Recargá la página.' );
    $target = burger_ef_target( (int) $m[1], $m[2] );
    if ( is_wp_error( $target ) ) return $target;
    $hash = $_POST['burger_ef_revision'] ?? '';
    if ( ! is_string( $hash ) || ! hash_equals( hash( 'sha256', $target['post']->post_content ), $hash ) ) {
        return new WP_Error( 'conflict', 'La página cambió desde que abriste esta ventana. Cerrala y volvé a abrir la sección para ver la última versión.' );
    }
    if ( ! isset( $_POST['acf'] ) || ! is_array( $_POST['acf'] ) || array_diff( array_keys( $_POST['acf'] ), array_keys( $target['fields'] ) ) ) {
        return new WP_Error( 'fields', 'El formulario contiene campos que no pertenecen a esta sección.' );
    }
    return $target;
}

add_action( 'acf/validate_save_post', function () {
    $virtual = $_POST['_acf_post_id'] ?? '';
    if ( ! is_string( $virtual ) || ! str_starts_with( $virtual, 'efblock_' ) ) return;
    $target = burger_ef_submission( $virtual );
    if ( is_wp_error( $target ) ) acf_add_validation_error( '', $target->get_error_message() );
}, 5 );

/** Usar el mismo aplanado nativo que usa ACF al guardar bloques en Gutenberg. */
function burger_ef_capture( $values, $virtual ) {
    $data = acf_setup_meta( $values, $virtual );
    acf_reset_meta( $virtual );
    return $data;
}

add_filter( 'acf/pre_save_post', function ( $virtual ) {
    if ( ! is_string( $virtual ) || ! str_starts_with( $virtual, 'efblock_' ) ) return $virtual;
    $target = burger_ef_submission( $virtual );
    if ( is_wp_error( $target ) ) burger_ef_error( $target->get_error_message(), 409 );
    $values = wp_unslash( $_POST['acf'] ); // ACF ya aplicó kses y validó su nonce.
    $data = $target['entry']['block']['attrs']['data'] ?? [];
    // Conservar campos no enviados; quitar filas viejas de los repetidores enviados.
    foreach ( array_keys( $values ) as $key ) {
        $name = $target['fields'][ $key ]['name'];
        if ( ! $name ) continue;
        $nested = in_array( $target['fields'][ $key ]['type'], [ 'repeater', 'group', 'flexible_content', 'clone' ], true );
        foreach ( array_keys( $data ) as $stored ) {
            $plain = str_starts_with( $stored, '_' ) ? substr( $stored, 1 ) : $stored;
            if ( $plain !== $name && ( ! $nested || ! str_starts_with( $plain, $name . '_' ) ) ) continue;
            $other_root = false;
            foreach ( $target['fields'] as $other_key => $other ) {
                $other_name = $other['name'];
                if ( $other_key !== $key && $other_name && str_starts_with( $other_name, $name . '_' )
                    && ( $plain === $other_name || str_starts_with( $plain, $other_name . '_' ) ) ) $other_root = true;
            }
            if ( ! $other_root ) unset( $data[ $stored ] );
        }
    }
    $data = array_replace( $data, burger_ef_capture( $values, $virtual ) );
    $attrs = $target['entry']['block']['attrs'];
    $attrs['data'] = $data;
    $attrs['efHide'] = burger_ef_hide( wp_unslash( $_POST['burger_ef_hide'] ?? [] ) );
    burger_ef_replace( $target['blocks'], $target['entry']['path'], $attrs );
    $target['blocks'] = burger_ef_assign_ids( $target['blocks'] );
    foreach ( burger_ef_entries( $target['blocks'] ) as $entry ) {
        if ( $entry['path'] === $target['entry']['path'] ) {
            $GLOBALS['acf_form']['return'] = add_query_arg( [ 'page' => 'burger-edita-facil',
                'post' => $target['post']->ID, 'bloque' => $entry['id'], 'guardado' => '1' ], admin_url( 'admin.php' ) );
            break;
        }
    }
    $saved = wp_update_post( [ 'ID' => $target['post']->ID,
        'post_content' => wp_slash( serialize_blocks( $target['blocks'] ) ) ], true );
    if ( is_wp_error( $saved ) || ! $saved ) burger_ef_error( 'No se pudieron guardar los cambios. Volvé a intentar.', 500 );
    $_POST['acf'] = []; // Evitar el guardado normal en postmeta/opciones.
    return $virtual;
}, 20 );

add_action( 'admin_menu', function () {
    add_submenu_page( '', 'Edita Fácil', 'Edita Fácil', 'read', 'burger-edita-facil', '__return_null' );
} );

add_action( 'admin_init', function () {
    if ( ( $_GET['page'] ?? '' ) !== 'burger-edita-facil' ) return;
    if ( ! function_exists( 'acf_form' ) ) burger_ef_error( 'Falta ACF o Secure Custom Fields.' );
    $post_id = absint( $_GET['post'] ?? 0 );
    $locator = isset( $_GET['bloque'] ) && is_string( $_GET['bloque'] ) ? sanitize_key( wp_unslash( $_GET['bloque'] ) ) : '';
    $target = burger_ef_target( $post_id, $locator );
    if ( is_wp_error( $target ) ) burger_ef_error( $target->get_error_message() );
    nocache_headers();
    if ( isset( $_GET['guardado'] ) ) {
        echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Guardado</title><p>Cambios guardados.</p><script>parent.postMessage({editaFacil:"guardado"},location.origin);</script></html>';
        exit;
    }
    $virtual = burger_ef_virtual( $post_id, $locator );
    acf_setup_meta( $target['entry']['block']['attrs']['data'] ?? [], $virtual );
    acf_form_head();
    wp_enqueue_media();
    wp_enqueue_editor();
    wp_enqueue_style( 'burger-ef-form', burger_ef_asset( 'formulario.css' ), [], burger_ef_version( 'formulario.css' ) );
    if ( ! get_current_screen() ) set_current_screen( 'front' );
    iframe_header( 'Editar sección' );
    echo '<div class="ef-form">';
    $hidden = wp_nonce_field( $virtual, 'burger_ef_nonce', false, false );
    $hidden .= '<input type="hidden" name="burger_ef_revision" value="' . esc_attr( hash( 'sha256', $target['post']->post_content ) ) . '">';
    $hidden .= '<fieldset class="ef-visibilidad"><legend>¿Dónde se ve esta sección?</legend><input type="hidden" name="burger_ef_hide[]" value="">';
    $hide = explode( ' ', burger_ef_hide( $target['entry']['block']['attrs']['efHide'] ?? '' ) );
    foreach ( burger_ef_devices() as $device => $label ) {
        $hidden .= '<label style="display:block;margin:8px 0"><input type="checkbox" name="burger_ef_hide[]" value="' . esc_attr( $device ) . '" ' . checked( in_array( $device, $hide, true ), true, false ) . '> Ocultar en ' . esc_html( $label ) . '</label>';
    }
    $hidden .= '</fieldset>';
    // El retorno no depende del efid anterior: el primer guardado asigna IDs definitivos.
    $return = add_query_arg( [ 'page' => 'burger-edita-facil', 'post' => $post_id,
        'bloque' => $locator, 'guardado' => '1' ], admin_url( 'admin.php' ) );
    acf_form( [ 'id' => 'edita-facil-form', 'post_id' => $virtual,
        'fields' => array_keys( $target['fields'] ), 'return' => $return,
        'html_after_fields' => $hidden, 'submit_value' => 'Guardar cambios',
        'html_submit_button' => '<button type="submit" class="ef-guardar">%s</button>',
        'updated_message' => false, 'kses' => true,
    ] );
    echo '</div>';
    acf_reset_meta( $virtual );
    iframe_footer();
    exit;
}, 5 );
