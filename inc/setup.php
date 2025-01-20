<?php
function my_theme_setup() {
    // Add WooCommerce support
    add_theme_support('woocommerce');

    // Add support for custom logo
    add_theme_support('custom-logo');

    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'my_theme_setup');
