<?php
/** Funciones administrables desde Configuración General. */
if ( ! defined( 'ABSPATH' ) ) exit;

function burger_site_setting( $name, $default = '' ) {
    // Lectura directa: los switches nuevos no necesitan defaults grabados en la BD.
    $value = get_option( 'options_burger_' . $name, null );
    return null === $value || '' === $value ? $default : $value;
}

function burger_site_image( $name ) {
    $value = burger_site_setting( $name );
    if ( is_array( $value ) ) $value = $value['ID'] ?? ( $value['url'] ?? '' );
    return is_numeric( $value ) ? ( wp_get_attachment_image_url( (int) $value, 'full' ) ?: '' ) : esc_url_raw( (string) $value );
}

function burger_site_color( $name, $default ) {
    return sanitize_hex_color( (string) burger_site_setting( $name, $default ) ) ?: $default;
}

function burger_site_css_url( $url ) {
    // Sólo URLs de imágenes: escapar también para el contexto CSS de un style inline.
    return 'url("' . str_replace( [ '\\', '"', '<', '>', "\r", "\n" ], [ '%5C', '%22', '%3C', '%3E', '', '' ], esc_url_raw( $url ) ) . '")';
}

function burger_site_control_fields() {
    $fields = [];
    $add = static function ( $name, $label, $type, $extra = [] ) use ( &$fields ) {
        $fields[] = array_merge( [ 'key' => 'field_burger_' . $name, 'name' => 'burger_' . $name, 'label' => $label, 'type' => $type ], $extra );
    };
    $tab = static function ( $name, $label ) use ( $add ) {
        $add( $name . '_tab', $label, 'tab', [ 'name' => '', 'placement' => 'top', 'endpoint' => 0 ] );
    };
    $switch = static function ( $name, $label, $instructions ) use ( $add ) {
        $add( $name, $label, 'true_false', [ 'ui' => 1, 'default_value' => 0, 'instructions' => $instructions ] );
    };
    $image = static function ( $name, $label ) use ( $add ) {
        $add( $name, $label, 'image', [ 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ] );
    };
    $color = static function ( $name, $label, $default ) use ( $add ) {
        $add( $name, $label, 'color_picker', [ 'default_value' => $default, 'wrapper' => [ 'width' => '33' ] ] );
    };

    $tab( 'comments', 'Comentarios' );
    $switch( 'comments_disabled', 'Desactivar comentarios', 'Cierra comentarios, pingbacks y trackbacks en todos los tipos de contenido; oculta los existentes sin borrarlos. Al desactivarlo se recupera la configuración anterior. Reemplaza Disable Comments cuando retires ese plugin.' );

    $tab( 'login', 'Custom Login' );
    $switch( 'login_enabled', 'Personalizar el acceso', 'Personaliza el formulario de WordPress, incluida la recuperación de contraseña. Configurá y guardá el diseño antes de retirar Custom Login. La dirección de acceso sigue siendo la misma.' );
    $image( 'login_logo', 'Logo del acceso' );
    $add( 'login_logo_width', 'Ancho del logo (px)', 'number', [ 'default_value' => 220, 'min' => 80, 'max' => 360, 'step' => 1 ] );
    $image( 'login_background', 'Imagen de fondo del acceso' );
    $color( 'login_background_color', 'Color de fondo', '#edf3fc' );
    $color( 'login_panel_color', 'Fondo del formulario', '#ffffff' );
    $color( 'login_text_color', 'Color del texto', '#25282a' );
    $color( 'login_button_color', 'Color del botón', '#4b8dff' );
    $color( 'login_button_text_color', 'Texto del botón', '#ffffff' );
    $add( 'login_message', 'Mensaje sobre el formulario', 'textarea', [ 'rows' => 3, 'instructions' => 'Opcional. Texto simple.' ] );
    $add( 'login_preview', 'Vista previa del acceso', 'message', [ 'name' => '', 'message' => 'Guardá los cambios y abrí <a href="' . esc_url( wp_login_url() ) . '" target="_blank" rel="noopener">la pantalla de acceso</a>. Para revisar el diseño del theme, activá esta opción y desactivá el plugin Custom Login.', 'esc_html' => 0 ] );

    $tab( 'soon', 'Próximamente' );
    $switch( 'soon_enabled', 'Activar página de próximamente', 'Los visitantes verán esta pantalla en todo el sitio. Los administradores siempre pueden ver el sitio y acceder al panel. Si usás caché de página o CDN, vaciala al activar o desactivar este modo.' );
    $add( 'soon_title', 'Título', 'text', [ 'default_value' => 'Algo nuevo está llegando.' ] );
    $add( 'soon_message', 'Mensaje', 'textarea', [ 'rows' => 4, 'default_value' => 'Mientras tanto, podés contactarnos y encontrarnos en:' ] );
    $image( 'soon_logo', 'Logo' );
    $add( 'soon_logo_width', 'Ancho del logo (px)', 'number', [ 'default_value' => 310, 'min' => 80, 'max' => 600, 'step' => 1 ] );
    $image( 'soon_background', 'Imagen de fondo' );
    $add( 'soon_overlay', 'Oscurecer el fondo (%)', 'number', [ 'default_value' => 20, 'min' => 0, 'max' => 90, 'step' => 5, 'instructions' => 'Aumentá el valor para mejorar el contraste del texto sobre la foto.' ] );
    $color( 'soon_background_color', 'Color de fondo', '#25201c' );
    $color( 'soon_text_color', 'Color del texto y redes', '#ffffff' );
    $color( 'soon_button_color', 'Color del botón', '#4b8dff' );
    $color( 'soon_button_text_color', 'Texto del botón', '#ffffff' );
    $add( 'soon_button_label', 'Texto del botón', 'text', [ 'instructions' => 'Opcional. Se muestra sólo si también indicás un enlace.' ] );
    $add( 'soon_button_url', 'Enlace del botón', 'url' );
    $add( 'soon_email', 'Email de contacto', 'email' );
    $add( 'soon_show_socials', 'Mostrar redes sociales', 'true_false', [ 'ui' => 1, 'default_value' => 1, 'instructions' => 'Usa el WhatsApp y los enlaces de Redes del configurador general. Sólo se muestran las redes con enlace.' ] );
    $roles = [];
    foreach ( wp_roles()->roles as $key => $role ) {
        if ( 'administrator' !== $key ) $roles[ $key ] = translate_user_role( $role['name'] );
    }
    $add( 'soon_allowed_roles', 'Otros roles que pueden ver el sitio', 'checkbox', [ 'choices' => $roles, 'default_value' => [], 'instructions' => 'El resto de usuarios verá Próximamente incluso con sesión iniciada. Los administradores siempre tienen acceso.', 'return_format' => 'value' ] );
    $add( 'soon_preview', 'Vista previa', 'message', [ 'name' => '', 'message' => 'Guardá primero los cambios. <a href="' . esc_url( add_query_arg( 'burger_soon_preview', '1', home_url( '/' ) ) ) . '" target="_blank" rel="noopener">Ver Próximamente sin activarlo para los visitantes</a>. Sólo disponible para administradores.', 'esc_html' => 0 ] );
    return $fields;
}

// Como Estructura fija: keys locales bajo padre virtual, campos anexados al grupo real.
// No usar acf/init: en esta instalación ya ocurrió antes de cargar el theme.
if ( function_exists( 'acf_add_local_fields' ) ) {
    $burger_control_fields = burger_site_control_fields();
    foreach ( $burger_control_fields as &$burger_control_field ) $burger_control_field['parent'] = 'group_burger_site_controls_virtual';
    unset( $burger_control_field );
    acf_add_local_fields( $burger_control_fields );
    unset( $burger_control_fields );
}
add_filter( 'acf/load_fields', function ( $fields, $parent ) {
    if ( ( $parent['key'] ?? '' ) !== 'group_66ec98973649f' ) return $fields;
    $existing = array_column( (array) $fields, 'key' );
    foreach ( burger_site_control_fields() as $field ) {
        if ( ! in_array( $field['key'], $existing, true ) ) {
            $field['parent'] = $parent['key'];
            // acf_validate_field() no agrega el prefijo de acf_get_field().
            // Sin él, estos inputs quedan fuera de $_POST['acf'] y no se guardan.
            $field['prefix'] = 'acf';
            $fields[] = acf_validate_field( $field );
        }
    }
    return $fields;
}, 30, 2 );

require_once __DIR__ . '/site-comments.php';
require_once __DIR__ . '/site-login.php';
require_once __DIR__ . '/site-coming-soon.php';
