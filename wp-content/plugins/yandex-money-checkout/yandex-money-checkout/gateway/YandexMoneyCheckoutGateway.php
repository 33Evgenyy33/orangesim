<?php

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\Payment;
use YandexCheckout\Model\PaymentMethodType;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Request\Payments\CreatePaymentRequest;
use YandexCheckout\Request\Payments\CreatePaymentRequestSerializer;

class YandexMoneyCheckoutGateway extends WC_Payment_Gateway
{
    public $paymentMethod;

    public $confirmationType = ConfirmationType::REDIRECT;

    public $defaultDescription = '';

    public $defaultTitle = '';

    /**
     * @var Client
     */
    private $apiClient;

    public function __construct()
    {
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
        $this->title          = $this->settings['title'];
        $this->description    = $this->settings['description'];
        $this->msg['message'] = "message";
        $this->msg['class']   = "class";

        if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
            add_action(
                'woocommerce_update_options_payment_gateways_'.$this->id,
                array(
                    $this,
                    'process_admin_options',
                )
            );
        } else {
            add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));
        }
        add_action('woocommerce_receipt_'.$this->id, array($this, 'receipt_page'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled'     => array(
                'title'   => __('Включить/Выключить', 'yandexcheckout'),
                'type'    => 'checkbox',
                'label'   => $this->method_description,
                'default' => 'no',
            ),
            'title'       => array(
                'title'       => __('Заголовок', 'yandexcheckout'),
                'type'        => 'text',
                'description' => __('Название, которое пользователь видит во время оплаты', 'yandexcheckout'),
                'default'     => $this->defaultTitle,
            ),
            'description' => array(
                'title'       => __('Описание', 'yandexcheckout'),
                'type'        => 'textarea',
                'description' => __('Описание, которое пользователь видит во время оплаты', 'yandexcheckout'),
                'default'     => $this->defaultDescription,
            ),
        );
    }

    public function admin_options()
    {
        echo '<h5>'.__(
                'Для работы с модулем необходимо <a href="https://money.yandex.ru/joinups/">подключить магазин к Яндек.Кассе</a>. После подключения вы получите параметры для приема платежей (идентификатор магазина — shopId  и секретный ключ).',
                'yandexcheckout'
            ).'</h5>';
        echo '<table class="form-table">';
        $this->generate_settings_html();
        echo '</table>';
    }

    /**
     *  There are no payment fields, but we want to show the description if set.
     */
    public function payment_fields()
    {
        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }
    }

    /**
     * Receipt Page
     * @param int $order_id
     * @throws Exception
     */
    public function receipt_page($order_id)
    {
        YandexMoneyLogger::info(
            'Receipt page init.'
        );
	    global $woocommerce;
        $apiClient = $this->getApiClient();
        $order     = new WC_Order($order_id);
        $paymentId = $order->get_transaction_id();
        YandexMoneyLogger::info(
            sprintf(__( 'Пользователь вернулся с формы оплаты. Id заказа - %1$s. Идентификатор платежа - %2$s.', 'yandexcheckout'), $order_id, $paymentId)
        );
        try {
            $payment = $apiClient->getPaymentInfo($paymentId);
            if (in_array($payment->getStatus(), array(PaymentStatus::SUCCEEDED, PaymentStatus::WAITING_FOR_CAPTURE))
                || ($payment->getStatus() === PaymentStatus::PENDING && $payment->getPaid())
            ) {
                $woocommerce->cart->empty_cart();
                wp_redirect($this->get_success_fail_url('ym_api_success', $order));
            } else {
                wp_redirect($this->get_success_fail_url('ym_api_fail', $order));
            }
        } catch (ApiException $e) {
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
        }
    }

    /**
     * @param WC_Order $order
     *
     * @return mixed|WP_Error
     * @throws Exception
     */
    public function createPayment($order)
    {
        $builder        = $this->getBuilder($order);
        $paymentRequest = $builder->build();
        if (YandexMoneyCheckoutHandler::isReceiptEnabled()) {
            $receipt = $paymentRequest->getReceipt();
            if ($receipt instanceof \YandexCheckout\Model\Receipt) {
                $receipt->normalize($paymentRequest->getAmount());
            }
        }
        $serializer     = new CreatePaymentRequestSerializer();
        $serializedData = $serializer->serialize($paymentRequest);
        YandexMoneyLogger::info('Create payment request: '.json_encode($serializedData));
        try {
            $response = $this->getApiClient()->createPayment($paymentRequest);
            return $response;
        } catch (ApiException $e) {
            YandexMoneyLogger::error('Api error: '.$e->getMessage());

            return new WP_Error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Process the payment and return the result
     *
     * @param $order_id
     *
     * @return array
     * @throws WC_Data_Exception
     * @throws Exception
     */
    public function process_payment($order_id)
    {
        global $woocommerce;
        $order  = new WC_Order($order_id);
        $result = $this->createPayment($order);
        if ($result) {
            if (is_wp_error($result)) {
                wc_add_notice(__('Платеж не прошел. Попробуйте еще или выберите другой способ оплаты', 'yandexcheckout'), 'error');

                return array('result' => 'fail', 'redirect' => $order->get_view_order_url());
            } else {
                if ($result->status == PaymentStatus::PENDING) {
                    $order->set_transaction_id($result->id);
                    $order->update_status('wc-pending');
                    if (get_option('ym_force_clear_cart') == 'on') {
                        $woocommerce->cart->empty_cart();
                    }
                    if ($result->confirmation->type == ConfirmationType::EXTERNAL) {
                        return array('result' => 'success', 'redirect' => $order->get_checkout_order_received_url());
                    } elseif ($result->confirmation->type == ConfirmationType::REDIRECT) {
                        return array('result' => 'success', 'redirect' => $result->confirmation->confirmationUrl);
                    }
                } elseif ($result->status == PaymentStatus::WAITING_FOR_CAPTURE) {
                    return array('result' => 'success', 'redirect' => $order->get_checkout_order_received_url());
                } elseif ($result->status == PaymentStatus::SUCCEEDED) {
                    return array(
                        'result'   => 'success',
                        'redirect' => $this->get_success_fail_url('ym_api_success', $order),
                    );
                } else {
                    YandexMoneyLogger::warning(sprintf(__('Неудалось создать платеж. Для заказа %1$s', 'yandexcheckout'), $order_id));
                    wc_add_notice(__('Платеж не прошел. Попробуйте еще или выберите другой способ оплаты', 'yandexcheckout'), 'error');
                    $order->update_status('wc-cancelled');

                    return array('result' => 'fail', 'redirect' => '');
                }
            }
        } else {
            YandexMoneyLogger::warning(sprintf(__('Неудалось создать платеж. Для заказа %1$s', 'yandexcheckout'), $order_id));
            wc_add_notice(__('Платеж не прошел. Попробуйте еще или выберите другой способ оплаты', 'yandexcheckout'), 'error');

            return array('result' => 'fail', 'redirect' => '');
        }
    }

    public function showMessage($content)
    {
        return '<div class="box '.$this->msg['class'].'-box">'.$this->msg['message'].'</div>'.$content;
    }

    // get all pages
    public function get_pages($title = false, $indent = true)
    {
        $wp_pages  = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title) {
            $page_list[] = $title;
        }
        foreach ($wp_pages as $page) {
            $prefix = '';
            // show indented child pages?
            if ($indent) {
                $has_parent = $page->post_parent;
                while ($has_parent) {
                    $prefix     .= ' - ';
                    $next_page  = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            // add to page list array array
            $page_list[$page->ID] = $prefix.$page->post_title;
        }

        return $page_list;
    }

    /**
     * @param $name
     * @param WC_Order $order
     * @return string
     */
    protected function get_success_fail_url($name, $order)
    {
        switch (get_option($name)) {
            case "wc_success":
                return $order->get_checkout_order_received_url();
                break;
            case "wc_checkout":
                return $order->get_view_order_url();
                break;
            case "wc_payment":
                return $order->get_checkout_payment_url();
                break;
            default:
                return get_page_link(get_option($name));
                break;
        }
    }

    /**
     * @return Client
     */
    private function getApiClient()
    {
        if (!$this->apiClient) {
            $shopId          = get_option('ym_api_shop_id');
            $shopPassword    = get_option('ym_api_shop_password');
            $this->apiClient = new Client();
            $this->apiClient->setAuth($shopId, $shopPassword);
            $this->apiClient->setLogger(new YandexMoneyLogger());
        }

        return $this->apiClient;
    }
    /**
     * @param WC_Order $order
     *
     * @return \YandexCheckout\Request\Payments\CreatePaymentRequestBuilder
     */
    protected function getBuilder($order)
    {
        $enableHold = get_option('ym_api_enable_hold')
            && in_array($this->paymentMethod, array('', PaymentMethodType::BANK_CARD));

        $builder = CreatePaymentRequest::builder()
                                       ->setAmount(YandexMoneyCheckoutOrderHelper::getTotal($order))
                                       ->setPaymentMethodData($this->paymentMethod)
                                       ->setCapture(!$enableHold)
                                       ->setDescription($this->createDescription($order))
                                       ->setConfirmation(
                                           array(
                                               'type'      => $this->confirmationType,
                                               'returnUrl' => $order->get_checkout_payment_url(true),
                                           )
                                       )
                                       ->setMetadata(array(
                                           'cms_name'       => 'ya_api_woocommerce',
                                           'module_version' => YAMONEY_API_VERSION,
                                       ));
        YandexMoneyLogger::info('Return url: '.$order->get_checkout_payment_url(true));
        YandexMoneyCheckoutHandler::setReceiptIfNeeded($builder, $order);

        return $builder;
    }

    /**
     * @param WC_Order $order
     * @return string
     */
    private function createDescription($order)
    {
        $descriptionTemplate = get_option('ym_api_description_template', __('Оплата заказа №%order_number%', 'yandexcheckout'));

        $replace  = array();
        $patterns = explode('%', $descriptionTemplate);
        foreach ($patterns as $pattern) {
            $value  = null;
            $method = 'get_'.$pattern;
            if (method_exists($order, $method)) {
                $value = $order->{$method}();
            }
            if (!is_null($value) && is_scalar($value)) {
                $replace['%'.$pattern.'%'] = $value;
            }
        }
        $description = strtr($descriptionTemplate, $replace);

        return (string)mb_substr($description, 0, Payment::MAX_LENGTH_DESCRIPTION);
    }

}