<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Void;
use Larium\Pay\Client\RestClient;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Authorize;
use Larium\Pay\Transaction\Transaction;
use Larium\Pay\Exception\NotImplementedException;

abstract class Gateway
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function execute(Transaction $transaction)
    {
        $transaction->commit();

        switch (true) {
            case $transaction instanceof Purchase:
                return $this->purchase($transaction);
            case $transaction instanceof Authorize:
                return $this->authorize($transaction);
            case $transaction instanceof Capture:
                return $this->capture($transaction);
            case $transaction instanceof Refund:
                return $this->refund($transaction);
            case $transaction instanceof Void:
                return $this->void($transaction);
            default:
                throw new \RuntimeException(
                    sprintf('Invalid transaction type `%s`', get_class($transaction))
                );
        }
    }

    protected function purchase(Purchase $transaction)
    {
        throw new NotImplementedException(
            sprintf('Transaction `%s` in not implemented by `%s`', __FUNCTION__, get_class($this))
        );
    }

    protected function authorize(Authorize $transaction)
    {
        throw new NotImplementedException(
            sprintf('Transaction `%s` in not implemented by `%s`', __FUNCTION__, get_class($this))
        );
    }

    protected function capture(Capture $transaction)
    {
        throw new NotImplementedException(
            sprintf('Transaction `%s` in not implemented by `%s`', __FUNCTION__, get_class($this))
        );
    }

    protected function refund(Refund $transaction)
    {
        throw new NotImplementedException(
            sprintf('Transaction `%s` in not implemented by `%s`', __FUNCTION__, get_class($this))
        );
    }

    protected function void(Void $transaction)
    {
        throw new NotImplementedException(
            sprintf('Transaction `%s` in not implemented by `%s`', __FUNCTION__, get_class($this))
        );
    }

    protected function createRestClient($uri, $resource)
    {
        return new RestClient($uri, $resource);
    }
}
