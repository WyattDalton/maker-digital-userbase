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
    if ( is_user_logged_in() ) {

        // If the user is logged in, user information from their profile

        // Get user object
        $ubCurrent_user = wp_get_current_user();
        
        
        // Get user first name, username, and ID
        $fName = $ubCurrent_user->user_firstname;
        $displayName = $ubCurrent_user->display_name;
        $username = $ubCurrent_user->user_login;
        $userID = $ubCurrent_user->ID;
    
        // Create array with user info
        $ubUser[ 'fName' ]       = $firstName;
        $ubUser[ 'displayName' ] = $displayName;
        $ubUser[ 'userName' ]    = $ubUsername;
        $ubUser[ 'id' ]          = $userID;
    
    } elseif ( ! empty( wp_get_current_commenter()["comment_author"] ) ) {

        // If the visitor isn't logged in, check if they have a comment cookie and get their information
        $commenter = wp_get_current_commenter();

        // Break full name into array
        $fullName = explode( ' ', $commenter[ 'comment_author' ] );

        // Common titles
        $titlesarray = array(
            'master',
            'mr',
            'mrs',
            'ms',
        );

        // Get first name (and title, if provided)
        if( array_find( 
                strtolower( 
                    str_replace( '.', '', $fullName[0] ) 
                ), $titlesarray ) 
            ) /* end if */ 
        {
            $fName = $fullName[0] . ' ' . $fullName[1];
        } else {
            $fName = $fullName[0];
        }
        
        // Creat array with user info
        $ubUser[ 'userName' ] = $commenter[ 'comment_author' ];
        $ubUser[ 'fName' ] = $fName;

    } else {

        // If visitor isn't logged in, and there's no cookie available, set name as default
        $ubUser[ 'fName' ]  = 'friend';
    }

    return $ubUser;

}