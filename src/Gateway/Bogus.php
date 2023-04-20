<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Authorize;
use Larium\Pay\Transaction\Cancel;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Initial;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Query;
use Larium\Pay\Transaction\Refund;
use Larium\Pay\Transaction\Retrieve;

class Bogus extends Gateway
{
    protected function purchase(Purchase $transaction): mixed
    {
    }

    protected function authorize(Authorize $transaction): mixed
    {
    }

    protected function capture(Capture $transaction): mixed
    {
    }

    protected function refund(Refund $transaction): mixed
    {
    }

    protected function cancel(Cancel $transaction): mixed
    {
    }

    protected function retrieve(Retrieve $transaction): mixed
    {
    }

    protected function initiate(Initial $transaction): mixed
    {
    }

    protected function query(Query $transaction): mixed
    {
    }

    protected function success(array $response): bool
    {
        return true;
    }

    protected function message(array $response): string
    {
        return '';
    }

    protected function transactionId(array $response): ?string
    {
        return null;
    }

    protected function errorCode(array $response): ?string
    {
        return null;
    }

    protected function responseCode(array $response): ?string
    {
        return null;
    }
}
