<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Authorize;

class TestGateway extends Gateway
{
    public function purchase(Purchase $transaction)
    {
    }

    public function authorize(Authorize $transaction)
    {
    }

    public function capture(Capture $transaction)
    {
    }

    protected function success(array $response)
    {
    }

    protected function message(array $response)
    {
    }

    protected function transactionId(array $response)
    {
    }

    protected function errorCode(array $response)
    {
    }

    protected function responseCode(array $response)
    {
    }
}
