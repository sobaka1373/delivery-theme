<?php
/* Template Name: Thank You Page */

get_header();

// Проверка, существует ли текущий заказ
if ( isset( $_GET['order'] ) ) {
    $order_id = sanitize_text_field( $_GET['order'] );
    $order = wc_get_order( $order_id );

    if ( $order ) {
        ?>
        <div class="thank-you-page">
            <h1>Спасибо за заказ!</h1>
            <p>Ваш заказ № <?php echo $order->get_order_number(); ?> был успешно оформлен. Вот информация о вашем заказе:</p>

            <h2>Детали заказа:</h2>
            <ul>
                <li><strong>Дата заказа:</strong> <?php echo $order->get_date_created()->date('F j, Y'); ?></li>
                <li><strong>Статус:</strong> <?php echo wc_get_order_status_name( $order->get_status() ); ?></li>
                <li><strong>Общая сумма:</strong> <?php echo wc_price( $order->get_total() ); ?></li>
            </ul>

            <h2>Список товаров:</h2>
            <ul>
                <?php
                // Перебираем все товары в заказе
                foreach ( $order->get_items() as $item_id => $item ) {
                    $product = $item->get_product();
                    echo '<li>';
                    echo $product->get_name() . ' - ' . $item->get_quantity() . ' шт. - ' . wc_price( $item->get_total() );
                    echo '</li>';
                }
                ?>
            </ul>

            <h2>Адрес доставки:</h2>
            <p>
                <?php
                // Получаем адрес доставки
                echo $order->get_formatted_shipping_address();
                ?>
            </p>

            <h2>Способ оплаты:</h2>
            <p>
                <?php echo $order->get_payment_method_title(); ?>
            </p>
        </div>
        <?php
    } else {
        echo '<p>Заказ не найден.</p>';
    }
} else {
    echo '<p>Информация о заказе недоступна.</p>';
}

get_footer();
?>
