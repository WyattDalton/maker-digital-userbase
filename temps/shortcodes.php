<?php

/**
 * Creates shortcode for displaying personalized greeting with a user's name or default name
 *
 * @param array  $atts shortcode attributes.
 *
 * @return shortcode output
 */
add_shortcode( 'ub_greeting', 'ub_greeting' );
function ub_greeting( $atts ) {

    $defaults = get_option( 'ub_settings' );

    $before = $defaults[ 'greeting' ][ 'greeting_before' ];
    $punc = $defaults[ 'greeting' ][ 'greeting_punc' ];
    // Setting shortcode attributes and defaults
    $a = shortcode_atts(array(
        'before' => $before,
        'punc'   => $punc
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
 * Creates shortcode for displaying personalized content blocks based on a user's segment
 * 
 * @param array  $atts shortcode attributes.
 * @param array  $content content inside div.
 *
 * @return shortcode output
 */
add_shortcode( 'ub_segment', 'ub_content_segment' );
function ub_content_segment( $atts, $content ) {

    $a = shortcode_atts( array(
        'segment' => 'new-users',
     ), $atts );

     $user = ub_get_user_info();

     $segment = str_replace( ' ', '-', strtolower( $user[ 'segment' ] ) );

     $output = '';

    if( $segment == $a[ 'segment' ]) {

        $output = "<div class='ub-personalized-content-segment-" . $segment . "'>";
        $output .= $content;
        $output .= "</div>";

    }

    return $output;

}

// Display recommended content
add_shortcode( 'ub_recommended_content', 'ub_recommended_content' );
function ub_recommended_content( $atts ) {

    $defaults = get_option( 'ub_settings' );
    $style = $defaults[ 'default_post_style' ];
    $number = $defaults[ 'recommended_posts_number_to_show' ];

    $a = shortcode_atts( array(
        'show'  => $number,
        'style' => $style,
    ), $atts );

    ob_start();
    ub_recommended_posts( $a[ 'show' ], $a[ 'style' ] );
    return ob_get_clean();

}