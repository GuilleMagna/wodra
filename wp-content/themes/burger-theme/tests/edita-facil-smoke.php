<?php
/** CLI: usa ACF real y crea únicamente una página descartable propia. */
if ( PHP_SAPI !== 'cli' ) exit;
$GLOBALS['wp_filter']['option_active_plugins'][1][] = [
    'function' => static fn( $plugins ) => array_values( array_filter( $plugins, static fn( $p ) => str_contains( $p, 'advanced-custom-fields' ) || str_contains( $p, 'secure-custom-fields' ) ) ),
    'accepted_args' => 1,
];
require dirname( __DIR__, 4 ) . '/wp-load.php';
register_block_type( 'acf/ef-smoke', [ 'uses_context' => [ 'postId' ], 'title' => 'Prueba EF' ] );

$checks = 0;
function ef_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) throw new RuntimeException( $message );
    $checks++;
}
acf_add_local_field_group( [ 'key' => 'group_ef_smoke', 'title' => 'EF smoke',
    'location' => [ [ [ 'param' => 'block', 'operator' => '==', 'value' => 'acf/ef-smoke' ] ] ],
    'fields' => [
        [ 'key' => 'field_ef_title', 'name' => 'titulo', 'label' => 'Título', 'type' => 'text' ],
        [ 'key' => 'field_ef_image', 'name' => 'imagen', 'label' => 'Imagen', 'type' => 'image', 'return_format' => 'id' ],
        [ 'key' => 'field_ef_list', 'name' => 'lista', 'label' => 'Lista', 'type' => 'repeater', 'sub_fields' => [
            [ 'key' => 'field_ef_group', 'name' => 'grupo', 'label' => 'Grupo', 'type' => 'group', 'sub_fields' => [
                [ 'key' => 'field_ef_items', 'name' => 'items', 'label' => 'Items', 'type' => 'repeater', 'sub_fields' => [
                    [ 'key' => 'field_ef_text', 'name' => 'texto', 'label' => 'Texto', 'type' => 'textarea' ],
                ] ],
            ] ],
        ] ],
    ],
] );
$text = "Rosario — שלום <b>texto</b> \"comillas\" 'simple' & > C:\\fotos\\foto.jpg\nSegunda línea";
$values = [ 'field_ef_title' => $text, 'field_ef_image' => '123',
    'field_ef_list' => [ [ 'field_ef_group' => [ 'field_ef_items' => [
        [ 'field_ef_text' => $text ], [ 'field_ef_text' => 'Segunda fila' ],
    ] ] ] ],
];
$flat = burger_ef_capture( $values, 'efblock_smoke' );
ef_check( $flat['lista'] === 1 && $flat['lista_0_grupo_items'] === 2, 'No aplanó repetidores anidados.' );
ef_check( $flat['lista_0_grupo_items_0_texto'] === $text, 'Cambió texto al aplanar.' );
ef_check( $flat['_lista_0_grupo_items_0_texto'] === 'field_ef_text', 'Falta referencia de subcampo.' );
acf_setup_meta( $flat, 'efblock_read' );
ef_check( get_field( 'field_ef_list', 'efblock_read' )[0]['grupo']['items'][0]['texto'] === $text, 'No precargó el repetidor anidado.' );
acf_reset_meta( 'efblock_read' );
ef_check( burger_ef_hide( [ 'movil', 'otro', 'movil', 'tablet' ] ) === 'tablet movil', 'Dispositivos inválidos o duplicados.' );
$raw = '<!-- wp:acf/ef-smoke {"data":{"titulo":"Original","_titulo":"field_ef_title"}} /-->';
$legacy = parse_blocks( $raw );
$legacy_entries = burger_ef_entries( $legacy );
ef_check( str_starts_with( $legacy_entries[0]['id'], 'legacy_' ), 'Sin soporte para contenido anterior.' );
ef_check( burger_ef_find( $legacy, $legacy_entries[0]['id'] ) !== null, 'No resuelve instancia anterior.' );
$assigned = burger_ef_assign_ids( array_merge( $legacy, $legacy ) );
ef_check( $assigned[0]['attrs']['efid'] !== $assigned[1]['attrs']['efid'], 'Colisión de IDs.' );
ef_check( burger_ef_assign_ids( $assigned ) === $assigned, 'IDs inestables.' );
$duplicate = burger_ef_assign_ids( [ $assigned[0], $assigned[0] ] );
ef_check( $duplicate[0]['attrs']['efid'] !== $duplicate[1]['attrs']['efid'], 'Duplicación conserva ID.' );
$nested = parse_blocks( '<!-- wp:group --><div class="wp-block-group">' . $raw . '</div><!-- /wp:group -->' );
$entry = burger_ef_entries( $nested )[0];
$attrs = $entry['block']['attrs'];
$attrs['data']['titulo'] = 'Anidado';
burger_ef_replace( $nested, $entry['path'], $attrs );
ef_check( str_contains( serialize_blocks( $nested ), 'Anidado' ), 'No actualiza innerBlocks.' );

$admins = get_users( [ 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ] );
ef_check( ! empty( $admins ), 'Falta administrador para el contexto aislado.' );
wp_set_current_user( $admins[0] );
$fixture = 0;
try {
    $fixture = wp_insert_post( [ 'post_type' => 'page', 'post_status' => 'draft',
        'post_title' => 'EF SMOKE descartable ' . wp_generate_uuid4(), 'post_content' => wp_slash( $raw . $raw ) ], true );
    ef_check( ! is_wp_error( $fixture ) && $fixture > 0, 'No creó fixture.' );
    $before = get_post( $fixture )->post_content;
    $entries = burger_ef_entries( parse_blocks( $before ) );
    ef_check( count( $entries ) === 2 && $entries[0]['id'] !== $entries[1]['id'], 'No persistió efid por instancia.' );
    $target = burger_ef_target( $fixture, $entries[1]['id'] );
    ef_check( ! is_wp_error( $target ) && isset( $target['fields']['field_ef_title'] ), 'No encontró campos del bloque: ' . ( is_wp_error( $target ) ? $target->get_error_message() : implode( ',', array_keys( $target['fields'] ) ) ) );
    $virtual = burger_ef_virtual( $fixture, $entries[1]['id'] );
    $_POST = [ '_acf_post_id' => $virtual, 'burger_ef_nonce' => wp_create_nonce( $virtual ),
        'burger_ef_revision' => hash( 'sha256', $before ), 'acf' => wp_slash( $values ), 'burger_ef_hide' => [ 'movil' ] ];
    ef_check( ! is_wp_error( burger_ef_submission( $virtual ) ), 'Rechazó formulario autorizado.' );
    $_POST['burger_ef_nonce'] = 'incorrecto';
    ef_check( is_wp_error( burger_ef_submission( $virtual ) ), 'Aceptó nonce inválido.' );
    $_POST['burger_ef_nonce'] = wp_create_nonce( $virtual );
    $_POST['acf']['field_no_pertenece'] = 'ataque';
    ef_check( is_wp_error( burger_ef_submission( $virtual ) ), 'Aceptó campo ajeno.' );
    unset( $_POST['acf']['field_no_pertenece'] );
    $_POST['burger_ef_revision'] = str_repeat( '0', 64 );
    ef_check( is_wp_error( burger_ef_submission( $virtual ) ), 'Aceptó formulario desactualizado.' );
    $_POST['burger_ef_revision'] = hash( 'sha256', $before );
    wp_set_current_user( 0 );
    ef_check( is_wp_error( burger_ef_submission( $virtual ) ), 'Visitante puede guardar.' );
    wp_set_current_user( $admins[0] );
    $meta_before = get_post_meta( $fixture );
    $GLOBALS['acf_form'] = [];
    apply_filters( 'acf/pre_save_post', $virtual, [] );
    $after = burger_ef_entries( parse_blocks( get_post( $fixture )->post_content ) );
    ef_check( $after[0]['block'] === $entries[0]['block'], 'Alteró otra instancia.' );
    ef_check( $after[1]['block']['attrs']['data']['titulo'] === $text, 'Perdió escapes al guardar en post_content.' );
    ef_check( $after[1]['block']['attrs']['data']['lista_0_grupo_items_0_texto'] === $text, 'Perdió el repetidor al guardar.' );
    ef_check( $after[1]['block']['attrs']['efHide'] === 'movil', 'No persistió visibilidad.' );
    ef_check( ! isset( get_post_meta( $fixture )['titulo'] ) && ! isset( get_post_meta( $fixture )['lista'] ), 'Escribió campos en postmeta.' );
    ef_check( count( wp_get_post_revisions( $fixture ) ) > 0, 'No creó revisión.' );
    ef_check( $_POST['acf'] === [], 'Dejó campos para el guardado normal.' );
    // Decoración fuera de la caché, sin botones para el visitante ni presets.
    $html = '<style>.x{color:red}</style><section><h2>Test</h2></section>';
    $instance = new WP_Block( $after[1]['block'], [ 'postId' => $fixture ] );
    $marked = apply_filters( 'render_block', $html, $after[1]['block'], $instance );
    ef_check( str_contains( $marked, 'data-ef-post="' . $fixture . '"' ) && str_contains( $marked, '<section' ), 'No marcó el contenedor correcto: ' . $marked . ' context=' . json_encode( $instance->context ) . ' admin=' . (int) is_admin() );
    wp_set_current_user( 0 );
    $public = apply_filters( 'render_block', $html, $after[1]['block'], $instance );
    ef_check( ! str_contains( $public, 'data-ef-bloque' ) && str_contains( $public, 'data-ef-hide="movil"' ), 'Filtró controles al público o perdió visibilidad.' );
    wp_set_current_user( $admins[0] );
    $orphan = new WP_Block( $after[1]['block'], [ 'postId' => 0 ] );
    ef_check( ! str_contains( apply_filters( 'render_block', $html, $after[1]['block'], $orphan ), 'data-ef-bloque' ), 'Marcó un bloque sin dueño.' );
    // El formulario ACF real debe firmar y renderizar la fuente virtual.
    acf_setup_meta( $after[1]['block']['attrs']['data'], $virtual );
    ob_start();
    acf_form( [ 'post_id' => $virtual, 'fields' => array_keys( $target['fields'] ) ] );
    $form_html = ob_get_clean();
    ef_check( str_contains( $form_html, 'name="acf[field_ef_title]"' ) && str_contains( $form_html, 'field_ef_items' ), 'Formulario incompleto.' );
    acf_reset_meta( $virtual );
    // Retirar una fila debe eliminar sus valores aplanados anteriores.
    $_POST = [ '_acf_post_id' => $virtual, 'burger_ef_nonce' => wp_create_nonce( $virtual ),
        'burger_ef_revision' => hash( 'sha256', get_post( $fixture )->post_content ),
        'acf' => [ 'field_ef_list' => [] ], 'burger_ef_hide' => [] ];
    apply_filters( 'acf/pre_save_post', $virtual, [] );
    $last = burger_ef_entries( parse_blocks( get_post( $fixture )->post_content ) )[1]['block']['attrs'];
    ef_check( ! isset( $last['data']['lista_0_grupo_items_0_texto'] ) && $last['data']['titulo'] === $text, 'Quedaron filas viejas o perdió campos no enviados.' );
    ef_check( $last['efHide'] === '', 'No permite volver a mostrar en todos los dispositivos.' );
    // Recorrer el formulario firmado y la validación de ACF, no solo el filtro de guardado.
    $stored = get_post( $fixture )->post_content;
    ob_start();
    acf_form( [ 'post_id' => $virtual, 'fields' => [ 'field_ef_title' ], 'return' => admin_url( 'admin.php' ) ] );
    $signed_form = ob_get_clean();
    $_POST = [];
    $inputs = new WP_HTML_Tag_Processor( $signed_form );
    while ( $inputs->next_tag( 'INPUT' ) ) {
        if ( $inputs->get_attribute( 'type' ) === 'hidden' ) {
            $name = $inputs->get_attribute( 'name' );
            if ( $name && ! str_contains( $name, '[' ) ) $_POST[ $name ] = $inputs->get_attribute( 'value' );
            if ( $name === '_acf_form_meta[]' ) $_POST['_acf_form_meta'][] = $inputs->get_attribute( 'value' );
        }
    }
    $_POST += [ 'burger_ef_nonce' => wp_create_nonce( $virtual ), 'burger_ef_revision' => hash( 'sha256', $stored ),
        'acf' => [ 'field_ef_title' => 'Guardado por formulario firmado' ] ];
    $redirect = static function () { throw new RuntimeException( 'ef_test_redirect' ); };
    add_filter( 'wp_redirect', $redirect );
    try {
        acf_get_instance( 'acf_form_front' )->check_submit_form();
    } catch ( RuntimeException $e ) {
        if ( $e->getMessage() !== 'ef_test_redirect' ) throw $e;
    } finally {
        remove_filter( 'wp_redirect', $redirect );
    }
    $saved_form = burger_ef_entries( parse_blocks( get_post( $fixture )->post_content ) )[1];
    ef_check( $saved_form['block']['attrs']['data']['titulo'] === 'Guardado por formulario firmado', 'ACF no guardó el formulario firmado.' );
    // Simular una página anterior solamente sobre la fila descartable creada arriba.
    global $wpdb;
    $wpdb->update( $wpdb->posts, [ 'post_content' => $raw ], [ 'ID' => $fixture ] );
    clean_post_cache( $fixture );
    $legacy_id = burger_ef_entries( parse_blocks( $raw ) )[0]['id'];
    $legacy_virtual = burger_ef_virtual( $fixture, $legacy_id );
    $_POST = [ '_acf_post_id' => $legacy_virtual, 'burger_ef_nonce' => wp_create_nonce( $legacy_virtual ),
        'burger_ef_revision' => hash( 'sha256', $raw ), 'acf' => [ 'field_ef_title' => 'Primera edición' ] ];
    apply_filters( 'acf/pre_save_post', $legacy_virtual, [] );
    $first = burger_ef_entries( parse_blocks( get_post( $fixture )->post_content ) )[0];
    ef_check( str_starts_with( $first['id'], 'ef_' ) && str_contains( $GLOBALS['acf_form']['return'], $first['id'] ), 'El retorno del primer guardado no usa el nuevo ID.' );
    // Render real de un bloque de una página existente, exclusivamente de lectura.
    $front = get_post( (int) get_option( 'page_on_front' ) );
    if ( $front ) {
        $real_entries = burger_ef_entries( parse_blocks( $front->post_content ) );
        foreach ( $real_entries as $real ) {
            if ( ! empty( $real['block']['attrs']['data']['preset'] ) || ! burger_ef_fields( $real['block']['blockName'] ) ) continue;
            $GLOBALS['post'] = $front;
            setup_postdata( $front );
            $real_html = do_blocks( serialize_block( $real['block'] ) );
            ef_check( str_contains( $real_html, 'data-ef-post="' . $front->ID . '"' ), 'El render ACF real no conservó el marcador de edición.' );
            wp_reset_postdata();
            break;
        }
    }
} finally {
    $_POST = [];
    if ( is_int( $fixture ) && $fixture > 0 ) wp_delete_post( $fixture, true );
    wp_set_current_user( 0 );
}
echo "OK: $checks verificaciones; página descartable y revisiones eliminadas.\n";
