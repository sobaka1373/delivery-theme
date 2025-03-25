jQuery(document).ready(function($) {
    function updateVariation(selectedSize, container) {
        let productId;
        let weightElements = container.find(".pizza__weight_val");
        if (!weightElements.length) {
            console.error("Элементы .pizza__weight_val не найдены!");
            return;
        }

        let weightElement = selectedSize === "30cm" ? weightElements.first() : weightElements.last();
        let classAttr = weightElement.attr("class");

        if (!classAttr) {
            console.error("Атрибут class отсутствует у .pizza__weight_val", weightElement);
            return;
        }

        let match = classAttr.match(/product-(\d+)/);
        if (!match) {
            console.error("Не найден product-ID в классе", classAttr);
            return;
        }

        productId = match[1];

        container.find(".size-toggle").removeClass("active");
        container.find(".pizza__weight_val, .pizza__price").removeClass("active");

        container.find(".size-toggle input[value='" + selectedSize + "']").parent().addClass("active");
        container.find(".pizza__weight_val.product-" + productId).addClass("active");
        container.find(".pizza__price.product-" + productId).addClass("active");

        container.find(".variation_id").val(productId);
        container.find("select[name='attribute_pa_size']").val(selectedSize).trigger("change");
    }

    $(document).on("click", ".size-toggle", function() {
        let container = $(this).closest(".pizza__item, .product-details");
        let selectedSize = $(this).find("input").val();
        updateVariation(selectedSize, container);
    });

    $(".pizza__item, .product-details").each(function() {
        let container = $(this);
        if (container.find('.woocommerce-variation-add-to-cart').length || container.find(".variations_form").length) {
            let initialSize = container.find("select[name='attribute_pa_size']").val() || "30cm";
            updateVariation(initialSize, container);
        }
    });

    $(".pizza__item .toggle-container").each(function () {
        const labels = $(this).find("label");
        if (labels.length === 1) {
            labels.css("width", "100%");
        }
    });


});