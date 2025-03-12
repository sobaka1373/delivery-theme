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
    wp_enqueue_style( 'css-thank-you-page', get_template_directory_uri() . '/assets/css/thank-you-page.css');
    wp_enqueue_script( 'attr-toggle', get_template_directory_uri() . '/assets/js/attr-toggle.js');
    wp_enqueue_script( 'switch-delivery', get_template_directory_uri() . '/assets/js/switch-delivery.js');
    wp_enqueue_script( 'increase-quantity', get_template_directory_uri() . '/assets/js/increase-quantity.js');
    wp_enqueue_script( 'burger-toggle', get_template_directory_uri() . '/assets/js/burger-toggle.js');

    wp_enqueue_script( 'cart-checkout', get_template_directory_uri() . '/assets/js/cart-checkout.js');
    wp_localize_script("cart-checkout", "wc_cart_params",
        ["ajax_url" => admin_url("admin-ajax.php"), 'apply_coupon_nonce' => wp_create_nonce('apply_coupon')]);
    wp_enqueue_script( 'switch-size', get_template_directory_uri() . '/assets/js/switch-size.js', array(), null, true );
    wp_enqueue_script('remove-item', get_template_directory_uri() . '/assets/js/remove-item.js', array('jquery'), null, true);
    wp_localize_script('remove-item', 'ajaxurl', admin_url('admin-ajax.php'));

    wp_enqueue_script( 'delivery_zones', get_template_directory_uri() . '/assets/js/delivery_zones.js', array('jquery'));
    wp_enqueue_script( 'work-time', get_template_directory_uri() . '/assets/js/work-time.js', array('jquery'));
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

    $new_total = WC()->cart->get_total();
    $new_discount = wc_price(WC()->cart->get_discount_total());
    $cart_empty = WC()->cart->is_empty();

    wp_send_json_success([
        'new_total' => $new_total,
        'new_discount' => $new_discount,
        'cart_empty' => $cart_empty
    ]);
}
add_action('wp_ajax_remove_item_from_cart', 'remove_item_from_cart');
add_action('wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart');

add_action("wp_ajax_update_cart_total", "custom_update_cart_total");
add_action("wp_ajax_nopriv_update_cart_total", "custom_update_cart_total");

function custom_update_cart_total() {
    wp_send_json_success(["total" => WC()->cart->get_total()]);
}

add_action("wp_ajax_update_cart_quantity", "update_cart_quantity");
add_action("wp_ajax_nopriv_update_cart_quantity", "update_cart_quantity");

function update_cart_quantity() {
    if (!isset($_POST["quantity"]) || !isset($_POST["cart_item_key"])) {
        wp_send_json_error("Неверные данные");
    }

    $quantity = (int) sanitize_text_field($_POST["quantity"]);
    $cart_item_key = sanitize_text_field($_POST["cart_item_key"]);

    if (WC()->cart->set_quantity($cart_item_key, $quantity, true)) {
        WC()->cart->calculate_totals();
        wp_send_json_success("Количество обновлено");
    } else {
        wp_send_json_error("Не удалось обновить количество");
    }
}

add_action('rest_api_init', function () {
    register_rest_route('custom-routes', '/geojson', [
        'methods'  => 'GET',
        'callback' => function () {
            $file_path = get_template_directory() . '/assets/js/data.geojson';

            if (!file_exists($file_path)) {
                return new WP_Error('not_found', 'Файл не найден', ['status' => 404]);
            }

            $file_content = file_get_contents($file_path);
            return new WP_REST_Response(json_decode($file_content), 200);
        },
        'permission_callback' => '__return_true',
    ]);
});

add_action('wp_ajax_apply_coupon', 'apply_coupon_ajax');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon_ajax');

function apply_coupon_ajax() {
    if (!isset($_POST['coupon_code'])) {
        wp_send_json_error(['message' => 'Купон не передан']);
    }

    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    WC()->cart->apply_coupon($coupon_code);
    WC()->cart->calculate_totals();

    if (WC()->cart->has_discount($coupon_code)) {
        wp_send_json_success(['message' => 'Купон успешно применен']);
    } else {
        wp_send_json_error(['message' => 'Неверный код купона']);
    }
}

