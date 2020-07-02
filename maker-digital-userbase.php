<?php 
/*
Plugin Name:         Userbase
Description:         Lightweight website content personalization.
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

// Include plugin scripts and setup files
include( plugin_dir_path( __FILE__ ) . 'setup/setup.php' );

// Include plugin settings and options
include( plugin_dir_path( __FILE__ ) . 'settings/settings.php' );

// Include utility functions and recommended/popular content logic
include( plugin_dir_path( __FILE__ ) . 'utility_functions/utility_functions.php' );

// Require cookies file
require( plugin_dir_path( __FILE__ ) . 'inc/cookies.php');

// Include data reporting files
include( plugin_dir_path( __FILE__ ) . 'data_display/post_data_display.php' );

// Include shortcodes and templates to display personalized and relevant content
include( plugin_dir_path( __FILE__ ) . 'temps/user_recommended_posts.php' );
include( plugin_dir_path( __FILE__ ) . 'temps/shortcodes.php' );

// Include Gutenberg blocks
include( plugin_dir_path( __FILE__ ) . 'blocks/blocks.php' );




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
  $user = ub_get_user_info();
	$meta = get_user_meta( $user[ 'id' ], 'ub_recommended_posts_data', true );

  echo '<pre>';
  var_dump( $meta );
  echo '</pre>';

}