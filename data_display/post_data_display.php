<?php

// Create basic engagement data column on posts listing page
add_filter( 'manage_posts_columns', 'ub_post_data_column' );
function ub_post_data_column( $columns ) {

    $comments = $columns[ 'comments' ];
    $date = $columns[ 'date'];
    unset( $columns['comments'] );
    unset( $columns['date'] );

    $columns['engagement'] = __( 'User Engagement' );
    $columns[ 'comments' ] = $comments;
    $columns[ 'date' ] = $date;
    return $columns;
}

// Add total post engagement to engagement column
add_action( 'manage_posts_custom_column', 'add_engagement_data_to_column', 10, 2 );
function add_engagement_data_to_column( $column, $post_id )
{
    // Get total engagement value for post
    $meta = get_post_meta($post_id, 'ub_post_engagement', true);
    if( $meta ) {
        $total = $meta[ 'total' ];
    }

    // Get the segment that has the highest engagement value for the post
    if( ! empty( $meta[ 'segments' ] ) ) {
        $segments = $meta[ 'segments' ];
        $segTotals = [];
        foreach( $segments as $seg => $val ) {
            $segTotal = array_sum( $val );
            $segTotals[$seg] = $segTotal;
        }
        krsort( $segTotals );

        $slice = reset( $segTotals );
        $mostPopularSeg = key( $segTotals );
    }
    
    // Output markup
    if( $column == 'engagement' ) {
        if( $total ) {
            echo '<b>Total Engagement: ' . $total . '</b></br>';
            if( $mostPopularSeg ) {
                echo 'Most popular with the <b>' . $mostPopularSeg . '</b> segment (' . $slice . ' of ' . $total . ')';
            }
        } else { 
            echo 'Not enough data';
        }
    }
}
