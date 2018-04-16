<?php

namespace Tests\YaMoney\Model\PaymentData;

use YaMoney\Helpers\Random;
use YaMoney\Model\PaymentData\PaymentDataAlfabank;
use YaMoney\Model\PaymentMethodType;

require_once __DIR__ . '/AbstractPaymentDataTest.php';

class PaymentDataAlfabankTest extends AbstractPaymentDataTest
{
    /**
     * @return PaymentDataAlfabank
     */
    protected function getTestInstance()
    {
        return new PaymentDataAlfabank();
    }

    /**
     * @return string
     */
    protected function getExpectedType()
    {
        return PaymentMethodType::ALFABANK;
    }

    /**
     * @dataProvider validLoginDataProvider
     * @param $value
     */
    public function testGetSetLogin($value)
    {
        $instance = $this->getTestInstance();

        self::assertNull($instance->getLogin());
        self::assertNull($instance->login);

        $instance->setLogin($value);
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);

        $instance = $this->getTestInstance();
        $instance->login = $value;
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);
    }

    /**
     * @dataProvider invalidLoginDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidLogin($value)
    {
        $instance = $this->getTestInstance();
        $instance->setLogin($value);
    }

    /**
     * @dataProvider invalidLoginDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidLogin($value)
    {
        $instance = $this->getTestInstance();
        $instance->login = $value;
    }

    public function validLoginDataProvider()
    {
        return array(
            array('123'),
            array(Random::str(256)),
            array(Random::str(1024)),
        );
    }

    public function invalidLoginDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(true),
            array(false),
            array(array()),
            array(new \stdClass()),
        );
    }
}