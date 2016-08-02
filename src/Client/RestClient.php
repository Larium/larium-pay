<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

class RestClient implements Client
{
    private $baseUri;

    private $resource;

    private $username;

    private $pass;

    private $headers = [];

    private $options = [];

    public function __construct(
        $baseUri,
        $resource,
        array $headers = [],
        array $options = []
    ) {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        $this->resource = $resource;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function addHeader($name, $value)
    {
        $this->headers[] = "{$name}: {$value}";
    }

    public function get($id = null, $payload = null)
    {
        $conn = new Curl(
            $this->getUri($id),
            Curl::METHOD_GET,
            null,
            $this->headers,
            $this->options
        );
        $this->authenticate($conn);

        return $conn->execute();
    }

    public function post($payload)
    {
        $conn = new Curl(
            $this->getUri(),
            Curl::METHOD_POST,
            $payload,
            $this->headers,
            $this->options
        );
        $this->authenticate($conn);

        return $conn->execute();
    }

    public function put($id, $payload = null)
    {
        $conn = new Curl(
            $this->getUri($id),
            Curl::METHOD_PUT,
            $payload,
            $this->headers,
            $this->options
        );
        $this->authenticate($conn);

        return $conn->execute();
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

    private function authenticate(Curl $conn)
    {
        if ($this->username || $this->pass) {
            $conn->setBasicAuthentication($this->username, $this->pass);
        }
    }
}
