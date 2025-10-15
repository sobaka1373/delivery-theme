jQuery(document).ready(function($){
    // Универсальный перехват на все ссылки добавления в корзину
    $(document).on('click', 'a.add-to-cart[href*="add-to-cart="]', function(e){
        e.preventDefault();
        var $btn = $(this);
        var href = $btn.attr('href');
        var match = href && href.match(/add-to-cart=([0-9]+)/);
        if (!match) return;
        var product_id = parseInt(match[1], 10);

        // Если внутри count-container — это инкремент
        var $count = $btn.closest('.count-container');
        if ($count.length) {
            var $input = $count.find('input[name="count"]');
            var nextQty = parseInt($input.val(), 10) + 1;
            return updateCartQuantity(product_id, nextQty, $input, $count, $btn.closest('.pizza__price'));
        }

        // Иначе — стандартное добавление (и поддержка quantity-wrapper для старых карточек)
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'custom_add_to_cart',
                product_id: product_id,
                quantity: 1
            },
            success: function(response){
                if (response.success) {
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(key, value){ $(key).replaceWith(value); });
                    }
                    if (response.data && response.data.basket_information) {
                        $('.basket__information .basket__list').html(response.data.basket_information);
                    }
                    var $price = $btn.closest('.pizza__price');
                    if (!$price.length) {
                        // fallback: ищем внутри карточки товара
                        var $card = $btn.closest('.pizza__item, .product-details, .dominos-product-page');
                        $price = $card.find('.pizza__price').first();
                    }
                    if ($price.length) {
                        // Вариант новой разметки
                        $price.find('.add-container').addClass('hide');
                        var $countNew = $price.find('.count-container');
                        if ($countNew.length) {
                            $countNew.removeClass('hide');
                            $countNew.find('input[name="count"]').val(1);
                        }
                        // Вариант старой разметки (quantity-wrapper)
                        var $qtyWrap = $price.find('.quantity-wrapper');
                        if ($qtyWrap.length) {
                            $btn.hide();
                            $qtyWrap.removeClass('hide');
                            $qtyWrap.find('input').val(1);
                        }
                    }
                }
            }
        });
    });

    // Минус в новой разметке (count-container)
    $(document).on('click', '.pizza__price .count-container .add-to-cart:not([href*="add-to-cart="])', function(e){
        e.preventDefault();
        var $btn = $(this);
        var $price = $btn.closest('.pizza__price');
        // Найти активный variation id по классу product-<id>
        var productId = null;
        var match = ($price.attr('class') || '').match(/product-(\d+)/);
        if (match) productId = parseInt(match[1], 10);

        // fallback: извлечь из соседних плюс-кнопок
        if (!productId) {
            var plusHref = $price.find('.count-container .add-to-cart[href*="add-to-cart="]').attr('href');
            var plusMatch = plusHref && plusHref.match(/add-to-cart=(\d+)/);
            if (plusMatch) productId = parseInt(plusMatch[1], 10);
        }
        if (!productId) return;

        var $count = $price.find('.count-container');
        var $input = $count.find('input[name="count"]');
        var quantity = Math.max(0, parseInt($input.val(), 10) - 1);
        updateCartQuantity(productId, quantity, $input, $count, $price);
    });

    // Поддержка старой разметки: quantity-wrapper +/-
    $(document).on('click', '.quantity-wrapper .increase', function(){
        var $wrap = $(this).closest('.quantity-wrapper');
        var product_id = parseInt($wrap.data('product_id'), 10);
        var $input = $wrap.find('input');
        var quantity = parseInt($input.val(), 10) + 1;
        updateCartQuantity(product_id, quantity, $input, $wrap, $wrap.closest('.pizza__price'));
    });

    $(document).on('click', '.quantity-wrapper .decrease', function(){
        var $wrap = $(this).closest('.quantity-wrapper');
        var product_id = parseInt($wrap.data('product_id'), 10);
        var $input = $wrap.find('input');
        var quantity = Math.max(0, parseInt($input.val(), 10) - 1);
        updateCartQuantity(product_id, quantity, $input, $wrap, $wrap.closest('.pizza__price'));
    });

    function updateCartQuantity(product_id, quantity, $input, $count, $priceWrap) {
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_cart_quantity',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response){
                if (response && response.success) {
                    if (response.data && response.data.basket_information) {
                        $('.basket__information .basket__list').html(response.data.basket_information);
                    }
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(key, value){ $(key).replaceWith(value); });
                    }
                    if ($count && $count.length) {
                        if (quantity <= 0) {
                            $count.addClass('hide');
                            if ($priceWrap) {
                                $priceWrap.find('.add-container').removeClass('hide');
                                $priceWrap.find('a.add-to-cart[href*="add-to-cart="]').show();
                            }
                        } else {
                            $input.val(quantity);
                            $count.removeClass('hide');
                        }
                    }
                }
            }
        });
    }
});
