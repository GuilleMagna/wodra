<?php

function dump( $var, $stop = true ){
  
	echo '<pre>';
	print_r( $var ); 
	echo '</pre>';  
	
	if( $stop ) die(); 
	
}

function cortar_texto($texto, $limite = 65) {
    if (strlen($texto) > $limite) {
        return substr($texto, 0, $limite) . "...";
    } else {
        return $texto;
    }
}

function cortar_titulo($titulo, $largo = 52) {

    if (mb_strlen($titulo) > $largo) {
        return mb_substr($titulo, 0, $largo - 3) . '&#8230;';
    }
    return $titulo;

}

function disable_plugin_updates( $value ) {
    unset( $value->response["advanced-custom-fields-pro/acf.php"] );
    return $value;	
}

add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

function auditar_bloques_por_post_type($post_types = ['page', 'template']) {

    $resultados = [];

    $posts = get_posts([
        'post_type'      => $post_types,
        'numberposts'    => -1,
        'post_status'    => 'publish', // O 'any' si querés borradores también
    ]);

    foreach ($posts as $post) {
        $bloques = parse_blocks($post->post_content);
        $lista_bloques = [];

        foreach ($bloques as $bloque) {
            if (!empty($bloque['blockName'])) {
                $lista_bloques[] = $bloque['blockName'];
            }
        }

        $resultados[] = [
            'ID'     => $post->ID,
            'titulo'=> get_the_title($post),
            'tipo'   => $post->post_type,
            'bloques'=> array_unique($lista_bloques),
        ];
    }

    // Mostrar resultados
    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Título</th><th>Post Type</th><th>Bloques usados</th></tr>";
    foreach ($resultados as $item) {
        echo "<tr>";
        echo "<td>{$item['ID']}</td>";
        echo "<td>{$item['titulo']}</td>";
        echo "<td>{$item['tipo']}</td>";
        echo "<td><ul>";
        foreach ($item['bloques'] as $bloque) {
            $bloque = str_replace( 'acf/', '', $bloque ); 
            echo "<li>$bloque</li>";
        }
        echo "</ul></td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>