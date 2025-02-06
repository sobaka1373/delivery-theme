<?php
// Add theme support
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/shortcodes.php';
require_once get_template_directory() . '/helpers.php';
require_once get_template_directory() . '/acf/init.php';

// Enqueue styles and scripts
function my_theme_enqueue_assets()
{
    wp_enqueue_style('my-theme-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('css-main-page', get_template_directory_uri() . '/assets/css/main-page.css');
    wp_enqueue_script('cdn', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js');
    wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/js/slider.js');
    wp_enqueue_style( 'css-main-page', get_template_directory_uri() . '/assets/css/main-page.css');
    wp_enqueue_script( 'attr-toggle', get_template_directory_uri() . '/assets/js/attr-toggle.js');
    wp_enqueue_script( 'switch-delivery', get_template_directory_uri() . '/assets/js/switch-delivery.js');
    wp_enqueue_script( 'increase-quantity', get_template_directory_uri() . '/assets/js/increase-quantity.js');
    wp_enqueue_script( 'switch-size', get_template_directory_uri() . '/assets/js/switch-size.js', array(), null, true );
    wp_enqueue_script('remove-item', get_template_directory_uri() . '/assets/js/remove-item.js', array('jquery'), null, true);
    wp_localize_script('remove-item', 'ajaxurl', admin_url('admin-ajax.php'));
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');

//THEME MENUS
add_action('after_setup_theme', 'theme_register_nav_menu');

function theme_register_nav_menu()
{
    register_nav_menu('main', 'Top Menu');
}

add_filter('woocommerce_checkout_fields', 'custom_checkout_fields');

function custom_checkout_fields($fields)
{
    // Удалить поле "Компания"
//    unset($fields['billing']['billing_company']);
//    unset($fields['billing']['billing_last_name']);
//    unset($fields['billing']['billing_country']);
//    unset($fields['billing']['billing_city']);
//    unset($fields['billing']['billing_state']);
//    unset($fields['billing']['billing_postcode']);
//    unset($fields['billing']['billing_email']);
//    unset($fields['billing']['billing_address_2']);

    // Переименовать поле "Телефон"
//    $fields['billing']['billing_first_name']['label'] = 'Ваше имя:';
//    $fields['billing']['billing_first_name']['placeholder'] = 'Ваше имя*';
//    $fields['billing']['billing_phone']['label'] = 'Контактный телефон';
//    $fields['billing']['billing_phone']['placeholder'] = 'Ваш телефон*';
//    $fields['billing']['billing_address_1']['label'] = 'Улица';
//    $fields['billing']['billing_address_1']['placeholder'] = '';
//    $fields['billing']['billing_address_house']['label'] = 'Дом';
//    $fields['billing']['billing_address_house']['placeholder'] = '';
//    $fields['billing']['billing_address_entrance']['label'] = 'Подъезд';
//    $fields['billing']['billing_address_entrance']['placeholder'] = '';
//    $fields['billing']['billing_address_floor']['label'] = 'Этаж';
//    $fields['billing']['billing_address_floor']['placeholder'] = '';
//    $fields['billing']['billing_address_flat']['label'] = 'Квартира';
//    $fields['billing']['billing_address_flat']['placeholder'] = '';
//    $fields['billing']['order_comments']['label'] = 'Дополнительная информация';
//    $fields['billing']['order_comments']['placeholder'] = 'Дополнительная информация';
//    $fields['billing']['order_comments']['type'] = 'textarea';

    $fields['billing']['billing_phone']['priority'] = 4;

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

function remove_item_from_cart() {
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);

    $cart = WC()->cart;
    $cart->remove_cart_item($cart_item_key);

    $new_total = WC()->cart->get_cart_total();
    $cart_empty = WC()->cart->is_empty() ? true : false;

    wp_send_json_success([
        'new_total' => $new_total,
        'cart_empty' => $cart_empty
    ]);
}
add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');
