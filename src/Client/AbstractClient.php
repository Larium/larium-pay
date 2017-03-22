<?php

namespace Larium\Pay\Client;

use Larium\Http\Client;
use Psr\Http\Message\RequestInterface;

abstract class AbstractClient
{
    protected $rawRequest;

    protected $options = [];

    abstract protected function authenticate(RequestInterface $request);

    protected function sendRequest(RequestInterface $request)
    {
        $request = $this->authenticate($request);

        $response = $this->discoverClient()->sendRequest($request);

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $this->rawRequest = $request->getBody()->__toString();

        return $response;
    }

    protected function discoverClient()
    {
        $client = new Client();
        $client->setOptions($this->options);

        return $client;
    }
}
