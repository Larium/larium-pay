<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Card;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\PurchaseTransaction;

class WorldpayTest extends TestCase
{
    public function testPurchaseMethod()
    {
        $worldpay = $this->createGateway();

        $amount = 1000;
        $txn = new PurchaseTransaction($amount, $this->getCard());
        $txn->setDescription('Test Worldpay order.');

        $response = $worldpay->execute($txn);

        print_r($response);
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
