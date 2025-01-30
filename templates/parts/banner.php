<div id="slider">
  <a href="#" class="control_next">></a>
  <a href="#" class="control_prev"><</a>
    <?php
    $banners = get_field('banner_block', 'option');
    ?>
  <ul>
      <?php foreach ($banners as $banner) : ?>
        <li>
            <?php if ($banner['is_two_sec'][0] === 'Yes'): ?>
              <div class="grid__two">
                <div class="left-item">
                    <?php echo $banner['left_sec']; ?>
                </div>
                <div>
                    <?php echo $banner['right']; ?>
                </div>
              </div>
            <?php elseif ($banner['is_two_sec'][0] === 'No'): ?>
              <div class="grid__one">
                <div class="left-item">
                    <?php echo $banner['left_sec']; ?>
                </div>
              </div>
            <?php endif; ?>
        </li>
      <?php endforeach; ?>
  </ul>
</div>