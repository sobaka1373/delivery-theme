(function( $) {
    'use strict';
    $(document).ready(function() {
        $('.toggle-container .size-toggle').click(function (){
            console.log();
            let allClasses = $(this).attr('class');
            if (allClasses) {
                let classesArray = allClasses.split(' ');
                let productClass = classesArray.filter(cls => cls.startsWith('product-'));

                if (productClass.length > 0) {
                    let targetClass = productClass[0];
                    $('.variation-container .pizza__price').removeClass('active');
                    $('.variation-container .pizza__price.' + targetClass).addClass('active');
                }
            }
        });
    });
})(jQuery);