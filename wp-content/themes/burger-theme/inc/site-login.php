<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'login_enqueue_scripts', static function () {
    if ( ! burger_site_setting( 'login_enabled', false ) ) return;
    $background = burger_site_color( 'login_background_color', '#edf3fc' );
    $panel = burger_site_color( 'login_panel_color', '#ffffff' );
    $text = burger_site_color( 'login_text_color', '#25282a' );
    $button = burger_site_color( 'login_button_color', '#4b8dff' );
    $button_text = burger_site_color( 'login_button_text_color', '#ffffff' );
    $image = burger_site_image( 'login_background' );
    $image_css = $image ? burger_site_css_url( $image ) : 'none';
    $css = "body.login{background-color:{$background};background-image:{$image_css};background-size:cover;background-position:center;background-attachment:fixed;color:{$text};}
    body.login #login{box-sizing:border-box;width:min(420px,calc(100% - 32px));padding:32px 24px;margin:6vh auto 24px;background:{$panel};border-radius:24px;box-shadow:0 16px 60px #0002;}
    body.login #login form{margin-top:20px;padding:0;border:0;box-shadow:none;background:transparent;color:{$text};}
    body.login #login h1 a{max-width:100%;}
    body.login #login label,body.login #login #nav a,body.login #login #backtoblog a{color:{$text};}
    body.login #login .button-primary{background:{$button};border-color:{$button};color:{$button_text};text-shadow:none;box-shadow:none;border-radius:8px;}
    body.login #login .button-primary:hover{filter:brightness(.9);}
    body.login #login a:focus-visible,body.login #login button:focus-visible,body.login #login input:focus-visible{outline:2px solid {$button};outline-offset:3px;}
    body.login #login .burger-login-message{white-space:pre-line;margin:0 0 20px;}
    body.login #login #nav,body.login #login #backtoblog{padding:0;}
    @media(max-width:480px){body.login #login{margin-top:24px;padding:24px 20px;}}";
    $logo = burger_site_image( 'login_logo' );
    if ( $logo ) {
        $width = max( 80, min( 360, (int) burger_site_setting( 'login_logo_width', 220 ) ) );
        $logo_css = burger_site_css_url( $logo );
        $css .= "body.login #login h1 a{background-image:{$logo_css};background-size:contain;background-position:center;width:{$width}px;height:100px;}";
    }
    wp_register_style( 'burger-custom-login', false, [ 'login' ], null );
    wp_enqueue_style( 'burger-custom-login' );
    wp_add_inline_style( 'burger-custom-login', $css );
}, 100 );
add_filter( 'login_display_language_dropdown', static function ( $show ) {
    return burger_site_setting( 'login_enabled', false ) ? false : $show;
} );
add_filter( 'login_headerurl', static function ( $url ) {
    return burger_site_setting( 'login_enabled', false ) ? home_url( '/' ) : $url;
} );
add_filter( 'login_headertext', static function ( $text ) {
    return burger_site_setting( 'login_enabled', false ) ? get_bloginfo( 'name' ) : $text;
} );
add_filter( 'login_message', static function ( $message ) {
    if ( ! burger_site_setting( 'login_enabled', false ) ) return $message;
    $custom = burger_site_setting( 'login_message' );
    return $custom ? $message . '<p class="burger-login-message">' . esc_html( $custom ) . '</p>' : $message;
} );
