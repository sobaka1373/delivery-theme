jQuery(document).ready(function($){
    $(document).on('click', '.add-to-cart', function(e){
        e.preventDefault();

        var $button = $(this);
        var href = $button.attr('href');
        var match = href.match(/add-to-cart=([0-9]+)/);
        if (!match) {
            console.error('Ошибка: не удалось определить ID товара');
            return;
        }

        var product_id = match[1];
        var quantity = 1;

        // === Показываем quantity-wrapper вместо визуального эффекта ===
        var $item = $button.closest('.pizza__item');
        var $qtyWrapper = $item.find('.quantity-wrapper');
        $qtyWrapper.removeClass('hide').addClass('show');


        // === AJAX добавление товара ===
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
                if (response.success && response.data.fragments) {
                    $.each(response.data.fragments, function(key, value){
                        $(key).replaceWith(value);
                    });
                } else {
                    console.warn('Не удалось добавить товар в корзину:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX ошибка:', error);
            }
        });
    });

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

