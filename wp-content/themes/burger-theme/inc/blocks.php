<?php

/**
 * CF7 sanitiza el HTML de cada formulario con su propio wp_kses (ver
 * wpcf7_kses() en contact-form-7/includes/formatting.php), que arranca
 * de wp_kses_allowed_html('post') y no incluye svg/path/etc. Por eso
 * cualquier ícono SVG pegado a mano en un botón del formulario (como los
 * que ya usa el theme en "Iconos de botones") queda roto: el <path> con
 * atributos se elimina y solo sobreviven las etiquetas de cierre.
 */
add_filter( 'wpcf7_kses_allowed_html', function ( $tags ) {

    $tags['svg'] = [
        'class'       => true,
        'width'       => true,
        'height'      => true,
        'viewbox'     => true,
        'fill'        => true,
        'xmlns'       => true,
        'aria-hidden' => true,
    ];

    $svg_shape_attrs = [
        'fill'             => true,
        'stroke'           => true,
        'stroke-width'     => true,
        'stroke-linecap'   => true,
        'stroke-linejoin'  => true,
    ];

    $tags['path']   = $svg_shape_attrs + [ 'd' => true ];
    $tags['circle'] = $svg_shape_attrs + [ 'cx' => true, 'cy' => true, 'r' => true ];
    $tags['rect']   = $svg_shape_attrs + [ 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ];
    $tags['line']   = $svg_shape_attrs + [ 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ];
    $tags['g']      = $svg_shape_attrs;

    return $tags;
});

add_action('template_redirect', function () {

    if ( is_user_logged_in() && is_page( 'acceso-distribuidores' ) ) {
        wp_redirect( home_url('/botonera') );
        exit;
    }

    if ( is_admin() || is_front_page() ) return;

    $targets = array_filter([
        BURGER_OPTIONS['template_singles']->ID ?? BURGER_OPTIONS['template_singles'] ?? null,
        BURGER_OPTIONS['template_productos']->ID ?? BURGER_OPTIONS['template_productos'] ?? null,
        BURGER_OPTIONS['template_servicios']->ID ?? BURGER_OPTIONS['template_servicios'] ?? null,
    ]);

    if ( ! empty($targets) && is_page($targets) ) {
        wp_redirect( home_url() );
        exit;
    }

});

add_action('save_post', function($post_id, $post, $update) {

    static $running = false;

    if ($running) return;
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;
    if ($post->post_type !== 'page') return;

    if (
        empty($_POST['burger_page_blocks_nonce']) ||
        !wp_verify_nonce($_POST['burger_page_blocks_nonce'], 'burger_page_blocks_save')
    ) {
        return;
    }

    $content = !empty($_POST['burger_replace_content']) ? '' : $post->post_content;

    if (empty($_POST['burger_add_blocks']) || !is_array($_POST['burger_add_blocks'])) {
        return;
    }

    $selected_blocks = array_map('sanitize_title', $_POST['burger_add_blocks']);

    $ordered_blocks = [];

    $bloques_disponibles = BURGER_OPTIONS['bloques_disponibles'] ?? [];

    foreach ($bloques_disponibles as $bloque) {

        if (empty($bloque['activo']) || empty($bloque['slug'])) {
            continue;
        }

        $slug = sanitize_title($bloque['slug']);

        if (in_array($slug, $selected_blocks, true)) {
            $ordered_blocks[] = $slug;
        }
    }

    foreach ($ordered_blocks as $slug) {

        $slug = sanitize_title($slug);

        if (!$slug) continue;

        if (burger_content_has_block($content, $slug)) {
            continue;
        }

        $content .= burger_get_block_markup($slug);
    }

    if ($content === $post->post_content) {
        return;
    }

    $running = true;

    wp_update_post([
        'ID'           => $post_id,
        'post_content' => $content,
    ]);

    $running = false;

}, 10, 3);

function burger_get_block_markup($slug) {
    
    return sprintf(
        '<!-- wp:acf/%s {"data":{"preset":"0"}} /-->' . "\n",
        esc_attr($slug)
    );

}

function burger_content_has_block($content, $slug) {

    return strpos($content, '<!-- wp:acf/' . $slug) !== false;

}

add_action('pre_get_posts', function($query){

    if ( is_admin() && $query->is_main_query() && $query->get('post_type') === 'preset' ) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }

});

/*
add_filter('allowed_block_types_all', 'burger_allowed_blocks_only', 10, 2);

function burger_allowed_blocks_only($allowed_blocks, $editor_context) {

    if (current_user_can('administrator')) {
        return true;
    }

    $allowed = [];
    //$allowed[] = 'core/paragraph';
    //$allowed[] = 'core/heading';
    //$allowed[] = 'core/image';
    //$allowed[] = 'core/list';

    $bloques = get_field(
        'bloques_disponibles',
        'option'
    );

    if (!empty($bloques)) {

        foreach ($bloques as $bloque) {

            if (empty($bloque['activo'])) {
                continue;
            }

            $allowed[] = 'acf/' . $bloque['slug'];
        }
    }

    return $allowed;

}
*/

function generate_dynamic_css() {

    $font_family            = get_field( 'font_family', 'option' ) ?: '"Lato", sans-serif;';
    $font_family_secudaria  = get_field( 'font_family_secudaria', 'option' ) ?: '"Work Sans", sans-serif';

    $expandir_fuentes         = get_field( 'expandir_fuentes', 'option' ) ?: false;

    if( $expandir_fuentes ):
        $font_family_h1         = get_field( 'h1', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_h2         = get_field( 'h2', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_h3         = get_field( 'h3', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_h4         = get_field( 'h4', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_h5         = get_field( 'h5', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_h6         = get_field( 'h6', 'option' ) ?: '"Lato", sans-serif;';
        $font_family_p          = get_field( 'p', 'option' ) ?: '"Lato", sans-serif;';
    else:
            $font_family_h1        = $font_family_h2 = $font_family_h3 = $font_family_h4 = $font_family_h5 = $font_family_h6 = $font_family;
            $font_family_p         = $font_family_secudaria;
    endif;

    $primary                = get_field( 'primary_color', 'option' ) ?: '#E2211C';
    $primary_dos            = get_field( 'primary_color_dos', 'option' ) ?: '#d1860e';
    $primary_text           = get_field( 'primary_text_color', 'option' ) ?: '#ffffff';
    $primary_text_dos       = get_field( 'primary_text_color_dos', 'option' ) ?: '#b31117';

    $secondary              = get_field( 'secondary_color', 'option' ) ?: '#323232';
    $secondary_dos          = get_field( 'secondary_color_dos', 'option' ) ?: '#050404';
    $secondary_text         = get_field( 'secondary_text_color', 'option' ) ?: '#ffffff';
    $secondary_text_dos     = get_field( 'secondary_text_color_dos', 'option' ) ?: '#050404';

    $light                  = get_field( 'light_color', 'option' ) ?: '#f4f4f4';
    $light_dos              = get_field( 'light_color_dos', 'option' ) ?: '#dad1d1';
    $dark                   = get_field( 'dark_color', 'option' ) ?: '#313e48';
    $dark_dos               = get_field( 'dark_color_dos', 'option' ) ?: '#1d2830';

    $warning                = get_field( 'warning_color', 'option' ) ?: '#FAC904';
    $warning_dos            = get_field( 'warning_color_dos', 'option' ) ?: '#dfb40a';
    $danger                 = get_field( 'danger_color', 'option' ) ?: '#FF0066';
    $danger_dos             = get_field( 'danger_color_dos', 'option' ) ?: '#d30f5e';
    $success                = get_field( 'success_color', 'option' ) ?: '#09B054';
    $success_dos            = get_field( 'success_color_dos', 'option' ) ?: '#179b52';

    $white                  = get_field( 'white_color', 'option' ) ?: '#fff';
    $black                  = get_field( 'black_color', 'option' ) ?: '#000';

    $border_radius          = get_field( 'border_radius', 'option' ) ?: '0 0 0 0';
    $section_padding        = get_field( 'section_padding', 'option' ) ?: '6rem 0';
    $section_margin         = get_field( 'section_margin', 'option' ) ?: '0';

    $padding_botones        = get_field( 'padding_botones', 'option' ) ?: '1rem 1.4rem';
    $rounded_botones        = get_field( 'rounded_botones', 'option' ) ?: '50rem';

    return "

        body                { font-family: $font_family }
        h1                  { font-family: $font_family_h1 }
        h2                  { font-family: $font_family_h2 }
        h3                  { font-family: $font_family_h3 }
        h4                  { font-family: $font_family_h4 }
        h5                  { font-family: $font_family_h5 }
        h1.h5               { font-family: $font_family_h5 }
        h6                  { font-family: $font_family_h6 }
        .text-contador      { font-family: $font_family_h1 }
        .text-parallax      { font-family: $font_family_h1 }
        .btn                { border-radius: $rounded_botones; padding: $padding_botones; transition: all 0.3s ease-in-out !important; }
        .slide p            { font-family: $font_family_h1 }

        :root {

            --font-family: $font_family;
            --primary: $primary;
            --primary-dos: $primary_dos;
            --primary-text: $primary_text;
            --primary-text-dos: $primary_text_dos;

            --secondary: $secondary;
            --secondary-dos: $secondary_dos;
            --secondary-text: $secondary_text;
            --secondary-text-dos: $secondary_text_dos;

            --light: $light;
            --light-dos: $light_dos;
            --dark: $dark;
            --dark-dos: $dark_dos;

            --white: $white;
            --black: $black;

            --warning: $warning;
            --warning-dos: $warning_dos;
            --danger: $danger;
            --danger-dos: $danger_dos;
            --success: $success;
            --success-dos: $success_dos;

            --section-padding: $section_padding;
            --section-margin: $section_margin;
            --border-radius: $border_radius;

            --btn-padding: $padding_botones;
            --btn-radius: $rounded_botones;
        }

    ";
}

function generate_button_classes_css() {

    $config = get_field( 'icono_de_botones', 'option' );

    if ( empty( $config ) || ! is_array( $config ) ) {
        return '';
    }

    $sanitize_color = static function ( $color, $fallback ) {

        $color = trim( (string) $color );

        if ( $color === '' ) {
            return $fallback;
        }

        $hex = sanitize_hex_color( $color );

        if ( $hex ) {
            return $hex;
        }

        if (
            preg_match(
                '/^rgba?\(\s*[\d.]+%?\s*,\s*[\d.]+%?\s*,\s*[\d.]+%?(?:\s*,\s*(?:0|1|0?\.\d+))?\s*\)$/i',
                $color
            )
        ) {
            return $color;
        }

        return $fallback;
    };

    $css = '
        .btn                { display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .btn svg            { flex-shrink: 0; }
        .btn-texto          { border-radius: 0 !important; padding: 0 !important; justify-content: start !important; }
        .btn-texto-blanco   { border-radius: 0 !important; padding: 0 !important; justify-content: start !important; }       
    ';

    foreach ( $config as $row ) {

        $slug = sanitize_title( $row['tipo'] ?? '' );

        if ( $slug === '' ) {
            continue;
        }

        $fondo = $sanitize_color(
            $row['color_fondo'] ?? '',
            'transparent'
        );

        $borde = $sanitize_color(
            $row['color_borde'] ?? '',
            'transparent'
        );

        $texto = $sanitize_color(
            $row['color_texto'] ?? '',
            'inherit'
        );

        $fondo_hover = $sanitize_color(
            $row['color_fondo_hover'] ?? '',
            $fondo
        );

        $borde_hover = $sanitize_color(
            $row['color_borde_hover'] ?? '',
            $borde
        );

        $texto_hover = $sanitize_color(
            $row['color_texto_hover'] ?? '',
            $texto
        );

        $css .= "
            .{$slug} {
                background-color: {$fondo} !important;
                border-color: {$borde} !important;
                color: {$texto} !important;
            }

            .{$slug}:hover,
            .{$slug}:focus {
                background-color: {$fondo_hover} !important;
                border-color: {$borde_hover} !important;
                color: {$texto_hover} !important;
            }
        ";
    }

    return $css;
}

function generate_icon_classes_css() {

    $config = get_field( 'estilos_de_iconos', 'option' );

    if ( empty( $config ) || ! is_array( $config ) ) {
        return '';
    }

    $sanitize_color = static function ( $color, $fallback ) {

        $color = trim( (string) $color );

        if ( $color === '' ) {
            return $fallback;
        }

        $hex = sanitize_hex_color( $color );

        if ( $hex ) {
            return $hex;
        }

        if (
            preg_match(
                '/^rgba?\(\s*[\d.]+%?\s*,\s*[\d.]+%?\s*,\s*[\d.]+%?(?:\s*,\s*(?:0|1|0?\.\d+))?\s*\)$/i',
                $color
            )
        ) {
            return $color;
        }

        return $fallback;
    };

    $css = '';

    foreach ( $config as $row ) {

        $slug = sanitize_title( $row['nombre'] ?? '' );

        if ( $slug === '' ) {
            continue;
        }

        $color = $sanitize_color(
            $row['color'] ?? '',
            'currentColor'
        );

        $color_hover = $sanitize_color(
            $row['color_hover'] ?? '',
            $color
        );

        $css .= "
            .{$slug} {
                color: {$color} !important;
            }

            .{$slug} svg {
                color: {$color} !important;
                fill: currentColor;
                transition:
                    color 0.3s ease,
                    fill 0.3s ease,
                    stroke 0.3s ease;
            }

            .{$slug} svg path,
            .{$slug} svg circle,
            .{$slug} svg rect,
            .{$slug} svg polygon,
            .{$slug} svg ellipse {
                fill: currentColor;
            }

            .{$slug}:hover,
            .{$slug}:focus,
            a:hover .icono-{$slug},
            button:hover .icono-{$slug} {
                color: {$color_hover} !important;
            }

            .{$slug}:hover svg,
            .{$slug}:focus svg,
            a:hover .icono-{$slug} svg,
            button:hover .icono-{$slug} svg {
                color: {$color_hover} !important;
            }
        ";
    }

    return $css;
}

function add_editor_dynamic_styles() {
      wp_register_style( 'nakama-editor-vars', false );
      wp_enqueue_style( 'nakama-editor-vars' );
      wp_add_inline_style(
          'nakama-editor-vars',
          generate_dynamic_css() . generate_button_classes_css() . generate_icon_classes_css()
      );
}

// Prioridad 20: debe imprimirse después de nakama_enqueue_editor_frontend_styles
// (que carga themes/css/colors.css con sus valores --primary/etc por defecto)
// para poder pisarlos, igual que en el front (header.php carga colors.css
// antes del <style> con generate_dynamic_css()).
add_action( 'enqueue_block_editor_assets', 'add_editor_dynamic_styles', 20 );

add_action( 'after_setup_theme', function() {
      add_theme_support( 'editor-styles' ); 
      add_theme_support( 'block-templates' ); 
});

/*
add_action('add_meta_boxes', function() {
    add_meta_box(
        'burger_page_blocks',
        'Burger - Agregar bloques',
        'burger_page_blocks_metabox',
        'page',
        'side',
        'high'
    );
});

function burger_page_blocks_metabox($post) {

    wp_nonce_field('burger_page_blocks_save', 'burger_page_blocks_nonce');

    $bloques = BURGER_OPTIONS['bloques_disponibles'] ?? [];

    if (!$bloques) {
        echo '<p>No hay bloques disponibles.</p>';
        return;
    }

    // Bloques iniciales configurados en Opciones
    $bloques_iniciales = get_field('bloques_iniciales', 'option') ?: [];
    $slugs_iniciales   = wp_list_pluck($bloques_iniciales, 'slug');

    echo '<p><strong>Agregar al guardar:</strong></p>';

    foreach ($bloques as $bloque) {

        if (empty($bloque['activo']) || empty($bloque['slug'])) {
            continue;
        }

        $slug   = esc_attr($bloque['slug']);
        $nombre = esc_html($bloque['nombre'] ?? $slug);

        $checked = in_array($slug, $slugs_iniciales, true) ? 'checked="checked"' : '';

        echo '<label style="display:block;margin-bottom:6px;">';
        echo '<input type="checkbox" name="burger_add_blocks[]" value="'.$slug.'" '.$checked.'> ';
        echo $nombre;
        echo '</label>';
    }

    echo '<hr>';
    echo '<label>';
    echo '<input type="checkbox" name="burger_replace_content" value="1"> ';
    echo 'Reemplazar contenido actual';
    echo '</label>';
}
*/

add_action( 'init', 'burger_extend_blocks' );

function burger_extend_blocks() {

    if ( function_exists( 'acf_register_block' ) ) {

        if( !empty(BURGER_OPTIONS['bloques_disponibles']) && count( BURGER_OPTIONS['bloques_disponibles'] ) > 0 ){

            foreach( BURGER_OPTIONS['bloques_disponibles'] as $bloque ){

                if( $bloque['activo'] == 0 ) continue;

                $slug = $bloque['slug'];
    
                acf_register_block( array(
                    'name'              => $slug,
                    'title'             => ucfirst($slug),
                    'description'       => 'Imprime el '.ucfirst($slug).' del sitio',
                    'render_callback'   => 'burger_render_blocks',
                    'category'          => 'layout',
                    'icon'              => '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="0" fill="none" width="20" height="20"></rect> <g> <path d="M15 6V4h-3v2H8V4H5v2H4c-.6 0-1 .4-1 1v8h14V7c0-.6-.4-1-1-1h-1z"></path> </g> </g></svg>',
                    'supports'          => array( 'align' => array( 'wide', 'full' ),'layout'  => true ),
                    'enqueue_assets'    => function() use ($slug) {

                        $style_path  = get_template_directory() . '/blocks/' . $slug . '/styles.css';
                        $script_path = get_template_directory() . '/blocks/' . $slug . '/scripts.js';

                        $style_url  = get_template_directory_uri() . '/blocks/' . $slug . '/styles.css';
                        $script_url = get_template_directory_uri() . '/blocks/' . $slug . '/scripts.js';

                        if (file_exists($style_path)) {
                            wp_enqueue_style(
                                'burger-block-' . $slug,
                                $style_url,
                                [],
                                filemtime($style_path)
                            );
                        }

                        if (file_exists($script_path)) {
                            wp_enqueue_script(
                                'burger-block-' . $slug,
                                $script_url,
                                ['jquery'],
                                filemtime($script_path),
                                true
                            );
                        }
                        
                    },
                ) );

            }

        }

    }

}

function burger_render_blocks( $block ) {

    $slug = str_replace( 'acf/', '', $block['name'] ?? '' );

    if ( empty( $slug ) ) {
        return;
    }

    $block_path  = BURGER_THEME_PATH . "/blocks/{$slug}";
    $content_png = $block_path . '/content.png';
    $content_php = $block_path . '/content.php';

    /*
     * Vista previa dentro del editor.
     */
    if ( 0 && is_admin() && file_exists( $content_png ) ) {

        $url = get_stylesheet_directory_uri() . "/blocks/{$slug}/content.png";

        echo '<div style="width:100%;">';
        echo '<img src="' . esc_url( $url ) . '" style="width:100%;height:auto;display:block;" />';
        echo '</div>';

        return;
    }

    if ( ! file_exists( $content_php ) ) {
        return;
    }

    $post_id = get_the_ID();

    /*
     * La caché solamente se permite en frontend público.
     */
    $cache_enabled = (
        ! is_admin()
        && ! is_user_logged_in()
        && ! wp_doing_ajax()
        && ! is_preview()
        && ! is_customize_preview()
        && ! wp_is_json_request()
        && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST )
        && ! empty( $post_id )
    );

    /*
     * Permite desactivar el caché por bloque.
     */
    $cache_enabled = apply_filters(
        'burger_block_cache_enabled',
        $cache_enabled,
        $block,
        $slug
    );

    $cache_time = defined( 'BURGER_BLOCK_CACHE_TIME' )
        ? (int) BURGER_BLOCK_CACHE_TIME
        : 10 * MINUTE_IN_SECONDS;

    /*
     * La fecha de modificación invalida automáticamente la caché
     * cuando se actualiza la página.
     */
    $post_modified = get_post_modified_time(
        'U',
        true,
        $post_id
    );

    /*
     * Datos relevantes para diferenciar cada bloque.
     */
    $cache_data = [
        'slug'          => $slug,
        'block_id'      => $block['id'] ?? '',
        'block_data'    => $block['data'] ?? [],
        'post_id'       => $post_id,
        'post_modified' => $post_modified,
        'locale'        => determine_locale(),
        'theme_version' => wp_get_theme()->get( 'Version' ),
    ];

    $cache_key = 'burger_block_v2_' . md5(
        maybe_serialize( $cache_data )
    );

    if ( $cache_enabled ) {

        $cached_html = get_transient( $cache_key );

        if (
            false !== $cached_html
            && burger_block_html_is_valid( $cached_html )
        ) {
            echo $cached_html;
            return;
        }

        /*
         * Si había una caché dañada, la eliminamos.
         */
        if ( false !== $cached_html ) {
            delete_transient( $cache_key );
        }
    }

    /*
    * Render normal del bloque.
    */
    ob_start();

    include $content_php;

    $html = ob_get_clean();

    /*
    * Solo se guarda si el HTML renderizado parece válido.
    */
    if (
        $cache_enabled
        && burger_block_html_is_valid( $html )
    ) {
        set_transient(
            $cache_key,
            $html,
            $cache_time
        );
    }

    echo $html;
}

function burger_block_html_is_valid( $html ) {

    if ( ! is_string( $html ) || trim( $html ) === '' ) {
        return false;
    }

    /*
     * Detecta codificación JSON sin barra invertida:
     * u003ch1u003e
     */
    if ( preg_match( '/u003[cCeE]/', $html ) ) {
        return false;
    }

    /*
     * Detecta codificación JSON todavía escapada:
     * \u003ch1\u003e
     */
    if ( preg_match( '/\\\\u003[cCeE]/', $html ) ) {
        return false;
    }

    /*
     * Detecta saltos de línea JSON impresos literalmente.
     */
    if (
        str_contains( $html, '\r\n' )
        || str_contains( $html, '\n' )
    ) {
        return false;
    }

    return true;
}

add_action( 'admin_init', function() {

    if (
        ! current_user_can( 'manage_options' )
        || ! isset( $_GET['burger_clear_block_cache'] )
    ) {
        return;
    }

    burger_flush_block_cache();

    wp_die( 'Caché de bloques eliminada.' );
});

function burger_flush_block_cache() {

    global $wpdb;

    $wpdb->query(
        "DELETE FROM {$wpdb->options}
        WHERE option_name LIKE '_transient_burger_block_%'
        OR option_name LIKE '_transient_timeout_burger_block_%'"
    );
}

/**
 * La cache_key ya incluye post_modified, así que guardar una página
 * "invalida" su propia caché al cambiar de key. Pero eso deja huérfanos
 * los transients viejos hasta que expiran solos, y no cubre el caso de
 * un bloque en una página A que cambia por una edición en la página B
 * (ej. un preset, o un post relacionado). Por eso acá se vacía todo
 * explícitamente ante cualquier guardado real (no autosave/revisión).
 */
add_action( 'save_post', function ( $post_id, $post, $update ) {

    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    burger_flush_block_cache();

}, 10, 3 );

/**
 * Respeta el toggle "Caché de bloques activa" de Opciones además de las
 * condiciones ya calculadas en burger_render_blocks() (front público,
 * sin preview, etc.).
 */
add_filter( 'burger_block_cache_enabled', function ( $enabled ) {
    return $enabled && (bool) get_field( 'burger_block_cache_enabled', 'option' );
});

/**
 * Agrega a la página de Opciones (Burger-Block / configuracion-general)
 * el toggle de caché y el botón para vaciarla, sin depender de que exista
 * en la configuración ya guardada en base (grupo de campos aparte).
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {

    acf_add_local_field_group([
        'key'      => 'group_burger_block_cache',
        'title'    => 'Caché de bloques',
        'fields'   => [
            [
                'key'           => 'field_burger_block_cache_enabled',
                'label'         => 'Caché de bloques activa',
                'name'          => 'burger_block_cache_enabled',
                'type'          => 'true_false',
                'instructions'  => 'Guarda en caché el HTML de cada bloque para visitantes anónimos del front (se invalida solo al editar la página). Recomendado activarlo en producción.',
                'default_value' => 0,
                'ui'            => 1,
            ],
            [
                'key'           => 'field_burger_clear_block_cache_now',
                'label'         => 'Vaciar caché de bloques ahora',
                'name'          => 'burger_clear_block_cache_now',
                'type'          => 'true_false',
                'instructions'  => 'Activá esta opción y guardá para borrar toda la caché de bloques ya generada. Se apaga sola después de guardar.',
                'default_value' => 0,
                'ui'            => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'configuracion-general',
                ],
            ],
        ],
        'position' => 'side',
        'style'    => 'default',
    ]);

}

/**
 * Config del Owl Carousel para el bloque Clientes, mismos campos que ya
 * existen en el bloque Conoce más (Items/Items Desktop/Tablet/Mobile,
 * Autoplay, Nav, Dots, Loop, Margen, Overflow), para poder configurarlo
 * desde el editor en vez de tenerlo fijo en el JS.
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {

    acf_add_local_field_group([
        'key'      => 'group_burger_clientes_owl',
        'title'    => 'Configuración del carrusel',
        'fields'   => [
            [
                'key'           => 'field_burger_clientes_items',
                'label'         => 'Items',
                'name'          => 'items',
                'type'          => 'number',
                'default_value' => 4,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_items_desktop',
                'label'         => 'Items Desktop',
                'name'          => 'items_desktop',
                'type'          => 'number',
                'default_value' => 3,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_items_tablet',
                'label'         => 'Items Tablet',
                'name'          => 'items_tablet',
                'type'          => 'number',
                'default_value' => 2,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_items_mobile',
                'label'         => 'Items Mobile',
                'name'          => 'items_mobile',
                'type'          => 'number',
                'default_value' => 4,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_autoplay',
                'label'         => 'Autoplay',
                'name'          => 'autoplay',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_nav',
                'label'         => 'Nav',
                'name'          => 'nav',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_dots',
                'label'         => 'Dots',
                'name'          => 'dots',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_loop',
                'label'         => 'Loop',
                'name'          => 'loop',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_burger_clientes_margen',
                'label'         => 'Margen',
                'name'          => 'margen',
                'type'          => 'number',
                'default_value' => 10,
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_burger_clientes_overflow',
                'label'         => 'Overflow',
                'name'          => 'overflow',
                'type'          => 'select',
                'choices'       => [ 'visible' => 'Visible', 'hidden' => 'Oculto' ],
                'default_value' => 'visible',
                'ui'            => 1,
                'wrapper'       => [ 'width' => '50' ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/clientes',
                ],
            ],
        ],
        'position' => 'normal',
        'style'    => 'default',
    ]);

}

/**
 * Config del Owl Carousel para el bloque Galería, modo "Carrusel"
 * (field_68595023f892f == carrusel). Mismos campos que Clientes/Conoce
 * más, visibles solo cuando se elige ese tipo de galería.
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {

    $galeria_owl_conditional_logic = [
        [
            [
                'field'    => 'field_68595023f892f',
                'operator' => '==',
                'value'    => 'carrusel',
            ],
        ],
    ];

    acf_add_local_field_group([
        'key'      => 'group_burger_galeria_owl',
        'title'    => 'Configuración del carrusel',
        'fields'   => [
            [
                'key'               => 'field_burger_galeria_items',
                'label'             => 'Items',
                'name'              => 'items',
                'type'              => 'number',
                'default_value'     => 4,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_items_desktop',
                'label'             => 'Items Desktop',
                'name'              => 'items_desktop',
                'type'              => 'number',
                'default_value'     => 3,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_items_tablet',
                'label'             => 'Items Tablet',
                'name'              => 'items_tablet',
                'type'              => 'number',
                'default_value'     => 2,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_items_mobile',
                'label'             => 'Items Mobile',
                'name'              => 'items_mobile',
                'type'              => 'number',
                'default_value'     => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_autoplay',
                'label'             => 'Autoplay',
                'name'              => 'autoplay',
                'type'              => 'true_false',
                'default_value'     => 0,
                'ui'                => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_nav',
                'label'             => 'Nav',
                'name'              => 'nav',
                'type'              => 'true_false',
                'default_value'     => 1,
                'ui'                => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_dots',
                'label'             => 'Dots',
                'name'              => 'dots',
                'type'              => 'true_false',
                'default_value'     => 0,
                'ui'                => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_loop',
                'label'             => 'Loop',
                'name'              => 'loop',
                'type'              => 'true_false',
                'default_value'     => 1,
                'ui'                => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '25' ],
            ],
            [
                'key'               => 'field_burger_galeria_margen',
                'label'             => 'Margen',
                'name'              => 'margen',
                'type'              => 'number',
                'default_value'     => 10,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '50' ],
            ],
            [
                'key'               => 'field_burger_galeria_overflow',
                'label'             => 'Overflow',
                'name'              => 'overflow',
                'type'              => 'select',
                'choices'           => [ 'visible' => 'Visible', 'hidden' => 'Oculto' ],
                'default_value'     => 'visible',
                'ui'                => 1,
                'conditional_logic' => $galeria_owl_conditional_logic,
                'wrapper'           => [ 'width' => '50' ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/galeria',
                ],
            ],
        ],
        'position' => 'normal',
        'style'    => 'default',
    ]);

}

add_action( 'acf/save_post', function ( $post_id ) {

    if ( $post_id !== 'options' ) {
        return;
    }

    if ( ! get_field( 'burger_clear_block_cache_now', 'option' ) ) {
        return;
    }

    burger_flush_block_cache();

    update_field( 'burger_clear_block_cache_now', 0, 'option' );
}, 20 );

/**
 * Carga en el editor de bloques el mismo stack de CSS que usa el front
 * (header.php), para que el preview del editor quede visualmente igual.
 */
function nakama_enqueue_editor_frontend_styles() {

    $vendor_files = [
        'vendors/bootstrap5.2/css/bootstrap.min.css',
        'vendors/font-awesome6/css/all.min.css',
        'vendors/fancybox/fancybox.css',
        'vendors/swiperjs/swiper-bundle.min.css',
        'vendors/aos/aos.css',
        'themes/css/fonts.css',
        'themes/css/colors.css',
        'themes/css/styler.css',
        'themes/css/buttons.css',
        'themes/css/editor.css',
    ];

    foreach ($vendor_files as $file) {
        $path = BURGER_THEME_PATH . '/' . $file;
        if (!file_exists($path)) {
            continue;
        }
        wp_enqueue_style(
            'nakama-editor-' . sanitize_title($file),
            BURGER_THEME_URL . '/' . $file,
            [],
            filemtime($path)
        );
    }

    if (!empty(BURGER_OPTIONS['url_font_family'])) {
        wp_enqueue_style('nakama-editor-fonts', BURGER_OPTIONS['url_font_family'], [], null);
    }

    wp_register_style( 'nakama-editor-overrides', false );
    wp_enqueue_style( 'nakama-editor-overrides' );
    wp_add_inline_style(
        'nakama-editor-overrides',
        '
        /* AOS (animate on scroll) nunca corre dentro del editor, así que
           los elementos [data-aos] quedarían con opacity:0 para siempre. */
        [data-aos] { opacity: 1 !important; transform: none !important; }

        /* El header usa position:fixed (.fixed-top) para quedar pegado
           arriba del viewport en el front. En el editor este WP no aísla
           el canvas en un iframe, así que ese fixed queda flotando sobre
           toda la pantalla de wp-admin y rompe el layout. Lo anclamos al
           flujo normal solo acá. */
        #fixedNav.fixed-top { position: relative !important; top: auto !important; }
        '
    );
}

add_action('enqueue_block_editor_assets', 'nakama_enqueue_editor_frontend_styles');

/**
 * Este WP no aísla el canvas del editor en un iframe, así que el esquema
 * de color de wp-admin (wp-admin/css/colors/{scheme}/colors.min.css) le
 * pisa reglas genéricas (ej. "a { color: ... }") al contenido de los
 * bloques. Se saca solo en la pantalla del editor de bloques; el resto
 * de wp-admin conserva el esquema de color del usuario normalmente.
 */
add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor()) {
        wp_dequeue_style('colors');
        wp_deregister_style('colors');
    }
}, 100);

function get_block_classes( $block = '') {

    $attributes = get_block_wrapper_attributes($block);
    preg_match('/class="([^"]+)"/', $attributes, $matches);

    return isset($matches[1]) ? $matches[1] : '';
}

function cargar_novedades() {

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $cat = isset($_POST['cat']) ? $_POST['cat'] : 0;
    $per_page = isset($_POST['per_page']) ? $_POST['per_page'] : 3;

    $query = new WP_Query(array(
        'posts_per_page' => $per_page,
        'paged' => $page,
        'cat' => $cat
    ));

    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

        <div class="col-lg-4">
            <div class="card card-novedades border-0 rounded-0 mb-5">
                <div class="card-header border-0 rounded-0 mb-4 img novedades-img position-relative" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');">
                    <a href="<?php echo get_the_permalink(); ?>" class="position-absolute w-100 h-100"></a>
                </div>
                <div class="card-body border-0 d-flex flex-column">
                    <div class="novedades-titulo">
                        <h3 class="mb-4 text-big light text-primary"><?php the_title(); ?></h3>
                    </div>
                    <div class="novedades-descripcion">
                        <p class="mb-4 text-normal text-dark"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <a href="<?php echo get_the_permalink(); ?>" class="text-normal text-primary text-uppercase text-decoration-underline">
                        Leer Más »
                    </a>
                </div>
            </div>
        </div>  

    <?php endwhile; wp_reset_postdata(); endif;

    wp_die();
}

add_action('wp_ajax_cargar_novedades', 'cargar_novedades');
add_action('wp_ajax_nopriv_cargar_novedades', 'cargar_novedades');

add_filter('acf/get_field_groups', function ($groups) {

    if (!is_admin()) return $groups;

    global $post;
    if (!$post) return $groups;

    $post_type = get_post_type($post);

    $options_map = [
        'post'      => 'campos_disponibles_post',
        'producto'  => 'campos_disponibles_producto',
        'servicio'  => 'campos_disponibles_servicio',
        'evento'    => 'campos_disponibles_evento',
    ];

    if (!isset($options_map[$post_type])) 
        return $groups;

    $repetidor = get_field($options_map[$post_type], 'option');
    $slugs_permitidos = array_filter(array_map(fn($item) => $item['title'] ?? null, (array) $repetidor));

    return array_filter( $groups, function ($group) use ( $slugs_permitidos ) {

        foreach ($slugs_permitidos as $slug) {
            if ( sanitize_title( $group['title'] ) == 'block-'.sanitize_title( $slug ) ) 
                return true; 
        }

        return false; 

    });

});

function get_block_content_fields($block, $fields = []) {

    if (!empty($block['data']['preset'])) {

        $source = resolve_block_data($block);

        if (!isset($source['encabezado']) || is_null($source['encabezado'])) {
            $source['encabezado'] = 'h2';
        }

        $result = [];

        foreach ($fields as $field) {
            $result[$field] = $source[$field] ?? '';
        }

        return $result;
    }

    $result = [];

    foreach ($fields as $field) {
        $result[$field] = get_field($field) ?? '';
    }

    if (
        in_array('encabezado', $fields, true) &&
        empty($result['encabezado'])
    ) {
        $result['encabezado'] = 'h2';
    }

    return $result;
}

/*
function get_block_content_fields( $block, $fields = [] ) {

    $source = resolve_block_data( $block );

    if( !isset($source['encabezado']) || is_null($source['encabezado']) ) $source['encabezado'] = 'h2';

    $result = [];
    foreach ( $fields as $field ) {
        $result[$field] = $source[$field] ?? '';
    }

    return $result;
}
*/

function resolve_block_data($block) {

    if (empty($block['name'])) {
        return [];
    }

    if (!empty($block['data']['preset'])) {

        $slug = str_replace('acf/', '', $block['name']);

        $preset_id = burger_get_selected_preset_id($block, $slug);

        if ( ! $preset_id ) {
            $preset_id = get_default_preset_id($slug);
        }

        if ($preset_id) {
            return get_fields($preset_id) ?: [];
        }

        if (current_user_can('administrator')) {
            echo '<div class="alert alert-warning my-3">';
            echo '<strong>Preset no encontrado:</strong> ';
            echo 'este bloque está configurado para usar preset, pero no existe un preset asignado para <code>' . esc_html($slug) . '</code>.';
            echo '</div>';
        }

        return [];
    }

    return get_fields() ?: [];
}

function get_block_values($slug, $block = []){

      $preset_id = burger_get_selected_preset_id($block, $slug);

      if ( ! $preset_id ) {
          $preset_id = get_default_preset_id($slug);
      }

      if( ! $preset_id ) return null;

      $preset_values = get_fields($preset_id);

      return $preset_values;
}

function burger_get_selected_preset_id( $block, $block_name ) {

    if ( empty( $block['data'] ) || ! is_array( $block['data'] ) || ! function_exists( 'acf_get_field' ) ) {
        return false;
    }

    foreach ( $block['data'] as $name => $value ) {
        if ( strpos( (string) $name, '_' ) === 0 || empty( $block['data'][ '_' . $name ] ) ) {
            continue;
        }

        $field = acf_get_field( $block['data'][ '_' . $name ] );

        if ( ! $field || ( $field['type'] ?? '' ) !== 'select_preset' ) {
            continue;
        }

        $preset_id = absint( $value );

        return burger_preset_matches_block( $preset_id, $block_name ) ? $preset_id : false;
    }

    return false;
}

function burger_preset_matches_block( $preset_id, $block_name ) {

    if ( ! $preset_id || get_post_type( $preset_id ) !== 'preset' || get_post_status( $preset_id ) !== 'publish' ) {
        return false;
    }

    $assigned_block = get_post_meta( $preset_id, 'grupo_acf_preset', true );

    if ( $assigned_block !== '' ) {
        return $assigned_block === $block_name;
    }

    $preset = get_post( $preset_id );

    return $preset && $preset->post_name === $block_name;
}

function get_default_preset_id($block_name) {

    $presets = get_posts([
        'post_type'      => 'preset',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => [
            [
                'key'   => 'grupo_acf_preset',
                'value' => $block_name,
            ]
        ],
    ]);

    if (!empty($presets[0])) {
        return $presets[0];
    }

    $preset = get_page_by_path($block_name, OBJECT, 'preset');

    if ($preset) {
        return $preset->ID;
    }

    return false;
}

function burger_option( $name, $fallback = '' ) {

    if ( defined( 'BURGER_OPTIONS' ) && isset( BURGER_OPTIONS[$name] ) && BURGER_OPTIONS[$name] !== '' && BURGER_OPTIONS[$name] !== null ) {
        return BURGER_OPTIONS[$name];
    }

    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $name, 'option' );

        if ( $value !== '' && $value !== null && $value !== false ) {
            return $value;
        }
    }

    return $fallback;
}

function burger_value_or_option( $value, $option_name, $fallback = '' ) {

    if ( $value !== '' && $value !== null && $value !== false ) {
        return $value;
    }

    return burger_option( $option_name, $fallback );
}

/**
 * Tipo de campo ACF que ofrece los estilos definidos en la configuración
 * global de botones, sin tener que repetir las opciones en cada field group.
 */
if ( class_exists( 'acf_field_select' ) && function_exists( 'acf_register_field_type' ) ) {

    class Burger_ACF_Field_Select_Button extends acf_field_select {

        public function initialize() {
            $this->name          = 'select_button';
            $this->label         = 'Selector de botón';
            $this->category      = 'choice';
            $this->description   = 'Selecciona un estilo configurado en Estilos de Botones.';
            $this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-select.png';
            $this->defaults      = [
                'multiple'      => 0,
                'allow_null'    => 0,
                'choices'       => [],
                'default_value' => '',
                'ui'            => 1,
                'ajax'          => 0,
                'placeholder'   => '',
                'return_format' => 'value',
            ];
        }

        public function load_field( $field ) {
            $field['choices']       = $this->get_button_choices();
            $field['multiple']      = 0;
            $field['ui']            = 1;
            $field['ajax']          = 0;
            $field['return_format'] = 'value';

            return $field;
        }

        public function render_field_settings( $field ) {
            acf_render_field_setting(
                $field,
                [
                    'label'        => 'Opciones',
                    'instructions' => 'Se cargan automáticamente desde Configuración General > Estilos de Botones.',
                    'name'         => '_button_choices_info',
                    'type'         => 'message',
                    'message'      => 'No es necesario cargar opciones manualmente.',
                ]
            );

            acf_render_field_setting(
                $field,
                [
                    'label'         => 'Valor predeterminado',
                    'name'          => 'default_value',
                    'type'          => 'select',
                    'choices'       => $this->get_button_choices(),
                    'allow_null'    => 1,
                    'return_format' => 'value',
                ]
            );
        }

        private function get_button_choices() {
            $styles  = function_exists( 'get_field' )
                ? get_field( 'icono_de_botones', 'option' )
                : [];
            $choices = [];

            if ( ! is_array( $styles ) ) {
                return $choices;
            }

            foreach ( $styles as $style ) {
                $type = trim( (string) ( $style['tipo'] ?? '' ) );

                if ( $type !== '' ) {
                    $choices[ $type ] = $type;
                }
            }

            return $choices;
        }
    }

    acf_register_field_type( 'Burger_ACF_Field_Select_Button' );

    class Burger_ACF_Field_Select_Preset extends acf_field_select {

        public function initialize() {
            $this->name          = 'select_preset';
            $this->label         = 'Selector de preset';
            $this->category      = 'choice';
            $this->description   = 'Selecciona un preset publicado correspondiente al bloque.';
            $this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-select.png';
            $this->defaults      = [
                'multiple'      => 0,
                'allow_null'    => 1,
                'choices'       => [],
                'default_value' => '',
                'ui'            => 1,
                'ajax'          => 0,
                'placeholder'   => 'Usar preset predeterminado',
                'return_format' => 'value',
            ];
        }

        public function load_field( $field ) {
            $field['choices']       = $this->get_preset_choices( $this->get_current_block_name( $field ) );
            $field['multiple']      = 0;
            $field['allow_null']    = 1;
            $field['ui']            = 1;
            $field['ajax']          = 0;
            $field['return_format'] = 'value';

            return $field;
        }

        public function render_field_settings( $field ) {
            acf_render_field_setting(
                $field,
                [
                    'label'        => 'Opciones',
                    'instructions' => 'Se cargan automáticamente con los presets publicados asignados al mismo bloque.',
                    'name'         => '_preset_choices_info',
                    'type'         => 'message',
                    'message'      => 'No es necesario cargar opciones manualmente.',
                ]
            );
        }

        public function get_preset_choices( $block_name ) {
            if ( ! $block_name ) {
                return [];
            }

            $preset_ids = get_posts([
                'post_type'      => 'preset',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
                'fields'         => 'ids',
                'meta_key'       => 'grupo_acf_preset',
                'meta_value'     => $block_name,
            ]);
            $choices = [];

            foreach ( $preset_ids as $preset_id ) {
                $choices[ $preset_id ] = get_the_title( $preset_id );
            }

            return $choices;
        }

        private function get_current_block_name( $field ) {
            if ( ! empty( $_REQUEST['block'] ) ) {
                $block = json_decode( wp_unslash( $_REQUEST['block'] ), true );

                if ( ! empty( $block['name'] ) ) {
                    return str_replace( 'acf/', '', $block['name'] );
                }
            }

            $parent = $field['parent'] ?? '';

            while ( $parent && function_exists( 'acf_get_field' ) ) {
                $parent_field = acf_get_field( $parent );

                if ( ! $parent_field ) {
                    break;
                }

                $parent = $parent_field['parent'] ?? '';
            }

            $group = $parent && function_exists( 'acf_get_field_group' )
                ? acf_get_field_group( $parent )
                : false;

            foreach ( (array) ( $group['location'] ?? [] ) as $rules ) {
                foreach ( (array) $rules as $rule ) {
                    if ( ( $rule['param'] ?? '' ) === 'block' && ( $rule['operator'] ?? '' ) === '==' ) {
                        return str_replace( 'acf/', '', $rule['value'] ?? '' );
                    }
                }
            }

            return '';
        }
    }

    acf_register_field_type( 'Burger_ACF_Field_Select_Preset' );
}
function get_burger_button( $boton, $estilo = '' ) {

    if (
        empty( $boton ) ||
        ! is_array( $boton ) ||
        empty( $boton['url'] )
    ) {
        return '';
    }

    $url = $boton['url'];
    $texto = $boton['title'] ?? '';
    $target = $boton['target'] ?? '_self';

    $clases = preg_split(
        '/\s+/',
        trim( (string) $estilo ),
        -1,
        PREG_SPLIT_NO_EMPTY
    );

    if ( ! in_array( 'btn', $clases, true ) ) {
        array_unshift( $clases, 'btn' );
    }

    $clases = array_unique( $clases );

    $config = (
        defined( 'BURGER_OPTIONS' ) &&
        isset( BURGER_OPTIONS['icono_de_botones'] ) &&
        is_array( BURGER_OPTIONS['icono_de_botones'] )
    )
        ? BURGER_OPTIONS['icono_de_botones']
        : [];

    $opciones = [];

    foreach ( $config as $item ) {

        $tipo = sanitize_title( $item['tipo'] ?? '' );

        if ( $tipo !== '' && in_array( $tipo, $clases, true ) ) {
            $opciones = $item;
            break;
        }
    }

    $icono_delante = $opciones['icono_delante'] ?? '';
    $icono_detras  = $opciones['icono_detras'] ?? '';

    $rel = $target === '_blank'
        ? ' rel="noopener noreferrer"'
        : '';

    return sprintf(
        '<a href="%1$s" class="%2$s" target="%3$s"%4$s>
            %5$s
            %6$s
            %7$s
        </a>',
        esc_url( $url ),
        esc_attr( implode( ' ', $clases ) ),
        esc_attr( $target ),
        $rel,
        $icono_delante,
        esc_html( $texto ),
        $icono_detras
    );

}

function get_burger_icon( $nombre, $clases = '' ) {

    if ( empty( $nombre ) ) {
        return '';
    }

    $config = (
        defined( 'BURGER_OPTIONS' ) &&
        isset( BURGER_OPTIONS['estilos_de_iconos'] ) &&
        is_array( BURGER_OPTIONS['estilos_de_iconos'] )
    )
        ? BURGER_OPTIONS['estilos_de_iconos']
        : [];

    foreach ( $config as $item ) {

        $item_nombre = sanitize_title( $item['nombre'] ?? '' );

        if ( $item_nombre !== sanitize_title( $nombre ) ) {
            continue;
        }

        $icono = $item['icono'] ?? '';

        if ( empty( $icono ) ) {
            return '';
        }

        $class = trim(
            $item_nombre . ' ' . $clases
        );

        return sprintf(
            '<span class="%1$s" aria-hidden="true">%2$s</span>',
            esc_attr( $class ),
            $icono
        );
    }

    return '';

}

/*
function burger_normalize_link( $link, $title = '' ) {

    if ( is_array( $link ) ) {
        return [
            'url'    => $link['url'] ?? '#',
            'title'  => $link['title'] ?? $title,
            'target' => $link['target'] ?? '_self',
        ];
    }

    return [
        'url'    => $link ?: '#',
        'title'  => $title,
        'target' => '_self',
    ];
}

function burger_get_button_icons( $classes = '', $type = '' ) {

    $button_config = [
        'icono_delante'    => '',
        'icono_detras'     => '',
        'color_fondo'      => '',
        'color_borde'      => '',
        'color_texto'      => '',
        'color_fondo_hover' => '',
        'color_borde_hover' => '',
        'color_texto_hover' => '',
    ];

    $config = burger_option( 'icono_de_botones', [] );

    if ( empty( $config ) || ! is_array( $config ) ) {
        return $button_config;
    }

    $classes = preg_split(
        '/\s+/',
        trim( (string) $classes ),
        -1,
        PREG_SPLIT_NO_EMPTY
    );

    $type = trim( (string) $type );

    foreach ( $config as $icon_button ) {

        if ( empty( $icon_button['tipo'] ) ) {
            continue;
        }

        $button_type = trim( (string) $icon_button['tipo'] );

        $matches_type  = $type !== '' && $type === $button_type;
        $matches_class = in_array( $button_type, $classes, true );

        if ( ! $matches_type && ! $matches_class ) {
            continue;
        }

        foreach ( array_keys( $button_config ) as $key ) {
            $button_config[ $key ] = $icon_button[ $key ] ?? '';
        }

        break;
    }

    return $button_config;
}

function burger_render_button( $link, $classes = 'btn btn-primario', $args = [] ) {

    $link      = burger_normalize_link( $link, $args['title'] ?? '' );
    $title     = $link['title'] ?: ( $args['label'] ?? '' );
    $icon_only = ! empty( $args['icon_only'] );

    if ( empty( $link['url'] ) ) {
        return '';
    }

    if ( ! $icon_only && empty( $title ) ) {
        return '';
    }

    $classes     = trim( $classes );
    $icon_type   = $args['icon_type'] ?? '';
    $icons       = burger_get_button_icons( $classes, $icon_type );
    $span_class  = $args['span_class'] ?? 'text-btn-01';
    $inner_class = $args['inner_class'] ?? 'd-flex align-items-center justify-content-start gap-2';
    $attrs       = $args['attrs'] ?? '';
    $target      = $link['target'] ?: '_self';
    $aria_label  = $args['aria_label'] ?? $title;

    if ( $icon_only && empty( $icons['before'] ) && empty( $icons['after'] ) && ! empty( $args['fallback_icon'] ) ) {
        $icons['after'] = $args['fallback_icon'];
    }

    ob_start();
    ?>
    <a href="<?= esc_url( $link['url'] ) ?>" class="<?= esc_attr( $classes ) ?>"<?= $title ? ' title="' . esc_attr( $title ) . '"' : '' ?> target="<?= esc_attr( $target ) ?>"<?= $aria_label ? ' aria-label="' . esc_attr( $aria_label ) . '"' : '' ?> <?= $attrs ?>>
        <?php if ( $icon_only ) : ?>
            <?= $icons['before'] ?><?= $icons['after'] ?>
        <?php else : ?>
            <span class="<?= esc_attr( $inner_class ) ?>">
                <?= $icons['before'] ?>
                <span class="<?= esc_attr( $span_class ) ?>"><?= esc_html( $title ) ?></span>
                <?= $icons['after'] ?>
            </span>
        <?php endif; ?>
    </a>
    <?php
    return trim( ob_get_clean() );
}

function burger_apply_button_icons( $html ) {

    $config = burger_option( 'icono_de_botones', [] );

    if ( empty( $config ) || ! is_array( $config ) ) {
        return $html;
    }

    return preg_replace_callback(
        "/<a\b([^>]*)>(.*?)<\/a>/is",
        function ( $matches ) {

            $attrs = $matches[1];
            $inner = $matches[2];

            if ( strpos( $attrs, 'data-burger-icons="off"' ) !== false || strpos( $attrs, "data-burger-icons='off'" ) !== false ) {
                return $matches[0];
            }

            if ( preg_match( '/data-burger-icons-applied=[\"\']1[\"\']/', $attrs ) ) {
                return $matches[0];
            }

            $classes = '';
            $type    = '';

            if ( preg_match( '/\bclass=["\']([^"\']+)["\']/', $attrs, $class_match ) ) {
                $classes = $class_match[1];
            }

            if ( preg_match( '/\bdata-burger-button-type=["\']([^"\']+)["\']/', $attrs, $type_match ) ) {
                $type = $type_match[1];
            }

            $is_button = false;

            if ( $type !== '' ) {
                $is_button = true;
            }

            if ( $classes !== '' && preg_match( '/(^|\s)(btn|btn-[^\s]+|.*-btn|button|boton)(\s|$)/', $classes ) ) {
                $is_button = true;
            }

            if ( ! $is_button ) {
                return $matches[0];
            }

            $icons = burger_get_button_icons( $classes, $type );

            if ( empty( $icons['icono_delante'] ) && empty( $icons['icono_detras'] ) ) {
                return $matches[0];
            }

            $icono_delante = $icons['icono_delante'] ?? '';
            $icono_detras  = $icons['icono_detras'] ?? '';

            if ( $replace || $plain_text === '' ) {
                $new_inner = $icono_delante . $icono_detras;
            } else {
                $new_inner = '<span class="d-inline-flex align-items-center justify-content-center gap-2">'
                    . $icono_delante
                    . $content_without_icons
                    . $icono_detras
                    . '</span>';
            }

            if ( strpos( $attrs, 'data-burger-icons-applied' ) === false ) {
                $attrs .= ' data-burger-icons-applied="1"';
            }

            return '<a' . $attrs . '>' . $new_inner . '</a>';
        },
        $html
    );
}
*/

function get_block_design( $block ) {

    $slug    = str_replace( 'acf/', '', $block['name'] );
    $presets = get_block_values( $slug, $block );

    $source = ! empty( $block['data']['preset_design'] )
        ? ( $presets ?: [] )
        : ( $block['data'] ?? [] );

    $imagen_fondo = $source['imagen_fondo'] ?? '';

    if ( $imagen_fondo && is_numeric( $imagen_fondo ) ) {
        $imagen_fondo = wp_get_attachment_image_url( $imagen_fondo, 'full' );
    }

    return [
        'col_container_class'       => burger_value_or_option( $source['col_container_class'] ?? '', 'col_container_class', 'col-lg-12' ),
        'col_md_container_class'    => burger_value_or_option( $source['col_md_container_class'] ?? '', 'col_md_container_class', 'col-md-12' ),
        'col_lg_container_class'    => burger_value_or_option( $source['col_lg_container_class'] ?? '', 'col_lg_container_class', 'col-12' ),
        'text_align_class'          => burger_value_or_option( $source['text_align_class'] ?? '', 'text_align_class', 'text-start' ),
        'class_container'           => burger_value_or_option( $source['class_container'] ?? '', 'class_container', 'container-fluid' ),
        'border_radius'             => burger_value_or_option( $source['border_radius'] ?? '', 'border_radius', '0 0 0 0' ),
        'section_padding'           => burger_value_or_option( $source['section_padding'] ?? '', 'section_padding', '6rem 0' ),
        'section_margin'            => burger_value_or_option( $source['section_margin'] ?? '', 'section_margin', '0 auto' ),
        'color_primario'            => burger_value_or_option( $source['color_primario'] ?? '', 'primary_color', 'var(--primary)' ),
        'color_secundario'          => burger_value_or_option( $source['color_secundario'] ?? '', 'secondary_color', 'var(--secondary)' ),
        'color_fondo'               => $source['color_fondo'] ?? '',
        'imagen_fondo'              => $imagen_fondo ?: '',
    ];
}

add_filter('acf/location/rule_types', function ($choices) {

    $choices['Burger']['burger_preset_group'] = 'Burger Preset Group';

    return $choices;
});

add_filter('acf/location/rule_values/burger_preset_group', function ($choices) {

    $choices = [];

    $blocks_path = defined('NAKAMA_THEME_PATH')
        ? NAKAMA_THEME_PATH . '/blocks/*'
        : get_template_directory() . '/blocks/*';

    $dirs = glob($blocks_path, GLOB_ONLYDIR);

    if (empty($dirs) || !is_array($dirs)) {
        return $choices;
    }

    foreach ($dirs as $dir) {

        $slug = basename($dir);

        if (empty($slug)) {
            continue;
        }

        $choices[$slug] = ucwords(str_replace('-', ' ', $slug));
    }

    return $choices;

});

add_filter('acf/location/rule_match/burger_preset_group', function ($match, $rule, $options) {

    $post_id = 0;

    if (!empty($options['post_id'])) {
        $post_id = (int) $options['post_id'];
    } elseif (!empty($_POST['post_id'])) {
        $post_id = (int) $_POST['post_id'];
    }

    if (!$post_id) {
        return false;
    }

    if (get_post_type($post_id) !== 'preset') {
        return false;
    }

    $selected_group = get_post_meta($post_id, 'grupo_acf_preset', true);

    if ($rule['operator'] === '==') {
        return $selected_group === $rule['value'];
    }

    if ($rule['operator'] === '!=') {
        return $selected_group !== $rule['value'];
    }

    return false;

}, 10, 3);

add_filter('acf/prepare_field/name=preset', function ($field) {

    $screen = get_current_screen();

    if ($screen && $screen->post_type === 'preset') {
        return false;
    }

    return $field;
});

if (!function_exists('burger_get_block_choices')) {

    function burger_get_block_choices() {

        $choices = [];

        $blocks_path = defined('NAKAMA_THEME_PATH')
            ? NAKAMA_THEME_PATH . '/blocks/*'
            : get_template_directory() . '/blocks/*';

        $dirs = glob($blocks_path, GLOB_ONLYDIR);

        if (empty($dirs) || !is_array($dirs)) {
            return $choices;
        }

        foreach ($dirs as $dir) {

            if (!file_exists($dir . '/content.php')) {
                continue;
            }

            $slug = basename($dir);

            if (empty($slug)) {
                continue;
            }

            $choices[$slug] = ucwords(str_replace('-', ' ', $slug));
        }

        asort($choices);

        return $choices;
    }
}

add_filter('acf/load_field/name=grupo_acf_preset', function ($field) {

    $field['choices'] = burger_get_block_choices();

    return $field;
});