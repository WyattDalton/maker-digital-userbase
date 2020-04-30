<?php
include( plugin_dir_path( __DIR__ ) . 'inc/logic.php' );

/**
 * Creates shortcode for displaying personalized greeting with a user's name or default name
 *
 * @param array  $atts shortcode attributes.
 *
 * @return shortcode output
 */
add_shortcode( 'ub_greeting', 'ub_greeting' );
function ub_greeting( $atts ) {

    // Setting shortcode attributes and defaults
    $a = shortcode_atts(array(
        'before' => 'Hi,',
        'punc'       => '.'
     ), $atts );

    $user = ub_get_user_info();

    // Instantiate greeting
    $greeting = '';

    // Set "before" text via shortcode attribute
    $greeting = $greeting . esc_attr($a['before']);

    // Try to get first name from ub_get_user_info() 
    if( $user[ 'fName' ] ) {
        $greeting .= ' ' . $user[ 'fName' ];

    // If not, try to get first name by user id
    } if( $user[ 'id' ] && ! empty(get_userdata( $user[ 'id' ] )->first_name ) ) {
        $user = get_userdata( $user[ 'id' ] );
        $greeting .= ' ' . $user->first_name;

    // If not, try to get display name
    } elseif ( $user[ 'displayName' ] ) {
        $greeting .= ' ' . $user[ 'displayName' ];

    // If not, try to get username
    } elseif ( $user[ 'userName' ] ) {
        $greeting .= ' ' . $user[ 'userName' ];

    // If not, use default
    } else {
        $greeting .= ' ' . $default;
    }

    // Set final punctuation via shortcode attribute 
    $greeting = $greeting . esc_attr($a['punc']);

    return $greeting;

}



// Display personalized content block for segments

// Display suggested content