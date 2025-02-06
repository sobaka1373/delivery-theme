<?php /* Template Name: main */ ?>

<?php get_header(); ?>

<?php

require_once locate_template('templates/parts/banner.php');

require_once locate_template('templates/parts/pizza-block.php');
?>


<div class="pizza" id="Kulek">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Кульки
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
          <?php
          $category_slug = 'kulek';
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => -1,
              'tax_query' => array(
                  array(
                      'taxonomy' => 'product_cat',
                      'field' => 'slug',
                      'terms' => $category_slug,
                  ),
              ),
          );
          $query = new WP_Query($args);

          if ($query->have_posts()) : ?>
              <?php while ($query->have_posts()) :
                  $query->the_post();
                  $product = wc_get_product(get_the_ID());
                  ?>

              <div class="pizza__item">
                <div class="new flex">
                    <?php
                    $tags = $product->get_tag_ids();
                    if (!empty($tags)) {
                        foreach ($tags as $tag_id) {
                            $tag = get_term($tag_id);
                            if ($tag->name === 'Хит') {
                                ?>
                              <div class="red-title">
                                Hit
                              </div>
                                <?php
                            }
                            if ($tag->name === 'New') {
                                ?>
                              <div class="green-title">
                                New
                              </div>
                                <?php
                            }

                        }
                    }
                    ?>

                </div>
                <a href="<?php the_permalink(); ?>">
                  <div class="pizza__image">
                      <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('medium'); ?>
                      <?php endif; ?>
                  </div>
                </a>
                <div class="pizza__title">
                    <?php the_title(); ?>
                </div>
                <div class="pizza__weight">
                    <?php
                    $weight = $product->get_weight(); // Получаем вес
                    if ($weight) {
                        echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                    } else {
                        echo '<p>Вес не указан</p>';
                    }
                    ?>
                </div>
                <div class="pizza__subtitle">
                    <?php echo $product->get_short_description(); ?>
                </div>
                <div class="pizza__size flex">
                  <div> 30 см</div>
                  <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                  <div class="price">
                      <?php echo $product->get_price_html(); ?>
                  </div>
                  <div class="flex">
                    <div class="like">
                      <a href="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                      </a>
                    </div>
                    <div class="basket">
                      <a href="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                      </a>
                    </div>
                  </div>
                </div>

              </div>
              <?php endwhile; ?>
          <?php else : ?>
            <p>Продукты не найдены в данной категории.</p>
          <?php endif;

          wp_reset_postdata();
          ?>

            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kylek.jpg"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Lunch">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Ланчи
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
          <?php
          $category_slug = 'lunches';
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => -1,
              'tax_query' => array(
                  array(
                      'taxonomy' => 'product_cat',
                      'field' => 'slug',
                      'terms' => $category_slug,
                  ),
              ),
          );
          $query = new WP_Query($args);

          if ($query->have_posts()) : ?>
              <?php while ($query->have_posts()) :
                  $query->the_post();
                  $product = wc_get_product(get_the_ID());
                  ?>

              <div class="pizza__item">
                <div class="new flex">
                    <?php
                    $tags = $product->get_tag_ids();
                    if (!empty($tags)) {
                        foreach ($tags as $tag_id) {
                            $tag = get_term($tag_id);
                            if ($tag->name === 'Хит') {
                                ?>
                              <div class="red-title">
                                Hit
                              </div>
                                <?php
                            }
                            if ($tag->name === 'New') {
                                ?>
                              <div class="green-title">
                                New
                              </div>
                                <?php
                            }

                        }
                    }
                    ?>

                </div>
                <a href="<?php the_permalink(); ?>">
                  <div class="pizza__image">
                      <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('medium'); ?>
                      <?php endif; ?>
                  </div>
                </a>
                <div class="pizza__title">
                    <?php the_title(); ?>
                </div>
                <div class="pizza__weight">
                    <?php
                    $weight = $product->get_weight(); // Получаем вес
                    if ($weight) {
                        echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                    } else {
                        echo '<p>Вес не указан</p>';
                    }
                    ?>
                </div>
                <div class="pizza__subtitle">
                    <?php echo $product->get_short_description(); ?>
                </div>
                <div class="pizza__size flex">
                  <div> 30 см</div>
                  <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                  <div class="price">
                      <?php echo $product->get_price_html(); ?>
                  </div>
                  <div class="flex">
                    <div class="like">
                      <a href="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                      </a>
                    </div>
                    <div class="basket">
                      <a href="">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                      </a>
                    </div>
                  </div>
                </div>

              </div>
              <?php endwhile; ?>
          <?php else : ?>
            <p>Продукты не найдены в данной категории.</p>
          <?php endif;

          wp_reset_postdata();

          ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/borshch.jpg"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Sides">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Закуски
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'snacks';
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>

                <div class="pizza__item">
                  <div class="new flex">
                      <?php
                      $tags = $product->get_tag_ids();
                      if (!empty($tags)) {
                          foreach ($tags as $tag_id) {
                              $tag = get_term($tag_id);
                              if ($tag->name === 'Хит') {
                                  ?>
                                <div class="red-title">
                                  Hit
                                </div>
                                  <?php
                              }
                              if ($tag->name === 'New') {
                                  ?>
                                <div class="green-title">
                                  New
                                </div>
                                  <?php
                              }

                          }
                      }
                      ?>

                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div class="pizza__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php endif; ?>
                    </div>
                  </a>
                  <div class="pizza__title">
                      <?php the_title(); ?>
                  </div>
                  <div class="pizza__weight">
                      <?php
                      $weight = $product->get_weight(); // Получаем вес
                      if ($weight) {
                          echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                      } else {
                          echo '<p>Вес не указан</p>';
                      }
                      ?>
                  </div>
                  <div class="pizza__subtitle">
                      <?php echo $product->get_short_description(); ?>
                  </div>
                  <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                  </div>
                  <div class="pizza__price flex">
                    <div class="price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="flex">
                      <div class="like">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                        </a>
                      </div>
                      <div class="basket">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                      </div>
                    </div>
                  </div>

                </div>
                <?php endwhile; ?>
            <?php else : ?>
              <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();

            ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/fries.jpg"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Combo">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Комбо
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'combo';
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>

                <div class="pizza__item">
                  <div class="new flex">
                      <?php
                      $tags = $product->get_tag_ids();
                      if (!empty($tags)) {
                          foreach ($tags as $tag_id) {
                              $tag = get_term($tag_id);
                              if ($tag->name === 'Хит') {
                                  ?>
                                <div class="red-title">
                                  Hit
                                </div>
                                  <?php
                              }
                              if ($tag->name === 'New') {
                                  ?>
                                <div class="green-title">
                                  New
                                </div>
                                  <?php
                              }

                          }
                      }
                      ?>

                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div class="pizza__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php endif; ?>
                    </div>
                  </a>
                  <div class="pizza__title">
                      <?php the_title(); ?>
                  </div>
                  <div class="pizza__weight">
                      <?php
                      $weight = $product->get_weight(); // Получаем вес
                      if ($weight) {
                          echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                      } else {
                          echo '<p>Вес не указан</p>';
                      }
                      ?>
                  </div>
                  <div class="pizza__subtitle">
                      <?php echo $product->get_short_description(); ?>
                  </div>
                  <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                  </div>
                  <div class="pizza__price flex">
                    <div class="price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="flex">
                      <div class="like">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                        </a>
                      </div>
                      <div class="basket">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                      </div>
                    </div>
                  </div>

                </div>
                <?php endwhile; ?>
            <?php else : ?>
              <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();

            ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/combo.jpg"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Dessert">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Десерты
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'desserts';
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>

                <div class="pizza__item">
                  <div class="new flex">
                      <?php
                      $tags = $product->get_tag_ids();
                      if (!empty($tags)) {
                          foreach ($tags as $tag_id) {
                              $tag = get_term($tag_id);
                              if ($tag->name === 'Хит') {
                                  ?>
                                <div class="red-title">
                                  Hit
                                </div>
                                  <?php
                              }
                              if ($tag->name === 'New') {
                                  ?>
                                <div class="green-title">
                                  New
                                </div>
                                  <?php
                              }

                          }
                      }
                      ?>

                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div class="pizza__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php endif; ?>
                    </div>
                  </a>
                  <div class="pizza__title">
                      <?php the_title(); ?>
                  </div>
                  <div class="pizza__weight">
                      <?php
                      $weight = $product->get_weight(); // Получаем вес
                      if ($weight) {
                          echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                      } else {
                          echo '<p>Вес не указан</p>';
                      }
                      ?>
                  </div>
                  <div class="pizza__subtitle">
                      <?php echo $product->get_short_description(); ?>
                  </div>
                  <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                  </div>
                  <div class="pizza__price flex">
                    <div class="price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="flex">
                      <div class="like">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                        </a>
                      </div>
                      <div class="basket">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                      </div>
                    </div>
                  </div>

                </div>
                <?php endwhile; ?>
            <?php else : ?>
              <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();

            ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dessert.jpg"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Drinks">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Напитки
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'drinks';
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>

                <div class="pizza__item">
                  <div class="new flex">
                      <?php
                      $tags = $product->get_tag_ids();
                      if (!empty($tags)) {
                          foreach ($tags as $tag_id) {
                              $tag = get_term($tag_id);
                              if ($tag->name === 'Хит') {
                                  ?>
                                <div class="red-title">
                                  Hit
                                </div>
                                  <?php
                              }
                              if ($tag->name === 'New') {
                                  ?>
                                <div class="green-title">
                                  New
                                </div>
                                  <?php
                              }

                          }
                      }
                      ?>

                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div class="pizza__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php endif; ?>
                    </div>
                  </a>
                  <div class="pizza__title">
                      <?php the_title(); ?>
                  </div>
                  <div class="pizza__weight">
                      <?php
                      $weight = $product->get_weight(); // Получаем вес
                      if ($weight) {
                          echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                      } else {
                          echo '<p>Вес не указан</p>';
                      }
                      ?>
                  </div>
                  <div class="pizza__subtitle">
                      <?php echo $product->get_short_description(); ?>
                  </div>
                  <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                  </div>
                  <div class="pizza__price flex">
                    <div class="price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="flex">
                      <div class="like">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                        </a>
                      </div>
                      <div class="basket">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                      </div>
                    </div>
                  </div>

                </div>
                <?php endwhile; ?>
            <?php else : ?>
              <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();

            ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cola.png"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pizza" id="Sause">
    <div class="container center">
        <div class="flex justify-content">
            <div class="title">
                Соусы
            </div>
<!--            <button class="" type="button">Смотреть все</button>-->
        </div>
        <div class="grid">
            <?php
            $category_slug = 'sauces';
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category_slug,
                    ),
                ),
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) :
                    $query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>

                <div class="pizza__item">
                  <div class="new flex">
                      <?php
                      $tags = $product->get_tag_ids();
                      if (!empty($tags)) {
                          foreach ($tags as $tag_id) {
                              $tag = get_term($tag_id);
                              if ($tag->name === 'Хит') {
                                  ?>
                                <div class="red-title">
                                  Hit
                                </div>
                                  <?php
                              }
                              if ($tag->name === 'New') {
                                  ?>
                                <div class="green-title">
                                  New
                                </div>
                                  <?php
                              }

                          }
                      }
                      ?>

                  </div>
                  <a href="<?php the_permalink(); ?>">
                    <div class="pizza__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php endif; ?>
                    </div>
                  </a>
                  <div class="pizza__title">
                      <?php the_title(); ?>
                  </div>
                  <div class="pizza__weight">
                      <?php
                      $weight = $product->get_weight(); // Получаем вес
                      if ($weight) {
                          echo '<p>Вес: ' . $weight . ' ' . get_option('woocommerce_weight_unit') . '</p>';
                      } else {
                          echo '<p>Вес не указан</p>';
                      }
                      ?>
                  </div>
                  <div class="pizza__subtitle">
                      <?php echo $product->get_short_description(); ?>
                  </div>
                  <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                  </div>
                  <div class="pizza__price flex">
                    <div class="price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="flex">
                      <div class="like">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                        </a>
                      </div>
                      <div class="basket">
                        <a href="">
                          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                        </a>
                      </div>
                    </div>
                  </div>

                </div>
                <?php endwhile; ?>
            <?php else : ?>
              <p>Продукты не найдены в данной категории.</p>
            <?php endif;

            wp_reset_postdata();

            ?>
            <div class="pizza__item">
                <div class="new flex">
                    <div class="red-title">
                        Hit
                    </div>
                    <div class="green-title">
                        New
                    </div>
                </div>
                <div class="pizza__image">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sause.png"
                         alt="logo">
                </div>
                <div class="pizza__title">
                    Гусарская
                </div>
                <div class="pizza__weight">
                    Вес: 40г
                </div>
                <!--                <div class="pizza__weight">-->
                <!--                    Вес: 120г-->
                <!--                </div>-->
                <div class="pizza__subtitle">
                    Курица, ананас, сладкий перец, моцарелла, соус терияки
                </div>
                <div class="pizza__size flex">
                    <div> 30 см</div>
                    <div> 40 см</div>
                </div>
                <div class="pizza__price flex">
                    <div class="price">
                        20 BYN
                    </div>
                    <div class="flex">
                        <div class="like">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/heart.svg">
                            </a>
                        </div>
                        <div class="basket">
                            <a href="">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/plus.svg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>