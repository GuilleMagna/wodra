<?php
/** Carga de SVG sanitizados, opcional desde Configuración General. */
if ( ! defined( 'ABSPATH' ) ) exit;

spl_autoload_register( static function ( $class ) {
    $prefix = 'Wodra\\SvgSanitize\\';
    if ( 0 !== strpos( $class, $prefix ) ) return;
    $path = __DIR__ . '/../vendors/svg-sanitizer/src/' . str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ) . '.php';
    if ( is_file( $path ) ) require_once $path;
} );

function burger_svg_can_upload() {
    return burger_site_setting( 'svg_enabled', false ) && class_exists( 'DOMDocument' )
        && current_user_can( 'manage_options' ) && current_user_can( 'upload_files' );
}

add_filter( 'upload_mimes', static function ( $mimes ) {
    if ( burger_svg_can_upload() ) $mimes['svg'] = 'image/svg+xml';
    return $mimes;
} );

function burger_svg_sanitize_upload( $file ) {
    if ( ! burger_site_setting( 'svg_enabled', false ) || ! empty( $file['error'] ) ) return $file;
    $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
    if ( ! in_array( $ext, [ 'svg', 'svgz' ], true ) ) return $file;
    if ( 'svgz' === $ext || ! burger_svg_can_upload() ) {
        $file['error'] = 'La carga de SVG requiere permisos de administrador y un archivo .svg sin comprimir.';
        return $file;
    }
    // Leer como máximo 2 MB + 1 byte, sin confiar en el tamaño informado.
    $path = $file['tmp_name'];
    $dirty = is_readable( $path ) ? file_get_contents( $path, false, null, 0, 2 * 1024 * 1024 + 1 ) : false;
    if ( false === $dirty || strlen( $dirty ) > 2 * 1024 * 1024 ) {
        $file['error'] = 'No se pudo leer el SVG o supera el límite de 2 MB.';
        return $file;
    }
    try {
        $sanitizer = new \Wodra\SvgSanitize\Sanitizer();
        $sanitizer->removeRemoteReferences( true );
        $clean = $sanitizer->sanitize( $dirty );
    } catch ( \Throwable $error ) {
        $clean = false;
    }
    if ( ! is_string( $clean ) || '' === trim( $clean ) ) {
        $file['error'] = 'El archivo no es un SVG válido o no se puede sanitizar.';
        return $file;
    }
    if ( file_put_contents( $path, $clean ) !== strlen( $clean ) ) {
        $file['error'] = 'No se pudo guardar el SVG sanitizado.';
        return $file;
    }
    // Autorizar sólo este archivo y estos bytes durante la comprobación de MIME.
    $GLOBALS['burger_sanitized_svg'][ $path ] = hash( 'sha256', $clean );
    $file['type'] = 'image/svg+xml';
    $file['size'] = strlen( $clean );
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'burger_svg_sanitize_upload', PHP_INT_MAX );
add_filter( 'wp_handle_sideload_prefilter', 'burger_svg_sanitize_upload', PHP_INT_MAX );

add_filter( 'wp_check_filetype_and_ext', static function ( $data, $file, $filename, $mimes ) {
    if ( ! burger_site_setting( 'svg_enabled', false ) ) return $data;
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    if ( ! in_array( $ext, [ 'svg', 'svgz' ], true ) ) return $data;
    $allowed = wp_check_filetype( $filename, $mimes );
    $approved = $GLOBALS['burger_sanitized_svg'][ $file ] ?? '';
    $valid = 'svg' === $ext && burger_svg_can_upload() && 'image/svg+xml' === $allowed['type']
        && $approved && is_readable( $file ) && hash_equals( $approved, hash_file( 'sha256', $file ) );
    $data['ext'] = $valid ? 'svg' : false;
    $data['type'] = $valid ? 'image/svg+xml' : false;
    $data['proper_filename'] = false;
    return $data;
}, PHP_INT_MAX, 4 );

/** Dimensiones para la biblioteca y SVG existentes sin metadatos de imagen. */
function burger_svg_dimensions( $file ) {
    if ( ! class_exists( 'DOMDocument' ) || ! is_readable( $file ) ) return [ 0, 0 ];
    $xml = file_get_contents( $file, false, null, 0, 2 * 1024 * 1024 + 1 );
    if ( false === $xml || strlen( $xml ) > 2 * 1024 * 1024 || false !== strpos( $xml, "\0" ) || preg_match( '/<!DOCTYPE|<!ENTITY/i', $xml ) ) return [ 0, 0 ];
    $previous = libxml_use_internal_errors( true );
    try {
        $dom = new DOMDocument();
        if ( ! $dom->loadXML( $xml, LIBXML_NONET ) || 'svg' !== $dom->documentElement->localName ) return [ 0, 0 ];
        $svg = $dom->documentElement;
        $length = static function ( $value ) {
            return preg_match( '/^\s*(\d+(?:\.\d+)?)\s*(?:px)?\s*$/', $value, $match ) ? (float) $match[1] : 0;
        };
        $width = $length( $svg->getAttribute( 'width' ) );
        $height = $length( $svg->getAttribute( 'height' ) );
        $viewbox = preg_split( '/[\s,]+/', trim( $svg->getAttribute( 'viewBox' ) ) );
        if ( 4 === count( $viewbox ) && is_numeric( $viewbox[2] ) && is_numeric( $viewbox[3] ) && $viewbox[2] > 0 && $viewbox[3] > 0 ) {
            if ( ! $width && ! $height ) { $width = (float) $viewbox[2]; $height = (float) $viewbox[3]; }
            elseif ( ! $width ) $width = $height * $viewbox[2] / $viewbox[3];
            elseif ( ! $height ) $height = $width * $viewbox[3] / $viewbox[2];
        }
        return [ (int) min( 100000, round( $width ) ), (int) min( 100000, round( $height ) ) ];
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors( $previous );
    }
}

add_filter( 'wp_generate_attachment_metadata', static function ( $metadata, $id ) {
    if ( 'image/svg+xml' !== get_post_mime_type( $id ) ) return $metadata;
    $file = get_attached_file( $id );
    [ $width, $height ] = burger_svg_dimensions( $file );
    $metadata = is_array( $metadata ) ? $metadata : [];
    $metadata['width'] = $width;
    $metadata['height'] = $height;
    $metadata['file'] = _wp_relative_upload_path( $file );
    return $metadata;
}, 10, 2 );

add_filter( 'wp_prepare_attachment_for_js', static function ( $response, $attachment ) {
    if ( 'image/svg+xml' !== $attachment->post_mime_type ) return $response;
    [ $width, $height ] = burger_svg_dimensions( get_attached_file( $attachment->ID ) );
    $response['width'] = $width;
    $response['height'] = $height;
    $response['orientation'] = $width > $height ? 'landscape' : 'portrait';
    $response['sizes']['full'] = [ 'url' => $response['url'], 'width' => $width, 'height' => $height, 'orientation' => $response['orientation'] ];
    return $response;
}, 10, 2 );

add_filter( 'image_downsize', static function ( $image, $id, $size ) {
    if ( 'image/svg+xml' !== get_post_mime_type( $id ) ) return $image;
    [ $width, $height ] = burger_svg_dimensions( get_attached_file( $id ) );
    [ $width, $height ] = image_constrain_size_for_editor( $width, $height, $size );
    return [ wp_get_attachment_url( $id ), $width, $height, false ];
}, 10, 3 );
