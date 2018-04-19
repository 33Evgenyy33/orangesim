/*!
 * clipboard.js v1.7.1
 * https://zenorocha.github.io/clipboard.js
 *
 * Licensed MIT © Zeno Rocha
 */

var balanceBtnVal = '';
var typedPack = '';
var typedBalance = '';

(function($) {
    $.fn.fixMe = function() {
        return this.each(function() {
            var $this = $(this),
                $t_fixed;
            function init() {
                $this.wrap('<div class="container" />');
                $t_fixed = $this.clone();
                $t_fixed
                    .find("tbody")
                    .remove()
                    .end()
                    .addClass("fixed")
                    .insertBefore($this);
                resizeFixed();
            }
            function resizeFixed() {
                $t_fixed.find("th").each(function(index) {
                    $(this).css(
                        "width",
                        $this
                            .find("th")
                            .eq(index)
                            .outerWidth() + "px"
                    );
                });
            }
            function scrollFixed() {
                var offset = $(this).scrollTop(),
                    tableOffsetTop = $this.offset().top - 70,
                    tableOffsetBottom =
                        tableOffsetTop + $this.height() - $this.find("thead").height();
                if (offset < tableOffsetTop || offset > (tableOffsetBottom))
                    $t_fixed.hide();
                else if (
                    offset >= (tableOffsetTop) &&
                    offset <= tableOffsetBottom &&
                    $t_fixed.is(":hidden")
                )
                    $t_fixed.show();
            }
            $(window).resize(resizeFixed);
            $(window).scroll(scrollFixed);
            init();
        });
    };
})(jQuery);

jQuery(document).ready(function ($) {

    // $("div#rb-balance + p").slideUp();
    // $("div#rb-pack").slideUp();

    $('.table').stacktable();

    $('.tariff-comparison-btn').click(function(){

        if ($(document).width() <= 1024) return;
        setTimeout(
            function() {
                if (!$('.tariff-comparison-btn').hasClass("active") && $(".container")[0]){
                    $('.stacktable.large-only.fixed').remove();
                    $('.stacktable.large-only').unwrap();
                } else {
                    $(".stacktable.large-only").fixMe();
                    let topPos = $("header").height() + $("#wpadminbar").height();
                    $(".stacktable.large-only.fixed").css({"top": topPos});
                }
                $us.scroll.resize();
            },
            300);
    });

    let orangeNumber = $("#orange_number");
    orangeNumber.inputmask({
        mask:"699999999",
        "oncomplete": function(e){
            console.log('oncomplete');
            $('#orange_number_field').removeClass('orange-number-invalid');
            $('#orange_number_field').addClass('orange-number-valid');
            e.preventDefault();
            balanceAjax();
        }
    });

    $("#replenish_balance").click(function(){
        let orangeNumberLength = orangeNumber.val();
        orangeNumberLength = orangeNumberLength.replace("_","");
        if (orangeNumberLength.length < 9) {
            $('#orange_number_field').removeClass('orange-number-valid');
            $('#orange_number_field').addClass('orange-number-invalid');
        }
    });

    $('.products-container .product-variation').click(function () {
        if ($(this).hasClass('active')) return;
        $(this).parent().find(".product-variation").removeClass("active");
        $(this).addClass("active");
    });

    function balanceAjax(){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data:  {
                action: 'woocommerce_check_orange_number',
                nonce_code : myajax.nonce,
                orange_number: orangeNumber.val(),
            },
            url: myajax.url,
            beforeSend: function(){
                // Handle the beforeSend event
                $('.loader.loader-border').addClass('is-active');
            },
            success: function (response) {
                // $('body').trigger('update_checkout');
                $('.loader.loader-border').removeClass('is-active');
                $('.form-group.products-container').slideDown();
                console.log(response);
                // $('html, body').animate({
                //     scrollTop: $("section#contact").offset().top
                // }, 3000);
            }
        });
    }

    function typeCostDesc(pack, balance) {
        if (balance === 0)
            balance = 0.05

        if (typeof(typedPack) === 'object') {
            typedPack.destroy();
            console.log('destroyed');
        }

        if (typeof(typedBalance) === 'object') {
            typedBalance.destroy();
            console.log('destroyed');
        }

        $('.include-package-typed').html("<span>" + pack + "</span>");
        // typedPack = new Typed('.include-package-typed', {
        //     strings: ["<span>" + pack + "</span>"],
        //     startDelay: 0,
        //     typeSpeed: 0,
        //     backSpeed: 0,
        //     fadeOut: true,
        //     loop: false,
        //     showCursor: false,
        // });

        $('.residual-balance-typed').html("<span>" + balance + "€</span>");
        // typedBalance = new Typed('.residual-balance-typed', {
        //     strings: ["<span>" + balance + "€</span>"],
        //     startDelay: 0,
        //     typeSpeed: 0,
        //     backSpeed: 0,
        //     fadeOut: true,
        //     loop: false,
        //     showCursor: false,
        // });
    }

    $('select#razmer-sim-karty option:first-child').text('Выберите размер сим-карты');
    $('select#balans option:first-child').text('Выберите баланс');
    $('select#paket-podklyuchennyj-za-schet-balansa option:first-child').text('Выберите пакет');

//Switcher function:
    $("#rb-balance .rb-tab").click(function () {

        var selectedBalanceIndex = $('select#balans')[0].selectedIndex;
        console.log("index: " + selectedBalanceIndex);

        balanceBtnVal = $(this).data("value");

        if ((selectedBalanceIndex === 1 || selectedBalanceIndex === 0) && balanceBtnVal !== "€5"){
            // $("div#rb-balance + p").slideDown();
            // $("div#rb-pack").slideDown();
        }

        $(".residual-balance-typed").html('-');
        $(".include-package-typed").html('-');

        if (selectedBalanceIndex === 1){
            $(".mb-tariff").hide();
            $(".residual-balance-title").text('Остаток на балансе');
            $(".include-package").show();
            $(".residual-balance-typed").html('-');

            // $("div#rb-balance + p").slideDown();
            // $("div#rb-pack").slideDown();
        }

        $(this).parent().find(".rb-tab").removeClass("rb-tab-active");
        $(this).addClass("rb-tab-active");

        $('#rb-pack').find(".rb-tab").removeClass("rb-tab-active");

        if (balanceBtnVal === "€5") {
            // $("div#rb-balance + p").slideUp();
            // $("div#rb-pack").slideUp();

            $(".include-package").hide();
            $(".residual-balance-title").text('Баланс на сим-карте');
            $(".mb-tariff").show();

            $('#rb-pack .rb-tab').css({"pointer-events": "auto", "opacity": "1"});
            $("#rb-pack .rb-tab .rb-spot").removeAttr("style");
            $("#rb-pack .rb-tab").addClass('balance_m');

            $("select#paket-podklyuchennyj-za-schet-balansa option:nth-child(" + 1 + ")").prop('selected', 'selected').trigger('change');
            $("select#balans option:nth-child(" + 2 + ")").prop('selected', 'selected').trigger('change');
            $("select#paket-podklyuchennyj-za-schet-balansa option:nth-child(" + 2 + ")").prop('selected', 'selected').trigger('change');

            $('.simcard-total-cost').text($('.single_variation_wrap span.price').text());

            if (typeof(typedBalance) === 'object') {
                typedBalance.destroy();
            }
            $('.residual-balance-typed').html("<span>€5</span>");
            // typedBalance = new Typed('.residual-balance-typed', {
            //     strings: ["<span class=\"balanse\">€5</span>"],
            //     startDelay: 0,
            //     typeSpeed: 0,
            //     backSpeed: 0,
            //     fadeOut: true,
            //     loop: false,
            //     showCursor: false,
            // });

        } else if (balanceBtnVal === "€10") {

            $("#rb-pack .rb-tab").removeClass('balance_m');

            $('#rb-pack .rb-tab').css({"pointer-events": "auto", "opacity": "1"});
            $("#rb-pack .rb-tab .rb-spot").removeAttr("style");
            $("#rb-pack .rb-tab:last-child").css({"pointer-events": "none", "opacity": "0.4"});

            $("select#paket-podklyuchennyj-za-schet-balansa option:nth-child(" + 1 + ")").prop('selected', 'selected').trigger('change');
            $('select#balans option[value="' + balanceBtnVal + '"]').prop('selected', 'selected').trigger('change');

            $('.simcard-total-cost').text('—');
        } else {
            $("#rb-pack .rb-tab").removeClass('balance_m');

            $('#rb-pack .rb-tab').css({"pointer-events": "auto", "opacity": "1"});
            $("#rb-pack .rb-tab .rb-spot").removeAttr("style");

            $("select#paket-podklyuchennyj-za-schet-balansa option:nth-child(" + 1 + ")").prop('selected', 'selected').trigger('change');
            $('select#balans option[value="' + balanceBtnVal + '"]').prop('selected', 'selected').trigger('change');

            $('.simcard-total-cost').text('—');
        }

        switch (balanceBtnVal) {
            case '€5':
                $('.start-balance-price').text('390.00₽');
                break;
            case '€10':
                $('.start-balance-price').text('780.00₽');
                break;
            case '€15':
                $('.start-balance-price').text('1170.00₽');
                break;
            case '€20':
                $('.start-balance-price').text('1560.00₽');
                break;
        }
    });

    $("#rb-format .rb-tab").click(function () {
        //Spot switcher:

        var formatBtnIndex = $(this).data("value");
        $('select#razmer-sim-karty option[value="' + formatBtnIndex + '"]').prop('selected', 'selected').trigger('change');
        $(this).parent().find(".rb-tab").removeClass("rb-tab-active");
        $(this).addClass("rb-tab-active");

    });

    $('body').on('click', '#rb-pack .rb-tab.balance_m', function () {
        $.fancybox.open('<div class="message"><div class="w-iconbox iconpos_top style_outlined color_custom no_text" style=" margin-bottom: 12px;"><div class="w-iconbox-icon" style="box-shadow: 0 0 0 2px #ffac00 inset;background-color: #ffac00;color: #ffac00;font-size: 26px;"><i class="fal fa-exclamation-square"></i></div></div><p style="margin: 0">Недостаточно баланса для подключения пакета. Выберите другой баланс сим-карты.</p><button data-fancybox-close="" class="fancybox-close-small" title="Close"></button></div>');
    });

    $('body').on('click', '#rb-pack .rb-tab:not(.balance_m)', function () {
        $("#rb-pack .rb-tab .rb-spot").removeAttr("style");
        var packBtnIndex = $(this).data("value");
        $('select#paket-podklyuchennyj-za-schet-balansa option[value="' + packBtnIndex + '"]').prop('selected', 'selected').trigger('change');
        $(this).parent().find(".rb-tab").removeClass("rb-tab-active");
        $(this).addClass("rb-tab-active");

        var totalPrice = $('.single_variation_wrap span.price').text();
        $('.simcard-total-cost').text(totalPrice);

        let packPrice = 0;
        let totalBalancePrice = 0;

        switch (balanceBtnVal) {
            case '€10':
                totalBalancePrice = 10;
                break;
            case '€15':
                totalBalancePrice = 15;
                break;
            case '€20':
                totalBalancePrice = 20;
                break;
        }

        var packeg = '';
        switch (packBtnIndex) {
            case '1ГБ (7€)':
                packPrice = 7;
                packeg = '2ГБ <span>(7€) - с учетом акции</span>';
                break;
            case '2ГБ (10€)':
                packPrice = 10;
                packeg = '4ГБ <span>(10€) - с учетом акции</span>'
                break;
            case '3ГБ (15€)':
                packPrice = 15;
                packeg = '6ГБ <span>(15€) - с учетом акции</span>'
                break;
        }

        typeCostDesc(packeg, totalBalancePrice - packPrice);
    });


    // var clipboard = new Clipboard('.copy-btn');
    // clipboard.on('success', function(e) {
    //     $(e.trigger).find("#myTooltip").css({"background":"#3eac00"});
    //     $(e.trigger).find("#myTooltip").html("Ссылка скопирована");
    // });
    //
    // $(".copy-btn").mouseover(function(){
    //     $(this).find("#myTooltip").css({"background":"#555"});
    //     $(this).find("#myTooltip").html("Копировать ссылку");
    // });

    // Read More
    $('.wrapper').removeClass('maxheight');

    $('.read-more-btn').click(function() {
        $(this).animate({
            top: '-10px'
        }, 150, 'easeInOutCubic');
        $(this).animate({
            top: '10px'
        }, 150, 'easeInOutCubic', function() {
            $(this).toggleClass('readless');
            $(this).parent().parent().children('.container').children('.wrapper').children('.background').toggle();
            $(this).parent().parent().children('.container').children('.wrapper').toggleClass('maxheight');

            if ($(this).hasClass('readless')) {
                $(this).text("Скрыть (-)");
                $(this).css('color', 'maroon');
            } else {
                $(this).text("Читать больше (+)");
                $(this).css('color', '#3F62E9');
            };
        });

    });

    // var pattern = Trianglify({
    //     width: window.innerWidth,
    //     height: $("section#home").height(),
    //     x_colors: 'YlOrBr',
    //     cell_size: 40,
    //     seed: 'ka6i1',
    // });
    // $( pattern.canvas() ).prependTo( "section#home" );

    // $('.read-more > .read-more__expand .btn').on('click', function() {
    //     $(this).parents('.read-more').toggleClass('collapsed');
    //     $(this).text($(this).text() == 'Read More' ? 'Read Less' : 'Read More');
    // });

    //  TESTIMONIALS CAROUSEL HOOK
    // $('#customers-testimonials').owlCarousel({
    //     loop: true,
    //     center: true,
    //     items: 3,
    //     margin: 0,
    //     autoplay: true,
    //     dots:true,
    //     autoplayTimeout: 8500,
    //     smartSpeed: 450,
    //     responsive: {
    //         0: {
    //             items: 1
    //         },
    //         768: {
    //             items: 2
    //         },
    //         1170: {
    //             items: 3
    //         }
    //     }
    // });

});

var myAcc = document.getElementsByClassName("my-accordion");
var i;

for (i = 0; i < myAcc.length; i++) {
    myAcc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight){
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}

