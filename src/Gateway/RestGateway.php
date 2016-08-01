<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Client\RestClient;

abstract class RestGateway extends Gateway
{
    abstract protected function getBaseUri();

    abstract protected function authenticate(Client $client);

    abstract protected function success(array $responseBody);

    abstract protected function message(array $responseBody);

    abstract protected function transactionId(array $responseBody);

    abstract protected function errorCode(array $responseBody);

    abstract protected function responseCode(array $responseBody);

    protected function getRestClient($resource)
    {
        $client = $this->createRestClient($this->getBaseUri(), $resource);
        $this->authenticate($client);

        return $client;
    }

    protected function createRestClient($uri, $resource)
    {
        return new RestClient($uri, $resource);
    }

    protected function getResponse(array $response)
    {
        $responseBody = json_decode($response['body'], true);

        return $this->createResponse(
            $this->success($responseBody),
            $this->message($responseBody),
            $this->transactionId($responseBody),
            $this->errorCode($responseBody),
            $this->responseCode($responseBody),
            $responseBody
        );
    }
}
