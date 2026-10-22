<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/** Sólo consulta archivos locales del theme o uploads; nunca descarga URLs. */
function burger_local_image_dimensions( $url ) {
    static $cache = [];
    if ( isset( $cache[ $url ] ) ) return $cache[ $url ];
    $cache[ $url ] = [];
    $image_url = wp_parse_url( $url );
    if ( ! is_array( $image_url ) || empty( $image_url['path'] ) ) return [];
    $uploads = wp_upload_dir();
    foreach ( [ [ BURGER_THEME_URL, BURGER_THEME_PATH ], [ $uploads['baseurl'], $uploads['basedir'] ] ] as [ $baseurl, $basedir ] ) {
        $base = wp_parse_url( $baseurl );
        $host = $image_url['host'] ?? wp_parse_url( home_url(), PHP_URL_HOST );
        if ( strtolower( $host ) !== strtolower( $base['host'] ?? '' ) ) continue;
        $prefix = rtrim( $base['path'] ?? '', '/' ) . '/';
        if ( 0 !== strpos( $image_url['path'], $prefix ) ) continue;
        $root = realpath( $basedir );
        $file = realpath( $basedir . '/' . rawurldecode( substr( $image_url['path'], strlen( $prefix ) ) ) );
        if ( ! $root || ! $file || 0 !== strpos( wp_normalize_path( $file ), wp_normalize_path( $root ) . '/' ) || ! is_file( $file ) ) continue;
        if ( 'svg' === strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) ) {
            $size = burger_svg_dimensions( $file );
        } else {
            $size = wp_getimagesize( $file );
        }
        if ( $size && $size[0] > 0 && $size[1] > 0 ) $cache[ $url ] = [ (int) $size[0], (int) $size[1] ];
        break;
    }
    return $cache[ $url ];
}

function burger_block_image_dimensions( $html ) {
    $tags = new WP_HTML_Tag_Processor( $html );
    while ( $tags->next_tag( 'IMG' ) ) {
        $width = $tags->get_attribute( 'width' );
        $height = $tags->get_attribute( 'height' );
        if ( $width && $height ) continue;
        $src = $tags->get_attribute( 'src' );
        if ( ! is_string( $src ) || '' === $src ) continue;
        $size = burger_local_image_dimensions( $src );
        if ( ! $size ) continue;
        if ( $width && ! $height && is_numeric( $width ) ) $size = [ (int) $width, (int) round( $width * $size[1] / $size[0] ) ];
        elseif ( $height && ! $width && is_numeric( $height ) ) $size = [ (int) round( $height * $size[0] / $size[1] ), (int) $height ];
        elseif ( $width || $height ) continue;
        $tags->set_attribute( 'width', $size[0] );
        $tags->set_attribute( 'height', $size[1] );
        $tags->add_class( 'burger-intrinsic-image' );
    }
    return $tags->get_updated_html();
}
