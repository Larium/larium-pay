<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\CreditCard\CreditCard;
use Larium\Pay\GatewayException;
use Larium\Pay\TestCase;
use Larium\Pay\Transaction\AuthorizeTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
use Larium\Pay\Transaction\QueryTransaction;
use Larium\Pay\Transaction\RefundTransaction;
use PHPUnit\Framework\MockObject\MockObject;

class GatewayTest extends TestCase
{
    /**
     * @dataProvider getExecuteMethods
     */
    public function testExecuteMethods($method, $transactionGetter): void
    {
        $transaction = $this->$transactionGetter();

        /** @var Bogus|MockObject $bogus */
        $bogus = $this->getMockBuilder('Larium\Pay\Gateway\Bogus')
                         ->onlyMethods([$method])
                         ->getMock();

        $bogus->expects($this->once())
            ->method($method)
            ->with($this->equalTo($transaction));

        $bogus->execute($transaction);
    }

    /**
     * @expectedException Larium\Pay\GatewayException
     * @expectedExceptionMessage Gateway `Larium\Pay\Gateway\TestGateway` does not support `Larium\Pay\Transaction\RefundTransaction
     */
    public function testNotImplementMethods(): void
    {
        $this->expectException(GatewayException::class);
        $this->expectExceptionMessage('Gateway `Larium\Pay\Gateway\TestGateway` does not support `Larium\Pay\Transaction\RefundTransaction');
        $bogus = new TestGateway();
        $transaction = $this->getRefundTransaction();

        $bogus->execute($transaction);
    }

    public function testQueryTransaction(): void
    {
        $g = $this->mockRestGatewayClient(
            'Larium\Pay\Gateway\MyRestGateway',
            [],
            [
                'status' => 200,
                'headers' => [],
                'body' => [],
                'raw_response' => null,
                'raw_request' => null,
            ]
        );

        $txn = new QueryTransaction([
            'order' => 'created_at',
            'limit' => 10,
        ]);

        $g->expects($this->once())
            ->method('query');

        $g->execute($txn);
    }

    public function testCustomResponse(): void
    {
        $gateway = new MyRestGateway(['username'=>'a', 'password'=>'b']);

        $txn = new PurchaseTransaction(1000, new CreditCard(['token'=>'1']));

        $response = $gateway->execute($txn, function ($response, $payload) {
            return ['message' => $payload['status']];
        });

        $this->assertArrayHasKey('message', $response);
    }

    public static function getExecuteMethods(): array
    {
        return [
            [
                'purchase',
                'getPurchaseTransaction',
            ],
            [
                'authorize',
                'getAuthorizeTransaction',
            ],
            [
                'capture',
                'getCaptureTransaction',
            ],
            [
                'refund',
                'getRefundTransaction',
            ],
        ];
    }

    private function getPurchaseTransaction(): PurchaseTransaction
    {
        return new PurchaseTransaction(
            1000,
            $this->getCard()
        );
    }

    private function getAuthorizeTransaction(): AuthorizeTransaction
    {
        return new AuthorizeTransaction(
            1000,
            $this->getCard()
        );
    }

    private function getCaptureTransaction(): CaptureTransaction
    {
        return new CaptureTransaction(
            1000,
            'txn-reference-12345'
        );
    }

    public function getRefundTransaction(): RefundTransaction
    {
        return new RefundTransaction(
            1000,
            'txn-reference-12345'
        );
    }
}
