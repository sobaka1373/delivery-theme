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
                <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
            <?php endif; ?>
        </div>
    </a>
    <div class="pizza__title"><?php the_title(); ?></div>
    <div class="pizza__weight">
        <?php
        $weight = $product->get_weight();
        echo $weight ? "<p>Вес: {$weight} " . get_option('woocommerce_weight_unit') . "</p>" : "<p>Вес не указан</p>";
        ?>
    </div>
    <div class="pizza__subtitle"><?php echo $product->get_short_description(); ?></div>

    <div class="pizza__price flex product-<?php echo $product->get_id(); ?>">
        <div class="price"><?php echo $product->get_price_html(); ?></div>

        <div class="count-container hide">
            <div class="flex">
                <div class="basket">
                    <a href="#" class="add-to-cart">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/minus.svg" alt="add-to-cart">
                    </a>
                </div>
            </div>
            <input type="text" name="count" value="1" disabled>
            <div class="flex">
                <div class="basket">
                    <a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="add-to-cart">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                    </a>
                </div>
            </div>
        </div>

        <div class="add-container">
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
