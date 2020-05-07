<?php 
/*
Plugin Name:         Userbase
Description:         Light weight website content personalization.
Version:             0.0.1
Contributers:        Maker Digital
Author:              Maker Digital
License:             GPLv2 or later
Text Domain:         usrbse
*/

// If this file is called directly, abort.
if ( ! Defined( 'WPINC' ) ) {
    die;
}

// Require user segments
require( plugin_dir_path( __FILE__ ) . 'inc/user-segments.php');

include( plugin_dir_path( __FILE__ ) . 'inc/get_user_info.php' );

// Require cookies file
require( plugin_dir_path( __FILE__ ) . 'inc/cookies.php');

// Include shortcodes
include( plugin_dir_path( __FILE__ ) . 'temps/shortcodes.php' );

// Include Gutenberg blocks
include( plugin_dir_path( __FILE__ ) . 'blocks/blocks.php' );

include( plugin_dir_path( __FILE__ ) . 'inc/user_recommended_content_meta.php' );

// add_action( 'init', function(){
//     delete_user_meta( 1, 'recommended_posts_data' );
// });