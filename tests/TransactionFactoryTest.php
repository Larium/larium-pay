<?php

namespace Larium\Pay;

class TransactionFactoryTest extends TestCase
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
}
