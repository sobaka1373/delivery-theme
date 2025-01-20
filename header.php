<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">

    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>
<header>
    <nav class="container flexbox center">
        <a href="/">
            <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png"
                 alt="logo">
        </a>
        <ul class="flex">
            <li>
                <a href="/">
                    Главная
                </a>
            </li>
            <li>
                <a href="/">
                    Доставка
                </a>
            </li>
            <li><a href="/">
                    О нас
                </a>
            </li>
            <li><a href="/">
                    Акции
                </a>
            </li>
        </ul>
        <div>
            <div class="align-center flex">
                <img class="image__location" src="<?php echo get_template_directory_uri(); ?>/assets/img/location.png"
                     alt="location">
                <p><a href="https://yandex.by/maps/-/CHQv4JJD" target="_blank">
                        Самовывоз: ул. Рабочая, 22
                    </a>
                </p>
            </div>
            <div class="align-center flex">
                <img class="image__phone" src="<?php echo get_template_directory_uri(); ?>/assets/img/phone.png"
                     alt="phone">
                <p><a href="tel:+375447127117" target="_blank">
                        +375 44 712-71-17
                    </a>
                </p>
            </div>
        </div>
        <div class="icon-block flex">
            <a href="">
                <img class="image__icon" src="<?php echo get_template_directory_uri(); ?>/assets/svg/like.svg"
                     alt="">
            </a>
            <a href="">
                <img class="image__icon" src="<?php echo get_template_directory_uri(); ?>/assets/svg/account.svg"
                     alt="">
            </a>
            <a href="">
                <img class="image__icon" src="<?php echo get_template_directory_uri(); ?>/assets/svg/basket.svg"
                     alt="">
            </a>
        </div>
    </nav>
    <div class="background_grey sticky">
        <nav class="container__submenu submenu flexbox center">
            <div>
                <a href="#Pizza">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/pizza.svg"
                         alt="logo">
                    <p>
                        Пицца
                    </p>
                </a>
            </div>
            <div>
                <a href="#Kulek">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/kulek.svg"
                         alt="logo">
                    <p>
                        Кульки
                    </p>
                </a>
            </div>
            <div>
                <a href="#Lunch">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/lunch.svg"
                         alt="logo">
                    <p>
                        Ланчи
                    </p>
                </a>
            </div>
            <div>
                <a href="#Sides">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/zakus.svg"
                         alt="logo">
                    <p>
                        Закуски
                    </p>
                </a>
            </div>
            <div>
                <a href="#Combo">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/fries.png"
                         alt="logo">
                    <p>
                        Комбо
                    </p>
                </a>
            </div>
            <div>
                <a href="#Dessert">
                    <img class="image__logo"
                         src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/dessert.svg"
                         alt="logo">
                    <p>
                        Десерты
                    </p>
                </a>
            </div>
            <div>
                <a href="#Drinks">
                    <img class="image__logo"
                         src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/drinks.svg"
                         alt="logo">
                    <p>
                        Напитки
                    </p>
                </a>
            </div>
            <div>
                <a href="#Sause">
                    <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/svg/menu/sos.svg"
                         alt="logo">
                    <p>
                        Соусы
                    </p>
                </a>
            </div>
        </nav>
    </div>
</header>

