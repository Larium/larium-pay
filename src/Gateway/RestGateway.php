<?php

declare(strict_types=1);

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\RestClient;
use Larium\Pay\Response;

abstract class RestGateway extends Gateway
{
    /**
     * Get the base uri for gateway to make the requests.
     *
     * @return string
     */
    abstract protected function getBaseUri(): string;

    /**
     * Authenticate gateway.
     * It is common for rest gateways to authenticate with basic auth, bearer
     * or custom header authentication.
     * So here we can add directly to client the appropriate authentication
     * info.
     *
     * @param RestClient $client
     * @return void
     */
    abstract protected function authenticate(RestClient $client): void;

    /**
     * Returns the RestClient for given resource name.
     *
     * Resource name is usually the uri path for the resource to call.
     * In conjuction with base uri will create the full endpoint uri of
     * gateway to request.
     *
     * @param string $resource
     * @return RestClient
     */
    protected function getRestClient($resource): RestClient
    {
        $client = $this->createClient($this->getBaseUri(), $resource);
        $this->authenticate($client);

        return $client;
    }

    /**
     * Factory method for creating the rest client.
     *
     * @param string $uri The base uri path of gateway.
     * @param string $resource The resource path to request.
     * @return RestClient
     */
    protected function createClient($uri, $resource): RestClient
    {
        return new RestClient($uri, $resource);
    }

    /**
     * Returns the final Response object
     *
     * @param array $response The response returned from RestClient @see
     *                        RestClient::resolveResponse method
     * @return \Larium\Pay\Response|mixed
     */
    protected function getResponse(array $response): mixed
    {
        return $this->createResponse(
            $this->success($response),
            $this->message($response),
            $this->transactionId($response),
            $this->errorCode($response),
            $this->responseCode($response),
            $response['body'],
            $response['raw_response'],
            $response['raw_request']
        );
    }
}
