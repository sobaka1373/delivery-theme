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
                    'posts_per_page' => -1,
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

                        // Проверяем, является ли товар переменным
                        $has_size_variations = false;
                        $sizes = [];

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
                        ?>

                      <div class="pizza__item">
                        <div class="new flex">
                            <?php showTags($product); ?>
                        </div>
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
                                <?php foreach ($sizes as $size => $variation_id) : ?>
                                  <label class="size-toggle pizza__size">
                                    <input type="radio" name="pizza_size_<?php echo get_the_ID(); ?>" value="<?php echo $size; ?>" data-variation-id="<?php echo $variation_id; ?>" <?php echo ($size === array_key_first($sizes)) ? 'checked' : ''; ?>>
                                    <span><?php echo str_replace('cm', '', $size); ?> см</span>
                                  </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="variation-container">
                                <?php foreach ($sizes as $size => $variation_id) :
                                    $variation = new WC_Product_Variation($variation_id); ?>
                                  <div class="pizza__price flex product-<?php echo $variation_id; ?><?php echo ($size === array_key_first($sizes)) ? ' active' : ''; ?>">
                                    <div class="price"><?php echo $variation->get_price_html(); ?></div>
                                    <div class="flex">
                                      <div class="basket">
                                        <a href="?add-to-cart=<?php echo $variation_id; ?>" class="add-to-cart">
                                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                            </div>
                          <?php else : ?>
                            <div class="pizza__price flex">
                              <div class="price"><?php echo $product->get_price_html(); ?></div>
                              <div class="flex">
                                <div class="basket">
                                  <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                  </a>
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
