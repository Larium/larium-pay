<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\RestClient;
use Larium\Pay\Transaction\Query;

class MyRestGateway extends RestGateway
{
    const URI = 'https://api.example.com/v1';

    const QUERY = 'payments';

    protected function getBaseUri()
    {
        return self::URI;
    }

    protected function authenticate(RestClient $client)
    {
        return true;
    }

    protected function success(array $response)
    {
        return true;
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

    protected function query(Query $transaction)
    {
        $payload = $transaction->getCriteria();

        $response = $this->getRestClient(self::QUERY)
            ->get(null, $payload);

        return $this->getResponse($response);
    }
}
