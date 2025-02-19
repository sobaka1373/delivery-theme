<div class="pizza" id="Pizza">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Пицца
            </div>
        </div>
        <div class="grid">
            <?php
            $category_slug = 'pizza';
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

                    if ($product->is_type('variable')) {
                        get_template_part('templates/parts/list-product-variable', null, ['product' => $product]);
                    } else {
                        get_template_part('templates/parts/list-product-simple', null, ['product' => $product]);
                    }
                endwhile;
            else : ?>
                <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>