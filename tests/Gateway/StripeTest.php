<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Card;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;

class StripeTest extends TestCase
{
    public function testPurchaseMethod()
    {
        $stripe = $this->createGateway(function ($response, $raw) {
            return $raw;
        });

        $txn = new PurchaseTransaction(1000, $this->getCard());
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
