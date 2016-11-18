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
}
