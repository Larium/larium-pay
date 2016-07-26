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
    public function testPurchaseMethod()
    {
        $stripe = $this->createGateway();

        $txn = new PurchaseTransaction(1000, $this->getCard());
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

        print_r($response);
    }

    public function testAuthorizeMethod()
    {
        $stripe = $this->createGateway();
        $txn = new AuthorizeTransaction(1000, $this->getCard());
        $response = $stripe->execute($txn);

        print_r($response);
    }

    public function testCaptureMethod()
    {
        $stripe = $this->createGateway();

        $txnId = 'ch_18bXOXKH7mEy5bci9i39sqFU';
        $txn = new CaptureTransaction(1000, $txnId);
        $response = $stripe->execute($txn);

        print_r($response);
    }

    public function testRefundMethod()
    {
        $stripe = $this->createGateway();

        $txnId = 'ch_18ZSt6KH7mEy5bcimOcCzxs7';
        $txn = new RefundTransaction(500, $txnId);
        $response = $stripe->execute($txn);

        print_r($response);
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

    private function createGateway(callable $callback = null)
    {
        $credentials = $this->getFixture('stripe');
        $options = ['sk' => $credentials['sk']];

        return new Stripe($options, $callback);
    }
}
