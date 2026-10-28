<?php
/** Destino opcional de los formularios; no depende de slugs de un cliente. */
if ( ! defined( 'ABSPATH' ) ) exit;

// ACF ya se inicializó antes de cargar el theme en esta instalación.
if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( [
        'key' => 'group_burger_form_thanks',
        // Coincide con la lista de bloques habilitados del editor de entradas.
        'title' => 'Block Formulario',
        'fields' => [ [
            'key' => 'field_burger_form_thanks_page',
            'name' => 'pagina_gracias',
            'label' => 'Página de gracias',
            'type' => 'page_link',
            'post_type' => [ 'page' ],
            'allow_null' => 1,
            'multiple' => 0,
            'instructions' => 'Destino después de enviar correctamente. Vacío usa /gracias/. Las newsletters no redirigen.',
        ] ],
        'location' => [ [ [ 'param' => 'block', 'operator' => '==', 'value' => 'acf/formulario' ] ] ],
    ] );
}
