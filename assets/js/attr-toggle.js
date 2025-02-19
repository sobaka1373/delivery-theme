jQuery(document).ready(function($) {
    function updateVariation(selectedSize) {
        let productId = selectedSize === "30cm" ? "134" : "135";

        // Убираем active у всех переключателей размеров
        $(".size-toggle").removeClass("active");
        $(".pizza__weight_val, .pizza__price").removeClass("active");

        // Добавляем active выбранным элементам
        $(".size-toggle input[value='" + selectedSize + "']").parent().addClass("active");
        $(".pizza__weight_val.product-" + productId).addClass("active");
        $(".pizza__price.product-" + productId).addClass("active");

        // Обновляем скрытые поля вариации
        $(".variation_id").val(productId);
        $("select[name='attribute_pa_size']").val(selectedSize).trigger("change");
    }

    $(".toggle-container .size-toggle").on("click", function() {
        let selectedSize = $(this).find("input").val();
        updateVariation(selectedSize);
    });

    // Устанавливаем начальное значение вариации при загрузке
    if ($('.woocommerce-variation-add-to-cart').length) {
        let initialSize = $("select[name='attribute_pa_size']").val() || "30cm"; // Если не выбрано, ставим 30cm
        updateVariation(initialSize);
    }
});
