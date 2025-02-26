<?php
global $product;
$terms = get_the_terms($product->get_id(), 'product_cat');
$variations = $product->get_available_variations();
?>

<div class="dominos-product-page product-page container center">
    <div class="category flex">
        <div class="button__back">
            <a href="">
                &lt; Назад
            </a>
        </div>
        <div class="button__home">
            <a href="">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/home.svg">
            </a>
        </div>
        <div class="arrow">
            &gt;
        </div>
        <div class="button__category">
            <a href="">
                <?php
                if (isset($terms) && !empty($terms)) {
                    echo $terms[0]->name;
                }
                ?>
            </a>
        </div>
        <div class="arrow">&gt;</div>
        <div class="button__name"><a href=""><?php the_title(); ?></a></div>
    </div>
    <div class="item-content container mx-auto py-10">
        <div class="item-information flex flex-wrap">
            <!-- Изображение товара -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="product-image">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('large', ['class' => 'rounded-lg shadow-md']);
                    }
                    ?>
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="product-details">
                    <div class="new flex">
                        <?php showTags($product); ?>
                    </div>
                    <h1 class="text-4xl font-bold mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <p class="text-xl text-gray-700 mb-4">
                        <?php echo $product->get_short_description(); ?>
                    </p>

                    <div class="price text-2xl font-bold text-red-500 mb-6">
                        <?php
                        if (!empty($variations)) {
                            foreach ($variations as $key => $variation_data) {
                                $variation = new WC_Product_Variation($variation_data['variation_id']);
                                ?>
                              <div class="pizza__price product-<?php echo $variation->get_id(); ?><?php echo $key === 0 ? ' active' : ''; ?>">
                                  <?php echo $variation->get_price_html(); ?>
                              </div>
                                <?php
                                ?>
                            <?php }
                        }
                        ?>

                    </div>
                    <div class="pizza__weight">
                      <?php
                      if (!empty($variations)) {
                          foreach ($variations as $index => $variation) : ?>
                            <div class="pizza__weight_val product-<?php echo $variation['variation_id']; ?><?php echo $index === 0 ? ' active' : ''; ?>">
                                <?php
                                $weight = $variation['weight'];
                                echo $weight ? "<p>Вес: {$weight} " . get_option('woocommerce_weight_unit') . "</p>" : "<p>Вес не указан</p>"; ?>
                            </div>
                          <?php endforeach;
                      }
                      ?>
                    </div>
                    <div class="add-information">
                        <p>Выберите размер:</p>
                      <div class="toggle-container center">
                          <?php
                          $variations = $product->get_available_variations();
                          if (!empty($variations)) {
                              foreach ($variations as $index => $variation) {
                                  $size = $variation['attributes']['attribute_pa_size'];
                                  $size_output = str_replace('cm', '', $size);
                                  $checked = $index === 0 ? 'checked' : '';
                                  echo "<label class='size-toggle pizza__size'>";
                                  echo "<input type='radio' name='pizza_size' value='{$size}' {$checked}>";
                                  echo "<span>{$size_output} см</span>";
                                  echo "</label>";
                              }
                          }
                          ?>
                      </div>
                    </div>
                  <div class="variation-container">
                      <?php
                      wc_get_template('single-product/add-to-cart/variable.php', [
                          'available_variations' => $product->get_available_variations(),
                          'attributes' => $product->get_variation_attributes(),
                          'selected_attributes' => $product->get_default_attributes(),
                      ]);
                      ?>
                  </div>
            </div>
        </div>

          <?php
          require_once locate_template('templates/parts/same-products.php');
          ?>
    </div>
</div>