jQuery(document).ready(function ($) {
    $('#billing_city').bind("input paste change keyup focusout", function () {
        var $this = $(this),
            val = $this.val();
        val = val.substr(0, 1).toUpperCase() + val.substr(1);
        $('#billing_city').val(val);
    });

    function enforceRegion(suggestion) {
        console.log(suggestion[0]);
        var sgt = $("#billing_city").suggestions();
        sgt.clear();
        sgt.setOptions({
            constraints: {
                locations: { kladr_id: suggestion[0].data.kladr_id }
            },
            restrict_value: true,

        });

    }

    var region = $("select#billing_state");



    $(function () {

        $("select#billing_state").suggestions({
            token: "5ef98f5781a106962077fb18109095f9f11ebac1",
            type: "ADDRESS",
            bounds: "region",
            geoLocation: false,
            onSuggestionsFetch: enforceRegion,
            // // запретить автоисправление по пробелу
            // triggerSelectOnSpace: false,
            // // запрещаем автоподстановку по Enter
            // triggerSelectOnEnter: false,
            // // запретить автоисправление при выходе из текстового поля
            // triggerSelectOnBlur: false,
        });

        $("#billing_city").suggestions({
            //addon: 'clear',
            // serviceUrl: "https://suggestions.dadata.ru/suggestions/api/4_1/rs",
            token: "94efab2e13b37cf6fe0d782a4c3f685ca2bf7627",
            type: "ADDRESS",
            count: 5,
            bounds: "city-settlement",

            onSelectNothing: function (query) {
                var $input = $(this);
                $input.val('');
                console.log('false');
                console.log(this);
            },

            onSelect: function (suggestion, changed) {
                if (suggestion.data.city != null)
                    $("#billing_city").val(suggestion.data.city);

                if (suggestion.data.settlement_with_type != null)
                    $("#billing_city").val(suggestion.data.settlement_with_type);

                /*$("#billing_address_1").val(function () {
                    var summ = '';
                    if (suggestion.data.street_with_type !== null) summ += suggestion.data.street_with_type + ', ';
                    if (suggestion.data.house_type !== null) summ += suggestion.data.house_type + '. ';
                    if (suggestion.data.house !== null) summ += suggestion.data.house;
                    if (suggestion.data.block_type !== null) summ += ' ' + suggestion.data.block_type + '. ';
                    if (suggestion.data.block !== null) summ += suggestion.data.block;
                    return summ;
                });*/

                $("#billing_state").val(suggestion.data.region_with_type);
                $("#fias_field input").val(suggestion.data.fias_id);
                $("#billing_city").change();
                $("#billing_state").change();
                $("#billing_address_1").suggestions({
                    //addon: 'clear',
                    // serviceUrl: "https://suggestions.dadata.ru/suggestions/api/4_1/rs",
                    token: "94efab2e13b37cf6fe0d782a4c3f685ca2bf7627",
                    type: "ADDRESS",
                    geoLocation: {kladr_id: suggestion.data.kladr_id},
                    count: 5,

                    onSelectNothing: function (query) {
                        console.log('false');
                        console.log(this);
                    },

                    onSelect: function (suggestion, changed) {

                        var summ = '';
                        if (suggestion.data.street_with_type !== null) summ += suggestion.data.street_with_type + ', ';
                        if (suggestion.data.house_type !== null) summ += suggestion.data.house_type + '. ';
                        if (suggestion.data.house !== null) summ += suggestion.data.house;
                        if (suggestion.data.block_type !== null) summ += ' ' + suggestion.data.block_type + '. ';
                        if (suggestion.data.block !== null) summ += suggestion.data.block;

                        $("#billing_address_1").val(summ);
                        $("#fias_field input").val(suggestion.data.fias_id);

                        $("#billing_address_1").change();
                        $("#billing_postcode").val(suggestion.data.postal_code);
                        console.log(suggestion);
                    }
                });

                console.log('true');
                console.log(suggestion);
            }
        });

        $("#billing_address_1").suggestions({
            //addon: 'clear',
            // serviceUrl: "https://suggestions.dadata.ru/suggestions/api/4_1/rs",
            token: "94efab2e13b37cf6fe0d782a4c3f685ca2bf7627",
            type: "ADDRESS",
            count: 5,

            onSelectNothing: function (query) {
                console.log('false');
                console.log(this);
            },

            onSelect: function (suggestion, changed) {
                var summ = '';
                if (suggestion.data.street_with_type !== null) summ += suggestion.data.street_with_type + ', ';
                if (suggestion.data.house_type !== null) summ += suggestion.data.house_type + '. ';
                if (suggestion.data.house !== null) summ += suggestion.data.house;
                if (suggestion.data.block_type !== null) summ += ' ' + suggestion.data.block_type + '. ';
                if (suggestion.data.block !== null) summ += suggestion.data.block;

                $("#billing_address_1").val(summ);
                console.log(suggestion);
            }
        });
    });
});

jQuery(function ($) {
    $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: 'Предыдущий',
        nextText: 'Следующий',
        currentText: 'Сегодня',
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв.', 'Фев.', 'Мрт', 'Апр', 'Май', 'Июн',
            'Июл.', 'Авг', 'Сен.', 'Окт.', 'Нбр.', 'Дек.'],
        dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
        dayNamesShort: ['Вскр.', 'Пон.', 'Втр.', 'Ср.', 'Чт.', 'Пт.', 'Сб.'],
        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        weekHeader: 'Sem.',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['ru']);
});