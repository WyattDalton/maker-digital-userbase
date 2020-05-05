<?php

function register_userbse_blocks() {
 
    // automatically load dependencies and version
    $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
 
    wp_register_script(
        'usrbse-blocks-script',
        plugins_url( 'build/index.js', __FILE__ ),
        $asset_file['dependencies'],
        $asset_file['version']
    );

    wp_register_style(
        'usrbse-blocks-editor-styles',
        plugins_url( 'editor.css', __FILE__ ),
        array( 'wp-edit-blocks' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
    );
 
 
    register_block_type( 'usrbse/personalized-content', array(
        'editor_script' => 'usrbse-blocks-script',
        'editor_style'  => 'usrbse-blocks-editor-styles',
        'render_callback' => 'personalized_content_block_render',
    ) );
 
}
add_action( 'init', 'register_userbse_blocks' );

function personalized_content_block_render( $attributes, $content ) {
    $user = ub_get_user_info();
    $userSegment = str_replace( ' ', '-', strtolower( $user[ 'segment' ][ 0 ] ) );
    $blockSegment = str_replace( ' ', '-', strtolower( $attributes[ 'segment' ] ) );

    if( $userSegment != $blockSegment ) {
        return null;
    }

    return $content;
}