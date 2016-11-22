<?php

namespace Larium\Pay\Transaction;

use Larium\Pay\TestCase;

class SaleTransactionTest extends TestCase
{
    const AMOUNT = 1000;

    /**
     * @expectedException RuntimeException
     */
    public function testCommitTransaction()
    {
        $amount = self::AMOUNT;
        $card = $this->getCard();

        $txn = new PurchaseTransaction($amount, $card);

        $txn->setMerchatReference('ORDER-WER-TYD');
        $txn->commit();

        $txn->setDescription('Bogus payment');
    }

    public function testTransactionExtraOptions()
    {
        $amount = self::AMOUNT;
        $card = $this->getCard();
        $extra = [
            'first_option' => 'first_value',
            'second_option' => 'second_value',
        ];

        $txn = new PurchaseTransaction($amount, $card, $extra);

        $extraOptions = $txn->getExtraOptions();

        $this->assertInstanceOf('Larium\Pay\ParamsBag', $extraOptions);
        $this->assertEquals('first_value', $extraOptions->first_option);
    }
}
