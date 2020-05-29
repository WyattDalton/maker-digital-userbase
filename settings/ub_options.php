<?php

// Create initial options 
function ub_options() {

    $userbase_settings = array(
        'greeting' => array(
            'greeting_before' => 'Hey there,',
            'greeting_punc'   => '!',
        ),
        'default_post_style' => 'simple-card',
        'default_engagement' => array(
            'post-view' => 1,
            'post_comment' => 3,
        ),
    );

    // delete_option('ub_settings');
    if( ! get_option( 'ub_settings' ) ) {
        add_option( 'ub_settings', $userbase_settings );
    }
}

add_action( 'admin_init', 'ub_options' );