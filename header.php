<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
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
      <a href="/checkout">
        <img class="image__icon" src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/basket.svg'); ?>" alt="basket">
      </a>
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
