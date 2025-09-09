<?php
/**
 * Template Name: Custom Checkout Page
 */

get_header(); ?>

<div class="hidden-basket">
    <?php
    if (class_exists('WooCommerce')) {
        // Проверка, если корзина пуста
        if (WC()->cart->is_empty()) {
            echo '<p class="text-red-500">Ваша корзина пуста. Пожалуйста, добавьте товары в корзину перед оформлением заказа.</p>';
            echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Перейти в магазин</a>';
        } else {
            // Отображение стандартной формы оформления заказа WooCommerce
            echo do_shortcode('[woocommerce_checkout]');
        }
    } else {
        echo '<p class="text-red-500">WooCommerce не установлен или отключен.</p>';
    }
    ?>
</div>

<div class="basket container center">
    <div class="basket__title">
        Ваш заказ
    </div>
    <div class="basket-mobile-flex flex justify-content center">
        <div class="basket__information">
            <?php if (WC()->cart->get_cart_contents_count() > 0):
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):
                    $_product = $cart_item['data'];
                    $product_id = $cart_item['product_id'];
                    $product_name = $_product->get_name();
                    $attributes = $_product->get_attributes();
                    if (!empty($attributes) && isset($attributes['pa_size'])) {
                        $product_name = str_replace(array(' - 30 см', ' - 40 см'), '', $product_name);
                    }
                    $product_permalink = $_product->is_visible() ? $_product->get_permalink() : '';
                    $product_image = $_product->get_image('thumbnail');
                    $product_price = wc_price($_product->get_price());
                    $product_quantity = $cart_item['quantity'];?>
                    <div class="item flex cart_item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                        <?php if ($product_permalink): ?>
                            <a href="<?php echo esc_url($product_permalink); ?>">
                                <?php echo $product_image; ?>
                            </a>
                        <?php else: ?>
                            <?php echo $product_image; ?>
                        <?php endif; ?>
                        <div class="name flex">
                            <div class="item__name">
                                <?php if ($product_permalink): ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>" class="hover:underline">
                                        <p><?php echo esc_html($product_name); ?></p>
                                    </a>
                                <?php else: ?>
                                    <?php echo esc_html($product_name); ?>
                                <?php endif; ?>
                                <div class="flex">
                                    <div class="weight">
                                        <?php if(!empty($_product->get_weight())) :
                                            echo $_product->get_weight() . "г";
                                        endif; ?>
                                    </div>
                                    |
                                    <div class="size">
                                        <?php
                                        if (!empty($attributes) && isset($attributes['pa_size'])) {
                                            if ($attributes['pa_size'] === '30cm') {
                                                echo '30 см';
                                            } elseif ($attributes['pa_size'] === '40cm') {
                                                echo '40 см';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="quantity" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                                <form method="post" action="">
                                    <div class="flex">
                                        <div class="decrease">
                                            &#8722;
                                        </div>
                                        <input type="text"
                                               name="cart[<?php echo $cart_item_key; ?>][qty]"
                                               value="<?php echo esc_attr($product_quantity); ?>"
                                               min="1"
                                               class="w-16 text-center border border-gray-300 rounded"
                                               disabled/>
                                        <div class="increase">
                                            &#43;
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="price">
                                <?php echo $product_price; ?>
                            </div>
                            <div class="delete">
                                <a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>">
                                    &#10006;
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="additional">
                <div class="justify-content flex title-container">
                    <div class="add-sos">
                        Добавить соусы?
                    </div>
                    <div class="add-button">
                        &#43;
                    </div>
                </div>
                <?php
                $category_slug = 'sauces';
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $category_slug,
                        ),
                    ),
                );
                $query = new WP_Query($args);


                if ($query->have_posts()) : ?>
                    <?php while ($query->have_posts()) :
                        $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        ?>

                        <div class="add-sos justify-content flex">

                            <div class="grid-third">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php endif; ?>
                                <div class="name">
                                    <?php echo esc_html($product->get_name()); ?>
                                </div>
                                <div class="price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                            </div>
                            <div class="add-button-sos">
                                <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                                    &#43;
                                </a>
                            </div>
                        </div>

                    <?php endwhile;
                endif; ?>
            </div>
        </div>
        <div class="basket__promo">
            <div class="promocode-container">
                Промокод:
                <div class="flex">
                    <div class="promo-input">
                        <input id="coupon-input" type="text"/>
                    </div>
                    <button type="submit" class="coupon-add text-sm text-blue-600 hover:underline">Применить
                    </button>
                </div>
                <p id="coupon_message" style="display:none;"></p>
                <div class="applied-coupon">
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
                </div>
            </div>
            <div class="custom-fee">
                <?php
                $applied_discount_label = WC()->session->get('applied_discount_label');
                if (!empty($applied_discount_label)) {
                    echo '<p class="applied-discount">Применена скидка: ' . esc_html($applied_discount_label) . '</p>';
                }
                ?>
            </div>
            <div class="total">
                Итого: <?php echo WC()->cart->get_total(); ?>
            </div>
            <div class="custom-error-container" style="display: none">
                <?php wc_print_notices(); ?>
            </div>

            <div class="basket__delivery">
                Оформление заказа
                <div class="delivery-self flex">
                    <div class="delivery active" id="deliveryButton1">
                        Доставка
                    </div>
                    <div class="self-delivery" id="deliveryButton2">
                        Самовывоз
                    </div>
                </div>
                <div class="delivery-information">
                    <div id="delivery-price">
                        <p class="delivery-info">
                            Суммы для бесплатной доставки:
                        </p>
                        <p class="delivery-info">
                            Зеленая зона: 25 руб, иначе доставка 6 руб
                        </p>
                        <p class="delivery-info">
                            Желтая зона: 35 руб, иначе доставка 8 руб
                        </p>
                        <p class="delivery-info">
                            Красная зона: 45 руб, иначе доставка 10 руб
                        </p>
                        <!--                        <p class="delivery-info" style="color: red">-->
                        <!--                           Доставка сета Сливок всегда платная!-->
                        <!--                        </p>-->
                        <!--                        <p class="delivery-info">-->
                        <!--                            Зеленая зона - 5р, Желатая - 6р, Красная - 7р-->
                        <!--                        </p>-->
                        <p>
                            Стоимость доставки:
                            <span id="delivery-price-value">
                  Мин 6,00 Br Введите адрес
                  </span>
                        </p>
                    </div>
                    <div id="pickup-location">
                        <p>
                            Самовывоз - 20%
                        </p>
                        <p>
                            * не распространяется на комбо и напитки
                        </p>
                    </div>
                    <input id="billing_phone" type="text" placeholder="Ваш номер*"/>
                    <input id="billing_first_name" type="text" placeholder="Вашe имя*"/>
                    <input id="billing_address_2" type="text" placeholder="Улица"/>
                    <input id="billing_address_house" type="text" placeholder="Дом"/>
                    <div class="flex billing-container">
                        <input id="billing_address_korp" type="text" placeholder="Корпус"/>
                        <input id="billing_address_pod" type="text" placeholder="Подъезд"/>
                    </div>
                    <div class="flex billing-container">
                        <input id="billing_address_flat" type="text" placeholder="Квартира"/>
                        <input id="billing_address_floor" type="text" placeholder="Этаж"/>
                    </div>
                    <input type="text" id="billing_address_note" placeholder="Примечание к заказу"/>
                    <div id="map"></div>
                    <p id="notice">Адрес не найден</p>
                    <div id="footer">
                        <div id="messageHeader"></div>
                        <div id="message"></div>
                    </div>
                    <div class="payment-type">
                        <label>
                            <input type="radio" name="payment" value="cash" checked>
                            Оплата наличными
                        </label>
                        <label>
                            <input type="radio" name="payment" value="online">
                            Оплата онлайн
                        </label>
                    </div>

                </div>
                <button type="submit" class="complete-order text-sm text-blue-600 hover:underline">Оформить
                </button>
            </div>
        </div>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<?php get_footer(); ?>
