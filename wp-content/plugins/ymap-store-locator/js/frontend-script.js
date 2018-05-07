jQuery(document).ready(function ($) {

    let ymapslMap;
    let objectManager;
    let ymapslLoader = $('.ymapsl-loader-wrap');
    let ymapslStoresList = $('#ymapsl_stores ul');

    ymapslDisplayLoader('show', 'ЗАГРУЗКА КАРТЫ');

    ymaps.ready({
        successCallback: function () {
            ymapslMap = new ymaps.Map('ymapsl_map', {
                center: [55.755814, 37.617635],
                zoom: 9,
                type: 'yandex#map',
                controls: []
            });

            ymapslDisplayLoader('hide');

            ymapslMap.controls.add('zoomControl', {
                size: "large"
            });
            ymapslMap.behaviors.disable('scrollZoom');

            objectManager = new ymaps.ObjectManager({
                // Чтобы метки начали кластеризоваться, выставляем опцию.
                clusterize: true,
                // ObjectManager принимает те же опции, что и кластеризатор.
                groupByCoordinates: false,
                clusterDisableClickZoom: false,
                clusterOpenBalloonOnClick: false,
            });

            objectManager.objects.options.set('preset', 'islands#blueStretchyIcon');
            // objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
            ymapslMap.geoObjects.add(objectManager);

            $("#ymapsl_search_cities").select2({
                placeholder: "Выберите город",
                theme: "material",
                language: {
                    noResults: function () {
                        return "Такого города нет...";
                    }
                }
            }).on('select2:select', function (e) {
                console.log($(this).val());

                ymapslStoresList.html('');
                objectManager.removeAll();

                ymapslDisplayLoader('show', 'ПОИСК ПУНКТОВ ВЫДАЧИ');

                $('html, body').animate({
                    scrollTop: $("#ymapsl_search").offset().top - ($('header.l-header').outerHeight() + $('div#wpadminbar').outerHeight())
                }, 1000);
                searchStoresAjax($(this).val());
            });


            ymapslStoresList.on("click", "a", function () {
                myFunction($(this).data('object-id'))
            });

        },
        errorCallback: function (ymapslMapInitError) {
            console.log('Ошибка загрузки Яндекс Карты:' + ymapslMapInitError);
        }
    });


    function searchStoresAjax(selectedCity) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ymapsl_search_stores',
                city: selectedCity,
            },
            url: ymapsl_ajax.url,
            beforeSend: function () {

            },
            success: function (json) {

                // Ближайшая станция метро (возможно пригодится!!!)
                // ymaps.geoQuery(ymaps.geocode([55.852508, 37.494944], { kind: 'metro', results: 1 })).addToMap(ymapslMap);

                console.log(json);

                if ($.isEmptyObject(json)) {
                    ymapslStoresList.html('<li class="ymapsl-stores-empty-list">Во всех пунктах города закончились карты =(</li>');
                    ymapslDisplayLoader('hide');
                    return;
                }

                // Выводим список пунктов выдочи
                let src_res = '';
                for (let i = 0; i < json[0]['address'].length; i++) {
                    let sch = i + 1;
                    // var placemark = new ymaps.Placemark([json[i].lon, json[i].lat], {
                    //     iconContent: sch,
                    //     balloonContentHeader: '<div style="color:#ff0303;font-weight:bold">' + json[i].address + '</div>',
                    //     balloonContentBody: '<div style="font-size:13px;"><div><strong>Адрес:</strong> ' + json[i].address + '<br>' + '<strong>Режим работы:</strong> ' + json[i].rrab + '<br></div></div>'
                    // }, {
                    //     // Опции
                    //     preset: 'twirl#nightStretchyIcon' // иконка растягивается под контент
                    // });
                    // myCollection.add(placemark);
                    // src_res = src_res + '<p>' + sch + '. ' + '<a href="#" onclick="myFunction(' + json[i].lat + ', ' + json[i].lon + ",'" + json[i].address + "')" + '\">' + json[i].address + '</a></p>';

                    let storeMetro = '';
                    if (json[0].address[i].metro){
                        // storeComment = '<span><strong>примечание: </strong>'+json[0].address[i].comment+'</span>';
                        storeMetro = '<div class="ymapsl-store-details-metro"><span>'+json[0].address[i].metro+'</span></div>';
                    }

                    let storeComment = '';
                    if (json[0].address[i].comment){
                        // storeComment = '<span><strong>примечание: </strong>'+json[0].address[i].comment+'</span>';
                        storeComment = '<p class="ymapsl-store-details-comment"><span><strong>Примечание: </strong>'+json[0].address[i].comment+'</span></p>';
                    }

                    src_res +=
                        '<li>' +
                          '<div class="ymapsl-store-details">' +
                              '<div class="ymapsl-store-name">' +
                                '<span>' + json[0].address[i].name + '</span>' +
                                '<div>' +
                                    '<span class="store-sim-qty-title">кол-во: </span>' +
                                    '<span class="store-sim-qty">'+ json[0].address[i].qty +'</span>' +
                                '</div>' +
                                '</div>' +
                              '<div class="ymapsl-store-address"><a data-object-id="' + json[0].address[i].id + '" data-address="' + json[0].address[i].address + '"><span><i class="far fa-map-marker-alt"></i> ' + json[0].address[i].address + '</span></a></div>' +
                              storeMetro +
                              '<div class="ymapsl-store-schedule"><i class="far fa-clock"></i> ' +json[0].address[i].opening_hours +'</div>' +
                              '<div class="ymapsl-store-contact"><i class="far fa-phone"></i> ' + json[0].address[i].phone + '</div>' +
                             storeComment +
                          '</div>' +
                        '</li>';
                }

                ymapslStoresList.html(src_res);

                // Добавляем элементы на карту
                objectManager.add(json[1]);

                // objectManager.options.set({gridSize: 80});
                // ymapslMap.margin.setDefaultMargin(70);
                ymapslMap.setBounds(objectManager.getBounds(), {
                    checkZoomRange: true,
                });

                // if (json[0]['address'].length > 1){
                //     ymapslMap.setBounds(objectManager.getBounds(), {
                //         checkZoomRange: true,
                //     });
                // } else if (json[0]['address'].length === 1) {
                //     ymapslMap.setBounds(objectManager.getBounds(), {
                //         checkZoomRange: false,
                //     });
                //     ymapslMap.setZoom(17, {duration: 300});
                // }

                ymapslDisplayLoader('hide');
            }
        });
    }

    function myFunction(id) {

        if ($( "#ymapsl_stores ul li" ).length === 1) {
            objectManager.objects.balloon.open(id);
            return;
        }

        // Плавное перемещение без приближения
        ymapslMap.panTo(objectManager.objects.getById(id).geometry.coordinates, {
            checkZoomRange:true,
        }).then(function () {
            objectManager.objects.balloon.open(id);
        });

        //Установка цента
        // ymapslMap.setCenter(objectManager.objects.getById(id).geometry.coordinates, 17,{
        //     duration:1000
        // }).then(function () {
        //     objectManager.objects.balloon.open(id);
        // });

        // Плавное перемещение с приближением
        // ymapslMap.panTo(objectManager.objects.getById(id).geometry.coordinates, {
        //     checkZoomRange:true,
        //     duration:2000
        // }).then(function () {
        //     ymapslMap.setZoom(17, {duration: 1000}).then(function () {
        //         objectManager.objects.balloon.open(id);
        //     });
        // });


    }

    function ymapslDisplayLoader(state, message) {
        if (state === 'show') {
            ymapslLoader.find('.ymapsl-loader-text').text(message);
            ymapslLoader.show();
        } else if (state === 'hide') {
            ymapslLoader.find('.ymapsl-loader-text').text('');
            ymapslLoader.hide();
        }
    }

    $('#ymapsl_map_wrap').resize(function () {
        $('#ymapsl_map').width($(window).width());
        $('#ymapsl_map').height($(window).height());
    });

});

