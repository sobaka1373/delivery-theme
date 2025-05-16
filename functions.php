<?php
// Add theme support
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/shortcodes.php';
require_once get_template_directory() . '/helpers.php';
require_once get_template_directory() . '/acf/init.php';

// Enqueue styles and scripts

function my_theme_enqueue_assets()
{
    global $post;
    wp_enqueue_style('my-theme-style', get_template_directory_uri() . '/assets/css/main.css');
    wp_enqueue_style('css-main-page', get_template_directory_uri() . '/assets/css/main-page.css');
    wp_enqueue_style('swiper-style', get_template_directory_uri() . '/assets/swiper/swiper-bundle.css', array(), null);
    wp_enqueue_script('swiper-script', get_template_directory_uri() . '/assets/swiper/swiper-bundle.js', array(), null, true);
//    wp_enqueue_script('tailwind-browser', get_template_directory_uri() . '/assets/tailwind/tailwind-bundle.js', array(), null, true);
    wp_enqueue_style('tailwind-style', get_template_directory_uri() . '/assets/tailwind/tailwind.min.css', array(), null);

    wp_enqueue_style( 'css-main-page', get_template_directory_uri() . '/assets/css/main-page.css');
    wp_enqueue_style( 'css-thank-you-page', get_template_directory_uri() . '/assets/css/thank-you-page.css');
    wp_enqueue_script( 'attr-toggle', get_template_directory_uri() . '/assets/js/attr-toggle.js');
    wp_enqueue_script( 'switch-delivery', get_template_directory_uri() . '/assets/js/switch-delivery.js');
    wp_enqueue_script( 'increase-quantity', get_template_directory_uri() . '/assets/js/increase-quantity.js');
    wp_enqueue_script( 'burger-toggle', get_template_directory_uri() . '/assets/js/burger-toggle.js');

    wp_enqueue_script( 'cart-checkout', get_template_directory_uri() . '/assets/js/cart-checkout.js', array('jquery'), null, true);
    wp_localize_script("cart-checkout", "wc_cart_params",
        [
            "ajax_url" => admin_url("admin-ajax.php"),
            'apply_coupon_nonce' => wp_create_nonce('apply_coupon'),
            'update_order_nonce' => wp_create_nonce('woocommerce-process-checkout'),
        ]);
    wp_enqueue_script( 'switch-size', get_template_directory_uri() . '/assets/js/switch-size.js', array(), null, true );
    wp_enqueue_script('remove-item', get_template_directory_uri() . '/assets/js/remove-item.js', array('jquery'), null, true);
    wp_localize_script('remove-item', 'ajaxurl', admin_url('admin-ajax.php'));

    wp_enqueue_script( 'delivery_zones', get_template_directory_uri() . '/assets/js/delivery_zones.js', array('jquery'));
    wp_enqueue_script( 'work-time', get_template_directory_uri() . '/assets/js/work-time.js', array('jquery'));

    if (is_page('promo') || $post->post_type === "promo_type") {
        wp_enqueue_style( 'promo', get_template_directory_uri() . '/assets/css/promo.css');
    }
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

add_action('template_redirect', 'custom_redirect_on_payment_failure');

function custom_redirect_on_payment_failure() {
    if (!empty($_GET['pay_for_order']) && !empty($_GET['key'])) {
        $allowed_host = parse_url(home_url(), PHP_URL_HOST);
        $current_host = $_SERVER['HTTP_HOST'];

        if ($current_host === $allowed_host) {
            wp_safe_redirect(home_url('/checkout/'));
            exit;
        }
    }
}

add_action('woocommerce_cart_calculate_fees', function(WC_Cart $cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $chosen_method = WC()->session->get('chosen_shipping_methods')[0] ?? '';

    // Получаем ID по slug
    $kupecheskaya_id = wc_get_product_id_by_slug('merchant');
    $kupecheskaya_obj = wc_get_product($kupecheskaya_id);
    $kupecheskaya_id = $kupecheskaya_obj->get_id();
    $pepperoni_id = wc_get_product_id_by_slug('pepperoni');
    $pepperoni_obj = wc_get_product($pepperoni_id);
    $pepperoni_id = $pepperoni_obj->get_id();

    $has_kupecheskaya = false;
    $kupecheskaya_item_key = null;
    $has_other_pizza = false;
    $total_cart_amount = 0;
    $pepperoni_in_cart = false;
    $self_pickup_discount = 0;
    $has_combo = false;

    // Подсчёт скидки за самовывоз
    if (strpos($chosen_method, 'pickup_location') !== false) {
        foreach ($cart->get_cart() as $cart_item) {
            $product = $cart_item['data'];
            if (!has_term('sale', 'product_tag', $product->get_id())) {
                $item_subtotal = $product->get_price() * $cart_item['quantity'];
                $self_pickup_discount += $item_subtotal * 0.20;
            }
        }
    }

    // Анализ корзины
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $product_id = $product->get_id();

        $parent_id = $product->is_type('variation') ? $product->get_parent_id() : $product_id;

        if ($parent_id == $kupecheskaya_id && has_term('pizza', 'product_cat', $parent_id)) {
            $has_kupecheskaya = true;
            $kupecheskaya_item_key = $cart_item_key;
        }

        if ($parent_id != $kupecheskaya_id && has_term('pizza', 'product_cat', $parent_id)) {
            $has_other_pizza = true;
        }

        if ($parent_id == $pepperoni_id) {
            $pepperoni_in_cart = true;
        }

        if (has_term('combo', 'product_cat', $parent_id)) {
            $has_combo = true;
        }

        $total_cart_amount += $product->get_price() * $cart_item['quantity'];
    }

    $possible_discounts = [];

    // 1. Скидка за самовывоз
    if ($self_pickup_discount > 0) {
        $possible_discounts['pickup'] = [
            'amount' => $self_pickup_discount,
            'label' => 'Скидка за самовывоз (без товаров со скидкой)'
        ];
    }

    // 2. Купеческая за полцены
//    if ($has_kupecheskaya && $has_other_pizza) {
//        $kupecheskaya_item = $cart->get_cart()[$kupecheskaya_item_key];
//        $kupecheskaya_price = $kupecheskaya_item['data']->get_price();
//        $kupecheskaya_discount = $kupecheskaya_price * 0.5 * $kupecheskaya_item['quantity'];
//        $possible_discounts['kupecheskaya'] = [
//            'amount' => $kupecheskaya_discount,
//            'label' => 'Скидка 50% на Купеческую пиццу'
//        ];
//    }

    // 3. Пепперони в подарок (четверг, заказ от 30р)
    $day_number = date('N');
    if ($day_number == '4' && $total_cart_amount >= 50 && $pepperoni_in_cart && !$has_combo) {
        $pepperoni_product = wc_get_product($pepperoni_id);
        if ($pepperoni_product) {
            $pepperoni_price = $pepperoni_product->get_price();
            $possible_discounts['pepperoni'] = [
                'amount' => $pepperoni_price,
                'label' => 'Пицца Пепперони в подарок (четверг при заказе от 30р)'
            ];
        }
    }

    // Выбираем максимальную скидку
    if (!empty($possible_discounts)) {
        $max_discount = array_reduce($possible_discounts, function($carry, $item) {
            return ($carry === null || $item['amount'] > $carry['amount']) ? $item : $carry;
        });

        $cart->add_fee($max_discount['label'], -$max_discount['amount']);
        WC()->session->set('applied_discount_label', $max_discount['label']);
    } else {
        WC()->session->__unset('applied_discount_label'); // если скидки нет, удаляем
    }

});

// Функция для получения ID продукта по slug
function wc_get_product_id_by_slug($slug) {
    $product = get_page_by_path($slug, OBJECT, 'product');
    return $product ? $product->ID : 0;
}


function load_product_category_template($template) {
    if (is_tax('product_cat')) {
        $new_template = locate_template(['taxonomy-product_cat.php']);
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_product_category_template');

add_action( 'init', 'register_post_types' );
function register_post_types(){

    flush_rewrite_rules( false );

    register_post_type( 'promo_type', [
        'label'  => null,
        'labels' => [
            'name'               => 'Акции',
            'singular_name'      => 'Акция',
            'add_new'            => 'Добавить Акцию',
            'add_new_item'       => 'Добавить Акцию',
            'edit_item'          => 'Редаактировать акцию',
            'new_item'           => 'Новая акция',
            'view_item'          => 'Просмотр акции',
            'search_items'       => 'Поиск акции',
            'not_found'          => 'Не Найдено',
            'not_found_in_trash' => 'Не найдено',
            'parent_item_colon'  => '',
            'menu_name'          => 'Акции',
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => true,
        'show_in_rest'        => null,
        'rest_base'           => null,
        'menu_position'       => null,
        'menu_icon'           => null,
        'hierarchical'        => false,
        'supports'            => [ 'title', 'editor' ],
        'taxonomies'          => [],
        'has_archive'         => false,
        'rewrite'             => true,
        'query_var'           => true,
    ] );
}