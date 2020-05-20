<?php 
if( ! defined( 'WPINC' ) ) {
    die;
}

// Create settings page
add_action( 'admin_menu', 'ub_settings_page' );
function ub_settings_page() {
    add_options_page(
        'Userbase', 
        'Userbase', 
        'manage_options', 
        'userbase', 
        'ub_settings_page_markup', 
        'dashicons-admin-users', 
        70
    );
}


// Add settings and setting sections
add_action( 'admin_init', 'ub_add_options_sections' );
function ub_add_options_sections() {

    add_settings_section(
        'ub_personalized_content',
        'Personalized Content Settings',
        '',
        'userbase'
    );

    add_settings_section(
        'ub_post_engagement_values',
        'Post Engagement Values',
        'post_engagement_description_callback',
        'userbase'
    );

    register_setting( 'userbase_settings', 'ub_settings' );

}

// Add settings fields
add_action( 'admin_init', 'ub_add_setting_fields' );
function ub_add_setting_fields() {
    include( plugin_dir_path( __DIR__ ) . 'settings/settings_fields/greeting.php' );
    include( plugin_dir_path( __DIR__ ) . 'settings/settings_fields/recommended_post_settings.php' );
    include( plugin_dir_path( __DIR__ ) . 'settings/settings_fields/engagement.php' );
}
?>

<!-- <h3><?php esc_html_e( 'Default personal greeting', 'usrbse' ); ?></h3>
<p><?php esc_html_e( 'Hey there, Wyatt!', 'usrbse' ); ?></p>

<h3><?php esc_html_e( 'Default recommended posts display', 'usrbse' ); ?></h3>
<p><?php esc_html_e( 'Hey there, Wyatt!', 'usrbse' ); ?></p>

<h3><?php esc_html_e( 'Default post engagement values', 'usrbse' ); ?></h3>
<p><?php esc_html_e( 'Post view: 1', 'usrbse' ); ?></p>
<p><?php esc_html_e( 'Comment: 3', 'usrbse' ); ?></p>  -->

<?php

// Callback function for displaying description of engagement value settings
function post_engagement_description_callback() {
    esc_html_e( 'Editing these values changes the recommended posts per user to give greater weight to certain actions. By default, Userbase is more likely to recommend a post that a user has viewed and commented on, than a post the user has simply viewed.' );
}

// Pull in markup for settings page
function ub_settings_page_markup() {

    if( !current_user_can( 'manage_options' ) ) {
        return;
    }

    include( plugin_dir_path( __DIR__ ) . 'temps/admin/ub_settings_page_markup.php' );

}