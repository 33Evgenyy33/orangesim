(function ($) {
    'use strict';

    $("#save-settings").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var form = $("#ym-settings"),
            shopIdInput = form.find('#ym_api_shop_id'),
            shopPassInput = form.find('#ym_api_shop_password'),
            shopIdError = form.find('#shop_id_error'),
            shopPassError = form.find('#shop_pass_error'),
            shopIdValue = shopIdInput.val(),
            shopPassValue = shopPassInput.val(),
            isShopIdValid = (/^\d+$/gi).test(shopIdValue),
            isShopPassValid = (/^test_.*|live_.*$/gi).test(shopPassValue);
        if (isShopIdValid && isShopPassValid) {
            form.submit();
        } else {
            if (!isShopIdValid) {
                shopIdInput.addClass('has-error');
                shopIdError.show();
            } else {
                shopIdError.hide();
                shopIdInput.removeClass('has-error');
            }

            if (!isShopPassValid) {
                shopPassInput.addClass('has-error');
                shopPassError.show();
            } else {
                shopPassError.hide();
                shopPassInput.removeClass('has-error');
            }
        }

        return false;
    });

})(jQuery);