function changeQuantity(amount) {
    var input = document.getElementById('quantityInput');
    var currentValue = parseInt(input.value);

    if (currentValue + amount >= 1) {
        input.value = currentValue + amount;
    }
}