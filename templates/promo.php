<?php
/* Template Name: Promo Page */

get_header();
?>
<div class="promo-page">
    <div class="container center">
        <nav class="category flex" aria-label="Хлебные крошки" itemscope itemtype="https://schema.org/BreadcrumbList">
            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a itemprop="item" href="<?php echo esc_url(home_url('/')); ?>">
                    <span itemprop="name">Главная</span>
                </a>
                <meta itemprop="position" content="1" />
            </span>

            <span class="arrow">&gt;</span>

            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">Акции</span>
                <meta itemprop="position" content="2" />
            </span>
        </nav>
        <h1><?php the_title(); ?></h1>
        <?php $promos = get_posts([
            'post_type' => 'promo_type',
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
        ]);; ?>
        <div class="promo-grid">
            <?php foreach ($promos as $promo): ?>
                <div class="promo-container">
                    <a href="/<?php echo $promo->post_name; ?>">
                        <?php $img = get_field('promo-img', $promo->ID); ?>
                        <div class="promo-img" style="background-image: url('<?php echo $img['url']; ?>')"></div>
                        <div class="title"><?php echo $promo->post_title; ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

