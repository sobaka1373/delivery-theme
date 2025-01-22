<?php
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $product;
if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
    $product = wc_get_product( get_the_ID() );
}
?>

<div class="dominos-product-page">
    <div class="container mx-auto py-10">
        <div class="flex flex-wrap">
            <!-- Изображение товара -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="product-image">
                    <?php
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'large', ['class' => 'rounded-lg shadow-md'] );
                    }
                    ?>
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="product-details">
                    <h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                    <p class="text-xl text-gray-700 mb-4"><?php echo $product->get_short_description(); ?></p>

                    <div class="price text-2xl font-bold text-red-500 mb-6">
                        <?php echo $product->get_price_html(); ?>
                    </div>

                    <!-- Кнопка добавления в корзину -->
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="additional-info mt-10">
            <div class="tabs border-t pt-6">
                <h2 class="text-2xl font-semibold mb-4">Дополнительная информация</h2>
                <?php woocommerce_output_product_data_tabs(); ?>
            </div>
        </div>
    </div>
</div>

<style>
    .dominos-product-page {
        font-family: 'Arial', sans-serif;
        background-color: #f9f9f9;
    }
    .dominos-product-page .product-details {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .dominos-product-page .product-image img {
        max-width: 100%;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out;
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
get_footer( 'shop' );
?>
