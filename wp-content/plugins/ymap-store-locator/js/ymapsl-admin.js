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

    // $("#ymapsl_id_ta").inputmask();


    ymaps.ready(function () {
        let ymapslMap = new ymaps.Map('YMapsID', {
            center: [55.76, 37.64],
            zoom: 10,
            type: 'yandex#map',
            controls: []
        });

        let ymapslLng = $('#ymapsl_lng').val();
        let ymapslLat = $('#ymapsl_lat').val();
        if (ymapslLng !== '' && ymapslLat !== '') {
            let startCoords = JSON.parse("[" + ymapslLng + "," + ymapslLat + "]");
            let myPlacemark = new ymaps.Placemark(startCoords, {
                hintContent: 'Собственный значок метки',
                balloonContent: 'Это красивая метка'
            });
            // myPlacemark.geometry.setCoordinates(startLocation);
            ymapslMap.geoObjects.add(myPlacemark);
            ymapslMap.setBounds(ymapslMap.geoObjects.getBounds());
            ymapslMap.setZoom(16);
        }

        $('#check_geocode_btn').click(function (e) {

            let ymapslCityValid    = $('#ymapsl_city').parsley().validate();
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

                // var obj = res.geoObjects.get(0),
                //     error, hint;

                // if (obj) {
                //     // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                //     switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                //         case 'exact':
                //             break;
                //         case 'number':
                //         case 'near':
                //         case 'range':
                //             error = 'Неточный адрес, требуется уточнение';
                //             hint = 'Уточните номер дома';
                //             break;
                //         case 'street':
                //             error = 'Неполный адрес, требуется уточнение';
                //             hint = 'Уточните номер дома';
                //             break;
                //         case 'other':
                //         default:
                //             error = 'Неточный адрес, требуется уточнение';
                //             hint = 'Уточните адрес';
                //     }
                // } else {
                //     error = 'Адрес не найден';
                //     hint = 'Уточните адрес';
                // }

                // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                // if (error) {
                //     // showError(error);
                //     // showMessage(hint);
                // } else {
                //     // showResult(obj);
                //     // Выбираем первый результат геокодирования.
                // }

                let firstGeoObject = res.geoObjects.get(0);
                // Координаты геообъекта.
                let coords = firstGeoObject.geometry.getCoordinates();
                let bounds = firstGeoObject.properties.get('boundedBy');

                firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
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


            }, function (e) {
                console.log(e)
            })

        }

    });

    $("#publish1").click(function () {
        // var firstErrorElem, currentTabClass, elemClass,
        //     errorMsg	= '<div id="message" class="error"><p>' + wpslL10n.requiredFields + '</p></div>',
        //     missingData = false;
        //
        // // Remove error messages and css classes from previous submissions.
        // $( "#wpbody-content .wrap #message" ).remove();
        // $( ".wpsl-required" ).removeClass( "wpsl-error" );
        //
        // // Loop over the required fields and check for a value.
        // $( ".wpsl-required" ).each( function() {
        //     if ( $( this ).val() == "" ) {
        //         $( this ).addClass( "wpsl-error" );
        //
        //         if ( typeof firstErrorElem === "undefined" ) {
        //             firstErrorElem = getFirstErrorElemAttr( $( this ) );
        //         }
        //
        //         missingData = true;
        //     }
        // });
        //
        // // If one of the required fields are empty, then show the error msg and make sure the correct tab is visible.
        // if ( missingData ) {
        //     $( "#wpbody-content .wrap > h2" ).after( errorMsg );
        //
        //     if ( typeof firstErrorElem.val !== "undefined" ) {
        //         if ( firstErrorElem.type == "id" ) {
        //             currentTabClass = $( "#" + firstErrorElem.val + "" ).parents( ".wpsl-tab" ).attr( "class" );
        //             $( "html, body" ).scrollTop( Math.round( $( "#" + firstErrorElem.val + "" ).offset().top - 100 ) );
        //         } else if ( firstErrorElem.type == "class" ) {
        //             elemClass		= firstErrorElem.val.replace( /wpsl-required|wpsl-error/g, "" );
        //             currentTabClass = $( "." + elemClass + "" ).parents( ".wpsl-tab" ).attr( "class" );
        //             $( "html, body" ).scrollTop( Math.round( $( "." + elemClass + "" ).offset().top - 100 ) );
        //         }
        //
        //         currentTabClass = $.trim( currentTabClass.replace( /wpsl-tab|wpsl-active/g, "" ) );
        //     }
        //
        //     // If we don't have a class of the tab that should be set to visible, we just show the first one.
        //     if ( !currentTabClass ) {
        //         activateStoreTab( 'first' );
        //     } else {
        //         activateStoreTab( currentTabClass );
        //     }
        //
        //     /*
        //      * If not all required fields contains data, and the user has
        //      * clicked the submit button. Then an extra css class is added to the
        //      * button that will disabled it. This only happens in WP 3.8 or earlier.
        //      *
        //      * We need to make sure this css class doesn't exist otherwise
        //      * the user can never resubmit the page.
        //      */
        //     $( "#publish" ).removeClass( "button-primary-disabled" );
        //     $( ".spinner" ).hide();
        //
        //     return false;
        // } else {
        //     return true;
        // }

        if ($('#ymapsl_city').val() == '' || $('#ymapsl_address').val() == '' || $('#ymapsl_id_ta').val() == '') {

            $('.ymapsl-error-form').addClass('active');
            $('.ymapsl-error-form').text('Заполните поля: ' + $('.ymapsl_id_ta_form label').text() + ', ' + $('.ymapsl_city_form label').text() + ', ' + $('.ymapsl_address_form label').text());

            $("#publish").removeClass("button-primary-disabled");
            $(".spinner").hide();

            $('html, body').animate({
                scrollTop: $("#ymapsl_box_id").offset().top
            }, 1000);

            return false;
        } else {
            $('.ymapsl-error-form').removeClass('active');
            return true;
        }

    });

});