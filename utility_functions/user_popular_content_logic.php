<?php

/**
 * 
 * Tracks popular posts with total engagement values, and engagement values by type.
 *
 * @param string  $valuetoupdate  Meta value type 
 * @param int     $incBy          Amount by wich to increase meta value
 * @param int     $postId         (optional) If post ID isn't avalable for hook, provide here
 * 
 * @return function updates meta for ID'd user, using ub_get_user_info(), with value and incBy 
 */

function ub_post_meta_to_update( $valueToUpdate = '', $incBy = 1, $postId = null ) {

	// set up variables
	if( null == $postId && 'post' == get_post_type() ) {
		$postId = get_the_id();
	}
	$metaKey = 'ub_post_engagement';
	$meta = get_post_meta( $postId, $metaKey, true );

	// check if meta already exists for post
	$oldData = $meta;

	// If $valueToUpdate already exists for this post, add it to $incBy
	/* Note: Can this by refactored to use the simplified code used to track user segment engagement? */
	if( 'views' == $valueToUpdate ) {
		if( $oldData ) {
			$views    = $oldData[ 'views' ] + $incBy;
			$comments = $oldData[ 'comments' ];
		} else {
			$views = $incBy;
		}
	} elseif ( 'comments' == $valueToUpdate ) {
		if( $oldData ) {
			$views    = $oldData[ 'views' ];
			$comments = $oldData[ 'comments' ] + $incBy;
		} else {
			$comments = $incBy;
		}
	} else {
		return null;
	}

	// Get current user segment if it exists, and run the code
	$segment = ub_get_user_info()[ 'segment' ];

	// Initiate $segmentData variable
	if( is_array( $meta[ 'segments' ] ) ) {
		$segmentData = $meta[ 'segments' ];
	} else {
		$segmentData = [];
	}

	// Add $incBy to $valueToUpdate for the user segment
	if( null != $segment ) {
		$segmentData[ $segment ][ $valueToUpdate ] += $incBy;
	}
	
	// Create array with new values
	$newData = array(
		'views'    => $views,
		'comments' => $comments,
		'total'    => $views + $comments,
		'segments' => $segmentData,
	);

	// If old data exists, combine with new data
	if ( is_array( $meta ) ) {
		$newData = $newData + $meta;
	}

	// Update user meta with new data
	update_post_meta( $postId, $metaKey, $newData );

}

// Update post engagement on view
add_action( 'wp_head', 'ub_update_post_meta_view' );
function ub_update_post_meta_view() {

	$default = (int) get_option( 'ub_settings' )[ 'default_engagement' ][ 'post_view' ];

	if( is_single() && 'post' == get_post_type() ) {
		ub_post_meta_to_update( 'views', $default );
	}

	
	
}

// Update post engagement on comment
add_action( 'comment_post', 'ub_update_post_meta_comment', 10, 3 );
function ub_update_post_meta_comment( $comment_ID, $comment_approved, $commentdata ) {

	$data = $commentdata;
	$postId = (int) $data[ 'comment_post_ID' ];

	$default = (int) get_option( 'ub_settings' )[ 'default_engagement' ][ 'post_comment' ];

	ub_post_meta_to_update( 'comments', $default, $postId );
	
}



/**
 * 
 * Retrieves a list of post ids, filtered in decending order, by total engagement
 * 
 * 
 * @return array an array containing popular post ids and total engagement   
 */
function ub_get_popular_dataset() {

	// Get posts that match tags and categories, but are not part of the dataset.
	$PopularPostsArgs = array(
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key' => 'ub_post_engagement',
				'value' => 0,
				'compare' => '>',
			)
		),
	);

	$popular_posts = [];
	$PopularPosts = new WP_Query( $PopularPostsArgs );
	while ( $PopularPosts->have_posts() ) : $PopularPosts->the_post();

		$currentPostEngagement = get_post_meta( get_the_id(), 'ub_post_engagement', true )[ 'total' ];

		// Add post to reccomended posts array with id and engagement value
		$popular_posts[] = array( 'id' => get_the_ID(), 'total' => $currentPostEngagement, 'name' => get_the_title(), );

	endwhile;
	wp_reset_query();

	// Sort $popular_posts from most total engagement to lowest
	usort( $popular_posts, function ( $a, $b ) {
		return $b[ 'total' ] <=> $a[ 'total' ];
	});

	// Limit popular posts to $limit
	$popular = [];
	for( $i = 0; $i < 10; $i++ ) {
		$popular[ $i ] = $popular_posts[ $i ];
	}

	// Return array
	return $popular;

}