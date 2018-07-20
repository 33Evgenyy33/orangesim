<?php

use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Model\Notification\NotificationSucceeded;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use YandexCheckout\Model\PaymentMethodType;
use YandexCheckout\Model\PaymentStatus;

/**
 * The payment-facing functionality of the plugin.
 */
class YandexMoneyCheckoutPayment
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    public function addGateways($methods)
    {
        global $woocommerce;
        $installmentsOn = !isset($woocommerce->cart)
            || !isset($woocommerce->cart->total)
            || $woocommerce->cart->total >= YandexMoneyCheckoutInstallments::MIN_AMOUNT;

        $shopPassword = get_option('ym_api_shop_password');
        $prefix       = substr($shopPassword, 0, 4);

        $testMode = $prefix == "test";
        if ($testMode) {
            $methods[] = 'YandexMoneyGatewayCard';
            $methods[] = 'YandexMoneyGatewayWallet';
        } else {
            if (get_option('ym_api_pay_mode') == '1') {
                $methods[] = 'YandexMoneyGatewayEPL';
                if ((get_option('ym_api_epl_installments') == '1') && $installmentsOn) {
                    $methods[] = 'YandexMoneyGatewayInstallments';
                }
            } else {
                $methods[] = 'YandexMoneyGatewayCard';
                $methods[] = 'YandexMoneyGatewayAlfabank';
                $methods[] = 'YandexMoneyGatewayQiwi';
                $methods[] = 'YandexMoneyGatewayCash';
                $methods[] = 'YandexMoneyGatewayWebmoney';
                $methods[] = 'YandexMoneyGatewaySberbank';
                $methods[] = 'YandexMoneyGatewayWallet';
                if ($installmentsOn) {
                    $methods[] = 'YandexMoneyGatewayInstallments';
                }
            }
        }

        return $methods;
    }

    public function loadGateways()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyCheckoutGateway.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayCard.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayAlfabank.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayQiwi.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayWebmoney.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayCash.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewaySberbank.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayWallet.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayEPL.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayInstallments.php';
    }

    public function processCallback()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == "POST" &&
            isset($_REQUEST['yandex_money'])
            && $_REQUEST['yandex_money'] == 'callback'
        ) {

            YandexMoneyLogger::info('Notification init');
            $body           = @file_get_contents('php://input');
            $callbackParams = json_decode($body, true);
            YandexMoneyLogger::info('Notification body: '.$body);

            if (!json_last_error()) {
                try {
                    $this->processNotification($callbackParams);
                } catch (Exception $e) {
                    YandexMoneyLogger::error("Error while process notification: ".$e->getMessage());
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                header("Status: 400 Bad Request");
            }
            exit();
        }
    }

    public function returnUrl()
    {
        global $wp;
        if (!empty($wp->query_vars['order-pay'])) {

            $this->order_pay($wp->query_vars['order-pay']);

        }
    }

    /**
     * @param $callbackParams
     * @throws ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     * @throws Exception
     */
    protected function processNotification($callbackParams)
    {
        try {
            $notificationModel = ($callbackParams['event'] === YandexCheckout\Model\NotificationEventType::PAYMENT_SUCCEEDED)
                ? new NotificationSucceeded($callbackParams)
                : new NotificationWaitingForCapture($callbackParams);

        } catch (\Exception $e) {
            YandexMoneyLogger::error('Invalid notification object - '.$e->getMessage());
            header("HTTP/1.1 400 Bad Request");
            header("Status: 400 Bad Request");
            exit();
        }

        $payment = $notificationModel->getObject();
        $order   = YandexMoneyCheckoutOrderHelper::getOrderIdByPayment($payment->getId());
        if (!$order) {
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            exit();
        }

        $payment = $this->getApiClient()->getPaymentInfo($payment->getId());

        if ($payment->getStatus() === PaymentStatus::SUCCEEDED) {
            YandexMoneyCheckoutHandler::completeOrder($order, $payment);
        } elseif ($payment->getStatus() === PaymentStatus::WAITING_FOR_CAPTURE) {
            if ($payment->getPaymentMethod()->getType() === PaymentMethodType::BANK_CARD) {
                YandexMoneyCheckoutHandler::holdOrder($order, $payment);
            } else {
                YandexMoneyCheckoutHandler::capturePayment($this->getApiClient(), $order, $payment);
            }
        } else {
            YandexMoneyLogger::error('Wrong payment status: '.$payment->getStatus());
            header("HTTP/1.1 402 Payment Required");
            header("Status: 402 Payment Required");
        }

        exit();
    }

    public function validStatuses()
    {
        return array('processing', 'completed', 'on-hold', 'pending');
    }

    /**
     * @param int $order_id
     * @throws Exception
     */
    public function changeOrderStatusToProcessing($order_id)
    {
        YandexMoneyLogger::info('Init changeOrderStatusToProcessing');
        if (!get_option('ym_api_enable_hold')) {
            return;
        }
        if (!$order_id) {
            return;
        }

        $order     = wc_get_order($order_id);
        $paymentId = $order->get_transaction_id();

        try {
            $payment = $this->getApiClient()->getPaymentInfo($paymentId);

            $payment = YandexMoneyCheckoutHandler::capturePayment($this->getApiClient(), $order, $payment);
            if ($payment->getStatus() === PaymentStatus::SUCCEEDED) {
                $order->payment_complete($payment->getId());
                $order->add_order_note(__('Вы подтвердили платёж в Яндекс.Кассе.'));
            } elseif ($payment->getStatus() === PaymentStatus::CANCELED) {
                YandexMoneyCheckoutHandler::cancelOrder($order, $payment);
                $order->add_order_note(__('Платёж не подтвердился. Попробуйте ещё раз.'));
            } else {
                $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_ON_HOLD);
                $order->add_order_note(__('Платёж не подтвердился. Попробуйте ещё раз.'));
            }
        } catch (ApiException $e) {
            $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_ON_HOLD);
            $order->add_order_note(__('Платёж не подтвердился. Попробуйте ещё раз.'));
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
        }
    }

    /**
     * @param int $order_id
     * @throws Exception
     */
    public function changeOrderStatusToCancelled($order_id)
    {
        YandexMoneyLogger::info('Init changeOrderStatusToCancelled');
        if (!get_option('ym_api_enable_hold')) {
            return;
        }
        if (!$order_id) {
            return;
        }

        $order     = wc_get_order($order_id);
        $paymentId = $order->get_transaction_id();

        try {
            $payment = $this->getApiClient()->cancelPayment($paymentId);
            if ($payment->getStatus() === PaymentStatus::CANCELED) {
                $order->add_order_note(__('Вы отменили платёж в Яндекс.Кассе. Деньги вернутся клиенту.'));
            } else {
                $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_ON_HOLD);
                $order->add_order_note(__('Платёж не отменился. Попробуйте ещё раз.'));
            }
        } catch (ApiException $e) {
            $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_ON_HOLD);
            $order->add_order_note(__('Платёж не отменился. Попробуйте ещё раз.'));
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
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
}
