jQuery(document).ready(function ($) {

    let ymapslMap;
    let objectManager;

    ymaps.ready(function () {
        ymapslMap = new ymaps.Map('ymapsl_map', {
            center: [56.326944, 44.0075],
            zoom: 10,
            type: 'yandex#map',
            controls: []
        });

        ymapslMap.controls.add('zoomControl', {
            size: "large"
        });
        ymapslMap.behaviors.disable('scrollZoom');

        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: false,
            clusterOpenBalloonOnClick: false,
        });

        // Чтобы задать опции одиночным объектам и кластерам,
        // обратимся к дочерним коллекциям ObjectManager.
        objectManager.objects.options.set('preset', 'islands#greenDotIcon');
        // objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
        ymapslMap.geoObjects.add(objectManager);

        $("#ymapsl_search_cities").select2({
            placeholder: "Выберите город",
            theme: "material",
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                noResults: function () {
                    return "Такого города нет...";
                }
            }
        }).on('select2:select', function (e) {
            console.log($(this).val());
            searchStoresAjax($(this).val());
        });


        $("div#shops").on("click","a", function(){
            myFunction($(this).data('object-id'))
        });

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
                // Handle the beforeSend event
                $('.loader.loader-border').addClass('is-active');
            },
            success: function (json) {
                console.log(json);

                if ($.isEmptyObject(json)) {
                    return;
                }

                $.each(json[0], function(k, v) {
                    console.log(v);
                });
                objectManager.add(json[1]);
                ymapslMap.setBounds(objectManager.getBounds(),{
                    checkZoomRange: true,
                });
            }
        });
    }

});

