<?php
/**
 * WooCommerce Order Status Manager
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Order Status Manager to newer
 * versions in the future. If you wish to customize WooCommerce Order Status Manager for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-order-status-manager/ for more information.
 *
 * @package     WC-Order-Status-Manager/Templates
 * @author      SkyVerge
 * @copyright   Copyright (c) 2015-2018, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Default customer order status email template.
 *
 * Note: the .td class used in table is from WooCommerce core (see email-styles.php).
 *
 * @type string $email_heading The email heading.
 * @type string $email_body_text The email body.
 * @type \WC_Order $order The order object.
 * @type bool $sent_to_admin Whether email is sent to admin.
 * @type bool $plain_text Whether email is plain text.
 * @type bool $show_download_links Whether to show download links.
 * @type bool $show_purchase_note Whether to show purchase note.
 * @type \WC_Email $email The email object.
 *
 * @since 1.0.0
 * @version 1.7.0
 */
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email );

//echo '<pre>' . print_r( $email, true ) . '</pre>'
//echo $email->dispatch_conditions[0];

//$email_template_status = str_replace("any_to_", "",$email->dispatch_conditions[0]);

?>

<?php if ( $email_body_text ) : ?>

<div style="background-color:transparent;">
    <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->
            <!--[if (mso)|(IE)]><td align="center" width="620" style=" width:620px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:10px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num12" style="min-width: 320px;max-width: 620px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:10px; padding-right: 0px; padding-left: 0px;">
                        <!--<![endif]-->
                        <div class="">
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 5px;"><![endif]-->
                            <div style="color:#000000;font-family:'Lato', Tahoma, Verdana, Segoe, sans-serif;/*line-height:120%;*/ padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 5px;">
                                <div style="font-size:16px;/*line-height:14px;*/font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;color:#000000;text-align:center;">
                                    <div id="body_text">
                                        <?php
                                        if ($email->id == 'wc_order_status_email_19277'){
                                            $order_first_name = $order->get_billing_first_name();
                                            echo '<div style="margin-bottom: 30px;font-size: 18px">';
                                            echo '<p style="margin: 0">Уважаемый(-ая) '.$order_first_name.',</p>';
                                            echo '<p style="margin:0;color: #f9870b;font-weight: bold">Ваш заказ был успешно оплачен.</p>';
                                            echo '<p style="margin: 0">Спасибо за покупку в нашем магазине!</p>';
                                            echo '</div>';
//                                            $order_shipping_method = $order->get_shipping_methods();
                                            if( $order->has_shipping_method('flat_rate:1') || $order->has_shipping_method('cse_shipping_method')) {
                                                echo '<div style="text-align:  left">';
                                                echo '<p style="margin:0;margin-bottom: 10px">В ближайшее время с Вами свяжется наш оператор для уточнения деталей заказа.</p>';
                                                echo '<p style="margin:0;margin-bottom: 20px">График работы OrangeSim: понедельник - пятница с 08:00 до 20:00, суббота с 12:00 до 18:00</p>';
                                                echo '</div>';
                                            }
                                            if ($order->has_shipping_method('local_pickup_plus')){
	                                            echo '<div style="text-align:  left">';
	                                            echo '<p style="margin:0;margin-bottom: 20px">Сим-карты уже в наличии в пунктах выдачи. Вы можете забрать карту согласно графику работы пункты выдачи. <strong>Выдача производится по номеру заказа.</strong></p>';
	                                            echo '</div>';
                                            }
                                        }
                                        ?>
                                        <?php echo $email_body_text; ?>
                                    </div>
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

<?php endif; ?>

<div style="background-color:transparent;">
    <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->
            <!--[if (mso)|(IE)]><td align="center" width="620" style=" width:620px; padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:10px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num12" style="min-width: 320px;max-width: 620px;display: table-cell;vertical-align: top;">
                <div style="background-color: transparent; width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:10px; padding-right: 0px; padding-left: 0px;">
                        <!--<![endif]-->
                        <div class="">
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 5px;"><![endif]-->
                            <div style="color:#000000;font-family:'Lato', Tahoma, Verdana, Segoe, sans-serif; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 5px;">
                                <div style="/*font-size:18px;*//*line-height:14px;*/font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;color:#000000;text-align:center;">
<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2><?php echo 'Заказ:' . ' № ' . $order->get_order_number(); ?></h2>


<table class="td" cellspacing="0" cellpadding="6" style="width: 100%;border-color: #5769ff;" border="1">
	<thead style="background-color: #5769ff;color: #fff">
		<tr>
			<th class="td" scope="col" style="text-align: left;"><?php esc_html_e( 'Сим-карта', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" scope="col" style="text-align: left;"><?php esc_html_e( 'Кол-во', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" scope="col" style="text-align: left;"><?php esc_html_e( 'Цена', 'woocommerce-order-status-manager' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php

		$email_order_items = array(
			'show_purchase_note'  => $show_purchase_note,
			'show_download_links' => $show_download_links,
			'show_sku'            => false,
		);

		echo SV_WC_Plugin_Compatibility::is_wc_version_gte_3_0() ? wc_get_email_order_items( $order, $email_order_items ) : $order->email_order_items_table( $email_order_items );

		?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++; ?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align: left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td class="td" style="text-align: left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email ); ?>

<div style="text-align: left;">
<h2>Данные клиента</h2>

<?php if ( $billing_email = SV_WC_Order_Compatibility::get_prop( $order, 'billing_first_name' ) ) : ?>
    <p style="margin-bottom: 0px;"><strong>Имя:</strong> <?php echo $billing_email; ?></p>
<?php endif; ?>
<?php if ( $billing_email = SV_WC_Order_Compatibility::get_prop( $order, 'billing_last_name' ) ) : ?>
    <p style="margin-top: 7px;margin-bottom: 0px;"><strong>Фамилия:</strong> <?php echo $billing_email; ?></p>
<?php endif; ?>
<?php if ( $billing_email = SV_WC_Order_Compatibility::get_prop( $order, 'billing_email' ) ) : ?>
	<p style="margin-top: 7px;margin-bottom: 0px;text-underline-position: under;"><strong>Email:</strong> <?php echo $billing_email; ?></p>
<?php endif; ?>
<?php if ( $billing_phone = SV_WC_Order_Compatibility::get_prop( $order, 'billing_phone' ) ) : ?>
	<p style="margin-top: 7px;"><strong>Тел:</strong> <?php echo $billing_phone; ?></p>
<?php endif; ?>
</div>

<?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
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