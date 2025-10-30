<?php
/*
Template Name: Single promo page
Template Post Type: post, promo_type
*/

get_header();
?>

<div class="single-promo-page">
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
                <a itemprop="item" href="/promo/">
                    <span itemprop="name">Акции</span>
                </a>
                <meta itemprop="position" content="2" />
            </span>

            <span class="arrow">&gt;</span>
            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name"><?php the_title(); ?></span>
                <meta itemprop="position" content="3" />
            </span>
        </nav>
        <?php $img = get_field('promo-img', $post->ID); ?>
        <div class="promo-img-main">
            <?php
            $image_id = $img['ID'];
            $size = 'large';
            echo wp_get_attachment_image( $image_id, $size, false, array(
                'loading' => 'lazy',
                'alt' => esc_attr($img['alt']),
            ) );
            ?>
        </div>
        <h1><?php the_title(); ?></h1>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php the_content(); ?>
        </article>
    </div>
</div>


<?php get_footer(); ?>