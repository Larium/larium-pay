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
    public function testPurchaseMethod()
    {
        $everypay = $this->createGateway();

        $amount = 1000;
        $txn = new PurchaseTransaction($amount, $this->getCard());

        $response = $everypay->execute($txn);

        print_r($response);
    }

    public function testAuthorizeMethod()
    {
        $everypay = $this->createGateway();

        $amount = 1000;
        $txn = new AuthorizeTransaction($amount, $this->getCard());

        $response = $everypay->execute($txn);

        print_r($response);
    }

    public function testCaptureMethod()
    {
        $everypay = $this->createGateway();

        $amount = 1000;
        $txnId = 'pmt_3FaqkPQHQhjUmAUumyncjc7P';
        $txn = new CaptureTransaction($amount, $txnId);

        $response = $everypay->execute($txn);

        print_r($response);
    }

    public function testRefundMethod()
    {
        $everypay = $this->createGateway();

        $amount = 1000;
        $txnId = 'pmt_zxWtEfA57saKzIDx5F3bPuo1';
        $txn = new RefundTransaction($amount, $txnId);

        $response = $everypay->execute($txn);

        print_r($response);
    }

    public function testVoidMethod()
    {
        $everypay = $this->createGateway();

        $txnId = 'pmt_yEZF0nqJEKmsEy07yTdXfhBN';
        $txn = new VoidTransaction($txnId);

        $response = $everypay->execute($txn);

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

    private function createGateway()
    {
        $credentials = $this->getFixture('everypay');
        $options = ['secret_key' => $credentials['secret_key']];

        return new Everypay($options);
    }
}
