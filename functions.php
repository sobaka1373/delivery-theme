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
    wp_enqueue_style('swiper-style', get_template_directory_uri() . '/assets/swiper/swiper-bundle.css', array(), null);
    wp_enqueue_script('swiper-script', get_template_directory_uri() . '/assets/swiper/swiper-bundle.js', array(), null, true);
    wp_enqueue_script('tailwind-browser', get_template_directory_uri() . '/assets/tailwind/tailwind-bundle.js', array(), null, true);
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
            wp_safe_redirect(home_url('/checkout'));
            exit;
        }
    }
}

add_action('woocommerce_cart_calculate_fees', function(WC_Cart $cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $chosen_method = WC()->session->get('chosen_shipping_methods')[0] ?? '';

    // Проверьте, содержит ли метод доставки 'local_pickup'
    if (strpos($chosen_method, 'pickup_location') !== false) {
        $discount = WC()->cart->subtotal * 0.20;
        WC()->cart->add_fee('Скидка за самовывоз', -$discount);
    }

});

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

function pizza_buy_2_get_1_free_discount() {
    $cart = WC()->cart;
    $pizza_category = 'pizza'; // Укажи slug категории пиццы
    $eligible_items = [];

    // Проходим по товарам в корзине
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $product_id = $product->get_id();
        $parent_id = $product->get_parent_id(); // Получаем ID родительского товара

        // Если это вариация, проверяем категорию у родителя
        if ($parent_id) {
            $product_id = $parent_id;
        }

        // Проверяем, принадлежит ли товар к категории пиццы
        if (has_term($pizza_category, 'product_cat', $product_id)) {
            // Добавляем товар столько раз, сколько его единиц в корзине
            for ($i = 0; $i < $cart_item['quantity']; $i++) {
                $eligible_items[] = $cart_item;
            }
        }
    }

    // Применяем скидку, если в корзине минимум 3 пиццы
    if (count($eligible_items) >= 3) {
        // Сортируем пиццы по цене (от дешевой к дорогой)
        usort($eligible_items, function($a, $b) {
            return $a['data']->get_price() <=> $b['data']->get_price();
        });

        // Получаем самую дешевую пиццу
        $cheapest_item = $eligible_items[0];
        $cheapest_price = $cheapest_item['data']->get_price();

        // Добавляем скидку
        $cart->add_fee(__('Акция 1+1=3 Скидка: ', 'woocommerce'), -$cheapest_price, false);
    }
}
add_action('woocommerce_cart_calculate_fees', 'pizza_buy_2_get_1_free_discount');

function get_pizza_discount_html() {
    if (!WC()->cart) {
        wp_send_json_error();
    }

    $cart = WC()->cart;
    $pizza_category = 'pizza';
    $eligible_items = [];

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $product_id = $product->get_id();
        $parent_id = $product->get_parent_id();

        if ($parent_id) {
            $product_id = $parent_id;
        }

        if (has_term($pizza_category, 'product_cat', $product_id)) {
            for ($i = 0; $i < $cart_item['quantity']; $i++) {
                $eligible_items[] = $cart_item;
            }
        }
    }

    if (count($eligible_items) >= 3) {
        usort($eligible_items, function ($a, $b) {
            return $a['data']->get_price() <=> $b['data']->get_price();
        });

        $cheapest_item = $eligible_items[0];
        $cheapest_price = wc_price($cheapest_item['data']->get_price());

        ob_start();
        ?>
        <tr class="fee">
            <th>Акция 1+1=3 Скидка:</th>
            <td><span class="woocommerce-Price-amount amount"><?php echo $cheapest_price; ?></span></td>
        </tr>
        <?php
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_success('');
    }
}

add_action('wp_ajax_get_pizza_discount_html', 'get_pizza_discount_html');
add_action('wp_ajax_nopriv_get_pizza_discount_html', 'get_pizza_discount_html');
