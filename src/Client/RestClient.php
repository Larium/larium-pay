<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Pay\Client;

class RestClient implements Client
{
    private $baseUri;

    private $resource;

    public function __construct($baseUri, $resource)
    {
        $this->baseUri = rtrim($baseUri, '/') . '/';
        $this->resource = $resource;
    }

    public function get(array $payload = [])
    {

    }

    public function post(array $payload)
    {

    }

    public function put($id, array $payload)
    {

    }

    public function delete($id)
    {

    }

    public function getUri($id = null)
    {
        $uri = sprintf('%s%s', $this->baseUri, $this->resource);

        if ($id) {
            $uri .= "/$id";
        }

        return $uri;
    }
}
