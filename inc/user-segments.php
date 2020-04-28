<?php


/*
*
* SET UP CUSTOM SEGMENT TAXONOMY
* 
*/

// Define taxonomy and meta key
define( 'USER_SEGMENT_NAME', 'user_segment' );
define( 'USER_SEGMENT_META_KEY', '_user_segment' );


// Create user segment taxonomy
add_action( 'init', 'UsrBse_register_user_segment_taxonomy' );
function UsrBse_register_user_segment_taxonomy() {
	register_taxonomy(
		USER_SEGMENT_NAME, //taxonomy name
		'user', //object for which the taxonomy is created
		array( //taxonomy details
			'public' => true,
			'labels' => array(
				'name'		=> 'User Segments',
				'singular_name'	=> 'User Segment',
				'menu_name'	=> 'User Segments',
				'search_items'	=> 'Search User Segment',
				'popular_items' => 'Popular User Segments',
				'all_items'	=> 'All User Segments',
				'edit_item'	=> 'Edit User Segment',
				'update_item'	=> 'Update User Segment',
				'add_new_item'	=> 'Add New User Segment',
				'new_item_name'	=> 'New User Segment Name',
            ),
			'update_count_callback' => function() {
                return;
            }
		)
	);
}



/*
*
* ADD COLUMN TO USERS TABLE
* 
*/
function UsrBse_segments_user_table( $column ) {
    $column['user_segment'] = 'Segment';
    return $column;
}
add_filter( 'manage_users_columns', 'UsrBse_segments_user_table' );


function UsrBse_segments_user_table_row( $val, $column_name, $user_id ) {

    $user = get_user_meta( $user_id );
    $segmentId = $user['_user_segment'][0];

    if( $segmentId ) {
        $segment = get_term( $segmentId, 'user_segment')->name;
    } else {
        $segment = 'No Segment';
    }

    switch ($column_name) {
        case 'user_segment' :
            return $segment;
        default:
    }
    return $val;

}
add_filter( 'manage_users_custom_column', 'UsrBse_segments_user_table_row', 10, 3 );


function UsrBse_segments_sortable_column( $columns ) {
    $columns['user_segment'] = 'Segment';

    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'UsrBse_segments_sortable_column' );





/*
 *
 * CREATE ADMIN MANAGEMENT PAGE
 * 
 */

// Add admin page for managing segments
add_action( 'admin_menu', 'UsrBse_add_user_segments_admin_page' );
function UsrBse_add_user_segments_admin_page() {
	$taxonomy = get_taxonomy( USER_SEGMENT_NAME );
	add_users_page(
		esc_attr( $taxonomy->labels->menu_name ),//page title
		esc_attr( $taxonomy->labels->menu_name ),//menu title
		$taxonomy->cap->manage_terms,//capability
		'edit-tags.php?taxonomy=' . $taxonomy->name//menu slug
	);
}

// Display proper parent when view segment management page
add_filter( 'submenu_file', 'UsrBse_set_user_segment_submenu_active' );
function UsrBse_set_user_segment_submenu_active( $submenu_file ) {
	global $parent_file;
	if( 'edit-tags.php?taxonomy=' . USER_SEGMENT_NAME == $submenu_file ) {
		$parent_file = 'users.php';
	}
	return $submenu_file;
}




/*
*
* CREATE SEGMENT EDIT FORM IN ADMIN USER DASHBOARD
* 
*/

// Add segement meta to user profile in WP admin
add_action( 'show_user_profile', 'UsrBse_admin_user_profile_segment_select' );
add_action( 'edit_user_profile', 'UsrBse_admin_user_profile_segment_select' );
function UsrBse_admin_user_profile_segment_select( $user ) {
	$taxonomy = get_taxonomy( USER_SEGMENT_NAME );

    if( ! current_user_can( 'administrator' )) {
        return;
    }

    
    
    ?>
	<table class="form-table">
		<tr>
			<th>
				<label for="<?php echo USER_SEGMENT_META_KEY ?>">User Segment</label>
			</th>
			<td>
				<?php
					$user_segment_terms = get_terms( array(
						'taxonomy' => USER_SEGMENT_NAME,
						'hide_empty' => 0
					) );
					
					$select_options = array();
					
					foreach ( $user_segment_terms as $term ) {
						$select_options[$term->term_id] = $term->name;
					}
					
					$meta_values = get_user_meta( $user->ID, USER_SEGMENT_META_KEY, true );
					
					echo UsrBse_custom_form_select(
						USER_SEGMENT_META_KEY,
						$meta_values,
						$select_options,
						'',
						array( )
                    );

				?>
			</td>
		</tr>
	</table>
	<?php
    
}

// Display segment select form
function UsrBse_custom_form_select( $name, $value, $options, $default_var ='') {

    echo "<select name='{$name}'>";
	
	foreach( $options as $options_value => $options_label ) {
		if( ( is_array( $value ) && in_array( $options_value, $value ) )
			|| $options_value == $value ) {
			$selected = " selected='selected'";
		} else {
			$selected = '';
		}
		if( empty( $value ) && !empty( $default_var ) && $options_value == $default_var ) {
			$selected = " selected='selected'";
		}
		echo "<option value='{$options_value}'{$selected}>{$options_label}</option>";
	}

    echo "</select>";
}

// Saving user segment
add_action( 'personal_options_update', 'UsrBse_admin_save_user_segments' );
add_action( 'edit_user_profile_update', 'UsrBse_admin_save_user_segments' );

function UsrBse_admin_save_user_segments( $user_id ) {
	$tax = get_taxonomy( USER_SEGMENT_NAME );
	$user = get_userdata( $user_id );
	
	$new_segments_ids = $_POST[USER_SEGMENT_META_KEY];
	$user_meta = get_user_meta( $user_id, USER_SEGMENT_META_KEY, true );
	$previous_segments_ids = array();
	
	if( !empty( $user_meta ) ) {
		$previous_segments_ids = (array)$user_meta;
	}

	
    update_user_meta( $user_id, USER_SEGMENT_META_KEY, $new_segments_ids );
    // UsrBse_update_users_segments_count( $previous_segments_ids, $new_segments_ids );
    

}


// User segment count callback function
// function UsrBse_update_users_segments_count( $previous_terms_ids, $new_terms_ids ) {
// 	global $wpdb;

// 	$terms_ids = array_unique( array_merge( (array)$previous_terms_ids, (array)$new_terms_ids ) );
	
// 	if( count( $terms_ids ) < 1 ) { return; }
	
// 	foreach ( $terms_ids as $term_id ) {
// 		$count = $wpdb->get_var(
// 			$wpdb->prepare(
// 				"SELECT COUNT(*) FROM $wpdb->usermeta WHERE meta_key = %s AND meta_value LIKE %s",
// 				USER_SEGMENT_META_KEY,
// 				'%"' . $term_id . '"%'
// 			)
// 		);
// 		$wpdb->update( $wpdb->term_taxonomy, array( 'count' => $count ), array( 'term_taxonomy_id' => $term_id ) );
// 	}
// }

