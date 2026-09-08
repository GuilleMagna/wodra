<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function burger_comments_disabled() {
    return (bool) burger_site_setting( 'comments_disabled', false );
}

foreach ( [ 'comments_open', 'pings_open' ] as $burger_comment_hook ) {
    add_filter( $burger_comment_hook, static function ( $open ) {
        return burger_comments_disabled() ? false : $open;
    }, 100 );
}
unset( $burger_comment_hook );
add_filter( 'comments_array', static function ( $comments ) {
    return burger_comments_disabled() ? [] : $comments;
}, 100 );
add_filter( 'get_comments_number', static function ( $count ) {
    return burger_comments_disabled() ? 0 : $count;
}, 100 );
add_filter( 'pre_option_default_comment_status', static function ( $value ) {
    return burger_comments_disabled() ? 'closed' : $value;
} );
add_filter( 'pre_option_default_ping_status', static function ( $value ) {
    return burger_comments_disabled() ? 'closed' : $value;
} );
add_action( 'init', static function () {
    if ( ! burger_comments_disabled() ) return;
    foreach ( get_post_types() as $type ) {
        remove_post_type_support( $type, 'comments' );
        remove_post_type_support( $type, 'trackbacks' );
    }
}, 100 );
add_filter( 'feed_links_show_comments_feed', static function ( $show ) {
    return burger_comments_disabled() ? false : $show;
} );
add_filter( 'wp_headers', static function ( $headers ) {
    if ( burger_comments_disabled() ) unset( $headers['X-Pingback'] );
    return $headers;
} );
add_filter( 'xmlrpc_methods', static function ( $methods ) {
    if ( burger_comments_disabled() ) {
        foreach ( array_keys( $methods ) as $method ) {
            if ( str_starts_with( $method, 'pingback.' ) || preg_match( '/^wp\.(?:new|edit|delete|get)Comment/', $method ) ) unset( $methods[ $method ] );
        }
    }
    return $methods;
} );
add_filter( 'rest_pre_dispatch', static function ( $result, $server, $request ) {
    if ( burger_comments_disabled() && preg_match( '#^/wp/v2/comments(?:/|$)#', $request->get_route() ) ) {
        return new WP_Error( 'burger_comments_disabled', 'Los comentarios están desactivados.', [ 'status' => 403 ] );
    }
    return $result;
}, 20, 3 );
add_action( 'template_redirect', static function () {
    if ( burger_comments_disabled() && is_comment_feed() ) {
        wp_die( 'Los comentarios están desactivados.', 'Comentarios desactivados', [ 'response' => 403 ] );
    }
}, -90 );
add_action( 'admin_menu', static function () {
    if ( ! burger_comments_disabled() ) return;
    remove_menu_page( 'edit-comments.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}, 100 );
add_action( 'admin_bar_menu', static function ( $bar ) {
    if ( burger_comments_disabled() ) $bar->remove_node( 'comments' );
}, 100 );
add_action( 'wp_dashboard_setup', static function () {
    if ( burger_comments_disabled() ) remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
} );
add_action( 'admin_init', static function () {
    if ( burger_comments_disabled() && in_array( $GLOBALS['pagenow'] ?? '', [ 'edit-comments.php', 'comment.php', 'options-discussion.php' ], true ) && ! wp_doing_ajax() ) {
        wp_safe_redirect( admin_url( 'admin.php?page=configuracion-general' ) );
        exit;
    }
} );

// Cubre también Actividad del escritorio y consultas de comentarios de widgets.
add_filter( 'comments_pre_query', static function ( $comments, $query ) {
    if ( ! burger_comments_disabled() ) return $comments;
    return ! empty( $query->query_vars['count'] ) ? 0 : [];
}, 100, 2 );
add_filter( 'wp_count_comments', static function ( $counts ) {
    if ( ! burger_comments_disabled() ) return $counts;
    return (object) array_fill_keys( [ 'approved', 'moderated', 'spam', 'trash', 'post-trashed', 'total_comments', 'all' ], 0 );
}, 100 );
