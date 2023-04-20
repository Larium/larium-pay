<?php

declare(strict_types=1);

namespace Larium\Pay;

class TransactionBuilderTest extends TestCase
{
    public const AMOUNT = 1000;

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
        $this->assertNotNull($txn->getCard());
        $this->assertNotNull($txn->getCurrency());
    }
}
