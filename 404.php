<?php
status_header(404);
nocache_headers();
?>
<?php get_header(); ?>

<main class="container center" style="min-height: 50vh; padding: 40px 0;">
    <h1>Страница не найдена</h1>
    <p>Похоже, здесь ничего нет. Возможно, страница была удалена или вы перешли по неправильной ссылке.</p>
    <p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="button">< На главную</a>
    </p>
</main>

<?php get_footer(); ?>


