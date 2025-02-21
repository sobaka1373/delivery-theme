<?php
global $product;
$terms = get_the_terms($product->get_id(), 'product_cat');
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
                        <div class="active">
                            <?php echo $product->get_price_html(); ?>
                        </div>

                    </div>
                    <div class="pizza__weight">
                        <div class="active">
                            <?php echo $product->get_weight(); ?>
                        </div>
                    </div>

                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>

        <div class="same-items">
            <div class="grid">
                <div class="pizza">
                    <div class="container center">
                        <div class="flex justify-content">
                            <div class="title">
                                Похожие товары
                            </div>
                        </div>
                        <div class="grid">
                            <?php

                            if (isset($terms[0]->slug)) {
                                $category_slug = $terms[0]->slug;
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
                                                <?php showTags($product); ?>
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
                                            <div class="pizza__size flex">
                                                <div> 30 см</div>
                                                <div> 40 см</div>
                                            </div>
                                            <div class="pizza__price flex">
                                                <div class="price">
                                                    <?php echo $product->get_price_html(); ?>
                                                </div>
                                                <div class="flex">
                                                    <div class="like">
                                                        <a href="">
                                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                                                        </a>
                                                    </div>
                                                    <div class="basket">
                                                        <a href="">
                                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

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
    </div>
</div>