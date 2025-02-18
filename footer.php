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
                     alt="logo">
            </a>
            <p class="footer__info">УНП 491331556
            <p>
                Свидетельство о госрегистрации №491331556, выдано Гомельским горисполкомом</p></p>
            <p class="address">
                Адрес:
            </p>
            <p class="address__link"><a href="https://yandex.by/maps/-/CHQv4JJD" target="_blank">
                    г. Гомель, ул. Рабочая, 22
                </a>
            </p>
        </div>
        <div class="footer-column">
            <div class="title">
                Каталог
            </div>
            <ul class="catalog">
                <li><a href="#">Пицца</a></li>
                <li><a href="#">Кульки</a></li>
                <li><a href="#">Ланчи</a></li>
                <li><a href="#">Закуски</a></li>
                <li><a href="#">Комбо</a></li>
                <li><a href="#">Десерты</a></li>
                <li><a href="#">Напитки</a></li>
                <li><a href="#">Соусы</a></li>
            </ul>
        </div>
        <div class="footer-column footer__order">
            <div class="title">Оформить заказ:</div>
            <p><a href="tel:+375447127117" target="_blank">
                    +375 44 712-71-17
                </a></p>
            <div class="title">Мы в соц. сетях:</div>
            <div class="flex">
                <a href="" target="_blank">
                    <img class="insta" src="<?php echo get_template_directory_uri(); ?>/assets/svg/insta.png"
                         alt="instagram">
                </a>
                <a href="" target="_blank">
                    <img class="telegram" src="<?php echo get_template_directory_uri(); ?>/assets/svg/tg.svg"
                         alt="telegram">
                </a>
                <a href="" target="_blank">
                    <img class="viber" src="<?php echo get_template_directory_uri(); ?>/assets/svg/viber.svg"
                         alt="viber">
                </a>
            </div>
            <div class="flex">
                <a href="" target="_blank">
                    <img class="app" src="<?php echo get_template_directory_uri(); ?>/assets/img/app-store.png"
                         alt="instagram">
                </a>
                <a href="" target="_blank">
                    <img class="google" src="<?php echo get_template_directory_uri(); ?>/assets/img/google-play.png"
                         alt="instagram">
                </a>
            </div>
        </div>
        <div class="footer-column">
            <div class="title">Время работы:</div>
            <p>ПН-ЧТ: 11:00 до 22:45
            </p>
            <p>ПТ-ВС: 11:00 до 22:45</p>
        </div>
    </div>
    <div class="container center test">
        <div class="payment-info flex">
            <p>Мы принимаем к оплате:</p>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/visa.png'); ?>"
                 alt="basket">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/mastercard.png'); ?>"
                 alt="basket">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/mastercard-securecode.png'); ?>"
                 alt="basket">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/belcart_internetpass.png'); ?>"
                 alt="basket">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/mir.png'); ?>"
                 alt="basket">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/belcart.png'); ?>"
                 alt="basket">
        </div>
    </div>
    <div class="public-info">
        <div class="container center">
            <p>
                Внешний вид продукта может отличаться от рекламного изображения.
            </p>
            <p>
                Полное наименование и место нахождение продавца находится в <a href="">публичной оферте</a>
            </p>
        </div>
    </div>
</footer>

</body>
</html>