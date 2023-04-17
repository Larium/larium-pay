<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\Pay\Transaction\Authorize;
use Larium\Pay\Transaction\Capture;
use Larium\Pay\Transaction\Purchase;

class TestGateway extends Gateway
{
    public function purchase(Purchase $transaction): mixed
    {
    }

    public function authorize(Authorize $transaction): mixed
    {
    }

    public function capture(Capture $transaction): mixed
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
