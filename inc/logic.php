<?php 

/**
 * Case in-sensitive array_search() with partial matches
 *
 * @param string $needle   The string to search for.
 * @param array  $haystack The array to search in.
 *
 * @author Bran van der Meer <branmovic@gmail.com>
 * @since 29-01-2010
 */
function array_find($needle, array $haystack)
{
    foreach ($haystack as $key => $value) {
        if (false !== stripos($value, $needle)) {
            return $key;
        }
    }
    return false;
}


function ub_get_user_info() {

    $ubUser = [];
    $userCookie = (array) json_decode( stripslashes( $_COOKIE[ 'usrbse_user_data' ] ) );

    if ( is_user_logged_in() ) {

        /*
         * If the user is logged in, get user information from their profile
         */ 

        // Get user object
        $ubCurrent_user = wp_get_current_user();
        $meta = get_user_meta( $ubCurrent_user->ID );

        // Create array with user info
        $ubUser[ 'first_name' ]  = is_array( $meta[ 'first_name' ] ) ? $meta[ 'first_name' ][ 0 ] : $meta[ 'first_name' ];
        $ubUser[ 'displayName' ] = $displayName = $ubCurrent_user->display_name;
        $ubUser[ 'userName' ]    = $ubCurrent_user->user_login;
        $ubUser[ 'id' ]          = $ubCurrent_user->ID;
        $ubUser[ 'segment' ]     = $meta[ '_user_segment' ];
    
    } elseif ( $userCookie ) {

        /*
         * If the visitor isn't logged in, check if they have a comment cookie and 
         * get their information
         */

        // Get user data from cookie
        $ubUser = $userCookie;

        // If there is a user id stored in cookie, check if there's a 'first_name'
        if( $ubUser[ 'id' ] ) {
            $first_name_check = get_user_meta( $ubUser[ 'id'], 'first_name', true );
        }

        // If 'first_name' exists, use it. Otherwise, use fullname from cookie data
        if( $first_name_check ) {

            // Set first_name_check to Full_name
            $first_name = $first_name_check;

        } elseif( $ubUser[ 'fullname' ] ) {

            // Break full name into array
            $fullname = $ubUser[ 'fullname' ];
            $fullname = explode( ' ', $fullname );
            
            // Common titles
            $titlesarray = array(
                'master',
                'mr',
                'mrs',
                'ms',
            );

            // Get first name. If the commenter used a common title, filter it out
            if( array_find( strtolower( str_replace( '.', '', $fullname[0] ) ), $titlesarray ) ) /* end if */ 
            {
                $first_name = $fullname[1];
            } else {
                $first_name = $fullname[0];
            }

        }
        
        // Overwrite "first_name" in ubUser array
        $ubUser[ 'first_name' ] = $first_name;

    } else {

        /*
         * If visitor isn't logged in, and there's no cookie available, set name as default
         */ 

        $ubUser[ 'first_name' ]  = 'friend';

    }

    return $ubUser;

}