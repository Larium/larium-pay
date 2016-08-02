<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Card;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\VoidTransaction;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
use Larium\Pay\Transaction\AuthorizeTransaction;

class EverypayTest extends TestCase
{
    const AMOUNT = 1000;

    public function testPurchaseMethod()
    {
        $everypay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new PurchaseTransaction($amount, $this->getCard());

        $response = $everypay->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    public function testAuthorizeMethod()
    {
        $everypay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new AuthorizeTransaction($amount, $this->getCard());

        $response = $everypay->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    /**
     * @depends testAuthorizeMethod
     */
    public function testCaptureMethod($txnId)
    {
        $everypay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new CaptureTransaction($amount, $txnId);

        $response = $everypay->execute($txn);

        $this->assertSuccess($response);
    }

    /**
     * @depends testPurchaseMethod
     */
    public function testRefundMethod($txnId)
    {
        $everypay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new RefundTransaction($amount, $txnId);

        $response = $everypay->execute($txn);

        $this->assertSuccess($response);
    }

    public function testVoidMethod()
    {
        $everypay = $this->createGateway();

        $amount = self::AMOUNT;
        $txn = new AuthorizeTransaction($amount, $this->getCard());
        $response = $everypay->execute($txn);
        $this->assertSuccess($response);

        $txn = new VoidTransaction($response->getTransactionId());

        $response = $everypay->execute($txn);

        $this->assertSuccess($response);
    }

    private function getCard()
    {
        return new Card([
            'name' => 'JOHN DOE',
            'number' => '4111111111111111',
            'month' => '01',
            'year' => date('Y') + 1,
            'cvv' => '123',
        ]);
    }

    private function createGateway()
    {
        $credentials = $this->getFixture('everypay');
        $options = [
            'secret_key' => $credentials['secret_key'],
            'sandbox' => true,
        ];

        return new Everypay($options);
    }
}
