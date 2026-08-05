<?php get_header(); 

$post = get_post( BURGER_OPTIONS['template_archivos'] );
setup_postdata( $post ); 
@the_content();
wp_reset_postdata();
get_footer() ?>