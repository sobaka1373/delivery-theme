jQuery(document).ready(function($) {
    function isBusinessHours() {
        var now = new Date();
        var day = now.getDay(); // 0 - воскресенье, 1 - понедельник, ..., 6 - суббота
        var hour = now.getHours();
        var year = now.getFullYear();
        var month = now.getMonth(); // 0 = Январь
        var date = now.getDate();

        // Проверка на даты акции: 3 - 6 июля
        var isPromoPeriod = (
            year === 2025 &&
            month === 6 && // Июль (0-based index)
            date >= 3 &&
            date <= 6
        );

        if (isPromoPeriod) {
            return hour >= 11 && hour < 23;
        }

        // Обычные часы работы
        if (day >= 1 && day <= 4) { // Пн – Чт
            return hour >= 10 && hour < 22;
        } else { // Пт – Сб – Вс
            return hour >= 11 && hour < 23;
        }
    }

    function toggleOrderButton() {
        var isOpen = isBusinessHours();
        $('.complete-order').prop('disabled', !isOpen);
        $('.complete-order').toggleClass('disabled', !isOpen);

        if (!isOpen) {
            if ($('.closed-message').length === 0) {
                $('.complete-order').after('<p class="closed-message" style="color: red; margin-top: 10px;">Оформление заказа доступно только в рабочее время.</p>');
            }
        } else {
            $('.closed-message').remove();
        }
    }

    toggleOrderButton();
});
