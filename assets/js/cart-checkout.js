(function( $ ) {
    'use strict';
    $(document).ready(function() {

        let shippingMethod = $("#shipping_method #shipping_method_0_flat_rate2");
        if (shippingMethod.length) {
            shippingMethod.trigger("click");
            setTimeout(function() {
                $(totalUpdate);
            }, 500); // Добавил задержку 500ms
        }

        let paymentMethod = $("#payment ul #payment_method_cod");
        if (paymentMethod.length) {
            paymentMethod.trigger("click");
            setTimeout(function() {
                $(totalUpdate);
            }, 500); // Добавил задержку 500ms
        }

        if (window.location.pathname.includes("checkout")) {
            setInterval(function (){
                $.ajax({
                    type: "POST",
                    url: wc_cart_params.ajax_url,
                    data: {
                        action: "update_cart_total",
                    },
                    beforeSend: function () {
                        $(".basket__promo .total").addClass("loading"); // Показываем лоадер при обновлении
                    },
                    success: function (response) {
                        if (response.success) {
                            $(".basket__promo .total").html("Итого: " + response.data.total);
                        } else {
                            alert("Ошибка при обновлении.");
                        }
                    },
                    complete: function () {
                        $(".basket__promo .total").removeClass("loading"); // Убираем лоадер
                    },
                });
            }, 3000);
        }

        $('.delivery-information #billing_phone').change(function (){
            $('.woocommerce-input-wrapper #billing_phone').val($(this).val());
        });

        $('.delivery-information #billing_first_name').change(function (){
            $('.woocommerce-input-wrapper #billing_first_name').val($(this).val());
        });

        $('.delivery-information #billing_address_2').change(function (){
            $('.woocommerce-input-wrapper #billing_street').val($(this).val());
        });

        $('.delivery-information #billing_address_house').change(function (){
            $('.woocommerce-input-wrapper #billing_house').val($(this).val());
        });

        $('.delivery-information #billing_address_korp').change(function (){
            $('.woocommerce-input-wrapper #billing_korp').val($(this).val());
        });

        $('.delivery-information #billing_address_pod').change(function (){
            $('.woocommerce-input-wrapper #billing_pod').val($(this).val());
        });

        $('.delivery-information #billing_address_flat').change(function (){
            $('.woocommerce-input-wrapper #billing_flat').val($(this).val());
        });

        $('.delivery-information #billing_address_floor').change(function (){
            $('.woocommerce-input-wrapper #billing_floor').val($(this).val());
        });

        $('.delivery-information #billing_address_note').change(function (){
            $('.woocommerce-input-wrapper #billing_note').val($(this).val());
        });

        $('.basket__delivery .complete-order').on('click', function (e) {
            e.preventDefault(); // предотвращаем стандартное поведение кнопки
            $('.custom-error-container').hide().empty(); // прячем и очищаем старые ошибки

            // Подписываемся на событие ошибки от WooCommerce (оно триггерится при AJAX ошибке в checkout)
            $(document.body).on('checkout_error', function () {
                var $noticeGroup = $('.woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout');
                var $customErrorContainer = $('.custom-error-container');

                if ($noticeGroup.length && $customErrorContainer.length) {
                    $customErrorContainer.html($noticeGroup.html());
                    $noticeGroup.remove();
                    $customErrorContainer.show();
                }
            });

            // Триггерим оформление заказа программно
            $('#place_order').trigger('click');
        });

        $('.basket__delivery #deliveryButton1').click(function (){
            $('.woocommerce-input-wrapper #billing_delivery_delivery').click();

            setDelivery();
            $('#shipping_method #shipping_method_0_flat_rate2').click();

            setTimeout(function() {
                $(totalUpdate);
            }, 1000);
        });

        $('.basket__delivery #deliveryButton2').click(function (){
            $('.woocommerce-input-wrapper #billing_delivery_local').click();

            setLocalPickUp();
            $('#shipping_method #shipping_method_0_pickup_location0').click();

            setTimeout(function() {
                $(totalUpdate);
            }, 1000);
        });

        $('.decrease').click(function () {
            changeQuantity(-1);
            // updateCustomFee();
        });

        $('.increase').click(function () {
            changeQuantity(1);
            // updateCustomFee();
        });

        $('.basket__promo .coupon-add').click(function (){
            let couponCode = $('#coupon_code').val();
            $.ajax({
                url: wc_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'apply_coupon',
                    coupon_code: couponCode,
                },
                beforeSend: function() {
                    $('#coupon_message').text('Проверяем купон...').show();
                    toggleLoading(true); // Показываем лоадер при отправке запроса
                },
                success: function(response) {
                    if (response.success) {
                        $('#coupon_message').text(response.data.message).css('color', 'green');
                        $(totalUpdate);
                    } else {
                        $('#coupon_message').text(response.data.message).css('color', 'red');
                    }
                },
                error: function() {
                    $('#coupon_message').text('Ошибка запроса').css('color', 'red');
                },
                complete: function() {
                    toggleLoading(false); // Скрываем лоадер по завершении запроса
                }
            });
        });

        $('.promo-input #coupon-input').change(function (){
            $('.woocommerce-form-coupon #coupon_code').val($(this).val());
        });

        $('.payment-type input').click(function (){
            if ($(this).val() === 'cash') {
                $("#payment ul #payment_method_cod").trigger("click");
            } else if ($(this).val() === 'online') {
                $("#payment ul #payment_method_alfabankby").trigger("click");
            } else {
                $('.complete-order').prop('disabled', true);
                $('.complete-order').addClass('disabled');
            }
        });

        function setLocalPickUp() {
            $('.woocommerce-input-wrapper #billing_street').val('.');
            $('.woocommerce-input-wrapper #billing_house').val('.');
            $('.woocommerce-input-wrapper #billing_korp').val('.');
            $('.woocommerce-input-wrapper #billing_pod').val('.');
            $('.woocommerce-input-wrapper #billing_flat').val('.');
            $('.woocommerce-input-wrapper #billing_floor').val('.');

            $('.delivery-information #billing_address_2').val('.');
            $('.delivery-information #billing_address_house').val('.');
            $('.delivery-information #billing_address_korp').val('.');
            $('.delivery-information #billing_address_pod').val('.');
            $('.delivery-information #billing_address_flat').val('.');
            $('.delivery-information #billing_address_floor').val('.');

            setTimeout(function () {
                $('.complete-order').prop('disabled', false);
                $('.complete-order').removeClass('disabled');
                $('#notice').hide();
                $('#footer').hide();
            }, 1500);
        }

        function setDelivery() {
            $('.woocommerce-input-wrapper #billing_street').val('');
            $('.woocommerce-input-wrapper #billing_house').val('');
            $('.woocommerce-input-wrapper #billing_korp').val('');
            $('.woocommerce-input-wrapper #billing_pod').val('');
            $('.woocommerce-input-wrapper #billing_flat').val('');
            $('.woocommerce-input-wrapper #billing_floor').val('');

            $('.delivery-information #billing_address_2').val('');
            $('.delivery-information #billing_address_house').val('');
            $('.delivery-information #billing_address_korp').val('');
            $('.delivery-information #billing_address_pod').val('');
            $('.delivery-information #billing_address_flat').val('');
            $('.delivery-information #billing_address_floor').val('');
        }

        function totalUpdate() {
            $.ajax({
                type: "POST",
                url: wc_cart_params.ajax_url,
                data: {
                    action: "update_cart_total",
                },
                beforeSend: function () {
                    $(".basket__promo .total").addClass("loading"); // Показываем лоадер при обновлении
                    toggleLoading(true);
                },
                success: function (response) {
                    if (response.success) {
                        $(".basket__promo .total").html("Итого: " + response.data.total);
                    } else {
                        alert("Ошибка при обновлении.");
                    }
                },
                complete: function () {
                    $(".basket__promo .total").removeClass("loading"); // Убираем лоадер
                    toggleLoading(false);
                },
            });

            $('body').trigger('update_checkout');

            setTimeout(function() {
                $('#billing_address_house').trigger('change');
            }, 2000);
        }

        function changeQuantity(amount) {
            const input = document.getElementById("quantityInput");
            const cartItemKey = getCartItemKey(input);
            if (!cartItemKey) return;

            let currentValue = parseInt(input.value);

            if (currentValue + amount >= parseInt(input.min)) {
                currentValue += amount;
                input.value = currentValue;

                updateCartQuantity(cartItemKey, currentValue);
            }
        }

        function updateCartQuantity(cartItemKey, quantity) {
            jQuery.ajax({
                type: "POST",
                url: wc_cart_params.ajax_url,
                data: {
                    action: "update_cart_quantity",
                    quantity: quantity,
                    cart_item_key: cartItemKey
                },
                beforeSend: function() {
                    toggleLoading(true); // Показываем лоадер при обновлении количества
                },
                success: function (response) {
                    if (response.success) {
                        totalUpdate();
                    } else {
                        alert("Ошибка обновления корзины.");
                    }
                },
                complete: function() {
                    toggleLoading(false); // Скрываем лоадер после обновления
                },
            });
        }

        function getCartItemKey(input) {
            const match = input.name.match(/\[([a-f0-9]{32})\]\[qty\]/);
            return match ? match[1] : null;
        }

        function toggleLoading(isLoading) {
            const overlay = document.getElementById('loadingOverlay');
            if (isLoading) {
                overlay.classList.add('visible');
            } else {
                overlay.classList.remove('visible');
            }
        }


        // function updateCustomFee() {
        //     $.ajax({
        //         url: wc_cart_params.ajax_url,
        //         type: 'GET',
        //         data: {
        //             action: "get_pizza_discount_html"
        //         },
        //         success: function (response) {
        //             if (response.success) {
        //                 if ($('.custom-fee').length === 0) {
        //                     $('<div class="custom-fee"></div>').insertAfter('.cart_totals');
        //                 }
        //                 $('.custom-fee').html(response.data);
        //             }
        //         }
        //     });
        // }

        // updateCustomFee();


    });
})(jQuery);
