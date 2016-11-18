<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

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
     * Return whether the response is success or not.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return bool
     */
    abstract protected function success(array $response);

    /**
     * Returns the message from gateway response.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string
     */
    abstract protected function message(array $response);

    /**
     * Returns th unique transaction id from gateway response.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string
     */
    abstract protected function transactionId(array $response);

    /**
     * Returns error code from gateway if exists.
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string|null
     */
    abstract protected function errorCode(array $response);

    /**
     * Returns response code from card processing, if exists.
     * @link https://arch.developer.visa.com/vpp/documents/xml/Request_and_Response.html Example of response codes
     *
     * $response param contains all the elements of gateway response,
     * parsed as associative array, including http status and headers.
     *
     * @param array $response
     * @return string|null
     */
    abstract protected function responseCode(array $response);

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
        $client = $this->createRestClient($this->getBaseUri(), $resource);
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
    protected function createRestClient($uri, $resource)
    {
        return new RestClient($uri, $resource);
    }

    /**
     * Returns the final Response object
     *
     * @param array $response  The raw response returned from client.
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
            $response['body']
        );
    }
}
