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

// Enqueue admin styles and scripts
function usrbse_admin_styles() {
  wp_enqueue_style( 'admin-styles', plugins_url( 'styles/admin-styles.min.css', __FILE__ ) );

  wp_enqueue_script( 'admin-js', plugins_url( 'js/admin.js', __FILE__ ), [], '', true );
}
add_action( 'admin_enqueue_scripts', 'usrbse_admin_styles' );

// Enqueue frontend styles
function usrbse_load_styles() {
    wp_enqueue_style( 'main-styles', plugins_url( 'styles/style.min.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'usrbse_load_styles' );

// Include plugin settings and options
include( plugin_dir_path( __FILE__ ) . 'settings/ub_options.php' );
include( plugin_dir_path( __FILE__ ) . 'settings/ub_settings.php' );
include( plugin_dir_path( __FILE__ ) . 'inc/custom_registration.php' );

// Require user segments
require( plugin_dir_path( __FILE__ ) . 'inc/user-segments.php');

// Include utility functions
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

// Add settings link to plugin listing on plugins page
$plugin_settings_name = 'plugin_action_links_' . plugin_basename( __FILE__ );
add_filter ( $plugin_settings_name, 'ub_plugin_settings_link' ); 
function ub_plugin_settings_link( $links ) {
  $settins_link = '<a href="options-general.php?page=userbase">' . __( 'Settings' ) . '</a>';
  array_push( $links, $settins_link );
  return $links;
}






















add_shortcode( 'show_meta', 'show_meta' );
function show_meta() {

  echo '<pre>';
  // var_dump( ub_get_user_info() );
  echo '</pre>';

}

// add_filter( 'the_content', 'ub_rec_check' );
function ub_rec_check( $content )
{
	$output = 'false';
	if( ub_is_post_recommended() ) {
		$output = 'true';
	}
	return $output . $content;
}