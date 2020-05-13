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

function usrbse_load_styles() {
    wp_enqueue_style( 'main-styles', plugins_url( 'style.min.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'usrbse_load_styles' );


// Require user segments
require( plugin_dir_path( __FILE__ ) . 'inc/user-segments.php');

// Include user segments
include( plugin_dir_path( __FILE__ ) . 'inc/get_user_info.php' );

// Include recommended/popular content Logic
include( plugin_dir_path( __FILE__ ) . 'inc/user_recommended_content_logic.php');
include( plugin_dir_path( __FILE__ ) . 'inc/user_popular_content_logic.php');

// Require cookies file
require( plugin_dir_path( __FILE__ ) . 'inc/cookies.php');

// Include shortcodes
include( plugin_dir_path( __FILE__ ) . 'temps/user_recommended_posts.php' );
include( plugin_dir_path( __FILE__ ) . 'temps/shortcodes.php' );


// Include Gutenberg blocks
include( plugin_dir_path( __FILE__ ) . 'blocks/blocks.php' );

// add_action( 'init', function(){
//     delete_user_meta( 21, 'ub_recommended_posts_data' );
//     delete_user_meta( 21,  'ub_recommended_posts' );
// });

add_shortcode( 'show_meta', 'show_meta' );
function show_meta() {

  return;

}