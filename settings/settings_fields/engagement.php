<?php

add_settings_field(
    'post_view',
    __( 'Post View' ),
    'ub_default_engagement_markup_view',
    'userbase',
    'ub_post_engagement_values'
);

add_settings_field(
    'post_comment',
    __( 'Post Comment' ),
    'ub_default_engagement_markup_comment',
    'userbase',
    'ub_post_engagement_values'
);

function ub_default_engagement_markup_view() {
    $option = get_option( 'ub_settings' );
    $option = $option[ 'default_engagement' ];

    $view = 1;
    if( isset( $option[ 'post_view' ] ) ) {
        $view = $option[ 'post_view' ];
    }

    echo "<input type='number' id='post_engagement_view' name='ub_settings[default_engagement][post_view]' value='$view'/>";
}

function ub_default_engagement_markup_comment() {
    $option = get_option( 'ub_settings' );
    $option = $option[ 'default_engagement' ];

    $comment = 3;
    if( isset( $option[ 'post_comment' ] ) ) {
        $comment = $option[ 'post_comment' ];
    }

    echo "<input type='number' id='post_engagement_comment' name='ub_settings[default_engagement][post_comment]' value='$comment'/>";
}