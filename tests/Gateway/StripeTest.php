<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\TestCase;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
use Larium\Pay\Transaction\RetrieveTransaction;
use Larium\Pay\Transaction\AuthorizeTransaction;

class StripeTest extends TestCase
{
    const AMOUNT = 1000;

    public function testPurchaseMethod()
    {
        $stripe = $this->createGateway();

        $txn = new PurchaseTransaction(self::AMOUNT, $this->getCard());
        $address = [
            'name' => 'John Doe',
            'city' => 'Athens',
            'country' => 'GR',
            'address1' => 'Ermou 14',
            'zip' => '12345',
            'state' => 'Attiki',
            'phone' => '+302112121211',
        ];
        $txn->setAddress($address);
        $response = $stripe->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    public function testAuthorizeMethod()
    {
        $stripe = $this->createGateway();
        $txn = new AuthorizeTransaction(self::AMOUNT, $this->getCard());
        $response = $stripe->execute($txn);

        $this->assertSuccess($response);

        return $response->getTransactionId();
    }

    /**
     * @depends testAuthorizeMethod
     */
    public function testCaptureMethod($txnId)
    {
        $stripe = $this->createGateway();

        $txn = new CaptureTransaction(self::AMOUNT, $txnId);
        $response = $stripe->execute($txn);

        $this->assertSuccess($response);
    }

    /**
     * @depends testPurchaseMethod
     */
    public function testRefundMethod($txnId)
    {
        $stripe = $this->createGateway();

        $txn = new RefundTransaction(self::AMOUNT, $txnId);
        $response = $stripe->execute($txn);

        $this->assertSuccess($response);
    }

    /**
     * @depends testPurchaseMethod
     */
    public function testRetrieveMethod($txnId)
    {
        $stripe = $this->createGateway();

        $txn = new RetrieveTransaction($txnId);
        $response = $stripe->execute($txn);

        $this->assertSuccess($response);
    }

    private function createGateway()
    {
        $credentials = $this->getFixture('stripe');
        $options = ['secret_key' => $credentials['secret_key']];

        return new Stripe($options);
    }
}
