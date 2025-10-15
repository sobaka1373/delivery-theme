<div class="same-items">
  <div class="grid">
    <div class="pizza">
      <div class="container center">
        <div class="flex justify-content">
          <div class="title">Похожие товары</div>
        </div>
        <div class="grid">
            <?php
            if (isset($terms[0]->slug)) {
                $category_slug = $terms[0]->slug;
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'orderby'        => 'rand',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => $category_slug,
                        ),
                    ),
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) :
                        $query->the_post();
                        $product = wc_get_product(get_the_ID());

                        // Подготовка данных по вариациям/количеству из корзины
                        $has_size_variations = false;
                        $sizes = [];
                        $selected_variations = [];
                        if (function_exists('WC') && WC()->cart) {
                            foreach (WC()->cart->get_cart() as $cart_item) {
                                if ($cart_item['variation_id']) {
                                    $parentId = (int) $cart_item['data']->get_parent_id();
                                    if ($parentId === (int) get_the_ID()) {
                                        $selected_variations[(int)$cart_item['variation_id']] = (int)$cart_item['quantity'];
                                    }
                                }
                            }
                        }

                        if ($product->is_type('variable')) {
                            $variations = $product->get_available_variations();
                            if (!empty($variations)) {
                                foreach ($variations as $variation) {
                                    if (!empty($variation['attributes']['attribute_pa_size'])) {
                                        $size = $variation['attributes']['attribute_pa_size'];
                                        $sizes[$size] = $variation['variation_id'];
                                        $has_size_variations = true;
                                    }
                                }
                            }
                        }

                        // Выбор активной вариации: первая выбранная, иначе первая по списку
                        $first_active_variation_id = 0;
                        if (!empty($selected_variations)) {
                            $keys = array_keys($selected_variations);
                            $first_active_variation_id = (int)$keys[0];
                        }
                        ?>

                      <div class="pizza__item">
<!--                        <div class="new flex">-->
<!--                            --><?php //showTags($product); ?>
<!--                        </div>-->
                        <a href="<?php the_permalink(); ?>">
                          <div class="pizza__image">
                              <?php if (has_post_thumbnail()) : ?>
                                  <?php the_post_thumbnail('medium',  array('loading' => 'lazy')); ?>
                              <?php endif; ?>
                          </div>
                        </a>
                        <div class="pizza__title"><?php the_title(); ?></div>

                        <div class="pizza__weight variation-container">
                            <?php if ($has_size_variations) : ?>
                                <?php foreach ($sizes as $size => $variation_id) : ?>
                                <div class="pizza__weight_val product-<?php echo $variation_id; ?>">
                                  <p>Размер: <?php echo str_replace('cm', '', $size); ?> см</p>
                                </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                              <p>Размер не указан</p>
                            <?php endif; ?>
                        </div>

                        <div class="pizza__subtitle"><?php echo $product->get_short_description(); ?></div>

                          <?php if ($has_size_variations) : ?>
                            <div class="toggle-container center">
                                <?php foreach ($sizes as $size => $variation_id) :
                                    $isActive = $first_active_variation_id ? ($variation_id === $first_active_variation_id) : ($size === array_key_first($sizes));
                                    $checked = $isActive ? 'checked' : '';
                                    $activeClass = $isActive ? ' active' : '';
                                    ?>
                                  <label class="size-toggle pizza__size<?php echo $activeClass; ?>">
                                    <input type="radio" name="pizza_size_<?php echo get_the_ID(); ?>" value="<?php echo $size; ?>" data-variation-id="<?php echo $variation_id; ?>" <?php echo $checked; ?>>
                                    <span><?php echo str_replace('cm', '', $size); ?> см</span>
                                  </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="variation-container">
                                <?php foreach ($sizes as $size => $variation_id) :
                                    $variation = new WC_Product_Variation($variation_id);
                                    $isActive = $first_active_variation_id ? ($variation_id === $first_active_variation_id) : ($size === array_key_first($sizes));
                                    $isSelected = array_key_exists($variation_id, $selected_variations);
                                    $qty = $isSelected ? (int)$selected_variations[$variation_id] : 1;
                                    ?>
                                  <div class="pizza__price flex product-<?php echo $variation_id; ?><?php echo $isActive ? ' active' : ''; ?>">
                                    <div class="price"><?php echo $variation->get_price_html(); ?></div>
                                    <div class="count-container<?php echo $isSelected ? '' : ' hide'; ?>">
                                        <div class="flex">
                                            <div class="basket">
                                                <a href="#" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/minus.svg" alt="add-to-cart">
                                                </a>
                                            </div>
                                        </div>
                                        <input type="text" name="count" value="<?php echo $qty; ?>" disabled>
                                        <div class="flex">
                                            <div class="basket">
                                                <a href="?add-to-cart=<?php echo $variation_id; ?>" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-container<?php echo $isSelected ? ' hide' : ''; ?>">
                                        <div class="flex">
                                            <div class="basket">
                                                <a href="?add-to-cart=<?php echo $variation_id; ?>" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                            </div>
                          <?php else : ?>
                            <?php
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
                            <div class="pizza__price flex product-<?php echo $product->get_id(); ?>">
                              <div class="price"><?php echo $product->get_price_html(); ?></div>
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
                          <?php endif; ?>
                      </div>

                    <?php endwhile; ?>
                <?php else : ?>
                  <p>Продукты не найдены в данной категории.</p>
                <?php endif;

                wp_reset_postdata();
            }
            ?>
        </div>
      </div>
    </div>
  </div>
</div>
