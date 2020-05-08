<?php

function ub_recommended_posts(  $posts_to_show = 3, $style = 'default') {

    $data = ub_get_recommended_dataset();

    if( count( $data ) >= $posts_to_show ) {

         foreach( $data as $item ) {
            $ids[] = $item[ 'id' ];
        }

        $args = array(
            'post__in' => $ids,
            'posts_per_page' => $posts_to_show,
        );

        $ub_recommended = new WP_Query( $args );

        if ( $ub_recommended->have_posts() ) : ?>
            <?php while ( $ub_recommended->have_posts() ) : $ub_recommended->the_post(); ?>

                <h2><?php the_title(); ?></h2>

            <?php endwhile; ?>
         
            <?php wp_reset_postdata(); ?>
         
        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif;

    } else {
        echo 'popular posts';
    }

}