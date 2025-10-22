<?php
global $product;
$terms = get_the_terms($product->get_id(), 'product_cat');

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

<div class="dominos-product-page product-page simple-product container center">
    <nav class="category flex" aria-label="Хлебные крошки" itemscope itemtype="https://schema.org/BreadcrumbList">
        <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
                <span itemprop="name">Главная</span>
            </a>
            <meta itemprop="position" content="1" />
        </span>
        <span class="arrow">&gt;</span>
        <?php if (!empty($terms)) : $cat = $terms[0]; ?>
            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a itemprop="item" href="<?php echo esc_url(get_term_link($cat)); ?>">
                    <span itemprop="name"><?php echo esc_html($cat->name); ?></span>
                </a>
                <meta itemprop="position" content="2" />
            </span>
            <span class="arrow">&gt;</span>
        <?php endif; ?>
        <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name"><?php the_title(); ?></span>
            <meta itemprop="position" content="3" />
        </span>
    </nav>
    <div class="item-content container mx-auto py-10">
        <div class="item-information flex flex-wrap">
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
                    </div>
                    <div class="pizza__weight">
                        <div class="active">
                            <p>Вес: <?php echo $product->get_weight() . " " . get_option('woocommerce_weight_unit') . " "; ?> </p>
                        </div>
                    </div>

                    <?php // Основную кнопку WC скрываем — используем AJAX-блоки выше ?>

                    <?php if ($product->get_description()) : ?>
                        <div class="product-seo-description">
                            <?php echo wp_kses_post(wpautop($product->get_description())); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        require_once locate_template('templates/parts/same-products.php');
        ?>
    </div>
</div>