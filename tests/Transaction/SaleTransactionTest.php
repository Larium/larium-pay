<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Transaction;

use Larium\Pay\Card;

class SaleTransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testCommitTransaction()
    {
        $amount = 1000;
        $card = new Card([
            'name' => 'JOHN DOE',
            'number' => '4111111111111111',
            'month' => '01',
            'year' => date('Y') + 1,
            'cvv' => '123',
        ]);

        $txn = new PurchaseTransaction($amount, $card);

        $txn->setMerchatReference('ORDER-WER-TYD');
        $txn->commit();

        $txn->setDescription('Bogus payment');
    }
}
