<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Void;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Initial;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Retrieve;
use Larium\Pay\Transaction\Authorize;

class Bogus extends Gateway
{
    protected function purchase(Purchase $transaction)
    {
    }

    protected function authorize(Authorize $transaction)
    {
    }

    protected function capture(Capture $transaction)
    {
    }

    protected function refund(Refund $transaction)
    {
    }

    protected function void(Void $transaction)
    {
    }

    protected function retrieve(Retrieve $transaction)
    {
    }

    protected function initiate(Initial $transaction)
    {
    }
}
