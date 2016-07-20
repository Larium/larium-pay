<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

class RestClient implements Client
{
    private $baseUri;

    private $resource;

    private $username;

    private $pass;

    public function __construct($baseUri, $resource)
    {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        $this->resource = $resource;
    }

    public function get($id = null, array $payload = [])
    {
        $conn = new Curl(
            $this->getUri($id),
            Curl::METHOD_GET
        );
        $this->authenticate($conn);

        return $conn->execute();
    }

    public function post(array $payload)
    {
        $conn = new Curl(
            $this->getUri(),
            Curl::METHOD_POST,
            $payload
        );
        $this->authenticate($conn);

        return $conn->execute();
    }

    public function put($id, array $payload = [])
    {
        $conn = new Curl(
            $this->getUri($id),
            Curl::METHOD_PUT,
            $payload
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
