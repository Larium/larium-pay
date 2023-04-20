<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\RestClient;
use Larium\Pay\Response;
use Larium\Pay\Transaction\Purchase;
use Larium\Pay\Transaction\Query;

class MyRestGateway extends RestGateway
{
    public const URI = 'https://api.example.com/v1';

    public const QUERY = 'payments';

    protected function getBaseUri(): string
    {
        return self::URI;
    }

    public function purchase(Purchase $transaction): mixed
    {
        return $this->getResponse([
            'status' => 200,
            'body' => ['status' => 'success'],
            'raw_response' => '{"status":"success"}',
            'raw_request' => ''
        ]);
    }

    protected function authenticate(RestClient $client): void
    {
        return;
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
        return '';
    }

    protected function errorCode(array $response): ?string
    {
        return null;
    }

    protected function responseCode(array $response): ?string
    {
        return null;
    }

    protected function query(Query $transaction): mixed
    {
        $payload = $transaction->getCriteria();

        $response = $this->getRestClient(self::QUERY)
            ->get(null, $payload);

        return $this->getResponse($response);
    }
}
