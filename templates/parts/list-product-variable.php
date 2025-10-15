<?php
$product = $args['product'] ?? null;
if (!$product) return;

// Вычисляем, какая вариация уже в корзине и её количество
$selected_variations = [];
if (function_exists('WC') && WC()->cart) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (!$cart_item['variation_id']) {
            continue;
        }
        $parentId = (int) $cart_item['data']->get_parent_id();
        if ((int)$product->get_id() === $parentId) {
            $selected_variations[(int)$cart_item['variation_id']] = (int)$cart_item['quantity'];
        }
    }
}
// Определяем какая вариация будет активной визуально: первая выбранная, иначе первая по списку
$first_active_variation_id = 0;
if (!empty($selected_variations)) {
    $keys = array_keys($selected_variations);
    $first_active_variation_id = (int)$keys[0];
}
?>

<div class="pizza__item">
<!--    <div class="new flex">-->
<!--        --><?php //showTags($product); ?>
<!--    </div>-->
    <a href="<?php the_permalink(); ?>">
        <div class="pizza__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium',  array('loading' => 'lazy')); ?>
            <?php endif; ?>
        </div>
    </a>
    <div class="pizza__title"><?php the_title(); ?></div>
    <div class="pizza__weight variation-container">
        <?php
        $variations = $product->get_available_variations();
        if (!empty($variations)) {
            foreach ($variations as $index => $variation) :
                $varId = (int)$variation['variation_id'];
                $isSelected = array_key_exists($varId, $selected_variations);
                $isActive = $first_active_variation_id ? ($varId === $first_active_variation_id) : ($index === 0);
                ?>
              <div class="pizza__weight_val product-<?php echo $variation['variation_id']; ?><?php echo $isActive ? ' active' : ''; ?>">
                <?php
                $weight = $variation['weight'];
                echo $weight ? "<p>Вес: {$weight} " . get_option('woocommerce_weight_unit') . "</p>" : "<p>Вес не указан</p>"; ?>
              </div>
           <?php endforeach;
        }
        ?>
    </div>
    <div class="pizza__subtitle"><?php echo $product->get_short_description(); ?></div>

    <div class="toggle-container center">
        <?php
        $variations = $product->get_available_variations();
        if (!empty($variations)) {
            foreach ($variations as $index => $variation) {
                $size = $variation['attributes']['attribute_pa_size'];
                $size_output = str_replace('cm', '', $size);
                $varId = (int)$variation['variation_id'];
                $isActive = $first_active_variation_id ? ($varId === $first_active_variation_id) : ($index === 0);
                $checked = $isActive ? 'checked' : '';
                $activeClass = $isActive ? ' active' : '';
                echo "<label class='size-toggle pizza__size{$activeClass}'>";
                echo "<input type='radio' name='pizza_size' value='{$size}' {$checked}>";
                echo "<span>{$size_output} см</span>";
                echo "</label>";
            }
        }
        ?>
    </div>

    <div class="variation-container">
        <?php
        foreach ($variations as $key => $variation_data) {
            $variation = new WC_Product_Variation($variation_data['variation_id']);
            $varId = (int)$variation->get_id();
            $isSelected = array_key_exists($varId, $selected_variations);
            $isActive = $first_active_variation_id ? ($varId === $first_active_variation_id) : ($key === 0);
            ?>
            <div class="pizza__price flex product-<?php echo $variation->get_id(); ?><?php echo $isActive ? ' active' : ''; ?>">
                <div class="price"><?php echo $variation->get_price_html(); ?></div>

                <?php
                $initQty = $isSelected ? (int)$selected_variations[$varId] : 1;
                ?>
                <div class="count-container<?php echo $isSelected ? '' : ' hide'; ?>">
                    <div class="flex">
                        <div class="basket">
                            <a href="#" class="add-to-cart">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/minus.svg" alt="add-to-cart">
                            </a>
                        </div>
                    </div>
                    <input type="text" name="count" value="<?php echo (int)$initQty; ?>" disabled>
                    <div class="flex">
                        <div class="basket">
                            <a href="?add-to-cart=<?php echo $variation->get_id(); ?>" class="add-to-cart">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="add-container<?php echo $isSelected ? ' hide' : ''; ?>">
                    <div class="flex">
                        <div class="basket">
                            <a href="?add-to-cart=<?php echo $variation->get_id(); ?>" class="add-to-cart">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
