jQuery(document).ready(function($) {
    $('.remove-item button').on('click', function(e) {
        e.preventDefault();

        var cartItemKey = $(this).data('cart-item');

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'remove_item_from_cart',
                cart_item_key: cartItemKey,
            },
            success: function(response) {
                if(response.success) {
                    $('[data-cart-item="' + cartItemKey + '"]').fadeOut(function() {
                        $(this).remove();
                    });

                    $('.cart-total-text').html(response.data.new_total);

                    if (response.data.cart_empty) {
                        $('.basket-dropdown').html('<p style="text-align: center">Корзина пуста</p>');
                    }
                } else {
                    alert('Ошибка при удалении товара.');
                }
            }
        });
    });
});
