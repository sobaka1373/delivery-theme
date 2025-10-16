<?php /* Template Name: main */ ?>

<?php get_header(); ?>

<?php
if( get_field('activate', 'options') === 'yes' ) {
    require_once locate_template('templates/parts/banner.php');
}
?>

<?php

require_once locate_template('templates/parts/pizza-block.php');

require_once locate_template('templates/parts/combo.php');

require_once locate_template('templates/parts/kulek.php');

//require_once locate_template('templates/parts/lunch.php');

require_once locate_template('templates/parts/sides.php');

//require_once locate_template('templates/parts/combo.php');

require_once locate_template('templates/parts/toasts.php');

//require_once locate_template('templates/parts/dessert.php');

require_once locate_template('templates/parts/drinks.php');

require_once locate_template('templates/parts/sause.php');
?>
    <div class="background_grey">
        <div class="container center">
            <?php
            $frontpage_id = get_option('page_on_front');
            $seo_text = get_post_field('post_content', $frontpage_id);
            if ( $seo_text ) : ?>
                <div class="seo-text">
                <?php echo apply_filters('the_content', $seo_text); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php get_footer(); ?>