<?php
defined('ABSPATH') || exit;

get_header();
$category_name = single_term_title('', false);

if ($category_name === 'Десерты') {
    require_once locate_template('templates/parts/dessert.php');
} elseif ($category_name === 'Закуски') {
    require_once locate_template('templates/parts/lunch.php');
} elseif ($category_name === 'Комбо') {
    require_once locate_template('templates/parts/combo.php');
} elseif ($category_name === 'Кульки') {
    require_once locate_template('templates/parts/kulek.php');
} elseif ($category_name === 'Ланчи') {
    require_once locate_template('templates/parts/lunch.php');
} elseif ($category_name === 'Напитки') {
    require_once locate_template('templates/parts/drinks.php');
} elseif ($category_name === 'Пицца') {
    require_once locate_template('templates/parts/pizza-block.php');
} elseif ($category_name === 'Соусы') {
    require_once locate_template('templates/parts/sause.php');
} elseif ($category_name === 'Тосты') {
    require_once locate_template('templates/parts/toasts.php');
} else {
    require_once locate_template('templates/parts/pizza-block.php');
    require_once locate_template('templates/parts/kulek.php');
    require_once locate_template('templates/parts/lunch.php');
    require_once locate_template('templates/parts/sides.php');
    require_once locate_template('templates/parts/toasts.php');
    require_once locate_template('templates/parts/dessert.php');
    require_once locate_template('templates/parts/drinks.php');
    require_once locate_template('templates/parts/sause.php');
}

?>



<?php get_footer(); ?>
