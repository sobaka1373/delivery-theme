jQuery(document).ready(function($) {
    function isBusinessHours() {
        var now = new Date();
        var day = now.getDay(); // 0 - воскресенье, 1 - понедельник, ..., 6 - суббота
        var hour = now.getHours();

        if (day >= 1 && day <= 4) { // Понедельник - Четверг
            return hour >= 10 && hour < 22;
        } else { // Пятница, Суббота, Воскресенье
            return hour >= 10 && hour < 23;
        }
    }

    function toggleOrderButton() {
        var isOpen = isBusinessHours();
        $('.complete-order').prop('disabled', !isOpen);
        $('.complete-order').toggleClass('disabled', !isOpen);

        if (!isOpen) {
            $('.complete-order').after('<p class="closed-message" style="color: red; margin-top: 10px;">Оформление заказа доступно только в рабочее время.</p>');
        } else {
            $('.closed-message').remove();
        }
    }

    toggleOrderButton();
});