<?php
// Fix Privacy Hole: hide internal wp information for anybody.
// See: c't 23/2020 p 28 in the context of allowed JSON-API calls
add_filter( 'rest_authentication_errors', function( $result ) { 
  if ( ! empty( $result ) ) { 
    return $result; 
  } 
  if ( ! is_user_logged_in() ) { 
    return new WP_Error( '401', 'not allowed.', array('status' => 401) ); 
  } 
  return $result;
});

// Enqueue parent theme styles and child theme stylesheet
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
}
// Enqueue child them js file
add_action( 'wp_enqueue_scripts', 'child_theme_js' );
function child_theme_js() {
  wp_enqueue_script( 'child-theme-js' , get_stylesheet_directory_uri() . '/child-theme-js.js' , array( 'twentyseventeen-global' ) , false , true );
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

function register_dcb_menu() {
  register_nav_menu('member-menu',__( 'Member Menu' ));
}
add_action('init','register_dcb_menu');

function print_menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array( 'name' => null, 'menu_class' => null, 'container_class' => null, 'menu_id' => null, 'container_id' => null, ), $atts));
    return wp_nav_menu( array( 'menu' => $name, 'menu_class' => $menu_class, 'container_class' => $container_class, 'menu_id' => $menu_id, 'container_id' => $container_id, 'echo' => false ) );
}
add_shortcode('menu', 'print_menu_shortcode');
?>
