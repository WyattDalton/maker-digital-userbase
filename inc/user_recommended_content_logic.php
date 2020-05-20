<?php

/**
 * 
 * Sets user meta with total engagement values, and engagement values by type, for every post 
 * an ID'd user interacts with
 *
 * @param string  $valuetoupdate  Meta value type 
 * @param int     $incBy          Amount by wich to increase meta value
 * @param int     $postId         (optional) If post ID isn't avalable for hook, provide here
 * 
 * @return function updates meta for ID'd user, using ub_get_user_info(), with value and incBy 
 */

function ub_meta_to_update( $valueToUpdate = '', $incBy = 1, $postId = null ) {

	// set up variables
	$user = ub_get_user_info();
	$metaKey = 'ub_recommended_posts_data';
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
		if( is_array( $meta ) && count( $meta ) > 20 ) {
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



/**
 * 
 * Retrieves a list of post ids, filtered in decending order, by total engagement with tags 
 * and categories
 *	1 - get initial reccomended dataset (array: post data from user interactions meta)
 *		- post id
 *		- post engagement
 *  2 - create arrays for all tags, categories, and ids of posts in initial dataset
 *      - post id
 *      - post engagement
 *      - all used tags
 *      - all used categories
 * 3 - Combine all tags and categories into seperate arrays
 * 4 - Add up total post engagement form all posts in dataset for each taxonomy
 * 5 - Loop through all WP posts that share taxonomies with dataset, but are not in dataset, or $recommended_posts[ 'viewed' ] cookie
 * 6 - Sort returned posts by total engagement value of their taxonomies
 * 7 - Return array of recommended posts, limited to $limit
 * 
 * 
 * @param int  $limit  How many posts to retrieve
 * 
 * @return array an array containing recommended post ids and total engagement   
 */
function ub_get_recommended_dataset( int $limit = 10 ) {

	// Set up variables
	$user = ub_get_user_info();
	$meta = get_user_meta( $user[ 'id' ], 'ub_recommended_posts_data', true );
	if( is_array( $meta ) ) { $dataset = array_keys( $meta ); }

	// If meta is not an array, stop
	if( ! is_array( $meta ) ) { return null; }

	// Create array of up to 10 of the most engaged with posts
	$recommended_dataset = [];
	for( $i = 0; $i < 10; $i++ ) {

		if( ! $dataset[ $i ] ) {
			break;
		}

		$recommended_dataset[] = array(
			'id' => $dataset[ $i ],
			'engagement' => $meta[ $dataset[ $i ] ][ 'total' ],
		);


	}

	// Create array with tags, categories, and total engagement for posts
	$recommended_postdata = [];
	foreach( $recommended_dataset as $post ) {

		// Get post tag data
		$tags = get_the_tags( $post[ 'id' ] );
		if( $tags ) {
			$tagsArray = [];
			foreach( $tags as $tag ) {
				array_push( $tagsArray, $tag->term_taxonomy_id );
			}
		}
		

		// get post category data
		$cats = get_the_category( $post[ 'id' ] );
		if( $cats ){
			$catsArray = [];
			foreach( $cats as $cat ) {
				array_push( $catsArray, $cat->term_taxonomy_id );
			}
		}
		

		// Create array
		$recommended_postdata[] = array(
			'id'         => $post[ 'id' ],
			'engagement' => $post[ 'engagement' ],
			'tags'       => $tagsArray,
			'cat'        => $catsArray,
		);
	}

	// Create combined tags array with total engagement per tag
	$tagdata = [];
	foreach( $recommended_postdata as $tag ) {
		$tags = $tag[ 'tags' ];
		if( $tags ){
			$tagSubData = [];
			foreach( $tags as $item ) {
				$tagSubData[ $item ] = $tag[ 'engagement' ];
			}
			$tagdata[] = $tagSubData;
		}
	}

	$tagsCombined = [];
	foreach( $tagdata as $tag ) {
		$tagsCombined = $tagsCombined + $tag;
	}
	

	// Create combined category array with total engagement per category
	$catdata = [];
	foreach( $recommended_postdata as $cat ) {
		$cats = $cat[ 'cat' ];
		if( $cats ){
			$catSubData = [];
			foreach( $cats as $item ) {
				$catSubData[ $item ] = $cat[ 'engagement' ];
			}
			$catdata[] = $catSubData;
		}
	}
	$catsCombined = [];
	foreach( $catdata as $cat ) {
		$catsCombined = $catsCombined + $cat;
	}

	// Get dataset post ids
	$postIddata = [];
	foreach($recommended_postdata as $post ) {
		$postIddata[] = $post[ 'id' ];
	}

	// Get posts that match tags and categories, but are not part of the dataset.
	$args = array(
		'posts_per_page' => -1,
		'post__not_in'   => $postIddata,
		'tag__in'        => array_keys( $tagsCombined ),
		'category__in'   => array_keys( $catsCombined ),
	);

	$recommended_posts = [];
	$postPool = new WP_Query( $args );
	while ( $postPool->have_posts() ) : $postPool->the_post();

		$currentPostEngagement = 0;

		// Add post taxonomy engagement value: tags
		$tags = get_the_tags();
		foreach( $tags as $tag ) {
			$tagId = $tag->term_taxonomy_id;
			if( array_key_exists( $tagId, $tagsCombined) ) {
				$val = $tagsCombined[ $tagId ];
				$currentPostEngagement = $currentPostEngagement + $val;
			}
		}

		// Add post taxonomy engagement value: category
		$cats = get_the_category();
		foreach( $cats as $cat ) {
			$catId = $cat->term_taxonomy_id;
			if( array_key_exists( $catId, $catsCombined) ) {
				$val = $catsCombined[ $catId ];
				$currentPostEngagement = $currentPostEngagement + $val;
			}
		}

		// Add post to reccomended posts array with id and engagement value
		$recommended_posts[] = array( 'id' => get_the_ID(), 'name' => get_the_title(), 'total' => $currentPostEngagement );

	endwhile;
	wp_reset_query();

	// Sort $recommended_posts from most total engagement to lowest
	usort( $recommended_posts, function ( $a, $b ) {
		return $b[ 'total' ] <=> $a[ 'total' ];
	});

	// Limit recommended posts to $limit
	$recommended = [];
	for( $i = 0; $i < $limit; $i++ ) {
		$recommended[ $i ] = $recommended_posts[ $i ];
	}

	// Return array
	return $recommended;

}


// Add recommended post array to user meta
add_action( 'template_redirect', 'ub_add_recommended_posts_to_meta' );
function ub_add_recommended_posts_to_meta() {

	$user = ub_get_user_info()[ 'id' ];

	$data = ub_get_recommended_dataset();

	$old_data = get_user_meta( $user, 'ub_recommended_posts', true );

	if( is_array( $old_data ) ) {
		$data = $data + $old_data;
	}

	update_user_meta( $user, 'ub_recommended_posts', $data);
}


// reduce post engagement data to 3 items on user loggout or auth cookie exiration 
add_action( 'wp_logout', 'ub_recommended_posts_meta_reset' );
add_action( 'clear_auth_cookie', 'ub_recommended_posts_meta_reset' );
function ub_recommended_posts_meta_reset() {
	
	$user = ub_get_user_info()[ 'id' ];
	$data = get_user_meta( $user, 'ub_recommended_posts_data', true );

	if( is_array( $data ) && count( $data ) > 3 ) {
		shuffle( $data );
		$data = array_slice( $data, 1, 3 );
	}

	update_user_meta( $user, 'ub_recommended_posts_data', $data);

	return $data;
}

/**
 * 
 * Gets true / false whether the current post is recommended
 *
 * @return bool 
 */
function ub_is_post_recommended() {
	$data = ub_get_recommended_dataset();

	if( ! is_array( $data ) ) {
		$recommended = false;
	}

	foreach( $data as $item ) {
		if( $item[ 'id' ] == get_the_ID() ) {
			$recommended = __return_true();
			break;
		}
	}

	return $recommended;
}