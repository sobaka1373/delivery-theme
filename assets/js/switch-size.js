document.addEventListener("DOMContentLoaded", function() {
    const size30cm = document.getElementById("size-30cm");
    const size40cm = document.getElementById("size-40cm");
    const price30cm = document.getElementById("price-30cm");
    const price40cm = document.getElementById("price-40cm");
    const weight30cm = document.getElementById("weight-30cm");
    const weight40cm = document.getElementById("weight-40cm");

    console.log(size30cm);
    console.log(size40cm);

    function initialize() {
        if (!size30cm || !size40cm || !price30cm || !price40cm || !weight30cm || !weight40cm) {
            console.error("Не все элементы найдены.");
            return;
        }

        function removeActiveClass() {
            size30cm.classList.remove("active");
            size40cm.classList.remove("active");
            price30cm.classList.remove("active");
            price40cm.classList.remove("active");
            weight30cm.classList.remove("active");
            weight40cm.classList.remove("active");
        }

        function setActiveElements(sizeElem, priceElem, weightElem) {
            removeActiveClass();
            sizeElem.classList.add("active");
            priceElem.classList.add("active");
            weightElem.classList.add("active");
        }

        size30cm.addEventListener("click", function() {
            setActiveElements(size30cm, price30cm, weight30cm);
        });

        size40cm.addEventListener("click", function() {
            setActiveElements(size40cm, price40cm, weight40cm);
        });
    }

    initialize();
});
