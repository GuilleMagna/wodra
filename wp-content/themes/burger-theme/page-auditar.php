<?php /* Template name: Auditar*/ get_header(); 

if( have_posts() ): while( have_posts() ): the_post(); ?>

    <h1>
        <?php the_title() ?>
    </h1>

    <?php the_content();
    auditar_bloques_por_post_type( ['page', 'template'] );

endwhile; endif;

get_footer() ?>

