<?php
/* Template Name: Thank You Page */

get_header();

// Проверка, существует ли текущий заказ
if ( isset( $_GET['order'] ) ) {
    $order_id = sanitize_text_field( $_GET['order'] );
    $order = wc_get_order( $order_id );

    if ( $order ) {
        ?>
        <div class="thank-you-page container center">
            <div class="flex">
                <div class="thank-you-left">
                    <h1>Спасибо за заказ!</h1>
                    <p>Ваш заказ <span class="order-number">№
                        <?php echo $order->get_order_number(); ?> </span>успешно оформлен. </p>

                </div>
                <div class="thank-you-right">

                    <h2>Детали заказа:</h2>
                    <ul>
                        <li><span class="label">Дата заказа:</span> <span class="dots"></span> <span class="value"><?php echo $order->get_date_created()->date('F j, Y'); ?></span></li>
                        <li><span class="label">Статус:</span> <span class="dots"></span> <span class="value"><?php echo wc_get_order_status_name( $order->get_status() ); ?></span></li>
                        <li><span class="label">К оплате:</span> <span class="dots"></span> <span class="value"><?php echo wc_price( $order->get_total() ); ?></span></li>
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
                </div>
            </div>
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
