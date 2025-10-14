<div class="pizza" id="Dessert">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Десерты
            </div>
        </div>
        <div class="grid">
            <?php
            $category_slug = 'desserts';
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
                    $cart_quantity = 0;
                    if (function_exists('WC') && WC()->cart) {
                        foreach (WC()->cart->get_cart() as $cart_item) {
                            $matchedId = $cart_item['variation_id'] ?: $cart_item['product_id'];
                            if ((int)$matchedId === (int)$product->get_id()) {
                                $cart_quantity = (int)$cart_item['quantity'];
                                break;
                            }
                        }
                    }
                    ?>

                    <div class="pizza__item">
                        <div class="new flex">
                            <?php showTags($product); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>">
                            <div class="pizza__image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="pizza__title">
                            <?php the_title(); ?>
                        </div>
                        <div class="pizza__weight">
                            <?php
                            $weight = $product->get_weight(); // Получаем вес
                            if ($weight) {
                                echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                            } else {
                                echo '<p>Вес не указан</p>';
                            }
                            ?>
                        </div>
                        <div class="pizza__subtitle">
                            <?php echo $product->get_short_description(); ?>
                        </div>
                        <div class="pizza__price flex product-<?php echo $product->get_id(); ?>">
                            <div class="price">
                                <?php echo $product->get_price_html(); ?>
                            </div>

                            <div class="count-container<?php echo $cart_quantity === 0 ? ' hide' : ''; ?>">
                                <div class="flex">
                                    <div class="basket">
                                        <a href="#" class="add-to-cart">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/minus.svg" alt="add-to-cart">
                                        </a>
                                    </div>
                                </div>
                                <input type="text" name="count" value="<?php echo $cart_quantity > 0 ? $cart_quantity : 1; ?>" disabled>
                                <div class="flex">
                                    <div class="basket">
                                        <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="add-container<?php echo $cart_quantity > 0 ? ' hide' : ''; ?>">
                                <div class="flex">
                                    <div class="basket">
                                        <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>