<?php
// Add theme support
require_once get_template_directory() . '/inc/setup.php';

// Enqueue styles and scripts
function my_theme_enqueue_assets() {
    wp_enqueue_style('my-theme-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style( 'css-main-page', get_template_directory_uri() . '/assets/css/main-page.css');
    wp_enqueue_script( 'cdn', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    wp_enqueue_script( 'swiper-js', get_template_directory_uri() . '/assets/js/slider.js');

}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');

//THEME MENUS
add_action( 'after_setup_theme', 'theme_register_nav_menu' );

function theme_register_nav_menu() {
    register_nav_menu( 'main', 'Top Menu' );
}