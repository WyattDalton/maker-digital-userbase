<?php 
/*
Plugin Name:         Userbase
Description:         Light weight website content personalization.
Version:             0.0.1
Contributers:        Maker Digital
Author:              Maker Digital
License:             GPLv2 or later
Text Domain:         UsrBse
*/

// If this file is called directly, abort.
if ( ! Defined( 'WPINC' ) ) {
    die;
}
// Require cookies file
require( plugin_dir_path( __FILE__ ) . 'inc/cookies.php');

// Require user segments
require( plugin_dir_path( __FILE__ ) . 'inc/user-segments.php');

// include( plugin_dir_path( __FILE__ ) . 'inc/logic.php' );
include( plugin_dir_path( __FILE__ ) . 'temps/shortcodes.php' );

add_shortcode('UsrBse_cookie_test', 'UsrBse_cookie_test'); 
function UsrBse_cookie_test() {

    $user_data = $_COOKIE['UsrBse_user_data'];
    $user_data = stripslashes( $user_data );
    $user = json_decode( $user_data );

    $user_data_login = $_COOKIE['UsrBse_user_data_login'];
    $user_data_login = stripslashes( $user_data_login );
    $user_login = json_decode( $user_data_login );

    $user_data_comment = $_COOKIE['UsrBse_user_data_comment'];
    $user_data_comment = stripslashes( $user_data_comment );
    $user_comment = json_decode( $user_data_comment );

    echo '<pre> USER DATA';
    var_dump( $user );
    echo '</pre>';

    echo 'USER DATA LOGIN';
    echo '<pre>';
    var_dump( $user_login );
    echo '</pre>';

    echo 'USER DATA COMMENT';
    echo '<pre>';
    var_dump( $user_comment );
    echo '</pre>';

}