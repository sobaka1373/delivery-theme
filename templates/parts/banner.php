<?php

$today = date('N');
$banners = get_field('banner_block', 'option');
if ($banners) :
?>
<div class="slider-wrapper-container">
    <div class="slider-wrapper">
        <div id="slider" class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                    foreach ($banners as $banner) :
                        $day_number = $banner['day_number'];
                        if ($day_number === $today || $day_number === '8') :
                            $image = $banner['image'];
                            if ($image) : ?>
                                <div class="swiper-slide">
                                    <?php
                                    $image_id = $image['ID'];
                                    $size = 'large';
                                    echo wp_get_attachment_image( $image_id, $size, false, array(
                                        'loading' => 'lazy',
                                        'alt' => esc_attr($image['alt']),
                                        'class' => 'slider-image',
                                    ) );
                                    ?>
                                </div>
                            <?php endif;
                        endif;
                    endforeach;
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
<script>
    var swiper = new Swiper('#slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 10000,
            disableOnInteraction: false,
        },
        speed: 5000,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        grabCursor: true,
    });
</script>
<div class="mobile-single-image">
<?php
foreach ($banners as $banner) :
    $day_number = $banner['day_number'];
    if ($day_number === $today) :
        $image = $banner['image'];
        if ($image) : ?>
            <div class="image">
                <?php
                $image_id = $image['ID'];
                $size = 'medium';
                echo wp_get_attachment_image( $image_id, $size, false, array(
                    'loading' => 'lazy',
                    'alt' => esc_attr($image['alt']),
                    'class' => 'slider-image',
                ) );
                ?>
            </div>
        <?php endif;
    endif;
endforeach;
?>
</div>
<?php endif; ?>