<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Пищеблок №1 Гомель | Доставка пиццы, шаурмы, закусок Гомель
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-P83G4MPP');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P83G4MPP"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(100664224, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/100664224" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

<!--    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">-->
<!--    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>-->
<!--    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>-->
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;coordorder=longlat&amp;apikey=a036afad-cc41-455e-b4fb-8a902f3496b0&suggest_apikey=c1670e47-aa05-4b57-83d6-772f46f9ca2b"
            type="text/javascript"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
    <nav class="container flexbox center">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <img class="image__logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>"
                 alt="logo" loading="lazy">
        </a>
        <ul class="flex menu mobile-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a></li>
            <li><a href="<?php echo esc_url(home_url('/delivery')); ?>">Доставка</a></li>
            <li><a href="<?php echo esc_url(home_url('/about')); ?>">О нас</a></li>
        </ul>
        <div class="header-info-container">
            <div class="align-center flex">
                <img class="image__location"
                     src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/location.svg'); ?>"
                     alt="location" loading="lazy">
                <p>
                    <a href="https://yandex.by/maps/-/CHRHmR4r" target="_blank" rel="noopener noreferrer">
                        Самовывоз: ул. Рабочая, 22
                    </a>
                </p>
            </div>
            <div class="align-center flex">
                <img class="image__phone"
                     src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/phone.svg'); ?>" alt="phone" loading="lazy">
                <p>
                    <a href="tel:+375447127117" target="_blank" rel="noopener noreferrer">
                        +375 44 712-71-17
                    </a>
                </p>
            </div>
        </div>
        <div class="hamburger-menu" id="hamburger-menu" onclick="toggleMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <div class="location-mobile">
            <div class="align-center flex">
                <img class="image__location"
                     src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/location.svg'); ?>"
                     alt="location" loading="lazy">
                <p>
                    <a href="https://yandex.by/maps/-/CHRHmR4r" target="_blank" rel="noopener noreferrer">
                        Самовывоз: ул. Рабочая, 22
                    </a>
                </p>
            </div>
            <div class="align-center flex">
                <img class="image__phone"
                     src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/phone.svg'); ?>" alt="phone" loading="lazy">
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
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/order.svg'); ?>"
                             alt="basket" loading="lazy">
                        <?php if (!empty($cart)): ?>
                            <p class="cart-total-text"><?php echo WC()->cart->get_cart_total(); ?></p>
                        <?php endif; ?>
                    </div>
                </a>

                <div class="basket-dropdown">
                    <?php if (!empty($cart)): ?>
                        <ul>
                            <?php foreach ($cart as $cart_item_key => $cart_item):
                                $hasVariation = $cart_item['variation_id'] && !empty($cart_item['variation']);
                                $product = $hasVariation ?
                                    wc_get_product($cart_item['variation_id']) :
                                    wc_get_product($cart_item['product_id']);
                                $quantity = $cart_item['quantity'];
                                $price = $product->get_price() * $quantity;
                                ?>
                                <li class="basket-item-wrapper"
                                    data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                                    <div class="basket-item-image-wrapper">
                                        <img src="<?php echo esc_url(get_the_post_thumbnail_url($cart_item['product_id'], 'thumbnail')); ?>"
                                             alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy">
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
                        <div class="cart-total">
                            <?php
                            $applied_coupons = WC()->cart->get_applied_coupons();
                            if (!empty($applied_coupons)):
                                foreach ($applied_coupons as $key => $coupon):
                                    $coupon_obj = new WC_Coupon($coupon);
                                    ?>
                                  <div class="cart-discount">
                                    <p>Промокод: <?php echo esc_html($coupon_obj->get_code()); ?></p>
                                    <p>Скидка: -
                                      <span class="cart-discount-text">
                                          <?php echo wc_price(WC()->cart->get_cart_discount_total()); ?>
                                      </span>
                                    </p>
                                  </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <p>Итого:
                                <span class="cart-total-text">
                                    <?php echo WC()->cart->get_cart_total(); ?>
                                </span>
                            </p>
                        </div>
                    <?php else: ?>
                        <p>Корзина пуста</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="nav-mobile-container">
        <div class="align-center flex">
            <a href="tel:+375447127117" target="_blank" rel="noopener noreferrer">
                <div class="flex container-phone">
                    <img class="image__phone"
                         src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/phone.svg'); ?>" alt="phone" loading="lazy">
                    <p>
                        +375 44 712-71-17
                    </p>
                </div>
            </a>
        </div>
    </div>
    <div class="background_grey sticky submenu-mobile">
        <nav class="container__submenu submenu flexbox center">
            <?php
            $menu_items = [
                ['href' => esc_url(home_url('#Pizza')), 'icon' => 'pizza.svg', 'label' => 'Пицца'],
                ['href' => esc_url(home_url('#Kulek')), 'icon' => 'kulek.svg', 'label' => 'Кульки'],
                ['href' => esc_url(home_url('#Lunch')), 'icon' => 'lunch.svg', 'label' => 'Ланчи'],
                ['href' => esc_url(home_url('#Sides')), 'icon' => 'zakus.svg', 'label' => 'Закуски'],
                ['href' => esc_url(home_url('#Toasts')), 'icon' => 'toasts.svg', 'label' => 'Тосты'],
                ['href' => esc_url(home_url('#Dessert')), 'icon' => 'dessert.svg', 'label' => 'Десерты'],
                ['href' => esc_url(home_url('#Drinks')), 'icon' => 'drinks.svg', 'label' => 'Напитки'],
                ['href' => esc_url(home_url('#Sause')), 'icon' => 'sos.svg', 'label' => 'Соусы']
            ];
            foreach ($menu_items as $item): ?>
                <div>
                    <a href="<?php echo esc_url($item['href']); ?>">
                        <img class="image__logo"
                             src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/menu/' . $item['icon']); ?>"
                             alt="<?php echo esc_attr($item['label']); ?>" loading="lazy">
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
