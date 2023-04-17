<?php

declare(strict_types=1);

namespace Larium\Pay;

class TransactionFactoryTest extends TestCase
{
    public const AMOUNT = 2000;

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

    public function testCancelFactory()
    {
        $txn = TransactionFactory::cancel('123456789');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\CancelTransaction',
            $txn
        );

        $this->assertTrue($txn->canCommit());
    }

    public function testRetrieveFactory()
    {
        $txn = TransactionFactory::retrieve('1');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\RetrieveTransaction',
            $txn
        );
    }

    public function testInitiateFactory()
    {
        $txn = TransactionFactory::initiate(
            self::AMOUNT,
            'https://checkout.example.com/success',
            'https://checkout.example.com/cancel'
        );

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\InitialTransaction',
            $txn
        );
    }

    public function testQueryFactory()
    {
        $txn = TransactionFactory::query(['id' => '1']);

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\QueryTransaction',
            $txn
        );
    }

    public function testThreedSecureAuthenticateFactory()
    {
        $txn = TransactionFactory::ThreedSecureAuthenticate('pares', '1234567');

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\ThreedSecureAuthenticateTransaction',
            $txn
        );
    }

    public function testTransferFactory()
    {
        $txn = TransactionFactory::transfer(
            self::AMOUNT,
            'EUR',
            'customer@example.com'
        );

        $this->assertInstanceOf(
            'Larium\Pay\Transaction\TransferTransaction',
            $txn
        );
    }
}
