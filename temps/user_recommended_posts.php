<?php

function ub_recommended_posts(  $posts_to_show, $style ) {

    
    $recommended = ub_get_recommended_dataset();
    $popular = ub_get_popular_dataset();

    if( is_array( $recommended ) && count( $recommended ) >= $posts_to_show ) {
        $data = $recommended;
        $class = 'recommended';
    } else {
        $data = $popular;
        $class = 'popular';
    }

    ?>

    <div class="ub-recommended-posts-container <?php echo $style; ?> <?php echo $class; ?>">

        <?php


        foreach( $data as $item ) {
            $ids[] = $item[ 'id' ];
        }

        $args = array(
            'post__in' => $ids,
            'posts_per_page' => $posts_to_show,
            'order' => 'DESC',
        );

        $ub_recommended = new WP_Query( $args );

        if ( $ub_recommended->have_posts() ) : ?>
            <?php while ( $ub_recommended->have_posts() ) : $ub_recommended->the_post(); ?>

                <article class="ub-recommended-post <?php if( ! has_post_thumbnail() ) { echo 'no-thumbnail'; } ?>">
                    <?php the_post_thumbnail(); ?>
                    <div class="content-cluster">
                        <h3><?php the_title(); ?></h3>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>">Read More</a>
                    </div>
                </article>

            <?php endwhile; ?>
            
            <?php wp_reset_postdata(); ?>
            
        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif;

        ?>

    </div>

    <?php

}