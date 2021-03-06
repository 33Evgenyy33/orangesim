<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
	return;
}

class WC_yamoney_Gateway extends WC_Payment_Gateway {
	protected $long_name;
	protected $payment_type;
	private $order;

	public function __construct() {
		$this->has_fields = false;
		$this->init_form_fields();
		$this->init_settings();
		$this->title          = $this->settings['title'];
		$this->description    = $this->settings['description'];
		$this->liveurl        = '';
		$this->msg['message'] = "";
		$this->msg['class']   = "";

		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				&$this,
				'process_admin_options'
			) );
		} else {
			add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
		}
		add_action( 'woocommerce_receipt_' . $this->id, array( &$this, 'receipt_page' ) );
	}

	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'   => __( 'Включить/Выключить', 'yandex_money' ),
				'type'    => 'checkbox',
				'label'   => $this->long_name,
				'default' => 'no'
			),
			'title'       => array(
				'title'       => __( 'Заголовок', 'yandex_money' ),
				'type'        => 'text',
				'description' => __( 'Название, которое пользователь видит во время оплаты', 'yandex_money' ),
				'default'     => $this->method_title
			),
			'description' => array(
				'title'       => __( 'Описание', 'yandex_money' ),
				'type'        => 'textarea',
				'description' => __( 'Описание, которое пользователь видит во время оплаты', 'yandex_money' ),
				'default'     => $this->long_name
			)
		);
	}

	public function admin_options() {
		echo '<h3>' . $this->long_name . '</h3>';
		echo '<h5>' . __( 'Для работы с модулем необходимо <a href="https://money.yandex.ru/joinups/">подключить магазин к Яндек.Кассе</a>. После подключения вы получите параметры для приема платежей (идентификатор магазина — shopId и номер витрины — scid).', 'yandex_money' ) . '</h5>';
		echo '<table class="form-table">';
		// Generate the HTML For the settings form.
		$this->generate_settings_html();
		echo '</table>';

	}

	/**
	 *  There are no payment fields for payu, but we want to show the description if set.
	 **/
	function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) );
		}
	}

	/**
	 * Receipt Page
	 **/
	function receipt_page( $order ) {
		echo $this->generate_payu_form( $order );
	}

	protected function get_success_fail_url( $name ) {
		switch ( get_option( $name ) ) {
			case "wc_success":
				return $this->order->get_checkout_order_received_url();
				break;
			case "wc_checkout":
				return $this->order->get_view_order_url();
				break;
			case "wc_payment":
				return $this->order->get_checkout_payment_url();
				break;
			default:
				return get_page_link( get_option( $name ) );
				break;
		}
	}

	protected function get_fail_url() {

	}

	/**
	 * Generate payu button link
	 **/
	public function generate_payu_form( $order_id ) {

		global $woocommerce;
		$this->order = new WC_Order( $order_id );
		$order       = $this->order;
		if ( version_compare( $woocommerce->version, "3.0", ">=" ) ) {
			$billing_first_name = $this->order->get_billing_first_name();
			$billing_last_name  = $this->order->get_billing_last_name();
			$billing_phone      = $this->order->get_billing_phone();
			$billing_email      = $this->order->get_billing_email();
			$order_total        = $this->order->get_total();
		} else {
			$billing_first_name = $this->order->billing_first_name;
			$billing_last_name  = $this->order->billing_last_name;
			$billing_phone      = $this->order->billing_phone;
			$billing_email      = $this->order->billing_email;
			$order_total        = number_format( $this->order->order_total, 2, '.', '' );
		}
		$txnid   = $order_id;
		$sendurl = get_option( 'ym_Demo' ) == '1' ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
		$result  = '';
		$result  .= '<form name=ShopForm method="POST" id="submit_' . $this->id . '_payment_form" action="' . $sendurl . '">';
		$result  .= '<input type="hidden" name="firstname" value="' . $billing_first_name . '">';
		$result  .= '<input type="hidden" name="lastname" value="' . $billing_last_name . '">';
		$result  .= '<input type="hidden" name="scid" value="' . get_option( 'ym_Scid' ) . '">';
		$result  .= '<input type="hidden" name="shopId" value="' . get_option( 'ym_ShopID' ) . '"> ';
		$result  .= '<input type="hidden" name="shopSuccessUrl" value="' . $this->get_success_fail_url( 'ym_success' ) . '"> ';
		$result  .= '<input type="hidden" name="shopFailUrl" value="' . $this->get_success_fail_url( 'ym_fail' ) . '"> ';
		$result  .= '<input type="hidden" name="CustomerNumber" value="' . $txnid . '" size="43">';
		if ( $billing_phone ) {
			$result .= '<input name="cps_phone" type="hidden" value="' . $billing_phone . '">';
		}
		if ( $billing_email ) {
			$result .= '<input name="cps_email" type="hidden" value="' . $billing_email . '">';
		}
		if ( $this->isReceiptEnabled() ) {
			$result .= '<input name="ym_merchant_receipt" type="hidden" value="' . $this->getReceiptJson( $order ) . '">';
		}
		$result .= '<input type="hidden" name="sum" value="' . $order_total . '">';
		$result .= '<input name="paymentType" value="' . $this->payment_type . '" type="hidden">';
		$result .= '<input name="cms_name" type="hidden" value="wp-woocommerce">';
		$result .= '<input type="submit" value="Оплатить">';
		$result .= '<script type="text/javascript">';
		$result .= 'jQuery(document).ready(function ($){ jQuery("#submit_' . $this->id . '_payment_form").submit(); });';
		$result .= '</script></form>';
		$woocommerce->cart->empty_cart();

		return $result;
	}

	/**
	 * Process the payment and return the result
	 **/
	function process_payment( $order_id ) {
		$order = new WC_Order( $order_id );

		return array( 'result' => 'success', 'redirect' => $order->get_checkout_payment_url( true ) );
	}

	function showMessage( $content ) {
		return '<div class="box ' . $this->msg['class'] . '-box">' . $this->msg['message'] . '</div>' . $content;
	}

	// get all pages
	function get_pages( $title = false, $indent = true ) {
		$wp_pages  = get_pages( 'sort_column=menu_order' );
		$page_list = array();
		if ( $title ) {
			$page_list[] = $title;
		}
		foreach ( $wp_pages as $page ) {
			$prefix = '';
			// show indented child pages?
			if ( $indent ) {
				$has_parent = $page->post_parent;
				while ( $has_parent ) {
					$prefix     .= ' - ';
					$next_page  = get_page( $has_parent );
					$has_parent = $next_page->post_parent;
				}
			}
			// add to page list array array
			$page_list[ $page->ID ] = $prefix . $page->post_title;
		}

		return $page_list;
	}

	/**
	 * @return bool
	 */
	private function isReceiptEnabled() {
		$taxRatesRelations = get_option( 'ym_tax_rate' );
		$defaultTaxRate    = get_option( 'ym_default_tax_rate' );

		return get_option( 'ym_enable_receipt' ) && ( $taxRatesRelations || $defaultTaxRate );
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return string
	 */
	private function getReceiptJson( $order ) {
		global $woocommerce;

		$receiptWrapper = new stdClass();

		if ( version_compare( $woocommerce->version, "3.0", ">=" ) ) {
			if ( $order->get_billing_email() ) {
				$receiptWrapper->customerContact = $order->get_billing_email();
			} else if ( $order->get_billing_phone() ) {
				$receiptWrapper->customerContact = $order->get_billing_phone();
			}
		} else {
			if ( $order->billing_email ) {
				$receiptWrapper->customerContact = $order->billing_email;
			} else if ( $order->billing_phone ) {
				$receiptWrapper->customerContact = $order->billing_phone;
			}
		}

		$receiptWrapper->items = array();

		if ( version_compare( $woocommerce->version, "3.0", ">=" ) ) {

			$data      = $order->get_data();
			$currency  = $data['currency'];
			$shipping  = $data['shipping_lines'];
			$euro_rate = 69; // Курс евро
			$usd_rate  = 60;

			foreach ( $order->get_items() as $item ) {

				$initial_price   = $item->get_subtotal();
				$discount_price  = $item->get_total();
				$taxes           = $item->get_taxes();
				$quantity        = $item->get_quantity();
				$tax             = $item->get_total_tax();
				$sim_card        = $item->get_product_id();
				$balance_name    = $item->get_meta( 'balans' );
				$balance_in_euro = 0;
				$balance_in_rub  = 0;

				if ( $sim_card == 1346 ) { //Orange

					//$is_old_euro_rate = is_int(($initial_price/$quantity)/$euro_rate);


					//======================================================
					// Позиция "Сим-карта"
					//======================================================
					$itemWrapper                  = new stdClass();
					$itemWrapper->quantity        = $quantity;
					$itemWrapper->price           = new stdClass();
					$itemWrapper->price->amount   = 0; //Стоимость
					$itemWrapper->price->currency = $currency; //Валюта
					$itemWrapper->text            = 'Сим-карта'; //Название
					$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
					$receiptWrapper->items[]      = $itemWrapper;

					$discount_one_pos  = 0;
					$is_discount_price = false;
					$discount          = $initial_price - $discount_price;

					if ( $discount != 0 ) {
						$is_discount_price = true;
						$discount_one_pos  = $discount / $quantity;
					}

					if ($balance_name == '€5'){

						$itemTotal = 690 * $quantity;
						$amount = $itemTotal / $quantity + $tax / $quantity;
						$itemWrapper                  = new stdClass();
						$itemWrapper->quantity        = $quantity;
						$itemWrapper->price           = new stdClass();
						$itemWrapper->price->amount   = round( $amount, 2 ); //Стоимость
						$itemWrapper->price->currency = $currency; //Валюта
						$itemWrapper->text            = 'Тариф Orange, регистрация в сети Оператором'; //Название
						$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
						$receiptWrapper->items[]      = $itemWrapper;

						$itemTotal = 345 * $quantity;
						$amount = $itemTotal / $quantity + $tax / $quantity;

						$itemWrapper                  = new stdClass();
						$itemWrapper->quantity        = $quantity;
						$itemWrapper->price           = new stdClass();
						$itemWrapper->price->amount   = round( $amount, 2 ); //Стоимость
						$itemWrapper->price->currency = $currency; //Валюта
						$itemWrapper->text            = 'Баланс сим-карты'; //Название
						$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
						$receiptWrapper->items[]      = $itemWrapper;

						continue;
					}

					//======================================================
					// Позиция "Тариф"
					//======================================================
					$tariff_name   = '';
					$tariff_in_rub = 0;
					switch ( $balance_name ) {
						case '€5' :
							$balance_in_euro = 10;
							$balance_in_rub  = 10 * $euro_rate;
							$tariff_name     = '20 EUR';
							$tariff_in_rub   = 20 * $euro_rate;
							break;
						case '€10' :
							$balance_in_euro = 10;
							$balance_in_rub  = 10 * $euro_rate;
							$tariff_name     = '20 EUR';
							$tariff_in_rub   = 20 * $euro_rate;
							break;
						case '€15' :
							$balance_in_euro = 15;
							$balance_in_rub  = 15 * $euro_rate;
							$tariff_name     = '20 EUR';
							$tariff_in_rub   = 20 * $euro_rate;
							break;
						case '€20' :
							$balance_in_euro = 20;
							$balance_in_rub  = 20 * $euro_rate;
							$tariff_name     = '20 EUR';
							$tariff_in_rub   = 20 * $euro_rate;
							break;
					}
					$itemTotal = 690 * $quantity;

					$amount = 0;
					if ( $is_discount_price && ( $itemTotal / $quantity - $discount_one_pos > 0 ) ) {
						$amount            = ( $itemTotal / $quantity + $tax / $quantity ) - $discount_one_pos;
						$is_discount_price = false;
					} else {
						$amount = $itemTotal / $quantity + $tax / $quantity;
					}

					$itemWrapper                  = new stdClass();
					$itemWrapper->quantity        = $quantity;
					$itemWrapper->price           = new stdClass();
					$itemWrapper->price->amount   = round( $amount, 2 ); //Стоимость
					$itemWrapper->price->currency = $currency; //Валюта
					$itemWrapper->text            = 'Тариф Orange, регистрация в сети, активация пакета Mundo Оператором'; //Название
					$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
					$receiptWrapper->items[]      = $itemWrapper;

					//======================================================
					// Позиция "Интернет-пакет"
					//======================================================
					$package_name = $item->get_meta( 'paket-podklyuchennyj-za-schet-balansa' );

					$package_in_euro = 0;
					$package_in_rub  = 0;
					switch ( $package_name ) {
						case '1ГБ (7€)' :
							$package_in_rub  = 7 * $euro_rate;
							$package_in_euro = 7;
							$package_name    = '1 ГБ';
							break;
						case '2ГБ (10€)' :
							$package_in_rub  = 10 * $euro_rate;
							$package_in_euro = 10;
							$package_name    = '2 ГБ';
							break;
						case '3ГБ (15€)' :
							$package_in_rub  = 15 * $euro_rate;
							$package_in_euro = 15;
							$package_name    = '3 ГБ';
							break;
					}
					$itemTotal = $package_in_rub * $quantity;

					if ( $is_discount_price && ( $itemTotal / $quantity - $discount_one_pos > 0 ) ) {
						$amount            = ( $itemTotal / $quantity + $tax / $quantity ) - $discount_one_pos;
						$is_discount_price = false;
					} else {
						$amount = $itemTotal / $quantity + $tax / $quantity;
					}

					$itemWrapper                  = new stdClass();
					$itemWrapper->quantity        = $quantity;
					$itemWrapper->price           = new stdClass();
					$itemWrapper->price->amount   = round( $amount, 2 ); //Стоимость
					$itemWrapper->price->currency = $currency; //Валюта
					$itemWrapper->text            = 'Стоимость пакета Mundo ' . $package_name; //Название
					$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
					$receiptWrapper->items[]      = $itemWrapper;

					//======================================================
					// Позиция "Баланс"
					//======================================================

					$balance_with_pack_in_euro = $balance_in_euro - $package_in_euro;
					$balance_with_pack_in_rub  = $balance_in_rub - $package_in_rub;

					$itemTotal = $balance_with_pack_in_rub * $quantity;

					if ( $is_discount_price && ( $itemTotal / $quantity - $discount_one_pos > 0 ) ) {
						$amount = ( $itemTotal / $quantity + $tax / $quantity ) - $discount_one_pos;
					} else {
						$amount = $itemTotal / $quantity + $tax / $quantity;
					}

					$itemWrapper                  = new stdClass();
					$itemWrapper->quantity        = $quantity;
					$itemWrapper->price           = new stdClass();
					$itemWrapper->price->amount   = round( $amount, 2 ); //Стоимость
					$itemWrapper->price->currency = $currency; //Валюта
					$itemWrapper->text            = 'Баланс сим-карты'; //Название
					$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
					$receiptWrapper->items[]      = $itemWrapper;

				} else {
					$itemTotal                    = $item->get_total();
					$amount                       = $itemTotal / $quantity + $tax / $quantity;
					$itemWrapper                  = new stdClass();
					$itemWrapper->quantity        = $quantity;
					$itemWrapper->price           = new stdClass();
					$itemWrapper->price->amount   = round( $amount, 2 );
					$itemWrapper->price->currency = $currency;
					$itemWrapper->text            = $item['name']; //str_replace( '€', '', $item['name'] ).' EUR';
					$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
					$receiptWrapper->items[]      = $itemWrapper;
				}
			}

			$shippingData = array_shift( $shipping );
			$amount       = $shippingData['total'] + $shippingData['total_tax'];

			if ( count( $shippingData ) && ( $amount != 0 ) ) {
				$taxes                        = $shippingData->get_taxes();
				$itemWrapper                  = new stdClass();
				$itemWrapper->quantity        = 1;
				$itemWrapper->price           = new stdClass();
				$itemWrapper->price->amount   = round( $amount, 2 );
				$itemWrapper->price->currency = $currency;
				$itemWrapper->text            = 'Доставка';
				$itemWrapper->tax             = $this->getYmTaxRate( $taxes );
				$receiptWrapper->items[]      = $itemWrapper;
			}

			$order_by = get_post_meta( $order->get_id(), '_created_via', true );
			//file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/yandex-order_by-$order_by-" . $order->get_id() . ".txt", print_r( $receiptWrapper, true ), FILE_APPEND | LOCK_EX );
			file_put_contents( $_SERVER['DOCUMENT_ROOT'] . "/logs/yandex-order_by-$order_by-" . $order->get_id() . ".txt", print_r( $order->get_items(), true ), FILE_APPEND | LOCK_EX );


		} else {
			echo '';
		}

		$result = htmlspecialchars( json_encode( $receiptWrapper, JSON_UNESCAPED_UNICODE ) );

		return $result;
	}

	/**
	 * @param $taxes
	 *
	 * @return int
	 */
	private function getYmTaxRate( $taxes ) {
		$taxRatesRelations = get_option( 'ym_tax_rate' );
		$defaultTaxRate    = (int) get_option( 'ym_default_tax_rate' );

		if ( $taxRatesRelations ) {
			$taxesSubtotal = $taxes['total'];
			if ( $taxesSubtotal ) {
				$wcTaxIds = array_keys( $taxesSubtotal );
				$wcTaxId  = $wcTaxIds[0];
				if ( isset( $taxRatesRelations[ $wcTaxId ] ) ) {
					return (int) $taxRatesRelations[ $wcTaxId ];
				}
			}
		}

		return $defaultTaxRate;
	}
}

class WC_yamoney_mpos_Gateway extends WC_yamoney_Gateway {
	public function __construct() {
		parent::__construct();
	}

	public function generate_payu_form( $order_id ) {
		global $woocommerce;
		$order  = new WC_Order( $order_id );
		$txnid  = $order_id;
		$result = '';
		$result .= '<form name=ShopForm method="POST" id="submit_' . $this->id . '_payment_form" action="' . get_page_link( get_option( 'ym_page_mpos' ) ) . '">';
		$result .= '<input type="hidden" name="CustomerNumber" value="' . $txnid . '" size="43">';
		$result .= '<input type="hidden" name="Sum" value="' . number_format( $order->order_total, 2, '.', '' ) . '" size="43">';
		$result .= '<input name="paymentType" value="' . $this->payment_type . '" type="hidden">';
		$result .= '<input name="cms_name" type="hidden" value="wp-woocommerce">';
		$result .= '<input type="submit" value="Перейти к инcтрукции по оплате">';
		$result .= '</form>';
		$woocommerce->cart->empty_cart();

		return $result;
	}
}

class WC_yamoney_smartpay_Gateway extends WC_yamoney_Gateway {
	public function __construct() {
		parent::__construct();
	}

	public function admin_options() {
		echo '<h3>' . $this->long_name . '</h3>';
		echo '<table class="form-table">';
		// Generate the HTML For the settings form.
		$this->generate_settings_html();
		echo '</table>';
	}
}

?>