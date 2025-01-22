<?php
/**
 * Template Name: Cart Page
 * Description: Custom cart template in the style of Domino's.
 */

get_header(); ?>

<div class="cart-container container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Корзина</h1>

    <?php if (WC()->cart->get_cart_contents_count() > 0): ?>
        <div class="cart-items bg-white shadow-lg rounded-lg p-4 mb-6">
            <ul class="divide-y divide-gray-200">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):
                    $_product = $cart_item['data'];
                    $product_id = $cart_item['product_id'];
                    $product_name = $_product->get_name();
                    $product_permalink = $_product->is_visible() ? $_product->get_permalink() : '';
                    $product_image = $_product->get_image('thumbnail');
                    $product_price = wc_price($_product->get_price());
                    $product_quantity = $cart_item['quantity'];
                    ?>
                    <li class="cart-item flex items-center justify-between py-4">
                        <div class="flex items-center">
                            <div class="cart-item-image mr-4">
                                <?php if ($product_permalink): ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo $product_image; ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo $product_image; ?>
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-details">
                                <h2 class="text-lg font-semibold">
                                    <?php if ($product_permalink): ?>
                                        <a href="<?php echo esc_url($product_permalink); ?>" class="hover:underline">
                                            <?php echo esc_html($product_name); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo esc_html($product_name); ?>
                                    <?php endif; ?>
                                </h2>
                                <p class="text-sm text-gray-500">Цена: <?php echo $product_price; ?></p>
                            </div>
                        </div>
                        <div class="cart-item-actions flex items-center space-x-4">
                            <div class="quantity">
                                <form method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                                    <input type="number" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo esc_attr($product_quantity); ?>" min="1" class="w-16 text-center border border-gray-300 rounded" />
                                    <button type="submit" class="text-sm text-blue-600 hover:underline">Обновить</button>
                                </form>
                            </div>
                            <div class="remove">
                                <?php echo sprintf('<a href="%s" class="text-red-500 hover:underline text-sm">Удалить</a>', esc_url(wc_get_cart_remove_url($cart_item_key))); ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="cart-summary bg-gray-100 shadow-md rounded-lg p-4">
            <h2 class="text-xl font-bold mb-4">Итог</h2>
            <div class="summary-details flex justify-between mb-4">
                <span>Подытог:</span>
                <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </div>
            <div class="summary-details flex justify-between mb-4">
                <span>Доставка:</span>
                <span><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
            </div>
            <div class="summary-total flex justify-between text-lg font-semibold">
                <span>Общая сумма:</span>
                <span><?php echo WC()->cart->get_total(); ?></span>
            </div>
        </div>

        <div class="cart-actions mt-6 flex justify-end space-x-4">
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="bg-blue-500 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-600">Оформить заказ</a>
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="bg-gray-300 text-black py-2 px-6 rounded-lg shadow hover:bg-gray-400">Продолжить покупки</a>
        </div>
    <?php else: ?>
        <div class="empty-cart text-center py-12">
            <p class="text-lg text-gray-600 mb-4">Ваша корзина пуста. 😞</p>
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="bg-blue-500 text-white py-3 px-8 rounded-lg shadow hover:bg-blue-600">Перейти в магазин</a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
