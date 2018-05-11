"use strict";

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

            objectManager.objects.options.set('preset', 'islands#darkblueStretchyIcon');
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
            }).on('select2:select', function () {
                console.log($(this).val());

                ymapslStoresList.html('');
                objectManager.removeAll();

                ymapslDisplayLoader('show', 'ПОИСК ПУНКТОВ ВЫДАЧИ');

                $('html, body').animate({
                    scrollTop: $("#ymapsl_search").offset().top - ($('header.l-header').outerHeight() + $('div#wpadminbar').outerHeight())
                }, 1000);
                searchStoresAjax($(this).val());
            });

            ymapslStoresList.on("click", ".ymapsl-store-address", function () {
                myFunction($(this).data('object-id'))
            });
        },
        errorCallback: function (ymapslMapInitError) {
            console.log('Ошибка загрузки Яндекс Карты:' + ymapslMapInitError);
        }
    });

    function isToday(day) {
        let currentDate = new Date();
        let days = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        let today = days[currentDate.getDay()];

        return (day === today);
    }

    function isOpen(timeRange) {
        let currentDate = new Date();
        let currentTimeHours = currentDate.getHours();
        currentTimeHours = currentTimeHours < 10 ? "0" + currentTimeHours : currentTimeHours;
        let currentTimeMinutes = currentDate.getMinutes();
        let timeNow = currentTimeHours + "" + currentTimeMinutes;

        let openTimex = timeRange[0].split('-')[0].split(':')[0] + "" + timeRange[0].split('-')[0].split(':')[1];
        let closeTimex = timeRange[0].split('-')[1].split(':')[0] + "" + timeRange[0].split('-')[1].split(':')[1];

        return (timeNow >= openTimex && timeNow <= closeTimex);
    }

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
                $('.ymapsl-store-schedule__header').off();
            },
            success: function (json) {
                console.log(json);

                // Ближайшая станция метро (возможно пригодится!!!)
                // ymaps.geoQuery(ymaps.geocode([55.852508, 37.494944], { kind: 'metro', results: 1 })).addToMap(ymapslMap);

                if ($.isEmptyObject(json)) {
                    ymapslStoresList.html('<li class="ymapsl-stores-empty-list">Во всех пунктах города закончились карты =(</li>');
                    ymapslDisplayLoader('hide');
                    return;
                }

                let localDeys = {
                    'monday': 'Понедельник',
                    'tuesday': 'Вторник',
                    'wednesday': 'Среда',
                    'thursday': 'Четверг',
                    'friday': 'Пятница',
                    'saturday': 'Суббота',
                    'sunday': 'Воскресенье'
                };

                // Выводим список пунктов выдочи
                let src_res = '';
                for (let i = 0; i < json[0]['address'].length; i++) {

                    let storeMetro = '';
                    if (json[0].address[i].metro) {
                        storeMetro =
                            '<div class="ymapsl-store-details-metro">' +
                              '<span>' + json[0].address[i].metro + '</span>' +
                            '</div>';
                    }

                    let storeComment = '';
                    if (json[0].address[i].comment) {
                        storeComment =
                            '<p class="ymapsl-store-details-comment">' +
                              '<span><strong>Примечание: </strong>' + json[0].address[i].comment + '</span>' +
                            '</p>';
                    }

                    let storeHoursHeaderHtml = '';
                    let storeHoursTableHtml = '<table>';
                    if (json[0].address[i].hours && json[0].address[i].hours !== 'need to clarify') {
                        $.each(json[0].address[i].hours, function (index, value) {
                            let localizedIndex = localDeys[index];
                            if (value.length > 0 && value.length < 2) {

                                let timeWithoutLunchBreak = value[0].replace("-", " – ");
                                if (isToday(index)) {
                                    if (isOpen(value)) {
                                        storeHoursHeaderHtml =
                                            '<span class="openorclosed open">Открыто: </span>' +
                                            '<span class="ymapsl-store-schedule__header-time">' +
                                            timeWithoutLunchBreak + '</span>';
                                        storeHoursTableHtml +=
                                            '<tr class="today open">' +
                                              '<td>' + localizedIndex + '</td>' +
                                              '<td>' + timeWithoutLunchBreak + '</td>' +
                                            '</tr>';
                                    } else {
                                        storeHoursHeaderHtml =
                                            '<span class="openorclosed closed">Закрыто. </span>' +
                                            '<span class="ymapsl-store-schedule__header-time">' +
                                            timeWithoutLunchBreak + '</span>';
                                        storeHoursTableHtml +=
                                            '<tr class="today closed">' +
                                              '<td>' + localizedIndex + '</td>' +
                                              '<td>' + timeWithoutLunchBreak + '</td>' +
                                            '</tr>';
                                    }
                                } else {
                                    storeHoursTableHtml +=
                                        '<tr>' +
                                          '<td>' + localizedIndex + '</td>' +
                                          '<td>' + timeWithoutLunchBreak + '</td>' +
                                        '</tr>';
                                }

                            } else if (value.length > 0 && value.length >= 2) {

                                let timeWithLunchBreak = '<ul>';
                                for (let j = 0; j < value.length; j++) {
                                    timeWithLunchBreak += '<li>' + value[j].replace("-", " – ") + '</li>';
                                }
                                timeWithLunchBreak += '</ul>';

                                if (isToday(index)) {
                                    storeHoursTableHtml +=
                                        '<tr class="today">' +
                                          '<td>' + localizedIndex + '</td>' +
                                          '<td>' + timeWithLunchBreak + '</td>' +
                                        '</tr>';

                                    let openTime = value[0].split('-')[0];
                                    let closeTime = value[value.length - 1].split('-')[1];
                                    storeHoursHeaderHtml =
                                        'Сегодня: <span class="ymapsl-store-schedule__header-time">' +
                                                    openTime + ' – ' + closeTime +
                                                 '</span>';
                                } else {
                                    storeHoursTableHtml +=
                                        '<tr>' +
                                          '<td>' + localizedIndex + '</td>' +
                                          '<td>' + timeWithLunchBreak + '</td>' +
                                        '</tr>';
                                }

                            } else {

                                if (isToday(index)) {
                                    storeHoursTableHtml +=
                                        '<tr class="closed today">' +
                                          '<td>' + localizedIndex + '</td>' +
                                          '<td>Выходной</td>' +
                                        '</tr>';
                                    storeHoursHeaderHtml =
                                        'Сегодня: <span class="ymapsl-store-schedule__header-time">' +
                                                    'Выходной' +
                                                 '</span>';
                                } else {
                                    storeHoursTableHtml +=
                                        '<tr class="closed">' +
                                          '<td>' + localizedIndex + '</td>' +
                                          '<td>Выходной</td>' +
                                        '</tr>';
                                }

                            }
                        });

                    } else if (json[0].address[i].hours && json[0].address[i].hours === 'need to clarify') {
                        storeHoursHeaderHtml = 'уточнять по телефону';
                        console.log(storeHoursHeaderHtml);
                    }
                    storeHoursTableHtml += '</table>';

                    src_res +=
                        '<li>' +
                          '<div class="ymapsl-store-details">' +
                            '<div class="ymapsl-store-name">' +
                              '<span>' + json[0].address[i].name + '</span>' +
                              '<div>' +
                                '<span class="store-sim-qty-title">кол-во: </span>' +
                                '<span class="store-sim-qty">' + json[0].address[i].qty + '</span>' +
                              '</div>' +
                            '</div>' +
                            '<div class="ymapsl-store-address" data-object-id="' + json[0].address[i].id + '" ' +
                                 'data-address="' + json[0].address[i].address + '">' +
                              '<span><i class="fas fa-map-marker-alt"></i> ' + json[0].address[i].address + '</span>' +
                            '</div>' +
                            storeMetro +
                            '<div class="ymapsl-store-schedule">' +
                              '<div class="ymapsl-store-schedule__header">' +
                                '<i class="far fa-clock"></i> ' + storeHoursHeaderHtml +
                              '</div>' +
                              '<div class="ymapsl-store-schedule__dropdown" style="display: none">' +
                                storeHoursTableHtml +
                              '</div>' +
                            '</div>' +
                            '<div class="ymapsl-store-contact">' +
                              '<i class="far fa-phone"></i> ' + json[0].address[i].phone +
                            '</div>' +
                            storeComment +
                          '</div>' +
                        '</li>';
                }

                ymapslStoresList.html(src_res);

                // Добавляем элементы на карту
                objectManager.add(json[1]);

                ymapslMap.setBounds(objectManager.getBounds(), {
                    checkZoomRange: true,
                });

                $('.ymapsl-store-schedule').click(function () {
                    $(this).toggleClass('active');
                    $(this).find('.ymapsl-store-schedule__dropdown').slideToggle();
                });

                ymapslDisplayLoader('hide');
            }
        });
    }

    function myFunction(id) {
        if ($("#ymapsl_stores ul li").length === 1) {
            objectManager.objects.balloon.open(id);
            return;
        }
        // Плавное перемещение без приближения
        ymapslMap.panTo(objectManager.objects.getById(id).geometry.coordinates, {
            checkZoomRange: true,
        }).then(function () {
            objectManager.objects.balloon.open(id);
        });
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
        let ymapslMapSize = $('#ymapsl_map');
        ymapslMapSize.width($(window).width());
        ymapslMapSize.height($(window).height());
    });

});

