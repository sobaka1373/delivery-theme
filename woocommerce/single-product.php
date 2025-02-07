<?php
defined('ABSPATH') || exit;

get_header('shop');

global $product;
if (!$product || !is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}
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
        <div class="button__category"><a href=""> Пиццы</a></div>
        <div class="arrow">&gt;</div>
        <div class="button__name"><a href="">Гусарская</a></div>
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
                        <div class="red-title">
                            Hit
                        </div>
                        <div class="green-title">
                            New
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold mb-4">
                        Гусарская
                        <!--                            --><?php //the_title(); ?>
                    </h1>
                    <p class="text-xl text-gray-700 mb-4">
                        Моцарелла, грибы, ветчина, соус грибной
                        <!--                            --><?php //echo $product->get_short_description(); ?>
                    </p>

                    <div class="price text-2xl font-bold text-red-500 mb-6">
                        <div class="active" id="price-30cm">
                            17.90BYN
                        </div>
                        <div id="price-40cm">20BYN</div>
                        <!--                            --><?php //echo $product->get_price_html(); ?>
                    </div>
                    <div class="pizza__weight">
                        <div id="weight-30cm" class="active">
                            40г
                        </div>
                        <div id="weight-40cm">
                            60г
                        </div>
                    </div>
                    <div class="add-information">
                        <p>Выберите размер:</p>
                        <div class="pizza__size flex">
                            <div id="size-30cm" class="active"> 30 см</div>
                            <div id="size-40cm" class=" "> 40 см</div>
                        </div>
                    </div>
                    <!-- Кнопка добавления в корзину -->
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>
        <div class="same-items">
            <div class="grid">
                <div class="pizza" id="Kulek">
                    <div class="container center">
                        <div class="flex justify-content">
                            <div class="title">
                                Похожие товары
                            </div>
                        </div>
                        <div class="grid">
                            <?php
                            $category_slug = 'kulek';
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
                            ?>

                            <div class="pizza__item">
                                <div class="new flex">
                                    <div class="red-title">
                                        Hit
                                    </div>
                                    <div class="green-title">
                                        New
                                    </div>
                                </div>
                                <div class="pizza__image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kylek.jpg"
                                         alt="logo">
                                </div>
                                <div class="pizza__title">
                                    Гусарская
                                </div>
                                <div class="pizza__weight">
                                    Вес: 40г
                                </div>
                                <!--                <div class="pizza__weight">-->
                                <!--                    Вес: 120г-->
                                <!--                </div>-->
                                <div class="pizza__subtitle">
                                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                                </div>
                                <div class="pizza__size flex">
                                    <div> 30 см</div>
                                    <div> 40 см</div>
                                </div>
                                <div class="pizza__price flex">
                                    <div class="price">
                                        20 BYN
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Дополнительная информация -->
        <!--            <div class="additional-info mt-10">-->
        <!--                <div class="tabs border-t pt-6">-->
        <!--                    <h2 class="text-2xl font-semibold mb-4">Дополнительная информация</h2>-->
        <!--                    --><?php //woocommerce_output_product_data_tabs(); ?>
        <!--                </div>-->
        <!--            </div>-->
    </div>
</div>

<style>

    .dominos-product-page .product-image img {
        max-width: 100%;
    }

    .dominos-product-page .product-image img:hover {
        transform: scale(1.05);
    }

    .dominos-product-page .price {
        color: #e31837;
    }

    button.single_add_to_cart_button {
        background-color: #e31837;
        color: #fff;
        padding: 12px 24px;
        border-radius: 4px;
        font-size: 18px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    button.single_add_to_cart_button:hover {
        background-color: #a10d25;
    }
</style>

<?php
get_footer('shop');
?>
