<div class="pizza" id="Pizza">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Пицца
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'pizza';
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

                    <div class="pizza__item">
                        <div class="new flex">
                            <?php
                            $tags = $product->get_tag_ids();
                            if (!empty($tags)) {
                                foreach ($tags as $tag_id) {
                                    $tag = get_term($tag_id);
                                    if ($tag->name === 'Хит') {
                                        ?>
                                        <div class="red-title">
                                            Hit
                                        </div>
                                        <?php
                                    }
                                    if ($tag->name === 'New') {
                                        ?>
                                        <div class="green-title">
                                            New
                                        </div>
                                        <?php
                                    }

                                }
                            }
                            ?>

                        </div>
                        <a href="<?php the_permalink(); ?>">
                            <div class="pizza__image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
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
                        <div class="toggle-container">
                            <?php
                            if ($product->is_type('variable')) {
                                $variations = $product->get_available_variations();
                                if (!empty($variations)) {
                                    foreach ($variations as $index => $variation) {
                                        $size = $variation['attributes']['attribute_pa_size'];
                                        $price = wc_price($variation['display_price']);
                                        $variation_id = $variation['variation_id'];
                                        $variation = new WC_Product_Variation($variation_id);

                                        if ($size) {
                                            $size_output = str_replace('cm', '', $size);
                                            $checked = $index === 0 ? 'checked' : '';
                                            echo "<label class='size-toggle product-$variation_id'>";
                                            echo '<input type="radio" name="pizza_size" value="' . esc_attr($size) . '" ' . $checked . '>';
                                            echo '<span>' . esc_html($size_output) . ' см</span>';
                                            echo '</label>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>

                        <?php
                        if ($product->is_type('variable')) {
                            if (!empty($variations)) {
                              ?>
                              <div class="variation-container">
                              <?php
                                foreach ($variations as $key=>$variation_data) {
                                    $variation_id = $variation_data['variation_id'];
                                    $variation = new WC_Product_Variation($variation_id);
                                    $price_html = $variation->get_price_html();
                                    ?>

                                    <div class="pizza__price flex product-<?php echo $variation->get_id(); ?><?php echo $key === 0 ? ' active' : ''; ?>">
                                        <div class="price">
                                            <?php echo $price_html; ?>
                                        </div>
                                        <div class="flex">
                                            <div class="basket">
                                                <a href="?add-to-cart=<?php echo $variation->get_id(); ?>" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>
                              </div>
                              <?php
                            }
                        } else {
                          ?>

                          <div class="pizza__price flex">
                            <div class="price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            <div class="flex">
                              <div class="basket">
                                <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                                  <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                                </a>
                              </div>
                            </div>
                          </div>

                          <?php
                        }
                        ?>

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