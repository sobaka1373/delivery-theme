<?php
/* Template Name: Sample Page */

get_header();
?>
<div class="sample-page">
    <div class="container center">
        <h1><?php the_title(); ?></h1>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php the_content(); ?>
        </article>
    </div>
</div>

<?php get_footer(); ?>

