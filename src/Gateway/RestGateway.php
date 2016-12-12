<?php

namespace Larium\Pay\Gateway;

use Larium\Pay\Client\Client;
use Larium\Pay\Client\RestClient;

abstract class RestGateway extends Gateway
{
    /**
     * Get the base uri for gateway to make the requests.
     *
     * @return string
     */
    abstract protected function getBaseUri();

    /**
     * Authenticate gateway.
     * It is common for rest gateways to authenticate with basic auth, bearer
     * or custom header authentication.
     * So here we can add directly to client the appropriate authentication
     * info.
     *
     * @param Larium\Pay\Client\Client $client
     * @return void
     */
    abstract protected function authenticate(Client $client);

    /**
     * Returns the RestClient for given resource name.
     *
     * Resource name is usually the uri path for the resource to call.
     * In conjuction with base uri will create the full endpoint uri of
     * gateway to request.
     *
     * @param string $resource
     * @return Larium\Pay\Client\RestClient.
     */
    protected function getRestClient($resource)
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
     * @return Larium\Pay\Client\RestClient.
     */
    protected function createClient($uri, $resource)
    {
        return new RestClient($uri, $resource);
    }

    /**
     * Returns the final Response object
     *
     * @param array $response The response returned from RestClient @see
     *                        RestClient::resolveResponse method
     * @return Larium\Pay\Response
     */
    protected function getResponse(array $response)
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
