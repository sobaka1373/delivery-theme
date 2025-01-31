<?php

if (function_exists('acf_add_options_page')) {

    require_once get_template_directory() . '/acf/acf_banner.php';

    $option_page = acf_add_options_page(array(
        'page_title'    => __('Настройки сайта', 'delivery-theme'),
        'menu_title'    => __('Настройки сайта'),
        'menu_slug'     => 'site-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}