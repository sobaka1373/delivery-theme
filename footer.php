<?php
/**
 * Шаблон подвала (footer.php)
 * @package WordPress
 * @subpackage pischeblok
 */
?>
<?php wp_footer(); ?>

<footer class="footer">
    <div class="container footer-container">
        <div class="footer-column">
            <a href="/">
                <img class="image__logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png"
                     alt="logo" loading="lazy">
            </a>
            <p class="footer__info">УНП 491390946</p>
            <p>ООО "Фуд Найс"</p>

            <p>
                Свидетельство о госрегистрации №491390946, выдано Гомельским горисполкомом 15.11.2024
            </p>
            <p class="address">
                Адрес:
            </p>
            <p class="address__link"><a href="https://yandex.by/maps/-/CHRHmR4r" target="_blank">
                   Беларусь, г. Гомель, ул. Рабочая, 22, помещение 2
                </a>
            </p>
        </div>
        <div class="footer-column">
            <div class="title">
                Каталог
            </div>
            <ul class="catalog">
                <li><a href="#Pizza">Пицца</a></li>
                <li><a href="#Kulek">Кульки</a></li>
                <li><a href="#Lunch">Ланчи</a></li>
                <li><a href="#Sides">Закуски</a></li>
                <li><a href="#Combo">Комбо</a></li>
                <li><a href="#Dessert">Десерты</a></li>
                <li><a href="#Drinks">Напитки</a></li>
                <li><a href="#Sause">Соусы</a></li>
            </ul>
        </div>
        <div class="footer-column footer__order">
            <div class="title">Оформить заказ:</div>
            <p><a href="tel:+375447127117" target="_blank">
                    +375 44 712-71-17
                </a></p>
            <div class="title">Мы в соц. сетях:</div>
            <div class="flex">
                <a href="https://www.instagram.com/pishcheblok.gomel/" target="_blank">
                    <img class="insta" src="<?php echo get_template_directory_uri(); ?>/assets/svg/insta.png"
                         alt="instagram">
                </a>
                <a href="https://t.me/pishcheblokgomel" target="_blank">
                    <img class="telegram" src="<?php echo get_template_directory_uri(); ?>/assets/svg/tg.svg"
                         alt="telegram">
                </a>
                <a href="" target="_blank">
                    <img class="viber" src="<?php echo get_template_directory_uri(); ?>/assets/svg/viber.svg"
                         alt="viber">
                </a>
                <a href="https://www.tiktok.com/@pishcheblok.gomel" target="_blank">
                    <img class="tiktok" src="<?php echo get_template_directory_uri(); ?>/assets/svg/tiktok.svg"
                         alt="tiktok">
                </a>
            </div>
<!--            <div class="flex">-->
<!--                <a href="" target="_blank">-->
<!--                    <img class="app" src="--><?php //echo get_template_directory_uri(); ?><!--/assets/img/app-store.png"-->
<!--                         alt="instagram">-->
<!--                </a>-->
<!--                <a href="" target="_blank">-->
<!--                    <img class="google" src="--><?php //echo get_template_directory_uri(); ?><!--/assets/img/google-play.png"-->
<!--                         alt="instagram">-->
<!--                </a>-->
<!--            </div>-->
        </div>
        <div class="footer-column">
            <div class="title">Время работы:</div>
            <p>ПН-ЧТ: 10:00 до 22:00
            </p>
            <p>ПТ-ВС: 11:00 до 23:00</p>
        </div>
    </div>
    <div class="container center">
        <div class="payment-info flex">
            <p>Мы принимаем к оплате:</p>
            <div class="flex">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-12.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-11.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-13.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-14.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-15.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-16.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-18.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-19.png'); ?>"
                     alt="basket" loading="lazy">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logos/black/Black-20.png'); ?>"
                     alt="basket" loading="lazy">
            </div>
        </div>
<!--        <p class="erip-pay">-->
<!--            - Оплату через Ерип (Ерип -> Сервис E-pos -> Оплата товаров и услуг -> 31052-1-1 -> Введите сумму платежа и Ваши данные-->
<!--        </p>-->
    </div>
    <div class="public-info">
        <div class="container center">
            <p>
                Внешний вид продукта может отличаться от рекламного изображения.
            </p>
<!--            <p>-->
<!--                Полное наименование и место нахождение продавца находится в <a href="">публичной оферте</a>-->
<!--            </p>-->
        </div>
    </div>
</footer>

</body>
</html>