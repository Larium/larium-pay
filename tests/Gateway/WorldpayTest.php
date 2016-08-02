<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Card;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
use Larium\Pay\Transaction\AuthorizeTransaction;

class WorldpayTest extends TestCase
{
    const AMOUNT = 1000;

    public function testPurchaseMethod()
    {
        $worldpay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new PurchaseTransaction($amount, $this->getCard());
        $txn->setDescription('Test Worldpay order.');

        $response = $worldpay->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    public function testAuthorizeMethod()
    {
        $worldpay = $this->createGateway();

        $amount = SELF::AMOUNT;
        $txn = new AuthorizeTransaction($amount, $this->getCard());
        $txn->setDescription('Test Worldpay order.');

        $response = $worldpay->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    /**
     * @depends testAuthorizeMethod
     */
    public function testCaptureMethod($txnId)
    {
        $worldpay = $this->createGateway();

        $txn = new CaptureTransaction(SELF::AMOUNT, $txnId);
        $response = $worldpay->execute($txn);

        $this->assertSuccess($response);
    }

    /**
     * @depends testPurchaseMethod
     */
    public function testRefundMethod($txnId)
    {
        $worldpay = $this->createGateway();

        $txn = new RefundTransaction(SELF::AMOUNT, $txnId);
        $response = $worldpay->execute($txn);

        $this->assertSuccess($response);
    }

    private function getCard()
    {
        return new Card([
            'name' => 'JOHN DOE',
            'number' => '4444333322221111',
            'month' => '01',
            'year' => date('Y') + 1,
            'cvv' => '123',
        ]);
    }

    private function createGateway()
    {
        $credentials = $this->getFixture('worldpay');
        $options = [
            'service_key' => $credentials['service_key'],
            'client_key' => $credentials['client_key'],
        ];

        return new Worldpay($options);
    }
}
