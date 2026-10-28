<?php
/** Regresión de los parches: solo lectura, sin enviar correos ni modificar entradas. */
if ( PHP_SAPI !== 'cli' ) exit;
$GLOBALS['wp_filter']['option_active_plugins'][1][] = [
    'function' => static fn( $plugins ) => array_values( array_filter( $plugins, static fn( $plugin ) => str_contains( $plugin, 'advanced-custom-fields' ) || str_contains( $plugin, 'secure-custom-fields' ) ) ),
    'accepted_args' => 1,
];
require dirname( __DIR__, 4 ) . '/wp-load.php';
function verify_blitz_patch( $ok, $label ) {
    if ( ! $ok ) throw new RuntimeException( $label );
    echo "OK: $label\n";
}
$markup = '<p><span class="wpcf7-form-control-wrap" data-name="email"><input name="email"></span><svg><path d="M0 0"></path></svg></p><pre>Texto</pre>';
$filtered = apply_filters( 'wpcf7_form_elements', $markup );
verify_blitz_patch( ! str_contains( $filtered, '<p>' ) && str_contains( $filtered, '<span class=' ) && str_contains( $filtered, '<path ' ) && str_contains( $filtered, '<pre>' ), 'CF7 conserva contenedores, SVG y pre; elimina párrafos' );
verify_blitz_patch( ! function_exists( 'custom_wp_mail_message' ), 'No existe el envoltorio global de correo heredado' );
verify_blitz_patch( ! function_exists( 'verify_recaptcha' ) && ! function_exists( 'my_enqueue_recaptcha_script' ) && ! function_exists( 'enviar_consulta' ), 'Sin envío ni clave reCAPTCHA heredados' );
verify_blitz_patch( ( acf_get_field( 'field_burger_form_thanks_page' )['type'] ?? '' ) === 'page_link', 'Destino de gracias registrado directamente en ACF' );
foreach ( [ '+54 9 341 155-123456', '0341 15 5123456', '3415123456', '005493415123456' ] as $phone ) {
    verify_blitz_patch( 'https://wa.me/5493415123456' === burger_whatsapp_url( $phone ), 'Normaliza ' . $phone );
}
foreach ( [ '', 'abc', '12345678', '+1 212 555 1234', '34151234567890', '3415123456 int 3', '00113415123456' ] as $phone ) {
    verify_blitz_patch( '' === burger_whatsapp_url( $phone ), 'Rechaza número inválido o incompleto: ' . $phone );
}
foreach ( [ 396, 802, 1033, 11811 ] as $form_id ) {
    verify_blitz_patch( 'wpcf7_contact_form' === get_post_type( $form_id ), 'Existe formulario local ' . $form_id );
}
verify_blitz_patch( 'page' === get_post_type( 10155 ), 'Existe página Contacto usada por los selectores' );
