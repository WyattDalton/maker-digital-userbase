<?php

require( plugin_dir_path( __FILE__ ) . 'enable_user_taxonomy.php');


/*
*
* SET UP CUSTOM SEGMENT TAXONOMY
* 
*/

// Define taxonomy and meta key
define( 'USER_SEGMENT_NAME', 'user_segment' );
define( 'USER_SEGMENT_META_KEY', '_user_segment' );


// Create user segment taxonomy
add_action( 'init', 'usrbse_register_user_segment_taxonomy' );
function usrbse_register_user_segment_taxonomy() {
	register_taxonomy(
		USER_SEGMENT_NAME, //taxonomy name
		'user', //object for which the taxonomy is created
		array( //taxonomy details
			'public'            => true,
			'labels'            => array(
				'name'		    => 'User Segments',
				'singular_name'	=> 'User Segment',
				'menu_name'    	=> 'User Segments',
				'search_items'	=> 'Search User Segment',
				'popular_items' => 'Popular User Segments',
				'all_items' 	=> 'All User Segments',
				'edit_item'	    => 'Edit User Segment',
				'update_item'	=> 'Update User Segment',
				'add_new_item'	=> 'Add New User Segment',
				'new_item_name'	=> 'New User Segment Name',
			),
			'show_in_rest'      => true,
		)
	);
}

// Setup default segment
add_action( 'init', 'usrbse_register_default_user_segment' );
function usrbse_register_default_user_segment() {

	// Create term
	wp_insert_term( 
		'New Users', 
		USER_SEGMENT_NAME, 
		array(
			'slug' => 'new-users',
		)
	);
}

// Assign term to new users
add_action( 'init', 'usrbse_add_default_user_segment');
add_action( 'register_new_user', 'usrbse_add_default_user_segment' );
function usrbse_add_default_user_segment() {

	$users = get_users();

	foreach( $users as $user ) {

		$useObject = wp_get_object_terms( $user->ID, 'user_segment' );
		$slug = $useObject[0]->slug;
		
		if( ! $slug ) {
			wp_set_object_terms( $user->ID, 'new-users', 'user_segment', false );
		}

	}
}



/*
*
* ADD COLUMN TO USERS TABLE
* 
*/
function usrbse_segments_user_table( $column ) {
    $column['user_segment'] = 'Segment';
    return $column;
}
add_filter( 'manage_users_columns', 'usrbse_segments_user_table' );

// Table row content
function usrbse_segments_user_table_row( $val, $column_name, $user_id ) {

	$user = get_user_meta( $user_id );
	
	echo '<pre>';
	// var_dump( $user );
	echo '</pre>';
	
    $segment = wp_get_object_terms( $user_id, 'user_segment' )[0]->name;

    if( ! $segment ) {
        $segment = 'No Segment';
    }

    switch ($column_name) {
        case 'user_segment' :
            return $segment;
        default:
    }
    return $val;

}
add_filter( 'manage_users_custom_column', 'usrbse_segments_user_table_row', 10, 3 );
