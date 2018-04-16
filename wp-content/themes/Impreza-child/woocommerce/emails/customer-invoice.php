<?php
/**
 * Customer invoice email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates/Emails
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php if ( $order->has_status( 'pending' ) ) : ?>

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
                                    <div style="font-size:18px;/*line-height:14px;*/font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;color:#000000;text-align:center;">
                                        <p style="margin: 0">
		                                    <?php
		                                    printf(
			                                    wp_kses(
			                                    /* translators: %1s item is the name of the site, %2s is a html link */
				                                    __( 'An order has been created for you on %1$s. %2$s', 'woocommerce' ),
				                                    array(
					                                    'a' => array(
						                                    'href' => array(),
					                                    ),
				                                    )
			                                    ),
			                                    esc_html( get_bloginfo( 'name', 'display' ) ),
			                                    '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'
		                                    );
		                                    ?>
                                        </p>
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
<?php

/**
 * Hook for the woocommerce_email_order_details.
 *
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for the woocommerce_email_order_meta.
 *
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for woocommerce_email_customer_details.
 *
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );

?>
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
