<?php
/**
 * Template Name: Cart Page
 * Description: Custom cart template in the style of Domino's.
 */

get_header(); ?>

<div class="cart-container center container mx-auto px-4 py-6">

  <div class="flex-container">
    <div class="left">
      <h1 class="cart__title text-2xl font-bold mb-6">Ваш заказ</h1>

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
                  <li class="cart-item flex items-center  justify-content py-4">
                    <div class="cart-item flex items-center">
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
                              <div class="item__size">40 см | 550г</div>
                            <?php else: ?>
                                <?php echo esc_html($product_name); ?>
                            <?php endif; ?>
                        </h2>
                      </div>
                    </div>
                    <div class="cart-item-actions flex items-center space-x-4">
                      <div class="quantity">
                        <form method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                          <div class="flex">
                            <div class="decrease">
                              &#8722;
                            </div>
                            <input type="number" name="cart[<?php echo $cart_item_key; ?>][qty]"
                                   value="<?php echo esc_attr($product_quantity); ?>" min="1"
                                   class="w-16 text-center border border-gray-300 rounded"/>
                            <div class="increase">
                              &#43;
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="text-sm text-gray-500"> <?php echo $product_price; ?></div>
                      <div class="remove">
                          <?php echo sprintf('<a href="%s" class="text-red-500 hover:underline text-sm">Удалить</a>', esc_url(wc_get_cart_remove_url($cart_item_key))); ?>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
            </ul>
          </div>
          <div class="promo flex">
            <div>
              Промокод:
              <div>
                <input type="text"/>
              </div>
            </div>
            <button class="coupon-add" type="submit" class="text-sm text-blue-600 hover:underline">Применить
            </button>
          </div>
          <div class="cart-summary bg-gray-100 shadow-md rounded-lg p-4">
            <div class="summary-total flex justify-between text-lg font-semibold">
              <span>Итого:</span>
              <span><?php echo WC()->cart->get_total(); ?></span>
            </div>
          </div>

          <div class="cart-actions mt-6 flex justify-end space-x-4">
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
               class="ordering bg-blue-500 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-600">К оформлению</a>
            <a href="<?php echo esc_url(home_url('/shop')); ?>"
               class="continue bg-gray-300 text-black py-2 px-6 rounded-lg shadow hover:bg-gray-400">Продолжить покупки</a>
          </div>
        <?php else: ?>
          <div class="empty-cart text-center py-12">
            <p class="text__empty text-lg text-gray-600 mb-4">В вашей корзина пока пусто. 😞</p>
            <a href="<?php echo esc_url(home_url('/shop')); ?>"
               class="button__return bg-blue-500 text-white py-3 px-8 rounded-lg shadow hover:bg-blue-600">Перейти в
              магазин</a>
          </div>
        <?php endif; ?>
    </div>

    <div class="right">
      <h1 class="title text-3xl font-bold mb-6">Оформление заказа</h1>

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
  </div>


</div>

<?php get_footer(); ?>
