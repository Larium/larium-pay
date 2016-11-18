<?php

namespace Larium\Pay;

class TransactionFactoryTest extends \PHPUnit_Framework_TestCase
{
    const AMOUNT = 2000;

    public function testPurchaseFactory()
    {
        $txn = TransactionFactory::purchase(self::AMOUNT, $this->getCard());

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\PurchaseTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
    }

    public function testAuthorizeFactory()
    {
        $txn = TransactionFactory::authorize(self::AMOUNT, $this->getCard());

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\AuthorizeTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
    }

    public function testCaptureFactory()
    {
        $txn = TransactionFactory::capture(self::AMOUNT, '123456789');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\CaptureTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
    }

    public function testRefundFactory()
    {
        $txn = TransactionFactory::refund(self::AMOUNT, '123456789');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\RefundTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
    }

    public function testVoidFactory()
    {
        $txn = TransactionFactory::void('123456789');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\VoidTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
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
