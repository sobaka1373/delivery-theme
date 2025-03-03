jQuery(document).ready(function($) {
    function updateVariation(selectedSize, container) {
        let productId = selectedSize === "30cm" ? container.find(".pizza__weight_val").first().attr("class").match(/product-(\d+)/)[1]
            : container.find(".pizza__weight_val").last().attr("class").match(/product-(\d+)/)[1];

        container.find(".size-toggle").removeClass("active");
        container.find(".pizza__weight_val, .pizza__price").removeClass("active");

        container.find(".size-toggle input[value='" + selectedSize + "']").parent().addClass("active");
        container.find(".pizza__weight_val.product-" + productId).addClass("active");
        container.find(".pizza__price.product-" + productId).addClass("active");

        container.find(".variation_id").val(productId);
        container.find("select[name='attribute_pa_size']").val(selectedSize).trigger("change");
    }

    $(".toggle-container .size-toggle").on("click", function() {
        let container = $(this).closest(".pizza__item");
        let selectedSize = $(this).find("input").val();
        updateVariation(selectedSize, container);
    });

    $(".pizza__item").each(function() {
        let container = $(this);
        if (container.find('.woocommerce-variation-add-to-cart').length) {
            let initialSize = container.find("select[name='attribute_pa_size']").val() || "30cm"; // Если не выбрано, ставим 30cm
            updateVariation(initialSize, container);
        }
    });
});
