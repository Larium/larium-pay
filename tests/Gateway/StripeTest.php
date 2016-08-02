<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Card;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
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
        $credentials = $this->getFixture('stripe');
        $options = ['sk' => $credentials['sk']];

        return new Stripe($options);
    }
}
