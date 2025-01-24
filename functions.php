<?php
// Add theme support
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/shortcodes.php';
require_once get_template_directory() . '/helpers.php';

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

add_filter('woocommerce_checkout_fields', 'custom_checkout_fields');

function custom_checkout_fields($fields) {
    // Удалить поле "Компания"
    unset($fields['billing']['billing_company']);

    // Переименовать поле "Телефон"
    $fields['billing']['billing_phone']['label'] = 'Контактный телефон';

    return $fields;
}

add_action('woocommerce_thankyou', 'custom_redirect_after_checkout');
// По каким-то причинам данная функция пока не работает, след. функция жестко редиректит после заказа
function custom_redirect_after_checkout( $order_id ) {
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );

    if ( $order ) {
        $redirect_url = home_url( '/thank-you/' ) . '?order=' . $order_id;

        wp_safe_redirect( $redirect_url );
        exit;
    }
}

add_action( 'template_redirect', function() {
    if ( is_wc_endpoint_url( 'order-received' ) ) {
        if ( isset( $_GET['key'] ) ) {
            $order_id = wc_get_order_id_by_order_key( $_GET['key'] );
            if ( $order_id ) {
                wp_safe_redirect( home_url( '/thank-you/' ) . '?order=' . $order_id );
                exit;
            }
        }
    }
});
