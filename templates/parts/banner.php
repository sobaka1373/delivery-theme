<div class="slider-wrapper-container">
    <div class="slider-wrapper">
        <!-- Контейнер слайдера -->
        <div id="slider" class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                $banners = get_field('banner_block', 'option');
                foreach ($banners as $banner) : ?>
                    <div class="swiper-slide">
                        <?php if ($banner['is_two_sec'][0] === 'Yes'): ?>
                            <div class="grid__two">
                                <div class="left-item">
                                    <?php echo $banner['left_sec']; ?>
                                </div>
                                <div>
                                    <?php echo $banner['right']; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="grid__one">
                                <div class="left-item">
                                    <?php echo $banner['left_sec']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Пагинация для слайдера -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
<script>

</script>