<?php
$product = $args['product'] ?? null;
if (!$product) return;
?>

<div class="pizza__item">
    <div class="new flex">
        <?php showTags($product); ?>
    </div>
    <a href="<?php the_permalink(); ?>">
        <div class="pizza__image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php endif; ?>
        </div>
    </a>
    <div class="pizza__title"><?php the_title(); ?></div>
    <div class="pizza__weight variation-container">
        <?php
        $variations = $product->get_available_variations();
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
    <div class="pizza__subtitle"><?php echo $product->get_short_description(); ?></div>

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

    <div class="variation-container">
        <?php
        foreach ($variations as $key => $variation_data) {
            $variation = new WC_Product_Variation($variation_data['variation_id']);
            ?>
            <div class="pizza__price flex product-<?php echo $variation->get_id(); ?><?php echo $key === 0 ? ' active' : ''; ?>">
                <div class="price"><?php echo $variation->get_price_html(); ?></div>
                <div class="flex">
                    <div class="basket">
                        <a href="?add-to-cart=<?php echo $variation->get_id(); ?>" class="add-to-cart">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
