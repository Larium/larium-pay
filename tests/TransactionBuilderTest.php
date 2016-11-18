<?php

namespace Larium\Pay;

class TransactionBuilderTest extends \PHPUnit_Framework_TestCase
{
    const AMOUNT = 1000;

    public function testPurchaseBuild()
    {
        $txn = TransactionBuilder::purchase(self::AMOUNT)
            ->withExtraOptions([
                'order_id' => '123456789',
            ])
            ->with('EUR')
            ->billTo([
                'address1' => 'Ermou 15',
                'city' => 'Athens',
                'country' => 'GR',
                'zip' => '13267'
            ])
            ->charge($this->getCard())
            ->describedAs('Test transaction')
            ->withClientIp('127.0.0.1')
            ->mailTo('andreas@larium.net')
            ->getTransaction();

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\PurchaseTransaction',
            $txn
        );

        $this->assertNotNull($txn->getAddress());
        $this->assertNotNull($txn->getDescription());
        $this->assertNotNull($txn->getClientIp());
        $this->assertNotNull($txn->getCustomerEmail());
        $this->assertNotNull($txn->getAmount());
        $this->assertNotNull($txn->getCardReference());
        $this->assertNotNull($txn->getCurrency());
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
}
