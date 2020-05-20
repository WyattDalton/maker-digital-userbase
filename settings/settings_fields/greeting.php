<?php

add_settings_field(
    'greeting_before',
    __( 'Default Before Personal Greeting' ),
    'ub_default_greeting_markup_before',
    'userbase',
    'ub_personalized_content'
);

add_settings_field(
    'greeting_punc',
    __( 'Default Personal Greeting Punctuation' ),
    'ub_default_greeting_markup_punc',
    'userbase',
    'ub_personalized_content'
);


function ub_default_greeting_markup_before() {
    $option = get_option( 'ub_settings' );
    $option = $option[ 'greeting' ];

    $before = '';
    if( isset( $option[ 'greeting_before' ] ) ) {
        $before = $option[ 'greeting_before' ];
    }

    echo "<input type='text' id='$option' name='ub_settings[greeting][greeting_before]' value='$before'/>";

}
function ub_default_greeting_markup_punc() {
    $option = get_option( 'ub_settings' );
    $option = $option[ 'greeting' ];

    $punc = '';
    if( isset( $option[ 'greeting_punc' ] ) ) {
        $punc = $option[ 'greeting_punc' ];
    }

    echo "<input type='text' id='$punc' name='ub_settings[greeting][greeting_punc]' value='$punc'/>";

}