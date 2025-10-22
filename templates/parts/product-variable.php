<?php
global $product;
$terms = get_the_terms($product->get_id(), 'product_cat');
$variations = $product->get_available_variations();

// Собираем выбранные вариации и количества из корзины
$selected_variations = [];
if (function_exists('WC') && WC()->cart) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if (!$cart_item['variation_id']) continue;
        $parentId = (int) $cart_item['data']->get_parent_id();
        if ((int)$product->get_id() === $parentId) {
            $selected_variations[(int)$cart_item['variation_id']] = (int)$cart_item['quantity'];
        }
    }
}
$first_active_variation_id = 0;
if (!empty($selected_variations)) {
    $keys = array_keys($selected_variations);
    $first_active_variation_id = (int)$keys[0];
}
?>

<div class="dominos-product-page product-page variable-product container center">
    <div class="category flex" aria-label="Хлебные крошки" itemscope itemtype="https://schema.org/BreadcrumbList">
        <div class="button__back">
            <a href="javascript:history.back()">
                &lt; Назад
            </a>
        </div>
        <div class="button__home">
            <a href="<?php echo home_url();?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/home.svg" loading="lazy">
            </a>
        </div>
        <div class="arrow">
            &gt;
        </div>
        <div class="button__category">
            <a href="<?php echo home_url() . "#" . ucfirst($terms[0]->slug); ?>">
                <?php
                if (isset($terms) && !empty($terms)) {
                    echo $terms[0]->name;
                }
                ?>
            </a>
        </div>
        <div class="arrow">&gt;</div>
        <div class="button__name"><a href="" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name"><?php the_title(); ?></span><meta itemprop="position" content="3" /></a></div>
    </div>
    <div class="item-content container mx-auto py-10">
        <div class="item-information flex">
            <!-- Изображение товара -->
            <div class="center w-full lg:w-1/2 px-4">
                <div class="product-image">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('large', array('loading' => 'lazy', 'class' => 'rounded-lg shadow-md'));
                    }
                    ?>
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="product-details">
<!--                    <div class="new flex">-->
<!--                        --><?php //showTags($product); ?>
<!--                    </div>-->
                    <h1 class="mobile-title text-4xl font-bold mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <p class="desc-mobile text-xl text-gray-700 mb-4">
                        <?php echo $product->get_short_description(); ?>
                    </p>
                    <div class="price text-2xl font-bold text-red-500 mb-6">
                        <?php
                        if (!empty($variations)) {
                            foreach ($variations as $key => $variation_data) {
                                $variation = new WC_Product_Variation($variation_data['variation_id']);
                                $varId = (int)$variation->get_id();
                                $isActive = $first_active_variation_id ? ($varId === $first_active_variation_id) : ($key === 0);
                                ?>
                                <div class="pizza__price product-<?php echo $variation->get_id(); ?><?php echo $isActive ? ' active' : ''; ?>">
                                    <?php echo $variation->get_price_html(); ?>
                                </div>
                                <?php
                            }
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
                    </div>
                    <div class="variation-container">
                        <?php
                        if (!empty($variations)) {
                            foreach ($variations as $index => $variation_data) {
                                $variation = new WC_Product_Variation($variation_data['variation_id']);
                                $varId = (int)$variation->get_id();
                                $isSelected = array_key_exists($varId, $selected_variations);
                                $qty = $isSelected ? (int)$selected_variations[$varId] : 1;
                                $isActive = $first_active_variation_id ? ($varId === $first_active_variation_id) : ($index === 0);
                                ?>
                                <div class="pizza__price flex product-<?php echo $varId; ?><?php echo $isActive ? ' active' : ''; ?>">
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
                                                <a href="?add-to-cart=<?php echo $varId; ?>" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-container<?php echo $isSelected ? ' hide' : ''; ?>">
                                        <div class="flex">
                                            <div class="basket">
                                                <a href="?add-to-cart=<?php echo $varId; ?>" class="add-to-cart">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg" alt="add-to-cart">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <?php if ($product->get_description()) : ?>
                        <div class="product-seo-description">
                            <?php echo wp_kses_post(wpautop($product->get_description())); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


        </div><?php
        require_once locate_template('templates/parts/same-products.php');
        ?>
    </div>