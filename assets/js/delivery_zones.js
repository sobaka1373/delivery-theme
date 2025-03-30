document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.includes("checkout") || window.location.pathname.includes("cart")) {

        ymaps.ready(init);
    }

    function init() {
        var myMap = new ymaps.Map('map', {
                center: [31.0041591111, 52.41496211111],
                zoom: 10,
                controls: ['geolocationControl', 'searchControl']
            }),
            deliveryPoint = new ymaps.GeoObject({
                geometry: {type: 'Point'},
                properties: {iconCaption: 'Адрес'}
            }, {
                preset: 'islands#blackDotIconWithCaption',
                draggable: true,
                iconCaptionMaxWidth: '215'
            }),
            searchControl = myMap.controls.get('searchControl');
        searchControl.options.set({noPlacemark: true, placeholderContent: 'Введите адрес доставки'});
        myMap.geoObjects.add(deliveryPoint);

        function onZonesLoad(json) {
            var deliveryZones = ymaps.geoQuery(json).addToMap(myMap);
            deliveryZones.each(function (obj) {
                obj.options.set({
                    fillColor: obj.properties.get('fill'),
                    fillOpacity: obj.properties.get('fill-opacity'),
                    strokeColor: obj.properties.get('stroke'),
                    strokeWidth: obj.properties.get('stroke-width'),
                    strokeOpacity: obj.properties.get('stroke-opacity')
                });
                obj.properties.set('balloonContent', obj.properties.get('description'));
            });

            searchControl.events.add('resultshow', function (e) {
                highlightResult(searchControl.getResultsArray()[e.get('index')]);
            });

            myMap.controls.get('geolocationControl').events.add('locationchange', function (e) {
                highlightResult(e.get('geoObjects').get(0));
            });

            deliveryPoint.events.add('dragstart', function () {
                deliveryPoint.properties.set({iconCaption: '', balloonContent: ''});
                deliveryPoint.options.set('iconColor', 'black');
            });

            deliveryPoint.events.add('dragend', function () {
                highlightResult(deliveryPoint);
            });

            function highlightResult(obj) {
                var coords = obj.geometry.getCoordinates(),
                    polygon = deliveryZones.searchContaining(coords).get(0);

                if (polygon) {
                    let cartTotalElement = document.querySelector('.cart-subtotal .woocommerce-Price-amount');
                    let totalCart = 0;

                    if (cartTotalElement) {
                        let rawText = cartTotalElement.innerText || cartTotalElement.textContent;
                        totalCart = parseFloat(rawText.replace(/[^\d,.]/g, '').replace(',', '.'));
                    }

                    if (polygon.properties.get('description').toLowerCase() === "красная") {
                        $('#billing_zone').val("red_zone");
                        if (totalCart && totalCart >= 45) {
                            $('#shipping_method_0_free_shipping3').click();
                            updateDeliveryPrice(0);
                        } else if (totalCart && totalCart < 45) {
                            $('#shipping_method_0_flat_rate5').click();
                            setTimeout(function() {
                                let priceFlatRate5 = getShippingPrice('#shipping_method_0_flat_rate5');
                                updateDeliveryPrice(priceFlatRate5);
                            }, 1000);
                        }
                        enableOrdBtn();
                    }
                    if (polygon.properties.get('description').toLowerCase() === "желтая") {
                        $('#billing_zone').val("yellow_zone");
                        if (totalCart && totalCart >= 35) {
                            $('#shipping_method_0_free_shipping3').click();
                            updateDeliveryPrice(0);
                        } else if (totalCart && totalCart < 35) {
                            $('#shipping_method_0_flat_rate4').click();
                            setTimeout(function() {
                                let priceFlatRate4 = getShippingPrice('#shipping_method_0_flat_rate4');
                                updateDeliveryPrice(priceFlatRate4);
                            }, 1000);
                        }
                        enableOrdBtn();
                    }
                    if (polygon.properties.get('description').toLowerCase() === "зеленая") {
                        $('#billing_zone').val("green_zone");
                        if (totalCart && totalCart >= 25) {
                            $('#shipping_method_0_free_shipping3').click();
                            updateDeliveryPrice(0);
                        } else if (totalCart && totalCart < 25) {
                            $('#shipping_method_0_flat_rate2').click();
                            setTimeout(function() {
                                let priceFlatRate2 = getShippingPrice('#shipping_method_0_flat_rate2');
                                updateDeliveryPrice(priceFlatRate2);
                            }, 1000);
                        }
                        enableOrdBtn();
                    }

                    deliveryZones.setOptions('fillOpacity', 0.4);
                    polygon.options.set('fillOpacity', 0.8);
                    deliveryPoint.geometry.setCoordinates(coords);
                    deliveryPoint.options.set('iconColor', polygon.properties.get('fill'));

                    if (typeof (obj.getThoroughfare) === 'function') {
                        setData(obj);
                    } else {
                        ymaps.geocode(coords, {results: 1}).then(function (res) {
                            var obj = res.geoObjects.get(0);
                            setData(obj);
                        });
                    }
                } else {
                    $('#billing_zone').val("error_zone");
                    deliveryZones.setOptions('fillOpacity', 0.4);
                    deliveryPoint.geometry.setCoordinates(coords);
                    deliveryPoint.properties.set({
                        iconCaption: 'Свяжитесь с оператором для уточнений условий доставки',
                        balloonContent: 'Cвяжитесь с оператором',
                        balloonContentHeader: ''
                    });
                    deliveryPoint.options.set('iconColor', 'black');
                    showError('Вне зоны доставки');
                    $('.complete-order').prop('disabled', true);
                    $('.complete-order').addClass('disabled');
                    console.log(true);
                }

                function setData(obj) {
                    var address = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');

                    if (address.trim() === '') {
                        address = obj.getAddressLine();
                    }
                    var price = polygon.properties.get('description');
                    price = price.match(/<strong>(.+)<\/strong>/)[1];
                    deliveryPoint.properties.set({
                        iconCaption: address,
                        balloonContent: address,
                        balloonContentHeader: price
                    });
                }
            }

            function geocode() {
                let adr = "Беларусь, Гомель";
                adr = adr + ", " + $('#billing_address_2').val() + " " + $('#billing_address_house').val();

                var request = adr;
                $('#loadingIndicator').show();
                ymaps.geocode(request).then(function (res) {
                    var obj = res.geoObjects.get(0),
                        error, hint;

                    if (obj) {
                        switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                            case 'exact':
                                break;
                            case 'number':
                            case 'near':
                            case 'range':
                                error = 'Неточный адрес, требуется уточнение';
                                hint = 'Уточните номер дома';
                                break;
                            case 'street':
                                error = 'Неполный адрес, требуется уточнение';
                                hint = 'Уточните номер дома';
                                break;
                            case 'other':
                            default:
                                error = 'Неточный адрес, требуется уточнение';
                                hint = 'Уточните адрес';
                        }
                    } else {
                        error = 'Адрес не найден';
                        hint = 'Уточните адрес';
                        $('.complete-order').prop('disabled', true);
                        $('.complete-order').addClass('disabled');
                    }

                    if (error) {
                        $('.complete-order').prop('disabled', true);
                        $('.complete-order').addClass('disabled');
                        if (!$('#shipping_method_0_pickup_location0').prop('checked')) {
                            showError(error);
                            showMessage(hint);
                        }
                    } else {
                        $('#notice').css('display', 'none');
                        $('#messageHeader').css('display', 'none');
                        $('#message').css('display', 'none');
                        highlightResult(obj);
                    }

                    $('#loadingIndicator').hide(); // Скрываем лоадер
                }, function (e) {
                    $('#loadingIndicator').hide(); // Скрываем лоадер
                });
            }

            function showResult(obj) {
                var mapContainer = $('#map');

                var coordinates = obj.geometry.getCoordinates();
                var bounds = obj.properties.get('boundedBy');
                if (!bounds) {
                    bounds = [coordinates, coordinates];
                }

                var mapState = ymaps.util.bounds.getCenterAndZoom(
                    bounds,
                    [mapContainer.width(), mapContainer.height()]
                );

                var address = obj.getAddressLine ? obj.getAddressLine() : "Адрес не найден";
                var shortAddress = obj.getThoroughfare ? obj.getThoroughfare() : "";
                shortAddress += obj.getPremiseNumber ? ", " + obj.getPremiseNumber() : "";
                shortAddress += obj.getPremise && obj.getPremise() ? ", " + obj.getPremise() : "";

                mapState.controls = [];
                createMap(mapState, shortAddress, coordinates);
                showMessage(address);
            }

            function showError(message) {
                $('#notice').text(message).show();
                $('#notice').css('display', 'block');
            }

            function showMessage(message) {
                $('#messageHeader').text('Данные получены:');
                $('#message').text(message);
            }

            $('.delivery-information #billing_address_house, .delivery-information #billing_address_2').on('input', function (e) {
                if (!$('#shipping_method_0_pickup_location0').prop('checked') && $('#billing_address_house').val() !== "") {
                    geocode();

                    setInterval(geocode, 3000);
                }
            });

            function enableOrdBtn() {
                $('.complete-order').prop('disabled', false);
                $('.complete-order').removeClass('disabled');
                deliveryPoint.properties.set({
                    iconCaption: 'Адрес',
                });
            }

            function getShippingPrice(shippingMethodId) {
                let amount = $(shippingMethodId).siblings().find('.woocommerce-Price-amount');
                let total = amount[0].innerText
                return total;
            }

            function updateDeliveryPrice(price) {
                $('#delivery-price-value').text(price);
            }
        }

        $.ajax({
            url: 'https://pishcheblok.by/wp-json/custom-routes/geojson',
            dataType: 'json',
            timeout: 15000,
            success: onZonesLoad
        });
    }
});
