<?php


$expiry = strtotime('+1 month');


/**
 * Sets cookie that stores user information on login
 *
 * @return cookie json
 */
add_action( 'wp_login', 'usrbse_user_logged_data_update', 10, 2 );
function usrbse_user_logged_data_update( $user_login, $user ) {

    // Get dataset
    $id = $user->ID;
    $meta = get_user_meta( $id );
    $segment = wp_get_object_terms( $id, 'user_segment' );

    // Create output array with data
    $outputArray = array(
        'first_name'  => $meta[ 'first_name' ],
        'displayName' => $user->display_name,
        'userName'    => $user->user_login,
        'segment'     => $meta[ '_user_segment' ],
        'id'          => $user,
        'segment'     => $segment[0]->name,
        'segmentId'   => $segment[0]->term_id,
    );

    // If cookie already exists, add data to it. If not, create cookie with new data
    if( isset( $_COOKIE[ 'usrbse_user_data' ] ) ) {

        $old_data = $_COOKIE[ 'usrbse_user_data' ];
        $old_data = stripslashes( $old_data );
        $old_data = (array) json_decode( $old_data, true );

        $output = array_merge( $outputArray, $old_data );
        $output = json_encode ( $output );

    } else {

        // Convert array to json
        $output = json_encode( $outputArray );

    }

    // Set cookie with data
    setcookie( 'usrbse_user_data', $output, $expiry, COOKIEPATH, COOKIE_DOMAIN );
}


/**
 * Sets cookie that stores user information on comment
 *
 * @return cookie json
 */
add_action( 'comment_post', 'usrbse_user_comment_data_update', 10, 3 );
function usrbse_user_comment_data_update( $comment_ID, $comment_approved, $commentdata ) {

    // Get dataset
    $data = $commentdata;

    // Get commenter name
    $fullname = $data[ 'comment_author' ];

    // Create output array
    $outputArray = array(
        'fullname' => $fullname,
    );

    // If cookie already exists, add data to it. If not, create cookie with new data
    if( isset( $_COOKIE[ 'usrbse_user_data' ] ) ) {

        $old_data = $_COOKIE[ 'usrbse_user_data' ];
        $old_data = stripslashes( $old_data );
        $old_data = (array) json_decode( $old_data, true );

        $output = array_merge( $outputArray, $old_data );
        $output = json_encode ( $output );
        
    } else {

        // Convert array to json
        $output = json_encode( $outputArray );

    }

    // Set cookie with data
    setcookie( 'usrbse_user_data', $output, $expiry, COOKIEPATH, COOKIE_DOMAIN );
}