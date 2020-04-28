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

// Require user segments
require( plugin_dir_path( __FILE__ ) . 'inc/user-segments.php');

// include( plugin_dir_path( __FILE__ ) . 'inc/logic.php' );
include( plugin_dir_path( __FILE__ ) . 'temps/shortcodes.php' );