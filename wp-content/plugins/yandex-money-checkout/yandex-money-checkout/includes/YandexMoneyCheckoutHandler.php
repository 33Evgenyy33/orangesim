<?php

use YandexCheckout\Client;
use YandexCheckout\Model\PaymentInterface;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequest;

/**
 * The payment-facing functionality of the plugin.
 */
class YandexMoneyCheckoutHandler
{
    /**
     * @return bool
     */
    public static function isReceiptEnabled()
    {
        $taxRatesRelations = get_option('ym_api_tax_rate');
        $defaultTaxRate    = get_option('ym_api_default_tax_rate');

        return get_option('ym_api_enable_receipt') && ($taxRatesRelations || $defaultTaxRate);
    }

    /**
     * @param \YandexCheckout\Request\Payments\CreatePaymentRequestBuilder|\YandexCheckout\Request\Payments\Payment\CreateCaptureRequestBuilder $builder
     * @param WC_Order $order
     */
    public static function setReceiptIfNeeded($builder, $order)
    {
        if (self::isReceiptEnabled()) {
            if ($order->get_billing_email()) {
                $builder->setReceiptEmail($order->get_billing_email());
            } elseif ($order->get_billing_phone()) {
                $builder->setReceiptPhone($order->get_billing_phone());
            }

            $items    = $order->get_items();
            $orderData = $order->get_data();
            $shipping = $orderData['shipping_lines'];
            /** @var WC_Order_Item_Product $item */
            foreach ($items as $item) {
                $taxes  = $item->get_taxes();
                $amount = $item->get_total() / $item->get_quantity() + $item->get_total_tax() / $item->get_quantity();
                $builder->addReceiptItem(
                    $item['name'],
                    $amount,
                    $item->get_quantity(),
                    self::getYmTaxRate($taxes)
                );
            }

            if (count($shipping)) {
                $shippingData = array_shift($shipping);
                $amount       = $shippingData['total'] + $shippingData['total_tax'];
                $taxes        = $shippingData->get_taxes();
                $builder->addReceiptShipping('Доставка', $amount, self::getYmTaxRate($taxes));
            }
        }
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function updateOrderStatus(WC_Order $order, PaymentInterface $payment)
    {
        switch ($payment->getStatus()) {
            case PaymentStatus::SUCCEEDED:
                self::completeOrder($order, $payment);
                break;
            case PaymentStatus::CANCELED:
                self::cancelOrder($order, $payment);
                break;
            case PaymentStatus::WAITING_FOR_CAPTURE:
                self::holdOrder($order, $payment);
                break;
            case PaymentStatus::PENDING:
                self::pendingOrder($order, $payment);
                break;
        }
        YandexMoneyCheckoutHandler::logOrderStatus($order->get_status());
    }

    /**
     * @param Client $apiClient
     * @param WC_Order $order
     * @param PaymentInterface $payment
     * @return PaymentInterface|\YandexCheckout\Request\Payments\Payment\CreateCaptureResponse
     * @throws Exception
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public static function capturePayment(Client $apiClient, WC_Order $order, PaymentInterface $payment)
    {
        $builder = CreateCaptureRequest::builder();
        $builder->setAmount(YandexMoneyCheckoutOrderHelper::getTotal($order));
        self::setReceiptIfNeeded($builder, $order);
        $captureRequest = $builder->build();

        $payment = $apiClient->capturePayment($captureRequest, $payment->getId());

        return $payment;
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function completeOrder(WC_Order $order, PaymentInterface $payment)
    {
        YandexMoneyLogger::info(
            sprintf(__('Успешный платеж. Id заказа - %1$s. Данные платежа - %2$s.', 'yandexcheckout'),
                $order->get_id(), json_encode($payment))
        );
        $order->payment_complete($payment->getId());
        $order->add_order_note(sprintf(
                __('Номер транзакции в Яндекс.Кассе: %1$s. Сумма: %2$s', 'yandexcheckout'
                ), $payment->getId(), $payment->getAmount()->getValue())
        );
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function cancelOrder(WC_Order $order, PaymentInterface $payment)
    {
        YandexMoneyLogger::warning(
            sprintf(__('Неуспешный платеж. Id заказа - %1$s. Данные платежа - %2$s.', 'yandexcheckout'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_CANCELLED);
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function pendingOrder(WC_Order $order, PaymentInterface $payment)
    {
        YandexMoneyLogger::warning(
            sprintf(__('Платеж в ожидании оплаты. Id заказа - %1$s. Данные платежа - %2$s.', 'yandexcheckout'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_PENDING);
    }

    /**
     * @param WC_Order $order
     * @param PaymentInterface $payment
     */
    public static function holdOrder(WC_Order $order, PaymentInterface $payment)
    {
        YandexMoneyLogger::warning(
            sprintf(__('Платеж ждет подтверждения. Id заказа - %1$s. Данные платежа - %2$s.', 'yandexcheckout'),
                $order->get_id(), json_encode($payment))
        );
        $order->update_status(YandexMoneyCheckoutOrderHelper::WC_STATUS_ON_HOLD);
        $order->add_order_note(sprintf(
                __('Поступил новый платёж. Он ожидает подтверждения до %1$s, после чего автоматически отменится', 'yandexcheckout'
                ), $payment->getExpiresAt()->format('d.m.Y H:i'))
        );
    }

    /**
     * @param string $status
     */
    public static function logOrderStatus($status)
    {
        YandexMoneyLogger::info(sprintf(__('Статус заказа. %1$s', 'yandexcheckout'), $status));
    }


    /**
     * @param $taxes
     *
     * @return int
     */
    private static function getYmTaxRate($taxes)
    {
        $taxRatesRelations = get_option('ym_api_tax_rate');
        $defaultTaxRate    = (int)get_option('ym_api_default_tax_rate');

        if ($taxRatesRelations) {
            $taxesSubtotal = $taxes['total'];
            if ($taxesSubtotal) {
                $wcTaxIds = array_keys($taxesSubtotal);
                $wcTaxId  = $wcTaxIds[0];
                if (isset($taxRatesRelations[$wcTaxId])) {
                    return (int)$taxRatesRelations[$wcTaxId];
                }
            }
        }

        return $defaultTaxRate;
    }

}
