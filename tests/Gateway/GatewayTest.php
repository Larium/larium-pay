<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Gateway\TestGateway;
use Larium\Pay\Transaction\VoidTransaction;
use Larium\Pay\Transaction\RefundTransaction;
use Larium\Pay\Transaction\CaptureTransaction;
use Larium\Pay\Transaction\PurchaseTransaction;
use Larium\Pay\Transaction\AuthorizeTransaction;

class GatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getExecuteMethods
     */
    public function testExecuteMethods($method, $transactionGetter)
    {
        $transaction = $this->$transactionGetter();

        $bogus = $this->getMockBuilder('Larium\Pay\Gateway\Bogus')
                         ->setMethods(array($method))
                         ->getMock();

        $bogus->expects($this->once())
            ->method($method)
            ->with($this->equalTo($transaction));

        $bogus->execute($transaction);
    }

    /**
     * @expectedException Larium\Pay\Exception\NotImplementedException
     */
    public function testNotImplementMethods()
    {
        $bogus = new TestGateway();
        $transaction = $this->getRefundTransaction();

        $bogus->execute($transaction);
    }

    public function getExecuteMethods()
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

    private function getPurchaseTransaction()
    {
        return new PurchaseTransaction(
            1000,
            $this->getCard()
        );
    }

    private function getAuthorizeTransaction()
    {
        return new AuthorizeTransaction(
            1000,
            $this->getCard()
        );
    }

    private function getCaptureTransaction()
    {
        return new CaptureTransaction(
            1000,
            'txn-reference-12345'
        );
    }

    public function getRefundTransaction()
    {
        return new RefundTransaction(
            1000,
            'txn-reference-12345'
        );
    }
}
