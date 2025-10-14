jQuery(document).ready(function($) {
    // Делегируем, чтобы работало на динамически обновлённых списках
    $(document).on('click', '.remove-item button', function(e) {
        e.preventDefault();

        var cartItemKey = $(this).data('cart-item');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'remove_item_from_cart',
                cart_item_key: cartItemKey,
            },
            success: function(response) {
                if(response.success) {
                    // Обновляем фрагменты корзины в хедере
                    if (response.data && response.data.fragments) {
                        $.each(response.data.fragments, function(key, value){ $(key).replaceWith(value); });
                    }

                    // Возвращаем карточки товара на странице в исходное состояние
                    var removedId = response.data.removed_product_id;
                    if (removedId) {
                        $('.pizza__price.product-' + removedId).each(function(){
                            var $price = $(this);
                            $price.find('.count-container').addClass('hide');
                            $price.find('.add-container').removeClass('hide');
                            $price.find('input[name="count"]').val(1);
                            // На старой разметке
                            $price.find('.quantity-wrapper').addClass('hide');
                            $price.find('a.add-to-cart[href*="add-to-cart="]').show();
                        });
                    }
                } else {
                    alert('Ошибка при удалении товара.');
                }
            }
        });
    });
});
