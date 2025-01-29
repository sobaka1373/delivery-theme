<?php
/**
 * Template Name: Custom Checkout Page
 */

get_header(); ?>

<div class="checkout-page container center mx-auto px-4 py-8">
    <h1 class="title text-3xl font-bold mb-6">Оформление заказа</h1>

    <?php
    if (class_exists('WooCommerce')) {
        // Проверка, если корзина пуста
        if (WC()->cart->is_empty()) {
            echo '<p class="text-red-500">Ваша корзина пуста. Пожалуйста, добавьте товары в корзину перед оформлением заказа.</p>';
            echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Перейти в магазин</a>';
        } else {
            // Отображение стандартной формы оформления заказа WooCommerce
            echo do_shortcode('[woocommerce_checkout]');
        }
    } else {
        echo '<p class="text-red-500">WooCommerce не установлен или отключен.</p>';
    }
    ?>
</div>

<style>
    /* Кастомная стилизация */
    .checkout-page {
        font-family: Arial, sans-serif;
    }

    .checkout-page h1 {
        text-align: center;
    }

    .woocommerce form .form-row input.input-text,
    .woocommerce form .form-row textarea {
        border: 1px solid #ddd;
        padding: 10px;
        width: 100%;
        margin-bottom: 15px;
        border-radius: 5px;
    }

    .woocommerce form .form-row input.input-text:focus,
    .woocommerce form .form-row textarea:focus {
        border-color: #0071e3;
        outline: none;
    }

    .woocommerce button.button {
        background-color: #0071e3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-transform: uppercase;
    }

    .woocommerce button.button:hover {
        background-color: #005bb5;
    }
</style>

<?php get_footer(); ?>
