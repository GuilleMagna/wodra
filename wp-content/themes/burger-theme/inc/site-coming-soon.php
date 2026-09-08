<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function burger_soon_can_bypass() {
    if ( current_user_can( 'manage_options' ) ) return true;
    $allowed = (array) burger_site_setting( 'soon_allowed_roles', [] );
    return is_user_logged_in() && (bool) array_intersect( $allowed, wp_get_current_user()->roles );
}

function burger_soon_is_preview() {
    return isset( $_GET['burger_soon_preview'] ) && '1' === $_GET['burger_soon_preview'] && current_user_can( 'manage_options' );
}

function burger_soon_blocks_request() {
    return (bool) burger_site_setting( 'soon_enabled', false ) && ! burger_soon_can_bypass();
}

function burger_soon_headers( $preview = false ) {
    if ( ! defined( 'DONOTCACHEPAGE' ) ) define( 'DONOTCACHEPAGE', true );
    nocache_headers();
    status_header( $preview ? 200 : 503 );
    header( 'X-Robots-Tag: noindex, nofollow', true );
    if ( ! $preview ) header( 'Retry-After: 3600', true );
}

add_action( 'template_redirect', static function () {
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) return;
    $preview = burger_soon_is_preview();
    if ( ! $preview && ! burger_soon_blocks_request() ) return;
    burger_soon_headers( $preview );
    header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset', 'UTF-8' ) );
    require __DIR__ . '/site-coming-soon-template.php';
    exit;
}, -100 );

// Evita exponer el contenido público por REST mientras el sitio está cerrado.
add_filter( 'rest_pre_dispatch', static function ( $result ) {
    if ( ! burger_soon_blocks_request() ) return $result;
    burger_soon_headers();
    return new WP_Error( 'burger_coming_soon', 'El sitio estará disponible próximamente.', [ 'status' => 503 ] );
}, 100 );

add_action( 'admin_bar_menu', static function ( $bar ) {
    if ( current_user_can( 'manage_options' ) && burger_site_setting( 'soon_enabled', false ) ) {
        $bar->add_node( [ 'id' => 'burger-coming-soon', 'title' => 'Próximamente activo', 'href' => admin_url( 'admin.php?page=configuracion-general' ) ] );
    }
}, 100 );
add_action( 'admin_notices', static function () {
    if ( ! current_user_can( 'manage_options' ) || ! burger_site_setting( 'soon_enabled', false ) ) return;
    echo '<div class="notice notice-warning"><p>Próximamente está activo: los visitantes no ven el sitio. <a href="' . esc_url( admin_url( 'admin.php?page=configuracion-general' ) ) . '">Configurar</a></p></div>';
} );
