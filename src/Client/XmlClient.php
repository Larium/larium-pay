<?php

namespace Larium\Pay\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;

class XmlClient implements Client
{
    private $uri;

    public function __construct($uri, array $options = [])
    {
        $this->uri = $uri;
    }

    /**
     * Post given xml string to remote gateway.
     *
     * @param string $xml
     * @return array @see self::resolveResponse
     */
    public function post($xml)
    {
        $factory = $this->getMessageFactory();
        $request = $factory->createRequest('POST', $this->uri, [], $xml);

        return $this->resolveResponse($this->sendRequest($request));
    }

    private function getMessageFactory()
    {
        return MessageFactoryDiscovery::find();
    }

    protected function discoverClient()
    {
        return HttpClientDiscovery::find();
    }

    /**
     * Resolve the response from client.
     *
     * @param ResponseInterface $response
     * @return array An array with following values:
     *              'status'      : The Http status of response
     *              'headers'     : An array of response headers
     *              'body'        : The response string.
     *              'raw_response': The raw body response for logging purposes.
     *              'raw_request' : The raw body request for logging purposes.
     */
    protected function resolveResponse(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();

        return array(
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $body,
            'raw_response' => $body,
            'raw_request' => $this->rawRequest,
        );
    }

    private function sendRequest(RequestInterface $request)
    {
        $response = $this->discoverClient()->sendRequest($request);

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $this->rawRequest = $request->getBody()->__toString();

        return $response;
    }
}