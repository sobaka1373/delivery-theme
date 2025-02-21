<?php

function showTags($product)
{
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
}