<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$custom_data = $GLOBALS['emails_custom_data'];
$order_id = $custom_data['order_id'];
$email = $custom_data['email'];
//$email_template_status = str_replace("any_to_", "",$email->dispatch_conditions[0]);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <!--[if gte mso 9]><xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <title></title>
    <!--[if !mso]><!-- -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <!--<![endif]-->

    <style type="text/css" id="media-query">
        body {
            margin: 0;
            padding: 0; }

        table, tr, td {
            vertical-align: top;
            border-collapse: collapse; }

        .ie-browser table, .mso-container table {
            table-layout: fixed; }

        * {
            line-height: inherit; }

        a[x-apple-data-detectors=true] {
            color: inherit !important;
            text-decoration: none !important; }

        [owa] .img-container div, [owa] .img-container button {
            display: block !important; }

        [owa] .fullwidth button {
            width: 100% !important; }

        [owa] .block-grid .col {
            display: table-cell;
            float: none !important;
            vertical-align: top; }

        .ie-browser .num12, .ie-browser .block-grid, [owa] .num12, [owa] .block-grid {
            width: 620px !important; }

        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
            line-height: 100%; }

        .ie-browser .mixed-two-up .num4, [owa] .mixed-two-up .num4 {
            width: 204px !important; }

        .ie-browser .mixed-two-up .num8, [owa] .mixed-two-up .num8 {
            width: 408px !important; }

        .ie-browser .block-grid.two-up .col, [owa] .block-grid.two-up .col {
            width: 310px !important; }

        .ie-browser .block-grid.three-up .col, [owa] .block-grid.three-up .col {
            width: 206px !important; }

        .ie-browser .block-grid.four-up .col, [owa] .block-grid.four-up .col {
            width: 155px !important; }

        .ie-browser .block-grid.five-up .col, [owa] .block-grid.five-up .col {
            width: 124px !important; }

        .ie-browser .block-grid.six-up .col, [owa] .block-grid.six-up .col {
            width: 103px !important; }

        .ie-browser .block-grid.seven-up .col, [owa] .block-grid.seven-up .col {
            width: 88px !important; }

        .ie-browser .block-grid.eight-up .col, [owa] .block-grid.eight-up .col {
            width: 77px !important; }

        .ie-browser .block-grid.nine-up .col, [owa] .block-grid.nine-up .col {
            width: 68px !important; }

        .ie-browser .block-grid.ten-up .col, [owa] .block-grid.ten-up .col {
            width: 62px !important; }

        .ie-browser .block-grid.eleven-up .col, [owa] .block-grid.eleven-up .col {
            width: 56px !important; }

        .ie-browser .block-grid.twelve-up .col, [owa] .block-grid.twelve-up .col {
            width: 51px !important; }

        @media only screen and (min-width: 640px) {
            .block-grid {
                width: 620px !important; }
            .block-grid .col {
                vertical-align: top; }
            .block-grid .col.num12 {
                width: 620px !important; }
            .block-grid.mixed-two-up .col.num4 {
                width: 204px !important; }
            .block-grid.mixed-two-up .col.num8 {
                width: 408px !important; }
            .block-grid.two-up .col {
                width: 310px !important; }
            .block-grid.three-up .col {
                width: 206px !important; }
            .block-grid.four-up .col {
                width: 155px !important; }
            .block-grid.five-up .col {
                width: 124px !important; }
            .block-grid.six-up .col {
                width: 103px !important; }
            .block-grid.seven-up .col {
                width: 88px !important; }
            .block-grid.eight-up .col {
                width: 77px !important; }
            .block-grid.nine-up .col {
                width: 68px !important; }
            .block-grid.ten-up .col {
                width: 62px !important; }
            .block-grid.eleven-up .col {
                width: 56px !important; }
            .block-grid.twelve-up .col {
                width: 51px !important; } }

        @media (max-width: 640px) {
            .block-grid, .col {
                min-width: 320px !important;
                max-width: 100% !important;
                display: block !important; }
            .block-grid {
                width: calc(100% - 40px) !important; }
            .col {
                width: 100% !important; }
            .col > div {
                margin: 0 auto; }
            img.fullwidth, img.fullwidthOnMobile {
                max-width: 100% !important; }
            .no-stack .col {
                min-width: 0 !important;
                display: table-cell !important; }
            .no-stack.two-up .col {
                width: 50% !important; }
            .no-stack.mixed-two-up .col.num4 {
                width: 33% !important; }
            .no-stack.mixed-two-up .col.num8 {
                width: 66% !important; }
            .no-stack.three-up .col.num4 {
                width: 33% !important; }
            .no-stack.four-up .col.num3 {
                width: 25% !important; }
            .mobile_hide {
                min-height: 0px;
                max-height: 0px;
                max-width: 0px;
                display: none;
                overflow: hidden;
                font-size: 0px; } }
    </style>
</head>

<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #FFFFFF">
<style type="text/css" id="media-query-bodytag">
    @media (max-width: 520px) {
        .block-grid {
            min-width: 320px!important;
            max-width: 100%!important;
            width: 100%!important;
            display: block!important;
        }

        .col {
            min-width: 320px!important;
            max-width: 100%!important;
            width: 100%!important;
            display: block!important;
        }

        .col > div {
            margin: 0 auto;
        }

        img.fullwidth {
            max-width: 100%!important;
        }
        img.fullwidthOnMobile {
            max-width: 100%!important;
        }
        .no-stack .col {
            min-width: 0!important;
            display: table-cell!important;
        }
        .no-stack.two-up .col {
            width: 50%!important;
        }
        .no-stack.mixed-two-up .col.num4 {
            width: 33%!important;
        }
        .no-stack.mixed-two-up .col.num8 {
            width: 66%!important;
        }
        .no-stack.three-up .col.num4 {
            width: 33%!important;
        }
        .no-stack.four-up .col.num3 {
            width: 25%!important;
        }
        .mobile_hide {
            min-height: 0px!important;
            max-height: 0px!important;
            max-width: 0px!important;
            display: none!important;
            overflow: hidden!important;
            font-size: 0px!important;
        }
    }
</style>
<!--[if IE]><div class="ie-browser"><![endif]-->
<!--[if mso]><div class="mso-container"><![endif]-->
<table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #FFFFFF;width: 100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #FFFFFF;"><![endif]-->

            <div style="background-color:transparent;">
                <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid two-up ">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->

                        <!--[if (mso)|(IE)]><td align="center" width="310" style=" width:310px; padding-right: 10px; padding-left: 10px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                        <div class="col num6" style="max-width: 320px;min-width: 310px;display: table-cell;vertical-align: top;">
                            <div style="background-color: transparent; width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!-->
                                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 10px; padding-left: 10px;">
                                    <!--<![endif]-->


                                    <div align="left" class="img-container left  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;">
                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="left"><![endif]-->
                                        <div style="line-height:15px;font-size:1px">&#160;</div> <img class="left  autowidth  fullwidth" align="left" border="0" src="https://orangesim.ru/wp-content/uploads/2018/03/1Montazhnaya-oblast-16.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 290px"
                                                                                                      width="290">
                                        <div style="line-height:15px;font-size:1px">&#160;</div>
                                        <!--[if mso]></td></tr></table><![endif]-->
                                    </div>


                                    <!--[if (!mso)&(!IE)]><!-->
                                </div>
                                <!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td><td align="center" width="310" style=" width:310px; padding-right: 10px; padding-left: 10px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                        <div class="col num6" style="max-width: 320px;min-width: 310px;display: table-cell;vertical-align: top;">
                            <div style="background-color: transparent; width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!-->
                                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 10px; padding-left: 10px;">
                                    <!--<![endif]-->


                                    <div class="">
                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top: 35px; padding-bottom: 35px;"><![endif]-->
                                        <div style="color:#f98200;font-family:Tahoma, Verdana, Segoe, sans-serif;line-height:120%; padding-right: 0px; padding-left: 0px; padding-top: 35px; padding-bottom: 35px;">
                                            <div style="font-size:12px;/*line-height:14px;*/font-family:Tahoma, Verdana, Segoe, sans-serif;color:#f98200;text-align:left;">
                                                <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: right"><span style="font-size: 26px; line-height: 31px;"><strong><span style="line-height: 31px; font-size: 26px;">Заказ</span>&#160;№ R<?= $order_id; ?></strong>
                                                            </span><br></p>
                                            </div>
                                        </div>
                                        <!--[if mso]></td></tr></table><![endif]-->
                                    </div>

                                    <!--[if (!mso)&(!IE)]><!-->
                                </div>
                                <!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>
            <div style="background-color:transparent;">
                <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->

                        <!--[if (mso)|(IE)]><td align="center" width="620" style=" width:620px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:0px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                        <div class="col num12" style="min-width: 320px;max-width: 620px;display: table-cell;vertical-align: top;">
                            <div style="background-color: transparent; width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!-->
                                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                    <!--<![endif]-->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                        <tbody>
                                        <tr style="vertical-align: top">
                                            <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 15px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                                <table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #222222;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                                    <tbody>
                                                    <tr style="vertical-align: top">
                                                        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                                            <span></span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--[if (!mso)&(!IE)]><!-->
                                </div>
                                <!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>

            <div style="background-color:transparent;">
                <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->

                        <!--[if (mso)|(IE)]><td align="center" width="620" style=" width:620px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
                        <div class="col num12" style="min-width: 320px;max-width: 620px;display: table-cell;vertical-align: top;">
                            <div style="background-color: transparent; width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!-->
                                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                    <!--<![endif]-->


                                    <div align="center" class="img-container center fixedwidth " style="padding-right: 0px;  padding-left: 0px;">
                                        <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px;line-height:0px;"><td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]-->

								        <?php
//                                        print_r($email->id);
                                        switch($email->id):
									        case "customer_invoice": // Счет клиенту ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/payment-pending.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="70">
										        <?php break; ?>
									        <?php case "wc_order_status_email_19277": // Оплачен Клиенту
									              case "wc_order_status_email_19278": // Оплачен Админу ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/payment-done.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="70">
                                                <?php break; ?>
									        <?php case "wc_order_status_email_19108": // Новый заказ Клиенту
									              case "wc_order_status_email_19107": // Новый заказ Админу ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/delivery-truck-70.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="70">
										        <?php break; ?>
									        <?php case "wc_order_status_email_19281": // Ожидание загранпаспорта Клиенту
									              case "wc_order_status_email_19282": // Ожидание загранпаспорта Админу ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/passport-70.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="70">
										        <?php break; ?>
									        <?php case "wc_order_status_email_19300": // Выполнен Orange Клиенту
	                                              case "wc_order_status_email_19301": // Выполнен Orange Админу ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/03/okok.gif" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 100px"
                                                     width="100">
										        <?php break; ?>
									        <?php case "wc_order_status_email_19394": // Заказ выдан ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/issue-70.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="124">
										        <?php break; ?>
                                            <?php case "wc_order_status_email_19273": // Заказ Аннулирован ?>
                                                <img class="center fixedwidth" align="center" border="0" src="https://orangesim.ru/wp-content/uploads/2018/04/delete.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 70px"
                                                     width="124">
	                                            <?php break; ?>
									        <?php endswitch; ?>



                                        <!--[if mso]></td></tr></table><![endif]-->
                                    </div>


                                    <!--[if (!mso)&(!IE)]><!-->
                                </div>
                                <!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>
