jQuery(document).ready(function($){
    // Добавление товара
    $(document).on('click', '.add-to-cart', function(e){
        e.preventDefault();
        var $btn = $(this);
        var href = $btn.attr('href');
        var match = href.match(/add-to-cart=([0-9]+)/);
        if (!match) return;
        var product_id = match[1];
        var quantity = 1;

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'custom_add_to_cart',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response){
                if (response.success) {
                    if (response.data.fragments) {
                        $.each(response.data.fragments, function(key, value){
                            $(key).replaceWith(value);
                        });
                    }
                    $btn.hide();
                    var $qtyWrap = $btn.closest('.basket').find('.quantity-wrapper');
                    $qtyWrap.removeClass('hide');
                    $qtyWrap.find('input').val(quantity);
                }
            }
        });
    });

    // Увеличение количества
    $(document).on('click', '.quantity-wrapper .increase', function(){
        var $wrap = $(this).closest('.quantity-wrapper');
        var product_id = $wrap.data('product_id');
        var $input = $wrap.find('input');
        var quantity = parseInt($input.val()) + 1;
        updateCartQuantity(product_id, quantity, $input, $wrap);
    });

    // Уменьшение количества
    $(document).on('click', '.quantity-wrapper .decrease', function(){
        var $wrap = $(this).closest('.quantity-wrapper');
        var product_id = $wrap.data('product_id');
        var $input = $wrap.find('input');
        var quantity = Math.max(0, parseInt($input.val()) - 1);
        updateCartQuantity(product_id, quantity, $input, $wrap);
    });

    function updateCartQuantity(product_id, quantity, $input, $wrap) {
        if (quantity === 0) {
            // Если 0, скрываем qty, показываем кнопку добавить
            $wrap.addClass('hide');
            $wrap.closest('.basket').find('.add-to-cart').show();
        }
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'update_cart_quantity_by_product_id',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response){
                if (response.success) {
                    $input.val(quantity);
                    if (quantity === 0) {
                        $wrap.addClass('hide');
                        $wrap.closest('.basket').find('.add-to-cart').show();
                    } else {
                        $wrap.removeClass('hide');
                    }
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(key, value){
                            $(key).replaceWith(value);
                        });
                    }
                }
            }
        });
    }

    // Sticky header logic
    const $header = $('#main-nav');
    let lastScrollTop = 0;
    const threshold = 50;

    if ($header.length) {
        const originalHeight = $header.outerHeight();
        $('<div class="sticky-wrapper"></div>').insertBefore($header).height(originalHeight).append($header);

        $(window).on('scroll', function () {
            const st = $(this).scrollTop();

            if (Math.abs(st - lastScrollTop) <= threshold) return;

            if (st > lastScrollTop && st > originalHeight) {
                $header.addClass('sticky-header--hidden');
            } else {
                $header.removeClass('sticky-header--hidden');
            }

            lastScrollTop = st;
        });
    }
});

