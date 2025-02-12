<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat&amp;apikey=a036afad-cc41-455e-b4fb-8a902f3496b0&suggest_apikey=c1670e47-aa05-4b57-83d6-772f46f9ca2b" type="text/javascript"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
    <nav class="container flexbox center">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <img class="image__logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="logo">
        </a>
        <ul class="flex">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a></li>
            <li><a href="<?php echo esc_url(home_url('/delivery')); ?>">Доставка</a></li>
            <li><a href="<?php echo esc_url(home_url('/about')); ?>">О нас</a></li>
            <li><a href="<?php echo esc_url(home_url('/promo')); ?>">Акции</a></li>
        </ul>
        <div>
            <div class="align-center flex">
                <img class="image__location" src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/location.png'); ?>" alt="location">
                <p>
                    <a href="https://yandex.by/maps/-/CHQv4JJD" target="_blank" rel="noopener noreferrer">
                        Самовывоз: ул. Рабочая, 22
                    </a>
                </p>
            </div>
            <div class="align-center flex">
                <img class="image__phone" src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/phone.png'); ?>" alt="phone">
                <p>
                    <a href="tel:+375447127117" target="_blank" rel="noopener noreferrer">
                        +375 44 712-71-17
                    </a>
                </p>
            </div>
        </div>
        <div class="icon-block flex">
            <div class="basket-container">
                <?php $cart = WC()->cart->get_cart(); ?>

                <a href="/checkout" class="basket-icon">
                    <div class="basket-image-icon">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/basket.svg'); ?>" alt="basket">
                        <?php if (!empty($cart)): ?>
                            <p class="cart-total-text"><?php echo WC()->cart->get_cart_total(); ?></p>
                        <?php endif; ?>
                    </div>
                </a>

                <div class="basket-dropdown">
                    <?php if (!empty($cart)): ?>
                        <ul>
                            <?php foreach ($cart as $cart_item_key => $cart_item):
                                $product = wc_get_product($cart_item['product_id']);
                                $quantity = $cart_item['quantity'];
                                $price = $product->get_price() * $quantity;
                                ?>
                                <li class="basket-item-wrapper" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                                    <div class="basket-item-image-wrapper">
                                        <img src="<?php echo esc_url(get_the_post_thumbnail_url($cart_item['product_id'], 'thumbnail')); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                    </div>
                                    <div class="basket-item-info">
                                        <div class="basket-item-name">
                                            <p><?php echo esc_html($product->get_name()); ?></p>
                                        </div>
                                        <div class="basket-item-description">
                                            <p><?php echo esc_html($quantity); ?> шт</p>
                                            <p><?php echo wc_price($price); ?></p>
                                        </div>
                                    </div>
                                    <div class="remove-item">
                                        <button data-cart-item="<?php echo esc_attr($cart_item_key); ?>">✖</button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="cart-total-text"><strong>Итого:</strong> <?php echo WC()->cart->get_cart_total(); ?></p>
                    <?php else: ?>
                        <p>Корзина пуста</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="background_grey sticky">
        <nav class="container__submenu submenu flexbox center">
            <?php
            $menu_items = [
                ['href' => esc_url(home_url('#Pizza')), 'icon' => 'pizza.svg', 'label' => 'Пицца'],
                ['href' => esc_url(home_url('#Kulek')), 'icon' => 'kulek.svg', 'label' => 'Кульки'],
                ['href' => esc_url(home_url('#Lunch')), 'icon' => 'lunch.svg', 'label' => 'Ланчи'],
                ['href' => esc_url(home_url('#Sides')), 'icon' => 'zakus.svg', 'label' => 'Закуски'],
                ['href' => esc_url(home_url('#Combo')), 'icon' => 'fries.png', 'label' => 'Комбо'],
                ['href' => esc_url(home_url('#Dessert')), 'icon' => 'dessert.svg', 'label' => 'Десерты'],
                ['href' => esc_url(home_url('#Drinks')), 'icon' => 'drinks.svg', 'label' => 'Напитки'],
                ['href' => esc_url(home_url('#Sause')), 'icon' => 'sos.svg', 'label' => 'Соусы']
            ];
            foreach ($menu_items as $item): ?>
                <div>
                    <a href="<?php echo esc_url($item['href']); ?>">
                        <img class="image__logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/menu/' . $item['icon']); ?>" alt="<?php echo esc_attr($item['label']); ?>">
                        <p><?php echo esc_html($item['label']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
<?php wp_footer(); ?>
</body>
</html>
