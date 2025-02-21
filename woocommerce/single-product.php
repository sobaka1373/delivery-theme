<?php
defined('ABSPATH') || exit;

get_header('shop');

global $product;
if (!$product || !is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (!$product) {
    echo '<p>Товар не найден.</p>';
    get_footer('shop');
    exit;
}



// Проверяем, является ли товар вариативным
if ($product->is_type('variable')) {
    get_template_part('templates/parts/product', 'variable'); // Загружаем шаблон для вариативного товара
} else {
    get_template_part('templates/parts/product', 'simple'); // Загружаем шаблон для обычного товара
}

get_footer('shop');