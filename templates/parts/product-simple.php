<?php
global $product;
$terms = get_the_terms($product->get_id(), 'product_cat');
?>

<div class="dominos-product-page product-page container center">
    <div class="category flex">
        <div class="button__back">
            <a href="javascript:history.back()">
                &lt; Назад
            </a>
        </div>
        <div class="button__home">
            <a href="<?php echo home_url();?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/home.svg">
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
        <div class="button__name"><a href=""><?php the_title(); ?></a></div>
    </div>
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
                    <div class="new flex">
                        <?php showTags($product); ?>
                    </div>
                    <h1 class="mobile-title text-4xl font-bold mb-4">
                        <?php the_title(); ?>
                    </h1>
                    <p class="desc-mobile text-xl text-gray-700 mb-4">
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

        <?php
        require_once locate_template('templates/parts/same-products.php');
        ?>
    </div>
</div>