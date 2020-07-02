<?php

// Enqueue admin styles and scripts
function usrbse_admin_styles() {
    wp_enqueue_style( 'admin-styles', plugins_url( 'styles/admin-styles.min.css', __FILE__ ) );
  
    wp_enqueue_script( 'admin-js', plugins_url( 'js/admin.js', __FILE__ ), [], '', true );
  }
  add_action( 'admin_enqueue_scripts', 'usrbse_admin_styles' );
  
  // Enqueue frontend styles
  function usrbse_load_styles() {
      wp_enqueue_style( 'main-styles', plugins_url( 'styles/style.min.css', __FILE__ ) );
  }
  add_action( 'wp_enqueue_scripts', 'usrbse_load_styles' );