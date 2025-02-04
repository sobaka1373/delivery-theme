document.addEventListener('DOMContentLoaded', function () {
    const deliveryButton = document.getElementById('deliveryButton1');
    const selfDeliveryButton = document.getElementById('deliveryButton2');
    const billingAddress1 = document.getElementById('billing_address_1');
    const billingAddress2 = document.getElementById('billing_address_2');
    const billingAddressHouse = document.getElementById('billing_address_house');
    const billingAddressKorp = document.getElementById('billing_address_korp');
    const billingAddressPod = document.getElementById('billing_address_pod');
    const billingAddressFlat = document.getElementById('billing_address_flat');
    const billingAddressFloor = document.getElementById('billing_address_floor');

    function toggleActiveClass(clickedElement, otherElement) {
        clickedElement.classList.add('active');
        otherElement.classList.remove('active');
    }

    function toggleDeliveryFields(isSelfDelivery) {
        if (isSelfDelivery) {
            billingAddress1.classList.add('hidden');
            billingAddress2.classList.add('hidden');
            billingAddressHouse.classList.add('hidden');
            billingAddressKorp.classList.add('hidden');
            billingAddressPod.classList.add('hidden');
            billingAddressFlat.classList.add('hidden');
            billingAddressFloor.classList.add('hidden');
        } else {
            billingAddress1.classList.remove('hidden');
            billingAddress2.classList.remove('hidden');
            billingAddressHouse.classList.remove('hidden');
            billingAddressKorp.classList.remove('hidden');
            billingAddressPod.classList.remove('hidden');
            billingAddressFlat.classList.remove('hidden');
            billingAddressFloor.classList.remove('hidden');
        }
    }

    deliveryButton.addEventListener('click', function () {
        toggleActiveClass(deliveryButton, selfDeliveryButton);
        toggleDeliveryFields(false); // показываем поля для доставки
    });

    selfDeliveryButton.addEventListener('click', function () {
        toggleActiveClass(selfDeliveryButton, deliveryButton);
        toggleDeliveryFields(true); // скрываем поля для самовывоза
    });
});
