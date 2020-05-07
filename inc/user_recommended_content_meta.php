<?php

function ub_meta_to_update( $valueToUpdate = '', $incBy = 1, $postId = null ) {

	// set up variables
	$user = ub_get_user_info();
	$metaKey = 'recommended_posts_data';
	$meta = get_user_meta( $user[ 'id' ], $metaKey, true );

	if( null == $postId && 'post' == get_post_type() ) {
		$postId = get_the_id();
	}


	if( $user[ 'id' ] ) {

		// check if meta already exists for post
		$oldData = $meta[ $postId ];

		// If $valueToUpdate already exists for this post, add it to $incBy
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
		

		// Create array with new values
		$newData = array(
			strval( $postId ) => array(
				'views'    => $views,
				'comments' => $comments,
				'total'    => $views + $comments,
			)
		);

		// If there are 20 posts in dataset, remove last post before adding current post
		if( count( $meta ) > 20 ) {
			array_pop( $meta );
		}

		// If old data exists, combine with new data
		if ( is_array( $meta ) ) {
			$newData = $newData + $meta;
		}

		// Sort data from most total engagement to lowest
		uasort( $newData, function ( $a, $b ) {
			return $b[ 'total' ] <=> $a[ 'total' ];
		});

		

		// Update user meta with new data
		update_user_meta( $user[ 'id' ], $metaKey, $newData );
		
		
	}

}

// Update post engagement on view
add_action( 'template_redirect', 'ub_update_user_post_meta_view' );
function ub_update_user_post_meta_view() {

	if( is_single() && 'post' == get_post_type() ) {
		ub_meta_to_update( 'views', 1 );
	}

	
	
}

// Update post engagement on comment
add_action( 'comment_post', 'ub_update_user_post_meta_comment', 10, 3 );
function ub_update_user_post_meta_comment( $comment_ID, $comment_approved, $commentdata ) {

	$data = $commentdata;
	$postId = (int) $data[ 'comment_post_ID' ];

	ub_meta_to_update( 'comments', 3, $postId );
	
}

// Get recommended posts dataset
function ub_get_recommended_dataset() {

	// Set up variables
	$user = ub_get_user_info();
	$meta = get_user_meta( $user[ 'id' ], 'recommended_posts_data', true );
	$dataset = array_keys( $meta );
	$recommended_dataset = [];

	// Create array of up to 10 of the most engaged with posts
	for( $i = 0; $i < 10; $i++ ) {

		if( ! $dataset[ $i ] ) {
			break;
		}

		$recommended_dataset[] = $dataset[ $i ];

	}

	// Return array
	return $recommended_dataset;

}

/*
*
* Add "recommended_posts" to user meta  [ 'post_id' => ,]
*
*/


/*
*
* Remove post from  "recommended_posts"  user meta  after view
*
*/


add_filter( 'the_content', 'ub_user_meta', 10, 1 );
function ub_user_meta( $content ) {

	$data = get_user_meta( ub_get_user_info()[ 'id' ], 'recommended_posts_data', true  );
	echo '<pre>';
	var_dump( $data );
	echo '</pre>';
	
	
	return $content;
}

add_shortcode( 'show_meta', 'show_meta' );
function show_meta() {
	echo '<pre>';
	var_dump( ub_get_recommended_dataset() );
	echo '</pre>';
}

