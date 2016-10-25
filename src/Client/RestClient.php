<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Discovery\MessageFactoryDiscovery;

class RestClient implements Client
{
    private $baseUri;

    private $resource;

    private $username;

    private $pass;

    private $headerAuthentication = [];

    private $headers = [];

    private $options = [];

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
        $this->headers[] = "{$name}: {$value}";
    }

    public function get($id = null, $payload = null)
    {
        $factory = $this->getMessageFactory();
        $request = $factory->createRequest(
            'GET',
            $this->getUri(),
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
        return  MessageFactoryDiscovery::find();
    }

    private function sendRequest(RequestInterface $request)
    {
        $request = $this->authenticate($request);
        $client = HttpClientDiscovery::find();

        return $client->sendRequest($request);
    }

    private function resolveResponse(ResponseInterface $response)
    {
        return array(
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody()->__toString(),
        );
    }
}
