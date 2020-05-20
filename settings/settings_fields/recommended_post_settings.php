<?php



add_settings_field(
    'recommended_posts_style',
    __( 'Default recommended posts display' ),
    'ub_default_recommended_markup',
    'userbase',
    'ub_personalized_content'
);

function ub_default_recommended_markup() {
    
    $option = get_option( 'ub_settings' );
    $option = $option[ 'default_post_style' ];


    $styles = array( 
        'simple' => array(
            'card'     => 'card',
            'overlay'     => 'overlay',
            'image-left'  => 'image-left',
            'image-right' => 'image-right',
    
        ),
        'inline' => array(
            'card'        => 'card',
            'overlay'     => 'overlay',
            'image-above' => 'image-above',
            'image-left'  => 'image-left',
            'image-right' => 'image-right',
    
        ),
        'grid' => array(
            'card'    => 'card',
            'overlay' => 'overlay',
    
        ),
        'featured' => array(
            'hero-cards'   => 'hero-cards',
            'hero-overlay' => 'hero-overlay',
            'mixed'        => 'mixed',
        )
    );


    $select = '';
    if( isset( $option ) ) {
        $select = esc_html( $option );
    }

    $output = "<select id='recommended_post_style' name='ub_settings[default_post_style]'>";
    foreach( $styles as $cat => $item){

        $category = '';
        $category .= "<optgroup label='$cat'>";
        
        foreach( $item as $val ) {
            $value = $cat . '-' . $val;
            $category .= "<option value='$value'";
            $category .= selected( $select, $value, false );
            $category .= ">($cat) $val</option>";
        }

        $category .= "</optgroup>";
        $output .= $category;
    }
    $output .= "</select>";
    echo $output;
    
    
}