<?php

function register_userbse_blocks() {
 
    // Automatically load dependencies and version
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
        'editor_script'   => 'usrbse-blocks-script',
        'editor_style'    => 'usrbse-blocks-editor-styles',
        'render_callback' => 'personalized_content_block_render',
    ) );
 
}
add_action( 'init', 'register_userbse_blocks' );

function personalized_content_block_render( $attributes, $content ) {
    $user = ub_get_user_info();
    $userSegment = str_replace( ' ', '-', strtolower( $user[ 'segment' ] ) );
    $blockSegment = str_replace( ' ', '-', strtolower( $attributes[ 'segment' ] ) );
    $recommended = $attributes[ 'recommended' ];

    // If block segment matches user or all users, display
    $showSeg = false;
    if( $userSegment == $blockSegment ) {
        $showSeg = true; 
    } elseif ( null == $blockSegment ) {
        $showSeg = true;
    }

    // If block if post is recommended for user, and block is set up display on reccomended posts, display
    $showRec = ub_is_post_recommended();

    // If block is recommended, only show if block segment matches user and post is recommended for user
    if( $recommended ) {
        if( $showRec && $showSeg ) {
            return $content;
        }
        return null;
    }

    // Otherwise, show block if block segment matches user
    if( $showSeg ) {
        return $content;
    }
    return null;
}