jQuery(document).ready(function ($) {

    Parsley.addMessages('ru', {
        defaultMessage: "Некорректное значение.",
        type: {
            email: "Введите адрес электронной почты.",
            url: "Введите URL адрес.",
            number: "Введите число.",
            integer: "Введите целое число.",
            digits: "Введите только цифры.",
            alphanum: "Введите буквенно-цифровое значение."
        },
        notblank: "Это поле должно быть заполнено.",
        required: "Обязательное поле.",
        pattern: "Это значение некорректно.",
        min: "Это значение должно быть не менее чем %s.",
        max: "Это значение должно быть не более чем %s.",
        range: "Это значение должно быть от %s до %s.",
        minlength: "Это значение должно содержать не менее %s символов.",
        maxlength: "Это значение должно содержать не более %s символов.",
        length: "Это значение должно содержать от %s до %s символов.",
        mincheck: "Выберите не менее %s значений.",
        maxcheck: "Выберите не более %s значений.",
        check: "Выберите от %s до %s значений.",
        equalto: "Это значение должно совпадать."
    });

    Parsley.setLocale('ru');

    let validForm = $('form#post').parsley().on('field:validated', function () {
        var ok = $('.parsley-error').length === 0;
        $('.bs-callout-info').toggleClass('hidden', !ok);
        $('.bs-callout-warning').toggleClass('hidden', ok);
    }).on('field:error', function () {
        // This global callback will be called for any field that fails validation.
        // $('html, body').animate({
        //     scrollTop: $("#ymapsl_box_id").offset().top
        // }, 1000);
        // console.log('Validation failed for: ', this.$element);
    });

    ymaps.ready(function () {
        let ymapslMap = new ymaps.Map('YMapsID', {
            center: [55.76, 37.64],
            zoom: 10,
            type: 'yandex#map',
            controls: []
        });
        ymapslMap.controls.add('zoomControl', {
            size: "large"
        });
        ymapslMap.behaviors.disable('scrollZoom');

        let ymapslLng = $('#ymapsl_lng').val();
        let ymapslLat = $('#ymapsl_lat').val();
        if (ymapslLng !== '' && ymapslLat !== '') {
            let startCoords = JSON.parse("[" + ymapslLng + "," + ymapslLat + "]");
            let myPlacemark = new ymaps.Placemark(startCoords, {
                    hintContent: 'Собственный значок метки',
                    balloonContent: 'Это красивая метка'
                },
                {
                    draggable: true
                });
            myPlacemark.events.add('dragend', function (e) {
                let target = e.get('target');
                let coords = target.geometry.getCoordinates();
                $('#ymapsl_lng').val(coords[0]);
                $('#ymapsl_lat').val(coords[1]);
            });

            // myPlacemark.geometry.setCoordinates(startLocation);
            ymapslMap.geoObjects.add(myPlacemark);

            ymapslMap.setBounds(ymapslMap.geoObjects.getBounds());
            ymapslMap.setZoom(16);
        }

        $('#check_geocode_btn').click(function (e) {

            let ymapslCityValid = $('#ymapsl_city').parsley().validate();
            let ymapslAdderssValid = $('#ymapsl_address').parsley().validate();

            if ((ymapslCityValid === true) && (ymapslAdderssValid === true)) {
                console.log('valid');
                geocode();
            }
        });

        function geocode() {
            // Забираем запрос из поля ввода.
            let request = $('#ymapsl_city').val() + ', ' + $('#ymapsl_address').val();

            // Геокодируем введённые данные.
            ymaps.geocode(request, {
                /**
                 * Опции запроса
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
                 */
                results: 1
            }).then(function (res) {

                let firstGeoObject = res.geoObjects.get(0);

                // Координаты геообъекта.
                let coords = firstGeoObject.geometry.getCoordinates();

                let bounds = firstGeoObject.properties.get('boundedBy');

                firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
                firstGeoObject.options.set('draggable',true);

                firstGeoObject.events.add('dragend', function (e) {
                    let target = e.get('target');
                    let coords = target.geometry.getCoordinates();
                    $('#ymapsl_lng').val(coords[0]);
                    $('#ymapsl_lat').val(coords[1]);
                });

                // Получаем строку с адресом и выводим в иконке геообъекта.
                firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

                // Добавляем первый найденный геообъект на карту.
                ymapslMap.geoObjects.removeAll();

                ymapslMap.geoObjects.add(firstGeoObject);

                // Масштабируем карту на область видимости геообъекта.
                ymapslMap.setBounds(bounds, {
                    // Проверяем наличие тайлов на данном масштабе.
                });

                ymapslMap.setZoom(16);

                $('#ymapsl_lng').val(coords[0]);
                $('#ymapsl_lat').val(coords[1]);

                $('html, body').animate({
                    scrollTop: $("#YMapsID").offset().top
                }, 1000);


            }, function (e) {
                console.log(e)
            })

        }

    });

});