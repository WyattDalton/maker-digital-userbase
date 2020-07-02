<?php

// Create basic engagement data column on posts listing page
add_filter( 'manage_posts_columns', 'ub_post_data_column' );
function ub_post_data_column( $columns ) {

    $comments = $columns[ 'comments' ];
    $date = $columns[ 'date'];
    unset( $columns['comments'] );
    unset( $columns['date'] );

    $columns['engagement'] = __( 'Engagement' );
    $columns[ 'comments' ] = $comments;
    $columns[ 'date' ] = $date;
    return $columns;
}

// Add total post engagement to engagement column
add_action( 'manage_posts_custom_column', 'add_engagement_data_to_column', 10, 2 );
function add_engagement_data_to_column( $column, $post_id )
{

    $meta = get_post_meta($post_id, 'ub_post_engagement', true);
    if( $meta ) {
        $total = $meta[ 'total' ];
    }
    
    if( $column == 'engagement' ) {

        if( $total ) {
            echo '<b>Total: ' . $total . '</b>';
        } else { 
            echo 'Total: 0';
        }
    }
}

// Add most popular with $segment to engagment column
