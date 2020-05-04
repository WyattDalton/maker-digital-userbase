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
    if( $user[ 'first_name' ] ) {
        $greeting .= ' ' . $user[ 'first_name' ];

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


/**
 * Creates shortcode for returning wrapper for personalized content blocks based on a user's segment
 *
 * @param array  $atts shortcode attributes.
 * @param array  $content content inside div.
 * 
 * @return shortcode output
 */
add_shortcode( 'ub_content', 'ub_content' );
function ub_content( $atts, $content = null ) {

    $a = shortcode_atts(array(
        'class' => null,
     ), $atts );

     $class = $a[ 'class' ] ? $a[ 'class'] : '';

    $output = '<div class="ub-personalized-content-wrapper ' . $class . '">' . do_shortcode( $content ) . '</div>';
    return $output;

}

/**
 * Creates shortcode for displaying personalized content blocks based on a user's segment
 * 
 * @param array  $atts shortcode attributes.
 * @param array  $content content inside div.
 *
 * @return shortcode output
 */
add_shortcode( 'ub_segment', 'ub_content_segment' );
function ub_content_segment( $atts, $content ) {

    $a = shortcode_atts(array(
        'segment' => 'default',
     ), $atts );

     $user = ub_get_user_info();

     $segment = str_replace( ' ', '-', strtolower( $user[ 'segment' ][ 0 ] ) );

     $output = '';

    if( $segment == $a[ 'segment' ]) {

        $output = "<div class='ub-personalized-content-segment-" . $segment . "'>";
        $output .= $content;
        $output .= "</div>";

    }

    return $output;

}

// Display suggested content





// Shortcode for testing cookies
add_shortcode('UsrBse_cookie_test', 'UsrBse_cookie_test'); 
function UsrBse_cookie_test() {

    $user_data = $_COOKIE['UsrBse_user_data'];
    $user_data = stripslashes( $user_data );
    $user = json_decode( $user_data );

    echo '<pre> USER DATA';
    var_dump( $user );
    echo '</pre>';
}