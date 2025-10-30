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

    if (!is_null($post) && (is_page('promo') || $post->post_type === "promo_type")) {
        wp_enqueue_style( 'promo', get_template_directory_uri() . '/assets/css/promo.css');
    }

    wp_enqueue_script('dilivery-add-to-cart-ajax', get_template_directory_uri() . '/assets/js/add-to-cart-ajax.js', ['jquery'], null, true);
    wp_localize_script('dilivery-add-to-cart-ajax', 'wc_add_to_cart_params', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
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

    // Определяем ID продукта (учитываем вариации) до удаления
    $removed_product_id = 0;
    $cart_item = $cart->get_cart_item($cart_item_key);
    if ($cart_item) {
        $removed_product_id = (int) ($cart_item['variation_id'] ?: $cart_item['product_id']);
    }

    $cart->remove_cart_item($cart_item_key);
    WC()->cart->calculate_totals();

    $new_total = WC()->cart->get_total();
    $new_discount = wc_price(WC()->cart->get_discount_total());
    $cart_empty = WC()->cart->is_empty();

    $fragments = [
        '.basket-dropdown' => render_basket_dropdown_html(),
        '.basket-icon .cart-total-text' => '<p class="cart-total-text">' . WC()->cart->get_cart_total() . '</p>',
    ];

    wp_send_json_success([
        'new_total' => $new_total,
        'new_discount' => $new_discount,
        'cart_empty' => $cart_empty,
        'removed_product_id' => $removed_product_id,
        'fragments' => $fragments,
        'basket_information' => render_checkout_basket_information_html(),
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
    // Поддержка двух режимов: по cart_item_key и по product_id (вариация)
    $quantity = isset($_POST['quantity']) ? (int) sanitize_text_field($_POST['quantity']) : null;
    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : null;
    $product_id = isset($_POST['product_id']) ? (int) sanitize_text_field($_POST['product_id']) : null;

    if ($quantity === null) {
        wp_send_json_error("Неверные данные");
    }

    $updated = false;

    if ($cart_item_key) {
        $updated = WC()->cart->set_quantity($cart_item_key, $quantity, true);
    } elseif ($product_id) {
        // Найти позицию в корзине по product_id (поддержка вариаций)
        foreach (WC()->cart->get_cart() as $key => $item) {
            $matchedProductId = $item['variation_id'] ?: $item['product_id'];
            if ((int)$matchedProductId === (int)$product_id) {
                if ($quantity > 0) {
                    $updated = WC()->cart->set_quantity($key, $quantity, true);
                } else {
                    $updated = WC()->cart->remove_cart_item($key);
                }
                break;
            }
        }
    }

    if ($updated) {
        WC()->cart->calculate_totals();
        $fragments = [
            '.basket-dropdown' => render_basket_dropdown_html(),
            '.basket-icon .cart-total-text' => '<p class="cart-total-text">' . WC()->cart->get_cart_total() . '</p>',
        ];
        wp_send_json_success([
            'message' => 'Количество обновлено',
            'fragments' => $fragments,
            'basket_information' => render_checkout_basket_information_html(),
        ]);
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

    try {
        // Получаем ID продуктов, участвующих в акциях дня недели
        $pepperoni_id = wc_get_product_id_by_slug('pepperoni');
        if ($pepperoni_id) {
            $pepperoni_obj = wc_get_product($pepperoni_id);
            $pepperoni_id = $pepperoni_obj ? $pepperoni_obj->get_id() : 0;
        }

        $ham_mushrooms_id = wc_get_product_id_by_slug('ham-and-mushrooms');
        if ($ham_mushrooms_id) {
            $ham_mushrooms_obj = wc_get_product($ham_mushrooms_id);
            $ham_mushrooms_id = $ham_mushrooms_obj ? $ham_mushrooms_obj->get_id() : 0;
        }

        // Служебные переменные
        $total_cart_amount     = 0;
        $pepperoni_in_cart     = false;
        $ham_mushrooms_in_cart = false;
        $self_pickup_discount  = 0;
        $has_combo             = false;

        $pizza_count       = 0;
        $pizza_total_price = 0;
        $pizzas_in_cart    = [];

        $is_pickup = strpos($chosen_method, 'pickup_location') !== false;

        /**
         * ---- Подсчёт скидки за самовывоз ----
         * Правила:
         * - Никогда НЕ скидка на: tag 'sale', cat 'drinks', cat 'combo'.
         * - Всегда 20% на eligible товары.
         */
        if ($is_pickup) {
            foreach ($cart->get_cart() as $cart_item) {
                /** @var WC_Product $product */
                $product    = $cart_item['data'];
                $qty        = (int) $cart_item['quantity'];
                $product_id = $product->get_id();
                $parent_id  = $product->is_type('variation') ? $product->get_parent_id() : $product_id;

                // Исключения
                if (
                    has_term('sale', 'product_tag', $product_id) ||
                    has_term('drinks', 'product_cat', $parent_id) ||
                    has_term('combo',  'product_cat', $parent_id)
                ) {
                    continue;
                }

                $discount_rate = 0.20; // фиксированная скидка
                $line_subtotal = $product->get_price() * $qty;
                $self_pickup_discount += $line_subtotal * $discount_rate;
            }
        }

        // ---- Анализ корзины для остальных акций ----
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            /** @var WC_Product $product */
            $product    = $cart_item['data'];
            $qty        = (int) $cart_item['quantity'];
            $product_id = $product->get_id();
            $parent_id  = $product->is_type('variation') ? $product->get_parent_id() : $product_id;

            $is_pizza = has_term('pizza', 'product_cat', $parent_id);

            if ($is_pizza) {
                $pizza_count       += $qty;
                $pizza_total_price += $product->get_price() * $qty;

                for ($i = 0; $i < $qty; $i++) {
                    $pizzas_in_cart[] = [
                        'id'    => $parent_id,
                        'price' => $product->get_price(),
                        'key'   => $cart_item_key,
                    ];
                }
            }

            if ($parent_id == $pepperoni_id) {
                $pepperoni_in_cart = true;
            }

            if ($parent_id == $ham_mushrooms_id) {
                $ham_mushrooms_in_cart = true;
            }

            if (has_term('combo', 'product_cat', $parent_id)) {
                $has_combo = true;
            }

            $total_cart_amount += $product->get_price() * $qty;
        }

        $possible_discounts = [];

        // 1. Скидка за самовывоз
        if ($self_pickup_discount > 0) {
            $pickup_discount_label = 'Скидка 20% за самовывоз';
            $possible_discounts['pickup'] = [
                'amount' => $self_pickup_discount,
                'label'  => $pickup_discount_label,
            ];
        }

        $day_number = date('N');

        // 2. Пицца Пепперони в подарок (четверг, заказ от 30р)
        if ($day_number == '4' && $total_cart_amount >= 30 && $pepperoni_in_cart && !$has_combo) {
            $pepperoni_product = wc_get_product($pepperoni_id);
            if ($pepperoni_product) {
                $pepperoni_price = $pepperoni_product->get_price();
                $possible_discounts['pepperoni'] = [
                    'amount' => $pepperoni_price,
                    'label'  => 'Пицца Пепперони в подарок (четверг при заказе от 30р)',
                ];
            }
        }

        // 3. Пицца Ветчина и грибы в подарок (среда, заказ от 30р)
        if ($day_number == '3' && $total_cart_amount >= 30 && $ham_mushrooms_in_cart && !$has_combo) {
            $ham_mushrooms_product = wc_get_product($ham_mushrooms_id);
            if ($ham_mushrooms_product) {
                $ham_mushrooms_price = $ham_mushrooms_product->get_price();
                $possible_discounts['ham_mushrooms'] = [
                    'amount' => $ham_mushrooms_price,
                    'label'  => 'Пицца Ветчина и грибы в подарок (среда при заказе от 30р)',
                ];
            }
        }

        // 4. Скидка 60% на вторую пиццу (понедельник)
        if ($day_number == '1' && count($pizzas_in_cart) >= 2 && !$has_combo) {
            $prices = array_column($pizzas_in_cart, 'price');
            $min_price = min($prices);

            // Размер скидки - 60% от самой дешевой пиццы
            $discount_on_cheapest_pizza = $min_price * 0.6;

            $possible_discounts['monday_second_pizza'] = [
                'amount' => $discount_on_cheapest_pizza,
                'label'  => 'Скидка 60% на пиццу меньшей стоимости (понедельник)',
            ];
        }

        // 5. Акция "1 + 1 = 3" (вторник)
        if ($day_number == '2' && count($pizzas_in_cart) >= 3 && !$has_combo) {
            usort($pizzas_in_cart, function($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            $cheapest_pizza_discount = $pizzas_in_cart[0]['price'];
            $possible_discounts['tuesday_three_pizzas'] = [
                'amount' => $cheapest_pizza_discount,
                'label'  => 'Акция 1 + 1 = 3: третья пицца бесплатно (вторник)',
            ];
        }

        // 5.1 Акция 1+1=3 на самовывоз (31.10–02.11)
        $current_date = date('Y-m-d');
        $special_start = '2025-10-31';
        $special_end   = '2025-11-02';

        if (
            $is_pickup &&
            $current_date >= $special_start &&
            $current_date <= $special_end &&
            count($pizzas_in_cart) >= 3 &&
            !$has_combo
        ) {
            usort($pizzas_in_cart, function($a, $b) {
                return $a['price'] <=> $b['price'];
            });

            $cheapest_pizza_discount = $pizzas_in_cart[0]['price'];

            $possible_discounts['weekend_three_pizzas'] = [
                'amount' => $cheapest_pizza_discount,
                'label'  => 'Акция 1 + 1 = 3 (только самовывоз, выходные)'
            ];
        }

        // 6. Скидка за количество пицц при доставке
        if (!$is_pickup && $pizza_count >= 3) {
            $percent_discount = 0;
            $discount_label   = '';

            if ($pizza_count >= 10) {
                $percent_discount = 0.25;
                $discount_label   = 'Скидка 25% за 10 и более пицц при доставке';
            } elseif ($pizza_count >= 7) {
                $percent_discount = 0.20;
                $discount_label   = 'Скидка 20% за 7 пицц при доставке';
            } elseif ($pizza_count >= 5) {
                $percent_discount = 0.15;
                $discount_label   = 'Скидка 15% за 5 пицц при доставке';
            } elseif ($pizza_count >= 3) {
                $percent_discount = 0.10;
                $discount_label   = 'Скидка 10% за 3 пиццы при доставке';
            }

            if ($percent_discount > 0) {
                $pizza_discount = $pizza_total_price * $percent_discount;
                $possible_discounts['pizza_quantity'] = [
                    'amount' => $pizza_discount,
                    'label'  => $discount_label,
                ];
            }
        }

        // Применяем максимальную скидку
        if (!empty($possible_discounts)) {
            $max_discount = array_reduce($possible_discounts, function($carry, $item) {
                return ($carry === null || $item['amount'] > $carry['amount']) ? $item : $carry;
            });
            $cart->add_fee($max_discount['label'], -$max_discount['amount']);
            WC()->session->set('applied_discount_label', $max_discount['label']);
        } else {
            WC()->session->__unset('applied_discount_label');
        }
    } catch (Exception $exception) {
        error_log($exception->getMessage());
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

add_action('wp_ajax_custom_add_to_cart', 'custom_add_to_cart');
add_action('wp_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart');

function custom_add_to_cart() {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        $fragments = [
            '.basket-dropdown' => render_basket_dropdown_html(),
            '.basket-icon .cart-total-text' => '<p class="cart-total-text">' . WC()->cart->get_cart_total() . '</p>',
        ];

        wp_send_json_success([
            'fragments' => $fragments,
            'basket_information' => render_checkout_basket_information_html(),
        ]);
    } else {
        wp_send_json_error('Не удалось добавить товар');
    }
}


function render_basket_dropdown_html() {
    ob_start();

    $cart = WC()->cart->get_cart();
    ?>
    <div class="basket-dropdown">
        <?php if (!empty($cart)): ?>
            <ul>
                <?php foreach ($cart as $cart_item_key => $cart_item):
                    $hasVariation = $cart_item['variation_id'] && !empty($cart_item['variation']);
                    $product = $hasVariation ?
                        wc_get_product($cart_item['variation_id']) :
                        wc_get_product($cart_item['product_id']);
                    $quantity = $cart_item['quantity'];
                    $price = $product->get_price() * $quantity;
                    ?>
                    <li class="basket-item-wrapper" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                        <div class="basket-item-image-wrapper">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url($cart_item['product_id'], 'thumbnail')); ?>"
                                 alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy">
                        </div>
                        <div class="basket-item-info">
                            <div class="basket-item-name">
                                <p><?php echo esc_html($product->get_name()); ?></p>
                            </div>
                            <div class="basket-item-description">
                                <p><?php echo esc_html($quantity); ?> шт</p>
                                <p><?php echo wc_price($price); ?></p>
                            </div>
                        </div>
                        <div class="remove-item">
                            <button data-cart-item="<?php echo esc_attr($cart_item_key); ?>">✖</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="cart-total">
                <?php
                $applied_coupons = WC()->cart->get_applied_coupons();
                if (!empty($applied_coupons)):
                    foreach ($applied_coupons as $key => $coupon):
                        $coupon_obj = new WC_Coupon($coupon);
                        ?>
                        <div class="cart-discount">
                            <p>Промокод: <?php echo esc_html($coupon_obj->get_code()); ?></p>
                            <p>Скидка: -
                                <span class="cart-discount-text">
                              <?php echo wc_price(WC()->cart->get_cart_discount_total()); ?>
                          </span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <p>Итого:
                    <span class="cart-total-text">
                        <?php echo WC()->cart->get_cart_total(); ?>
                    </span>
                </p>
            </div>
        <?php else: ?>
            <p>Корзина пуста</p>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

// Renders the basket items list used on checkout/cart pages for AJAX updates
function render_checkout_basket_information_html() {
    ob_start();
    if (WC()->cart && WC()->cart->get_cart_contents_count() > 0):
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):
            $_product = $cart_item['data'];
            $product_name = $_product->get_name();
            $attributes = $_product->get_attributes();
            if (!empty($attributes) && isset($attributes['pa_size'])) {
                $product_name = str_replace(array(' - 30 см', ' - 40 см'), '', $product_name);
            }
            $product_permalink = $_product->is_visible() ? $_product->get_permalink() : '';
            $product_image = $_product->get_image('thumbnail');
            $product_price = wc_price($_product->get_price());
            $product_quantity = (int) $cart_item['quantity'];
            ?>
            <div class="item flex cart_item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <?php if ($product_permalink): ?>
                    <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $product_image; ?></a>
                <?php else: ?>
                    <?php echo $product_image; ?>
                <?php endif; ?>
                <div class="name flex">
                    <div class="item__name">
                        <?php if ($product_permalink): ?>
                            <a href="<?php echo esc_url($product_permalink); ?>" class="hover:underline"><p><?php echo esc_html($product_name); ?></p></a>
                        <?php else: ?>
                            <?php echo esc_html($product_name); ?>
                        <?php endif; ?>
                    </div>
                    <div class="quantity" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                        <div class="flex">
                            <div class="decrease"><img src="<?php echo get_template_directory_uri(); ?>/assets/svg/minus.svg" alt="minus"></div>
                            <input type="text"
                                   name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
                                   value="<?php echo esc_attr($product_quantity); ?>"
                                   min="1"
                                   class="w-16 text-center border border-gray-300 rounded"
                                   disabled />
                            <div class="increase"><img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="plus"></div>
                        </div>
                    </div>
                    <div class="price"><?php echo $product_price; ?></div>
                    <div class="delete"><button class="ajax-remove" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">✖</button></div>
                </div>
            </div>
            <?php
        endforeach;
    endif;
    return ob_get_clean();
}

add_action('woocommerce_checkout_create_order', function($order, $data) {
    $discount_label  = WC()->session->get('applied_discount_label');
    $discount_amount = 0;

    if ($discount_label) {
        $fees = WC()->cart->get_fees();
        foreach ($fees as $fee) {
            if ($fee->name === $discount_label) {
                $discount_amount = abs($fee->amount);
                break;
            }
        }

        // Сохраняем скидку в мета заказа
        $order->update_meta_data('_applied_discount_label', $discount_label);
        $order->update_meta_data('_applied_discount_amount', $discount_amount);
    }
}, 10, 2);

// functions.php
add_action('wp_ajax_get_applied_discount', 'get_applied_discount');
add_action('wp_ajax_nopriv_get_applied_discount', 'get_applied_discount');

function get_applied_discount() {
    if (!WC()->session) {
        wp_send_json_error(['message' => 'Session not found']);
    }

    $applied_discount_label = WC()->session->get('applied_discount_label');

    ob_start();
    if (!empty($applied_discount_label)) {
        echo '<p class="applied-discount">Применена скидка: ' . esc_html($applied_discount_label) . '</p>';
    }
    $html = ob_get_clean();

    wp_send_json_success(['html' => $html]);
}
