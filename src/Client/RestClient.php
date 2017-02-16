<?php

namespace Larium\Pay\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Discovery\MessageFactoryDiscovery;

class RestClient
{
    private $baseUri;

    private $resource;

    private $username;

    private $pass;

    private $headerAuthentication = [];

    private $headers = [];

    private $options = [];

    private $rawRequest;

    public function __construct(
        $baseUri,
        $resource,
        array $headers = [],
        array $options = []
    ) {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        $this->headers = $headers;
        $this->options = $options;
        $this->resource = $resource;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function get($id = null, $payload = null)
    {
        $factory = $this->getMessageFactory();
        $uri = $this->getUri($id);
        if ($query = $this->normalizePayload($payload)) {
            $uri = $uri . '?' . ltrim($query, '?');
        }
        $request = $factory->createRequest(
            'GET',
            $uri,
            $this->headers
        );

        $request = $this->authenticate($request);

        return $this->resolveResponse($this->sendRequest($request));
    }

    public function post($payload)
    {
        $factory = $this->getMessageFactory();
        $request = $factory->createRequest(
            'POST',
            $this->getUri(),
            $this->headers,
            $this->normalizePayload($payload)
        );

        $request = $this->authenticate($request);

        return $this->resolveResponse($this->sendRequest($request));
    }

    public function put($id, $payload = null)
    {
        $factory = $this->getMessageFactory();
        $request = $factory->createRequest(
            'PUT',
            $this->getUri($id),
            $this->headers,
            $this->normalizePayload($payload)
        );

        $request = $this->authenticate($request);

        return $this->resolveResponse($this->sendRequest($request));
    }

    public function delete($id)
    {
    }

    public function getUri($id = null)
    {
        $uri = sprintf('%s%s', $this->baseUri, $this->resource);

        if ($id) {
            $uri = sprintf($uri, $id);
        }

        return $uri;
    }

    public function setBasicAuthentication($username, $password)
    {
        $this->username = $username;
        $this->pass = $password;
    }

    public function setHeaderAuthentication($name, $value)
    {
        $this->headerAuthentication = ['name' => $name, 'value' => $value];
    }

    private function authenticate(RequestInterface $request)
    {
        if ($this->username || $this->pass) {
            $authentication = new BasicAuth($this->username, $this->pass);

            return $authentication->authenticate($request);
        }

        if (!empty($this->headerAuthentication)) {
            $request = $request->withHeader(
                $this->headerAuthentication['name'],
                $this->headerAuthentication['value']
            );

            return $request;
        }

        return $request;
    }

    private function normalizePayload($payload)
    {
        if (is_array($payload)) {
            return http_build_query($payload);
        }

        return $payload;
    }

    private function getMessageFactory()
    {
        return MessageFactoryDiscovery::find();
    }

    protected function discoverClient()
    {
        return HttpClientDiscovery::find();
    }

    private function sendRequest(RequestInterface $request)
    {
        $request = $this->authenticate($request);

        $response = $this->discoverClient()->sendRequest($request);

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $this->rawRequest = $request->getBody()->__toString();

        return $response;
    }

    /**
     * Resolve the response from client.
     *
     * @param ResponseInterface $response
     * @return array An array with following values:
     *              'status': The Http status of response
     *              'headers': An array of response headers
     *              'body': The json decoded body response. (Since we are in
     *              RestClient)
     *              'raw_response': The raw body response for logging purposes.
     *              'raw_request': The raw body request for logging purposes.
     */
    protected function resolveResponse(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        $responseBody = json_decode($body, true) ?: [];

        return array(
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $responseBody,
            'raw_response' => $body,
            'raw_request' => $this->rawRequest,
        );
    }
}
